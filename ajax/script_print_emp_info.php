<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$_SESSION["print_emp_info_id"] = $emp_id;
	}

	else { // ibig savihin error message
		echo "Error";
	}
}

else {
	header("Location:../Mainform.php");
}


?>