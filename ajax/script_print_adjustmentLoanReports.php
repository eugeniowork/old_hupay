<?php
session_start();
include "../class/connect.php";
include "../class/adjustmentLoan.php";

if (isset($_POST["adjustment_loan_id"])){
	$adjustment_loan_id = $_POST["adjustment_loan_id"];

	$adjustment_loan_class = new AdjustmentLoan;

	// if not exist
	if ($adjustment_loan_class->checkExistAdjustmentLoanId($adjustment_loan_id) == 0){
		echo "Error";
	}

	else {
		$_SESSION["reports_adjustment_loan_id"] = $adjustment_loan_id;
		//echo "Success";
	}

}

else {
	header("Location:../MainForm.php");
}


?>