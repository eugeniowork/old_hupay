<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";

//echo $_POST["empId"];


if (isset($_POST["empName"]) && isset($_POST["empId"]) && isset($_POST["deductionTypeExist"]) && isset($_POST["totalMonthsExist"]) && isset($_POST["dateFrom"]) && isset($_POST["dateTo"]) 
	&& isset($_POST["amountLoan"]) && isset($_POST["decution"]) && isset($_POST["remainingBalance"]) && isset($_POST["total_payment"]) && isset($_POST["remarks"])) {

	$salary_loan_class = new SalaryLoan;
	$date_class = new date;

	$deductionType = $_POST["deductionTypeExist"];
	$empName = $_POST["empName"];
	$empId = $_POST["empId"];

	$totalMonths = $_POST["totalMonthsExist"];

	$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom"]);
	$dateTo = $date_class->dateDefaultDb($_POST["dateTo"]);
	$amountLoan = $_POST["amountLoan"];
	$totalPayment = $_POST["total_payment"];
	$decution = $_POST["decution"];
	$remainingBalance = $_POST["remainingBalance"];
	$remarks = $_POST["remarks"];
	$dateCreated = $date_class->getDate();

	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}


	// if remove the required attribute
	//if ($empName == "" || $empId == "" || $dateFrom == "" || $dateTo == "" || $amountLoan == "" || $decution == ""){
	//	$_SESSION["add_pagibigloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	//}

	// if the date from is bigger than the date to

	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["add_salaryloan_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	else if ($dateFrom > $dateTo) {
		$_SESSION["add_salaryloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	// this facility is for erroring when has exist salary loan
	//else if ($salary_loan_class->existSalaryLoan($empId) != 0){
	//	$_SESSION["add_salaryloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Employee $empName</b> has already an existing salary loan.</center>";
	//}

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
		//insertSalaryLoan($emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$dateCreated)
		// $emp_id,$approver_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$remainingBalance,$dateCreated
		$salary_loan_class->insertSalaryLoanManual($empId,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$decution,$remainingBalance,$remarks,$dateCreated);
		$_SESSION["add_salaryloan_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Employee $empName</b> is successfully added a <b>Salary Loan</b></center>";

	}

	header("Location:../salary_loan.php");

}

else {
	header("Location:../MainForm.php");
}



?>