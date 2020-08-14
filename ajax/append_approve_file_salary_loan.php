<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";

if(isset($_POST["file_salary_loan_id"]) && isset($_POST["approve"])) {
	$file_salary_loan_id = $_POST["file_salary_loan_id"];
	$approve = $_POST["approve"];

	$salary_loan_class = new SalaryLoan;
	// if edited in the inspect element and attendance notif id is not existed so error 
	if ($salary_loan_class->checkExistFileSalaryLoanId($file_salary_loan_id) == 0){
		echo "Error";
	}

	// if success
	else {
		echo "Success";
	} // end of else
} // end of if
else {
	header("Location:../Mainform.php");
}

?>