<?php
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/cut_off.php";
include "../class/Payroll.php";
include "../class/department.php";
include "../class/minimum_wage_class.php";
include "../class/allowance_class.php";
include "../class/time_in_time_out.php";
	
if (isset($_POST["emp_name"]) && isset($_POST["cutOffPeriod"]) && isset($_POST["year"])){
	$emp_name = $_POST["emp_name"];
	$cutOffPeriod = $_POST["cutOffPeriod"];
	$year = $_POST["year"];

	$emp_info_class = new EmployeeInformation;
	$cut_off_class = new CutOff;
	$payroll_info_class = new Payroll;
	$department_class = new Department;
	$min_wage_class = new MinimumWage;
	$allowance_class = new Allowance;
	$attendance_class = new Attendance;

	
	// check if the name is existing
	//if ($emp_info_class->checkExistEmployeeName($emp_name) == 1){
	//	echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>$emp_name</b> does not exist to <b>Employee Name List</b>.";
	//}

	// check if cut off is existing
	if ($cut_off_class->checkExistCutOffPeriod($cutOffPeriod) == 1){
		echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>$cutOffPeriod</b> does not exist to <b>Cut Off Period List</b>.";
	}

	// success
	else {


		if ($cutOffPeriod == "December 26 - January 10"){
			$cut_off = explode("-",$cutOffPeriod);
			$dateFrom = substr($cut_off[0],0,-1);
			$dateFrom = $dateFrom.", " .($year - 1);

			$dateTo = substr($cut_off[1],1);
			$dateTo = $dateTo.", " .$year;
		}

		else {
			$cut_off = explode("-",$cutOffPeriod);
			$dateFrom = substr($cut_off[0],0,-1);
			$dateFrom = $dateFrom.", " .$year;

			$dateTo = substr($cut_off[1],1);
			$dateTo = $dateTo.", " .$year;
		}

		$final_cut_off_period = $dateFrom . " - " . $dateTo;

		if ($payroll_info_class->checkExistPayrollInformation($emp_name,$final_cut_off_period) == 0){
			echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No information found.";
		}

		else {
			$row = $payroll_info_class->getPayrollInfoByCutOffPeriodEmpName($emp_name,$final_cut_off_period);

			$payroll_id = $row->payroll_id;

			$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);
			$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

			//echo $row->Salary;

			$inCutOff = $min_wage_class->checkMinWageEffectiveDateInCutOff($row->emp_id,($allowance+$row_emp->Salary)); // kung ung increase

			//echo $inCutOff;



			$min_wage = $min_wage_class->getMinimumWage();

			$has_tax = 0;
			if ($min_wage < $row->salary){
				$has_tax = 1;
			}
			//echo $min_wage . "<br/>";
			//echo $has_tax . "<br/>";
			//echo $row->salary;

			$row_dept = $department_class->getDepartmentValue($row->dept_id);
			$department = $row_dept->Department;

			$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


			//echo $final_cut_off_period;

			// check if the cut off period is current cut off period
			 $current_cut_off = $cut_off_class->getCutOffPeriodLatest();

			 $is_cut_off = 0;
			 if ($final_cut_off_period == $current_cut_off){
			 	//echo "Sakop";
			 	if ($payroll_info_class->getPayrollApprovalByCutOffPeriod($current_cut_off) == 1){
			 		$is_cut_off = 1;
			 	}
			 }

			 $totalDeductions = $row->sssDeduction + $row->sssLoan + $row->philhealthDeduction+ $row->pagibigDeduction + $row->pagibigLoan+ $row->CashBond+ $row->cashAdvance;

			 $basicCutOffPay = round($row_emp->Salary / 2,2);
			 if ($inCutOff == 1){
				$basicCutOffPay = round($attendance_class->getBasicPayAmount($row_emp->emp_id),2);
			 }




			 $incentives = $row->totalGrossIncome - ($row->ratePayPrd + ($row->regularOT + $row->restdayOT + $row->reg_holidayOT + $row->special_holidayOT + $row->rd_reg_holidayOT + $row->rd_special_holidayOT) - ($row->Tardiness + $row->Absences));



			 if ($row->datePayroll >= "2020-01-15"){


			 	$incentives = $row->netPay - $row->totalGrossIncome + $row->totalDeductions - $row->Tax - $row->NontaxAllowance - $row->adjustmentAfter - $row->cut_off_13_pay_basic - $row->cut_off_13_pay_allowance;
			 }



			 if ($row->datePayroll == "2020-01-30"){


			 	$cut_off_13_pay_basic = $row->cut_off_13_pay_basic;
				$cut_off_13_pay_allowance = $row->cut_off_13_pay_allowance;

			 	$december_15_2019_13_pay_basic = 0;
				$december_15_2019_13_pay_allowance = 0;

				$december_30_2019_13_pa_basic = 0;
				$december_30_2019_13_pay_allowance = 0;

				$january_15_2020_13_pay_basic = 0;
				$january_15_2020_13_pay_allowance = 0;


				if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019") != 0){

					$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019");

					$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
					$december_15_2019_13_pay_allowance = $row_13->allowancePay;
				}

				if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019") != 0){
					$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019");

					$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
					$december_30_2019_13_pay_allowance = $row_13->allowancePay;
				}

				if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020") != 0){

					$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020");

					$january_15_2020_13_pay_basic = $row_13->ratePayPrd;
					$january_15_2020_13_pay_allowance = $row_13->allowancePay;
				}


				$cut_off_13_pay_basic += round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic/12,2);


				$cut_off_13_pay_allowance += round($december_15_2019_13_pay_allowance /12 ,2) + round($december_30_2019_13_pay_allowance /12,2) + round($january_15_2020_13_pay_allowance/12,2);


				//echo $cut_off_13_pay_basic . " " . $cut_off_13_pay_allowance;


			 	$incentives = $row->netPay - $row->totalGrossIncome + $row->totalDeductions - $row->Tax - $row->NontaxAllowance - $row->adjustmentAfter - $cut_off_13_pay_basic - $cut_off_13_pay_allowance;


			 }


			 if ($row->datePayroll >= "2020-02-15"){


			 	$incentives = $row->netPay - $row->totalGrossIncome + $row->totalDeductions - $row->Tax - $row->NontaxAllowance - $row->adjustmentAfter - $row->cut_off_13_pay_basic - $row->cut_off_13_pay_allowance;
			 }


			 $last_total_gross_income = 0;
			 if (date_format(date_create($row->datePayroll),"d") == "30"){
			 	if ($payroll_info_class->checkExistPayrollLastTotalGrossIncome($row->emp_id) != 0){

				// to get previous totgal gross income
					$last_total_gross_income = $payroll_info_class->payrollLastTotalGrossIncome($row->emp_id);
				}
			 }

?>
		<div class="thumbnail col-sm-12" style="border:1px solid #BDBDBD;font-size:11.5px;">
					<div class="caption">
						<b>
							<form class="form-horizontal" action="" method="post">

								<div class="form-group">
									<div class="col-sm-12">
										<div class="pull-right">
											<img src="img/logo/lloyds logo.png" class="payroll-logo"/> LLOYDS FINANCING CORPORATION
										</div>
									</div>
								</div>

								


								<div class="form-group">
									<div class="col-sm-12" style="">
										<span><u>Employee No: <span id="emp_id"><?php echo $row->emp_id; ?></span></u></span>
										<span class="pull-right"><u>Payroll Period: <span id="cut_off_period"><?php echo $row->CutOffPeriod; ?></span></u></span>
									</div>
								</div>

								<div class="form-group" style="margin-top:-10px;">
									<div class="col-sm-12" style="border-bottom:1px solid #566573 ;">
										<span><u>Department: <?php echo $department; ?></u></span>
										<span class="pull-right"><u>Basic Pay: <?php echo round($row->salary/2,2);  ?><span id="basicPay" style="display:none"><?php echo $basicCutOffPay;  ?></span></u></span>
									</div>
								</div>

								<div class="form-group" style="margin-top:-10px;">
									<div class="col-sm-12" style="border-bottom:1px solid #566573 ;">
										<span>Name: <span id="emp_name"><?php echo $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename; ?></span></span>
										<span class="pull-right">Tax Code: <?php echo $row->taxCode; ?></span> 
										<span id="taxableStatus" style="display:none;"><?php echo $has_tax; ?></span>
									</div>
								</div>


								<?php

									if ($row->datePayroll < "2020-01-30"){

								?>

								<div class="form-group">
									<div class="col-sm-12">
										Incentives: <?php echo number_format($incentives,2); ?>
									</div>
								</div>

								<?php
									}


								?>

								<div class="col-sm-12">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #2471a3 ">Earnings</b></div>

											<div class="form-group" style="margin-top:-10px;">
												<div class="col-sm-2">
													<label class="control-label">Regular OT:</label>


														<input type="text" name="regOT" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->regularOT; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">Restday OT:</label>

														<input type="text" title="earnings" name="rdOT" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->restdayOT; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">Regular Holiday OT:</label>

														<input type="text" title="earnings" name="regHolidayOT" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->reg_holidayOT; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">Special Holiday OT:</label>

														<input type="text" title="earnings" name="specialHolidayOT" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->special_holidayOT; ?>" class="form-control custom-form-control"/> 

												</div>
		

												<div class="col-sm-2">
													<label class="control-label">RD/Regular Holiday OT:</label>

														<input type="text" title="earnings" name="rd_regHolidayOT" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->rd_reg_holidayOT; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">RD/Special Holiday OT:</label>

														<input type="text" name="rd_specialHolidayOT" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->rd_special_holidayOT; ?>" class="form-control custom-form-control"/> 

												</div>

											</div>


											<div class="form-group" style="margin-top:-10px;">
												<div class="col-sm-2">
													<label class="control-label">Tardiness:</label>

														<input type="text" name="tardiness" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->Tardiness; ?>" class="form-control custom-form-control"/> 

												</div>

												<?php


													if ($row->datePayroll <= "2020-01-30"){
												?>

												<div class="col-sm-2">
													<label class="control-label">Absences:</label>

														<input type="text" name="absences" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->Absences; ?>" class="form-control custom-form-control"/> 

												</div>

												<?php

													}

													else if ($row->datePayroll >= "2020-02-15"){
												?>


												<div class="col-sm-2">
													<label class="control-label">Present:</label>

														<input type="text" name="present" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->present_amount; ?>" class="form-control custom-form-control"/> 

												</div>

												<?php

													}
												?>

												<div class="col-sm-2">
													<label class="control-label">Gross income:</label>

														<input type="text" name="grossIncome" id="input_payroll" value="<?php echo $row->totalGrossIncome; ?>" class="form-control custom-form-control"/> 

												</div>


												<div class="col-sm-2">
													<label class="control-label">Adjustment:</label>

														<input type="text" name="adjustmentEarnings" title="earnings" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->adjustmentEarnings; ?>" class="form-control custom-form-control adjustment"/> 

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

														<input type="text" name="sssContribution" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->sssDeduction; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">SSS Loan:</label>

														<input type="text" name="sssLoan" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->sssLoan; ?>" class="form-control custom-form-control"/> 

												</div>

												<div class="col-sm-2">
													<label class="control-label">Philhealth Contribution:</label>
														<input type="text" name="philhealthContribution" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->philhealthDeduction; ?>" class="form-control custom-form-control"/> 
												</div>

												<div class="col-sm-2">
													<label class="control-label">Pag-ibig Contribution:</label>
														<input type="text" name="pagibigContribution" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->pagibigDeduction; ?>" class="form-control custom-form-control"/> 
												</div>

												<div class="col-sm-2">
													<label class="control-label">Pag-ibig Loan:</label>        
														<input type="text" name="pagibigLoan" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->pagibigLoan; ?>" class="form-control custom-form-control"/> 
												</div>

												<div class="col-sm-2">
													<label class="control-label">Cashbond:</label>       
														<input type="text" name="cashbond" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->CashBond; ?>" class="form-control custom-form-control"/> 
												</div>


											</div>


											<div class="form-group" style="margin-top:-10px;">

												
												<div class="col-sm-2">
													<label class="control-label">Cash Advance:</label>
														<input type="text" name="cashAdvance" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->cashAdvance; ?>" class="form-control custom-form-control"/> 
												</div>

												<div class="col-sm-2">
													<label class="control-label">Total Deductions:</label>

													<input type="text" name="totalDeductions" id="input_payroll" value="<?php echo $totalDeductions; ?>" class="form-control custom-form-control"/> 
												</div>


												<div class="col-sm-2">
													<label class="control-label">Adjustment:</label>
			      
													<input type="text" name="adjustmentdeduction" title="deduction" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->adjustmentDeductions; ?>" class="form-control custom-form-control adjustment"/> 
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

													<input type="text" name="witholdingTax" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";} ?> value="<?php echo $row->Tax; ?>" class="form-control custom-form-control"/> 
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
													<input type="text" name="nontaxAllowance" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->NontaxAllowance; ?>" class="form-control custom-form-control"/> 
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

													<input type="text" name="adjustmentAfter" <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->adjustmentAfter ?>" class="form-control custom-form-control"/> 
												</div>
											</div>
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>



								<?php

									if ($row->datePayroll == "2020-01-15"){

								?>

									<div class="col-sm-3">
										<div class="panel panel-info">
										    <div class="panel-footer" style="border:1px solid #BDBDBD;">
										    	<div class="group-title"><b style="color: #2471a3 ">Additional</b></div>
									    			<div class="form-group" style="margin-top:-10px;">

													<div class="col-sm-12">
														<label class="control-label">Incentives:</label>
														
														<?php echo number_format($incentives,2); ?>	

														



													
														
														
														
													</div>
												</div>
								    		</div> <!-- end of panel-footer -->
										</div> <!-- end of panel - info -->
									</div>
								<?php
									}

								?>

								<?php

									if ($row->datePayroll >= "2020-01-30"){
								?>
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

															$cut_off_13_pay_basic = $row->ratePayPrd;		
															$cut_off_13_pay_allowance = $row->allowancePay;		


															/*if ($cut_off_class->checkIfHiredWithinCutOff($row_emp->DateHired) == 1){
																$daily_basic_13_month_pay = round($basicCutOffPay / $working_days_count,2);
																$dayily_allowance_13_month_pay = round($allowance / $working_days_count,2);

																

																$cut_off_13_pay_basic = round($daily_basic_13_month_pay * $present,2);	
																$cut_off_13_pay_allowance = round($dayily_allowance_13_month_pay * $present,2);

															}*/									
														?>

														<?php

															if ($row->CutOffPeriod == "January 11, 2020 - January 25, 2020"){

																// November 26, 2019 - December 10, 2019
																// December 11, 2019 - December 25, 2019
																// December 26, 2019 - January 10, 2020

																if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019") != 0){

																	$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019");

																	$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
																	$december_15_2019_13_pay_allowance = $row_13->allowancePay;
																}

																if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019") != 0){
																	$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019");

																	$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
																	$december_30_2019_13_pay_allowance = $row_13->allowancePay;
																}

																if ($payroll_info_class->getCountCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020") != 0){

																	$row_13 = $payroll_info_class->getCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020");

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
														<label class="control-label"><?php echo $row->CutOffPeriod; ?> (BASIC):</label>
														<?php echo number_format($cut_off_13_pay_basic,2); ?>
														<br/>

														<label class="control-label"><?php echo $row->CutOffPeriod; ?> (ALLOWANCE):</label>
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



													
														
														
														
													</div>
												</div>
								    		</div> <!-- end of panel-footer -->
										</div> <!-- end of panel - info -->
									</div>
								<?php
									}

								?>

								<div class="col-sm-3">
									<div class="panel panel-info">
									    <div class="panel-footer" style="border:1px solid #BDBDBD;">
									    	<div class="group-title"><b style="color: #196f3d ">Net Pay</b></div>
								    			<div class="form-group" style="margin-top:-10px;">

												<div class="col-sm-12">
													<label class="control-label">Net Pay:</label>
											

														<input type="text" name="netPay"  <?php if ($is_cut_off == 1) { echo "id='float_only'";} else { echo "id='input_payroll'";}?> value="<?php echo $row->netPay; ?>" class="form-control custom-form-control"/> 

												</div>

												
											</div>


											
							    		</div> <!-- end of panel-footer -->
									</div> <!-- end of panel - info -->
								</div>


								<div class="col-sm-12" style="margin-bottom:10px;">
									<label class="control-label">Remarks:</label>
									<textarea class="form-control" name="remarks" <?php if ($is_cut_off == 1) { echo "id=''";} else { echo "id='input_payroll'";}?>><?php echo $row->remarks ?></textarea>
								</div>




								<div class="form-group">
									<?php 
									//echo $is_cut_off;
									if ($is_cut_off == 1){
									?>
									<center>
										<button type="button" class="btn btn-success" id="update_payroll_info">Update Payroll Info</button>
									</center>
									<?php
										}

										else {
									?>
									<center>
										<button type="button" class="btn btn-success" id="print_empployee_payslip">Print Payslip</button>
									</center>
									<?php
										}
									?>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<span id="message"></span>
									</div>
								</div>

								


								<!--<div class="col-sm-12">		
					    			<div class="form-group">
					    				<center>
					    					<button type="button" id="adjustment_remarks" class="btn btn-primary btn-sm">Adjustment</button>
										</center>
									</div>							    	
								</div> -->
							</form>
																		
						</b>
					</div>
				</div>


				<script>
					$(document).ready(function(){

						$("button[id='print_empployee_payslip']").on("click", function () {
				          var datastring = "payroll_id="+"<?php echo $payroll_id; ?>";
				          $.ajax({
				              type: "POST",
				              url: "ajax/script_print_employee_payslip.php",
				              data: datastring,
				              cache: false,
				              success: function (data) {
				                // if has error 
				               if (data == "Error"){
				                  $("#update_errorModal").modal("show");
				                }
				                // if success
				                else {       
				                  //$("#modal_body_delete").html(data);
				                  //$("#delete_modal").modal("show");
				                  //$("#submit_div").html(data);
				                  //$("#print_payslip_form").submit();
				                  window.location = "my_payslip_reports.php";
				                }
				                
				              }
				           });
				       });

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


					     $("textarea[id='input_payroll']").keydown(function (e) {
					      //  return false;
					        if(e.keyCode != 116) {
					            return false;
					        }
					      });

					        // onpaste
					     $("textarea[id='input_payroll").on("paste", function(){
					          return false;
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


				     // float only
				    $("input[id='float_only']").on('input', function(){
				       if ($(this).attr("maxlength") != 10){
				            if ($(this).val().length > 10){
				                $(this).val($(this).val().slice(0,-1));
				            }
				           $(this).attr("maxlength","10");
				       }

				   });

			        // FOR DECIMAL POINT
				      $("input[id='float_only'").keydown(function (e) {


				      //	alert(e.keyCode);
				     	if ($(this).val() == 0 && e.keyCode == "9") {
				     		$(this).val("0");
			     	 	}

				      	//var new_value =0;
				      //	else if ($(this).val() == 0) {
				      	//	$(this).val($(this).val().slice(1,-1));
				      	//}


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



				      	//alert("HELLO WORLD!");

				      // errorMessage
					    $("div[id='errorMessage']").change("input[title='earnings']",function () {
					          		

					         //alert("wew");
					        if ($(this).val() == ""){
					          $(this).val(0);
					        }


					         
					         var emp_id = $("span[id='emp_id']").html();
					         var basicPay = $("span[id='basicPay']").html();
					         //var hasTax = $("span[id='taxableStatus']").html();
					         var hasTax = 1;
					         var emp_name = $("span[id='emp_name']").html();


					         var last_total_gross_income = <?php echo $last_total_gross_income; ?>;
					         var cutOff_day = "<?php echo date_format(date_create($row->datePayroll),'d'); ?>";

					         var regOt = $("input[name='regOT']").val();
					         var rdOT = $("input[name='rdOT']").val();
					         var regHolidayOT = $("input[name='regHolidayOT']").val();
					         var specialHolidayOT = $("input[name='specialHolidayOT']").val();
					         var rd_regHolidayOT = $("input[name='rd_regHolidayOT']").val();
					         var rd_specialHolidayOT = $("input[name='rd_specialHolidayOT']").val();
					         var tardiness = $("input[name='tardiness']").val();
					        // var absences = $("input[name='absences']").val();
					         var absences = 0;
					         var present = $("input[name='present']").val();
					         var adjustmentEarnings = $("input[name='adjustmentEarnings']").val();

					          var sssContribution = $("input[name='sssContribution']").val();
					         // var sssLoan = $("input[name='sssLoan']").val();
					          var philhealthContribution = $("input[name='philhealthContribution']").val();
					          var pagibigContribution = $("input[name='pagibigContribution']").val();

					          var incentives = <?php echo $incentives; ?>;
					          var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 				  var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;
					         // var pagibigLoan = $("input[name='pagibigLoan']").val();


					         var totalGrossIncome = parseFloat(convertToZero(present)) + parseFloat(convertToZero(regOt)) + parseFloat(convertToZero(rdOT)) + parseFloat(convertToZero(regHolidayOT)) + parseFloat(convertToZero(specialHolidayOT)) + parseFloat(convertToZero(rd_regHolidayOT)) + parseFloat(convertToZero(rd_specialHolidayOT)) - parseFloat(convertToZero(tardiness)) - parseFloat(convertToZero(absences)) + parseFloat(convertToZero(adjustmentEarnings));
					         

					         // for 2 decimal places
					          totalGrossIncome = totalGrossIncome.toString().split('e');
					          totalGrossIncome = Math.round(+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] + 2) : 2)));

					          totalGrossIncome = totalGrossIncome.toString().split('e');
					          totalGrossIncome =  (+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] - 2) : -2))).toFixed(2);


					         $("input[name='grossIncome']").val(totalGrossIncome);
					        // alert(totalGrossIncome);

					         if (hasTax == 1) {
					            var datastring = "taxable_income="+totalGrossIncome+"&emp_id="+emp_id+"&emp_name="+emp_name;
					            datastring += "&last_total_gross_income="+last_total_gross_income;
					            datastring += "&cutOff_day="+cutOff_day;
					            datastring += "&sss_contribution="+sssContribution;
					            datastring += "&pagibig_contribution="+pagibigContribution;
					            datastring += "&philhealth_contribution="+philhealthContribution;
					           // alert(datastring);
					            $.ajax({
					              type: "POST",
					              url: "ajax/append_tax_value.php",
					              data: datastring,
					              cache: false,
					              success: function (data) {
					                //alert(data);
					              // if has error 
					                  //alert(data);
					                  $("input[name='witholdingTax']").val(data);

					                  // for computing net pay
					                  var totalDeductions = $("input[name='totalDeductions']").val();
					                  var nontaxAllowance = $("input[name='nontaxAllowance']").val();
					                  var adjustmentAfter = $("input[name='adjustmentAfter']").val();

					                  var netPay = parseFloat(totalGrossIncome) - parseFloat(converToZero(totalDeductions)) -  parseFloat(data) + parseFloat(converToZero(nontaxAllowance)) + parseFloat(converToZero(adjustmentAfter));


					                  netPay += parseFloat(convertToZero(incentives));
	             					  netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

					                  // for 2 decimal places
					                  netPay = netPay.toString().split('e');
					                  netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

					                  netPay = netPay.toString().split('e');
					                  final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

					                  $("input[name='netPay']").val(final_netPay);
					                  //alert(final_netPay);

					              }
					            });
					         }

					         // if has no tax
					         else {

					               // alert("wew");
					               // for computing net pay
					                var totalDeductions = $("input[name='totalDeductions']").val();
					                var nontaxAllowance = $("input[name='nontaxAllowance']").val();
					                var adjustmentAfter = $("input[name='adjustmentAfter']").val();

					                var netPay = parseFloat(totalGrossIncome) - parseFloat(converToZero(totalDeductions)) + parseFloat(converToZero(nontaxAllowance)) + parseFloat(converToZero(adjustmentAfter));


					                netPay += parseFloat(convertToZero(incentives));
             					    netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

					                // for 2 decimal places
					                netPay = netPay.toString().split('e');
					                netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

					                netPay = netPay.toString().split('e');
					                final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

					                $("input[name='netPay']").val(final_netPay);
					         }



					    });



						



					     $("div[id='errorMessage']").change("input[title='deduction']",function () {

					          if ($(this).val() == ""){
					            $(this).val(0);
					          }

					          var totalGrossIncome = $("input[name='grossIncome']").val();

					          var sssContribution = $("input[name='sssContribution']").val();
					          var sssLoan = $("input[name='sssLoan']").val();
					          var philhealthContribution = $("input[name='philhealthContribution']").val();
					          var pagibigContribution = $("input[name='pagibigContribution']").val();
					          var pagibigLoan = $("input[name='pagibigLoan']").val();
					          var cashbond = $("input[name='cashbond']").val();
					          var cashAdvance = $("input[name='cashAdvance']").val();
					          var adjustmentEarnings = $("input[name='adjustmentdeduction']").val();

					          var incentives = <?php echo $incentives; ?>;
					          var total_13_basic_pay = <?php echo $total_13_basic_pay; ?>;
  			 				  var total_13_allowance_pay = <?php echo $total_13_allowance_pay; ?>;


					          var totalDeduction = parseFloat(convertToZero(sssContribution)) + parseFloat(convertToZero(sssLoan)) + parseFloat(convertToZero(philhealthContribution)) + parseFloat(convertToZero(pagibigContribution)) + parseFloat(convertToZero(pagibigLoan)) + parseFloat(convertToZero(cashbond)) + parseFloat(convertToZero(cashAdvance)) + parseFloat(convertToZero(adjustmentEarnings));

					          
					          // for 2 decimal places
					          totalDeduction = totalDeduction.toString().split('e');
					          totalDeduction = Math.round(+(totalDeduction[0] + 'e' + (totalDeduction[1] ? (+totalDeduction[1] + 2) : 2)));

					          totalDeduction = totalDeduction.toString().split('e');
					          totalDeduction =  (+(totalDeduction[0] + 'e' + (totalDeduction[1] ? (+totalDeduction[1] - 2) : -2))).toFixed(2);

					          $("input[name='totalDeductions']").val(totalDeduction);

					          var tax = $("input[name='witholdingTax']").val();
					          var nontaxAllowance = $("input[name='nontaxAllowance']").val();
					          var adjustmentAfter = $("input[name='adjustmentAfter']").val();

					          var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(totalDeduction) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));

					          netPay += parseFloat(convertToZero(incentives));
     					      netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

					          netPay = netPay.toString().split('e');
					          netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

					          netPay = netPay.toString().split('e');
					          netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

					          $("input[name='netPay']").val(netPay);
					    });


					    $("div[id='errorMessage']").change("input[name='adjustmentAfter']",function () {

					          if ($(this).val() == ""){
					            $(this).val(0);
					          }

					          var totalGrossIncome = $("input[name='grossIncome']").val();
					          var totalDeduction = $("input[name='totalDeductions']").val();
					          var tax = $("input[name='witholdingTax']").val();
					          var nontaxAllowance = $("input[name='nontaxAllowance']").val();
					          var adjustmentAfter = $("input[name='adjustmentAfter']").val();

					          var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeduction)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));


					          netPay += parseFloat(convertToZero(incentives));
     					      netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

					           netPay = netPay.toString().split('e');
					           netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

					           netPay = netPay.toString().split('e');
					           netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

					           $("input[name='netPay']").val(netPay);

					    });

					    // nontaxAllowance
					    $("div[id='errorMessage']").change("input[name='nontaxAllowance']",function () {

					          if ($(this).val() == ""){
					            $(this).val(0);
					          }

					          var totalGrossIncome = $("input[name='grossIncome']").val();
					          var totalDeduction = $("input[name='totalDeductions']").val();
					          var tax = $("input[name='witholdingTax']").val();
					          var nontaxAllowance = $("input[name='nontaxAllowance']").val();
					          var adjustmentAfter = $("input[name='adjustmentAfter']").val();

					          var netPay = parseFloat(convertToZero(totalGrossIncome)) - parseFloat(convertToZero(totalDeduction)) - parseFloat(convertToZero(tax)) + parseFloat(convertToZero(nontaxAllowance)) + parseFloat(convertToZero(adjustmentAfter));


					           netPay += parseFloat(convertToZero(incentives));
         					   netPay += (parseFloat(convertToZero(total_13_basic_pay)) + parseFloat(convertToZero(total_13_allowance_pay)));

					           netPay = netPay.toString().split('e');
					           netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

					           netPay = netPay.toString().split('e');
					           netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

					           $("input[name='netPay']").val(netPay);

					    });


					    // for update  payroll info button
				    $("button[id='update_payroll_info']").on("click" ,function () {

				          // for earnings
				          var regularOT = $("input[name='regOT']").val();
				          var rdOt = $("input[name='rdOT']").val();
				          var reg_holidayOt = $("input[name='regHolidayOT']").val();
				          var special_holidayOT = $("input[name='specialHolidayOT']").val();
				          var rd_regHolidayOT = $("input[name='rd_regHolidayOT']").val();
				          var rd_specialHolidayOT = $("input[name='rd_specialHolidayOT']").val();
				          var tardiness = $("input[name='tardiness']").val();
				          var absences = 0;
				          var present = $("input[name='present']").val();
				          var grossIncome = $("input[name='grossIncome']").val();
				          var adjustmentEarnings = $("input[name='adjustmentEarnings']").val();

				          // for deduction
				          var sssDeduction = $("input[name='sssContribution']").val();
				          var sssLoan = $("input[name='sssLoan']").val();
				          var philhealthContrib = $("input[name='philhealthContribution']").val();
				          var pagibigContrib = $("input[name='pagibigContribution']").val();
				          var pagibigLoan = $("input[name='pagibigLoan']").val();
				          var cashBond = $("input[name='cashbond']").val();
				          var cashAdvance = $("input[name='cashAdvance']").val();
				          var totalDeduction = $("input[name='totalDeductions']").val();
				          var adjustmentDeduction = $("input[name='adjustmentdeduction']").val();

				          var tax = $("input[name='witholdingTax']").val();
				          var allowance = $("input[name='nontaxAllowance']").val();
				          var adjustmentAfter = $("input[name='adjustmentAfter']").val();
				          var netPay = $("input[name='netPay']").val();
				          var remarks = $("textarea[name='remarks']").val();

				          /*alert(regularOT);
				          alert(rdOt);
				          alert(reg_holidayOt);
				          alert(special_holidayOT);
				          alert(rd_regHolidayOT);
				          alert(rd_specialHolidayOT);
				          alert(tardiness);
				          alert(absences);
				          alert(grossIncome);
				          alert(adjustmentEarnings);

				          alert(sssDeduction);
				          alert(sssLoan);
				          alert(philhealthContrib);
				          alert(pagibigContrib);
				          alert(pagibigLoan);
				          alert(cashBond);
				          alert(cashAdvance);
				          alert(totalDeduction);
				          alert(adjustment);

				          alert(tax);
				          alert(allowance);
				          alert(adjustmentAfter);
				          alert(netPay);
				          */

				          //if (regularOT == "" || rdOt == "" || reg_holidayOt == "" || special_holidayOT == "" || rd_regHolidayOT == "" || rd_specialHolidayOT == "" || tardiness == "" || absences == "" || grossIncome =="" || adjustmentEarnings == "" || sssDeduction == "" || sssLoan == "" || philhealthContrib == "" || pagibigContrib == "" || pagibigLoan == "" || cashBond == "" || cashAdvance == "" || totalDeduction == "" ||adjustment == ""||tax == "" ||allowance == "" ||adjustmentAfter == "" || netPay == ""){
				          //    $("span[id='message']").html("<span class='glyphicon glyphicon-remove'></span>");
				          //}
				         // alert("HELLO WOLD!");

				           $("span[id='message']").html("<div class='loader' style='float:left;'></div> Updating Information, Please wait ...");

				            var emp_id = $("span[id='emp_id']").html();
				            //var cutOffPeriod = $("span[id='cut_off_period']").html();
				            var datastring = "regOT="+regularOT + "&rdOT=" +rdOt + "&reg_holidayOT="+reg_holidayOt + "&special_holidayOT="+special_holidayOT + "&rd_regularHolidayOT="+rd_regHolidayOT+"&rd_specialHolidayOT=" +rd_specialHolidayOT + "&tardiness="+tardiness + "&absences="+absences + "&totalGrossIncome="+grossIncome +"&earningsAdjustment="+adjustmentEarnings +"&sssDeduction="+sssDeduction+"&sssLoan="+sssLoan +"&philhealthDeduction="+philhealthContrib+"&pagibigContribution="+pagibigContrib+"&pagibigLoan="+pagibigLoan+"&cashbond="+cashBond+"&cashAdvance="+cashAdvance+"&totalDeductions="+totalDeduction+"&deductionAdjustment="+adjustmentDeduction +"&tax="+tax + "&nontaxAllowance="+allowance +"&afterAdjustment="+adjustmentAfter + "&netPay="+netPay + "&update_payrollEmpId="+emp_id + "&remarks="+remarks+"&present="+present;

				            

				            $.ajax({
				              type: "POST",
				              url: "php script/updatePayrollInfo.php",
				              data: datastring,
				              cache: false,
				              success: function (data) {
				                  $("span[id='message']").html(data);

				              }
				            });


				    });




			   		 });


					function convertToZero(value){

					    	// //alert("Wew");
					      if (value == ""){
					        value = 0;
					     	 }

					      return value;
				    	}

				</script>
<?php
		}
		//echo $dateFrom . "<br/>";
		//echo $dateTo;

	}



}

else {
	header("Location:../MainForm.php");
}
	

?>