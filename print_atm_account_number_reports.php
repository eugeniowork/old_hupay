<?php
session_start();
include "class/connect.php";
include "class/emp_information.php";


if ($_SESSION["role"] != 4){
	$emp_info_class = new EmployeeInformation;

	$emp_info_class->printAtmAccountNoReports();
}
else {
	header("Location:MainForm.php");
}

?>