<?PHP
/*
constants.php
Group 14
Sept 25, 2017
WEBD3201
This PHP file will define constants to be used in the other files.
*/
define ("MINIMUM_USER_ID_LENGTH", 5);
define ("MAXIMUM_USER_ID_LENGTH", 20);
define ("MINIMUM_PASSWORD_LENGTH", 6);
define ("MAXIMUM_PASSWORD_LENGTH", 9);
define ("MAX_FIRST_NAME_LENGTH", 20);
define ("MAX_LAST_NAME_LENGTH",30);
define ("MAXIMUM_EMAIL_LENGTH", 255);
define ("MAXIMUM_LOGIN_CONTENT_LENGTH", 42);
define ("MAXIMUM_HEADLINE_LENGTH", 100);
define ("MAXIMUM_DESCRIPTION_LENGTH", 1000);
define ("MINIMUM_AGE", 18);
define ("FIFTEEN_REQUIREMENTS", 15);
define ("MINIMUM_SEARCH_REQUIREMENTS", 4);
define ("MAX_INT_CHARACTER_LEN", 11); //eg. -4294967295
define ("SALT", "stNMn40NqurwQOoJU3");
define ("USER_TYPE", 'c');
define ("UNIX_EPOCH_DATE", '1970-01-01');
define ("MAX_RECORDS_RETURNED", 200);
define ("MAX_IMAGE_SIZE", 1000000);
define ("MAX_IMAGES", 4);
define ("MAX_PROFILES_PER_PAGE", 10);
//session constants
define ("SESSION_COOKIE_TIME_LIMIT", 86400 * 30);// 86400s = 1 day
//database constants
define ("DATABASE_HOST", '127.0.0.1');
define ("DATABASE_NAME", 'group14_db');
define ("DATABASE_USER", 'group14_admin');
define ("DATABASE_PASSWORD", '4ermonkeyi9o');
define ("DB_CONNECTION_STRING", "host = " . DATABASE_HOST . " dbname=" . DATABASE_NAME . " user=" . DATABASE_USER . " password=" . DATABASE_PASSWORD);

define ("ADMIN", 'a');
define ("CLIENT", 'c');
define ("INCOMPLETE", 'i');
define ("DISABLED", 'd');

?>