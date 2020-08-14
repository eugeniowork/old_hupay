<?php
session_start();
ini_set('max_execution_time', 300);
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/allowance_class.php";
include "../class/year_total_deduction_class.php";
include "../class/simkimban_class.php";
include "../class/salary_loan_class.php";
include "../class/cut_off.php";
include "../class/date.php";
include "../class/Payroll.php";
include "../class/minimum_wage_class.php";
include "../class/dependent.php";
include "../class/attendance_overtime.php";
include "../class/time_in_time_out.php";
include "../class/BIR_Contribution.php";
include "../class/SSS_Contribution.php";
include "../class/Philhealth_Contribution.php";
include "../class/Pagibig_Contribution.php";
include "../class/sss_loan_class.php";
include "../class/pagibig_loan_class.php";
include "../class/cashbond_class.php";
include "../class/working_hours_class.php";
include "../class/holiday_class.php";
include "../class/working_days_class.php";



if (isset($_POST["submit_payroll"])){

	$submit_payroll = $_POST["submit_payroll"];

	// if not edited
	if ($submit_payroll){
		$emp_info_class = new EmployeeInformation;
		$allowance_class = new Allowance;
		$ytd_class = new YearTotalDeduction;
		$simkimban_class = new Simkimban;
		$salary_loan_class = new SalaryLoan;
		$cut_off_class = new CutOff;
		$date_class = new date;
		$payroll_class = new Payroll;
		$min_wage_class = new MinimumWage;
   		$dependent_class = new Dependent;
   		$attendance_ot_class = new Attendance_Overtime;
   		$attendance_class = new Attendance;
   		$bir_contrib_class = new BIR_Contribution;
   		$sss_contrib_class = new SSS_Contribution;
   		$philhealth_contrib_class = new Philhealth_Contribution;
   		$pagibig_contrib_class = new Pagibig_Contribution;
   		$sss_loan_class = new SSSLoan;
   		$pagibig_loan_class = new PagibigLoan;
   		$cashbond_class = new Cashbond;
   		$working_hours_class = new WorkingHours;
   		$holiday_class = new Holiday;

   		 $working_days_class = new WorkingDays;

		$all_emp_id = $emp_info_class->getEmpIdAllActiveEmp();

		//echo $all_emp_id;
		
		$current_emp_id = explode("#",$all_emp_id); // for exploding in order to make a loop

		$count = $emp_info_class->getCountActiveEmp(); // active count

		
		// first finding the number of days in a cut off
		$cut_off_count = $cut_off_class->getCutOffAttendanceDateCount();
		$holiday_cut_off_count = $holiday_class->holidayCutOffTotalCount();

		// for breakingdown all emp active id's
		$counter = 0;
		do {

			$emp_id = $current_emp_id[$counter];


			/*if ($emp_id == 68 || $emp_id == 85){

				$days = ($cut_off_count - $holiday_cut_off_count)+ 2; // sa susunod na ung codes pra dynamic kung ilang sabado
			}


			else {
				$days = $cut_off_count - $holiday_cut_off_count;
			}*/

			$row = $emp_info_class->getEmpInfoByRow($emp_id);

			$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);
			$day_from = $row_wd->day_from;
			$day_to = $row_wd->day_to;

			$working_days_count = $cut_off_class->getCutOffAttendanceDateCountToRunningBalance($day_from,$day_to);

			$days = $working_days_count; //- $holiday_cut_off_count;


			$dept_id = $row->dept_id;
			$company_id = $row->company_id;
			$cutOffPeriod = $cut_off_class->getCutOffPeriodLatest();
			$salary = round($row->Salary,2);

			$min_wage = $min_wage_class->getMinimumWage();
			$taxCode = "";
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


			$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);
			$dailyAllowance = round(($allowance / 2)/$days,2);


			$row_wh = $working_hours_class->getWorkingHoursInfoById($row->working_hours_id);
										

			$timeFrom = $row_wh->timeFrom;
			$timeTo = $row_wh->timeTo;

			$timeFrom = strtotime($timeFrom);
			$timeTo = strtotime($timeTo);

			$total_hours = (($timeTo - $timeFrom) / 3600) - 1;

			 // for getting regular ot by minutes
		    //$regularOTmin = round($attendance_ot_class->getOvertimeRegularOt($emp_id)/60,2); // 90/60 = 1.5

		    // for getting regular holiday ot by minutes
		   // $regHolidayOTmin = round($attendance_ot_class->getOvertimeRegularHolidayOt($emp_id)/60,2); // 90/60 = 1.5


		    /*
		    // for special holiday ot
		    $specialHolidayOTmin = round($attendance_ot_class->getOvertimeSpecialHolidayOt($emp_id)/60,2); // 90/60 = 1.5


		    // for rd regular holiday ot // getOvertimeRDRegularHolidayOt
		    $rd_regularHolidayOTmin = round($attendance_ot_class->getOvertimeRDRegularHolidayOt($emp_id)/60,2); // 90/60 = 1.5


		    // for rd speacial holiday ot // getOvertimeRDSpecialHolidayOt
		    $rd_specialHolidayOTmin = round($attendance_ot_class->getOvertimeRDSpecialHolidayOt($emp_id)/60,2); // 90/60 = 1.5
			 */

		    // for getting rd ot
		   // $restdayOTmin = round($attendance_ot_class->getOvertimeRestdayOt($emp_id)/60,2); // 90/60 = 1.5
		   

		    //echo $allowance;
			//$daily_rate =  $row->Salary / 26;
		    $daily_rate =  (($row->Salary + $allowance ) / 2)/ $days;
			//$hourly_rate = $daily_rate / 8;
			$hourly_rate = round($daily_rate / $total_hours,2);
			/*if ($emp_id == 68 || $emp_id == 85){ // kay kuya drew at kay kuya vannie
				$hourly_rate = $daily_rate / 12;
			}*/
			//$new_daily_rate = $hourly_rate * 9;

			// for OT's rate
			$regular_ot_rate = round($hourly_rate + ($hourly_rate * .25),2);
			//$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2); 
			$reg_ot_amount = $_POST["regOT_".$emp_id];
			$regularOTmin = round($reg_ot_amount / $regular_ot_rate,2); // for computation
			//echo $regularOTmin_2;



			$regHoliday_ot_rate = round($hourly_rate,2);
			$regHoliday_ot_amount = round($_POST["regHolidayOT_".$emp_id],2); 
			$regHolidayOTmin = round($regHoliday_ot_amount/$regHoliday_ot_rate,2);


			$specialHoliday_ot_rate = round($hourly_rate * .3,2);
			$specialHoliday_ot_amount = round($_POST["specialHolidayOT_".$emp_id],2); 
			$specialHolidayOTmin = round($specialHoliday_ot_amount/$specialHoliday_ot_rate,2);

			$rdRegularHoliday_ot_rate = round($hourly_rate * 2.6,2);
			$rdRegularHoliday_ot_amount = round($_POST["rdREgHolidayOT_".$emp_id],2); 
			$rd_regularHolidayOTmin = round($rdRegularHoliday_ot_amount / $rdRegularHoliday_ot_rate,2);

			$rdSpecialHoliday_ot_rate = round($hourly_rate + ($hourly_rate * .6) ,2);
			$rdSpecialHoliday_ot_amount = round($_POST["rdSpecialHolidayOT_".$emp_id],2); 
			$rd_specialHolidayOTmin = round($rdSpecialHoliday_ot_amount / $rdSpecialHoliday_ot_rate,2);

			$rd_ot_rate = round($hourly_rate + ($hourly_rate * .3),2);
			$rd_ot_amount = round($_POST["rdOT_".$emp_id],2); 
			$restdayOTmin = round($rd_ot_amount / $rd_ot_rate,2);


			//$bio_id 
			//$attendance_rate = round(((($row->Salary + $allowance) / 22) /9),2);
			$attendance_rate = 0;
			//$tardinessMin = round($attendance_class->getTardiness($row->bio_id) / 60,2);
			//echo $tardinessMin;
			//echo $tardinessMin . "<br/>";
			//echo $attendance_rate;
			$tardinessAmount = round($_POST["tardiness_".$emp_id],2);
			//$tardinessMin = round($tardinessAmount / $attendance_rate,2);
			$tardinessMin = 0;


			$absencesRate = 0; // 1300/22 , 61.375 , 138.0
			//echo $absencesRate;
			//$absencesMin = round($attendance_class->getAbsences($row->bio_id) * 9,2);

			//echo $absencesMin;

			//echo $absencesMin; 

			//echo $absencesRate;
			$absencesAmount = 0;
			$absencesMin = 0;


			// for present
			$present = $attendance_class->getPresentToPayroll($row->bio_id,$day_from,$day_to);
			$present_amount = round($_POST["present_".$emp_id],2);

			$adjustmentEarnings = $_POST["adjustment_".$emp_id];
			$adjustmentDeduction = $_POST["adjustmentdeduction_".$emp_id];
			$adjustmentAfterTax = $_POST["adjustmentAfter".$emp_id];

			//$adjustmentBefore = $adjustmentEarnings - $adjustmentDeduction;
			$adjustmentBefore = $adjustmentEarnings;
			
			$totalAdjustment = ($adjustmentEarnings - $adjustmentDeduction) + $adjustmentAfterTax; 

			$adjustmentAfterTax = $adjustmentAfterTax + $adjustmentDeduction;

		  // echo $emp_id
			$totalGrossIncome = $_POST["grossIncome_".$emp_id];
			$nontaxAllowance = $_POST["nontaxAllowance_".$emp_id];

			$totalEarnings = $totalGrossIncome + $nontaxAllowance + $totalAdjustment;
			$tax = $_POST["witholdingTax_".$emp_id];
			$sssContribution = $_POST["sssContrib_".$emp_id];
			$philhealthContribution = $_POST["philhealthContrib_".$emp_id];
			$pagibigContribution = $_POST["pagibigContrib_".$emp_id];
			$sss_loan_amount = $_POST["sssLoan_".$emp_id];
			$pagibig_loan_amount = $_POST["pagibigLoan_".$emp_id];
			$totalCashAdvance = $_POST["cashAdvance_".$emp_id];
			$cashBond = $_POST["cashBond_".$emp_id];
			$totalDeductions = $_POST["totalDeductions_".$emp_id];
			$netPay = $_POST["netPay_".$emp_id];
			$basicRate = $salary;
			//$dailyRate = round($basicRate / 22,2);
			$dailyRate = (($row->Salary) / 2)/ $days;
			//$dailyAllowance = round($allowance/22,2);
			$dailyAllowance =  (($allowance ) / 2)/ $days;
			$ratePayPeriod = round($salary / 2,2);
			$allowancePay = round($allowance/2,2);

			$row_ytd = $ytd_class->getInfoByEmpId($emp_id);

			


			// for simkimban
			$existSimkimban = $simkimban_class->existPendingSimkimban($emp_id);			
			$simkimban_bal = 0;
			if ($existSimkimban != 0) {
				//echo "wew";
				//$simkimban_row = $simkimban_class->getInfoBySimkimbanToPayrollEmpId($emp_id);
				$simkimban_bal = $simkimban_class->getAllRemainingBalanceSimkimban($emp_id);
			}

			//echo $simkimban_bal;

			// for salary loan
			$existSalaryLoan = $salary_loan_class->existSalaryLoan($emp_id);
			$salary_loan_bal = 0;
			if ($existSalaryLoan != 0){

				$salary_loan_bal = $salary_loan_class->getAllSalaryLoan($emp_id);
				//$salary_loan_bal = $salary_loan_row->remainingBalance;
			}
			
			$currentRemainingBal = $simkimban_bal + $salary_loan_bal;
			//echo $currentRemainingBal;
			//echo $currentRemainingBal . "<br/>";

		    //$currentRemainingBal = $simkimban_bal + $salary_loan_bal;
		   	$cashAdvanceBal = $currentRemainingBal - $totalCashAdvance;
		   //	echo $cashAdvanceBal;
		   	$datePayroll = $cut_off_class->getDatePayroll();

		   	if (date_format(date_create($datePayroll),'m-d') == "01-15"){
				$ytdGross =  $totalGrossIncome;
				$ytdAllowance = $nontaxAllowance;
				$ytdTax = $tax;
			}
			else {
				$ytdGross = $row_ytd->ytd_Gross + $totalGrossIncome;
				$ytdAllowance = $row_ytd->ytd_Allowance + $nontaxAllowance;
				$ytdTax = $row_ytd->ytd_Tax + $tax;
			}
		   	
	   		$approveStat = 0;
		   	$dateCreated = $date_class->getDate();
		   	$remarks = $_POST["adjustmentRemarks_".$emp_id];
			
			/*
			echo "Emp id: " .$emp_id. "<br/>";
			echo "Dept id: ".$dept_id."<br/>";
			
			echo "Cutoff Period: ".$cutOffPeriod ."<br/>";
			echo "Salary: ".$salary."<br/>";
			echo "Tax Code: ".$taxCode."<br/>";
			echo "Reg OT Hour: ".$regularOTmin."<br/>";
			echo "Reg OT Rate: ".$regular_ot_rate."<br/>";
			echo "Reg OT Amount: ".$reg_ot_amount."<br/>";	
			echo "RD OT Hour: ".$restdayOTmin."<br/>";
			echo "RD OT Rate: ".$rd_ot_rate."<br/>";
			echo "RD OT Amount: ".$rd_ot_amount."<br/>";
			echo "Reg Holiday OT Hour: ".$regHolidayOTmin."<br/>";
			echo "Reg Holiday OT Rate: ".$regHoliday_ot_rate."<br/>";
			echo "Reg Holiday OT Amount: ".$regHoliday_ot_amount."<br/>";
			echo "Special Holiday OT Hour: ".$specialHolidayOTmin."<br/>";
			echo "Special Holiday OT Rate: ".$specialHoliday_ot_rate."<br/>";
			echo "Special Holiday OT Amount: ".$specialHoliday_ot_amount."<br/>";
			echo "RD Reg Holiday OT Hour: ".$rd_regularHolidayOTmin."<br/>";
			echo "RD Reg Holiday OT Rate: ".$rdRegularHoliday_ot_rate."<br/>";
			echo "RD Reg Holiday OT Amount: ".$rdRegularHoliday_ot_amount."<br/>";
			echo "RD Special Holiday OT Hour: ".$rd_specialHolidayOTmin."<br/>";
			echo "RD Special Holiday OT Rate: ".$rdSpecialHoliday_ot_rate."<br/>";
			echo "RD Special Holiday OT Amount: ".$rdSpecialHoliday_ot_amount."<br/>";
			echo "Tardiness Hour: ".$tardinessMin."<br/>";
			echo "Tardiness Rate: ".$attendance_rate."<br/>";
			echo "Tardiness Amount: ".$tardinessAmount."<br/>";
			echo "Absences Hour: ".$absencesMin."<br/>";
			echo "Absences Rate: ".$absencesRate."<br/>";
			echo "Absences Amount: ".$absencesAmount."<br/>";
			echo "Adjustment: ".$totalAdjustment."<br/>";
			echo "Total Gross Income: ".$totalGrossIncome."<br/>";
			echo "Nontax Allowance: ".$nontaxAllowance."<br/>";
			echo "Total Earnings: ".$totalEarnings."<br/>";
			echo "Tax: ".$tax."<br/>";
			echo "SSS Deduction: ".$sssContribution."<br/>";
			echo "Philhealth Deduction: ".$philhealthContribution."<br/>";
			echo "Pagibig Dedecution: " . $pagibigContribution."<br/>";
			echo "SSS Loan: ".$sss_loan_amount."<br/>";
			echo "Pagibig Loan: ".$pagibig_loan_amount."<br/>";
			echo "Cash Advance: ". $totalCashAdvance. "<br/>";
			echo "Cash Bond: ".$cashBond."<br/>";
			echo "Total Deductions: ".$totalDeductions."<br/>";
			echo "Net Pay: ".$netPay."<br/>";
			echo "Basic Rate: ".$basicRate."<br/>";
			echo "Allowance: ".$allowance."<br/>";
			echo "Daily Rate: ".$dailyRate."<br/>";
			echo "Daily Allowance: ".$dailyAllowance."<br/>";
			echo "Rate Pay Period: ".$ratePayPeriod."<br/>";
			echo "Allowance Pay: ".$allowancePay."<br/>";
			echo "YTD Gross: ". $ytdGross. "<br/>";
			echo "YTD Allowance: ".$ytdAllowance."<br/>";
			echo "YTD With Tax: ".$ytdTax."<br/>";
			echo "Cash Advance Balance: ".$cashAdvanceBal."<br/>";
			echo "Date Payroll: ".$datePayroll."<br/>";
			echo "Payroll Status: ".$approveStat."<br/>";
			echo "Date Created: ".$dateCreated."<br/>";
			/*
			//echo "<br/>";*/

			// for insert data

			// cutOffPeriod

			$december_15_2019_13_pay_basic = 0;
			$december_15_2019_13_pay_allowance = 0;

			$december_30_2019_13_pa_basic = 0;
			$december_30_2019_13_pay_allowance = 0;

			$january_15_2020_13_pay_basic = 0;
			$january_15_2020_13_pay_allowance = 0;

			$cut_off_13_pay_basic = round($ratePayPeriod / 12,2);		
			$cut_off_13_pay_allowance = round($allowancePay / 12,2);


			$payroll_class->insertPayroll($emp_id,$dept_id,$company_id,$cutOffPeriod,$salary,$taxCode,$regularOTmin,$regular_ot_rate,$reg_ot_amount,
								  $restdayOTmin,$rd_ot_rate,$rd_ot_amount,$regHolidayOTmin,$regHoliday_ot_rate,$regHoliday_ot_amount,
								  $specialHolidayOTmin,$specialHoliday_ot_rate,$specialHoliday_ot_amount,
								  $rd_regularHolidayOTmin,$rdRegularHoliday_ot_rate,$rdRegularHoliday_ot_amount,
								  $rd_specialHolidayOTmin,$rdSpecialHoliday_ot_rate,$rdSpecialHoliday_ot_amount,
								  $tardinessMin,$attendance_rate,$tardinessAmount,
								  $absencesMin,$absencesRate,$absencesAmount,$present,$present_amount,$adjustmentEarnings,$adjustmentDeduction,$adjustmentBefore,$adjustmentAfterTax,
								  $totalAdjustment,$totalGrossIncome,$nontaxAllowance,$totalEarnings,$tax,
								  $sssContribution,$philhealthContribution,$pagibigContribution,$sss_loan_amount,
								  $pagibig_loan_amount,$totalCashAdvance,$cashBond,$totalDeductions,$netPay,$basicRate,
								  $allowance,$dailyRate,$dailyAllowance,$ratePayPeriod,$allowancePay,$cut_off_13_pay_basic,$cut_off_13_pay_allowance,
								  $ytdGross,$ytdAllowance,$ytdTax,$cashAdvanceBal,$datePayroll,$remarks,$approveStat,$dateCreated);
			

			//$payroll_class->insertPayroll();
			

			//echo "<br/>";
			$counter++;
		}while($counter < $count);


		
		// for saving an entry for approval
		$payroll_class->insertPayrollApproval($cutOffPeriod,'3',$dateCreated);


		// for notifications


		$_SESSION["submit_payroll_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Payroll for the <b>Cut off $cutOffPeriod</b> is successfully submitted</center>";
		header("Location:../generate_payroll.php");
	
	}

	else {
		header("Location:../MainForm.php");
	}


}

else {
	header("Location:../MainForm.php");
}

?>