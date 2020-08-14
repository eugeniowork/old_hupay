<?php
session_start();
ini_set('max_execution_time', 300);

include "../class/connect.php";
include "../class/Payroll.php";
include "../class/pagibig_loan_class.php";
include "../class/sss_loan_class.php";
include "../class/salary_loan_class.php";
include "../class/simkimban_class.php";
include "../class/year_total_deduction_class.php";
include "../class/date.php";
include "../class/cashbond_class.php";
include "../class/emp_information.php";
include "../class/payroll_notif_class.php";
include "../class/cut_off.php";
include "../class/audit_trail_class.php";
include "../class/leave_class.php";
include "../class/allowance_class.php";


if (isset($_GET["approve_payroll_id"]) && isset($_GET["approve"])) {
	$approve_payroll_id = $_GET["approve_payroll_id"];
	$approve = $_GET["approve"];

	//echo $approve_payroll_id . "<br/>";
	//echo $approve;

	$payroll_class = new Payroll;
	$date_class = new date;

	$current_date = $date_class->getDate();

	// ibig sabihin approve
	if ($approve == "Approve") {
		$payroll_class->preAppovePayroll($approve_payroll_id,$current_date);

		// for updating table payrol info
		/*$row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);

		$cutOffPeriod = $row->CutOffPeriod;

		$payroll_class->approveInfoPayroll($cutOffPeriod);
		// for loans
		$pagibig_loan_class = new PagibigLoan;
		$pagibig_loan_class->deductPagibigLoan();


		$sss_loan_class = new SSSLoan;
		$sss_loan_class->deductionSSSLoan();

		$salary_loan_class = new SalaryLoan;
		$salary_loan_class->deductSalaryLoan($cutOffPeriod);

		
		$simkimban_class = new Simkimban;
		$simkimban_class->deductSimkimban($cutOffPeriod);


		// for ytd
		$ytd_class = new YearTotalDeduction;
		$ytd_class->addYTDcurrentYear($cutOffPeriod);

		
		
		$cashbond_class = new Cashbond;
		$cashbond_class->insertEmpCashbondHistory($current_date); // for inserting to employee cashbond history
		//$cashbond_class->addCashbondTotalValue(); // for updating new cashbond total value

		
		// for deducting leave count
		$leave_class = new Leave;
		$leave_class->deductLeaveCount();



		// for adding to payslip allowance
		$allowance_class = new Allowance;
		$allowance_class->insertPayslipAllowance($cutOffPeriod,$current_date);*/
		


		// for adding total cashbond with interest


		
		
		$_SESSION["preapprove_payroll_success"] = "success";


		$cut_off_class = new CutOff;
		$cutOffPeriod = $cut_off_class->getCutOffPeriodLatest();



		// FOR NOTIFICATION
		// first check all count of active employee
		$emp_info_class = new EmployeeInformation;
		//$emp_count = $emp_info_class->getCountActiveEmp();

		/*$payroll_notif_class = new PayrollNotif; // for payroll notifications

		$notfi_emp_id = explode("#",$emp_info_class->getEmpIdAllActiveEmp());
		$emp_id = $_SESSION["id"];
		$notifType = "Already Computed";
		$emp_counter = 0;
		do {

			// for getting the employee information , dapat d mabigyan ng notification ung mga empleyadong wlang bio_id
			$row = $emp_info_class->getEmpInfoByRow($notfi_emp_id[$emp_counter]);

			//if ($row->bio_id != 0){

				$payroll_admin_id = $payroll_notif_class->getPayrollAdminIdByApprovePayrollId($approve_payroll_id);

				// for selecting payroll info by emp id at current cut off
				$row_payroll_info = $payroll_class->getPayrollInfoByEmpIdCutOffPeriod($notfi_emp_id[$emp_counter],$cutOffPeriod);
				$readStatus = '0';

				//echo . 
				//echo $attendance_notifications_class->existUploadAttendanceNotif($notfi_emp_id[$emp_counter],$emp_id,$notifType);
				// check if exist so nothing will be done
				//if ($attendance_notifications_class->existUploadAttendanceNotif($notfi_emp_id[$emp_counter],$emp_id,$notifType) == 0){
				$payroll_notif_class->insertPayrollNotif($payroll_admin_id,$notfi_emp_id[$emp_counter],$row_payroll_info->payroll_id,'0','0',$notifType,$cutOffPeriod,$readStatus,$date_class->getDateTime());
				//}
			//}

			$emp_counter++;
		}while($emp_counter < $emp_count);*/


		// for audit trail
		$audit_trail_class = new AuditTrail;
		$module = "Pre approve Payroll Reports";
		$task_description = "Pre approve Payroll Reports, " . $cutOffPeriod;
		$dateTime = $date_class->getDateTime();

		$audit_trail_class->insertAuditTrail('0','0',$_SESSION["id"],$module,$task_description,$dateTime);
		
	}

	else {
		/*$payroll_class->disappovePayroll($approve_payroll_id,$current_date);

		// for updating table payrol info
		$row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);

		$cutOffPeriod = $row->CutOffPeriod;

		$payroll_class->disapproveInfoPayroll($cutOffPeriod);

		$_SESSION["approve_payroll_failed"] = "failed";*/
	}
	//header("Location:../payroll_approval.php");
	header("Location:../payroll_reports.php");
}
else {
	header("Location:../MainForm.php");
}


?>