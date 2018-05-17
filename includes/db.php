<?php

/***  Author: Group 14
	  Date  : Sept 28
	  Modified: Nov. 25, 2017
	  Course : WEBD-3201
	  Purpose : Hold database-related functions for use throughout the website
***/

function db_connect (){
	$conn = pg_connect(DB_CONNECTION_STRING) or die ("cannot connect"); 
	
	return $conn;
	
}

// pstInsertUser used by --> user-register.php
pg_prepare(db_connect(), "pstInsertUser", "INSERT INTO users (user_id, password, user_type, email_address, first_name, last_name, birth_date, enrol_date, last_access) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)");
// qry_user_not_disabled --> header.php
//function qry_user_not_disabled(){
pg_prepare(db_connect(), "pstGetUserNotDisabled", "SELECT COUNT (*) FROM users WHERE user_id = $1 AND user_type <> '" . DISABLED . "'");
//}
// qry_user --> user-login.php 
pg_prepare(db_connect(), "pstGetUser", "SELECT user_id, password, user_type, email_address, first_name, last_name, birth_date, enrol_date, last_access FROM users WHERE user_id = $1 LIMIT 1");
// gets user id and email --> user-password-request.php
pg_prepare(db_connect(), "pstGetUserAndEmail", "SELECT user_id, password, user_type, email_address, first_name, last_name, birth_date, enrol_date, last_access FROM users WHERE user_id = $1 LIMIT 1");
//qry_user_details --> user-register.php
function qry_user_details(){
	return pg_prepare(db_connect(), "pstGetUserDetails", "SELECT first_name, last_name, email_address FROM users WHERE user_id = $1 ");
}
//pstGetUserProfileDetailsEx used by--> profile-create.php
pg_prepare(db_connect(), "pstGetUserProfileDetailsEx", "SELECT headline, self_description, match_description FROM profiles WHERE user_id = $1 LIMIT 1 ");
// qry_update_user_last_access --> user-login.php 
function qry_update_user_last_access(){
	return pg_prepare(db_connect(), "pstLastAccess", "UPDATE users SET last_access = CURRENT_DATE WHERE user_id = $1 AND user_type <> '" . DISABLED . "'");
}
// qry_user_profile --> user-login.php 
pg_prepare(db_connect(), "pstGetUserProfile", "SELECT * FROM profiles WHERE user_id = $1 LIMIT 1;");
// pstInsertProfile used by --> user-register.php
pg_prepare(db_connect(), "pstInsertProfile", "INSERT INTO profiles (user_id, headline, self_description, match_description, city, state, gender, gender_sought, images, tax_bracket, education, occupation, housing_status, vehicle_type, hobbies, sports, religion) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17)");
// pstUpdateProfile used by --> profile-create.php
pg_prepare(db_connect(), "pstUpdateProfile", "UPDATE profiles SET headline=$2, self_description=$3, match_description=$4, city=$5, state=$6, gender=$7, gender_sought=$8, images=$9, tax_bracket=$10, education=$11, occupation=$12, housing_status=$13, vehicle_type=$14, hobbies=$15, sports=$16, religion=$17 WHERE user_id=$1");
// pstUpdateUserType used by --> profile-create.php
pg_prepare(db_connect(), "pstUpdateUserType", "UPDATE users SET user_type = $1 WHERE user_id = $2 AND user_type <> '" . DISABLED . "'");
// pstUpdateUser used by --> user-update.php
pg_prepare(db_connect(), "pstUpdateUser", "UPDATE users SET first_name = $1, last_name = $2, email_address = $3, birth_date = $4 WHERE user_id = $5");
// pstUpdatePassword --> user-password-request.php, user-password-change.php
pg_prepare(db_connect(), "pstUpdatePassword", "UPDATE users SET password = $1 WHERE user_id = $2 AND user_type <> '" . DISABLED . "'");
// pstSearchProfiles --> not used
pg_prepare(db_connect(), "pstSearchProfiles", "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE gender_sought = $1 AND city = $2 AND state = $3 AND tax_bracket = $4 AND images = 0 AND hobbies = $5 AND education = $6 AND occupation = $7 AND housing_status = $8 AND vehicle_type = $9 AND sports = $10 AND religion = $11 AND users.user_type <> 'd' ORDER BY users.last_access DESC LIMIT " . MAX_RECORDS_RETURNED);
pg_prepare(db_connect(), "pstCountProfilesByCity", "SELECT COUNT(*) FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE city = $1 AND users.user_type <> '" . DISABLED . "'");
// pstGetUserImage, pstUpdateImages --> profile-images.php
pg_prepare(db_connect(), "pstGetUserImage", "SELECT images FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profiles.user_id = $1 AND users.user_type <> '" . DISABLED . "' LIMIT 1 ");
pg_prepare(db_connect(), "pstUpdateImages", "UPDATE profiles SET images = $1 WHERE user_id = $2");
//pg_prepare(db_connect(), "pstSubtractImages", "UPDATE profiles SET images = ($1::int - 1) WHERE user_id = $2");
pg_prepare(db_connect(), "pstSubtractImages", "UPDATE profiles SET images = $1 WHERE user_id = $2");
pg_prepare(db_connect(), "pstDisableUser", "UPDATE users SET user_type = '" . DISABLED . "' WHERE user_id = $1");
pg_prepare(db_connect(), "pstGetUserFullDetails", "SELECT users.email_address, users.first_name, users.last_name, users.birth_date, profiles.city, profiles.state, profiles.gender, profiles.gender_sought, profiles.images, profiles.tax_bracket, profiles.education, profiles.occupation, profiles.housing_status, profiles.vehicle_type, profiles.hobbies, profiles.sports, profiles.religion FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profiles.user_id = $1 LIMIT 1");
pg_prepare(db_connect(), "pstAdminUpdateUserType", "UPDATE users SET user_type = $1 WHERE user_id = $2");
// profile-display.php
pg_prepare(db_connect(), "pstGetProfileInterest", "SELECT prospective_id FROM interests WHERE user_id = $1 AND prospective_id = $2 LIMIT 1");
pg_prepare(db_connect(), "pstInsertInterest", "INSERT INTO interests (user_id, prospective_id, interested_time) VALUES ($1, $2, NOW());");
pg_prepare(db_connect(), "pstUpdateInterest", "UPDATE interests SET prospective_id = $1, interested_time = NOW() WHERE user_id = $2");
pg_prepare(db_connect(), "pstGetUserType", "SELECT user_type FROM users WHERE user_id = $1");
pg_prepare(db_connect(), "pstDeleteInterest", "DELETE FROM interests WHERE user_id = $1 AND prospective_id = $2");
// profile-display.php (cont'd)
pg_prepare(db_connect(), "pstGetProfileOffence", "SELECT offending_id FROM offensives WHERE user_id = $1 AND offending_id = $2 AND status = 'o' LIMIT 1");
pg_prepare(db_connect(), "pstInsertOffence", "INSERT INTO offensives (user_id, offending_id, offended_time) VALUES ($1, $2, NOW());");
pg_prepare(db_connect(), "pstUpdateOffence", "UPDATE offensives SET offending_id = $1, offended_time = NOW(), status = 'o' WHERE user_id = $2 AND offending_id = $1;");
pg_prepare(db_connect(), "pstDeleteOffence", "DELETE FROM offensives WHERE user_id = $1 AND offending_id = $2");
// interests.php
pg_prepare(db_connect(), "pstGetProfileInterests", "SELECT prospective_id, first_name, last_name FROM interests INNER JOIN users ON interests.prospective_id = users.user_id WHERE interests.user_id = $1 AND user_type <> '" . DISABLED . "' ORDER BY interested_time DESC");
pg_prepare(db_connect(), "pstGetInterestedProfiles", "SELECT interests.user_id, first_name, last_name FROM interests INNER JOIN users ON interests.user_id = users.user_id WHERE interests.prospective_id = $1 AND user_type <> '" . DISABLED . "' ORDER BY interested_time DESC");
// admin.php
pg_prepare(db_connect(), "pstGetOffencesDesc", "SELECT user_id, offending_id, offended_time, status FROM offensives ORDER BY $1 DESC");
pg_prepare(db_connect(), "pstGetOffencesAsc", "SELECT user_id, offending_id, offended_time, status FROM offensives ORDER BY $1 ASC");
pg_prepare(db_connect(), "pstCloseOffence", "UPDATE offensives SET status = 'c' WHERE user_id = $2 AND offending_id = $1;");
pg_prepare(db_connect(), "pstDisplayDisabledUsers", "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE user_type = '" . DISABLED . "' LIMIT '" . MAX_RECORDS_RETURNED . "'");
?>