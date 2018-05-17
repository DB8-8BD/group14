<?php 

//Filename: profile-images.php
//Author: Group 14
//Date: Nov 23rd, 2017
//Description: Profile images

$title = "Profile Images";
$banner = "Profile Images";
$date = "Nov 23rd, 2017";	
$filename = "profile-images.php";
$description;

require_once ("header.php"); 
$temp = "";
$name = "";
$ftype = "";
$error = "";
$f="";
$authorized = false;
$imgsArr = null;
$pgName = $_SERVER['PHP_SELF'];
if (isset($_SESSION['user_id'])) 
{
	$folder_name = $_SESSION['user_id'];
	$user_id = $_SESSION['user_id'];
	if (isset($_SESSION['user_type']))
		{
		$bLinux = is_linux();
		$user_type = $_SESSION['user_type'];
		if ($user_type === CLIENT) 
			{
				$authorized = true;
				$highest = 0;
				$result = pg_execute(db_connect(), "pstGetUserImage", array($folder_name));
				if (FALSE != $result)
				{
					$highest = pg_fetch_result($result, 0, 0);
					if (0 != $highest)
					{
						echo "<form id=\"checkboxes\" method=\"post\" action=\"$pgName\" >";
						
						for($a=1;$a<=$highest;$a++)
						{
							if (($a + 2) % 2)
							{
								echo "<input type=\"radio\" name=\"Select\" checked = \"checked\">";
								
							}
							else 
							{
								echo "<input type=\"radio\" name=\"Select\">";
							}
							
							echo "<img src=\"$folder_name/" . $user_id . "_$a.jpg\" width=\"300px\" height=\"350px\" />";
							 echo "<input type=\"checkbox\" name=\"delete[]\" value=\"$a\" checked=\"checked\" />";
							 
						}
						
						echo "<input type=\"submit\" name=\"submit\" value=\"Delete-Image\"/>";
						echo "</form>";
					}
					if ($highest >= MAX_IMAGES)
					{
						$authorized = false;
					}
					if(isset($_REQUEST['submit']))
					{
						$temp=$_REQUEST['submit'];
						if(0 == strcmp($temp, "Upload-Image"))
						{
						 $name=$_FILES['upload_file']['name'];
						 $ftype=$_FILES['upload_file']['type'];
						 $type= array("image/jpg","image/jpeg","image/pjpeg");
						 $size = $_FILES['upload_file']['size'];
						
							if(0 == strlen($name))
							 {
								$error .= "Please select file to upload.";
							 }
							 else if($size > MAX_IMAGE_SIZE)
							 {
								 $error .= "The size of the file should be less than " . MAX_IMAGE_SIZE . " bytes.";
								 $f=0;
							 }
							 else
							 {
								 if ($bLinux)
								 {
									 if (!is_dir("./".$folder_name))
									 {
										 mkdir($folder_name);
									 }
								 }
								 else
								 {
									 if (!is_dir(".\\".$folder_name))
									 {
										 mkdir($folder_name);
									 }
								 }
								 echo "<form id=\"checkboxes\" method=\"post\" action=\"$pgName\" >";
								 for($a=++$highest;$a <= MAX_IMAGES;$a++)//init $a to prefix-incremented $highest
									{
										if((0==strcmp($type[0],$ftype))||(0==strcmp($type[1],$ftype))||(0==strcmp($type[2],$ftype)))
										 {
											 $f=1;
											 if ($bLinux)
											 {
												 if (false == move_uploaded_file($_FILES['upload_file']['tmp_name'], "./$folder_name/" . $user_id ."_$a.jpg"))
												 {
													 $error .= "Image upload failed!!";
												 }
												 else if(UPLOAD_ERR_OK === $_FILES['upload_file']['error'])
												 {
													 pg_execute(db_connect(), "pstUpdateImages", array($a, $user_id));
												 }
											 }
											 else
											 {
												 if (false == move_uploaded_file($_FILES['upload_file']['tmp_name'], ".\\$folder_name\\" . $user_id ."_$a.jpg"))
												 {
													 $error .= "Image upload failed!!";
												 }
												 else if(UPLOAD_ERR_OK === $_FILES['upload_file']['error'])
												 {
													 pg_execute(db_connect(), "pstUpdateImages", array($a, $user_id));
												 }
											 }
											 
											 echo "<img src=\"$folder_name/" . $user_id . "_$a.jpg\" width='300px' height='350px' />";
											echo "<input type=\"checkbox\" name=\"delete\" value=\"yes\"/>";
											
											 break;
											 //echo "<a href=newfolder/$name target='_blank' buffer='write'> View file </a>";
										 }
									}
							 
									 if($f==0)
									 {
										 $error .= "Invalid File type.<br/>";
										 $error .= "Your file is ".$name;
									 }
							 }
							
						}
						else if (0 == strcmp($temp, "Delete-Image"))
						{
							$amount = 0;
							$numToDelete=0;
							$newHighest=0;
							if ($_SERVER["REQUEST_METHOD"] == "POST")
							{
								if (isset($_POST["delete"]))
								{
									$folderSpec = "";
									if ($bLinux)
									{
										$folderSpec = "./$folder_name/";
									}
									else
									{
										$folderSpec = ".\\$folder_name\\";
									}
									$imgsArr = $_POST["delete"];
									$numToDelete=count(array_keys($imgsArr));
									$tempfilename = "";
									for ($i=$numToDelete-1,$j=1;$i>=0;$i--,$j++)
									{
										$value = $imgsArr[$i];
										$newFileName=$folderSpec . $user_id . "_" . $value . ".jpg";
										if (!unlink($newFileName))
										{
											$error .= "Image delete failed!!";
										}
										else
										{
											$f=1;
											pg_execute(db_connect(), "pstSubtractImages", array(--$value, $user_id));
										}
										$amount=$j;
									}
									
								}
							}
							//echo "Deleted $amount file(s)";
						}
						 if(!($f==0))
						 {
							 header("refresh:0; url=$pgName");
						 }
					}
				}
			}
			else if($user_type === DISABLED)
			{
				
				header("refresh:0; url = aup.php");
			}				
			else 
			{
				header("refresh:0; url = profile-create.php");
			}
		}
 }
 else 
	{
		header("refresh:0; url = user-login.php");
	}

?>
<?php echo "<br/> <br/><h2>$error </h2>"; ?>
<br/><?php print_r($imgsArr); ?>
 <?php if ($authorized): ?>
 <p>To delete an image, do not select the first image as this is the picture showing in your profile page.<br/>
	If you do select the first image and press the delete button, all images will be deleted.</p> 
						
<form id="imageuploadform" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<h3>Select image for upload:
	<input name="upload_file" type="file" id="upload_file"  /></h3>
	<input type="submit" value="Upload-Image" name="submit">
</form> 

<?php endif; ?>
<br />
<?php require_once ("footer.php"); ?>






