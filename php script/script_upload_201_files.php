<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/image_class.php";
include "../class/201_files_class.php";
include "../class/date.php";
ini_set('max_execution_time', 300);


if(isset($_FILES['201_files_pic_file']['tmp_name']) && isset($_POST["201_files_emp_id"]) && isset($_POST["files_201_name"])){

	$image_class = new Image;
	$files201_class = new files201_class;
	$date_class = new date;

	$files_201_name = $_POST["files_201_name"];
	$emp_id = $_POST["201_files_emp_id"];
	$emp_info_class = new EmployeeInformation;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id); // for naming purposes

	$emp_fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
	if ($row_emp->Middlename == ""){
		$emp_fullName = $row_emp->Lastname . ", " . $row_emp->Firstname;
	}

	//echo $emp_fullName;


	$num_files = count($_FILES['201_files_pic_file']['name']);

	$emp_201_files_count = $files201_class->getCountEmp201Files($emp_id);

	$naming_count = 0;
	// ibig sabihin wla pa simula pa lang ng pag upload sa kanya
	if ($emp_201_files_count == 0){
		// insert lang
		$files201_class->insertFiles201Count($emp_id,$num_files);
	}
	else {
		// getting the 201 files count
		$row_files201_count = $files201_class->getCount201FilesByEmpId($emp_id);

		$files201_count = $row_files201_count->count;

		$naming_count = $files201_count;

		$new_count = $num_files + $files201_count;


		// update
		$files201_class->updateFiles201Count($emp_id,$new_count);
	}



	 // Loop through each file
	for($i=0; $i<$num_files; $i++) {

		$naming_count++; // for increminting


	  //Get the temp file path
		$tmpFilePath = $_FILES['201_files_pic_file']['tmp_name'][$i];
		$base_name = basename($_FILES["201_files_pic_file"]["name"][$i]);
		$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
		$file_name =  $emp_fullName . "_".$naming_count . "." . $file_type;
		//$file_name =  $emp_fullName . "_". $naming_count;


		//echo $file_name . "<br/>";

		
		$path = "../img/201 Files images/";
		$newFilePath = $path . $emp_fullName . "_". $naming_count;



		move_uploaded_file($tmpFilePath, $newFilePath);

		// for inserting to database
		$db_path = "img/201 Files images/" . $file_name;

		$files201_class->insert201FilesImage($emp_id,$files_201_name,$file_name,$db_path,$date_class->getDate());

        
		$image_class->resize('1000',$newFilePath,$newFilePath); // for resizing



		//echo $file_type;

	}

	$_SESSION["success_msg_upload_201_files"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>Uploading 201 Files Images</b> for Employee <b>$emp_fullName</b> is successfully uploaded.</center>";
	header("Location:../employee_list.php");
}

else {
	header("Location:../MainForm.php");
}

 /*echo $num_files;

$file_tmp_name = $_FILES["201_files_pic_file"]["tmp_name"];
$base_name = basename($_FILES["201_files_pic_file"]["name"]);
$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
$file_name = "image_files" . "." . $file_type;


// if the uploaded files is not dat
if ($file_type != "png" && $file_type != "jpeg" && $file_type != "jpg"){
	//$_SESSION["attendance_upload_error"] = "Please upload only dat files";			
	//header("Location:../attendance_upload.php");
	echo "Please upload only image files";
}

// if success
else {
	// uploading
	//$path = "../dat files/";
	//$location = $path . $final_final_name;
	//move_uploaded_file($file_tmp_name,$location);
	echo $base_name . "<br/>";
	echo $file_name;
	echo "success";
}*/


?>