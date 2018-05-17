<?php 

//Filename: index.php
//Author: Group
//Date: Sept 28th, 2017
//Description: The main page of the site where users can log in/register


$title = "Index";
$banner = "Welcome to the World of Dating!";
$date = "Sept 15, 2017";
$filename = "index.php";
$description;

require_once ("header.php"); 
?>

<br/>
<p>
	<b>Previous user? Log in here!</b>
</p>
			
	<ul>
		<li><a href="./user-login.php"><b>Member Login</b></a></li>
	</ul>
	
		 <b>New member? Create an account here!</b>
	<ul>
		<li><a href="./user-register.php"><b>Create an Account</b></a></li>		
	</ul>
			
<?php require_once ("footer.php"); ?>
