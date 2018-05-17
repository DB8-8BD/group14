<?php 

//Filename: interests.php
//Author: Ryan Beckett
//Date: Jan 13th, 2018
//Description: Will have basic information for the user

$title = "Interests";
$banner = "Interests";
$date = "Jan 13th, 2018";
$filename = "interests.php";
$description;

require_once ("header.php"); 
$error = "";
$user_id = "";
$iResult = [];//initial result
$rResult = [];
$brows = 0;
$crows = 0;
$pageName = $_SERVER['PHP_SELF'];
if (isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
}
else
{
	header("Location: user-login.php");
}
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
	header("Location: user-login.php");
}
// Failure means invalid userid
$bResult = pg_execute(db_connect(), "pstGetProfileInterests", array($user_id));
$cResult = pg_execute(db_connect(), "pstGetInterestedProfiles", array($user_id));
$brows = pg_num_rows($bResult);
$crows = pg_num_rows($cResult);
for ($i=0;$i<$crows;$i++)
{
	$row = pg_fetch_row($cResult);
	if (null != $row[0])
	{
		$iResult[$i]=$row[0];//userid
	}
}
$iCount = count($iResult);//1-based
$rCount = 0;
?>

<table cellpadding="0" cellspacing="0" style="width:68%;border:1px solid black">
<tr><th>Users that you are interested in:</th><th>Users that are interested in you:</th></tr>
<tr><td><ul>
<?php 
// iterate the users interests
for ($i=0,$r=0;$i<$brows;$i++)
{
	$bFoundMatch = false;
	$row = pg_fetch_row($bResult);
	//iterate the other user's interests looking for a match
	for ($j=0;$j<$iCount;$j++)
	{
		if (0 == strcmp($row[0], $iResult[$j]))
		{
			$bFoundMatch = true;//flag
		}
	}
	if ($bFoundMatch)
	{
		echo "<li class=\"highlightListItem\"><a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</a>&nbsp;|&nbsp;<a href=\"profile-display.php?action=Remove&user_id=" . $row[0] . "\">Remove</a></li>";
		$rResult[$r] = $row[0];//rResult means refined results
		$r++;
	}
	else
	{
		echo "<li><a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</a>&nbsp;|&nbsp;<a href=\"profile-display.php?action=Remove&user_id=" . $row[0] . "\">Remove</a></li>";
	}
}
$rCount = count($rResult);//1-based
$bFoundMatch = false;
 ?>
</ul><td><ul>
<?php
for ($i=0,$r=0;$i<$crows;$i++)
{
	$row = pg_fetch_row($cResult, $i);
	for ($j=0;$j<$rCount;$j++)
	{
		if (0 == strcmp($row[0], $rResult[$j]))
		{
			$bFoundMatch = true;//flag
		}
	}
	if ($bFoundMatch)
	{
		echo "<li class=\"highlightListItem\"><a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</a>&nbsp;|&nbsp;<a href=\"profile-display.php?action=Block&user_id=" . $row[0] . "\">Block</a></li>";
	}
	else
	{
		echo "<li><a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</a>&nbsp;|&nbsp;<a href=\"profile-display.php?action=Block&user_id=" . $row[0] . "\">Block</a></li>";
	}
}

?>
</td></tr></table>

<?php require_once ("footer.php"); ?>