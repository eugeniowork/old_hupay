<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

$salary_loan_class = new SalaryLoan;
$date_class = new date;

$emp_id = $_SESSION["id"];

$file_salary_loan_id = $salary_loan_class->fileSalaryLoanLastId($emp_id);

// for security purposes
if ($salary_loan_class->checkExistAcceptFileSalaryLoan($emp_id) == 1){

	$row = $salary_loan_class->getFileSalaryLoanById($file_salary_loan_id);


	$approver_id = $row->approver_id;
	$deductionType = $row->deductionType;
	$deductionDay = $row->deductionDay;
	$totalMonths = $row->totalMonths;
	$dateFrom = $row->dateFrom;
	$dateTo = $row->dateTo;
	$amountLoan = $row->amountLoan;
	$totalPayment = $row->totalPayment;
	$deduction = $row->deduction;
	$remainingBalance = $totalPayment;
	$dateCreated = $date_class->getDate();


	$approveStat = 3; // 3 means accept

	// for updating file salary loan accepting
	$salary_loan_class->acceptFileSalaryLoan($file_salary_loan_id,$approveStat,$date_class->getDate());

	$salary_loan_class->insertSalaryLoan($emp_id,$approver_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$remainingBalance,$dateCreated);

	// for audit trail
	$audit_trail_class = new AuditTrail;
	$module = "Accept File Salary Loan";
	$task_description = "Accept File Salary Loan, " . $row->deductionType;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class->insertAuditTrail('0','0',$emp_id,$module,$task_description,$dateTime);

	$_SESSION["accept_file_success_salary_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully accept your file a salary loan of <b>File Salary Loan</b>.</center>";

	header("Location:../salary_loan.php");
}

else {
	header("Location:../MainForm.php");
}






?>