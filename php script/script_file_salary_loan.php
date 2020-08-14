<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/money.php";
include "../class/payroll_notif_class.php";
include "../class/emp_information.php";
	
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

	$date_class = new date;
	
	
	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	$emp_id = $_SESSION["id"];
	$salary_loan_class = new SalaryLoan;

	// for saving
	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	// kapag may pending na filed salary loan
	else if ($salary_loan_class->checkExistFileSalaryLoan($emp_id) == 1){
		$_SESSION["file_error_salary_loan"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You cannot file a salary loan yet if you have a pending filed salary loan.";
	}

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
		$salary_loan_class->insertFileSalaryLoan($emp_id,$deductionType,$deductionDay,$totalMonths,$final_dateFrom,$final_dateTo,$amountLoan,$totalPayment,$deduction,$remarks,$dateCreated);

		$last_file_salary_loan_id = $salary_loan_class->lastIdFileSalaryLoan();

		$notif_type = "File Salary Loan";
		$readStatus = '0';

		
		$payroll_notif_class = new PayrollNotif;
		$emp_info_class = new EmployeeInformation;
		$emp_values = explode("#",$emp_info_class->getEmpIdRoleAdmin());
		$admin_count = $emp_info_class->getAdminCount();
		$counter = 0;
		do {
			$emp_id = $emp_values[$counter];
			//echo $emp_id . "<br/>";
			$payroll_notif_class->insertPayrollNotif('0',$emp_id,'0','0',$last_file_salary_loan_id,$notif_type,'',$readStatus,$date_class->getDateTime());
			$counter++;
		} while($admin_count > $counter);
		

		//echo $last_file_salary_loan_id;

		$_SESSION["file_success_salary_loan"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> You successfully file a salary loan for <b>$totalMonths months</b> starting from <b>$new_dateFrom - $new_dateTo</b> amounting of <b>Php $new_amountLoan $deductionType</b>.</center>";

	}

	
	header("Location:../file_salary_loan.php");
	

	//$dateCreated = $date_class->getDate();
	



}
else {
	header("Location:../MainForm.php");
}


?>