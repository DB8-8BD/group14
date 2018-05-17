<?php     

/* 
   Author    : Mathew Kostrzewa, Shreeji Patel, Ryan Beckett
   Date      : Sept 25, 2017
   Filename  : profile-display.php
   Course    : WEBD-3201
   Purpose   : Allow users to view information on another user's profile page.
*/  

$title = "View Profile";
$banner = "View Profile";
$date = "Sept 25, 2017";
$filename = "profile-display.php";
$description = "Allow users to view information on another user's profile page.";

date_default_timezone_set('America/Toronto');

require_once ("header.php");
$error = "";
$currentID = "";
$Picture = 0;
$result = "";
$pResult = null;
$uResult = null;
$user_type = "";
$selfMatch = false;
$selfUserId = "";
$bAdministrator = false;
$bClient = false;
$bUserDisabled = false;
$bUserDoesntExist = false;
$bInterestFound = false;
$bInterestAlreadyExists = false;
$bOffenceAlreadyExists = false;
$bUserBlocked = false;
$requestParams = [];
$bypass = false;
$prospective_id = "";
$actionRequested = "";
$pageName = $_SERVER['PHP_SELF'];
if (isset($_SESSION['user_id'])) 
{
	$selfUserId = $_SESSION['user_id'];
	if (isset($_SESSION['user_type']))
	{
		$currentID = $_SESSION['user_id'];
		$user_type = $_SESSION['user_type'];
		if ($user_type === ADMIN)
		{
			$bAdministrator = true;
		}
		if ($user_type === CLIENT) 
		{
			$bClient = true;
		}
		if ($bClient || $bAdministrator)
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$requestParams = $_POST;
			}
			else if ($_SERVER["REQUEST_METHOD"] == "GET")
			{
				$requestParams = $_GET;
			}
			if (isset($requestParams['user_id']))
			{
				$currentID = $requestParams['user_id'];
			}
			else
			{
				$currentID = $_SESSION['user_id'];
			}
			if (0 == strcmp($currentID, $selfUserId))
			{
				$selfMatch = true;
			}
			// Determine if prospective user is not disabled
			$result = pg_execute(db_connect(), "pstGetUserType", array($currentID));
			$cresult = pg_fetch_result($result, 0);
			if (DISABLED == $cresult)
			{
				$bUserDisabled = true;
			}
			if (isset($requestParams['action']))
			{
				$actionRequested = $requestParams['action'];
			}
			// initialize
			for ($j=0;$j<16;$j++)
			{
				$uResult[$j] = "0";
			}
			$pResult[0] = "";
			$pResult[1] = "";
			$pResult[2] = "";
			// compare for true
			switch(true)
			{
				case ($bClient && !$bAdministrator):// searching user cannot be disabled
				{
					if (!$bUserDisabled)// search for users cannot be disabled
					{
						if (!$selfMatch)// cannot do anything for self-searchers, except show them their own profile
						{
							// test user's id first, try to find 1 match (ie. they are already interested in this user)
							$result = pg_execute(db_connect(), "pstGetProfileInterest", array($selfUserId, $currentID));
							if (FALSE != $result)
							{
								if (0 < pg_num_rows($result))
								{
									// obtain any user's that may already be interested in them
									$prospective_id = pg_fetch_result($result, 0);
									if (0 == strcmp($prospective_id, $currentID))
									{
										// if they have signalled remove, then remove their interests
										if (0 == strcmp($actionRequested, "Remove"))
										{
											$result = pg_execute(db_connect(), "pstDeleteInterest", array($selfUserId, $currentID));
											header("refresh:0; url = $pageName?user_id=$currentID");
										}
										else if (0 == strcmp($actionRequested, "Block"))
										{
											$error .= "You must first remove your interest in $currentID before you may block them!";
										}
										else
										{
											$bInterestAlreadyExists = true;
										}
									}
									else
									{
										//pstUpdateInterest - put sought after user's id into current user's interests
										if (0 == strcmp($actionRequested, "Add"))
										{
											$result = pg_execute(db_connect(), "pstUpdateInterest", array($currentID, $selfUserId));
											header("refresh:0; url = $pageName?user_id=$currentID");
										}
									}
								}
								else
								{
									//pstInsertInterest - put sought after user's id into current user's interests
									if (0 == strcmp($actionRequested, "Add"))
									{
										$result = pg_execute(db_connect(), "pstInsertInterest", array($selfUserId, $currentID));
										header("refresh:0; url = $pageName?user_id=$currentID");
									}
									// if they have signalled block, then remove the interested party's interests
									if (0 == strcmp($actionRequested, "Block"))
									{
										$result = pg_execute(db_connect(), "pstDeleteInterest", array($currentID, $selfUserId));
										$bUserBlocked = true;//no need to refresh page here, as blocked is something not normally displayed on this page
									}
								}
							}
							else
							{
								//$error .= "ERROR querying db";
							}
							// test user's id first, try to find 1 match (ie. they are already offended by this user)
							$result = pg_execute(db_connect(), "pstGetProfileOffence", array($selfUserId, $currentID));
							if (FALSE != $result)
							{
								if (0 < pg_num_rows($result))
								{
									$bOffenceAlreadyExists = true;
								}
								else
								{
									//pstInsertOffence - put offending user's id into current user's offences
									if (0 == strcmp($actionRequested, "Report"))
									{
										$result = pg_execute(db_connect(), "pstInsertOffence", array($selfUserId, $currentID));
										header("refresh:0; url = $pageName?user_id=$currentID");
									}
								}
							}
						}
						else
						{
							//$error .= "selfmatching user";
						}
					}
					else
					{
						$error .= "disabled user";// should never see this
					}
					//fall-through
				}
				case (!$selfMatch)://search for users cannot be disabled
				{
					$result = pg_execute(db_connect(), "pstGetUserFullDetails", array($currentID));
					if (FALSE != $result)
					{
						$uResult = pg_fetch_assoc($result);
						$Picture = $uResult['images'];
						$result = pg_execute(db_connect(), "pstGetUserProfileDetailsEx", array($currentID));
						if (FALSE != $result)
						{
							$pResult = pg_fetch_assoc($result);
						}
					}
					else
					{
						$bUserDoesntExist = true;
					}
					break;
				}
				default:
				{
					break;
				}
			}
		}
		else 
		{ 
			header("refresh:0; url = profile-create.php");
		}
	}
}

else {
		header("refresh:0; url = user-login.php");
	 }
?>
<!--Profile status-->
<?php if ($bypass): ?>
<h3>bypassed</h3>
<?php endif; ?>
<?php if ($bUserDoesntExist): ?>
  <p>User doesn't exist</p>
<?php else: ?>
  <?php if ($user_type === CLIENT): ?>
    <?php if ($selfMatch): ?> 
    <p>This is what your profile looks like to other users</p>
	<?php else: ?>
	  <?php if ($bInterestAlreadyExists): ?> 
	    <p><a href="<?php echo $pageName . "?user_id=" . $selfUserId; ?>"><?php echo $selfUserId; ?></a> is interested in <?php echo $currentID; ?>.&nbsp;
		<a href="<?php echo $pageName . "?action=Remove&user_id=" . $currentID; ?>">Remove</a></p>
      <?php else: ?>
	    <?php if ($bUserBlocked): ?>
		<p><a href="<?php echo $pageName . "?user_id=" . $currentID; ?>"><?php echo $currentID; ?></a>&nbsp;has been blocked.</p>
		<?php else: ?>
	    <p>to show you are interested in&nbsp;<?php echo $currentID; ?>&nbsp;click&nbsp;<a href="<?php echo $pageName . "?action=Add&user_id=" . $currentID; ?>">Add</a>&nbsp;</p>
		<?php endif; ?>
	  <?php endif; ?>
	  <?php if ($bOffenceAlreadyExists): ?> 
	    <p>You have reported &nbsp;<?php echo $currentID; ?>&nbsp;for offencive behaviour.</p>
	  <?php else: ?>
	    <p>to show you are offended by&nbsp;<?php echo $currentID; ?>&nbsp;click&nbsp;<a href="<?php echo $pageName . "?action=Report&user_id=" . $currentID; ?>">Report</a>&nbsp;</p>
	  <?php endif; ?>
    <?php endif; ?>
  <?php else: ?>
    <?php if ($user_type === ADMIN): ?>
      <?php if ($bUserDisabled): ?> 
        <p><a href="admin.php?action=Enable&user_id="<?php echo $currentID; ?>">Enable this user</a></p>
        <?php else: ?>
        <p><a href="admin.php?action=Disable&user_id="<?php echo $currentID; ?>">Disable this user</a></p>
	  <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>

<br/><br/><?php echo $error; ?>
<table class = "center" cellpadding="10" >
	<tr>
		<td><strong>User Name:</strong></td>
		<td><strong><?php echo $uResult['first_name'] . " " . $uResult['last_name'] ;?> </strong></td>
	</tr>
	<tr>
		<td><strong>Email:</strong></td>
		<td><a href="mailto:<?php echo $uResult['email_address'] ;?>"><?php echo $uResult['email_address'] ;?></a></td>
	</tr>
	<tr>
		<td><strong>Birth Date:</strong></td>
		<td><strong><?php echo $uResult['birth_date'] ;?> </strong></td>
	</tr>
	<tr>
		<td><strong>Gender:</strong></td>
		<td><strong><?php echo getProperty("gender", $uResult['gender']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Looking For Gender:</strong></td>
		<td><strong><?php echo getProperty("gender_sought", $uResult['gender_sought']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>City:</strong></td>
		<td><strong><?php echo getProperty("city", $uResult['city']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>State:</strong></td>
		<td><strong><?php echo getProperty("state", $uResult['state']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Tax Bracket:</strong></td>
		<td><strong><?php echo getProperty("tax_bracket", $uResult['tax_bracket']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Education:</strong></td>
		<td><strong><?php echo getProperty("education", $uResult['education']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Occupation:</strong></td>
		<td><strong><?php echo getProperty("occupation", $uResult['occupation']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Housing:</strong></td>
		<td><strong><?php echo getProperty("housing_status", $uResult['housing_status']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Vehicle Type:</strong></td>
		<td><strong><?php echo getProperty("vehicle_type", $uResult['vehicle_type']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Hobbies:</strong></td>
		<td><strong><?php echo getProperty("hobbies", $uResult['hobbies']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Sports:</strong></td>
		<td><strong><?php echo getProperty("sports", $uResult['sports']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Religion:</strong></td>
		<td><strong><?php echo getProperty("religion", $uResult['religion']); ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Headline:</strong></td>
		<td><strong><?php echo $pResult['headline']; ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Bio:</strong></td>
		<td><strong><?php echo $pResult['self_description']; ?> </strong></td>
	</tr>
	<tr>
		<td><strong>Match Description:</strong></td>
		<td><strong><?php echo $pResult['match_description']; ?> </strong></td>
	</tr>
	<tr>
	<td>
	<?php if (0!=$Picture): ?>
	<?php echo "<img src=\"$currentID/" . $currentID . "_$Picture.jpg\" width='300px' height='350px' />";?>
	<?php endif; ?>
	</td>
	</tr>
</table>
<?php require_once ("footer.php"); ?>