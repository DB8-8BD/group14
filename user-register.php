<?php     

/* 
   Author    : Shreeji Patel
   Date      : September 29th 2017
   Modified by : Shreeji Patel, November 22, 2017
   Filename  : user-register.php
   Course    : WEBD-3201
   Purpose   : This file takes the user's details and creats the user account based on the credentials and 
			   insters user's information in the database using INSERT statement.
*/  


date_default_timezone_set('America/Toronto');
$title = "User Registration";
$description = "This page provides the different input boxes for registration.
			   The page gets different information from the user and valiadates it and 
			   addds in it the database. The user can insert their id, first and last name and
			   email address and register themselves on the database. It also tracks the date of when the 
			   user was registered and when he last accessed his account. ";
$date = date("F j, Y, g:i a");
$filename = "user-register.php";
$banner = "User Registration";
$date = "November 22, 2017";

require_once ("header.php"); 

$user_id = false;
$password = false;
$first_name = false;
$last_name = false;
$email_address = false;
$error = "";
$md5_hash_password = "";
$birth_date = false;
$timestamp = 0;

$conn = db_connect();
$bRegistrationSuccessful = false;

if (isset($_SESSION['user_type']))
{
	$user_type = $_SESSION['user_type'];
	if ($user_type == DISABLED)
	{
		header("Location: aup.php");
	}
	else if ($user_type == INCOMPLETE)
	{
		header("Location: profile-create.php");
	}
	else if ($user_type == ADMIN)
	{
		header("Location: admin.php");
	}
}

else 
	{
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
	
		if (isset($_POST["user_id"]) === false ) 
		{
			$error .= "You must enter the username.<br/>";
		
		} 	else if (trim($_POST["user_id"]) == "") 
			{
				$error .= "You must enter the user id.<br/>";
			} 	else if (strlen(trim($_POST["user_id"])) > MAXIMUM_USER_ID_LENGTH ) 
				{
					$error .= "Your user-id is " . strlen(trim($_POST["user_id"])) . " characters long. The maximum allowed 
					characters is ". MAXIMUM_USER_ID_LENGTH . "<br/>";
				}	 else if (strlen(trim($_POST["user_id"])) < MINIMUM_USER_ID_LENGTH ) 
				{
				$error .= "Your user-id is " . strlen(trim($_POST["user_id"])) . " characters long. The minimum allowed 
				characters is ". MINIMUM_USER_ID_LENGTH . "<br/>";
				} else
					{
						  
				        //$sql = "SELECT first_name, last_name, email_address FROM users WHERE user_id = '" . trim($_POST["user_id"]) . "'";
							$sql = qry_user_details();
							$result = pg_execute(db_connect(), "pstGetUserDetails", array(trim($_POST["user_id"])));
						//$result = pg_query($conn, $sql);
			
							
						 if (pg_num_rows($result)) {
				
							$error .= "A user with id ' " . trim($_POST["user_id"]) . "' already exists <br/>";
						} else {
								$user_id = trim($_POST["user_id"]);
								}
					}
					
		if (isset($_POST["password"]) === false ) {
			$error .= "You must enter the password.<br/>";
		
		
		} else if (trim($_POST["password"]) == "") {
			$error .= "You must enter the password.<br/>";
		} else if (strlen(trim($_POST["password"])) > MAXIMUM_PASSWORD_LENGTH ) {
			$error .= "Your password is " . strlen(trim($_POST["password"])) . " characters long. The maximum allowed 
			characters is ". MAXIMUM_PASSWORD_LENGTH . "<br/>";
		} else if (strlen(trim($_POST["password"])) < MINIMUM_PASSWORD_LENGTH ) {
			$error .= "Your password is " . strlen(trim($_POST["password"])) . " characters long. The minimum allowed 
			characters is ". MINIMUM_PASSWORD_LENGTH . "<br/>";
		} else {
					if (isset($_POST["confirm"]) === false ) 
					{
						$error .= "You must confirm password.<br/>";
					} else if (trim($_POST["confirm"]) == "") {
						$error .= "Empty confirm password<br/>";
					} else if(strcmp(trim($_POST["password"]), trim($_POST["confirm"])) !== 0) 
					{
						$error .= "The passwords do not match<br/>";
					} else {
							
							
							$password = trim($_POST["password"]);
							$md5_hash_password = md5(SALT . $password);
						   }
				}
			
			if (isset($_POST["first_name"]) === false ) 
			{
				$error .= "You must enter the first name.<br/>";
			} else if (trim($_POST["first_name"]) == "") {
			$error .= "You must enter the first name<br/>";
			} else if (is_numeric(trim($_POST["first_name"])))
				{
					$error .= "Your first name can not be a number. You entered " . $_POST["first_name"];
				}
			
			else if (strlen(trim($_POST["first_name"])) > MAX_FIRST_NAME_LENGTH ) {
			$error .= "Your first name is " . strlen(trim($_POST["id"])) . " characters long. The maximum allowed 
			characters is ". MAX_FIRST_NAME_LENGTH . "<br/>";
			}  else {
						$first_name = trim($_POST["first_name"]);
					}
			
		if (isset($_POST["last_name"]) === false ) {
			$error .= "<br/>You must enter the first name.<br/>";
		
		
		} else if (trim($_POST["last_name"]) == "") {
			$error .= "You must enter last name<br/>";
		} else if (is_numeric(trim($_POST["last_name"])))
				{
					$error .= "<br/>Your last name can not be a number. You entered " . $_POST["last_name"];
				}
		else if (strlen(trim($_POST["last_name"])) > MAX_LAST_NAME_LENGTH ) {
			$error .= "<br/>Your last name is " . strlen(trim($_POST["last_name"])) . " characters long. The maximum allowed 
			characters is ". MAX_LAST_NAME_LENGTH . "<br/>";
		}  else {
					$last_name = trim($_POST["last_name"]);
				}
				
		if (isset($_POST["email_address"]) === false ) {
			$error .= "You must enter the email address.<br/>";
		} else if (trim($_POST["email_address"]) == "") {
			$error .= "You must enter your email address.<br/>";
		} else if (strlen(trim($_POST["email_address"])) > MAXIMUM_EMAIL_LENGTH ) {
			$error .= "Your last name is " . strlen(trim($_POST["email_address"])) . " characters long. The maximum allowed 
			characters is ". MAXIMUM_EMAIL_LENGTH . "<br/>";
		}  else if (filter_var(trim($_POST["email_address"]), FILTER_VALIDATE_EMAIL) === false) {
			$error .= "<br/>Email address is not valid. You entered: ". $_POST['email_address'] ;
		}
			else {
					$email_address = trim($_POST["email_address"]);
				 }
				 
			if (trim($_POST["birth_date"]) == "") {
				$error .= "You must enter the date of birth.<br/>";
			}
			
			else {
					$birth_date	= trim($_POST["birth_date"]);
					if (($timestamp = strtotime($birth_date) ) === false)
						{
							$error .= "Please enter a valid date in a format of MM/DD/YYYY";
							$birth_date = "";
						}
						else 
						{
							$birth_date = $_POST["birth_date"];
							$difference = calculateAge($birth_date);
				
										if ($difference < MINIMUM_AGE)
											{
											$error .= "Your age must be atleast 18!!";
											} 
						}
					}
				
			
			if ($error == "") 
			{
				
				$result = pg_execute(db_connect(), "pstInsertUser", array($user_id, $md5_hash_password, INCOMPLETE, $first_name, $last_name, $email_address, '1980-01-01', date("Y-m-d"), date("Y-m-d")));
				
				if ($result === false) {
					$error .= "<br/>Query Failed: ";
				}
				else {
					
					$bRegistrationSuccessful = true;
					header("refresh:3; url = user-login.php");
					
				}
				
			}
			
	}
}
		
?>
<?php echo "<h2>$error </h2>"; ?>
<?php if ($bRegistrationSuccessful): ?> <b>Your registration was successful. Now you will be redirected to the <a href = "user-login.php">login page. </b> 
<?php else: ?>

 <h2><br/><br/>Sign Up here!!</h2>
<p><b>Insert your User ID, Password, first name, last name and email address to register to this system. </b> </p><br/>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<table class = "center" border="0"  cellpadding="10" >
	<tr>
		<td><strong>Login ID</strong></td>
		<td><input type = "text" name = "user_id" value = "<?php echo $user_id ?>"/></td>
	</tr>
	
	<tr>
		<td><strong>Password</strong></td>
		<td><input type = "password" name ="password" value = "<?php $password ?>"/></td>
	</tr>
	
	<tr>
		<td><strong>Confirm Password</strong></td>
		<td><input type = "password" name ="confirm" value = "<?php $confirm ?>"/></td>
	</tr>
	
	<tr>
		<td><strong>First Name:</strong></td>
		<td><input type = "text" name = "first_name" value = "<?php echo $first_name ?>"/></td>
	</tr>
	<tr>
		<td><strong>Last Name:</strong></td>
		<td><input type = "text" name = "last_name" value = "<?php echo $last_name ?>"/></td>
	</tr>
	<tr>
		<td><strong>Email Address:</strong></td>
		<td><input type = "text" name = "email_address" value = "<?php echo $email_address ?>"/></td>
	</tr>
	<tr>
		<td><strong>Birth Date: (MM/DD/YYYY)</strong></td>
		<td><input type = "text" name = "birth_date" value = "<?php echo $birth_date ?>" /></td>
	</tr>
</table>

<table class = "class"  border="0" cellspacing="15" >
<tr>
	<td><input type="submit" value = "Sign Up"/></td>
	
	<td><input type="reset" value = "Reset"/></td>
</tr>
</table>
</form>
<?php endif; ?>
<?php

require_once "footer.php";
?>