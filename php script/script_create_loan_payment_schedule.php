<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/money.php";
include "../class/payroll_notif_class.php";
include "../class/emp_information.php";
include "../class/emp_loan_class.php";
	
if (isset($_POST["deductionType"]) && isset($_POST["dateFromMonth"]) && isset($_POST["dateFromDay"]) && isset($_POST["dateFromYear"]) && isset($_POST["totalMonths"]) && isset($_POST["dateTo"])
	&& isset($_POST["amountLoan"]) && isset($_POST["deduction"]) && isset($_POST["totalPayment"]) && isset($_POST["remarks"])){

	$deductionType = $_POST["deductionType"];
	$dateFrom = $_POST["dateFromMonth"] . "/" .$_POST["dateFromDay"] . "/" . $_POST["dateFromYear"];
	$totalMonths = $_POST["totalMonths"];
	$dateTo = $_POST["dateTo"];
	$amountLoan = $_POST["amountLoan"];
	$deduction = $_POST["deduction"];
	$totalPayment = $_POST["totalPayment"];
	$remarks = $_POST["remarks"];
	$file_loan_id = $_GET["file_loan_id"];

	$pre_approver_id = $_SESSION["id"];
	$pre_approve_date = date("Y-m-d");

	$date_class = new date;

	$emp_loan_class = new EmployeeLoan;

	$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);

	$emp_id = $row->emp_id;

	$ref_no = $row->ref_no;

	$row_emp = $emp_loan_class->getEmpInfoByRow($emp_id);

	
	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	//$emp_id = $_SESSION["id"];
	$salary_loan_class = new SalaryLoan;

	// for saving
	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	// kapag may pending na filed salary loan
	/*else if ($salary_loan_class->checkExistFileSalaryLoan($emp_id) == 1){
		$_SESSION["file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You cannot file a salary loan yet if you have a pending filed salary loan.";
	}*/

	// kapag ung date from is mas mababa sa date ngaun
	else if ($date_class->dateDefaultDb($dateFrom) < $date_class->getDate()){
		$new_dateFrom = $date_class->dateFormat($dateFrom);
		$dateCreated = $date_class->getDate();
		$dateCreated = $date_class->dateFormat($dateCreated);
		$_SESSION["file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From $new_dateFrom</b> must be not below the current date <b>$dateCreated</b>.";
	}

	else {
		
		
		$money_class = new Money;

		

		$dateCreated = $date_class->getDate();

		$final_dateFrom = $date_class->dateDefaultDb($dateFrom);
		$final_dateTo = $date_class->dateDefaultDb($dateTo);


		$new_dateFrom = $date_class->dateFormat($dateFrom);
		$new_dateTo = $date_class->dateFormat($dateTo);

		$new_amountLoan = $money_class->getMoney($amountLoan);

		


		//$emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$dateCreated)

		// $emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$dateCreated
		$salary_loan_class->insertFileSalaryLoan($emp_id,$pre_approver_id,$pre_approve_date,$deductionType,$deductionDay,$totalMonths,$final_dateFrom,$final_dateTo,$amountLoan,$totalPayment,$deduction,$remarks,$dateCreated);


		// aupdate natin ang ref no
		$salary_loan_class->updateReferenceNo($salary_loan_class->lastIdFileSalaryLoan(),$ref_no);

		$last_file_salary_loan_id = $salary_loan_class->lastIdFileSalaryLoan();

		$emp_loan_class->onProcesFileLoan($file_loan_id);

		$notif_type = "File Salary Loan";
		$readStatus = '0';


		//echo $last_file_salary_loan_id;

		$_SESSION["file_success_emp_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully file a salary loan for <b>".$row_emp->Firstname . " " . $row_emp->Lastname."</b> of <b>$totalMonths months</b> starting from <b>$new_dateFrom - $new_dateTo</b> amounting of <b>Php $new_amountLoan $deductionType</b>.</center>";

	}

	
	header("Location:../file_loan.php");
	

	//$dateCreated = $date_class->getDate();
	



}
else {
	header("Location:../MainForm.php");
}


?>