<?php
//Filename: header.php
//Author: Group 14
//Date: Sept 28th, 2017
//Description: Will be the header for all the pages on the site

require_once 'includes/constants.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if(session_id() == "")
{
	session_start();
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/webd3201.css" /> 

	<title><?php echo $title; ?></title>
</head>
<body>
<?php
// start a session, ensuring one doesn't exist already



//determine if they are logged in by grabbing their user_id
$user_id = "";
$user_type = "";
$bLoggedIn = false;
if (isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
	//qry_user_not_disabled();
	$result = pg_execute(db_connect(), "pstGetUserNotDisabled", array($user_id));
	if (FALSE != $result)
	{
		$bLoggedIn = true;
		if (isset($_SESSION['user_type']))
		{
			$user_type = $_SESSION['user_type'];
		}
	}
}
?>

<div id="container">
	<div id="header">	
			<img src = "img/webd3201logo.png" height = "160" width="250" 
			alt="MadLove Logo"/>
		<h1>
			&nbsp;&nbsp;&nbsp; MadLove Dating Website
		</h1>
	</div>
	
	<div id="sites">
		<ul>
			<li><a href="./about.php"><b>About</b></a></li>
			<li><a href="./index.php"><b>Home</b></a></li>
			
		</ul>
	</div>
	<div id="content-container">
		<div id="navigation">
			<h2>
				<b>Navigation</b>
			</h2>
			<ul>
				<?php if ($bLoggedIn): ?>
					<?php if ($user_type === CLIENT): ?>
					<li><a href="./index.php">Index</a></li>
					<li><a href="./user-logout.php">Logout</a></li>
					<li><a href="./user-dashboard.php">Dashboard</a></li>
					<li><a href="./user-update.php">Update User</a></li>
					<li><a href="./profile-create.php">Update Profile</a></li>
					<li><a href="./user-password-change.php">Change Password</a></li>
					
					<li><a href="./profile-select-city.php">Profile Select City</a></li>
					<li><a href="./profile-search.php">Profile Search</a></li>
					<li><a href="./profile-images.php">Image Upload</a></li>
					<li><a href="./interests.php">Interests</a></li>
					
					<?php if ((isset($_SESSION['search_results'])) && (count($_SESSION['search_results'] > 0)) && ("POST" == $_SERVER['REQUEST_METHOD'])) : ?>
						<li><a href="./profile-search-result.php">Profile Search Results</a></li>
					<?php endif; ?>
					
					<?php else: ?>
						<?php if ($user_type === ADMIN): ?>
						<li><a href="./admin.php">Admin</a></li>
						<li><a href="./user-logout.php">Logout</a></li>
						<li><a href="./user-dashboard.php">Dashboard</a></li>
						<li><a href="./user-update.php">Update User</a></li>
						<li><a href="./user-password-change.php">Change Password</a></li>
						<li><a href="./profile-select-city.php">Profile Select City</a></li>
						<li><a href="./profile-search.php">Profile Search</a></li>
						<li><a href="./disabled_users.php">Disabled Users</a></li>
						<?php else: ?>
						<?php if ($user_type === DISABLED): ?>
						<li><a href="./user-logout.php">Logout</a></li>
						<?php else: ?>
						<li><a href="./user-logout.php">Logout</a></li>
						<li><a href="./profile-create.php">Create Profile</a></li>
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php else: ?>
				<li><a href="./index.php">Index</a></li>
				<li><a href="./user-register.php">Register</a></li>
				<li><a href="./user-login.php">Login</a></li>
				<li><a href="./user-password-request.php">Request Password</a></li>
				<li><a href="./profile-select-city.php">Profile Select City</a></li>
				<li><a href="./profile-search.php">Profile Search</a></li>
				
					<?php if ((isset($_SESSION['search_results'])) && (count($_SESSION['search_results'] > 0)) && ("POST" == $_SERVER['REQUEST_METHOD'])) : ?>
						<li><a href="./profile-search-result.php">Profile Search Results</a></li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>

		</div>
		<div id="content">
		
<h2>
	<?php echo $banner; ?>
</h2>