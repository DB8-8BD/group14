<?php 
// Filename: user-login.php
// Date: Sept. 21, 2017
// Modified: Nov. 23, 2017
// Author: Ryan Beckett, Mat Kostrezwa
// Description: Handles forms authentication via the PHP framework, sets up user sessions
//
date_default_timezone_set('America/Toronto');
$title = "Login";
$banner = "Please Login";
$date = "Nov. 25, 2017";
$filename = "user-login.php";
$description = "This page handles login requests for the on-line dating website using forms authentication.";
require_once ("header.php"); 
$bLoginSuccessful = false;
$currentHttpTime = gmdate("M d Y H:i:s");
header("Cache-Control: no-cache, must-revalidate"); // taken from php manual - prevents caching
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");// taken from php manual - prevents caching
header("Last-Modified: ".$currentHttpTime);// append last-modified header so browsers always think they have the latest version 
$LoginStatus = "Not Logged in";
$logout_message = "";
$username = "";
$user_type = "";


// if user has a session, grab it from the indempotent request otherwise
// begin http form fields examination, where all errors shall return http 401 (unauthorized)
if ("POST" != $_SERVER['REQUEST_METHOD'])
{
	if (isset($_SESSION["password_request"]))
	{
		echo $_SESSION["password_request"];
		unset($_SESSION["password_request"]);
	}
	
	// check if the user has just logged out of a session
	if (isset($_SESSION["logout_message"]))
	{
		// if the user just logged out, display the logout message
		$logout_message = $_SESSION["logout_message"];
	}
	if (isset($_SESSION["user_id"]))
	{
		$username = $_SESSION["user_id"];
		$LoginStatus = "Logged in";
	}
	else if (isset($_COOKIE["user_id"]))
	{
		$username = $_COOKIE["user_id"];
	}
	// grab the user_type session variable
	if (isset($_SESSION['user_type']))
	{
		$user_type = $_SESSION['user_type'];
	}
	// test for disabled in case an admin has just disabled their account
	if (0 == strcmp($user_type, DISABLED))
	{
		$LoginStatus = "Disabled";
		unset($_SESSION);
		session_destroy();
		header("refresh: 3; url=aup.php");
	}
}
else
{
	// trim the username http form field
	$username = trim($_POST['username']);
	// if the form field is not empty, continue
	if ("" != $username)
	{
		// trim the username http form field
		$pwd = trim($_POST['pwd']);
		// if the form field is not empty, continue
		if ("" != $pwd)
		{
			// hash the password provided by the user
			$md5_hash_pwd = md5(SALT . $pwd);
			// prepared statement for accessing password
			if (FALSE != pg_execute(db_connect(), "pstGetUser", array($username)))
			{
				// Failure means invalid userid
				$bResult = pg_execute(db_connect(), "pstGetUser", array($username));
				if (FALSE != $bResult)
				{
					// obtain the hashed password from the db
					$uResult = pg_fetch_assoc($bResult);
					$db_hash_pwd = $uResult['password'];
					// compare the hashes
					if (0 == strcmp($db_hash_pwd, $md5_hash_pwd))
					{
						// it is time to update the time of their last access
						if (FALSE != qry_update_user_last_access())
						{
							// there is no rowset associated with pstLastAccess
							$laResult = pg_execute(db_connect(), "pstLastAccess", array($username));
							if (FALSE != $laResult)
							{
								$bLoginSuccessful = true;// set logged in flag
								$LoginStatus = "Logged in";
								$_SESSION['user_id'] = $username;
								$_SESSION['last_access'] = $uResult['last_access'];
								$_SESSION['first_name'] = $uResult['first_name'];
								$_SESSION['last_name'] = $uResult['last_name'];
								$_SESSION['birth_date'] = $uResult['birth_date'];
								$_SESSION['enrol_date'] = $uResult['enrol_date'];
								$_SESSION['user_type'] = $uResult['user_type'];
								// session cookie - change this value to modify the login timeout 
								// ie. if you want the user to be perpetually logged in for 30 days, uncomment the next line (useful for debugging purposes)
								//session_set_cookie_params(SESSION_COOKIE_TIME_LIMIT);
								
								// clear the logged out flag
								unset($_SESSION["logout_message"]);
								
								// user_id cookie
								setcookie("user_id", $username, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
								// if customer, then populate session variables with their profile
								if (0 == strcmp(CLIENT, $uResult['user_type']) or 0 == strcmp(ADMIN, $uResult['user_type']))
								{
									if (FALSE != pg_execute(db_connect(), "pstGetUserProfile", array($username)))
									{
										$xResult = pg_execute(db_connect(), "pstGetUserProfile", array($username));
										if (FALSE != $xResult)
										{
											$pResult = pg_fetch_assoc($xResult);
											//$_SESSION['headline'] = $pResult['headline'];
											//$_SESSION['self_description'] = $pResult['self_description'];
											//$_SESSION['match_description'] = $pResult['match_description'];
											$_SESSION['city'] = $pResult['city'];
											$_SESSION['state'] = $pResult['state'];
											$_SESSION['gender'] = $pResult['gender'];
											$_SESSION['gender_sought'] = $pResult['gender_sought'];//renaming of session variable
											$_SESSION['images'] = $pResult['images'];
											$_SESSION['tax_bracket'] = $pResult['tax_bracket'];
											$_SESSION['education'] = $pResult['education'];
											$_SESSION['occupation'] = $pResult['occupation'];
											$_SESSION['housing_status'] = $pResult['housing_status'];
											$_SESSION['vehicle_type'] = $pResult['vehicle_type'];
											$_SESSION['hobbies'] = $pResult['hobbies'];
											$_SESSION['sports'] = $pResult['sports'];
											$_SESSION['religion'] = $pResult['religion'];
											
										}
									}
									if ($uResult['user_type'] == ADMIN)
									{
										header("Location: admin.php"); // redirect to admin page for admin users
									}
									else
									{
										header("refresh: 3; url=user-dashboard.php"); // other users redirect to dashboard
									}
									if ($uResult['user_type'] == DISABLED)
									{
										$LoginStatus = "Disabled";
										unset($_SESSION);
										session_destroy();
										header("refresh: 3; url=aup.php");
									}
								}
								
								else
								{
									header("refresh: 3; url=profile-create.php");
									
								}
							}
							else
							{
								$LoginStatus = "Unspecified error";
								http_response_code(500); 
							}
						}
						else
						{
							$LoginStatus = "Unspecified error";
							http_response_code(500); 
						}
					}
					else
					{
						$LoginStatus = "Invalid Password";
						http_response_code(401); 
					}
				}
				else
				{
					$LoginStatus = "Invalid Login ID";
					http_response_code(401);
				}
			}
			else
			{
				$LoginStatus = "Invalid Login ID";
				http_response_code(401); 
			}
		}
		else
			http_response_code(401);
	}
	
}
?> 
<?php if ($bLoginSuccessful): ?><b>Welcome MadLove dating user, this page will automatically redirect you within a few seconds.</b><br /><?php else: ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="user">
        <table cellpadding="0" cellspacing="0" style="border:solid 2px darkgrey">
            <tr style="border:solid 1px moccasin">
                <td align="right">User Name:&nbsp;</td>
                <td><input name="username" type="text" value="<?php 
						if (!$bLoginSuccessful)
						{
							if ($LoginStatus != "Invalid Login ID")
							{
								echo $username;
							}
						}

				?>"/></td><td></td>
            </tr>
            <tr style="border:solid 1px violet">
                <td align="right">Password:&nbsp;</td>
                <td><input name="pwd" type="password" /></td><td></td>
            </tr>
            <tr>
                <td align="right" >Status:&nbsp;<br /></td>
                <td colspan="2" onmouseover="this.style.border='solid 1px yellow'"><?php echo $LoginStatus; ?>&nbsp;<input name="login" type="submit" value="Login" /></td>
            </tr>
        </table>
    </form>
    <?php endif; ?>
<?php require_once ("footer.php"); ?>