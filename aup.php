<?php
/* 
   Author    : Shreeji Patel
   Date      : September 29th 2017
   Modified by : Shreeji Patel, November 22, 2017
   Filename  : aup.php
   Course    : WEBD-3201
   Purpose   : Acceptable Usage Policy
*/  


date_default_timezone_set('America/Toronto');
$title = "Acceptable Usage Policy";
$description = "This page provides a information about MadLove's acceptable usage policy";
$date = date("F j, Y, g:i a");
$filename = "aup.php";
$banner = "Acceptable Usage Policy";
$date = "November 22, 2017";

require_once ("header.php"); 

$error = "";
if (isset($_SESSION['user_type']))
{
	$user_type = $_SESSION['user_type'];
	{
		if ($user_type === DISABLED)
		{
			$error .= "You have been disabled by the admin for your offensive behavior";
		}
	}
}
?>

<?php echo "<h2>$error </h2>"; ?>

By visiting MadLove website means you are agreeing to the terms and conditions of the "MadLove acceptable use policy":
MadLove website is a public dating website, wholly owned and incorporated by MadLove. Any commercial use, in part or in full, of MadLove content is strictly forbidden. Usage of MadLove is subject to applicable laws and regulations in the province of Ontario. Unauthorized use of this software and/or use in violation of the laws and regulations in the province of Ontario will be prosecuted to the maximum extent possible under law.
MadLove shall be used on a single computer by a single person at a time. The website is considered in use as soon as the user browses it on their computer.
MadLove pictures and content is the sole and exclusive property of MadLove . The user has only purchased the right to view it’s content according to the conditions described below:
The user agrees to use MadLove website only for the purpose for which it is intended. The user shall not modify, or attempt to modify, or disseminate or attempt to disseminate MadLove dating profile information The user shall not remove, or attempt to remove, the copyright mentions that may be displayed and/or included in MadLove website, and there are no exceptions.
MadLove website is sold without warranty with regards to performance, and fitness for any particular purpose. MadLove website shall not be held liable for damages of any nature whatsoever, including data loss or deterioration, financial or operational loss, discrepancies between information supplied and behaviour of other subscribers, in-case they behaves differently than expected.
This acceptable use policy shall not be modified by anyone without written approval from an executive officer of MadLove website . This license agreement is governed by laws in Canada, in the province of Ontario. Any disputes that may arise between the user of MadLove website and MadLove shall be settled within the jurisdiction of the courts where MadLove has its headquarters.
MadLove
3055-32 Commencement Dr.
Oshawa, ON  L1G8G3
Canada
<?php

require_once "footer.php";
?>