<?php

/***  Author: Group 14
	  Date  : Sept 28
	  Course : WEBD-3201
	  Purpose : Hold function to be used throughout the website
***/

function displayCopyrightInfo(){
	echo '&copy; Mathew Kostrzewa, Ryan Beckett, Shreeji Patel, Erik Henry   ' . date("Y");
}
function calculateAge($birth_date){
				$difference = (date('Y') - date('Y',strtotime($birth_date))); // got help from the stackoverflow
				return $difference;			
}
function isValidDate($birth_date){
	try {
		$date = new DateTime(trim($birth_date));
	}
	catch (Exception $e){
		return false;
	}
	$month = $date->format('m');
	$day = $date->format('d');
    $year = $date->format('Y');
    if( checkdate($month, $day, $year) ) {
        return true;
    }
    else {
        return false;
    }
}
// function: getPropertySELECT property FROM gender WHERE value = 0;
// description: used to get the property of a given table and a given value
// params: $selectedTable -> the table you wish to query
//			$selectedIndex -> the value you wish to query 
function getProperty($selectedTable, $selectedIndex)
{
	$output = "";
	if (is_numeric($selectedIndex))
	{
		$sql = "SELECT property FROM " . $selectedTable . " WHERE value = " . $selectedIndex . " LIMIT 1;";
		$result = pg_query(db_connect(), $sql);
		$output = pg_fetch_row($result)[0];
	}
	return $output;
}
// function: buildRadio
// description: used to build an html radio selection group
// params: $selectedTable -> the table you wish to query against
//			$selectedIndex -> the selected property in the table, as indicated by http post or session variable
function buildRadio($selectedTable, $selectedIndex)
{
	$output = "";
	$sql = "SELECT * FROM " . $selectedTable;
	$result = pg_query(db_connect(), $sql);
	$matrix = pg_fetch_all($result);
	$size = count(array_keys($matrix));
	$output .= "<fieldset id=\"rad$selectedTable\">";
	$output .= "<legend>Select a $selectedTable</legend>";
	for($i=0;$i<$size;$i++)
	{
		$line = array_values($matrix[$i]);
		if ($line[0] === $selectedIndex)
		{
			$output .= "<input name=\"$selectedTable\" type=\"radio\" value=\"$line[0]\" checked=\"checked\" />&nbsp;$line[1]&nbsp;";
		}
		else
		{
			$output .= "<input name=\"$selectedTable\" type=\"radio\" value=\"$line[0]\" />&nbsp;$line[1]&nbsp;";
		}
		if ($i==$size-1)
		{
		}
		else
		{
			$output .= "|&nbsp;";
		}
	}
	$output .= "</fieldset>";
	return $output;
}
// function: buildDropDown
// description: used to build an html option group for a select element
// params: $selectedTable -> the table you wish to query against
//			$selectedIndex -> the selected property in the table, as indicated by http post or session variable
function buildDropDown($selectedTable, $selectedIndex)
{
	$output = "";
	$sql = "SELECT * FROM " . $selectedTable;
	$result = pg_query(db_connect(), $sql);
	$matrix = pg_fetch_all($result);
	$size = count(array_keys($matrix));
	for($i=0;$i<$size;$i++)
	{
		$line = array_values($matrix[$i]);
		if ($line[0] === $selectedIndex)
		{
			$output .= "<option value=\"$line[0]\" selected>$line[1]</option>";
		}
		else
		{
			$output .= "<option value=\"$line[0]\">$line[1]</option>";
		}
	}
	return $output;
}

/*
	this function can be passed an array of numbers 
	(like those submitted as part of a named[] check 
	box array in the $_POST array).
*/
function sumCheckBox($array)
{
	$num_checks = count($array); 
	$sum = 0;
	for ($i = 0; $i < $num_checks; $i++)
	{
		if ($i>9)
		{
			break;
		}
	  $sum += $array[$i]; 
	}
	return $sum;
}
// discovers how many powers of 2 are in a number
// only discovers them in the most significant 2's complement
function getCountPowersOf2($selectedIndexes)
{
	$counter = 0;
	while ($selectedIndexes % 2 != 0)
	{
		// odd number
		$counter++;
		$selectedIndexes /= 2;
	}
	if (0 == $counter)
	{
		// even number
		while ($selectedIndexes > 1)
		{
			$selectedIndexes /= 2;
			$counter++;
		}
	}
	return $counter;
}
// used to dynamically construct an sql qquery string to query the ddatabase for profiles
function buildQueryString($selectedTable, $selectedIndex)
{
	$sql = "SELECT * FROM " . $selectedTable . " ORDER BY value DESC;";
	$result = pg_query(db_connect(), $sql);
	$records = pg_num_rows($result);
	$previousValue = $selectedIndex;
	$counter = 0;
	$outputString = $selectedTable . " = ";
	$selectedPowers = getCountPowersOf2($selectedIndex);
	$offset = $selectedIndex - $selectedPowers;
	$offsetPowers = getCountPowersOf2($offset);
	for ($i=0;$i<$records-1;$i++)
	{
		$counter = $i;
		$newValue = pg_fetch_result($result, $i, 0);
		if ($previousValue - $newValue < 0)
		{
			continue;
		}
		$previousValue -= $newValue;
		if (0 == $previousValue)
		{
			$outputString .= $newValue;
		}
		else if ($selectedIndex==$offsetPowers*$selectedPowers)
		{
			$outputString .= $newValue . " OR " . $selectedTable . " = ";
		}
		else if ($selectedIndex==$selectedPowers+$offset)
		{
			$outputString .= $newValue . " OR " . $selectedTable . " = ";
		}
		else
		{
			$outputString .= $newValue;
		}
	}
	return $outputString;
}
/*
	this function should be passed a integer power of 2, and any 
	decimal number,	it will return true (1) if the power of 2 is 
	contain as part of the decimal argument
*/
function isBitSet($power, $decimal) {
	return ($power & $decimal);
	// if($power & $decimal) 
		// return 1;
	// else
		// return 0; 
} 

// function: buildCheckBox
// description: used to build an html radio selection group
// params: $selectedTable -> the table you wish to query against
//			$selectedIndex -> the selected properties in the table, as indicated by http post or session variable
// NOTE: unlike other functions which use equality to determine whether $selectedIndex is selected, this function uses a function called isBitSet to determine if it's corresponding value is selected
// therefore, $selectedIndex is actually an array
function buildCheckBox($selectedTable, $selectedIndex)
{
	$output = "";
	$sql = "SELECT * FROM " . $selectedTable;
	$result = pg_query(db_connect(), $sql);
	$matrix = pg_fetch_all($result);
	$size = count(array_keys($matrix)); 
	$output .= "<fieldset id=\"chk$selectedTable\">";
	$output .= "<legend>Select at least one $selectedTable</legend>";
	for($i=0;$i<$size;$i++)
	{
		$line = array_values($matrix[$i]);
		if (true == isBitSet($line[0],$selectedIndex))
		{
			$output .= "<input id=\"chk" . $selectedTable . "$line[0]\" name=\"" . $selectedTable . "[]\" type=\"checkbox\" value=\"$line[0]\" checked=\"checked\" /><label for=\"chk" . $selectedTable . "$line[0]\">$line[1]</label>&nbsp;";
		}
		else
		{
			$output .= "<input id=\"chk" . $selectedTable . "$line[0]\" name=\"" . $selectedTable . "[]\" type=\"checkbox\" value=\"$line[0]\" /><label for=\"chk" . $selectedTable . "$line[0]\">$line[1]</label>&nbsp;";
		}
		if ($i!=$size-1)
		{
			$output .= "|&nbsp;";
			if (($i / 2)==3)
			{
				$output .= "<br />";
			}
		}
	}
	$output .= "</fieldset>";
	echo "<p><br/><br/></p>";
	return $output;
}
// borrowed from: https://stackoverflow.com/questions/5879043
function is_linux(){return (DIRECTORY_SEPARATOR == '/') ? true : false;}
?>