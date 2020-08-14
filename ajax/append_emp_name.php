<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";


if (isset($_POST["emp_id"])){
	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;
	if ($emp_id == "1"){
		echo "1";
	}

	else if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		if ($row->Middlename == ""){
			echo $row->Lastname . ", " . $row->Firstname;
		}

		else {
			echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
		}

		

	}

	else {
		echo "Error";
	}
}

else {
	header("Location:../MainForm.php");
}

?>