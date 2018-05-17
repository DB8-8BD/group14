<?php     

/* 
   Author    : Mathew Kostrzewa
   Date      : Sept 25, 2017
   Filename  : profile-create.php
   Course    : WEBD-3201
   Purpose   : Allow users to enter information to be displayed on their profile for other users
*/  

$title = "Create Profile";
$banner = "Make A Profile Today!";
$date = "Sept 25, 2017";
$filename = "profile-create.php";
$description = "Allow users to enter information to be displayed on their profile for other users.";
date_default_timezone_set('America/Toronto');
require_once ("header.php");
$selectedGender = 0;
$selectedOtherGender = 1;
$selectedCity = 0;
$selectedState = 0;
$selectedBracket = 0;
$selectedHobby = 0;
$selectedEducation = 0;
$selectedOccupation = 0;
$selectedHousing = 0;
$selectedVehicle = 0;
$selectedHobby = 0;
$selectedSports = 0;
$selectedReligion = 0;
$selectedHeadline = "";
$selectedSelfDescription = "";
$selectedMatchDescription = "";
$failedParam = "";
$user_id = "";
$requiredParams = 0;
$error = false;
if (isset($_SESSION['user_id'])) 
{
	$user_id = $_SESSION['user_id'];
	if(isset($_SESSION['user_type']))
	{
		$user_type = $_SESSION['user_type'];
		 if($user_type === DISABLED)
		{
			header("refresh:0; url=aup.php");
		}
	}
	// obtain the user's bio from the database only
	$result = pg_execute(db_connect(), "pstGetUserProfileDetailsEx", array($user_id));
	if (FALSE != $result)
	{
		$pResult = pg_fetch_assoc($result);
		if (isset($pResult['headline']))
		{
			$selectedHeadline = trim($pResult['headline']);
		}
		if (isset($pResult['self_description']))
		{
			$selectedSelfDescription = trim($pResult['self_description']);
		}
		if (isset($pResult['match_description']))
		{
			$selectedMatchDescription = trim($pResult['match_description']);
		}
	}
}
if (isset($_SESSION['city'])) 
{
	$selectedCity = $_SESSION['city'];
}
if (isset($_SESSION['state'])) 
{
	$selectedState = $_SESSION['state'];
}
if (isset($_SESSION['gender'])) 
{
	$selectedGender = $_SESSION['gender'];
}
if (isset($_SESSION['gender_sought'])) 
{
	$selectedOtherGender = $_SESSION['gender_sought'];
}
if (isset($_SESSION['tax_bracket'])) 
{
	$selectedBracket = $_SESSION['tax_bracket'];
}
if (isset($_SESSION['education'])) 
{
	$selectedEducation = $_SESSION['education'];
}
if (isset($_SESSION['occupation'])) 
{
	$selectedOccupation = $_SESSION['occupation'];
}
if (isset($_SESSION['housing_status'])) 
{
	$selectedHousing = $_SESSION['housing_status'];
}
if (isset($_SESSION['vehicle_type'])) 
{
	$selectedVehicle = $_SESSION['vehicle_type'];
}
if (isset($_SESSION['hobbies'])) 
{
	$selectedHobby = $_SESSION['hobbies'];
}
if (isset($_SESSION['sports'])) 
{
	$selectedSports = $_SESSION['sports'];
}
if (isset($_SESSION['religion'])) 
{
	$selectedReligion = $_SESSION['religion'];
}
// loop through params, obtaining required elements
// only requirement needed to update database is that there are fifteen parameters submitted
foreach ($_POST as $varproperty => $varvalue)
{
    if (isset($varvalue))
	{
		$len = strlen($varvalue);
        switch ($varproperty)
		{
			case "gender":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$selectedGender = $varvalue;
					$requiredParams++;
				}
				else
				{
					$failedParam .= " gender ";
				}
				break;
			}
			case "otherGender":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedOtherGender = $varvalue;
				}
				else
				{
					$failedParam .= " otherGender ";
				}
				break;
			}
			case "city":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedCity = $varvalue;
				}
				else
				{
					$failedParam .= " city ";
				}
				break;
			}
			case "state":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedState = $varvalue;
				}
				else
				{
					$failedParam .= " state ";
				}
				break;
			}
			case "tax_bracket":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedBracket = $varvalue;
				}
				else
				{
					$failedParam .= " tax_bracket ";
				}
				break;
			}
			case "hobbies";
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedHobby = $varvalue;
				}
				else
				{
					$failedParam .= " hobbies ";
				}
				break;
			}
			case "education":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedEducation = $varvalue;
				}
				else
				{
					$failedParam .= " education ";
				}
				break;
			}
			case "occupation":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedOccupation = $varvalue;
				}
				else
				{
					$failedParam .= " occupation ";
				}
				break;
			}
			case "housing_status":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedHousing = $varvalue;
				}
				else
				{
					$failedParam .= " housing_status ";
				}
				break;
			}
			case "vehicle_type":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedVehicle = $varvalue;
				}
				else
				{
					$failedParam .= " vehicle_type ";
				}
				break;
			}
			case "sports":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedSports = $varvalue;
				}
				else
				{
					$failedParam .= " sports ";
				}
				break;
			}
			case "religion":
			{
				if (is_numeric($varvalue) && 0 < $len && $len < MAX_INT_CHARACTER_LEN)
				{
					$requiredParams++;
					$selectedReligion = $varvalue;
				}
				else
				{
					$failedParam .= " regligion ";
				}
				break;
			}
			case "headline":
			{
				if (0 < $len && $len < MAXIMUM_HEADLINE_LENGTH)
				{
					$requiredParams++;
					$selectedHeadline = trim($varvalue);
				}
				else
				{
					$failedParam .= " headline ";
				}
				break;
			}
			case "self_description":
			{
				if (0 < $len && $len < MAXIMUM_DESCRIPTION_LENGTH)
				{
					$requiredParams++;
					$selectedSelfDescription = trim($varvalue);
				}
				else
				{
					$failedParam .= " self_description ";
				}
				break;
			}
			case "match_description":
			{
				if (0 < $len && $len < MAXIMUM_DESCRIPTION_LENGTH)
				{
					$requiredParams++;
					$selectedMatchDescription = trim($varvalue);
				}
				else
				{
					$failedParam .= " match_description ";
				}
				break;
			}
			default:
			{
				// ignore all other params
				break;
			}
		}
    }
}
if ($requiredParams === FIFTEEN_REQUIREMENTS)
{
	if (isset($_SESSION['user_type']))
	{
		$user_type = $_SESSION['user_type'];
		if ($user_type === CLIENT) 
		{
			// update operation
			if (FALSE != pg_execute(db_connect(), "pstUpdateProfile", array($user_id,$selectedHeadline,$selectedSelfDescription,$selectedMatchDescription,$selectedCity,$selectedState,
				$selectedGender,$selectedOtherGender,'0',$selectedBracket,$selectedEducation,$selectedOccupation,$selectedHousing,$selectedVehicle,$selectedHobby,$selectedSports,$selectedReligion)))
			{
				if (FALSE != pg_execute(db_connect(), "pstUpdateUserType", array(CLIENT, $user_id)))
				{
					$_SESSION['city'] = $selectedCity;
					$_SESSION['state'] = $selectedState;
					$_SESSION['gender'] = $selectedGender;
					$_SESSION['gender_sought'] = $selectedOtherGender;
					$_SESSION['images'] = '0';
					$_SESSION['tax_bracket'] = $selectedBracket;
					$_SESSION['education'] = $selectedEducation;
					$_SESSION['occupation'] = $selectedOccupation;
					$_SESSION['housing_status'] = $selectedHousing;
					$_SESSION['vehicle_type'] = $selectedVehicle;
					$_SESSION['hobbies'] = $selectedHobby;
					$_SESSION['sports'] = $selectedSports;
					$_SESSION['religion'] = $selectedReligion;
					$_SESSION['user_type'] = CLIENT;
					header("refresh: 2; url=user-dashboard.php"); // append refresh header
				}
			}
		}
		else if ($user_type === INCOMPLETE)
		{
			// insert operation
			if (FALSE != pg_execute(db_connect(), "pstInsertProfile", array($user_id,$selectedHeadline,$selectedSelfDescription,$selectedMatchDescription,$selectedCity,$selectedState,
				$selectedGender,$selectedOtherGender,'0',$selectedBracket,$selectedEducation,$selectedOccupation,$selectedHousing,$selectedVehicle,$selectedHobby,$selectedSports,$selectedReligion)))
			{
				if (FALSE != pg_execute(db_connect(), "pstUpdateUserType", array(CLIENT, $user_id)))
				{
					$_SESSION['city'] = $selectedCity;
					$_SESSION['state'] = $selectedState;
					$_SESSION['gender'] = $selectedGender;
					$_SESSION['gender_sought'] = $selectedOtherGender;
					$_SESSION['images'] = '0';
					$_SESSION['tax_bracket'] = $selectedBracket;
					$_SESSION['education'] = $selectedEducation;
					$_SESSION['occupation'] = $selectedOccupation;
					$_SESSION['housing_status'] = $selectedHousing;
					$_SESSION['vehicle_type'] = $selectedVehicle;
					$_SESSION['hobbies'] = $selectedHobby;
					$_SESSION['sports'] = $selectedSports;
					$_SESSION['religion'] = $selectedReligion;
					$_SESSION['user_type'] = CLIENT;
					header("refresh: 2; url=user-dashboard.php"); // append refresh header
				}
			}
		}
		
	}
}
else
{
	$error = true;
}
?>
<p><b>
<?php if ("POST" == $_SERVER['REQUEST_METHOD']): ?>
	<?php if ($error): ?>
	You did not enter all of the required parameter(s):<br /> <?php echo $failedParam; ?>
	<?php else: ?>
	Thank you
	<?php endif; ?>
<?php else: ?>	
Enter your user ID, gender, gender you are seeking, city, headline, bio and upload images to make your profile.
<?php endif; ?>
</b></p>
<!--
<form action="" method="post">
		<strong>Select an image to upload:</strong>
		<input type = "file" name = "images" />
		<input type = "submit" value = "Upload Image" />
</form> 
-->
<br/><br/>
	
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<table class = "center" cellpadding="10" >

	<tr>
		<td align="right"><strong>User ID</strong></td>
		<td><input type="text" name="user_id" disabled="disabled" value="<?php echo $user_id; ?>" />
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Your Gender</strong></td>
		<td> 	
			<?php echo buildRadio("gender", $selectedGender); ?>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Gender You Are Seeking</strong></td>
		<td>
			<fieldset>
			<?php if ($selectedOtherGender == 0): ?>
				<input name="otherGender" type="radio" value="0" checked="checked" />&nbsp;Female&nbsp;
				<input name="otherGender" type="radio" value="1" />&nbsp;Male&nbsp;
			<?php else: ?>
				<?php if ($selectedOtherGender == 1): ?>
				<input name="otherGender" type="radio" value="1" checked="checked" />&nbsp;Male&nbsp;|
				<input name="otherGender" type="radio" value="0" />&nbsp;Female&nbsp;
				<?php else: ?>
				<input name="otherGender" type="radio" value="0" />&nbsp;Female&nbsp;|
				<input name="otherGender" type="radio" value="1" />&nbsp;Male&nbsp;
				<?php endif; ?>
			<?php endif; ?>
			</fieldset>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>City</strong></td>
		<td>
			<select name="city">
				<?php echo buildDropDown("city", $selectedCity); ?>
			</select>
		</td> 
	</tr>
	
	<tr>
		<td align="right"><strong>State</strong></td>
		<td>
		<select name="state">
				<?php echo buildDropDown("state", $selectedState); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Tax Bracket</strong></td>
		<td>
		<select name="tax_bracket">
				<?php echo buildDropDown("tax_bracket", $selectedBracket); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Education</strong></td>
		<td>
		<select name="education">
				<?php echo buildDropDown("education", $selectedEducation); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Occupation</strong></td>
		<td>
		<select name="occupation">
				<?php echo buildDropDown("occupation", $selectedOccupation); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Housing</strong></td>
		<td>
		<select name="housing_status">
				<?php echo buildDropDown("housing_status", $selectedHousing); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Vehicle Type</strong></td>
		<td>
		<select name="vehicle_type">
				<?php echo buildDropDown("vehicle_type", $selectedVehicle); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Hobbies</strong></td>
		<td>
		<select name="hobbies">
				<?php echo buildDropDown("hobbies", $selectedHobby); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Sports</strong></td>
		<td>
		<select name="sports">
				<?php echo buildDropDown("sports", $selectedSports); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Religion</strong></td>
		<td>
			<select name="religion">
				<?php echo buildDropDown("religion", $selectedReligion); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Headline</strong><br /><cite>(100 characters max)</cite></td>
		<td>
			<textarea cols="30" rows="3" name="headline" ><?php echo trim($selectedHeadline); ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Self Description</strong><br /><cite>(1000 characters max)</cite></td>
		<td>
			<textarea cols="30" rows="7" name="self_description" ><?php echo trim($selectedSelfDescription); ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td align="right"><strong>Match Description</strong><br /><cite>(1000 characters max)</cite></td>
		<td>
			<textarea cols="30" rows="7" name="match_description" ><?php echo trim($selectedMatchDescription); ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td></td><td>
		<?php if ($user_type === CLIENT): ?>
		<input type="submit" value = "Update"/>
		<?php else: ?>
		<input type="submit" value = "Create"/>
		<?php endif; ?>
		<input type="reset" value = "Reset"/>
		</td>
	</tr>
</table>
</form>

<?php

require_once "footer.php";
?>