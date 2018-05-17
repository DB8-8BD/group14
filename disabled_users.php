<?php 

//Filename: disabled_users.php
//Author: Ryan Beckett, Mathew Kostrzewa, Shreeji Patel
//Date: Jan 13th, 2018
//Description: A page for admin users that will display a preview of disabled users

$title = "Disabled Users";
$banner = "Disabled Users";
$date = "Jan 13th, 2018";
$filename = "disabled_users.php";
$description;
$cur_row = 0;

require_once ("header.php"); 

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
	$results = [];
	$requestParams = [];
	$numberOfResults = 0;
	$pageNum = 0;//no such thing as page 0
	$pageName = $_SERVER['PHP_SELF'];
	$resultsPerPage = MAX_PROFILES_PER_PAGE;
	$offset = 0;

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
		if (isset($requestParams['pg']))
		{
			if (isset($requestParams['pp']))
			{
				$resultsPerPage = $requestParams['pp'];
			}
			$pageNum = $requestParams['pg'];
		}
	}
	$sql = "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE user_type = '" . DISABLED . "' LIMIT '" . MAX_RECORDS_RETURNED . "'" ;
	$dbResults = pg_query(db_connect(), $sql);
	$numberOfResults = pg_num_rows($dbResults);
	
	// for($i = 0; $i < $numberOfResults; $i++)
	// {
		// $results = pg_fetch_row($result);
	// }
		
	echo "<p>Search returned $numberOfResults results</p>";
	$offset = $pageNum*$resultsPerPage;
	if (0==$pageNum)
	{
		$pageNum = 1;
	}
	for ($i=$offset;(0==$offset)?$i<$resultsPerPage:$i<$offset+$resultsPerPage;$i++)
	{
		$results = pg_fetch_row($dbResults, $i);
		if (isset($results))
		{
			//$innerResults=$results;
			echo "<a href=\"profile-display.php?user_id=" . $results[0] . "\">" . $results[0] . "</a><br />";
			//echo $innerResults[1] . " ";
			//echo $innerResults[2] . "</a><br />";
			//echo getProperty("city", $innerResults[3]) . "<br/>";
			echo "--------------------------------<br />";
		}
	}
	echo "<br />";
	$backwards = 0;
	if (!(0>$pageNum-10))
	{
		$backwards = $pageNum-10;
	}
	echo "<a href=\"?pg=" . $backwards . "&pp=$resultsPerPage\"><<</a> | ";
	$pagesRemaining = round($numberOfResults/$resultsPerPage)-round($offset/$resultsPerPage);
	for ($k=$offset,$l=0;$k>$backwards;$k--,$l++)
	{
		if ($l>8)
		{
			break;
		}
		$temp=$pageNum+$l-10;
		if ($temp>0)
		{
			echo "<a href=\"$pageName?pg=" . $temp . "&pp=$resultsPerPage\">" . $temp . "</a> | ";
		}
	}
	for ($j=0;(0>$pagesRemaining)?false:$j<=$pagesRemaining;$j++)
	{
		if ($j>10)
		{
			break;
		}
		$temp=$pageNum+$j-1;
		if ($j==10)
		{
			$forwards=$pagesRemaining+2;
			if ($pagesRemaining>$j+8)
			{
				$forwards=$temp;
			}
			echo "<a href=\"$pageName?pg=" . $forwards . "&pp=$resultsPerPage\">>></a>";
		}
		else
		{
			if ($temp>0)
			{
				echo "<a href=\"$pageName?pg=" . $temp . "&pp=$resultsPerPage\">" . $temp . "</a> | ";
			}
		}
	}
}
else
{
	header("Location: user-login.php"); // redirect non-logged in users to login page
}

require_once ("footer.php"); ?>