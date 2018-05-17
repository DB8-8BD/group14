<?php     

/* 
   Author    : Erik Henry
   Date      : Nov 24th, 2017
   Filename  : user-update.php
   Course    : WEBD-3201
   Purpose   : Allow users to update their information
*/  

$title = "Update User Info";
$banner = "Update Your Information!";
$date = "Nov 24th, 2017";
$filename = "user-update.php";
$description = "Allow users to update their information";

date_default_timezone_set('America/Toronto');

require_once ("header.php");
$requiredParams = 0;
$error = false;

$user_id = false;
$first_name = false;
$last_name = false;
$email_address = false;
$error = "";
$md5_hash_password = "";
$birth_date = false;

$conn = db_connect();
$bRegistrationSuccessful = false;


if (!isset($_SESSION['user_id']))
{
	header("Location: user-login.php"); // redirect user to login page
}
else if ($_SESSION['user_type'] == DISABLED)
{
	header("Location: aup.php"); // redirect user to dashboard
}
else
{
	$user_id = $_SESSION['user_id'];
	// obtain the user's bio from the database only
	$result = pg_execute(db_connect(), "pstGetUser", array($user_id));
	
	$first_name = pg_fetch_result($result, "first_name");
	$last_name = pg_fetch_result($result, "last_name");
	$email_address = pg_fetch_result($result, "email_address");
	$birth_date = pg_fetch_result($result, "birth_date");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") //make sure the user has submitted their entry (POST)
{
			
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
		
		else
		{
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
				
				$result = pg_execute(db_connect(), "pstUpdateUser", array($first_name, $last_name, $email_address, $birth_date, $user_id));
				
				//$result = pg_execute(db_connect(), "pstInsertProfile", array($user_id,'0','0','0',0,0,0,0,0,0,0,0,0,0,0,0,0));
				if ($result === false) {
					$error .= "<br/>Query Failed: ";
				}
				else {
					//$_SESSION["msg"] = Your registration was successful. Now you will be redirected to the login page.;
					$bRegistrationSuccessful = true;
				
					
				}
				
			}
}	
?>
<?php echo "<h2>$error </h2>"; ?>
<br/>
<?php if ($bRegistrationSuccessful): ?><b>User Has Been Updated!</b> 
<?php else: ?>

<p><b>Please re-enter any information you would like to be updated. </b> </p><br/>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<table class = "center" border="0"  cellpadding="10" >
	<tr>
		<td><strong>User ID</strong></td>
		<td><input type="text" name="user_id" disabled="disabled" value="<?php echo $user_id; ?>" />
		</td>
	</tr>	
	<tr>
		<td><strong>First Name:</strong></td>
		<td><input type = "text" name = "first_name" value = "<?php echo $first_name; ?>"/></td>
	</tr>
	<tr>
		<td><strong>Last Name:</strong></td>
		<td><input type = "text" name = "last_name" value = "<?php echo $last_name; ?>"/></td>
	</tr>
	<tr>
		<td><strong>Email Address:</strong></td>
		<td><input type = "text" name = "email_address" value = "<?php echo $email_address; ?>"/></td>
	</tr>
	<tr>
		<td><strong>Birth Date: (MM/DD/YYYY)</strong></td>
		<td><input type = "text" name = "birth_date" value = "<?php echo $birth_date; ?>" /></td>
	</tr>
</table>


<table class = "class"  border="0" cellspacing="15" >
<tr>
	<td><input type="submit" value = "Update"/></td>
	
	<td><input type="reset" value = "Reset"/></td>
</tr>
</table>
</form>
<?php endif; ?>
<?php

require_once "footer.php";
?>