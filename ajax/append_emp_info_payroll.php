<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/role.php";
include "../class/department.php";
include "../class/position_class.php";
include "../class/attendance_overtime.php";
include "../class/allowance_class.php";
include "../class/SSS_Contribution.php";
include "../class/Philhealth_Contribution.php";
include "../class/Pagibig_Contribution.php";
include "../class/time_in_time_out.php";

include "../class/date.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];
	//$_SESSION["pay"] = $emp_id; 

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	$department_class = new Department;
	$atttendance_ot_class = new Attendance_Overtime;
	$atttendance_class = new Attendance;
	$allowance_class = new Allowance;
	$sss_contribution_class = new SSS_Contribution;
	$philhealth_contribution_class = new Philhealth_Contribution;
	$pagibig_contribution_class = new Pagibig_Contribution;

	// if exist
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$bio_id = $row->bio_id;

		$employeeName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
		$department = $department_class->getDepartmentValue($row->dept_id)->Department;

		$basicSalary = "Php " . ($row->Salary / 2);

		$daily_rate =  $row->Salary / 26;
		$hourly_rate = $daily_rate / 8;
		$allowance = round((($allowance_class->getAllowanceInfoByEmpId($emp_id)) / 2),2);

		$allowance_tardy = (($allowance_class->getAllowanceInfoToPayslip($emp_id)) / 2);

		$allowance_rate = (($allowance_tardy*2) /26)/8;


		if ($atttendance_ot_class->getOvertimeRegularOt($emp_id) == 0){
			$regular_ot = 0;
			$final_regular_ot = "Php 0";
		}
		else {
			$regular_ot = round((($hourly_rate + ($hourly_rate * .25))/60) * $atttendance_ot_class->getOvertimeRegularOt($emp_id),2);
			$final_regular_ot = "Php " . $regular_ot;
		}


		// getOvertimeHolidayOt
		if ($atttendance_ot_class->getOvertimeHolidayOt($emp_id) == 0){
			$holiday_ot = 0;
			$final_holiday_ot = "Php 0";
		}
		else {
			$holiday_ot = round((($hourly_rate/60) * $atttendance_ot_class->getOvertimeHolidayOt($emp_id))*2,2);
			$final_holiday_ot = "Php " . $holiday_ot;
		}


		// getOvertimeHolidayOt
		if ($atttendance_ot_class->getOvertimeRestdayOt($emp_id) == 0){
			$restday_ot= 0;
			$final_restday_ot = "Php 0";
		}
		else {
			$restday_ot = round((((($daily_rate * .3) + $daily_rate)/480) * $atttendance_ot_class->getOvertimeRestdayOt($emp_id)),2);
			$final_restday_ot = "Php " . $restday_ot;
		}

		// getOvertimeRestdayHolidayOt
		if ($atttendance_ot_class->getOvertimeRestdayHolidayOt($emp_id) == 0){
			$restdayHoliday_ot = 0;
			$final_restdayHoliday_ot = "Php 0";
		}
		else {
			$restdayHoliday_ot = round((((($daily_rate * .3) + $daily_rate)/480) * $atttendance_ot_class->getOvertimeRestdayHolidayOt($emp_id))*2,2);
			$final_restdayHoliday_ot = "Php " . $restdayHoliday_ot;
		}

		// getTardiness
		if ($atttendance_class->getTardiness($bio_id) == 0){
			$tardiness = 0;
			$final_tardiness = "Php 0";
		}
		else {
			$tardiness = round((($hourly_rate + $allowance_rate) / 60 ) * $atttendance_class->getTardiness($bio_id),2);
			$final_tardiness = "Php " . $tardiness;
		}

		
		// for absent
		if ($atttendance_class->getAbsences($bio_id) == 0){
			$absent = 0;
			$final_absent = "Php 0";
		}
		else {
			$absent = round(($atttendance_class->getAbsences($bio_id) * ($daily_rate + ($allowance_rate * 8))),2);
			$final_absent = "Php " .$absent ;
		}
		

		if ($allowance_class->existAllowance($emp_id) == 0) {
			$allowance = 0;
			$final_allowance = "Php 0";
		}
		else {
			$final_allowance = "Php " . $allowance;
		}

	
		


		$totalGrossIncome = (($row->Salary / 2) + $regular_ot + $holiday_ot + $restday_ot + $restdayHoliday_ot) - ($tardiness + $absent);
		$final_totalGrossIncome = "Php " . $totalGrossIncome;


		if ($row->SSS_No == ""){
			$final_sssContribution = "Php 0";
		}
		else {
			$sssContribution = $sss_contribution_class->getContribution($row->Salary);
			$final_sssContribution = "Php " . $sssContribution; // philhealth_contribution_class
		}


		if ($row->PhilhealthNo == ""){
			$final_philhealthContribution = "Php 0";
		}
		else {
			$philhealthContribution = $philhealth_contribution_class->getContribution($row->Salary);
			$final_philhealthContribution = "Php " . $philhealthContribution; 
		}

		if ($row->PagibigNo == ""){
			$final_pagibigContribution = "Php 0";
		}
		else {
			$pagibigContribution = $pagibig_contribution_class->getContribution($row->Salary);
			$final_pagibigContribution = "Php " . $pagibigContribution; 
		}

		// for cash advance
		$cashBond = "Php " . round((($row->Salary + $allowance_class->getAllowanceInfoToPayslip($emp_id)) *.02)/2,2);



		$values = $emp_id. "#".$employeeName . "#" . $department ."#". $basicSalary ."#"
				  . $final_regular_ot . "#" . $final_holiday_ot . "#" .$final_restday_ot . "#" 
				   .$final_restdayHoliday_ot . "#" . $final_tardiness . "#" .$final_absent . "#"
				   . $final_allowance. "#" . $final_totalGrossIncome . "#" . $final_sssContribution . "#" 
				   . $final_philhealthContribution . "#" . $final_pagibigContribution . "#" .$cashBond;
	
		echo $values;




	}
	else { // ibig savihin error message
		echo "Error";
	}
}
else {
	header("Location:../Mainform.php");
}

?>