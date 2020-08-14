<?php
session_start();
include "class/connect.php";
include "class/emp_information.php";
//include "class/dependent.php";

if ($_SESSION["role"] != 4) {
	
	$emp_info_class = new EmployeeInformation;
	//$dependent_class = new Dependent;
	$emp_info_class->printEmployeeListReports();


}
else {
	header("Location:MainForm.php");
}

?>