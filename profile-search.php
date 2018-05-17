<?php     
// Filename: profile-search.php
// Date: Sept. 27, 2017
// Author: Group 14
// Description: Handles queries for users
//
date_default_timezone_set('America/Toronto');
$title = "profile-search";
$banner = "Search for Profiles";
$date = "Sept 27, 2017";
$filename = "profile-search.php";
$description = "Search other user's profiles on Mad Love Dating.";
require_once ("header.php");
$currentHttpTime = gmdate("M d Y H:i:s");
header("Last-Modified: ".$currentHttpTime);// append last-modified header so browsers always think they have the latest information
// if there is no city on the session, redirect to city select page
if (!(isset($_SESSION['search_city'])))
{
	header("Location: profile-select-city.php");
}
else if (0 == $_SESSION['search_city'])
{
	header("Location: profile-select-city.php");
}

// yes we could've used a prepared statement, but using a dynamic sql statement allows the database to provide us with larger datasets when no parameters are specified
$profileSearchString = "SELECT users.user_id, users.first_name, users.last_name, profiles.city, profiles.state, profiles.gender, profiles.gender_sought, profiles.images, profiles.tax_bracket, profiles.education, profiles.occupation, profiles.housing_status, profiles.vehicle_type, profiles.hobbies, profiles.sports, profiles.religion FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE gender = ";
$selectedGender = 0;
$selectedCity = 0;
$selectedState = 0;
$selectedTaxBracket = 0;
$selectedHobbies = 0;
$selectedEducation = 0;
$selectedOccupation = 0;
$selectedHousingStatus = 0;
$selectedVehicleType = 0;
$selectedSports = 0;
$selectedReligion = 0;
$requiredParams = 0;
$errorString = "";
$sqlString = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$records = 0;
	// begin tri-state http variable examination
	// check for cookies first, then session, then post
	// stored values to be overwritten as necessary by each succeeding parameter
	
	// search_gender or gender_sought is a required parameter
	if (isset($_COOKIE['search_gender']))
	{
		$selectedGender = $_COOKIE['search_gender'];
	}
	if (isset($_SESSION['gender_sought']))
	{
		$selectedGender = $_SESSION['gender_sought'];
	}
	if (isset($_POST['gender_sought']))
	{
		$selectedGender = $_POST['gender_sought'];
		$_SESSION['gender_sought'] = $selectedGender;
		$requiredParams++;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_gender', $selectedGender, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	$profileSearchString .= $selectedGender;
	
	// search_city or city is an optional parameter
	// if (isset($_COOKIE['search_city']))
	// {
		// $selectedCity = $_COOKIE['search_city'];
	// }
	// take the city value from the SESSION, since the city value is set in another page
	if (isset($_SESSION['search_city']))
	{
		$selectedCity = $_SESSION['search_city'];
	}
	// if (isset($_POST['city']))
	// {
		// $selectedCity = sumCheckBox($_POST['city']);
		// $_SESSION['search_city'] = $selectedCity;
		// if (isset($_SESSION['user_id']))
		// {
			// setcookie('search_city', $selectedCity, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		// }
	// }
	// since the city value may be set in another page, update the cookie here too
	if (0 != $selectedCity)
	{
		$profileSearchString .= " AND (" . buildQueryString("city", $selectedCity) . ")";
		$requiredParams++;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_city', $selectedCity, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	
	// search_state or state is an optional parameter
	if (isset($_COOKIE['search_state']))
	{
		$selectedState = $_COOKIE['search_state'];
	}
	if (isset($_SESSION['search_state']))
	{
		$selectedState = $_SESSION['search_state'];
	}
	if (isset($_POST['state']))
	{
		$selectedState = sumCheckBox($_POST['state']);
		$_SESSION['search_state'] = $selectedState;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_state', $selectedState, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedState)
	{
		$profileSearchString .= " AND (" . buildQueryString("state", $selectedState) . ")";
		$requiredParams++;
	}

	// search_tax_bracket or tax_bracket is an optional parameter
	if (isset($_COOKIE['search_tax_bracket']))
	{
		$selectedTaxBracket = $_COOKIE['search_tax_bracket'];
	}
	if (isset($_SESSION['search_tax_bracket']))
	{
		$selectedTaxBracket = $_SESSION['search_tax_bracket'];
	}
	if (isset($_POST['tax_bracket']))
	{
		$selectedTaxBracket = sumCheckBox($_POST['tax_bracket']);
		$_SESSION['search_tax_bracket'] = $selectedTaxBracket;		
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_tax_bracket', $selectedTaxBracket, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedTaxBracket)
	{
		$profileSearchString .= " AND (" . buildQueryString("tax_bracket", $selectedTaxBracket) . ")";
		$requiredParams++;
	}

	// search hobbies or hobbies is an optional parameter
	if (isset($_COOKIE['search_hobbies']))
	{
		$selectedHobbies = $_COOKIE['search_hobbies'];
	}
	if (isset($_SESSION['search_hobbies']))
	{
		$selectedHobbies = $_SESSION['search_hobbies'];
	}
	if (isset($_POST['hobbies']))
	{
		$selectedHobbies = sumCheckBox($_POST['hobbies']);
		$_SESSION['search_hobbies'] = $selectedHobbies;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_hobbies', $selectedHobbies, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedHobbies)
	{
		$profileSearchString .= " AND (" . buildQueryString("hobbies", $selectedHobbies) . ")";
		$requiredParams++;
	}

	// education is an optional parameter
	if (isset($_COOKIE['search_education']))
	{
		$selectedEducation = $_COOKIE['search_education'];
	}
	if (isset($_SESSION['search_education']))
	{
		$selectedEducation = $_SESSION['search_education'];
	}
	if (isset($_POST['education']))
	{
		$selectedEducation = sumCheckBox($_POST['education']);
		$_SESSION['search_education'] = $selectedEducation;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_education', $selectedEducation, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedEducation)
	{
		$profileSearchString .= " AND (" . buildQueryString("education", $selectedEducation) . ")";
		$requiredParams++;
	}

	//search_occupation is an optinoal parameter
	if (isset($_COOKIE['search_occupation']))
	{
		$selectedOccupation = $_COOKIE['search_occupation'];
	}
	if (isset($_SESSION['search_occupation']))
	{
		$selectedOccupation = $_SESSION['search_occupation'];
	}
	if (isset($_POST['occupation']))
	{
		$selectedOccupation = sumCheckBox($_POST['occupation']);
		$_SESSION['search_occupation'] = $selectedOccupation;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_occupation', $selectedOccupation, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedOccupation)
	{
		$profileSearchString .= " AND (" . buildQueryString("occupation", $selectedOccupation) . ")";
		$requiredParams++;
	}

	// search_housing_status is an optional parameter
	if (isset($_COOKIE['search_housing_status']))
	{
		$selectedHousingStatus = $_COOKIE['search_housing_status'];
	}
	if (isset($_SESSION['search_housing_status']))
	{
		$selectedHousingStatus = $_SESSION['search_housing_status'];
	}
	if (isset($_POST['housing_status']))
	{
		$selectedHousingStatus = sumCheckBox($_POST['housing_status']);
		$_SESSION['search_housing_status'] = $selectedHousingStatus;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_housing_status', $selectedHousingStatus, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedHousingStatus)
	{
		$profileSearchString .= " AND (" . buildQueryString("housing_status", $selectedHousingStatus) . ")";
		$requiredParams++;
	}

	// search_vehicle_type or vehicle_type is an optional parameter
	if (isset($_COOKIE['search_vehicle_type']))
	{
		$selectedVehicleType = $_COOKIE['search_vehicle_type'];
	}
	if (isset($_SESSION['search_vehicle_type']))
	{
		$selectedVehicleType = $_SESSION['search_vehicle_type'];
	}
	if (isset($_POST['vehicle_type']))
	{
		$selectedVehicleType = sumCheckBox($_POST['vehicle_type']);
		$_SESSION['search_vehicle_type'] = $selectedVehicleType;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_vehicle_type', $selectedVehicleType, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedVehicleType)
	{
		$profileSearchString .= " AND (" . buildQueryString("vehicle_type", $selectedVehicleType) . ")";
		$requiredParams++;
	}

	// search_sports or sports is an optional parameter
	if (isset($_COOKIE['search_sports']))
	{
		$selectedSports = $_COOKIE['search_sports'];
	}
	if (isset($_SESSION['search_sports']))
	{
		$selectedSports = $_SESSION['search_sports'];
	}
	if (isset($_POST['sports']))
	{
		$selectedSports = sumCheckBox($_POST['sports']);
		$_SESSION['search_sports'] = $selectedSports;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_sports', $selectedSports, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedSports)
	{
		$profileSearchString .= " AND (" . buildQueryString("sports", $selectedSports) . ")";
		$requiredParams++;
	}

	if (isset($_COOKIE['search_religion']))
	{
		$selectedReligion = $_COOKIE['search_religion'];
	}
	if (isset($_SESSION['search_religion']))
	{
		$selectedReligion = $_SESSION['search_religion'];
	}
	if (isset($_POST['religion']))
	{
		$selectedReligion = sumCheckBox($_POST['religion']);
		$_SESSION['search_religion'] = $selectedReligion;
		if (isset($_SESSION['user_id']))
		{
			setcookie('search_religion', $selectedReligion, time() + (SESSION_COOKIE_TIME_LIMIT), "/");
		}
	}
	if (0 != $selectedReligion)
	{
		$profileSearchString .= " AND (" . buildQueryString("religion", $selectedReligion) . ")";
		$requiredParams++;
	}

	if ($requiredParams > MINIMUM_SEARCH_REQUIREMENTS)
	{
		$profileSearchString .= " LIMIT " . MAX_RECORDS_RETURNED;
		//$sqlString = $profileSearchString;
		// actually do the search in the database
		$result = pg_query(db_connect(), $profileSearchString);
		
		$records = pg_num_rows($result);
		$resultsArray = [];
		for($i = 0; $i < $records; $i++)
		{
			for ($j=0;$j<15;$j++)
			{
				$resultsArray[$i][$j] = pg_fetch_result($result, $i, $j);
			}
		}
		
		// clear any previous search results and set the new ones
		unset($_SESSION['search_results']);
		$_SESSION['search_results'] = $resultsArray;
		
		// and when  you do put the results in the session_cache_expire
		
		//no results found
		if ($records == 0)
		{
			$_SESSION['no_results_message'] = "<h3>No results were found for the given search criteria</h3>";
		}
		//one result found
		else if ($records == 1)
		{
			header("Location: profile-display.php?user_id=" . $resultsArray[0][0]);
		}
		//multiple results found
		else
		{
			header("Location: profile-search-result.php");
		}
	}
	else
	{
		$errorString = "You must select at least 5 criteria";
	}
	
	if (isset($_SESSION['no_results_message']))
	{
		echo $_SESSION['no_results_message'];
		unset($_SESSION['no_results_message']);
	}
}
else
{
	// GET request
	if (isset($_SESSION['user_id']))
	{
		if (isset($_COOKIE['search_gender']))
		{
			$selectedGender = $_COOKIE['search_gender'];
		}
		if (isset($_COOKIE['search_city']))
		{
			$selectedCity = $_COOKIE['search_city'];
		}
		if (isset($_COOKIE['search_state']))
		{
			$selectedState = $_COOKIE['search_state'];
		}
		if (isset($_COOKIE['search_tax_bracket']))
		{
			$selectedTaxBracket = $_COOKIE['search_tax_bracket'];
		}
		if (isset($_COOKIE['search_hobbies']))
		{
			$selectedHobbies = $_COOKIE['search_hobbies'];
		}
		if (isset($_COOKIE['search_education']))
		{
			$selectedEducation = $_COOKIE['search_education'];
		}
		if (isset($_COOKIE['search_occupation']))
		{
			$selectedOccupation = $_COOKIE['search_occupation'];
		}
		if (isset($_COOKIE['search_housing_status']))
		{
			$selectedHousingStatus = $_COOKIE['search_housing_status'];
		}
		if (isset($_COOKIE['search_vehicle_type']))
		{
			$selectedVehicleType = $_COOKIE['search_vehicle_type'];
		}
		if (isset($_COOKIE['search_sports']))
		{
			$selectedSports = $_COOKIE['search_sports'];
		}
		if (isset($_COOKIE['search_religion']))
		{
			$selectedReligion = $_COOKIE['search_religion'];
		}
	}
	else
	{
		if (isset($_SESSION['gender_sought']))
		{
			$selectedGender = $_SESSION['gender_sought'];
		}
		if (isset($_SESSION['search_city']))
		{
			$selectedCity = $_SESSION['search_city'];
		}
		if (isset($_SESSION['search_state']))
		{
			$selectedState = $_SESSION['search_state'];
		}
		if (isset($_SESSION['search_tax_bracket']))
		{
			$selectedTaxBracket = $_SESSION['search_tax_bracket'];
		}
		if (isset($_SESSION['search_hobbies']))
		{
			$selectedHobbies = $_SESSION['search_hobbies'];
		}
		if (isset($_SESSION['search_education']))
		{
			$selectedEducation = $_SESSION['search_education'];
		}
		if (isset($_SESSION['search_occupation']))
		{
			$selectedOccupation = $_SESSION['search_occupation'];
		}
		if (isset($_SESSION['search_housing_status']))
		{
			$selectedHousingStatus = $_SESSION['search_housing_status'];
		}
		if (isset($_SESSION['search_vehicle_type']))
		{
			$selectedVehicleType = $_SESSION['search_vehicle_type'];
		}
		if (isset($_SESSION['search_sports']))
		{
			$selectedSports = $_SESSION['search_sports'];
		}
		if (isset($_SESSION['search_religion']))
		{
			$selectedReligion = $_SESSION['search_religion'];
		}
	}
}
?>
<?php echo $errorString; ?><?php echo $sqlString; ?><br />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<a href="profile-select-city.php"><h3>Click here to change your city selection.</h3></a> <br/><br/>
<?php
echo buildRadio("gender_sought", $selectedGender);
echo buildCheckBox("state", $selectedState);
echo buildCheckBox("tax_bracket", $selectedTaxBracket);
echo buildCheckBox("hobbies", $selectedHobbies);
echo buildCheckBox("education", $selectedEducation);
echo buildCheckBox("occupation", $selectedOccupation);
echo buildCheckBox("housing_status", $selectedHousingStatus);
echo buildCheckBox("vehicle_type", $selectedVehicleType);
echo buildCheckBox("sports", $selectedSports);
echo buildCheckBox("religion", $selectedReligion);

echo "<p>&nbsp;</p>";
?>
<div><input type="submit" value="Search" />&nbsp;|&nbsp;<input type="reset" value = "Reset" /></div>

</form><cite>&nbsp;Press Search to find matches</cite>
<!--<?php echo $sqlString; ?>-->
<?php require_once ("footer.php"); ?>