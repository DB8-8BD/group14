	<?php 

//Filename: profile-select-city.php
//Author: Group
//Date: Dec. 12, 2017
//Description: The map page
$title = "Select a city";
$banner = "Please select a city to find a date!";
$date = "Dec. 3, 2017";
$filename = "profile-select-city.php";
$description = "map page for selecting a city";
require_once ("header.php"); 
include("includes/maps.txt");
$selectedCity = 0;
$sum = 0;
$bValidCity = false;

?>

<script type="text/javascript">
	function cityToggleAll()
	{
		
		var isChecked = document.getElementById("city_toggle").checked;
		var city_checkboxes = document.getElementsByName("city[]");
		for (var i in city_checkboxes){
		
			city_checkboxes[i].checked = isChecked;
		}		
	}
	</script>
<br/>

<div class="divRight">
<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Statistics</h3>
<ul>
<?php 
$sql = "SELECT value FROM city WHERE value <> '0'";
$result = pg_query(db_connect(), $sql);
$rows = pg_num_rows($result);
for ($i=0;$i<$rows;$i++)
{
	$row = pg_fetch_row($result);
	$bResult = pg_execute(db_connect(), "pstCountProfilesByCity", array($row[0]));
	$brow = pg_fetch_row($bResult);
	if (FALSE != $brow)
	{
		echo "<li>" . getProperty("city",$row[0]) . ": " . $brow[0] . " dates</li>";
	}
}
?>
</ul>
</div>

<p><?php if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (isset($_POST['city']))
	{
		$selectedCity = sumCheckBox($_POST['city']);// remember to offset for entry 0 --> Not specified
		//setcookie('search_city', $selectedCity, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		$_SESSION['search_city'] = $selectedCity;
		echo "<b>Thank you for your selection(s)! Please click continue at the <a href=\"#bottoms\">bottom</a> of the page.</b>";
		$bValidCity = true;
	}
	else
	{
		echo "<p><b>Please select a city!</b></p>";
	}
} else if ($_SERVER["REQUEST_METHOD"] == "GET")	
{
	if (isset($_SESSION['search_city']))
	{
		$selectedCity = $_SESSION['search_city'];
	}
	if (isset($_SESSION['user_id']))
	{
		if (isset($_COOKIE['search_city']))
		{
			$selectedCity = $_COOKIE['search_city'];
		}
		else if (isset($_GET['city']))
		{
			setcookie('search_city', $selectedCity, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (isset($_GET['city']))
	{
		$selectedCity = $_GET['city'];
		$_SESSION['search_city'] = $selectedCity;
		echo "<p><b>Thank you for your selection(s)! Please click search at the <a href=\"#bottoms\">bottom</a> of the page.</b></p>";
	}
}
?></p>
<img src="img/map.gif" alt="GTA" usemap="#gtamap" />
<br/><br/><br/><br/>
<?php if ($bValidCity): ?>
<form action="profile-search.php" method="post">
<input name="city[]" type="hidden" value="<?php echo $selectedCity; ?>" />
<?php else: ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php endif; ?>
<?php echo buildCheckBox("city", $selectedCity); ?>
<?php if ($bValidCity): ?>
<p><input type="submit" value="Continue" /></p><address>&nbsp;Press continue to select additional preferences</address>
<?php else: ?>
<p><input type="submit" value="Submit" /><input type="checkbox"  id="city_toggle" onclick="cityToggleAll();" name="blafg" value="0"/>Toggle all city checkboxes&nbsp;<i>(Not compatible with Internet Explorer)</i></p><address>&nbsp;Press submit to save your selections</address>
<?php endif; ?>
</form><p><a name="bottoms">&nbsp;</a></p>
<?php require_once ("footer.php"); ?>