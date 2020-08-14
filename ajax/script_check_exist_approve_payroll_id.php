<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";


if (isset($_POST["approve_payroll_id"])) {
	$approve_payroll_id = $_POST["approve_payroll_id"];
	$payroll_class = new Payroll;

	if ($payroll_class->existApprovePayrollId($approve_payroll_id) == 0) {
		echo "Error";
	}

	else {
		$_SESSION["print_payroll_reports_approve_id"] = $approve_payroll_id;
		echo "Success";
	}
}
else {
	header("Location:../MainForm.php");
}


?>
