<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/audit_trail_class.php";
include "../class/money.php";
include "../class/payroll_notif_class.php";
include "../class/emp_loan_class.php";	

//echo $_POST["dateFromFileSalaryLoan"];


if (isset($_POST["fileSalaryLoanId"]) && isset($_POST["deductionType"])
	&& isset($_POST["dateFromMonth"]) && isset($_POST["dateFromDay"]) && isset($_POST["dateFromYear"]) && isset($_POST["totalMonths"]) && isset($_POST["dateTo"])
	&& isset($_POST["amountLoan"]) && isset($_POST["deduction"]) && isset($_POST["totalPayment"]) && isset($_POST["remarks"])){

	//$date_class = new date;
	//echo $date_class->dateDefaultDb();


	
	$fileSalaryLoanId = $_POST["fileSalaryLoanId"];
	$deductionType = $_POST["deductionType"];

	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	$totalMonths = $_POST["totalMonths"];
	$dateFrom = $_POST["dateFromMonth"] . "/" .$_POST["dateFromDay"] . "/" .$_POST["dateFromYear"];
	$dateTo = $_POST["dateTo"];
	$amountLoan = $_POST["amountLoan"];
	$totalPayment = $_POST["totalPayment"];
	$deduction = $_POST["deduction"];
	$remarks = $_POST["remarks"];

	/*echo $fileSalaryLoanId . "<br/>";
	echo $deductionType . "<br/>";
	echo $deductionDay . "<br/>";
	echo $totalMonths . "<br/>";
	echo $dateFrom . "<br/>";
	echo $dateTo . "<br/>";
	echo $amountLoan . "<br/>";
	echo $deduction . "<br/>";*/

	
	$salary_loan_class = new SalaryLoan;
	$emp_loan_class = new EmployeeLoan;

	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["approve_file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	// check pa pla natin kung 0 ung approve stat bka kasi iedit sa inspect element eh , tapos check natin kung exist file salary loan id
	else if ($salary_loan_class->checkExistFileSalaryLoanId($fileSalaryLoanId) == 0) {
		$_SESSION["approve_file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	else {

		$money_class = new Money;

		$date_class = new date;

		$dateCreated = $date_class->getDate();

		$row = $salary_loan_class->getFileSalaryLoanById($fileSalaryLoanId);
		$emp_id = $row->emp_id;
		$ref_no = $row->ref_no;
		$pre_approver_id = $row->pre_approver_id;
		$pre_approval_date = $row->pre_approval_date;

		$emp_info_class = new EmployeeInformation;
		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

		$empName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


		$final_dateFrom = $date_class->dateDefaultDb($dateFrom);
		$final_dateTo = $date_class->dateDefaultDb($dateTo);

		$approver_id = $_SESSION["id"];


		//echo $dateCreated;


		
		// if no changes was done
		//if ($salary_loan_class->sameFileSalaryLoanInfo($fileSalaryLoanId,$deductionType,$deductionDay,$totalMonths,$date_class->dateDefaultDb($dateFrom),$date_class->dateDefaultDb($dateTo),$amountLoan,$totalPayment,$deduction) == 1){
		//	echo "No changes!";	
			$salary_loan_class->insertSalaryLoan($emp_id,$approver_id,$pre_approver_id,$pre_approval_date,$deductionType,$deductionDay,$totalMonths,$final_dateFrom,$final_dateTo,$amountLoan,$totalPayment,$deduction,$totalPayment,$remarks,$dateCreated);

			// UPDATE LAST SALARY LOAN
			$last_id = $salary_loan_class->lastIdInsertSalaryLoan();
			$salary_loan_class->updateSalaryLoanReferenceNo($last_id,$ref_no);

			// approve file loan
			$emp_loan_class->approveFileLoan($ref_no);
			
			$apporveStat = 3;
			$dateApprove = $date_class->getDate();
			$salary_loan_class->approveFileSalaryLoan($fileSalaryLoanId,$approver_id,$deductionType,$deductionDay,$totalMonths,
											$final_dateFrom,$final_dateTo,$amountLoan,$totalPayment,$deduction,$apporveStat,$dateApprove);
			
			$audit_trail_class = new AuditTrail;
			$module = "Approve File Salary Loan";
			$task_description = "Approve File Salary Loan, " . $deductionType;
			
			$dateTime = $date_class->getDateTime();
			$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);

		//}
		
		/*else {
			
			//echo "With Changes";

			$apporveStat = 1;

			$dateApprove = $date_class->getDate();

			// for updating file salary loan
			$salary_loan_class->approveFileSalaryLoan($fileSalaryLoanId,$approver_id,$deductionType,$deductionDay,$totalMonths,
											$final_dateFrom,$final_dateTo,$amountLoan,$totalPayment,$deduction,$apporveStat,$dateApprove);

			
			// for audit trail
			$audit_trail_class = new AuditTrail;
			$module = "Approve File Salary Loan";
			$task_description = "Approve File Salary Loan, " . $deductionType;
			
			$dateTime = $date_class->getDateTime();
			$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);
		}*/

		$new_amountLoan = $money_class->getMoney($amountLoan);
		$new_dateFrom = $date_class->dateFormat($dateFrom);
		$new_dateTo = $date_class->dateFormat($dateTo);


		// this facility is for notifications
		$payroll_notif_class = new PayrollNotif;


		$notif_type = "Approve Your File Salary Loan";
		$readStatus = '0';
		$payroll_notif_class->insertPayrollNotif('0',$emp_id,$approver_id,'0',$fileSalaryLoanId,$notif_type,'',$readStatus,$date_class->getDateTime());

		$_SESSION["approve_file_success_salary_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully approve file a salary loan of <b>$empName</b>  for <b>$totalMonths months</b> starting from <b>$new_dateFrom - $new_dateTo</b> amounting of <b>Php $new_amountLoan $deductionType</b>.</center>";
		
	}

	header("Location:../salary_loan.php");

	


}
else {
	//header("Location:../MainForm.php");
}



?>