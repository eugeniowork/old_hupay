<?php
ini_set('max_execution_time', 300);
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/cut_off.php";
include "../class/department.php";
include "../class/minimum_wage_class.php";
include "../class/dependent.php";
//include "../class/BIR_Contribution.php";
include "../class/attendance_overtime.php";
include "../class/SSS_Contribution.php";
include "../class/sss_loan_class.php";
include "../class/Philhealth_Contribution.php";
include "../class/time_in_time_out.php";
include "../class/allowance_class.php";
include "../class/Pagibig_Contribution.php";
include "../class/pagibig_loan_class.php";
include "../class/simkimban_class.php";
include "../class/salary_loan_class.php";
include "../class/BIR_Contribution.php";
include "../class/year_total_deduction_class.php";
include "../class/cashbond_class.php";
include "../class/company_class.php";
include "../class/Payroll.php";
include "../class/holiday_class.php";
include "../class/working_hours_class.php";
include "../class/working_days_class.php";


if (isset($_POST["active_emp_count"])){


	$emp_info_class = new EmployeeInformation;
    

    $cut_off_class = new CutOff;
    $dept_class = new Department;
    $min_wage_class = new MinimumWage;
    $dependent_class = new Dependent;
   // $bir_class = new BIR_Contribution;
    $attendance_ot_class = new Attendance_Overtime;
    $sss_contrib_class = new SSS_Contribution;
    $sss_loan_class = new SSSLoan;
    $philhealth_contrib_class = new Philhealth_Contribution;
    $attendance_class = new Attendance;
    $allowance_class = new Allowance;
    $pagibig_contrib_class = new Pagibig_Contribution;
    $pagibig_loan_class = new PagibigLoan;
    $simkimban_class = new Simkimban;
    $salary_loan_class = new SalaryLoan;
    $bir_contrib_class = new BIR_Contribution;
    $ytd_class = new YearTotalDeduction;
    $cashbond_class = new Cashbond;
    $company_class = new Company;
    $payroll_class = new Payroll;
    $holiday_class = new Holiday;
    $working_hours_class = new WorkingHours;
    $working_days_class = new WorkingDays;

	


	$count = $_POST["active_emp_count"]; // 68
	//$count = 10; // 68
	$counter = 0; 


	// first finding the number of days in a cut off
	$cut_off_count = $cut_off_class->getCutOffAttendanceDateCount();
	$holiday_cut_off_count = $holiday_class->holidayCutOffTotalCount(); // +2
	//echo $cut_off_count;

	//echo $cut_off_count . " " . $holiday_cut_off_count;

	//$days = $cut_off_count - $holiday_cut_off_count;

	$all_emp_id = $emp_info_class->getEmpIdAllActiveEmp();
	$emp_values = explode("#",$all_emp_id);

	do {




		$emp_id = $emp_values[$counter];
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);
		$day_from = $row_wd->day_from;
		$day_to = $row_wd->day_to;

		$working_days_count = $cut_off_class->getCutOffAttendanceDateCountToPayroll($day_from,$day_to);


		$days = $working_days_count; //- $holiday_cut_off_count;

		//echo $working_days_count . " " . $holiday_cut_off_count . "<br/>";

		/*if ($emp_id == 68 || $emp_id == 85){

			$days = ($cut_off_count - $holiday_cut_off_count)+ 2; // sa susunod na ung codes pra dynamic kung ilang sabado
		}

		else {
			$days = $cut_off_count - $holiday_cut_off_count;
		}*/



		$is_increase = $emp_info_class->checkExistIncreaseCutOff($emp_id);

		//echo $is_increase;

		$gross_income_inc = 0;
		if ($is_increase == 1){
			// aalamanin natin kung ilan araw ung dapat bawasan

			// kung ilang days sa cut off
			/*$day_count = $cut_off_class->getCutOffAttendanceDateCount();


			$row_increase = $emp_info_class->getIncreaseInfoById($emp_id);

			$date_increase = date_format(date_create($row_increase->date_increase), 'Y-m-d');

			// kung ilan ung days na maaapektuhan ng increase
			$affect_days_increase = $cut_off_class->getDaysAffectIncrease($date_increase);

			// $affect_days_increase . "<br/>";

			$days_no_increase = $day_count - $affect_days_increase; // 5


			$increase_amount_day = ($row_increase->new_salary - $row_increase->old_salary) / 11; // 600


			$gross_income_inc = round($increase_amount_day * $days_no_increase,2);*/




		}


		//echo $gross_income_inc; // 272.73



		
		$row_company = $company_class->getInfoByCompanyId($row->company_id);
		$logo_source = $row_company->logo_source;
	


	//echo $emp_id;


   


    

    //$minWageEffectiveDate = "";
    //if($inCutOff == 1){
    	//$minWageEffectiveDate = $min_wage_class->getLatestEffectiveDate();
   // }

    //echo $minWageEffectiveDate . "<br/>";
	








    $row_dept = $dept_class->getDepartmentValue($row->dept_id);

    $min_wage = $min_wage_class->getMinimumWage();

    //echo $min_wage;

    // for tax code
    $taxCode = "";
    $tax = 0;
    if ($row->Salary > $min_wage) {
    	$dependentCount = $dependent_class->existDependent($emp_id);
    	$taxStatus = $bir_contrib_class->getBIRStatusToPayroll($dependentCount)->Status;

    	$civilStatus = $row->CivilStatus;

    	 if ($dependentCount == 0){
    	 	$dependentCount = "";
    	 }

    	 if ($civilStatus == "Single"){
    	 	$taxCode = "S" . $dependentCount;
    	 }

    	 else {
    	 	$taxCode = "ME" . $dependentCount;
    	 }
    }

    //$tax = 0;


    // for basic cut off pay


    /*
    $salary_allowance = 0;
    if ($min_wage >= $row->Salary) {
    	$basicCutOffPay = round((($row->Salary / 26) * 22)/2,2);
    	$salary_allowance = round(($row->Salary/2) - $basicCutOffPay,2);
    }
    else {
   		$basicCutOffPay = round($row->Salary / 2,2);
    }
    */

    
    //echo $basicCutOffPay ."<br/>";
    //echo $salary_allowance;


    // for getting regular ot by minutes
    $regularOTmin = round($attendance_ot_class->getOvertimeRegularOt($emp_id)/60,2); // 90/60 = 1.5

    //echo $regularOTmin;

    //echo $regularOTmin * 60;

    // for getting regular holiday ot by minutes
    $regHolidayOTmin = round($attendance_ot_class->getOvertimeRegularHolidayOt($emp_id)/60,2); // 90/60 = 1.5


    // for special holiday ot
    $specialHolidayOTmin = round($attendance_ot_class->getOvertimeSpecialHolidayOt($emp_id)/60,2); // 90/60 = 1.5


    // for rd regular holiday ot // getOvertimeRDRegularHolidayOt
    $rd_regularHolidayOTmin = round($attendance_ot_class->getOvertimeRDRegularHolidayOt($emp_id)/60,2); // 90/60 = 1.5


    // for rd speacial holiday ot // getOvertimeRDSpecialHolidayOt
    $rd_specialHolidayOTmin = round($attendance_ot_class->getOvertimeRDSpecialHolidayOt($emp_id)/60,2); // 90/60 = 1.5

    // for getting rd ot
    $restdayOTmin = round($attendance_ot_class->getOvertimeRestdayOt($emp_id)/60,2); // 90/60 = 1.5

    //echo $restdayOTmin . " ";

    // for getting rd holiday ot
    //$restday_holidayOTmin = round($attendance_ot_class->getOvertimeRestdayHolidayOt($emp_id)/60,2); // 90/60 = 1.5




	$row_wh = $working_hours_class->getWorkingHoursInfoById($row->working_hours_id);
										

	$timeFrom = $row_wh->timeFrom;
	$timeTo = $row_wh->timeTo;

	$timeFrom = strtotime($timeFrom);
	$timeTo = strtotime($timeTo);

	//echo " " . $timeFrom . " " . $timeTo;

	$total_hours = (($timeTo - $timeFrom) / 3600) - 1;


    $allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);
    //$daily_rate =  $row->Salary / 26;
    $daily_rate =  (($row->Salary + $allowance ) / 2)/ $days;

    $daily_rate_basic = (($row->Salary ) / 2)/ $days;
    //echo $daily_rate_basic . " " . $days;
    $daily_rate_allowance = (($allowance ) / 2)/ $days;

	//$hourly_rate = $daily_rate / 8;
	$hourly_rate = round($daily_rate / $total_hours,2); 
	//echo $days . " " . $daily_rate . " " . $total_hours . "<br/>";
	/*if ($emp_id == 68 || $emp_id == 85){ // kay kuya drew at kay kuya vannie
		$hourly_rate = $daily_rate / 12;
	}*/

	//echo $daily_rate . " " . $hourly_rate;
	//$new_daily_rate = $hourly_rate * 9;



	// check muna natin kung sakop ng cut off yung effective date ng min wage
    $inCutOff = $min_wage_class->checkMinWageEffectiveDateInCutOff($emp_id,($allowance+$row->Salary)); // kung ung increase

    //echo $inCutOff;

   // echo $inCutOff;

	// for OT's rate
	$regular_ot_rate = round($hourly_rate + ($hourly_rate * .25),2);

	//echo $regular_ot_rate;


	$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);

	//echo " " . $regularOTmin;
	// if sakop ng cut off
	if($inCutOff == 1){
		//echo "wew";
		$reg_ot_amount = round($attendance_ot_class->getRegOtAmount($emp_id),2);
	}

	$regHoliday_ot_rate = round($hourly_rate,2);
	$regHoliday_ot_amount = round($regHoliday_ot_rate * $regHolidayOTmin,2); 
	if ($inCutOff == 1){
		$regHoliday_ot_amount = round($attendance_ot_class->getRegHolidayOtAmount($emp_id),2);
	}

	//echo $regHoliday_ot_rate . " " . $regHolidayOTmin;


	$specialHoliday_ot_rate = round($hourly_rate * .3,2);
	$specialHoliday_ot_amount = round($specialHoliday_ot_rate * $specialHolidayOTmin,2); 
	if ($inCutOff == 1){
		$specialHoliday_ot_amount = round($attendance_ot_class->getSpecialHolidayOtAmount($emp_id),2);
	}

	$rdRegularHoliday_ot_rate = round($hourly_rate * 2.6,2);
	$rdRegularHoliday_ot_amount = round($rdRegularHoliday_ot_rate * $rd_regularHolidayOTmin,2); 
	if ($inCutOff == 1){
		$specialHoliday_ot_amount = round($attendance_ot_class->getRdRegularHolidayOtAmount($emp_id),2);
	}


	$rdSpecialHoliday_ot_rate = round($hourly_rate + ($hourly_rate * .6) ,2);
	$rdSpecialHoliday_ot_amount = round($rdSpecialHoliday_ot_rate * $rd_specialHolidayOTmin,2); 
	if ($inCutOff == 1){
		$rdSpecialHoliday_ot_amount = round($attendance_ot_class->getRdSpecialHolidayOTamount($emp_id),2);
	}


	$rd_ot_rate = round($hourly_rate + ($hourly_rate * .3),2);

	//echo $rd_ot_rate . "<br/>";

	$rd_ot_amount = round($rd_ot_rate * $restdayOTmin,2); 

	if ($inCutOff == 1){
		$rd_ot_amount = round($attendance_ot_class->getRdOtAmount($emp_id),2);
	}

	$attendance_ot_class->getRdOtAmount($emp_id);

	//$rd_holiday_ot_rate = round($hourly_rate * 2.6,2);
	//$rd_holiday_ot_amount = round($rd_holiday_ot_rate * $restday_holidayOTmin,2);



	// for present
	$present = $attendance_class->getPresentToPayroll($row->bio_id,$day_from,$day_to);

	//echo $present;


	// kailangan macheck ko kung ilan beses na siya napayrollan
	$payroll_count = $payroll_class->countPayrollCreatedToEmployee($emp_id);

	//echo $payroll_count;

	// for sss contribution
	$sssContribution = 0;
	//echo $row->SSS_No;
	if ($row->SSS_No != "" && $payroll_count > 2){
		//echo "Wew";
		//echo "wew";
		//echo $row->Salary;

		$sssContribution = round($sss_contrib_class->getContribution($row->Salary),2);
		//$sssContribution = 0;
			
		//echo "wew " . $sssContribution . "<br/>";
	}
	//$sssContribution = $sss_contrib_class->getContribution($row->Salary);
	$philhealthContribution = 0;
	if ($row->PhilhealthNo != "" && $payroll_count > 2){
		$philhealthContribution = round($philhealth_contrib_class->getContribution($row->Salary),2);
		//$philhealthContribution = 0;
	}

	$pagibigContribution = 0;
	if ($row->PagibigNo != "" && $payroll_count > 2){
		$pagibigContribution = round($pagibig_contrib_class->getContribution($row->Salary),2);
		//$pagibigContribution = 0;

		/*if ($emp_id == 87){
			$pagibigContribution = 300; // for upgrading purposes
		}*/
	}
	

	// for sss loan
	$has_sssLoan = $sss_loan_class->existPendingSSSLoan($emp_id);

	$sss_loan_amount = 0;
	//echo $has_sssLoan;
	if ($has_sssLoan != 0){
		//echo "Ready for Condition";

		$cutOff_day = $cut_off_class->getCutOffDay();
		//echo $cutOff_day;

		if ($cutOff_day == "30" || $cutOff_day == "28" || $cutOff_day == "29") {
			//echo "Wew";
			//$row_sssLoan = $sss_loan_class->getInfoBySSSLoanEmpId($emp_id); 
			//$sss_loan_amount = $row_sssLoan->deduction;

			// kapag mas mababa ung remaining balance sa total deduction malay mo nagadvance payment
			//if ($row_sssLoan->deduction >= $row_sssLoan->remainingBalance){
			//	$sss_loan_amount = $row_sssLoan->remainingBalance;
			//}
			$sss_loan_amount = $sss_loan_class->getSSSLoanToPayroll($emp_id);
		}

		//$sss_loan_amount = 0;
	}



	// for pagibig loan
	$has_pagibigLoan = $pagibig_loan_class->existPendingPagibigLoan($emp_id);

	$pagibig_loan_amount = 0;
	//echo $has_pagibigLoan;
	if ($has_pagibigLoan != 0){
		//echo "Ready for Condition";

		$cutOff_day = $cut_off_class->getCutOffDay();

		if ($cutOff_day == "15") {
			/*$row_pagibigLoan = $pagibig_loan_class->getInfoByPagibigLoanEmpId($emp_id); 
			$pagibig_loan_amount = $row_pagibigLoan->deduction;
			// kapag mas mababa ung remaining balance sa total deduction malay mo nagadvance payment
			if ($row_pagibigLoan->deduction >= $row_pagibigLoan->remainingBalance){
				$pagibig_loan_amount = $row_pagibigLoan->remainingBalance;
			}
			*/

			$pagibig_loan_amount = $pagibig_loan_class->getPagibigLoanToPayroll($emp_id);
			//;
		}

		//$pagibig_loan_amount = 0;
	}


	// for pagibig loan
	$has_salaryLoan = $salary_loan_class->existPendingSalaryLoan($emp_id);

	$salary_loan_amount = 0;
	if ($has_salaryLoan != 0){
		//echo "Ready for Condition";

		$salary_loan_amount = $salary_loan_class->getSalaryLoanInfoToPayroll($emp_id);


		//}
	}

	//echo $salary_loan_amount;



	// for simkimban
	$has_simkimban = $simkimban_class->existPendingSimkimban($emp_id);

	$simkimban_amount = 0;
	if ($has_simkimban != 0){
		//echo "Ready for Condition";

		$simkimban_amount = $simkimban_class->getInfoBySimkimbanEmpId($emp_id); 
		
	}


	// for attendance
	/*$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);
	if ($allowance == ""){
		$allowance = 0;
	}*/


	$totalAllowance = round($allowance,2); // for cut off allowance
	$dailyAllowance = round(($allowance / 2)/$days,2);


	//echo $row->bio_id;

	$attendance_rate = 0;
	$tardinessMin = 0;
	$tardinessAmount = 0;
	if ($row->bio_id != 0){
		//echo "wew";
		//$attendance_rate = round((((($row->Salary + $allowance)/2) / $days) /$total_hours),2);

		/*if ($emp_id == 68 || $emp_id == 85){ // kay kuya drew at kay kuya vannie
			$attendance_rate = round((((($row->Salary + $allowance)/2) / $days) /12),2);
		}*/

		//$tardinessMin = round($attendance_class->getTardiness($row->bio_id) / 60,2);



		//echo $tardinessMin . "<br/>";

		//echo $tardinessMin;
		//echo $tardinessMin . "<br/>";
		//echo $attendance_rate;
		$tardinessAmount = $attendance_class->getTardinessLatest($row->emp_id,$row->bio_id,$row_wh->timeFrom,$row_wh->timeTo,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours);	

		if ($inCutOff == 1){
			//$tardinessAmount = round($attendance_class->getTardinessAmount($row->bio_id),2);
		}
	}

	//echo $tardinessMin . "<br/>";
	//echo $attendance_rate;
	//echo $tardinessMin;


	//echo $attendance_class->getTardiness($row->bio_id);


	// for allowance adjust
	//$allowace_adjust = ($row->Salary / 2) - ((($row->Salary / 26) / 8)* 9 )* 11; 

	//echo $allowace_adjust;

	//$total_cut_allowance = round($allowance_class->getAllowanceInfoByEmpId($emp_id) / 2,2);

	//echo $allowance;

	//$absencesRate = round(((($row->Salary) / 26 )/8) + (($allowace_adjust / 11)/9) + (($allowance /22)/9),2) ; // 1300/22
	//$absencesRate = round((((($row->Salary) / 2) / $days )/$total_hours),2) + round(($dailyAllowance/$total_hours),2) ; // 1300/22 , 61.375 , 138.0
	/*if ($emp_id == 68 || $emp_id == 85){ // kay kuya drew at kay kuya vannie
		$absencesRate = round((((($row->Salary) / 2) / $days )/12),2) + round(($dailyAllowance/12),2) ;
	}*/

	//echo $absencesRate;
	//$absencesMin = round($attendance_class->getAbsences($row->bio_id) * $total_hours,2);
	/*if ($emp_id == 68 || $emp_id == 85){ // kay kuya drew at kay kuya vannie
		$absencesMin = round($attendance_class->getAbsences($row->bio_id) * 12,2);
	}*/

	//echo $absencesMin;

	//echo $absencesMin; 

	//echo $absencesRate;
	$absencesAmount = 0;


	if ($inCutOff == 1){
		//$absencesAmount = round($attendance_class->getAbsencesAmount($row->bio_id),2);
	}




	// for cashbond
	//$cashBond = round((($row->Salary + $allowance) * .02)/2,2);
	$cashBond = $cashbond_class->getInfoByEmpId($emp_id)->cashbondValue;
	//$cashBond = 0;

	$basicCutOffPay = round($row->Salary / 2,2);
	if ($inCutOff == 1){
		//$basicCutOffPay = round($attendance_class->getBasicPayAmount($emp_id),2);
	}


	


	// for incentives
	$incentives = round($attendance_class->getIncentives($row->bio_id,round($daily_rate,2)),2);

	//echo $incentives;



	// for total gross income

	//$present = $present + 2; // ibig sabihin bayad ung dalawang holiday

	$totalGrossIncome = round((($daily_rate_basic * $present) + $reg_ot_amount + $regHoliday_ot_amount + $specialHoliday_ot_amount+ $rdRegularHoliday_ot_amount + $rdSpecialHoliday_ot_amount + $rd_ot_amount) - ($tardinessAmount + $absencesAmount) - $gross_income_inc,2);


	//echo $totalGrossIncome . "<br/>";

	// for tax
	
	/*
	if ($row->Salary > $min_wage) {

		if ($row->TinNo != ""){
			// if 0 remain 0 ung tax
			if ($totalGrossIncome != 0){
				$tax = round($bir_contrib_class->getTax($taxStatus,$totalGrossIncome),2);
			}
		}
	}*/


	$cutOff_day = $cut_off_class->getCutOffDay();

	

	/*
	if ($row->Salary > "20833") {

		if ($row->TinNo != ""){
			// if 0 remain 0 ung tax
			if ($totalGrossIncome != 0){
				$tax = round($bir_contrib_class->getTax($taxStatus,$totalGrossIncome),2);
			}
		}
	}
	*/


	// for allowance
	$allowance = round($allowance_class->getAllowanceInfoByEmpId($emp_id) / 2,2);

	$present_allowance = round($daily_rate_allowance * $present,2);

	$total = round($totalGrossIncome+ $present_allowance,2);

	//echo $pagibig_loan_amount . " " . $sss_loan_amount;

	$totalCashAdvance = $simkimban_amount + $salary_loan_amount;

	//$totalCashAdvance = 0;

	$totalDeduction = $sssContribution + $sss_loan_amount + $philhealthContribution + $pagibigContribution + $pagibig_loan_amount + $cashBond +$totalCashAdvance;


	$taxableIncome = $totalGrossIncome - ($sssContribution + $pagibigContribution + $philhealthContribution); 

	$last_total_gross_income = 0;
	if ($cutOff_day == "30") {


		if ($payroll_class->checkExistPayrollLastTotalGrossIncome($emp_id) != 0){

		// to get previous totgal gross income
			$last_total_gross_income = $payroll_class->payrollLastTotalGrossIncome($emp_id);
		}

		//echo $last_total_gross_income;
		//$totalGrossIncome += $last_total_gross_income;


		if (($taxableIncome + $last_total_gross_income) <= 20833){
			$tax = 0;
		}

		else if (($taxableIncome + $last_total_gross_income) > 20833 && ($taxableIncome + $last_total_gross_income) < 33333){
			$tax = round(((($taxableIncome + $last_total_gross_income) - 20833) * .20),2);

		}

		else if (($taxableIncome + $last_total_gross_income) > 33333 && $taxableIncome < 66667){
			$tax = round(((($taxableIncome + $last_total_gross_income) - 33333) * .25) + 2500,2);
		}
		
		else if (($taxableIncome + $last_total_gross_income) > 66667 && ($taxableIncome + $last_total_gross_income) < 166667){
			$tax = round(((($taxableIncome + $last_total_gross_income) - 66667) * .30) + 10833.33,2);
		}

		else if (($taxableIncome + $last_total_gross_income) > 166667 && ($taxableIncome + $last_total_gross_income) < 666667){
			$tax = round(((($taxableIncome + $last_total_gross_income) - 166667) * .32) + 40833.33,2);
		}

		else if (($taxableIncome + $last_total_gross_income) >= 666667){
			$tax = round(((($taxableIncome + $last_total_gross_income) - 666667) * .35) + 200833.33,2);
		}
	}


	// for Year Total Deduction
	$ytd_row = $ytd_class->getInfoByEmpId($emp_id);

	$ytdGross = $ytd_row->ytd_Gross;
	$ytdAllowance = $ytd_row->ytd_Allowance;
	$ytdTax = $ytd_row->ytd_Tax;

	$final_totalDeduction = $totalDeduction + $tax;

		

	//echo $ytdGross;
	

	//echo $total . " " . $final_totalDeduction . " " . $incentives;

	$netPay = round($total  -  $final_totalDeduction + $incentives,2)


?>
	<div class="panel panel-primary">
		<div class="panel-body"> 
			<div class="col-sm-12">

				<div class="thumbnail col-sm-12" style="border:1px solid #BDBDBD;font-size:11.5px;">
					<div class="caption">
						<b>
							
								<div class="form-group">
									<div class="col-sm-12">
										<div class="pull-right">
											<img src="<?php echo $logo_source; ?>" class="payroll-logo"/> LLOYDS FINANCING CORPORATION
										</div>
									</div>
								</div>


								<div class="form-group">
									<div class="col-sm-12" style="">
										<span><u>Employee No: <?php echo $emp_id; ?></u></span>
										<span class="pull-right"><u>Payroll Period: <?php echo $cut_off_class->getCutOffPeriod(); ?></u></span>
									</div>
								</div>

								<div class="form-group" style="margin-top:-10px;">
									<div class="col-sm-12" style="border-bottom:1px solid #566573 ;">
										<span><u>Department: <?php echo $row_dept->Department; ?></u></span>
										<span class="pull-right"><u>Basic Pay: <?php if ($min_wage >= $row->Salary) { echo round(($row->Salary/2),2);} else { echo $basicCutOffPay; } ?> <span id="basicPay_<?php echo $emp_id; ?>" style="display:none;"><?php echo $basicCutOffPay;  ?></span></u></span>
									</div>
								</div>

								<div class="form-group" style="margin-top:-10px;">
									<div class="col-sm-12" style="border-bottom:1px solid #566573 ;">
										<span>Name: <?php echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename; ?></span>
										<span class="pull-right">Tax Code: <?php echo $taxCode; ?></span> 
									</div>
								</div>

								

								<div class="col-sm-12">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Earnings</b></div>

											<div class="form-group" style="margin-top:-10px;">
												<div class="col-sm-2">
													<label class="control-label">Regular OT:</label>
													<!--<input type="text" value="<?php echo $emp_id;  ?>">-->
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="regOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>      
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="regOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $reg_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">Restday OT:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>      
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $rd_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">Regular Holiday OT:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="regHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>      
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="regHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $regHoliday_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">Special Holiday OT:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="specialHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>       
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="specialHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $specialHoliday_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>
		

												<div class="col-sm-2">
													<label class="control-label">RD/Regular Holiday OT:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdREgHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>       
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdREgHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $rdRegularHoliday_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">RD/Special Holiday OT:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdSpecialHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>       
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="rdSpecialHolidayOT_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $rdSpecialHoliday_ot_amount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

											</div>


											<div class="form-group" style="margin-top:-10px;">
												<div class="col-sm-2">
													<label class="control-label">Tardiness:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="tardiness_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>        
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="tardiness_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $tardinessAmount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2" style="display: none">
													<label class="control-label">Absences:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="hidden" title="earnings_<?php echo $emp_id; ?>" name="absences_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>        
													<?php
														} // end of else 

														else {
													?>
														<input type="hidden" title="earnings_<?php echo $emp_id; ?>" name="absences_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $absencesAmount; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">Present:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="present_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>        
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="present_<?php echo $emp_id; ?>" id="float_only" value="<?php echo round($daily_rate_basic * $present,2); ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>

												<div class="col-sm-2">
													<label class="control-label">Gross income:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" name="grossIncome_<?php echo $emp_id; ?>" id="input_payroll" value="<?php echo $basicCutOffPay; ?>" class="form-control custom-form-control"/>        
													<?php
														} // end of else 

														else {
													?>
														<input type="text" name="grossIncome_<?php echo $emp_id; ?>" id="input_payroll" value="<?php echo $totalGrossIncome; ?>" class="form-control custom-form-control"/> 
													<?php
														} // end of else
													?>
												</div>


												<div class="col-sm-2">
													<label class="control-label">Adjustment:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="adjustment_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/>        
													<?php
														} // end of else 

														else {
													?>
														<input type="text" title="earnings_<?php echo $emp_id; ?>" name="adjustment_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control adjustment"/> 
													<?php
														} // end of else
													?>
												</div>
											</div>									
										</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-12">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #ca6f1e ">Deductions</b></div>

									    	<div class="form-group" style="margin-top:-10px;">
												

												<div class="col-sm-2">
													<label class="control-label">SSS Contribution:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="sssContrib_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $sssContribution; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">SSS Loan:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="sssLoan_<?php echo $emp_id; ?>"  id="float_only" value="<?php echo $sss_loan_amount; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">Philhealth Contribution:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="philhealthContrib_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $philhealthContribution; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">Pag-ibig Contribution:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="pagibigContrib_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $pagibigContribution; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">Pag-ibig Loan:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="pagibigLoan_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $pagibig_loan_amount; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">Cashbond:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="cashBond_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $cashBond; ?>" class="form-control custom-form-control"/> 
													
												</div>


											</div>


											<div class="form-group" style="margin-top:-10px;">

												
												<div class="col-sm-2">
													<label class="control-label">Cash Advance:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="cashAdvance_<?php echo $emp_id; ?>" id="input_payroll" value="<?php echo $totalCashAdvance; ?>" class="form-control custom-form-control"/> 
													
												</div>

												<div class="col-sm-2">
													<label class="control-label">Total Deductions:</label>
													
													<input type="text" name="totalDeductions_<?php echo $emp_id; ?>" id="input_payroll" value="<?php echo $totalDeduction; ?>" class="form-control custom-form-control"/> 
													
												</div>


												<div class="col-sm-2">
													<label class="control-label">Adjustment:</label>
													
													<input type="text" title="deduction_<?php echo $emp_id; ?>" name="adjustmentdeduction_<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control adjustment"/> 
													
												</div>

												

											</div>	




								    	</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-2">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Tax</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Witholding Tax:</label>
													<?php
														// float_only
													if ($row->bio_id == 0){
													?>
														<input type="text" name="witholdingTax_<?php echo $emp_id; ?>" id="input_payroll" value="0" class="form-control custom-form-control"/> 
													<?php
														}
													else {
													?>
														<input type="text" name="witholdingTax_<?php echo $emp_id; ?>" id="input_payroll" value="<?php echo $tax; ?>" class="form-control custom-form-control"/>
													<?php
													}
													?>


												</div>
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-2">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Allowance</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Nontax Allowance:</label>
													
													<input type="text" name="nontaxAllowance_<?php echo $emp_id; ?>" id="float_only" value="<?php echo $present_allowance; ?>" class="form-control custom-form-control"/> 
													
												</div>
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-2">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Adjustment After</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Adjustment:</label>
													
													<input type="text" name="adjustmentAfter<?php echo $emp_id; ?>" id="float_only" value="0" class="form-control custom-form-control"/> 
													
												</div>
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-3">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Additional</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Incentives:</label>
													
													<?php echo number_format($incentives,2); ?>	

													<br/>

													<?php

														$december_15_2019_13_pay_basic = 0;
														$december_15_2019_13_pay_allowance = 0;

														$december_30_2019_13_pa_basic = 0;
														$december_30_2019_13_pay_allowance = 0;

														$january_15_2020_13_pay_basic = 0;
														$january_15_2020_13_pay_allowance = 0;

														$cut_off_13_pay_basic = $basicCutOffPay;		
														$cut_off_13_pay_allowance = $allowance;		


														if ($cut_off_class->checkIfHiredWithinCutOff($row->DateHired) == 1){
															$daily_basic_13_month_pay = round($basicCutOffPay / $working_days_count,2);
															$dayily_allowance_13_month_pay = round($allowance / $working_days_count,2);

															

															$cut_off_13_pay_basic = round($daily_basic_13_month_pay * $present,2);	
															$cut_off_13_pay_allowance = round($dayily_allowance_13_month_pay * $present,2);

														}									
													?>

													<?php

														if ($cut_off_class->getCutOffPeriodLatest() == "January 11, 2020 - January 25, 2020"){

															// November 26, 2019 - December 10, 2019
															// December 11, 2019 - December 25, 2019
															// December 26, 2019 - January 10, 2020

															if ($payroll_class->getCountCutOff13MonthPayOld($emp_id,"November 26, 2019 - December 10, 2019") != 0){

																$row_13 = $payroll_class->getCutOff13MonthPayOld($emp_id,"November 26, 2019 - December 10, 2019");

																$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
																$december_15_2019_13_pay_allowance = $row_13->allowancePay;
															}

															if ($payroll_class->getCountCutOff13MonthPayOld($emp_id,"December 11, 2019 - December 25, 2019") != 0){
																$row_13 = $payroll_class->getCutOff13MonthPayOld($emp_id,"December 11, 2019 - December 25, 2019");

																$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
																$december_30_2019_13_pay_allowance = $row_13->allowancePay;
															}

															if ($payroll_class->getCountCutOff13MonthPayOld($emp_id,"December 26, 2019 - January 10, 2020") != 0){

																$row_13 = $payroll_class->getCutOff13MonthPayOld($emp_id,"December 26, 2019 - January 10, 2020");

																$january_15_2020_13_pay_basic = $row_13->ratePayPrd;
																$january_15_2020_13_pay_allowance = $row_13->allowancePay;
															}

													?>
													<label class="control-label">December 15, 2019 (BASIC):</label>
													<?php echo number_format($december_15_2019_13_pay_basic,2); ?>
													<br/>

													<label class="control-label">December 15, 2019 (ALLOWANCE):</label>
													<?php echo number_format($december_15_2019_13_pay_allowance,2); ?>
													<br/>

													<label class="control-label">December 30, 2019 (BASIC):</label>
													<?php echo number_format($december_30_2019_13_pa_basic,2); ?>
													<br/>

													<label class="control-label">December 30, 2019 (ALLOWANCE):</label>
													<?php echo number_format($december_30_2019_13_pay_allowance,2); ?>
													<br/>


													<label class="control-label">January 15, 2019 (BASIC):</label>
													<?php echo number_format($january_15_2020_13_pay_basic,2); ?>
													<br/>

													<label class="control-label">January 15, 2019 (ALLOWANCE):</label>
													<?php echo number_format($january_15_2020_13_pay_allowance,2); ?>
													<br/>
													
													<?php
														}

													?>

													<label class="control-label"><b>Current Cut Off 13th</b></label>
													<br/>
													<label class="control-label"><?php echo $cut_off_class->getDatePayroll(); ?> (BASIC):</label>
													<?php echo number_format($cut_off_13_pay_basic,2); ?>
													<br/>

													<label class="control-label"><?php echo $cut_off_class->getDatePayroll(); ?> (ALLOWANCE):</label>
													<?php echo number_format($cut_off_13_pay_allowance,2); ?>
													<br/>

													<?php

														if ($cut_off_class->getCutOffPeriodLatest() == "January 11, 2020 - January 25, 2020"){
													?>
													<label class="control-label"><b>Total</b></label>
													<br/>

													<label class="control-label">TOTAL BASIC:</label>
													<?php echo number_format($december_15_2019_13_pay_basic + $december_30_2019_13_pa_basic + $january_15_2020_13_pay_basic + $cut_off_13_pay_basic,2); ?>
													<br/>

													<label class="control-label">TOTAL ALLOWANCE:</label>
													<?php echo number_format($december_15_2019_13_pay_allowance + $december_30_2019_13_pay_allowance + $january_15_2020_13_pay_allowance + $cut_off_13_pay_allowance,2); ?>
													<br/>
													<?php
														}

													?>


													<label class="control-label">TOTAL BASIC PAY:</label>
													<?php

														$total_13_basic_pay = round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic /12,2) + round($cut_off_13_pay_basic/12,2);


														echo number_format($total_13_basic_pay,2);
													?>
													<br/>

													<label class="control-label">TOTAL ALLOWANCE PAY:</label>
													<?php

														$total_13_allowance_pay = round($december_15_2019_13_pay_allowance/12,2) + round($december_30_2019_13_pay_allowance/12,2) + round($january_15_2020_13_pay_allowance/12,2) + round($cut_off_13_pay_allowance/12,2);

														echo number_format($total_13_allowance_pay,2);
													?>
													<br/>



													<?php
														$netPay += ($total_13_basic_pay + $total_13_allowance_pay);
													?>
													
													
													
												</div>
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>

								<div class="col-sm-3">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #196f3d ">Net Pay</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Net Pay:</label>
													<?php
														// float_only
														if ($row->bio_id == 0){
													?>
														<input type="text" name="netPay_<?php echo $emp_id; ?>"  id="input_payroll" value="0" class="form-control custom-form-control"/> 
													<?php
														}
													else {
													?>
														<input type="text" name="netPay_<?php echo $emp_id; ?>"  id="input_payroll" value="<?php echo $netPay; ?>" class="form-control custom-form-control"/> 
													<?php
														}
													?>
												</div>

												

						

												<input type="hidden" name="taxCode_<?php echo $emp_id; ?>" value="<?php echo $taxCode; ?>"/>

												<div class="col-sm-12">
													<input type="hidden" name="adjustmentRemarks_<?php echo $emp_id; ?>" value="" />
												</div>
											</div>


											<div class="form-group">
												
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>




								


								<div class="col-sm-12">		
					    			<div class="form-group">
					    				<center>
					    					<button type="button" id="adjustment_remarks<?php echo $emp_id; ?>" class="btn btn-primary btn-sm">Adjustment</button>
										</center>
									</div>							    	
								</div> 


								

								
								

							
						</b>
					</div>
				</div>

			</div>
		</div>
	</div>


	<div id="payrollRemarksModal<?php echo $emp_id; ?>" class="modal fade" role="dialog" tabindex="-1">
		<div class="modal-dialog">
		<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color:#1d8348;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-edit' style='color:#fff'></span>&nbsp;Remarks</h5>
				</div> 
				<div class="modal-body" id="add_modal_body">
					<div class="container-fluid">
						<textarea class="form-control" placeholder="Input Remarks" name="remarksModal<?php echo $emp_id; ?>" rows="3"></textarea>
					</div>
				</div> 
				<div class="modal-footer" style="padding:5px;">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="remarks_ok_adjustment<?php echo $emp_id; ?>">OK</button>
				</div>
			</div>

		</div>
	</div> <!-- end of modal -->


	<script>
		 $(document).ready(function(){
		 	// for payroll
	      //  input_payroll
	      $("input[id='input_payroll']").keydown(function (e) {
	      //  return false;
	        if(e.keyCode != 116) {
	            return false;
	        }
	      });

	        // onpaste
	     $("input[id='input_payroll").on("paste", function(){
	          return false;
	     });



	      // onpaste
	      $("input[name='adjustment_<?php echo $emp_id; ?>'").blur(function(){

	          if ($(this).val()==""){
	             $(this).val("0"); 
	          }

	     });


	       // onpaste
        $("input[name='adjustmentdeduction_<?php echo $emp_id; ?>'").blur(function(){

	          if ($(this).val()==""){
	             $(this).val("0"); 
	          }

	     });

          // onpaste
	     $("input[id='float_only").on("paste", function(){
	          return false;
	     });


           // onpaste
         $("input[id='float_only").blur(function(){

	          if ($(this).val()==""){
	             $(this).val("0"); 
	          }

	     });

        // FOR DECIMAL POINT
	      $("input[id='float_only'").keydown(function (e) {


	      //	alert(e.keyCode);
	     	if ($(this).val() == 0 && e.keyCode == "9") {
	     		$(this).val("0");
     	 	}

	      	//var new_value =0;
	      	else if ($(this).val() == 0) {
	      		$(this).val($(this).val().slice(1,-1));
	      	}


	      	if (e.keyCode == "190" && $(this).val() == 0) {
	      	 	$(this).val("0.");
      	  	}

	        // for decimal pint
	        if (e.keyCode == "190") {
	            if ($(this).val().replace(/[0-9]/g, "") == ".") {
	                return false;  
	            }
	        }


	        if (e.keyCode == "189" || e.keyCode == "173") {
	            if ($(this).val().replace(/[0-9]/g, "") == "-") {
	                return false;  
	            }
	        }





	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });


        



      	// for remarks of adjust
      	$("button[id='adjustment_remarks<?php echo $emp_id; ?>']").on("click", function () {
      		 var emp_id = $(this).attr("id").slice(18);
      		
      		 var adjustmentRemarks = $("input[name='adjustmentRemarks_"+emp_id+"']").val();
      		// alert(adjustmentRemarks);

      		 $("#remarksModal"+emp_id).html(adjustmentRemarks);




      		$("#payrollRemarksModal"+emp_id).modal("show");

  		});


  		// remarks_ok_adjustment
  		$("button[id='remarks_ok_adjustment<?php echo $emp_id; ?>']").on("click", function () {
  			 var emp_id = $(this).attr("id").slice(21);

  			 //alert(emp_id);
  			  var remarks = $("textarea[name='remarksModal"+emp_id+"']").val();


  			  //alert(remarks);

  			 $("input[name='adjustmentRemarks_"+emp_id+"']").val(remarks);
		});

  		$("input[title='earnings_<?php echo $emp_id; ?>']").change(function(){

             if ($(this).val() == ""){
             	$(this).val(0);
             }


  			 var incentives = <?php echo $incentives; ?>;
  			 var regOT = $("input[name='regOT_<?php echo $emp_id; ?>']").val();
  			 var rdOT = $("input[name='rdOT_<?php echo $emp_id; ?>']").val();
  			 var regHolidayOT =  $("input[name='regHolidayOT_<?php echo $emp_id; ?>']").val();
  			 var specialHolidayOT = $("input[name='specialHolidayOT_<?php echo $emp_id; ?>']").val();
  			 var rd_regHolidayOT = $("input[name='rdREgHolidayOT_<?php echo $emp_id; ?>']").val();
  			 var rd_specialHolidayOT = $("input[name='rdSpecialHolidayOT_<?php echo $emp_id; ?>']").val();
  			 var tardiness = $("input[name='tardiness_<?php echo $emp_id; ?>']").val();
  			 var absences = $("input[name='absences_<?php echo $emp_id; ?>']").val();
  			 var present = $("input[name='present_<?php echo $emp_id; ?>']").val();
  			 var adjustmentEarnings = $("input[name='adjustment_<?php echo $emp_id; ?>']").val();

  			 var last_total_gross_income = <?php echo $last_total_gross_income; ?>;
  			 var sss_contribution = <?php echo $sssContribution; ?>;
  			 var pagibig_contribution = <?php echo $pagibigContribution; ?>;
  			 var philhealth_contribution = <?php echo $philhealthContribution; ?>;
  			 var cutOff_day = "<?php echo $cutOff_day; ?>";
  				

  			 var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;


  			 /*alert(regOT);
  			 alert(rdOT);
  			 alert(regHolidayOT);
  			 alert(specialHolidayOT);
  			 alert(rd_regHolidayOT);
  			 alert(rd_specialHolidayOT);
  			 alert(tardiness);
  			 alert(absences);
  			 alert(adjustmentEarnings);
  			 */

  			  //alert(adjustmentEarnings);

  			/* var totalGrossIncome = parseFloat(regOT) + parseFloat(rdOT) + parseFloat(regHolidayOT) + parseFloat(specialHolidayOT) + parseFloat(rd_regHolidayOT) + parseFloat(rd_specialHolidayOT) - parseFloat(tardiness) - parseFloat(absences) + parseFloat(adjustmentEarnings);*/

  		     var basicPay = $("span[id='basicPay_<?php echo $emp_id; ?>']").html();
  		     //<?php
  		    // 	if ($inCutOff == 1){
  		     //		echo "var basicPay = " . $basicCutOffPay;
  		     //	}

  		     //?>
	
  		     console.log(basicPay);
  		     console.log(regOT);
  		     console.log(rdOT);
  		     console.log(regHolidayOT);
  		     console.log(specialHolidayOT);
  		     console.log(rd_regHolidayOT);
  		     console.log(rd_specialHolidayOT);
  		     console.log(tardiness);
  		     console.log(absences);
  		     console.log(adjustmentEarnings);




  		    // alert(basicPay);
  			 var totalGrossIncome = parseFloat(convertToZero(present)) + parseFloat(convertToZero(regOT)) + parseFloat(convertToZero(rdOT)) + parseFloat(convertToZero(regHolidayOT)) + parseFloat(convertToZero(specialHolidayOT)) + parseFloat(convertToZero(rd_regHolidayOT)) + parseFloat(convertToZero(rd_specialHolidayOT)) - parseFloat(convertToZero(tardiness)) - parseFloat(convertToZero(absences)) + parseFloat(convertToZero(adjustmentEarnings));

  			 console.log(totalGrossIncome);
  			 $("input[name='grossIncome_<?php echo $emp_id; ?>']").val(totalGrossIncome);

  			 console.log(sss_contribution);
  			 console.log(pagibig_contribution);
  			 console.log(philhealth_contribution);

  			 //totalGrossIncome -= (parseFloat(sss_contribution) + parseFloat(pagibig_contribution) + parseFloat(philhealth_contribution)); 

  			 //console.log(totalGrossIncome);

  			  //alert(totalGrossIncome);

  			  totalGrossIncome = totalGrossIncome.toString().split('e');
              totalGrossIncome = Math.round(+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] + 2) : 2)));

              totalGrossIncome = totalGrossIncome.toString().split('e');
              totalGrossIncome =  (+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] - 2) : -2))).toFixed(2);


              //alert("Hello World!");
  			
  			 
  			  // $taxStatus,$totalGrossIncome
  			  var datastring = "taxable_income="+totalGrossIncome+"&emp_id=<?php echo $emp_id; ?>&emp_name=<?php echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename; ?>" + "&last_total_gross_income="+last_total_gross_income+"&cutOff_day="+cutOff_day+"&sss_contribution="+sss_contribution+"&pagibig_contribution="+pagibig_contribution+"&philhealth_contribution="+philhealth_contribution;
          	 // alert(datastring);
	            $.ajax({
	              type: "POST",
	              url: "ajax/append_tax_value.php",
	              data: datastring,
	              cache: false,
	              success: function (data) {
	              		//alert(data);
	              		$("input[name='witholdingTax_<?php echo $emp_id; ?>']").val(data);

	              		 var totalDeductions = $("input[name='totalDeductions_<?php echo $emp_id; ?>']").val();
		                  var nontaxAllowance = $("input[name='nontaxAllowance_<?php echo $emp_id; ?>']").val();
		                  var adjustmentAfter = $("input[name='adjustmentAfter<?php echo $emp_id; ?>']").val();

		                  var netPay = parseFloat(totalGrossIncome) - parseFloat(convertToZero(totalDeductions)) -  parseFloat(data) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));

		                  netPay += parseFloat(convertToZero(incentives));
	             		    netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

		                  // for 2 decimal places
		                  netPay = netPay.toString().split('e');
		                  netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

		                  netPay = netPay.toString().split('e');
		                  final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

		                  $("input[name='netPay_<?php echo $emp_id; ?>']").val(final_netPay);
              		}
              	});
		});



		$("input[title='deduction_<?php echo $emp_id; ?>']").change(function(){

				if ($(this).val() == ""){
	             	$(this).val(0);
	             }

				var sssContrib = $("input[name='sssContrib_<?php echo $emp_id; ?>']").val();
				var sssLoan = $("input[name='sssLoan_<?php echo $emp_id; ?>']").val();
				var philhealthContrib = $("input[name='philhealthContrib_<?php echo $emp_id; ?>']").val();
				var pagibigContrib = $("input[name='pagibigContrib_<?php echo $emp_id; ?>']").val();
				var pagibigLoan = $("input[name='pagibigLoan_<?php echo $emp_id; ?>']").val();
				var cashBond = $("input[name='cashBond_<?php echo $emp_id; ?>']").val();
				var cashAdvance = $("input[name='cashAdvance_<?php echo $emp_id; ?>']").val();
				var adjustmentDeduction = $("input[name='adjustmentdeduction_<?php echo $emp_id; ?>']").val();

				var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 	var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;
				/*alert(sssContrib);
				alert(sssLoan);
				alert(philhealthContrib);
				alert(pagibigContrib);
				alert(pagibigLoan);
				alert(cashBond);
				alert(cashAdvance);
				alert(adjustmentDeduction);
				*/


				var totalDeductions = parseFloat(convertToZero(sssContrib)) + parseFloat(convertToZero(sssLoan)) + parseFloat(convertToZero(philhealthContrib)) + parseFloat(convertToZero(pagibigContrib)) + parseFloat(convertToZero(pagibigLoan)) + parseFloat(convertToZero(cashBond)) + parseFloat(convertToZero(cashAdvance)) + parseFloat(convertToZero(adjustmentDeduction));

				//alert(totalDeductions);

				 totalDeductions = totalDeductions.toString().split('e');
	             totalDeductions = Math.round(+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] + 2) : 2)));

	             totalDeductions = totalDeductions.toString().split('e');
	             totalDeductions =  (+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] - 2) : -2))).toFixed(2);

	             $("input[name='totalDeductions_<?php echo $emp_id; ?>']").val(totalDeductions);


	             var totalGrossIncome = $("input[name='grossIncome_<?php echo $emp_id; ?>']").val()
	             var tax = $("input[name='witholdingTax_<?php echo $emp_id; ?>']").val();
	             var nontaxAllowance = $("input[name='nontaxAllowance_<?php echo $emp_id; ?>']").val();
	             var adjustmentAfter = $("input[name='adjustmentAfter<?php echo $emp_id; ?>']").val();
	            // alert(nontaxAllowance);
	            // alert(adjustmentAfter);

	             //alert(totalGrossIncome);
	            // alert(tax);
	             var incentives = <?php echo $incentives; ?>;
	             var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeductions)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));


	             netPay += parseFloat(convertToZero(incentives));
	             netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

	             netPay = netPay.toString().split('e');
	             netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

	             netPay = netPay.toString().split('e');
	             netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

	             $("input[name='netPay_<?php echo $emp_id; ?>']").val(netPay);


	             //alert(netPay);

	              // var datastring = "taxable_income="+totalGrossIncome+"&emp_id=<?php echo $emp_id; ?>&emp_name=<?php echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename; ?>";


		});



		// for adjustment after
		$("input[name='adjustmentAfter<?php echo $emp_id; ?>']").change(function(){

				if ($(this).val() == ""){
	             	$(this).val(0);
	             }

				var totalGrossIncome = $("input[name='grossIncome_<?php echo $emp_id; ?>']").val();
				var totalDeductions = $("input[name='totalDeductions_<?php echo $emp_id; ?>']").val();
				var tax = $("input[name='witholdingTax_<?php echo $emp_id; ?>']").val();
				var allowance = $("input[name='nontaxAllowance_<?php echo $emp_id; ?>']").val();
				var adjustmentAfter = $("input[name='adjustmentAfter<?php echo $emp_id; ?>']").val();

				var incentives = <?php echo $incentives; ?>;
				var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 	var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;
  			 	

				/*alert(totalGrossIncome);
				alert(totalDeductions);
				alert(tax);
				alert(allowance);
				alert(adjustmentAfter);*/
				var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeductions)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(allowance)) + parseFloat(convertToZero(adjustmentAfter));

				netPay += parseFloat(convertToZero(incentives));
				netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

				netPay = netPay.toString().split('e');
	            netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

	            netPay = netPay.toString().split('e');
	            netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

	            $("input[name='netPay_<?php echo $emp_id; ?>']").val(netPay);



		});


		$("input[name='nontaxAllowance_<?php echo $emp_id; ?>']").change(function(){

			     if ($(this).val() == ""){
	             	$(this).val(0);
	             }

				var totalGrossIncome = $("input[name='grossIncome_<?php echo $emp_id; ?>']").val();
				var totalDeductions = $("input[name='totalDeductions_<?php echo $emp_id; ?>']").val();
				var tax = $("input[name='witholdingTax_<?php echo $emp_id; ?>']").val();
				var allowance = $("input[name='nontaxAllowance_<?php echo $emp_id; ?>']").val();
				var adjustmentAfter = $("input[name='adjustmentAfter<?php echo $emp_id; ?>']").val();

				var incentives = <?php echo $incentives; ?>;
				var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 	var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;
  			 	


				/*alert(totalGrossIncome);
				alert(totalDeductions);
				alert(tax);
				alert(allowance);
				alert(adjustmentAfter);*/
				var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeductions)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(allowance)) + parseFloat(convertToZero(adjustmentAfter));


				netPay += parseFloat(convertToZero(incentives));
				netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));



				netPay = netPay.toString().split('e');
	            netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

	            netPay = netPay.toString().split('e');
	            netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

	            $("input[name='netPay_<?php echo $emp_id; ?>']").val(netPay);



		});


		function convertToZero(value){

			if (value == ""){
				value = 0;
			}

			return value;
		}
 	});

	</script>



<?php
	$counter++;
	}while($counter < $count);

} // end of if
else {
	header("Location:../MainForm.php");
}


?>


