<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/time_in_time_out.php";
$emp_info_class = new EmployeeInformation;
$attendance_class = new Attendance;

if (isset($_POST["create_bio_emp_id"]) && isset($_POST["created_bio_id"])){

	$created_bio_id = $_POST["created_bio_id"];
	$create_bio_emp_id = $_POST["create_bio_emp_id"];

	/*echo $created_bio_id . "<br/>";
	echo $create_bio_emp_id;
	*/

	// if bio id = 0 , dapat d magsave
	if ($created_bio_id == 0){
		$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Biometrics ID '0'</b> is not a valid <b>Biometrics ID</b>.</center>";
	}

	// if no changes were made
	else if ($emp_info_class->sameBioId($create_bio_emp_id,$created_bio_id) != 0){
		$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.</center>";
		
	}

	// if exist bio id in the dataabase
	else if ($emp_info_class->checkExistBioId($created_bio_id) != 0){
		$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span><b> Biometrics ID ".$created_bio_id."</b> is already assigned to other employee</center>";
	}

	 
	// if success
	else {

		$row = $emp_info_class->getEmpInfoByRow($create_bio_emp_id);

		$old_bio_id = $row->bio_id;

		$emp_info_class->createBioId($create_bio_emp_id,$created_bio_id);

		$new_bio_id = $created_bio_id;

		$attendance_class->updateAttendanceBioId($old_bio_id,$new_bio_id);


		$_SESSION["success_msg_update_basic_info"] =  "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Employee <b>Biometrics ID</b> is Successfully Updated/ Registered.</center>";
	}
	header("Location:../biometrics_info.php");


}
else {
	header("Location:../MainForm.php");
}

?>