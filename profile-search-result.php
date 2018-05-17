<?php
// Filename: profile-search-result.php
// Date: Dec 9th, 2017
// Author: Group 14
// Description: shows query results
//

date_default_timezone_set('America/Toronto');
$title = "search results";
$banner = "Search Results";
$date = "Sept 27, 2017";
$filename = "profile-search-result.php";
$description = "Search results of other user's profiles on Mad Love Dating.";
$currentHttpTime = gmdate("M d Y H:i:s");
header("Last-Modified: ".$currentHttpTime);// append last-modified header so browsers always think they have the latest information
include ("header.php");
$results = [];
$requestParams = [];
$numberOfResults = 0;
$pageNum = 0;//no such thing as page 0
$pageName = $_SERVER['PHP_SELF'];
$resultsPerPage = MAX_PROFILES_PER_PAGE;
$offset = 0;
?>



<?php
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
if (isset($_SESSION['search_results']))
{
	$results = $_SESSION['search_results'];
	$numberOfResults = count(array_keys($results));
	echo "<p>Search returned $numberOfResults results</p>";
	$offset = $pageNum*$resultsPerPage;
	if (0==$pageNum)
	{
		$pageNum = 1;
	}
	for ($i=$offset;(0==$offset)?$i<$resultsPerPage:$i<$offset+$resultsPerPage;$i++)
	{
		if (isset($results[$i]))
		{
			$innerResults=$results[$i];
			echo "<a href=\"profile-display.php?user_id=" . $innerResults[0] . "\">";
			echo $innerResults[1] . " ";
			echo $innerResults[2] . "</a><br />";
			echo getProperty("city", $innerResults[3]) . "<br/>";
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
?>

<form method = "get" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
	<table cellspacing="0" cellpadding="0">
<tr><td>Results per page:&nbsp;</td><td>
	<select name="pp">
		<?php if (10==$resultsPerPage): ?><option selected="selected"><?php else: ?><option><?php endif; ?>10</option>
		<?php if (25==$resultsPerPage): ?><option selected="selected"><?php else: ?><option><?php endif; ?>25</option>
		<?php if (40==$resultsPerPage): ?><option selected="selected"><?php else: ?><option><?php endif; ?>40</option>
		<?php if (50==$resultsPerPage): ?><option selected="selected"><?php else: ?><option><?php endif; ?>50</option>
	</select>
	<input type="hidden" name="pg" value="<?php echo $pageNum; ?>" />
	<input type="submit" value="Submit" /></td></tr></table>
</form>
<?php

require_once "footer.php";
?>