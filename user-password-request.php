<?php 

//Filename: user-password-request.php
//Author: Group 14
//Date: Nov 24th, 2017
//Description: Password Request

$title = "Password Reset Request";
$banner = "Password Reset Request";
$date = "Nov 24th, 2017";
$filename = "user-password-request.php";
$description;
date_default_timezone_set('America/Toronto');
require_once ("header.php"); 
//pg_prepare(db_connect(), "pstUpdatePassword", "UPDATE users SET password = $1 WHERE user_id = $2 AND user_type <> '" . DISABLED . "'");

$error = "";
if (isset($_SESSION['user_id'])) //ensure user is not logged in
{
	header("Location: user-dashboard.php"); // redirect user to dashboard page if there is a user logged in
}

if ($_SERVER["REQUEST_METHOD"] == "POST") //make sure the user has submitted their entry (POST)
{
	$userid = trim($_POST["user_id"]);
	$email = trim($_POST["email_address"]);
	
	//check for empty userid
	if ($userid == "")
	{
		$error .= "<br/>Error: Your User ID must not be empty.";
	}
	//check for empty email
	if ($email == "")
	{
		$error .= "<br/>Error: Your Email must not be empty.";
	}
	//check for valid email
	else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		$error .= "<br/>Error: Your Email must be a valid email address.";
	}
	
	//if there are no errors continue
	if ($error == "")
	{
		$sqlResult = pg_execute(db_connect(), "pstGetUserAndEmail", array($userid)); //get the user's id and email from database
		
		if ($sqlResult != FALSE)
		{
			//fetch fields from the query
			$fetchResult = pg_fetch_assoc($sqlResult);
			$rows = pg_num_rows($sqlResult);
			
			//check if the email and userid match
			if ($email == $fetchResult["email_address"] && $rows > 0) // I don't know why "last name" is returning their email and "email" returns first name
			{			
				// used this stackoverflow thread for ideas on random string generation: https://stackoverflow.com/questions/4356289/php-random-string-generator
				$possibleCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($possibleCharacters);
				$randomPassword = '';
				$passwordLength = 8; // ensure the password is 8 characters
				
				//pick a random character for to fill up the random string till it reaches desired length
				for ($counter = 0; $counter < $passwordLength; $counter++)
				{
					$randomPassword .= $possibleCharacters[mt_rand(0, $charactersLength - 1)];
				}
				
				//get user details for personalized message
				$userQuery = pg_execute(db_connect(), "pstGetUser", array($userid));
				$userResults = pg_fetch_assoc($userQuery);
				
				//SHOW EMAIL FOR DEMO PURPOSES
				//echo "<br/><br/><img src = 'img/webd3201logo.png' height = '160' width='250' alt='MadLove Logo'/><br/>" . date('Y-m-d h:i:s A') . "<br/><br/>Hello " . $userResults['email_address'] . ",<br/>Your password for MadLove dating has been set to <b>$randomPassword</b>.<br/><br/>";
				
				//set up and send the password request email
				$emailSubject = "MadLove Password Reset";
				$emailBody = "<img src = 'img/webd3201logo.png' height = '160' width='250' alt='MadLove Logo'/><br/>" . date('Y-m-d h:i:s A') . "<br/><br/>Hello " . $userResults['email_address'] . ",<br/>Your password for MadLove dating has been set to <b>$randomPassword</b>.";
				$headers = "From: MadLoveBot@MadLove.com";
				//if (mail($email, $emailSubject, $emailBody, $headers)) //check if email went through
				//{
					
					//salt and hash password, then place into the database
					$md5_hash_password = md5(SALT . $randomPassword);
					$result = pg_execute(db_connect(), "pstUpdatePassword", array($md5_hash_password, $userid));
					
					//check if the pg_execute worked properly
					if (FALSE == $result)
					{
						$error .= "<br/>Error: There was a problem updating your password.";
					}
					else
					{
						//set session message and redirect to login page
						$_SESSION["password_request"] = "<br/>An email has been sent to your email address.<br/>Your temporary password is <b>$randomPassword</b><br/><br/>";
						header("Location: user-login.php");
					}
				//}
			}
			else
			{
				$error .= "<br/>Error: Invalid userid or email address was entered.";
			}
		}
		else
		{
			$error .= "<br/>Error: Unable to retrieve information from database.";
		}
	}
}

echo "<h2>$error<br/></h2>"; ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="user">
<table border="1" class = "center" cellpadding="10" >
	<tr>
		<td><strong>User ID:</strong></td>
		<td><input type = "text" name = "user_id"/></td>
	</tr>
	<tr>
		<td><strong>Email Address:</strong></td>
		<td><input type = "text" name = "email_address"/></td>
	</tr>
	<tr>
		<td>Press submit to reset your password:&nbsp;</td>
		<td align="right"><input name="login" type="submit" value="Submit" /></td>
	</tr>
</table>
</form>
<?php

require_once "footer.php";
?>