<?php 

//Filename: user-password-change.php
//Author: Group 14
//Date: Nov 24th, 2017
//Description: Password Request

$title = "Password Change Request";
$banner = "Password Change Request";
$date = "Nov 24th, 2017";
$filename = "user-password-change.php";
$description = "Allow user to request a password change.";

require_once ("header.php");

$error = "";
$user_id = "";
if (isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
}
if (isset($_SESSION['user_type']))
{
	$user_type = $_SESSION['user_type'];
	if ($user_type == DISABLED)
	{
		header("Location: aup.php");
	}
}
else
{
	header("Location: user-login.php"); // redirect user to login page
}

// only let http post reset passwords
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// set up the hash with the current password entered by the user and search the database for the REAL current password
	$md5_hash_pwd = md5(SALT . (trim($_POST["current_password"])));
	$bResult = pg_execute(db_connect(), "pstGetUser", array($user_id));
	if (FALSE != $bResult)
	{
		// obtain the hashed password from the db
		$uResult = pg_fetch_assoc($bResult);
		$db_hash_pwd = $uResult['password'];
		// compare the hashes
		if (0 == strcmp($db_hash_pwd, $md5_hash_pwd))
		{
			
			if (isset($_POST["new_password"]) === false ) {
					$error .= "You must enter the new password.<br/>";
				} else if (trim($_POST["new_password"]) == "") {
					$error .= "You must enter the new password.<br/>";
				} else if (strlen(trim($_POST["new_password"])) > MAXIMUM_PASSWORD_LENGTH ) {
					$error .= "Your new password is " . strlen(trim($_POST["new_password"])) . " characters long. The maximum allowed 
					characters is ". MAXIMUM_PASSWORD_LENGTH . "<br/>";
				} else if (strlen(trim($_POST["new_password"])) < MINIMUM_PASSWORD_LENGTH ) {
					$error .= "Your new password is " . strlen(trim($_POST["new_password"])) . " characters long. The minimum allowed 
					characters is ". MINIMUM_PASSWORD_LENGTH . "<br/>";
				}
				else
				{
					if (isset($_POST["confirm"]) === false ) 
					{
						$error .= "You must confirm password.<br/>";
					} else if (trim($_POST["confirm"]) == "") {
						$error .= "Empty confirm password<br/>";
					} else if(strcmp(trim($_POST["new_password"]), trim($_POST["confirm"])) !== 0) 
					{
						$error .= "The new passwords do not match<br/>";
					} else {
							$password = trim($_POST["new_password"]);
							$md5_hash_password = md5(SALT . $password);
							pg_prepare(db_connect(), "pstUpdatePassword", "UPDATE users SET password = $1 WHERE user_id = $2 AND user_type <> '" . DISABLED . "'");
							$result = pg_execute(db_connect(), "pstUpdatePassword", array($md5_hash_password, $user_id));
							if (FALSE == $result)
							{
								// their userid is not accurate --> MEANS THEY ARE TRYING TO RESET SOMEONE ELSE'S PASSWORD
								$error = "Unable to change your password";
								http_response_code(400); 
							}
							else
							{
								$_SESSION['password_change_successful'] = "<h3><br/>Password change was successful!<br/></h3>";
								header("Location: user-dashboard.php"); // redirect user to dashboard
							}
						   }
				}
		}
		else
		{
			$error .= "You have incorrectly entered your current password<br/>";
		}
	}
	else
	{
		$error .= "An error occured when entering your current password<br/>";
	}
	
	echo "<h2>$error </h2>"; 
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="user">
<table border="1" class = "center" cellpadding="10" >
	<tr>
		<td><strong>User ID:</strong></td><td style="text-align:right;"><?php echo $user_id; ?></td>
	</tr>
	<tr>
		<td><strong>Current Password:</strong></td><td><input type="password" name="current_password" /></td>
	</tr>
	<tr>
		<td><strong>New Password:</strong></td><td><input type="password" name="new_password" /></td>
	</tr>
	<tr>
		<td><strong>Confirm New Password:</strong></td><td><input type="password" name="confirm" /></td>
	</tr>
	<tr>
		<td>Press submit to change your password:&nbsp;</td>
		<td align="right" onmouseover="this.style.border='solid 1px yellow'"><input name="login" type="submit" value="Submit" /></td>
	</tr>
</table>
</form>
<?php

require_once "footer.php";
?>