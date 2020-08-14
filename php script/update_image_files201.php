<?php
session_start();
include "../class/connect.php";
include "../class/201_files_class.php";
include "../class/image_class.php";
ini_set('max_execution_time', 300);



if(isset($_FILES['201_files_pic_file']['tmp_name']) && isset($_POST["201_files_id"])){

	$files201_class = new files201_class;
	$image_class = new Image;

	$files201_id = $_POST["201_files_id"];

	$tmpFilePath = $_FILES['201_files_pic_file']['tmp_name'];
	$base_name = basename($_FILES["201_files_pic_file"]["name"]);
	$file_type = pathinfo($base_name,PATHINFO_EXTENSION);



	$row = $files201_class->getInfoById($files201_id);

	$file_name = $row->file_name;
	$file_path = $row->file_path;

	$new_file_name = substr($file_path,21);


	$path = "../img/201 Files images/";
	//$newFilePath = $path .$new_file_name;




	$file_name_Resizing = substr($new_file_name, 0, -4);

	//echo $file_name_Resizing;

	$file_path_Resizing = $path .$file_name_Resizing;

	//echo $file_path_Resizing;


	// unlink first the img
	unlink("../".$file_path);


	move_uploaded_file($tmpFilePath, $file_path_Resizing);
	$image_class->resize('1000',$file_path_Resizing,$file_path_Resizing); // for resizing


	$db_file_path = "img/201 Files images/" . $file_name_Resizing . "." . $file_type;

	// for updating the path
	$files201_class->updateFiles201FilePath($files201_id,$db_file_path);




	$_SESSION["update_files201_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Image for <b>$file_name</b> is successfully updated.</center>";

	header("Location:../view_emp_profile.php");

}

else {
	header("Location:../MainForm.php");
}





?>