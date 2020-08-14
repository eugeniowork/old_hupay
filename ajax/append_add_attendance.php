<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])){

	$emp_info_class = new EmployeeInformation;
	$emp_id = $_POST["emp_id"];

	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}
	else {
		$row = $emp_info_class->getEmpInfoByRow($emp_id);
		echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;		
	}
}
else {
	header("Location:../MainForm.php");
}


?>