<?php
session_start();
include "../class/connect.php";
include "../class/sss_loan_class.php";
include "../class/date.php";

echo $_POST["empId"];


if (isset($_POST["empName"]) && isset($_POST["loan_type"]) && isset($_POST["empId"]) && isset($_POST["dateFrom"]) && isset($_POST["dateTo"]) 
	&& isset($_POST["amountLoan"]) && isset($_POST["decution"]) && isset($_POST["remainingBalance"])) {

	$sss_loan_class = new SSSLoan;
	$date_class = new date;

	$empName = $_POST["empName"];
	$loan_type = $_POST["loan_type"];
	$empId = $_POST["empId"];
	$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom"]);
	$dateTo = $date_class->dateDefaultDb($_POST["dateTo"]);
	$amountLoan = $_POST["amountLoan"];
	$decution = $_POST["decution"];
	$remainingBalance = $_POST["remainingBalance"];
	$dateCreated = $date_class->getDate();


	// if remove the required attribute
	//if ($empName == "" || $empId == "" || $dateFrom == "" || $dateTo == "" || $amountLoan == "" || $decution == ""){
	//	$_SESSION["add_pagibigloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	//}

	// if the date from is bigger than the date to
	if ($dateFrom >= $dateTo) {
		$_SESSION["add_sssloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	// this facility is for erroring when has exist salary loan
	else if ($sss_loan_class->existSSSLoan($empId,$loan_type) != 0){
		$_SESSION["add_sssloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Employee $empName</b> has already an existing salary loan.</center>";
	}

	// success so add
	else {

		//echo $empName . "<br/>";
		//echo $empId . "<br/>";
		//echo $dateFrom . "<br/>";
		//echo $dateTo . "<br/>";
		//echo $amountLoan . "<br/>";
		//echo $decution . "<br/>";
		//echo $remainingBalance . "<br/>";
		//echo $dateCreated . "<br/>";

		// ($emp_id, $dateFrom, $dateTo, $amountLoan, $deduction, $remainingBalance,$dateCreated)
		$sss_loan_class->insertSSSLoan($empId,$loan_type,$dateFrom,$dateTo,$amountLoan,$decution,$remainingBalance,$dateCreated);
		$_SESSION["add_sssloan_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Employee $empName</b> is successfully added a <b>SSS Loan</b></center>";

	}

	header("Location:../sss_loan.php");

}

else {
	header("Location:../MainForm.php");
}



?>