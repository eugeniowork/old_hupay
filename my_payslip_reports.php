<?php
session_start();
include "class/connect.php";
include "class/Payroll.php";
include "class/department.php";
include "class/emp_information.php";
include "class/dependent.php";
include "class/BIR_Contribution.php";
include "class/money.php";
include "class/attendance_overtime.php";
include "class/time_in_time_out.php";
include "class/allowance_class.php";
include "class/date.php";
include "class/minimum_wage_class.php";


if (isset($_SESSION["current_payroll_id"])){

$payroll_class = new Payroll;
$department_class = new Department;
$emp_info_class = new EmployeeInformation;
$dependent_class = new Dependent;
$money_class = new Money;
$attendance_ot_class = new Attendance_Overtime;
$attendance_class = new Attendance;
$allowance_class = new Allowance;
$date_class = new date;

$payroll_id = $_SESSION["current_payroll_id"];

$row = $payroll_class->getInfoByPayrollId($payroll_id);


if ($row->datePayroll <= '2018-06-15'){
	//echo "Payslip for the cut off <b>June 11, 2018 - June 25, 2018</b> is temporary hold for updating information purposes. <br/>
	//Thank you! <br/> - bogz";
	require ("reports/fpdf.php");

	$pdf = new PDF_MC_Table("l");
	$pdf->SetMargins("65","35");

	$pdf->AddPage();

	$pdf->SetFillColor(220,220,220); // GRAY
	$pdf->Rect(65, 35, 167, 118, 'F'); //margin-left,margin-top,width,height

	$pdf->Image("img/logo/lloyds logo.png",178,38,8,8);// margin-left,margin-top,width,height

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(0,5,"","LRT",1); // FOR SPACING
	$pdf->Cell(0,5,"LLOYDS FINANCING CORPORATION","LR",1,"R");

	$pdf->Cell(0,5,"","LR",1); // FOR SPACING
	//$pdf->Cell(0,5,"",0,1); // FOR SPACING

	$pdf->SetFont("Arial","BU","7");

	$pdf->Cell(0,5,"Employee No: " . $row->emp_id,"L",0,"L");
	$pdf->Cell(0,5,"Payroll Period: ". $row->CutOffPeriod	,"R",1,"R");

	$pdf->Cell(0,5,"Department: " . $department_class->getDepartmentValue($row->dept_id)->Department,"L",0,"L");
	$pdf->Cell(0,5,"","R",1,"R");


	$min_wage_class = new MinimumWage();
	$min_wage = $min_wage_class->getMinimumWage();

	$current_salary = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;


	//$salary_allowance = 0;
	if ($min_wage >= $current_salary) {
		$withTax = 0;
		//$salary_allowance = round(($current_salary/2) - (($current_salary / 26) *11),2);
	}

	else {
		$withTax = 1;
	}

	// getMinimumWage

	// for dependent count
	//$withTax = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;

	// if with tax merong tax code
	if ($withTax == 1) {

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(0,5,"Name: " . utf8_decode($emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename),1,0,"L");
		$pdf->Cell(0,5,"Tax Code: ". $row->taxCode,0,1,"R");
	}
	// if not with tax wla
	else {
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(0,5,"Name: " . utf8_decode($emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename),1,1,"L");
		//$pdf->Cell(0,5,"Tax Code: " . $taxCode,0,1,"R");
	}

					$row_th = $payroll_class->getPayrollInfoByEmpIdCutOffPeriod($row->emp_id,$row->CutOffPeriod);

				

					$cut_off_13_pay_basic = $row_th->cut_off_13_pay_basic;
					$cut_off_13_pay_allowance = $row_th->cut_off_13_pay_allowance;


					$december_15_2019_13_pay_basic = 0;
					$december_15_2019_13_pay_allowance = 0;

					$december_30_2019_13_pa_basic = 0;
					$december_30_2019_13_pay_allowance = 0;

					$january_15_2020_13_pay_basic = 0;
					$january_15_2020_13_pay_allowance = 0;

					if ($row->CutOffPeriod == "January 11, 2020 - January 25, 2020"){
						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"November 26, 2019 - December 10, 2019") != 0){

							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"November 26, 2019 - December 10, 2019");

							$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
							$december_15_2019_13_pay_allowance = $row_13->allowancePay;
						}

						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"December 11, 2019 - December 25, 2019") != 0){
							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"December 11, 2019 - December 25, 2019");

							$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
							$december_30_2019_13_pay_allowance = $row_13->allowancePay;
						}

						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"December 26, 2019 - January 10, 2020") != 0){

							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"December 26, 2019 - January 10, 2020");

							$january_15_2020_13_pay_basic = $row_13->ratePayPrd;
							$january_15_2020_13_pay_allowance = $row_13->allowancePay;
						}


						$cut_off_13_pay_basic += round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic/12,2);


						$cut_off_13_pay_allowance += round($december_15_2019_13_pay_allowance /12 ,2) + round($december_30_2019_13_pay_allowance /12,2) + round($january_15_2020_13_pay_allowance/12,2);
					}


						

	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,5,"Earnings","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"Hour",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"Rate",0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,5,"Amount","R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,5,"Deductions",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,5,"Amount","R",1);

	if ($row->datePayroll <= "2020-01-30"){

		// one line
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,2,"BASIC PAY","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,2,$money_class->getMoney($row->salary / 2),"R",0,"R");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(44,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(43,2,"","R",1);

	}
	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,5,"","L",0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,5,"","R",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,5,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"REG_OT","L",0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_OThour,2),0,0);


	//$hr_rate = ($emp_info_class->getEmpInfoByRow($row->emp_id)->Salary/26)/8;

	//$reg_ot_hr_rate =($hr_rate*.25) + $hr_rate;

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4, $money_class->getMoney($row->regularOT),"R",0,"R");



	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"WITHHOLDING TAX",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->Tax),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(15,4,($attendance_ot_class->getOvertimeHolidayOt($row->emp_id)/60),0,0);
	$pdf->Cell(15,4,round($row->rd_OThour),0,0);

	//$holiday_ot_hr_rate =($hr_rate*2);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(20,4,$money_class->getMoney($row->holidayOT),"R",0,"R");
	$pdf->Cell(20,4,$money_class->getMoney($row->restdayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"SSSPREM",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssDeduction),"R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"REG_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_holiday_OThour,2),0,0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->reg_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"SSSLOAN",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssLoan),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"SPE_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->special_holiday_OThour,2),0,0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->special_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(20,4,$money_class->getMoney($row->holidayRestdayOT),"R",0,"R");
	$pdf->Cell(20,4,$money_class->getMoney($row->special_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PHILHEALTH",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->philhealthDeduction),"R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_REG_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_reg_holiday_OThour,2),0,0);


	$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);

	$basic_salary = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;

	$tardiness_rate = (($basic_salary + $allowance)/26)/8;


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_reg_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->rd_reg_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PAGIBIG",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->pagibigDeduction),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_SPE_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_special_holiday_OThour,2),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_special_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->rd_special_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PAGIBIGLOAN",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->pagibigLoan),"R",1);

	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"TARDINESS","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->tardinessHour,2),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->tardinessRate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->Tardiness),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"CASHBOND",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->CashBond),"R",1);


	// one line
	

	if ($row->datePayroll <= "2020-01-30"){
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,4,"ABSENCES","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,round($row->absencesHour,2),0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,round($row->absencesRate,2),0,0,"C");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,4,$money_class->getMoney($row->Absences),"R",0,"R");
	}

	else if ($row->datePayroll >= "2020-02-15"){
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,4,"PRESENT","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,"-",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,"-",0,0,"C");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,4,$money_class->getMoney($row->present_amount),"R",0,"R");
	}

	

	

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"CASHADVANCE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->cashAdvance),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"ADJUSTMENT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->adjustmentAfter + $row->adjustmentBefore),"R",0,"R");

	$total = $row->Allowance + $row->totalGrossIncome;

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"GROSS INCOME","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);




	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney(round($row->totalGrossIncome,2)),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"NONTAX ALLOWANCE","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->NontaxAllowance),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,"","R",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"TOTAL","LB",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney(round($row->adjustmentAfter + $row->adjustmentBefore + $row->totalGrossIncome + $row->NontaxAllowance,2)),"RB",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"TOTAL DEDUCTIONS","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssDeduction + $row->sssLoan + $row->philhealthDeduction + $row->pagibigLoan + $row->CashBond + $row->cashAdvance + $row->pagibigDeduction +$row->Tax),"RB",1);

	// for single row
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(80,4,"",1,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(87,4,"",1,1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"BASIC RATE","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->basicRate),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"DAILY RATE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->dailyRate),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD GROSS",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdGross),0,0);


	if ($row->datePayroll == "2020-01-30"){

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(25,4,"13th Month Pay(12/15/2019-01/30/2020)",0,0);
	}


	if ($row->datePayroll > "2020-01-30"){
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(25,4,"13th Month Pay(".$row->datePayroll.")",0,0);
	}

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOWANCE","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->Allowance),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"DAILY ALLOW",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->dailyAllowance),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD ALLOWANCE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdAllowance),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"BASIC",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$cut_off_13_pay_basic,"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"RATE/ PAY PRD",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ratePayPrd),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD W/ TAX",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdWithTax),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOWANCE",0,0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$cut_off_13_pay_allowance,"R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"","BL",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOW/ PAY","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->allowancePay),"RB",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"CASH ADV. BAL","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->cashAdvBal),"B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"NET PAY","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$money_class->getMoney($row->netPay),"RB",1);



	// for single row
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(80,4,$date_class->dateFormat($row->datePayroll)." PAYOUT","LBR",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(87,4,"","RB",1);




	$pdf->output("I",$date_class->dateFormat($row->datePayroll) . "_" .$emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename.'.pdf');
}
else {

	require ("reports/fpdf.php");

	$pdf = new PDF_MC_Table("l");
	$pdf->SetMargins("65","35");

	$pdf->AddPage();

	$pdf->SetFillColor(220,220,220); // GRAY
	$pdf->Rect(65, 35, 167, 118, 'F'); //margin-left,margin-top,width,height

	$pdf->Image("img/logo/lloyds logo.png",178,38,8,8);// margin-left,margin-top,width,height

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(0,5,"","LRT",1); // FOR SPACING
	$pdf->Cell(0,5,"LLOYDS FINANCING CORPORATION","LR",1,"R");

	$pdf->Cell(0,5,"","LR",1); // FOR SPACING
	//$pdf->Cell(0,5,"",0,1); // FOR SPACING

	$pdf->SetFont("Arial","BU","7");

	$pdf->Cell(0,5,"Employee No: " . $row->emp_id,"L",0,"L");
	$pdf->Cell(0,5,"Payroll Period: ". $row->CutOffPeriod	,"R",1,"R");

	$pdf->Cell(0,5,"Department: " . $department_class->getDepartmentValue($row->dept_id)->Department,"L",0,"L");
	$pdf->Cell(0,5,"","R",1,"R");


	$min_wage_class = new MinimumWage();
	$min_wage = $min_wage_class->getMinimumWage();

	$current_salary = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;


	//$salary_allowance = 0;
	if ($min_wage >= $current_salary) {
		$withTax = 0;
		//$salary_allowance = round(($current_salary/2) - (($current_salary / 26) *11),2);
	}

	else {
		$withTax = 1;
	}

	// getMinimumWage

	// for dependent count
	//$withTax = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;

	// if with tax merong tax code
	if ($withTax == 1) {

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(0,5,"Name: " . utf8_decode($emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename),1,0,"L");
		$pdf->Cell(0,5,"Tax Code: ". $row->taxCode,0,1,"R");
	}
	// if not with tax wla
	else {
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(0,5,"Name: " . utf8_decode($emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename),1,1,"L");
		//$pdf->Cell(0,5,"Tax Code: " . $taxCode,0,1,"R");
	}

	$row_th = $payroll_class->getPayrollInfoByEmpIdCutOffPeriod($row->emp_id,$row->CutOffPeriod);

				

					$cut_off_13_pay_basic = $row_th->cut_off_13_pay_basic;
					$cut_off_13_pay_allowance = $row_th->cut_off_13_pay_allowance;


					$december_15_2019_13_pay_basic = 0;
					$december_15_2019_13_pay_allowance = 0;

					$december_30_2019_13_pa_basic = 0;
					$december_30_2019_13_pay_allowance = 0;

					$january_15_2020_13_pay_basic = 0;
					$january_15_2020_13_pay_allowance = 0;

					if ($row->CutOffPeriod == "January 11, 2020 - January 25, 2020"){
						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"November 26, 2019 - December 10, 2019") != 0){

							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"November 26, 2019 - December 10, 2019");

							$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
							$december_15_2019_13_pay_allowance = $row_13->allowancePay;
						}

						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"December 11, 2019 - December 25, 2019") != 0){
							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"December 11, 2019 - December 25, 2019");

							$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
							$december_30_2019_13_pay_allowance = $row_13->allowancePay;
						}

						if ($payroll_class->getCountCutOff13MonthPayOld($row_th->emp_id,"December 26, 2019 - January 10, 2020") != 0){

							$row_13 = $payroll_class->getCutOff13MonthPayOld($row_th->emp_id,"December 26, 2019 - January 10, 2020");

							$january_15_2020_13_pay_basic = $row_13->ratePayPrd;
							$january_15_2020_13_pay_allowance = $row_13->allowancePay;
						}


						$cut_off_13_pay_basic += round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic/12,2);


						$cut_off_13_pay_allowance += round($december_15_2019_13_pay_allowance /12 ,2) + round($december_30_2019_13_pay_allowance /12,2) + round($january_15_2020_13_pay_allowance/12,2);
					}

	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,5,"Earnings","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"Hour",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"Rate",0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,5,"Amount","R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,5,"Deductions",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,5,"Amount","R",1);

	if ($row->datePayroll <= "2020-01-30"){
		// one line
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,2,"BASIC PAY","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,2,$money_class->getMoney($row->salary / 2),"R",0,"R");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(44,2,"",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(43,2,"","R",1);
	}

	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,5,"","L",0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,5,"","R",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,5,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,5,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"REG_OT","L",0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_OThour,2),0,0);


	//$hr_rate = ($emp_info_class->getEmpInfoByRow($row->emp_id)->Salary/26)/8;

	//$reg_ot_hr_rate =($hr_rate*.25) + $hr_rate;

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4, $money_class->getMoney($row->regularOT),"R",0,"R");



	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"WITHHOLDING TAX",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->Tax),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(15,4,($attendance_ot_class->getOvertimeHolidayOt($row->emp_id)/60),0,0);
	$pdf->Cell(15,4,round($row->rd_OThour),0,0);

	//$holiday_ot_hr_rate =($hr_rate*2);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(20,4,$money_class->getMoney($row->holidayOT),"R",0,"R");
	$pdf->Cell(20,4,$money_class->getMoney($row->restdayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"SSSPREM",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssDeduction),"R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"REG_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_holiday_OThour,2),0,0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->reg_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->reg_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"SSSLOAN",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssLoan),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"SPE_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->special_holiday_OThour,2),0,0);


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->special_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	//$pdf->Cell(20,4,$money_class->getMoney($row->holidayRestdayOT),"R",0,"R");
	$pdf->Cell(20,4,$money_class->getMoney($row->special_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PHILHEALTH",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->philhealthDeduction),"R",1);



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_REG_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_reg_holiday_OThour,2),0,0);


	$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);

	$basic_salary = $emp_info_class->getEmpInfoByRow($row->emp_id)->Salary;

	$tardiness_rate = (($basic_salary + $allowance)/26)/8;


	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_reg_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->rd_reg_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PAGIBIG",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->pagibigDeduction),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"RD_SPE_HLDY_OT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_special_holiday_OThour,2),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->rd_special_holiday_OTrate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->rd_special_holidayOT),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"PAGIBIGLOAN",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->pagibigLoan),"R",1);

	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"TARDINESS","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->tardinessHour,2),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,round($row->tardinessRate,2),0,0,"C");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->Tardiness),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"CASHBOND",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->CashBond),"R",1);


	// one line
	if ($row->datePayroll <= "2020-01-30"){
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,4,"ABSENCES","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,round($row->absencesHour,2),0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,round($row->absencesRate,2),0,0,"C");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,4,$money_class->getMoney($row->Absences),"R",0,"R");
	}

	else if ($row->datePayroll >= "2020-02-15"){
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(30,4,"PRESENT","L",0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,"-",0,0);

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(15,4,"-",0,0,"C");

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(20,4,$money_class->getMoney($row->present_amount),"R",0,"R");
	}

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"CASHADVANCE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->cashAdvance),"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"ADJUSTMENT","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney($row->adjustmentAfter + $row->adjustmentBefore),"R",0,"R");

	$total = $row->Allowance + $row->totalGrossIncome;

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"GROSS INCOME","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);




	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney(round($row->totalGrossIncome,2)),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);



	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'live_db_hr_payroll';
	// mysqli connect
	$conn = mysqli_connect($servername,$username,$password,$dbname);


	// for looping purposes
	$select_allow_qry = "SELECT * FROM tb_payslip_allowance WHERE payroll_id = '$row->payroll_id'";
	if ($result_allow = mysqli_query($conn,$select_allow_qry)){
		while($row_allow = mysqli_fetch_object($result_allow)){

			if ($row_allow->allowanceValue > 0){
				// one line
				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(30,4,$row_allow->allowanceType,"L",0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(15,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(20,4,$money_class->getMoney($row_allow->allowanceValue),"R",0,"R");

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(44,4,"",0,0);

				$pdf->SetFont("Arial","B","7");
				$pdf->Cell(43,4,"","R",1);
			}
		}
	}




	



	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,"","R",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(30,4,"TOTAL","LB",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(20,4,$money_class->getMoney(round($row->adjustmentAfter + $row->adjustmentBefore + $row->totalGrossIncome + $row->NontaxAllowance,2)),"RB",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(44,4,"TOTAL DEDUCTIONS","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(43,4,$money_class->getMoney($row->sssDeduction + $row->sssLoan + $row->philhealthDeduction + $row->pagibigLoan + $row->CashBond + $row->cashAdvance + $row->pagibigDeduction +$row->Tax),"RB",1);

	// for single row
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(80,4,"",1,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(87,4,"",1,1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"BASIC RATE","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->basicRate),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"DAILY RATE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->dailyRate),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD GROSS",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdGross),0,0);


	if ($row->datePayroll == "2020-01-30"){

		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(25,4,"13th Month Pay(12/15/2019-01/30/2020)",0,0);
	}

	else {
		$pdf->SetFont("Arial","B","7");
		$pdf->Cell(25,4,"13th Month Pay(".$row->datePayroll.")",0,0);
	}

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,"","R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOWANCE","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->Allowance),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"DAILY ALLOW",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->dailyAllowance),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD ALLOWANCE",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdAllowance),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"BASIC",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$cut_off_13_pay_basic,"R",1);


	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"","L",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"RATE/ PAY PRD",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ratePayPrd),"R",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"YTD W/ TAX",0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->ytdWithTax),0,0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOWANCE",0,0,"7");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$cut_off_13_pay_allowance,"R",1);

	




	// one line
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"","BL",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,"","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"ALLOW/ PAY","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->allowancePay),"RB",0,"R");

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"CASH ADV. BAL","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(15,4,$money_class->getMoney($row->cashAdvBal),"B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(25,4,"NET PAY","B",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(22,4,$money_class->getMoney($row->netPay),"RB",1);



	// for single row
	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(80,4,$date_class->dateFormat($row->datePayroll)." PAYOUT","LBR",0);

	$pdf->SetFont("Arial","B","7");
	$pdf->Cell(87,4,"","RB",1);




	$pdf->output("I",$date_class->dateFormat($row->datePayroll) . "_" .$emp_info_class->getEmpInfoByRow($row->emp_id)->Lastname . ", " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Firstname . " " . $emp_info_class->getEmpInfoByRow($row->emp_id)->Middlename.'.pdf');

}

	

}
else {
	header("Location:MainForm.php");
}





?>