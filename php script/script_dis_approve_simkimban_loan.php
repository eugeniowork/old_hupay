<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/emp_loan_class.php";	
include "../class/audit_trail_class.php";
include "../class/money.php";
include "../class/date.php";


if (isset($_POST["simkimban_id"])){
	$simkimban_id = $_POST["simkimban_id"];


	$simkimban_class = new Simkimban;
	$emp_loan_class = new EmployeeLoan;
	$money_class = new Money;
	$date_class = new date;


	$row = $simkimban_class->getInfoBySimkimbaId($simkimban_id);
	$emp_id = $row->emp_id;
	$ref_no = $row->ref_no;
	$deductionType  = $row->deductionType;
	$totalMonths = $row->totalMonths;
	$new_amountLoan = $money_class->getMoney($row->amountLoan);
	$new_dateFrom = $date_class->dateFormat($row->dateFrom);
	$new_dateTo = $date_class->dateFormat($row->dateTo);


	$row_emp = $simkimban_class->getEmpInfoByRow($emp_id);
	$empName = $row_emp->Firstname . " " . $row_emp->Lastname;

	// first we need to update the simkimban status to 1
	$simkimban_class->disApproveSimkimbanLoan($simkimban_id);


	// update din sa file loan to 1
	// approve file loan
	$emp_loan_class->disApproveFileLoan($ref_no);


	// insert audit trail
	$audit_trail_class = new AuditTrail;
	$module = "Disapprove File SIMKIMBAN Loan";
	$task_description = "Disapprove File SIMKIMBAN Loan, " . $deductionType;
	$approver_id = $_SESSION["id"];
	$dateTime = $date_class->getDateTime();
	$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);

	$_SESSION["add_simkimban_success"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully disapprove file a simkimban loan of <b>$empName</b>  for <b>$totalMonths months</b> starting from <b>$new_dateFrom - $new_dateTo</b> amounting of <b>Php $new_amountLoan $deductionType</b>.</center>";

	header("Location:../simkimban.php");
}

else {
	header("Location:../MainForm.php");
}


?>