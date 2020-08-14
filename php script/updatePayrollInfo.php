<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";
include "../class/cut_off.php";
include "../class/emp_information.php";

if (isset($_POST["totalGrossIncome"]) && isset($_POST["regOT"]) && isset($_POST["rdOT"]) && isset($_POST["reg_holidayOT"])
	&& isset($_POST["special_holidayOT"]) && isset($_POST["rd_regularHolidayOT"]) && isset($_POST["rd_specialHolidayOT"])
	&& isset($_POST["tardiness"]) && isset($_POST["absences"])  && isset($_POST["sssDeduction"]) && isset($_POST["sssLoan"]) 
	&& isset($_POST["philhealthDeduction"]) && isset($_POST["pagibigContribution"]) && isset($_POST["pagibigLoan"]) && isset($_POST["cashbond"])
	&& isset($_POST["cashAdvance"]) && isset($_POST["earningsAdjustment"]) && isset($_POST["totalDeductions"]) 
	&& isset($_POST["deductionAdjustment"]) && isset($_POST["tax"]) && isset($_POST["nontaxAllowance"]) && isset($_POST["afterAdjustment"])
	&& isset($_POST["netPay"]) && isset($_POST["update_payrollEmpId"]) && isset($_POST["remarks"])){

	$cut_off_class = new CutOff;
	$payroll_class = new Payroll;
	$emp_info_class = new EmployeeInformation;

	$cutOffPeriod = $cut_off_class->getCutOffPeriodLatest();

	$emp_id = $_POST["update_payrollEmpId"];
	
	

	$totalGrossIncome = $_POST["totalGrossIncome"];
	$earningsAdjustment = $_POST["earningsAdjustment"];
	$totalDeductions = $_POST["totalDeductions"];
	$deductionAdjustment = $_POST["deductionAdjustment"];
	$tax = $_POST["tax"];
	$nontaxAllowance = $_POST["nontaxAllowance"];
	$afterAdjustment = $_POST["afterAdjustment"];
	$netPay = $_POST["netPay"];
	$final_total_deduction = $totalDeductions;//+ $tax - $deductionAdjustment;


	$adjustmentBefore = $earningsAdjustment - $deductionAdjustment;


	// additional
	$regOT = $_POST["regOT"];
	$rdOT = $_POST["rdOT"];
	$reg_holidayOT = $_POST["reg_holidayOT"];
	$special_holidayOT = $_POST["special_holidayOT"];
	$rd_regularHolidayOT = $_POST["rd_regularHolidayOT"];
	$rd_specialHolidayOT = $_POST["rd_specialHolidayOT"];
	$tardiness = $_POST["tardiness"];
	$absences = $_POST["absences"];
	$sssDeduction = $_POST["sssDeduction"];
	$sssLoan = $_POST["sssLoan"];
	$philhealthDeduction = $_POST["philhealthDeduction"];
	$pagibigContribution = $_POST["pagibigContribution"];
	$pagibigLoan = $_POST["pagibigLoan"];
	$cashbond = $_POST["cashbond"];
	$cashAdvance = $_POST["cashAdvance"];
	$remarks = $_POST["remarks"];
	$present = $_POST["present"];


	/*echo "Total Gross Income : " . $totalGrossIncome . "<br/>";
	echo "Earnings Adjustment : " . $earningsAdjustment . "<br/>";
	echo "Total Deductions : " . $totalDeductions . "<br/>";
	echo "Deductions Adjusment : " . $deductionAdjustment . "<br/>";
	echo "Tax : " . $tax . "<br/>";
	echo "Nontax Allowance : " . $nontaxAllowance . "<br/>";
	echo "After Adjustment : " . $afterAdjustment . "<br/>";
	echo "Net Pay: " . $netPay . "<br/>";*/
	//echo "Total Deductions : " . $final_total_deduction . "<br/>";
	$noChanges = $payroll_class->checkExistPayrollInfoToUpdate($emp_id,$cutOffPeriod,$regOT,$rdOT,$reg_holidayOT,$special_holidayOT,$rd_regularHolidayOT,
												$rd_specialHolidayOT,$tardiness,$absences,$sssDeduction,$sssLoan,$philhealthDeduction,$pagibigContribution,
												$pagibigLoan,$cashbond,$cashAdvance,$totalGrossIncome,$earningsAdjustment,
												$final_total_deduction,$deductionAdjustment,$tax,$nontaxAllowance,
												$afterAdjustment,$netPay,$remarks,$present);

	// if no changes
	if ($noChanges == 1){
		//$_SESSION["error_update_payroll_info"]= "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.</center>";
		echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.";
	}

	else {

		//echo "wew";
			$payroll_class->updatePayrollInfo($emp_id,$cutOffPeriod,$regOT,$rdOT,$reg_holidayOT,$special_holidayOT,$rd_regularHolidayOT,
												$rd_specialHolidayOT,$tardiness,$absences,$sssDeduction,$sssLoan,$philhealthDeduction,$pagibigContribution,
												$pagibigLoan,$cashbond,$cashAdvance,$totalGrossIncome,$earningsAdjustment,
												$final_total_deduction,$deductionAdjustment,$tax,$nontaxAllowance,
												$adjustmentBefore,$afterAdjustment,$netPay,$remarks,$present);
			
		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " ". $row_emp->Middlename;

		//$_SESSION["success_update_payroll_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Payroll Information of <b>$fullName</b> for the Cut Off Period <b>$cutOffPeriod</b> is successfully updated.</center>";
		echo "<span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Payroll Information of <b>$fullName</b> for the Cut Off Period <b>$cutOffPeriod</b> is successfully updated.";
	}

	//header("Location:../updatePayroll.php");
}
else {
	//header("Location:../MainForm.php");
	echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, please refresh the page.";
}



?>