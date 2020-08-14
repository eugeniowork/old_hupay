<?php
session_start();
include "class/connect.php";
include "class/adjustmentLoan.php";

if (isset($_SESSION["reports_adjustment_loan_id"])){
	
	$adjustment_loan_id = $_SESSION["reports_adjustment_loan_id"];	

	$adjustment_loan_class = new AdjustmentLoan;
	
	$adjustment_loan_class->SimkimbanAdjustmentReports($adjustment_loan_id);
}

else {
	header("Location:MainForm.php");
}

?>