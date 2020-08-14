<?php
include "class/connect.php";
include "class/Payroll.php";

if (isset($_GET["approve_payroll_id"])){
	$approve_payroll_id = $_GET["approve_payroll_id"];

	// for checking if exist sa tb_payroll_approval
	$payroll_class = new Payroll;

	if ($payroll_class->existApprovePayrollId($approve_payroll_id) == 0){
		header("Location:../MainForm.php");
	}

	else {
		$row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);
		$cut_off_period = $row->CutOffPeriod;

		$payroll_class->salaryInfoReports($cut_off_period);

		//echo $cut_off_period;
	}

}
else {
	header("Location:../MainForm.php");
}


?>