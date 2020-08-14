<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/simkimban_class.php";

$emp_id = $_SESSION["id"];


$salary_loan_class = new SalaryLoan;
$simkimban_class = new Simkimban;

$has_salary_loan = $salary_loan_class->existSalaryLoan($emp_id);
$has_simkimban = $simkimban_class->existSimkimbanLoan($emp_id);

if ($has_salary_loan != 0 || $has_simkimban != 0){
	echo "exist";
}
else {
	echo "no exist";
}


?>