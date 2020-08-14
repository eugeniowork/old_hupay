<?php
session_start();
include "../class/connect.php";
include "../class/sss_loan_class.php";
include "../class/date.php";

if (isset($_POST["update_empName"]) && isset($_POST["update_dateFrom"]) && isset($_POST["update_dateTo"])
	&& isset($_POST["update_amountLoan"]) && isset($_POST["update_decution"]) && isset($_POST["update_remainingBalance"])){


	$sss_loan_class = new SSSLoan;
	$date_class = new date;


	$sssLoan_id = $_POST["update_sssLoanId"];
	$empName = $_POST["update_empName"];
	$dateFrom = $date_class->dateDefaultDb($_POST["update_dateFrom"]);
	$dateTo = $date_class->dateDefaultDb($_POST["update_dateTo"]);
	$amountLoan = $_POST["update_amountLoan"];
	$deduction = $_POST["update_decution"];
	$remainingBalance = $_POST["update_remainingBalance"];




	if ($sss_loan_class->sameSSSLoanInfo($sssLoan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance) == 1){ // $pagibig_loan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance
		$_SESSION["update_sssloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if the date from is bigger than the date to
	else if ($dateFrom >= $dateTo) {
		$_SESSION["update_sssloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	else {
		$sss_loan_class->updateSSSLoan($sssLoan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance);
		$_SESSION["update_sssloan_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The SSS Loan info of <b>$empName</b> is successfully updated.</center>";
	}


	header("Location:../sss_loan.php");

}

else {
	header("Location:../MainForm.php");
}


?>