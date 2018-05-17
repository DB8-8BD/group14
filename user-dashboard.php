<?php 

//Filename: user-dashboard.php
//Author: Group 14
//Date: Sept 28th, 2017
//Modified: Nov. 25, 2017
//Description: Will be the site the user sees when they log in

$title = "Dashboard";
$banner = "Welcome!";
$date = "Sept 15, 2017";
$filename = "user-dashboard.php";
$description;

$first_name = "";
$last_name = "";
$last_access = "";
$vehicle = "";

require_once ("header.php"); 

if (isset($_SESSION['password_change_successful']))
{
	echo $_SESSION['password_change_successful'];
	unset($_SESSION['password_change_successful']);
}

// obtain information from the session
if (isset($_SESSION['first_name']))
	$first_name = $_SESSION['first_name'];
if (isset($_SESSION['last_name']))
	$last_name = $_SESSION['last_name'];
else
	$first_name = $_SESSION['user_id'];
if (isset($_SESSION['last_access']))
	$last_access = $_SESSION['last_access'];
// show a reasonable last access message, if they have never access the site
if (0 == strcmp(UNIX_EPOCH_DATE, $last_access))
	$last_access = "never";
?>
<br/>
	<p>
		Welcome&nbsp;<?php echo $first_name; ?>&nbsp;<?php echo $last_name; ?>&nbsp;|&nbsp;Last &nbsp;Accessed:&nbsp;<?php echo $last_access; ?>
	</p>
<?php require_once ("footer.php"); ?>