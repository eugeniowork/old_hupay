<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/audit_trail_class.php";
include "../class/payroll_notif_class.php";
include "../class/emp_loan_class.php";	

if (isset($_GET["file_salary_loan_id"])) {
	$file_salary_loan_id = $_GET["file_salary_loan_id"];

	$salary_loan_class = new SalaryLoan;
	$date_class = new date;
	$emp_info_class = new EmployeeInformation;
	$emp_loan_class = new EmployeeLoan;
	
	// first check natin kugn exist
	if ($salary_loan_class->checkExistFileSalaryLoanId($file_salary_loan_id) == 0){
		$_SESSION["disapprove_file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	// if success
	else {
		$row = $salary_loan_class->getFileSalaryLoanById($file_salary_loan_id);
		$emp_id = $row->emp_id;
		$ref_no = $row->ref_no;

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		if ($row_emp->Middlenam == ""){
			$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}

		$approveStat = 2;

		$approver_id = $_SESSION["id"];


		// approve file loan
		$emp_loan_class->disApproveFileLoan($ref_no);


		$salary_loan_class->disapproveFileSalaryLoan($file_salary_loan_id,$approver_id,$approveStat,$date_class->getDate());
		//disapproveFileSalaryLoan($file_salary_loan_id,$approver_id,$approveStat,$dateApprove)


		// for audit trail
		$audit_trail_class = new AuditTrail;
		$module = "Disapprove File Salary Loan";
		$task_description = "Disapprove File Salary Loan, " . $row->deductionType;
		
		$dateTime = $date_class->getDateTime();
		$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);


		// this facility is for notifications
		$payroll_notif_class = new PayrollNotif;

		// for notif here
		$notif_type = "Disapprove Your File Salary Loan";
		$readStatus = '0';
		$payroll_notif_class->insertPayrollNotif('0',$emp_id,$approver_id,'0',$file_salary_loan_id,$notif_type,'',$readStatus,$date_class->getDateTime());

		$_SESSION["disapprove_file_success_salary_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully disapprove file a salary loan of <b>$fullName</b>.</center>";

	

	}




	header("Location:../salary_loan.php");
}

else {
	header("Location:../MainForm.php");
}


?>