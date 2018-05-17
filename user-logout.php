<?PHP
/*
user-logout.php
Group 14
Nov 10, 2017
WEBD3201
This PHP file will be used to log a user out of their current session.
*/
date_default_timezone_set('America/Toronto');

$title = "Logout";
$description = "This page will be used to log the user out of their current session
				and redirect them to the login page. ";
$date = "November 10, 2017";
$filename = "user-logout.php";
$banner = "Logout";

require_once ("header.php"); 

// check if there is a session before unsetting and destroying it
if(isset($_SESSION))
{
	unset($_SESSION);
	session_destroy();
	
	session_start();
	// create logout message to be displayed on the login page after redirection
	$_SESSION["logout_message"] = '<br/>You have been successfully logged out.';
}

header("Location: user-login.php"); // redirect user to login page

// add redirect message just in case anything goes wrong and allow manual redirection for the user
?>
<br/>
<b>You will be automatically redirect to the login page within a few seconds.</b><br/>
<b>If you are not automatically redirected, please press <a href="user-login.php">here</a>.</b>

<?php require_once ("footer.php"); ?>