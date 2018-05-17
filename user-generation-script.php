<?PHP
/*
user-logout.php
Group 14
Nov 10, 2017
WEBD3201
This PHP file will be used to log a user out of their current session.
*/
date_default_timezone_set('America/Toronto');

require_once 'includes/constants.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

for ($counter = 1; $counter <= 2500; $counter++)
{
	$user_login_details = getFakeUserInfo();
	
	pg_execute(db_connect(), "pstInsertUser",
			array($user_login_details['userID'], $user_login_details['password'], $user_login_details['user type'], $user_login_details['email address'],
				  $user_login_details['first name'], $user_login_details['last name'], $user_login_details['birth date'],
				  $user_login_details['enrol date'], $user_login_details['last access']));
	
	echo "USER INFO:<br/>";
	
	echo $user_login_details['userID'];
	echo "<br/>";

	echo $user_login_details['password'];
	echo "<br/>";

	echo $user_login_details['user type'];
	echo "<br/>";
	
	echo $user_login_details['first name'];
	echo "<br/>";
	
	echo $user_login_details['last name'];
	echo "<br/>";
	
	echo $user_login_details['email address'];
	echo "<br/>";
	
	echo $user_login_details['birth date'];
	echo "<br/>";
	
	echo $user_login_details['enrol date'];
	echo "<br/>";
	
	echo $user_login_details['last access'];
	echo "<br/>";
	echo "<br/>"; 
	echo "<br/><br/>Completed generating<br/>";
	
	if ($user_login_details['user type'] == 'c' or $user_login_details['user type'] == 'a') // check if user type is 'completed' and process profile details if so
	{
		$profile_details = getFakeProfileInfo();
				
		pg_execute(db_connect(), "pstInsertProfile",
				array($user_login_details['userID'], $profile_details['headline'], $profile_details['self description'], $profile_details['match description'],
				      $profile_details['city'], $profile_details['state'], $user_login_details['gender'], $profile_details['gender sought'], 0,
					  $profile_details['tax bracket'], $profile_details['education'], $profile_details['occupation'], $profile_details['housing status'],
					  $profile_details['vehicle type'], $profile_details['hobbies'], $profile_details['sports'], $profile_details['religion']));
					  
		echo "PROFILE INFO:<br/>";
		
		echo $user_login_details['userID']; //USER ID
		echo "<br/>";
		
		echo $profile_details['headline'];
		echo "<br/>";
		
		echo $profile_details['self description'];
		echo "<br/>";
		
		echo $profile_details['match description'];
		echo "<br/>";
		
		echo $profile_details['city'];
		echo "<br/>";
		
		echo $profile_details['state'];
		echo "<br/>";
		
		echo $user_login_details['gender']; //GENDER
		echo "<br/>";
		
		echo $profile_details['gender sought'];
		echo "<br/>";
		
		echo $profile_details['tax bracket'];
		echo "<br/>";
		
		echo $profile_details['education'];
		echo "<br/>";
		
		echo $profile_details['occupation'];
		echo "<br/>";
		
		echo $profile_details['housing status'];
		echo "<br/>";
		
		echo $profile_details['vehicle type'];
		echo "<br/>";
		
		echo $profile_details['hobbies'];
		echo "<br/>";
		
		echo $profile_details['sports'];
		echo "<br/>";
		
		echo $profile_details['religion'];
		echo "<br/>";
		echo "<br/>";
		echo "<br/>";
		echo "<br/>";
	}
}

// -------------------START USER PROFILE FUNCTIONS--------------------

function getFakeProfileInfo()
{
	$profile_details = [];
	
	// $userID already exists from the user's table
	$headline = getFakeHeadline();
	$selfDescription = getFakeSelfDescription();
	$matchDescription = getFakeMatchDescription();
	$city = getFakeCity();
	$state = getFakeState();
	//$gender already exists from the user's table
	$genderSought = getFakeGenderSought();
	$taxBracket = getFakeTaxBracket();
	$education = getFakeEducation();
	$occupation = getFakeOccupation();
	$housingStatus = getFakeHousingStatus();
	$vehicleType = getFakeVehicleType();
	$hobbies = getFakeHobby();
	$sports = getFakeSport();
	$religion = getFakeReligion();
	
	$profile_details = ['headline' => $headline, 'self description' => $selfDescription, 'match description' => $matchDescription,
						'city' => $city, 'state' => $state, 'gender sought' => $genderSought, 'tax bracket' => $taxBracket,
						'education' => $education, 'occupation' => $occupation, 'housing status' => $housingStatus, 
						'vehicle type' => $vehicleType, 'hobbies' => $hobbies, 'sports' => $sports, 'religion' => $religion];
	
	return $profile_details;
}

//generate headline
function getFakeHeadline()
{
	$fakeHeadlineList = ['Your search is over', 'I am the greatest', 'Living the dream', 'Insert pickup line', 'Stop reading my headline'];
	shuffle($fakeHeadlineList);
	return $fakeHeadlineList[0];
}

//generate self description
function getFakeSelfDescription()
{
	$fakeSelfDescription = 'I like ';
	$fakeSelfDescriptionWords = ['long walks on the beach', 'turtles', 'traveling', 'eating', 'relaxing at home', 'taking care of children'];
	shuffle($fakeSelfDescriptionWords);
	$fakeSelfDescription .= $fakeSelfDescriptionWords[0] . '.';
	return $fakeSelfDescription;
}

//generate match description
function getFakeMatchDescription()
{
	$fakeMatchDescription = 'I am looking for someone to ';
	$fakeMatchDescriptionWords = ['excite me', 'calm me down', 'motivate me', 'explore new places with', 'have deep conversations with'];
	shuffle($fakeMatchDescriptionWords);
	$fakeMatchDescription .= $fakeMatchDescriptionWords[0] . '.';
	return $fakeMatchDescription;
}

//generate city
function getFakeCity()
{
	$fakeCityList = [1, 2, 4, 8, 16, 32,
	64, 128, 256, 512, 1024, 2048, 4096, 8192];
	shuffle($fakeCityList);
	return $fakeCityList[0];
}

//generate state
function getFakeState()
{
	$fakeStateList = [1, 2, 4, 8, 16, 32];
	shuffle($fakeStateList);
	return $fakeStateList[0];
}

//generate gender sought
function getFakeGenderSought()
{
	$fakeGenderSoughtList = [0, 1];
	shuffle($fakeGenderSoughtList);
	return $fakeGenderSoughtList[0];
}

//generate tax bracket
function getFakeTaxBracket()
{
	$fakeTaxBracketList = [1, 2, 4, 8, 16];
	shuffle($fakeTaxBracketList);
	return $fakeTaxBracketList[0];
}

//generate education
function getFakeEducation()
{
	$fakeEducationList = [1, 2, 4, 8, 
	16, 32];
	shuffle($fakeEducationList);
	return $fakeEducationList[0];
}

//generate occupation
function getFakeOccupation()
{
	$fakeOccupationList = [1, 2, 4, 8, 16, 32];
	shuffle($fakeOccupationList);
	return $fakeOccupationList[0];
}

//generate housing status
function getFakeHousingStatus()
{
	$fakeHousingStatusList = [1, 2, 4, 8, 16];
	shuffle($fakeHousingStatusList);
	return $fakeHousingStatusList[0];
}
//generate vehicle type
function getFakeVehicleType()
{
	$fakeVehicleTypeList = [1, 2, 4, 8, 16, 32];
	shuffle($fakeVehicleTypeList);
	return $fakeVehicleTypeList[0];
}

//generate hobby
function getFakeHobby()
{
	$fakeHobbyList = [1, 2, 4, 8, 16, 32];
	shuffle($fakeHobbyList);
	return $fakeHobbyList[0];
}

//generate sport
function getFakeSport()
{
	$fakeSportList = [1, 2, 4, 8, 16];
	shuffle($fakeSportList);
	return $fakeSportList[0];
}

//generate religion
function getFakeReligion()
{
	$fakeReligionList = [1, 2, 4, 8];
	shuffle($fakeReligionList);
	return $fakeReligionList[0];
}

// -------------------END USER PROFILE FUNCTIONS--------------------



// -------------------START USER INFO FUNCTIONS-----------------------

function getFakeUserInfo()
{
	$user_login_details = [];
	
	$gender = getFakeGender(); // ONLY USED TO DECIDE FIRST NAME GENDER. NOT TO BE PLACED INTO USER DATABASE TABLE LIKE ALL OTHER FIELDS
	
	$userID = getFakeUserID();
	$password = md5(SALT . "password");
	$userType = getFakeUserType();
	$firstName = getFakeFirstName($gender);
	$lastName = getFakeLastName();
	$emailAddress = getFakeEmail();
	$birthDate = getFakeBirthDate();
	$enrolDate = date('Y-m-d');
	$lastAccess = date('Y-m-d');
	
	$user_login_details = ['gender' => $gender, 'userID' => $userID, 'password' => $password, 'user type' => $userType, 
						  'first name' => $firstName, 'last name' => $lastName, 'email address' => $emailAddress, 
						  'birth date' => $birthDate, 'enrol date' => $enrolDate, 'last access' => $lastAccess];
	return $user_login_details;
}

// generate a random gender
function getFakeGender()
{
	$genders = [0, 1];
	// pick a random gender from the array
	
	// chose to shuffle and then get first index to pick randomly, this thread 
	// suggested that "array_rand()" function isn't as random: https://stackoverflow.com/questions/1643431/how-to-get-random-value-out-of-an-array
	shuffle($genders);
	$fakeGender = $genders[0]; 
	
	return $fakeGender;
}

// generate a random user id
function getFakeUserID()
{
	// used this stackoverflow thread for ideas on random string generation: https://stackoverflow.com/questions/4356289/php-random-string-generator
	$possibleCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($possibleCharacters);
	$randomString = '';
	$userLength = mt_rand(6, 19); // ensure user id length is also random, but still in valid range
	
	//pick a random character for to fill up the random string till it reaches desired length
	for ($counter = 0; $counter < $userLength; $counter++)
	{
		$randomString .= $possibleCharacters[mt_rand(0, $charactersLength - 1)];
	}
	
	return $randomString;
}

// generate a random user type
function getFakeUserType()
{
	$fakeUserType = '';
	// make an array for possible user types, weight it to mostly include complete users
	$userTypes = ['a', 'c', 'c', 'c', 'c', 'c', 'c', 'i', 'i', 'i'];
	
	// get a random user type from the array
	
	// chose to shuffle and then get first index to pick randomly, this thread 
	// suggested that "array_rand()" function isn't very random: https://stackoverflow.com/questions/1643431/how-to-get-random-value-out-of-an-array
	
	shuffle($userTypes);
	$fakeUserType = $userTypes[0]; 
	return $fakeUserType;
}

// generate random first name
function getFakeFirstName($gender)
{
	require ("names.php");

	$fakeFirstName = "";
	
	// check gender and get an appropriate first name from the arrays in names.php
	if ($gender == 1)
	{
		shuffle($maleNames);
		$fakeFirstName = $maleNames[0]; 
	}
	else
	{
		shuffle($femaleNames);
		$fakeFirstName = $femaleNames[0]; 
	}
	return $fakeFirstName;
}

// generate random last name
function getFakeLastName()
{
	require ("names.php");

	// pick a last name from the array in names.php
	shuffle($lastNames);
	$fakeLastName = $lastNames[0]; 	
	return $fakeLastName;
}

// generate random email
function getFakeEmail()
{
	$fakeEmail = '';
	// create array of fake emails
	$fakeEmailList = ['Email1@fake.com', 'Email2@fake.com', 'Email3@fake.com', 'Emai14@fake.com', 'Email5@fake.com', 
					  'Email6@fake.com', 'Email7@fake.com', 'Email8@fake.com', 'Email9@fake.com', 'Email10@fake.com'];
					  
	// pick random fake email
	shuffle($fakeEmailList);
	$fakeEmail = $fakeEmailList[0];
	
	return $fakeEmail;
}

//generate random birth date
function getFakeBirthDate()
{
	//help from stackoverflow thread: https://stackoverflow.com/questions/1972712/how-to-generate-random-date-between-two-dates-using-php
	$earliestDate = strtotime('1930-01-01');
	$latestDate = strtotime('1998-01-01');
	
	$randomDate = rand($earliestDate, $latestDate);
	
	return date('Y-m-d', $randomDate);
}

// -------------------END USER INFO FUNCTIONS-----------------------