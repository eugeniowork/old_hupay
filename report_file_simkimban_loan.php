<?php
include "class/connect.php";
include "class/simkimban_class.php";


if (isset($_GET["simkimban_id"])){

	$simkimban_id = $_GET["simkimban_id"];
	//echo $salary_loan_id;

	// for security purpose aalamin natin kung equal ba tlga ung remaing balance sa loan amount
	$simkimban_class = new Simkimban;

	// checking muna if exist ung salary loan id
	if ($simkimban_class->checkExistSimkimbanUpdate($simkimban_id) == 0){
		header("Location:MainForm.php");
	}
	/*else if ($salary_loan_class->getInfoBySalaryLoanId($salary_loan_id)->amountLoan > $salary_loan_class->getInfoBySalaryLoanId($salary_loan_id)->remainingBalance){
		header("Location:MainForm.php");
	}*/

	else {
		$simkimban_class->printFileSimkimbanLoan($simkimban_id);
	}


}
else {
	header("Location:MainForm.php");
}

?>