<?php
include "class/connect.php";
include "class/salary_loan_class.php";


if (isset($_GET["salary_loan_id"])){

	$salary_loan_id = $_GET["salary_loan_id"];
	//echo $salary_loan_id;

	// for security purpose aalamin natin kung equal ba tlga ung remaing balance sa loan amount
	$salary_loan_class = new SalaryLoan;

	// checking muna if exist ung salary loan id
	if ($salary_loan_class->checkExistSalaryLoanUpdate($salary_loan_id) == 0){
		header("Location:MainForm.php");
	}
	/*else if ($salary_loan_class->getInfoBySalaryLoanId($salary_loan_id)->amountLoan > $salary_loan_class->getInfoBySalaryLoanId($salary_loan_id)->remainingBalance){
		header("Location:MainForm.php");
	}*/

	else {
		$salary_loan_class->printFileSalaryLoan($salary_loan_id);
	}


}
else {
	header("Location:MainForm.php");
}

?>