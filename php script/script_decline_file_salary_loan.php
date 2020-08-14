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

	$approveStat = 4; // 3 means accept

	// for updating file salary loan accepting
	$salary_loan_class->acceptFileSalaryLoan($file_salary_loan_id,$approveStat,'0000-00-00');


	// for audit trail
	$audit_trail_class = new AuditTrail;
	$module = "Decline File Salary Loan";
	$task_description = "Decline File Salary Loan, " . $row->deductionType;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class->insertAuditTrail('0','0',$emp_id,$module,$task_description,$dateTime);

	$_SESSION["accept_file_success_salary_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully decline your file a salary loan of <b>File Salary Loan</b>.</center>";

	header("Location:../salary_loan.php");
}

else {
	header("Location:../MainForm.php");
}






?>