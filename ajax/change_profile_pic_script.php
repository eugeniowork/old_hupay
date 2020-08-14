<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";

$emp_id = $_SESSION["id"];




$file_tmp_name = $_FILES["profile_pic_file"]["tmp_name"];
$profileImage = basename($_FILES["profile_pic_file"]["name"]);
//$profileImageName = ++$emp_last_id ."_". $profileImage;
$file_type = pathinfo($profileImage,PATHINFO_EXTENSION);
$file_size = $_FILES["profile_pic_file"]["size"];

//echo $emp_id;

//echo $file_tmp_name . "<br/>";
//echo $profileImage . "<br/>";
//echo $file_type . "<br/>";


$emp_info_class = new EmployeeInformation;
$row = $emp_info_class->getEmpInfoByRow($emp_id);



$name = $row->Firstname . "_" . $row->Lastname;



$imageName = $emp_id . "_" . $name . ".jpg";


$location = "../img/profile images/profile picture/" . $imageName; // for saving to the directory
$profilePath = "img/profile images/profile picture/" . $imageName; // for database purpose

//echo $location;
//echo $profilePath;

$emp_info_class->updateProfilePic($imageName,$profilePath,$emp_id); //update

move_uploaded_file($file_tmp_name, $location); // move sa directory

//echo $profilePath;

?>