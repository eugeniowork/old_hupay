<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/salary_loan_class.php"; // fixed class
include "../class/simkimban_class.php";


// for validation purpose
if (isset($_POST["check_request"])){
	$emp_id = $_SESSION["id"];

	$cashbond_class = new Cashbond;
	$salary_loan_class = new SalaryLoan;
	$simkimban_class = new Simkimban;

	$totalCashbond = $cashbond_class->getInfoByEmpId($emp_id)->totalCashbond;


	$total_salary_loan = $salary_loan_class->getAllSalaryLoan($emp_id);
	$total_simkimban_loan = $simkimban_class->getAllRemainingBalanceSimkimban($emp_id);

	$available_withdraw_cashbond = ($totalCashbond - 5000) - ($total_salary_loan + $total_simkimban_loan);

	if ($available_withdraw_cashbond < 0){
		$available_withdraw_cashbond = 0;
	}

	echo $available_withdraw_cashbond;

}
else {
	header("Location:../MainForm.php");
}



?>