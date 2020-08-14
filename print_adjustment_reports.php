<?php
session_start();
include "class/connect.php";
include "class/Payroll.php";
include "class/emp_information.php";


if (isset($_SESSION["cut_off_period_adjustment_report"])) {
	$cutOffPeriod = $_SESSION["cut_off_period_adjustment_report"];

	//$connect_class = new Connect_db;
	$payroll_class = new Payroll;
	//$emp_info_class = new EmployeeInformation;
	
	//$connect = $connect_class->connect();
	$payroll_class->adjustmentPayrollReports($cutOffPeriod);
	
	
}

else {
	header("Location:MainForm.php");
}



?>