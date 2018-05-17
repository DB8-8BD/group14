<?php
/* 
   Author    : Group 14
   Date      : Dec 5, 2017
   Filename  : admin.php
   Course    : WEBD-3201
   Purpose   : A page for administrators to access advanced functionality
*/  
date_default_timezone_set('America/Toronto');

$title = "Admin";
$banner = "Admin Page";
$date = "Dec 5, 2017";
$filename = "admin.php";
$description = "Displays a page exclusive to administrators";

require_once ("header.php");
$requestParams = [];
$action = "";
$userID = "";
$error = "";
$sortBy = "status";
$direction = "DESC";
$picType = "v";
$pageName = $_SERVER['PHP_SELF'];
$result = [];
$sql = "SELECT user_id, offending_id, offended_time, status FROM offensives ";
$f1 = false;
if ($_SESSION['user_type'] == INCOMPLETE)
{
	header("Location: profile-create.php"); // redirect user to create profile if they are incomplete
}
else if ($_SESSION['user_type'] == CLIENT)
{
	header("Location: user-dashboard.php"); // redirect user to dashboard if they are completed
}
else if ($_SESSION['user_type'] == ADMIN) // if user is admin, they can access this page
{
	// only look for request parameters if the user is an admin
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$requestParams = $_POST;
	}
	else if ($_SERVER["REQUEST_METHOD"] == "GET")
	{
		$requestParams = $_GET;
	}
	if (isset($requestParams))
	{
		if (isset($requestParams['action']))
		{
			$action = $requestParams['action'];
			if (isset($requestParams['user_id']))
			{
				$userID = $requestParams['user_id'];
				if (0 == strcmp($action, "Enable"))
				{
					if (FALSE != pg_execute(db_connect(), "pstAdminUpdateUserType", array('c' ,$userID)))
					{
						$error .= "Succeeded Enabled";
						$f1 = true;
					}
				}
				if (0 == strcmp($action, "Disable"))
				{
					if (FALSE != pg_execute(db_connect(), "pstAdminUpdateUserType", array('d' ,$userID)))
					{
						$error .= "Succeeded disabling user";
						$f1 = true;
					}
				}
				if (isset($requestParams['offending_id']))
				{
					$offending_id = $requestParams['offending_id'];
					if (0 == strcmp($action, "Close"))
					{
						if (FALSE != pg_execute(db_connect(), "pstCloseOffence", array($offending_id, $userID)))
						{
							$error .= "Succeeded closing offence";
							$f1 = true;
						}
					}
				}
			}
		}
		if (isset($requestParams['by']))
		{
			$sortBy = trim($requestParams['by']);
			$sql .= " ORDER BY " . $sortBy . " ";
			if (isset($requestParams['dir']))
			{
				$direction = trim($requestParams['dir']);
				$sql .= $direction;
			}
		}
	}
	$result = pg_query(db_connect(), $sql);
	$rows = pg_num_rows($result);
}
else
{
	header("Location: user-login.php"); // redirect user to login if they are disabled or otherwise
}

$sort_user_id = $pageName . "?by=user_id&dir=" . $direction . "\"> " . $picType . " </a>";
$sort_offending_id = $pageName . "?by=offending_id&dir=" . $direction . "\"> " . $picType . " </a>";
$sort_offended_time = $pageName . "?by=offended_time&dir=" . $direction . "\"> " . $picType . " </a>";
$sort_status = $pageName . "?by=status&dir=" . $direction . "\"> " . $picType . " </a>";
if (0 == strcmp($direction, "DESC"))
{
	$direction = "ASC";
	$picType = "^";
}
else
{
	$direction = "DESC";
	$picType = "v";
}
if (0 == strcmp($sortBy, "user_id"))
{
	$sort_user_id = $pageName . "?by=user_id&dir=" . $direction . "\"> " . $picType . " </a>";
}
else if (0 == strcmp($sortBy, "offending_id"))
{
	$sort_offending_id = $pageName . "?by=offending_id&dir=" . $direction . "\"> " . $picType . " </a>";
}
else if (0 == strcmp($sortBy, "offended_time"))
{
	$sort_offended_time = $pageName . "?by=offended_time&dir=" . $direction . "\"> " . $picType . " </a>";
}
else
{
	$sort_status = $pageName . "?by=status&dir=" . $direction . "\"> " . $picType . " </a>";
}
echo $error;
?>
<form action="<?php echo $pageName; ?>" method="get">
<table cellpadding="0" cellspacing="0" style="width:68%;border:1px solid black">
<tr class="altRow"><th class="dottedColumnSpec"><label for="user_id" accesskey="1">user_id&nbsp;</label><a class="selectedDirection" id="user_id" href="<?php echo $sort_user_id; ?></th>
<th class="dottedColumnSpec"><label for="offending_id" accesskey="2">offending_id&nbsp;</label><a class="selectedDirection" id="offending_id" href="<?php echo $sort_offending_id; ?></th>
<th class="dottedColumnSpec"><label for="offended_time" accesskey="3">offended_time&nbsp;</label><a class="selectedDirection" id="offended_time" href="<?php echo $sort_offended_time; ?></th>
<th class="dottedColumnSpec"><label for="status" accesskey="4">status&nbsp;</label><a class="selectedDirection" id="status" href="<?php echo $sort_status; ?></th>
<th class="dottedColumnSpec"><label for="actionTitle" accesskey="5">offender-action</label><span id="actionTitle">&nbsp;</span></tr>

<?php 
for ($i=0;$i<$rows;$i++)
{
	$row = pg_fetch_row($result);
	$status = "Closed";
	if ($row[3] == 'o')
	{
		$status = "Open";
	}
	if (($i + 2) % 2)
	{
		
		echo "<tr class=\"altRow\"><td class=\"dottedColumnSpec\">&nbsp;<a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[0] . "</a></td><td class=\"dottedColumnSpec\">&nbsp;<a href=\"profile-display.php?user_id=" . $row[1] . "\">" . $row[1] . "</a></td><td class=\"dottedColumnSpec\">&nbsp;" . $row[2] . "</td><td class=\"dottedColumnSpec\">&nbsp;<a href=\"" . $pageName . "?action=Close&user_id=" . $row[0] . "&offending_id=" . $row[1] . "\">" . $status . "</a></td><td>&nbsp;<a href=\"" . $pageName . "?action=Disable&user_id=" . $row[1] . "\">Disable</a>&nbsp;|&nbsp;<a href=\"" . $pageName . "?action=Enable&user_id=" . $row[1] . "\">&nbsp;Enable</a>&nbsp;</td></tr>";
	}
	else
	{
		echo "<tr class=\"Row\"><td class=\"dottedColumnSpec\">&nbsp;<a href=\"profile-display.php?user_id=" . $row[0] . "\">" . $row[0] . "</a></td><td class=\"dottedColumnSpec\">&nbsp;<a href=\"profile-display.php?user_id=" . $row[1] . "\">" . $row[1] . "</a></td><td class=\"dottedColumnSpec\">&nbsp;" . $row[2] . "</td><td class=\"dottedColumnSpec\">&nbsp;<a href=\"" . $pageName . "?action=Close&user_id=" . $row[0] . "&offending_id=" . $row[1] . "\">" . $status . "</a></td><td>&nbsp;<a href=\"" . $pageName . "?action=Disable&user_id=" . $row[1] . "\">Disable</a>&nbsp;|&nbsp;<a href=\"" . $pageName . "?action=Enable&user_id=" . $row[1] . "\">&nbsp;Enable</a>&nbsp;</td></tr>";
	}
}
?>

</table></form>
<?php 
if ($f1)
{
	header("refresh:2; url=$pageName");
}
?>
<br />
<?php require_once ("footer.php"); ?>