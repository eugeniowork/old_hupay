<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
ini_set('max_execution_time', 300);


if (isset($_POST["generated_code"]) && isset($_POST["emp_id"])){

	$generated_code = $_POST["generated_code"];
	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id); // for naming purposes

	$emp_fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
	if ($row_emp->Middlename == ""){
		$emp_fullName = $row_emp->Lastname . ", " . $row_emp->Firstname;
	}

	$emp_info_class->updateGeneratedCode($emp_id,$generated_code);

	$_SESSION["success_msg_upload_201_files"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>Generated Code</b> for Employee <b>$emp_fullName</b> is successfully saved.</center>";
	header("Location:../employee_list.php");

}
else {
	header("Location:../MainForm.php");
}



?>