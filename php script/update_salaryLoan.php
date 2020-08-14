<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";

if (isset($_POST["update_empName"]) && isset($_POST["update_dateFrom"]) && isset($_POST["deductionTypeExist"]) && isset($_POST["totalMonthsExist"])
	&&isset($_POST["update_dateTo"])
	&& isset($_POST["update_amountLoan"]) && isset($_POST["update_decution"]) && isset($_POST["update_remainingBalance"])
	&& isset($_POST["remarks"])){


	$salary_loan_class = new SalaryLoan;
	$date_class = new date;


	$deductionType = $_POST["deductionTypeExist"];
	$totalMonths = $_POST["totalMonthsExist"];
	$salaryLoan_id = $_POST["update_salaryLoanId"];
	$empName = $_POST["update_empName"];
	$dateFrom = $date_class->dateDefaultDb($_POST["update_dateFrom"]);
	$dateTo = $date_class->dateDefaultDb($_POST["update_dateTo"]);
	$amountLoan = $_POST["update_amountLoan"];
	$deduction = $_POST["update_decution"];
	$remainingBalance = $_POST["update_remainingBalance"];
	$remarks = $_POST["remarks"];

	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	//echo $deductionType;
	//echo "<br/>";
	//echo $deductionDay;
	//echo "<br/>";
	//echo $totalMonths;
	//echo "<br/>";


	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["update_salaryloan_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	else if ($salary_loan_class->sameSalaryLoanInfo($salaryLoan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$remarks) == 1){ // $pagibig_loan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance
		$_SESSION["update_salaryloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if the date from is bigger than the date to
	else if ($dateFrom >= $dateTo) {
		$_SESSION["update_salaryloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	else {
		$salary_loan_class->updateSalaryLoan($salaryLoan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$remarks);
		$_SESSION["update_salary_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Salary Loan info of <b>$empName</b> is successfully updated.</center>";
	}


	header("Location:../salary_loan.php");

}

else {
	header("Location:../MainForm.php");
}


?>