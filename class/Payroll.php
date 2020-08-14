<?php
class Payroll extends Connect_db{


	// for getting the last id in database
	public function payrollLastTotalGrossIncome($emp_id){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' ORDER BY payroll_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$totalGrossIncome = $row->totalGrossIncome - ($row->sssDeduction + $row->philhealthDeduction + $row->pagibigDeduction);
		return $totalGrossIncome;
	}


	public function checkExistPayrollLastTotalGrossIncome($emp_id){
		$connect = $this->connect();
		//$select_last_id_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' ORDER BY payroll_id DESC LIMIT 1";
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' ORDER BY payroll_id DESC LIMIT 1"));
        return $num_rows;
	}

	public function insertPayroll($emp_id,$dept_id,$company_id,$cutoffPeriod,$salary,$taxCode,$regOtHour,$regOtRate,$regularOt,
								  $rdOtHour,$rdOtRate,$restdayOT,$regHolidayOtHour,$regHolidayOtRate,$regHolidayOt,
								  $specialHolidayOtHour,$specialHolidayOtRate,$specialHolidayOt,
								  $rd_regHolidayOtHour,$rd_regHolidayOtRate,$rd_regHolidayOt,
								  $rd_specialHolidayOtHour,$rd_specialHolidayOtRate,$rd_specialHolidayOt,
								  $tardinessHour,$tardinessRate,$tardiness,
								  $absencesHour,$absencesRate,$absences,$present,$present_amount,$adjustmentEarnings,$adjustmentDeductions,$adjustmentBefore,$adjustmentAfter,
								  $adjustment,$totalGrossIncome,$nonTaxAllowance,$totalEarnings,$tax,
								  $sssDeduction,$philhealthDeduction,$pagibigDeduction,$sssLoan,
								  $pagibigLoan,$cashAdvance,$cashBond,$totalDeductions,$netPay,$basicRate,
								  $allowance,$dailyRate,$dailyAllowance,$ratePayPeriod,$allowancePay,$cut_off_13_pay_basic,$cut_off_13_pay_allowance,
								  $ytdGross,$ytdAllowance,$ytdTax,$cashAdvanceBal,$datePayroll,$remarks,$payrollStatus,$dateCreated){

	$connect = $this->connect();


	$emp_id = mysqli_real_escape_string($connect,$emp_id);
	$dept_id = mysqli_real_escape_string($connect,$dept_id);
	$company_id = mysqli_real_escape_string($connect,$company_id);
	$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);
	$salary = mysqli_real_escape_string($connect,$salary);
	$taxCode = mysqli_real_escape_string($connect,$taxCode);

	// for reg OT
	$regOtHour = mysqli_real_escape_string($connect,$regOtHour);
	$regOtRate = mysqli_real_escape_string($connect,$regOtRate);
	$regularOt = mysqli_real_escape_string($connect,$regularOt);
	
	// for RD OT
	$rdOtHour = mysqli_real_escape_string($connect,$rdOtHour);
	$rdOtRate = mysqli_real_escape_string($connect,$rdOtRate);
	$restdayOT = mysqli_real_escape_string($connect,$restdayOT);

	// for reg Holiday OT
	$regHolidayOtHour = mysqli_real_escape_string($connect,$regHolidayOtHour);
	$regHolidayOtRate = mysqli_real_escape_string($connect,$regHolidayOtRate);
	$regHolidayOt = mysqli_real_escape_string($connect,$regHolidayOt);

	// for Special Holiday OT
	$specialHolidayOtHour = mysqli_real_escape_string($connect,$specialHolidayOtHour);
	$specialHolidayOtRate = mysqli_real_escape_string($connect,$specialHolidayOtRate);
	$specialHolidayOt = mysqli_real_escape_string($connect,$specialHolidayOt);

	// for RD reg holiday OT
	$rd_regHolidayOtHour = mysqli_real_escape_string($connect,$rd_regHolidayOtHour);
	$rd_regHolidayOtRate = mysqli_real_escape_string($connect,$rd_regHolidayOtRate);
	$rd_regHolidayOt = mysqli_real_escape_string($connect,$rd_regHolidayOt);

	// for RD special holiday OT
	$rd_specialHolidayOtHour = mysqli_real_escape_string($connect,$rd_regHolidayOtHour);
	$rd_specialHolidayOtRate = mysqli_real_escape_string($connect,$rd_specialHolidayOtRate);
	$rd_specialHolidayOt = mysqli_real_escape_string($connect,$rd_specialHolidayOt);

	// for tardiness
	$tardinessHour = mysqli_real_escape_string($connect,$tardinessHour);
	$tardinessRate = mysqli_real_escape_string($connect,$tardinessRate);
	$tardiness = mysqli_real_escape_string($connect,$tardiness);

	// for absences
	$absencesHour = mysqli_real_escape_string($connect,$absencesHour);
	$absencesRate = mysqli_real_escape_string($connect,$absencesRate);
	$absences = mysqli_real_escape_string($connect,$absences);

	$present = mysqli_real_escape_string($connect,$present);
	$present_amount = mysqli_real_escape_string($connect,$present_amount);



	$adjustmentEarnings = mysqli_real_escape_string($connect,$adjustmentEarnings);
	$adjustmentDeductions = mysqli_real_escape_string($connect,$adjustmentDeductions);
	$adjustmentBefore = mysqli_real_escape_string($connect,$adjustmentBefore);
	$adjustmentAfter = mysqli_real_escape_string($connect,$adjustmentAfter);
	$adjustment = mysqli_real_escape_string($connect,$adjustment);
	$totalGrossIncome = mysqli_real_escape_string($connect,$totalGrossIncome);
	$nonTaxAllowance = mysqli_real_escape_string($connect,$nonTaxAllowance);
	$totalEarnings = mysqli_real_escape_string($connect,$totalEarnings);
	$tax = mysqli_real_escape_string($connect,$tax);

	$sssDeduction = mysqli_real_escape_string($connect,$sssDeduction);
	$philhealthDeduction = mysqli_real_escape_string($connect,$philhealthDeduction);
	$pagibigDeduction = mysqli_real_escape_string($connect,$pagibigDeduction);
	$sssLoan = mysqli_real_escape_string($connect,$sssLoan);
	$pagibigLoan = mysqli_real_escape_string($connect,$pagibigLoan);

	$cashAdvance = mysqli_real_escape_string($connect,$cashAdvance);
	$cashBond = mysqli_real_escape_string($connect,$cashBond);
	$totalDeductions = mysqli_real_escape_string($connect,$totalDeductions);
	$netPay = mysqli_real_escape_string($connect,$netPay);

	$basicRate = mysqli_real_escape_string($connect,$basicRate);
	$allowance = mysqli_real_escape_string($connect,$allowance);
	$dailyRate = mysqli_real_escape_string($connect,$dailyRate);
	$dailyAllowance = mysqli_real_escape_string($connect,$dailyAllowance); 
	$ratePayPeriod = mysqli_real_escape_string($connect,$ratePayPeriod); 
	$allowancePay = mysqli_real_escape_string($connect,$allowancePay); 
	$cut_off_13_pay_basic = mysqli_real_escape_string($connect,$cut_off_13_pay_basic); 
	$cut_off_13_pay_allowance = mysqli_real_escape_string($connect,$cut_off_13_pay_allowance); 

	$ytdGross = mysqli_real_escape_string($connect,$ytdGross);
	$ytdAllowance = mysqli_real_escape_string($connect,$ytdAllowance);
	$ytdTax = mysqli_real_escape_string($connect,$ytdTax);
	$cashAdvanceBal = mysqli_real_escape_string($connect,$cashAdvanceBal);

	$datePayroll = mysqli_real_escape_string($connect,$datePayroll);
	$remarks = mysqli_real_escape_string($connect,$remarks);
	$payrollStatus = mysqli_real_escape_string($connect,$payrollStatus); 
	$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

 	/*$insert_qry = "INSERT INTO tb_payroll_info (payroll_id,emp_id,dept_id,CutOffPeriod,salary,taxCode,regularOT,holidayOT,
												restdayOT,holidayRestdayOT,Tardiness,Absences,Adjustment,totalGrossIncome,Allowance,
												sssDeduction,philhealthDeduction,pagibigDeduction,sssLoan,pagibigLoan,cashAdvance,
												CashBond,totalDeductions,taxableIncome,Tax,netPay,datePayroll,DateCreated) 

										VALUES ('','$emp_id','$dept_id','$cutoffPeriod','$salary','$regularOt','$holidayOT',
												'$restdayOT','$holidayRestdayOT','$tardiness','$absences','$adjustment','$totalGrossIncome','$allowance',
												'$sssDeduction','$philhealthDeduction','$pagibigDeduction','$sssLoan','$pagibigLoan','$cashAdvance',
												'$cashBond','$totalDeductions','$taxableIncome','$tax','$netPay','$datePayroll','$dateCreated')";*/

	$insert_qry = "INSERT INTO tb_payroll_info (payroll_id,emp_id,dept_id,company_id,CutOffPeriod,salary,taxCode,
												reg_OThour,reg_OTrate,regularOT,rd_OThour,rd_OTrate,restdayOT,
												reg_holiday_OThour,reg_holiday_OTrate,reg_holidayOT,special_holiday_OThour,
												special_holiday_OTrate,special_holidayOT,rd_reg_holiday_OThour,rd_reg_holiday_OTrate,
												rd_reg_holidayOT,rd_special_holiday_OThour,rd_special_holiday_OTrate,rd_special_holidayOT,
												tardinessHour,tardinessRate,Tardiness,absencesHour,absencesRate,Absences,present,present_amount,adjustmentEarnings,adjustmentDeductions,adjustmentBefore,adjustmentAfter,Adjustment,
												totalGrossIncome,NontaxAllowance,totalEarnings,Tax,sssDeduction,philhealthDeduction,pagibigDeduction,
												sssLoan,pagibigLoan,cashAdvance,CashBond,totalDeductions,netPay,basicRate,Allowance,dailyRate,dailyAllowance,
												ratePayPrd,allowancePay,cut_off_13_pay_basic,cut_off_13_pay_allowance,ytdGross,ytdAllowance,ytdWithTax,cashAdvBal,datePayroll,remarks,payrollStatus,DateCreated)

											VALUES ('','$emp_id','$dept_id','$company_id','$cutoffPeriod','$salary','$taxCode',
													'$regOtHour','$regOtRate','$regularOt','$rdOtHour','$rdOtRate','$restdayOT',
													'$regHolidayOtHour','$regHolidayOtRate','$regHolidayOt','$specialHolidayOtHour','$specialHolidayOtRate',
													'$specialHolidayOt','$rd_regHolidayOtHour','$rd_regHolidayOtRate','$rd_regHolidayOt','$rd_specialHolidayOtHour',
													'$rd_specialHolidayOtRate','$rd_specialHolidayOt','$tardinessHour','$tardinessRate','$tardiness',
													'$absencesHour','$absencesRate','$absences','$present','$present_amount','$adjustmentEarnings','$adjustmentDeductions','$adjustmentBefore','$adjustmentAfter','$adjustment','$totalGrossIncome','$nonTaxAllowance',
													'$totalEarnings','$tax','$sssDeduction','$philhealthDeduction','$pagibigDeduction','$sssLoan','$pagibigLoan',
													'$cashAdvance','$cashBond','$totalDeductions','$netPay','$basicRate','$allowance','$dailyRate','$dailyAllowance',
													'$ratePayPeriod','$allowancePay','$cut_off_13_pay_basic','$cut_off_13_pay_allowance','$ytdGross','$ytdAllowance','$ytdTax','$cashAdvanceBal',
													'$datePayroll','$remarks','$payrollStatus','$dateCreated')";

	/*$insert_qry = "INSERT INTO tb_payroll_info (payroll_id,emp_id,dept_id,CutOffPeriod,salary,taxCode,reg_OThour,reg_OTrate,regularOT,
												rd_OThour,rd_OTrate,restdayOT,reg_holiday_OThour,reg_holiday_OTrate,reg_holidayOT,special_holiday_OThour,
												special_holiday_OTrate,special_holidayOT,rd_reg_holiday_OThour,rd_reg_holiday_OTrate,rd_reg_holidayOT,rd_special_holiday_OThour,
												rd_special_holiday_OTrate,rd_special_holidayOT,tardinessHour,tardinessRate,Tardiness,absencesHour,absencesRate,Absences,
												Adjustment,totalGrossIncome,NontaxAllowance,totalEarnings,Tax,sssDeduction,philhealthDeduction,pagibigDeduction,sssLoan,
												pagibigLoan,cashAdvance,CashBond,totalDeductions,netPay,basicRate,Allowance,dailyRate,dailyAllowance,ratePayPrd,)


										VALUES('','$emp_id','$dept_id','$cutoffPeriod','$salary','$taxCode','$regOtHour','$regOtRate','$regularOt',
												'$rdOtHour','$rdOtRate','$restdayOT','$regHolidayOtHour','$regHolidayOtRate','$regHolidayOt','$specialHolidayOtHour',
												'$specialHolidayOtRate','$specialHolidayOt','$rd_regHolidayOtHour','$rd_regHolidayOtRate','$rd_regHolidayOt',
												'$rd_specialHolidayOtHour','$rd_specialHolidayOtRate','$rd_specialHolidayOt','$tardinessHour','$tardinessRate','$tardiness',
												'$absencesHour','$absencesRate','$absences','$adjustment','$totalGrossIncome','$nonTaxAllowance','$totalEarnings','$tax','$sssDeduction',
												'$philhealthDeduction','$pagibigDeduction','$sssLoan','$pagibigLoan','$cashAdvance','$cashBond','$totalDeductions','$netPay','$basicRate',
												'$allowance','$dailyRate','$dailyAllowance')"; */

	$sql = mysqli_query($connect,$insert_qry);



	} // end of insert function


	// for existpayroll for update lang
	public function existPayroll($emp_id,$datePayroll){
        $connect = $this->connect();

        $emp_id = mysqli_real_escape_string($connect,$emp_id);
        $datePayroll = mysqli_real_escape_string($connect,$datePayroll);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND datePayroll='$datePayroll'"));
        return $num_rows;
	}

	// for update payroll
	public function updatePayroll($emp_id,$datePayroll,$salary,$regularOt,$holidayOT,
								  $restdayOT,$holidayRestdayOT,$tardiness,$absences,$adjustment,$totalGrossIncome,
								  $allowance,$sssDeduction,$philhealthDeduction,$pagibigDeduction,
								  $sssLoan,$pagibigLoan,$cashAdvance,$cashBond,$totalDeductions,$taxableIncome,
								  $tax,$netPay,$dateCreated){

	$connect = $this->connect();

	$emp_id = mysqli_real_escape_string($connect,$emp_id);
	$datePayroll = mysqli_real_escape_string($connect,$datePayroll);
	//$dept_id = mysqli_real_escape_string($connect,$dept_id);
	//$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);
	$salary = mysqli_real_escape_string($connect,$salary);
	$regularOt = mysqli_real_escape_string($connect,$regularOt);
	$holidayOT = mysqli_real_escape_string($connect,$holidayOT);
	$restdayOT = mysqli_real_escape_string($connect,$restdayOT);
	$holidayRestdayOT = mysqli_real_escape_string($connect,$holidayRestdayOT);
	$tardiness = mysqli_real_escape_string($connect,$tardiness);
	$absences = mysqli_real_escape_string($connect,$absences);
	$adjustment = mysqli_real_escape_string($connect,$adjustment);
	$totalGrossIncome = mysqli_real_escape_string($connect,$totalGrossIncome);
	$allowance = mysqli_real_escape_string($connect,$allowance);
	$sssDeduction = mysqli_real_escape_string($connect,$sssDeduction);
	$philhealthDeduction = mysqli_real_escape_string($connect,$philhealthDeduction);
	$pagibigDeduction = mysqli_real_escape_string($connect,$pagibigDeduction);
	$sssLoan = mysqli_real_escape_string($connect,$sssLoan);
	$pagibigLoan = mysqli_real_escape_string($connect,$pagibigLoan);
	$cashAdvance = mysqli_real_escape_string($connect,$cashAdvance);
	$cashBond = mysqli_real_escape_string($connect,$cashBond);
	$totalDeductions = mysqli_real_escape_string($connect,$totalDeductions);
	$taxableIncome = mysqli_real_escape_string($connect,$taxableIncome);
	$tax = mysqli_real_escape_string($connect,$tax);
	$netPay = mysqli_real_escape_string($connect,$netPay);
	//$datePayroll = mysqli_real_escape_string($connect,$datePayroll);
	$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

	$insert_qry = "UPDATE tb_payroll_info SET salary = '$salary',regularOT = '$regularOt',holidayOT='$holidayOT',
												restdayOT = '$restdayOT',holidayRestdayOT='$holidayRestdayOT',Tardiness = '$tardiness',
												Absences = '$absences' ,Adjustment = '$adjustment',totalGrossIncome = '$totalGrossIncome',Allowance = '$allowance',
												sssDeduction='$sssDeduction',philhealthDeduction ='$philhealthDeduction',pagibigDeduction = '$pagibigDeduction',
												sssLoan='$sssLoan',pagibigLoan = '$pagibigLoan',cashAdvance = '$cashAdvance',
												CashBond = '$cashBond',totalDeductions = '$totalDeductions',taxableIncome = '$taxableIncome',Tax = '$tax',
												netPay='$netPay',DateCreated = '$dateCreated' WHERE emp_id='$emp_id' AND datePayroll ='$datePayroll'";

										
	$sql = mysqli_query($connect,$insert_qry);



	} // end of insert function



	public function payrollInfoToTable($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND payrollStatus = '1' ORDER BY DateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$year = date_format(date_create($row->datePayroll),"Y");

				$payroll_date_payroll = date_format(date_create($row->datePayroll),"m-d");

				//echo $payroll_date_payroll . "<br/>";

				//echo $row->datePayroll . "<br/>";
				$select_qry_cutoff = "SELECT * FROM tb_cut_off";
				if ($result_cutoff = mysqli_query($connect,$select_qry_cutoff)){
					while($row_cutoff = mysqli_fetch_object($result_cutoff)){

						//$year = date("Y");

						$date_create = date_create($row_cutoff->datePayroll . ", " . $year);
						$date_format = date_format($date_create, 'm-d');

						//echo $row->datePayroll . "<br/>";
						if ($payroll_date_payroll == $date_format){



							if ($payroll_date_payroll == "01-15"){
								$cut_off_period = $row_cutoff->dateFrom . ", " . ($year - 1) . " - " . $row_cutoff->dateTo . ", " . $year;
							}
							else {
								$cut_off_period = $row_cutoff->dateFrom . ", " . $year . " - " . $row_cutoff->dateTo . ", " . $year;
							}
						}
					}
				}


				echo "<tr id='".$row->payroll_id."'>";
					echo "<td>".$cut_off_period."</td>";
					echo "<td>";
						echo "<span class='glyphicon glyphicon-print' style='color: #283747 '></span> <a href='#' id='print_myPayslip' class='action-a'>Print Payslip</a>";
					echo "</td>";
				echo "</tr>";
			}
		}

	} // end of function



	// check if exist payroll id to the current employee
	public function existPayrollId($emp_id,$payroll_id){
		$connect = $this->connect();

        $emp_id = mysqli_real_escape_string($connect,$emp_id);
        $payroll_id = mysqli_real_escape_string($connect,$payroll_id);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND payroll_id='$payroll_id'"));
        return $num_rows;

	}


	// get infor by payroll id
	public function getInfoByPayrollId($payroll_id){
		$connect = $this->connect();
		$payroll_id = mysqli_real_escape_string($connect,$payroll_id);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE payroll_id = '$payroll_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for finding the cut off period
	public function payrollPeriod($datePayroll){
		$connect = $this->connect();

		$datePayroll = mysqli_real_escape_string($connect,$datePayroll);

		$select_qry_cutoff = "SELECT * FROM tb_cut_off";
		if ($result_cutoff = mysqli_query($connect,$select_qry_cutoff)){
			while($row_cutoff = mysqli_fetch_object($result_cutoff)){

				$year = date("Y");

				$date_create = date_create($row_cutoff->datePayroll . ", " . $year);
				$date_format = date_format($date_create, 'Y-m-d');

				if ($datePayroll == $date_format){
					$cut_off_period = $row_cutoff->dateFrom . ", " . $year . " - " . $row_cutoff->dateTo . ", " . $year;
				}
			}
		}

		return $cut_off_period;
	}




	// checking if the current payroll is already exist in the database
	public function alreadyGeneratePayroll($datePayroll){
		$connect = $this->connect();

		$datePayroll = mysqli_real_escape_string($connect,$datePayroll);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE datePayroll = '$datePayroll'"));
        return $num_rows;
		
	}




	// for apporval list to table
	public function approvalListToTable(){
		$connect = $this->connect();

		$selecy_qry = "SELECT * FROM tb_payroll_approval";
		if ($result = mysqli_query($connect,$selecy_qry)){
			while($row = mysqli_fetch_object($result)){

				if ($row->approveStat == 0) {
					echo "<tr id='".$row->approve_payroll_id."'>";
						echo "<td>" . $row->CutOffPeriod . "</td>";
						echo "<td>Pending</td>";
						echo "<td>";
							echo "<a href='#' id='approve_payroll' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
							//echo "<span> | </span>";
							//echo "<a href='#' id='approve_payroll' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
						echo "</td>";
					echo "</tr>";
				} // end of if

				// for additional purposes para d makita ni ma'am jhoanna
				else if ($row->approveStat == 3){

				}

				else {
					$status = "Approved";
					if ($row->approveStat == 2){
						$status = "Disapproved";
					}

					echo "<tr id='".$row->approve_payroll_id."'>";
						echo "<td>" . $row->CutOffPeriod . "</td>";
						echo "<td>".$status."</td>";
						echo "<td>No Action</td>";
					echo "</tr>";

				} // end of else


			}
		} 
	}


	//  for saving to approval table
	public function insertPayrollApproval($cutoffPeriod,$approveStat,$dateCreated){
		$connect = $this->connect();

		//$payroll_id = mysqli_real_escape_string($connect,$payroll_id);
		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_payroll_approval (approve_payroll_id,CutOffPeriod,approveStat,DateCreated) VALUES ('','$cutoffPeriod','$approveStat','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);



		//return $sql;
	}


	// for checking exist approval_id
	public function existApprovePayrollId($approve_payroll_id){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE approve_payroll_id = '$approve_payroll_id'"));
        return $num_rows;
		

	}

	// for checking if the approve payroll id is already approved
	public function checkAlreadyApprove($approve_payroll_id){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE approve_payroll_id = '$approve_payroll_id' AND approveStat = '1'"));
        return $num_rows;
	}


	// for updating payroll approve, making it pending by sending the payroll reports
	public function sendPayrollReports($approve_payroll_id){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);

		$update_qry = "UPDATE tb_payroll_approval SET approveStat = '0' WHERE approve_payroll_id = '$approve_payroll_id'";
		$sql = mysqli_query($connect,$update_qry);

	}




	// for approving the payroll
	public function appovePayroll($approve_payroll_id,$current_date){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);
		$current_date = mysqli_real_escape_string($connect,$current_date);

		// for approving tb_payroll_approval
		$update_qry = "UPDATE tb_payroll_approval SET approveStat = '1', dateApprove = '$current_date' WHERE approve_payroll_id = '$approve_payroll_id'";
		$sql = mysqli_query($connect,$update_qry);


	}


	// for approving the payroll
	public function preAppovePayroll($approve_payroll_id,$current_date){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);
		$current_date = mysqli_real_escape_string($connect,$current_date);

		// for approving tb_payroll_approval
		$update_qry = "UPDATE tb_payroll_approval SET approveStat = '4', preApproveDate = '$current_date' WHERE approve_payroll_id = '$approve_payroll_id'";
		$sql = mysqli_query($connect,$update_qry);


	}


	// for approving the payroll
	public function disappovePayroll($approve_payroll_id,$current_date){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);
		$current_date = mysqli_real_escape_string($connect,$current_date);

		// for approving tb_payroll_approval
		$update_qry = "UPDATE tb_payroll_approval SET approveStat = '2', dateApprove= '$current_date'  WHERE approve_payroll_id = '$approve_payroll_id'";
		$sql = mysqli_query($connect,$update_qry);


	}




	// for getting information of tb_payroll_aproval
	public function getInfoPayrollAppoval($approve_payroll_id){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);

		$select_qry = "SELECT * FROM tb_payroll_approval WHERE approve_payroll_id='$approve_payroll_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for apprving tb_payroll_info
	public function approveInfoPayroll($cutoffPeriod){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		// for approving tb_payroll_approval
		$update_qry = "UPDATE tb_payroll_info SET payrollStatus = '1' WHERE CutOffPeriod = '$cutoffPeriod'";
		$sql = mysqli_query($connect,$update_qry);
	}

	// for apprving tb_payroll_info
	public function disapproveInfoPayroll($cutoffPeriod){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		// for approving tb_payroll_approval
		$update_qry = "UPDATE tb_payroll_info SET payrollStatus = '2' WHERE CutOffPeriod = '$cutoffPeriod'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for payroll report list
	public function payrollReportToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_payroll_approval ORDER BY DateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($_SESSION["role"] == 3) {
					echo "<tr id='".$row->approve_payroll_id."'>";

						if ($row->approveStat == 0){
							$status = "Already Sent";
						}

						else if ($row->approveStat == 1){
							$status = "Approved";
						}


						else if ($row->approveStat == 3){
							$status = "On Proccess";
						}

						else if ($row->approveStat == 4){
							$status = "Pre Approved";
						}

						//if ()


						echo "<td>" .$row->CutOffPeriod. "</td>";
						echo "<td id='status".$row->approve_payroll_id."'>" .$status. "</td>";
						echo "<td id='action".$row->approve_payroll_id."'>";
							echo "<a href='#' id='print_payroll_reports' class='action-a'><span class='glyphicon glyphicon-print' style='color: #2c3e50'></span> Print</a>";
							if ($row->approveStat == 3){
								echo " | ";
								echo "<a href='#' id='send_payroll_reports' class='action-a'><span class='glyphicon glyphicon-send' style='color: #2c3e50'></span> Send</a>";
							}
							if ($row->approveStat == 0 && $_SESSION["role"] == 1){
								echo " | ";
								echo "<a href='#' id='approve_payroll' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
							}

							if ($row->approveStat == 1 && $_SESSION["role"] == 3){

								echo " | ";
								echo "<a href='#' id='print_salary_info' class='action-a' title='Approve'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span> Salary Info</a>";
							}

						echo "</td>";
					echo "</tr>";
				}

				if (($_SESSION["role"] == 1 || $_SESSION["id"] == 47 || $_SESSION["id"] == 44) && $row->approveStat != 3 && $row->approveStat != 0) {

					

					echo "<tr id='".$row->approve_payroll_id."'>";

						/*if ($row->approveStat == 0){
							$status = "Already Sent";
						}*/

						if ($row->approveStat == 1){
							$status = "Approved";
						}


						else if ($row->approveStat == 3){
							$status = "On Proccess";
						}


						else if ($row->approveStat == 4){
							$status = "Pre Approved";
						}

						//if ()


						echo "<td>" .$row->CutOffPeriod. "</td>";
						echo "<td id='status".$row->approve_payroll_id."'>" .$status. "</td>";
						echo "<td id='action".$row->approve_payroll_id."'>";
							echo "<a href='#' id='print_payroll_reports' class='action-a'><span class='glyphicon glyphicon-print' style='color: #2c3e50'></span> Print</a>";
							//if ($row->approveStat == 3){
							//	echo " | ";
							//	echo "<a href='#' id='send_payroll_reports' class='action-a'><span class='glyphicon glyphicon-send' style='color: #2c3e50'></span> Send</a>";
							//}
							if ($row->approveStat == 4){
								echo " | ";
								echo "<a href='#' id='approve_payroll' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
							}

							if ($row->approveStat == 1 && $_SESSION["role"] == 3){

								echo " | ";
								echo "<a href='#' id='print_salary_info' class='action-a' title='Approve'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span> Salary Info</a>";
							}

						echo "</td>";
					echo "</tr>";
				}


				if ($_SESSION["role"] == 2 && $row->approveStat != 3) {

					

					echo "<tr id='".$row->approve_payroll_id."'>";

						if ($row->approveStat == 0){
							$status = "Already Sent";
						}

						else if ($row->approveStat == 1){
							$status = "Approved";
						}


						else if ($row->approveStat == 3){
							$status = "On Proccess";
						}

						else if ($row->approveStat == 4){
							$status = "Pre Approved";
						}

						//if ()


						echo "<td>" .$row->CutOffPeriod. "</td>";
						echo "<td id='status".$row->approve_payroll_id."'>" .$status. "</td>";
						echo "<td id='action".$row->approve_payroll_id."'>";
							echo "<a href='#' id='print_payroll_reports' class='action-a'><span class='glyphicon glyphicon-print' style='color: #2c3e50'></span> Print</a>";
							//if ($row->approveStat == 3){
							//	echo " | ";
							//	echo "<a href='#' id='send_payroll_reports' class='action-a'><span class='glyphicon glyphicon-send' style='color: #2c3e50'></span> Send</a>";
							//}
							if ($row->approveStat == 0 && $_SESSION["role"] == 2){
								echo " | ";
								echo "<a href='#' id='pre_approve_payroll' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
							}

							if ($row->approveStat == 1 && $_SESSION["role"] == 3){

								echo " | ";
								echo "<a href='#' id='print_salary_info' class='action-a' title='Approve'><span class='glyphicon glyphicon-print' style='color:#2c3e50'></span> Salary Info</a>";
							}

						echo "</td>";
					echo "</tr>";
				}








			}
		}
	}



	public function adjustmentReportToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_payroll_approval";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE Adjustment != '0'")); 
				if ($num_rows != 0) {
					echo "<tr id='".$row->CutOffPeriod."'>";
						echo "<td>" .$row->CutOffPeriod. "</td>";
						echo "<td>";
							echo "<a href='#' id='print_adjustment_reports' class='action-a'><span class='glyphicon glyphicon-print' style='color: #2c3e50'></span> Print</a>";
						echo "</td>";
					echo "</tr>";
				}
			}
		}
	}


	// for checking exist cut off period
	public function checkExistCutOffPeriod($cutoffPeriod){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutoffPeriod'"));
        return $num_rows;
	}



	// for checking the total number of exist in the current cut off period
	public function countAdjustmentCutOff($cutoffPeriod){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutoffPeriod' AND Adjustment != '0'"));
        return $num_rows;
	}

	// for adjustment reports
	/*public function getAllEmpWithAdjustment($CutOffPeriod){
		$connect = $this->connect();

		$CutOffPeriod = mysqli_real_escape_string($connect,$CutOffPeriod);

		$value = array();
		//$counter = 0;
		$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$CutOffPeriod' AND Adjustment != '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				return array($row->emp_id,"CIVIL STATUS",$row->salary,"ALLOWANCE","BEFORE","AFTER","SSS LOAN","CASH ADVANCE","CASH BOND","REMARKS");
				//$counter++;
			}
		}

		//return $value[1];
	

	}*/

	public function adjustmentPayrollReports($cutOffPeriod){
		$connect = $this->connect();

	//	echo $cutOffPeriod;
		require ("reports/fpdf.php");

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$splitCutOff = explode("-",$cutOffPeriod);

		$pdf = new PDF_MC_Table("l");
		$pdf->SetMargins("20","10"); // left top

		$pdf->AddPage();

		$pdf->SetFont("Arial","B","9");
		$pdf->Cell(65,5,"LLOYDS FINANCING CORPORATION",0,1,"C");

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,"LIST OF ACTIVE EMPLOYEES",0,1,"C");

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,$splitCutOff[0],0,1,"C"); // from

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,$splitCutOff[1],0,1,"C"); // to


		$date_create = date_create($splitCutOff[1]);
		$day = date_format($date_create, 'd');

		if ($day == "10") {
			$dayImgPayroll = "img/payroll images/15.png";
		}

		if ($day == "25") {
			$dayImgPayroll = "img/payroll images/30.png";
		}

		$pdf->Image($dayImgPayroll,85,10,15,20);// margin-left,margin-top,width,height


		$pdf->Cell(65,5,"",0,1,"C"); // for margin


		if ($day == "10") {
			
			$pdf->SetFont("Arial","B","7");
			// for headers of adjustment
			$pdf->Cell(110,5,"",0,0,"C");
			$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
			// for header
			$pdf->SetWidths(array(50,20,20,20,20,20,20,30,20,50));
			$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C"));
			$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","ALLOWANCE","BEFORE","AFTER","HDMF LOAN","CASH ADVANCE","CASH BOND","REMARKS"));
		}

		if ($day == "25") {
			$pdf->SetFont("Arial","B","7");
			// for headers of adjustment
			$pdf->Cell(90,5,"",0,0,"C");
			$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
			// for header
			$pdf->SetWidths(array(50,20,20,20,20,20,20,30,20,50));
			$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C"));
			$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","ALLOWANCE","BEFORE","AFTER","SSS LOAN","CASH ADVANCE","CASH BOND","REMARKS")); 
		}


		//echo $cutOffPeriod;
		$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND Adjustment != '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$pdf->SetFont("Arial","","7");

				//echo "wew";
				
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				//$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
				$date_create = date_create($row_emp->DateHired);
				$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";


				// check kong taxable ba siya
				$salary = $row_emp->Salary;

				$select_min_wage_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
				$result_min_wage = mysqli_query($connect,$select_min_wage_qry);
				$row_min_wage = mysqli_fetch_object($result_min_wage);

				$minimumWage = ($row_min_wage->basicWage + $row_min_wage->COLA) * 26;
				$civilStatus = "S";

				if ($row_emp->CivilStatus == "Married") {
					$civilStatus = "ME";
				}

				// if salary is greater than minimum wage ibig sabihin may tax siya
				if ($salary > $minimumWage) {
					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

					if ($num_rows == 0){
						$num_rows = "";
					}

					$civilStatus = $civilStatus . $num_rows;
				}
				

			
				if ($row_emp->DateHired == "0000-00-00"){
					$dateHired = "";
				}


				// for getting allowance
				$allowance = 0;

				$select_allowance_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'";
				if ($result_allowance = mysqli_query($connect,$select_allowance_qry)){
						while ($row_allowance = mysqli_fetch_object($result_allowance)){	
									
							if ($allowance == ""){
								$allowance = $row_allowance->AllowanceValue;		
							}
							else {
								$allowance = $allowance + $row_allowance->AllowanceValue;
							}
						}
				}


				// pag-ibig loan
				if ($day == "10") {
					
					$loan = $row->pagibigLoan;
					

				}

				// sss loan
				if ($day == "25") {
					
					$loan = $row->sssLoan;
					

				}
				// for cash advance 



				$pdf->Row(array($fullName . " ".$dateHired,$civilStatus,$this->getMoney($salary),$this->getMoney($allowance),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->adjustmentAfter),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),htmlspecialchars($row->remarks))); 
				//$counter++;
			}
		}


		
		$pdf->output();
	}


	// for payroll reports
	public function payrollReportsPDF($cutOffPeriod){
		$connect = $this->connect();

		require ("reports/fpdf.php");

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$splitCutOff = explode("-",$cutOffPeriod);

		$pdf = new PDF_MC_Table("l","mm","LEGAL");
		$pdf->SetMargins("8","10"); // left top

		$pdf->AddPage();

		$pdf->SetFont("Arial","B","9");
		$pdf->Cell(65,5,"LLOYDS FINANCING CORPORATION",0,1,"C");

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,"LIST OF ACTIVE EMPLOYEES",0,1,"C");

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,$splitCutOff[0],0,1,"C"); // from

		$pdf->SetFont("Arial","","9");
		$pdf->Cell(65,5,$splitCutOff[1],0,1,"C"); // to


		$date_create = date_create($splitCutOff[1]);
		$day = date_format($date_create, 'd');

		if ($day == "10") {
			$dayImgPayroll = "img/payroll images/15.png";
		}

		if ($day == "25") {
			$dayImgPayroll = "img/payroll images/30.png";
		}

		$pdf->Image($dayImgPayroll,92,10,15,20);// margin-left,margin-top,width,height



		$grand_total_pay = 0;



		$select_qry_pi = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod'";
		$result_pi = mysqli_query($connect,$select_qry_pi);
		$row_pi = mysqli_fetch_object($result_pi);

		$datePayroll = $row_pi->datePayroll;



		$absent_present ="ABSENT";
		if ($datePayroll >= "2020-02-15"){
			$absent_present = "PRESENT";
		}
		


		$select_company_qry = "SELECT * FROM tb_company ORDER BY company_id ASC";
		if ($result_company = mysqli_query($connect,$select_company_qry)){
			while ($row_company = mysqli_fetch_object($result_company)){


				//if ($row_company->company_id == 1) {

					//$num_rows_with_atm = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE WithAtm = '1' AND company_id = '$row_company->company_id'"));

					//if ($num_rows_with_atm != 0){
						$pdf->Cell(65,5,"",0,1,"C"); // for margin
						$pdf->SetFont("Arial","","9");
						//$pdf->Cell(65,5,"WITH ATM - ". $row_company->company ,0,1,"L"); // for margin
						$pdf->Cell(65,5,$row_company->company ,0,1,"L"); // for margin

						if ($day == "10") {
							
							$pdf->SetFont("Arial","B","6");
							// for headers of adjustment
							//$pdf->Cell(110,5,"",0,0,"C");
							//$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
							// for header
							//$pdf->SetFillColor(0,0,0);
					

							$month_pay_13 = "CUT OFF 13TH MONTH PAY";
							if ($cutOffPeriod == "January 11, 2020 - January 25, 2020"){
								$month_pay_13 = "12/15/19 - 01/30/20 13TH MONTH PAY";
							}

							$pdf->Cell(292,5,"",0,0,"C");
							$pdf->Cell(25,5,$month_pay_13,1,1,"C");
							
							// 349

							$pdf->SetWidths(array(28,12,13,13,13,15,14,13,13,18,13,13,16,13,13,13,13,15,18,13,13,12,12,13));
							$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
							$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","DAILY RATE",$absent_present,"TARDY/ UNDERTIME","OVERTIME","REG HOLIDAY","SPECIAL HOLIDAY","ADJUSTMENT BEFORE TAX","GROSS INCOME","HDMF","PHILHEALTH","TAXABLE INCOME","W/ TAX","HDMF LOAN","CASH ADVANCE","CASHBOND","ADJUSTMENT AFTER TAX","NET SALARIES","ALLOWANCE","BASIC","ALLOWANCE","TOTAL"));
						}

						if ($day == "25") {
							$pdf->SetFont("Arial","B","6");
							// for headers of adjustment
							//$pdf->Cell(90,5,"",0,0,"C");
							//$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
							// for header

							$month_pay_13 = "CUT OFF 13TH MONTH PAY";
							if ($cutOffPeriod == "January 11, 2020 - January 25, 2020"){
								$month_pay_13 = "12/15/19 - 01/30/20 13TH MONTH PAY";
							}

							$pdf->Cell(296,5,"",0,0,"C");
							$pdf->Cell(24,5,$month_pay_13,1,1,"C");

							$pdf->SetWidths(array(35,12,13,13,13,15,14,13,13,18,13,13,13,13,13,13,15,18,13,13,12,12,13));
							$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
							$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","DAILY RATE",$absent_present,"TARDY/ UNDERTIME","OVERTIME","REG HOLIDAY","SPECIAL HOLIDAY","ADJUSTMENT BEFORE TAX","GROSS INCOME","SSS","TAXABLE INCOME","W/ TAX","SSS LOAN","CASH ADVANCE","CASHBOND","ADJUSTMENT AFTER TAX","NET SALARIES","ALLOWANCE","BASIC","ALLOWANCE","TOTAL")); 
						}



						$grandTotal_1 = 0;
						$totalGrossIncome_1 = 0;
						$totalPagibigContrib_1 = 0;
						$totalPhilhealthContrib_1 = 0;
						$totalTaxableIncome_1 = 0;
						$totalTax_1 = 0;
						$totalPagibigLoan_1 = 0;
						$totalCashAdvance_1 = 0;
						$totalCashBond_1 = 0;
						$totalAdjustmentAfterTax_1 = 0;
						$totalNetSalaries_1 = 0;
						$totalAllowance_1 = 0;
						$total_absent_present_1 = 0;
						$total_tardiness_1 = 0;
						$total_overtime_1 = 0;
						$total_reg_holiday_1 = 0;
						$total_special_holiday_1 = 0;
						$total_adjustment_before_tax_1 = 0;

						$total_13_pay_basic_1 = 0;
						$total_13_pay_allowance_1 = 0;


						$totalSSS_1 = 0;
						$totalSSSLoan_1 = 0;


					

						// select ung may mga may ATM
						$select_with_atm = "SELECT * FROM tb_employee_info WHERE company_id = '$row_company->company_id' ORDER BY Lastname ASC"; //WithAtm = '1' AND
						if ($result_with_atm = mysqli_query($connect,$select_with_atm)){
							while ($row_with_atm = mysqli_fetch_object($result_with_atm)){

								$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id = '$row_with_atm->emp_id'";
								if ($result = mysqli_query($connect,$select_qry)){
									while ($row = mysqli_fetch_object($result)){
										$pdf->SetFont("Arial","","6");


										// for grand total
										if ($grandTotal_1 == 0){
											$grandTotal_1 = $row->netPay;
										}

										else {
											$grandTotal_1 = $grandTotal_1 + $row->netPay;
										}



										// employee info query
										$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
										$result_emp = mysqli_query($connect,$select_emp_qry);
										$row_emp = mysqli_fetch_object($result_emp);

										$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
										$date_create = date_create($row_emp->DateHired);
										$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";

										if ($row_emp->DateHired == "0000-00-00"){
											$dateHired = "";
										}

										$salary = $row_emp->Salary;

										$select_min_wage_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
										$result_min_wage = mysqli_query($connect,$select_min_wage_qry);
										$row_min_wage = mysqli_fetch_object($result_min_wage);

										$minimumWage = ($row_min_wage->basicWage + $row_min_wage->COLA) * 26;
										$civilStatus = "S";

										if ($row_emp->CivilStatus == "Married") {
											$civilStatus = "ME";
										}

										// if salary is greater than minimum wage ibig sabihin may tax siya
										if ($salary > $minimumWage) {
											$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

											if ($num_rows == 0){
												$num_rows = "";
											}

											$civilStatus = $civilStatus . $num_rows;
										}
										// $pdf->Row(array($fullName . " ".$dateHired,$civilStatus,$this->getMoney($salary),$this->getMoney($allowance),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->adjustmentAfter),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),htmlspecialchars($row->remarks))); 

										$dailyRate = round($salary / 26,2);

										// pag-ibig loan
										if ($day == "10") {
											
											$loan = $row->pagibigLoan;
											

										}

										// sss loan
										if ($day == "25") {
											
											$loan = $row->sssLoan;
											

										}

										$taxableIncome = round($row->totalGrossIncome - $row->pagibigDeduction - $row->philhealthDeduction - $row->sssDeduction,2);

										if ($totalTaxableIncome_1 == 0) {
											$totalTaxableIncome_1 = $taxableIncome;
										}

										else {
											$totalTaxableIncome_1  = $totalTaxableIncome_1 + $taxableIncome;
										}

										$cut_off_13_pay_basic = $row->cut_off_13_pay_basic;
										$cut_off_13_pay_allowance = $row->cut_off_13_pay_allowance;


										


										$december_15_2019_13_pay_basic = 0;
										$december_15_2019_13_pay_allowance = 0;

										$december_30_2019_13_pa_basic = 0;
										$december_30_2019_13_pay_allowance = 0;

										$january_15_2020_13_pay_basic = 0;
										$january_15_2020_13_pay_allowance = 0;

										if ($cutOffPeriod == "January 11, 2020 - January 25, 2020"){
											if ($this->getCountCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019") != 0){

												$row_13 = $this->getCutOff13MonthPayOld($row->emp_id,"November 26, 2019 - December 10, 2019");

												$december_15_2019_13_pay_basic = $row_13->ratePayPrd;
												$december_15_2019_13_pay_allowance = $row_13->allowancePay;
											}

											if ($this->getCountCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019") != 0){
												$row_13 = $this->getCutOff13MonthPayOld($row->emp_id,"December 11, 2019 - December 25, 2019");

												$december_30_2019_13_pa_basic = $row_13->ratePayPrd;
												$december_30_2019_13_pay_allowance = $row_13->allowancePay;
											}

											if ($this->getCountCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020") != 0){

												$row_13 = $this->getCutOff13MonthPayOld($row->emp_id,"December 26, 2019 - January 10, 2020");

												$january_15_2020_13_pay_basic = $row_13->ratePayPrd;
												$january_15_2020_13_pay_allowance = $row_13->allowancePay;
											}


											$cut_off_13_pay_basic += round($december_15_2019_13_pay_basic/12,2) + round($december_30_2019_13_pa_basic/12,2) + round($january_15_2020_13_pay_basic/12,2);


											$cut_off_13_pay_allowance += round($december_15_2019_13_pay_allowance /12 ,2) + round($december_30_2019_13_pay_allowance /12,2) + round($january_15_2020_13_pay_allowance/12,2);
										}	

										$total_13_pay_basic_1 += $cut_off_13_pay_basic;
										$total_13_pay_allowance_1 += $cut_off_13_pay_allowance;

										
										$netSalaries = round($row->netPay - ($cut_off_13_pay_basic + $cut_off_13_pay_allowance) - $row->NontaxAllowance,2);
										$halfMonth = round($salary /2);

										if ($totalGrossIncome_1 == 0){
											$totalGrossIncome_1 = $row->totalGrossIncome;
										}

										else {
											$totalGrossIncome_1 = $totalGrossIncome_1 + $row->totalGrossIncome;
										}


										if ($totalPagibigContrib_1 == 0){
											$totalPagibigContrib_1 = $row->pagibigDeduction;
										}

										else {
											$totalPagibigContrib_1 = $totalPagibigContrib_1 + $row->pagibigDeduction;
										}

										if ($totalPhilhealthContrib_1 == 0){
											$totalPhilhealthContrib_1 = $row->philhealthDeduction;
										}

										else {
											$totalPhilhealthContrib_1 = $totalPhilhealthContrib_1 + $row->philhealthDeduction;
										}


										if ($totalTax_1 == 0){
											$totalTax_1 = $row->Tax;
										}

										else {
											$totalTax_1 = $totalTax_1 + $row->Tax;
										}

										if ($totalPagibigLoan_1 == 0){
											$totalPagibigLoan_1 = $loan;
										}

										else {
											$totalPagibigLoan_1 = $totalPagibigLoan_1 + $loan;
										}

										if ($totalCashAdvance_1 == 0){
											$totalCashAdvance_1 = $row->cashAdvance;
										}

										else {
											$totalCashAdvance_1 = $totalCashAdvance_1 + $row->cashAdvance;
										}

										if ($totalCashBond_1 == 0){
											$totalCashBond_1 = $row->CashBond;
										}

										else {
											$totalCashBond_1 = $totalCashBond_1 + $row->CashBond;
										}

										if ($totalAdjustmentAfterTax_1 == 0){
											$totalAdjustmentAfterTax_1 = $row->adjustmentAfter;
										}

										else {
											$totalAdjustmentAfterTax_1 = $totalAdjustmentAfterTax_1 + $row->adjustmentAfter;
										}

										if ($totalNetSalaries_1 == 0){
											$totalNetSalaries_1 = $netSalaries;
										}

										else {
											$totalNetSalaries_1 = $totalNetSalaries_1 + $netSalaries;
										}

										if ($totalAllowance_1 == 0){
											$totalAllowance_1 = $row->NontaxAllowance;
										}

										else {
											$totalAllowance_1 = $totalAllowance_1 + $row->NontaxAllowance;
										}


										//$totalTardy = 0;
										
										if ($day == "25") {
											if ($totalSSS_1 == 0){
												$totalSSS_1 = $row->sssDeduction;
											}

											else {
												$totalSSS_1 = $row->sssDeduction + $totalSSS_1;
											}
										}


										if ($totalSSSLoan_1 == 0){
											$totalSSSLoan_1 = $loan;
										}

										else {
											$totalSSSLoan_1 = $totalSSSLoan_1 + $loan;
										}

										
										$total_tardiness_1 += $row->Tardiness;
										$total_reg_holiday_1 += $row->reg_holidayOT;
										$total_special_holiday_1 += $row->special_holidayOT;
										$total_adjustment_before_tax_1 += $row->adjustmentBefore;
										

										// for getting total ot
										$totalOt = round($row->regularOT + $row->restdayOT + $row->reg_holidayOT + $row->special_holidayOT + $row->rd_reg_holidayOT + $row->rd_special_holidayOT,2);
										$total_overtime_1 += $totalOt;

										$fullName = utf8_decode($fullName);

											

										$absent_present_amount = number_format($row->Absences,2);
										if ($row->datePayroll >= "2020-02-15"){
											$absent_present_amount = number_format($row->present_amount,2);
											$total_absent_present_1 += $row->present_amount; 
										}

										else {
											$total_absent_present_1 += $row->Absences; 
										}


										if ($day == "10") {

											

											$pdf->Row(array($fullName . " " .$dateHired ,$civilStatus,$this->getMoney($halfMonth),$this->getMoney($dailyRate),$absent_present_amount,$this->getMoney($row->Tardiness),$this->getMoney($totalOt),$this->getMoney($row->reg_holidayOT),$this->getMoney($row->special_holidayOT),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->totalGrossIncome),$this->getMoney($row->pagibigDeduction),$this->getMoney($row->philhealthDeduction),$this->getMoney($taxableIncome),$this->getMoney($row->Tax),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),$this->getMoney($row->adjustmentAfter),$this->getMoney($netSalaries),$this->getMoney($row->NontaxAllowance),number_format($cut_off_13_pay_basic,2),number_format($cut_off_13_pay_allowance,2),$this->getMoney($row->netPay)));
										}
										if ($day == "25") {
											$pdf->Row(array($fullName . " " .$dateHired ,$civilStatus,$this->getMoney($halfMonth),$this->getMoney($dailyRate),$absent_present_amount,$this->getMoney($row->Tardiness),$this->getMoney($totalOt),$this->getMoney($row->reg_holidayOT),$this->getMoney($row->special_holidayOT),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->totalGrossIncome),$this->getMoney($row->sssDeduction),$this->getMoney($taxableIncome),$this->getMoney($row->Tax),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),$this->getMoney($row->adjustmentAfter),$this->getMoney($netSalaries),$this->getMoney($row->NontaxAllowance),number_format($cut_off_13_pay_basic,2),number_format($cut_off_13_pay_allowance,2),$this->getMoney($row->netPay)));
										}
									}
								}
								
							}
						}

						if ($day == "10"){
							/*$objPHPExcel->setActiveSheetIndex(0) 
												->setCellValue('A'.++$count, "TOTAL")
												->setCellValue('B'.$count,"")
												->setCellValue('C'.$count,"")
												->setCellValue('D'.$count,"")
												->setCellValue('E'.$count,"")
												->setCellValue('F'.$count,"")
												->setCellValue('G'.$count,"")
												->setCellValue('H'.$count,"")
												->setCellValue('I'.$count,"")
												->setCellValue('J'.$count,"")
												->setCellValue('K'.$count,($totalGrossIncome_1))
												->setCellValue('L'.$count,($totalPagibigContrib_1))
												->setCellValue('M'.$count,($totalPhilhealthContrib_1))
												->setCellValue('N'.$count,($totalTaxableIncome_1))
												->setCellValue('O'.$count,($totalTax_1))
												->setCellValue('P'.$count,($totalSSSLoan_1))
												->setCellValue('Q'.$count,($totalCashAdvance_1))
												->setCellValue('R'.$count,($totalCashBond_1))
												->setCellValue('S'.$count,($totalAdjustmentAfterTax_1))
												->setCellValue('T'.$count,($totalNetSalaries_1))
												->setCellValue('U'.$count,($totalAllowance_1))
												->setCellValue('V'.$count,($grandTotal_1));*/




							$pdf->Row(array("TOTAL","","","",$this->getMoney($total_absent_present_1),$this->getMoney($total_tardiness_1),$this->getMoney($total_overtime_1),$this->getMoney($total_reg_holiday_1),$this->getMoney($total_special_holiday_1),$total_adjustment_before_tax_1,$this->getMoney($totalGrossIncome_1),$this->getMoney($totalPagibigContrib_1),$this->getMoney($totalPhilhealthContrib_1),$this->getMoney($totalTaxableIncome_1),$this->getMoney($totalTax_1),$this->getMoney($totalSSSLoan_1),$this->getMoney($totalCashAdvance_1),$this->getMoney($totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries_1),$this->getMoney($totalAllowance_1),number_format($total_13_pay_basic_1,2),number_format($total_13_pay_allowance_1,2),$this->getMoney($grandTotal_1)));
						
							$grand_total_pay += $grandTotal_1;
						}
						else {
							$pdf->Row(array("TOTAL","","","",$this->getMoney($total_absent_present_1),$this->getMoney($total_tardiness_1),$this->getMoney($total_overtime_1),$this->getMoney($total_reg_holiday_1),$this->getMoney($total_special_holiday_1),$total_adjustment_before_tax_1,$this->getMoney($totalGrossIncome_1),$this->getMoney($totalSSS_1),$this->getMoney($totalTaxableIncome_1),$this->getMoney($totalTax_1),$this->getMoney($totalSSSLoan_1),$this->getMoney($totalCashAdvance_1),$this->getMoney($totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries_1),$this->getMoney($totalAllowance_1),number_format($total_13_pay_basic_1,2),number_format($total_13_pay_allowance_1,2),$this->getMoney($grandTotal_1)));
							
							$grand_total_pay += $grandTotal_1;
						}

					//} // end of numb rows with atm


					/*
					$num_rows_without_atm = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE WithAtm = '0' AND company_id = '$row_company->company_id'"));

					if ($num_rows_without_atm != 0) {


						$pdf->Cell(65,5,"",0,1,"C"); // for margin
						$pdf->SetFont("Arial","","9");
						$pdf->Cell(65,5,"",0,1,"L"); // for margin
						$pdf->Cell(65,5,"WITHOUT ATM - ". $row_company->company,0,1,"L"); // for margin

						if ($day == "10") {
							
							$pdf->SetFont("Arial","B","6");
							// for headers of adjustment
							//$pdf->Cell(110,5,"",0,0,"C");
							//$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
							// for header
							//$pdf->SetFillColor(0,0,0);
							$pdf->SetWidths(array(35,12,13,13,13,15,14,13,13,18,13,13,16,13,13,13,13,15,18,13,13,13));
							$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
							$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","DAILY RATE","ABSENT","TARDY/ UNDERTIME","OVERTIME","REG HOLIDAY","SPECIAL HOLIDAY","ADJUSTMENT BEFORE TAX","GROSS INCOME","HDMF","PHILHEALTH","TAXABLE INCOME","W/ TAX","HDMF LOAN","CASH ADVANCE","CASHBOND","ADJUSTMENT AFTER TAX","NET SALARIES","ALLOWANCE","TOTAL"));
						}

						if ($day == "25") {
							$pdf->SetFont("Arial","B","6");
							// for headers of adjustment
							//$pdf->Cell(90,5,"",0,0,"C");
							//$pdf->Cell(40,5,"ADJUSTMENT",1,1,"C");
							// for header
							$pdf->SetWidths(array(35,12,13,13,13,15,14,13,13,18,13,13,13,13,13,13,15,18,13,13,13));
							$pdf->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
							$pdf->Row(array("EMPLOYEE NAME","CIVIL STATUS","SALARY","DAILY RATE","ABSENT","TARDY/ UNDERTIME","OVERTIME","REG HOLIDAY","SPECIAL HOLIDAY","ADJUSTMENT BEFORE TAX","GROSS INCOME","SSS","TAXABLE INCOME","W/ TAX","SSS LOAN","CASH ADVANCE","CASHBOND","ADJUSTMENT AFTER TAX","NET SALARIES","ALLOWANCE","TOTAL")); 
						}



						$grandTotal = 0;
						$totalGrossIncome = 0;
						$totalPagibigContrib = 0;
						$totalPhilhealthContrib = 0;
						$totalTaxableIncome = 0;
						$totalTax = 0;
						$totalPagibigLoan = 0;
						$totalCashAdvance = 0;
						$totalCashBond = 0;
						$totalAdjustmentAfterTax = 0;
						$totalNetSalaries = 0;
						$totalAllowance = 0;

						$totalSSS = 0;
						$totalSSSLoan = 0;

						// select ung mga wlang ATM
						$select_with_atm = "SELECT * FROM tb_employee_info WHERE WithAtm = '0' AND company_id = '$row_company->company_id' ORDER BY Lastname ASC";
						if ($result_with_atm = mysqli_query($connect,$select_with_atm)){
							while ($row_with_atm = mysqli_fetch_object($result_with_atm)){

								$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id = '$row_with_atm->emp_id'";
								if ($result = mysqli_query($connect,$select_qry)){
									while ($row = mysqli_fetch_object($result)){
										$pdf->SetFont("Arial","","6");


										// for grand total
										if ($grandTotal == 0){
											$grandTotal = $row->netPay;
										}

										else {
											$grandTotal = $grandTotal + $row->netPay;
										}



										// employee info query
										$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
										$result_emp = mysqli_query($connect,$select_emp_qry);
										$row_emp = mysqli_fetch_object($result_emp);

										$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
										$date_create = date_create($row_emp->DateHired);
										$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";

										if ($row_emp->DateHired == "0000-00-00"){
											$dateHired = "";
										}

										$salary = $row_emp->Salary;

										$select_min_wage_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
										$result_min_wage = mysqli_query($connect,$select_min_wage_qry);
										$row_min_wage = mysqli_fetch_object($result_min_wage);

										$minimumWage = ($row_min_wage->basicWage + $row_min_wage->COLA) * 26;
										$civilStatus = "S";

										if ($row_emp->CivilStatus == "Married") {
											$civilStatus = "ME";
										}

										// if salary is greater than minimum wage ibig sabihin may tax siya
										if ($salary > $minimumWage) {
											$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

											if ($num_rows == 0){
												$num_rows = "";
											}

											$civilStatus = $civilStatus . $num_rows;
										}
										// $pdf->Row(array($fullName . " ".$dateHired,$civilStatus,$this->getMoney($salary),$this->getMoney($allowance),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->adjustmentAfter),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),htmlspecialchars($row->remarks))); 

										$dailyRate = round($salary / 26,2);

										// pag-ibig loan
										if ($day == "10") {
											
											$loan = $row->pagibigLoan;
											

										}

										// sss loan
										if ($day == "25") {
											
											$loan = $row->sssLoan;
											

										}

										$taxableIncome = round($row->totalGrossIncome - $row->pagibigDeduction - $row->philhealthDeduction - $row->sssDeduction,2);

										if ($totalTaxableIncome == 0) {
											$totalTaxableIncome = $taxableIncome;
										}

										else {
											$totalTaxableIncome  = $totalTaxableIncome + $taxableIncome;
										}
										
										$netSalaries = round($row->netPay - $row->NontaxAllowance,2);
										$halfMonth = round($salary /2);

										if ($totalGrossIncome == 0){
											$totalGrossIncome = $row->totalGrossIncome;
										}

										else {
											$totalGrossIncome= $totalGrossIncome + $row->totalGrossIncome;
										}


										if ($totalPagibigContrib == 0){
											$totalPagibigContrib = $row->pagibigDeduction;
										}

										else {
											$totalPagibigContrib = $totalPagibigContrib + $row->pagibigDeduction;
										}

										if ($totalPhilhealthContrib == 0){
											$totalPhilhealthContrib = $row->philhealthDeduction;
										}

										else {
											$totalPhilhealthContrib = $totalPhilhealthContrib + $row->philhealthDeduction;
										}


										if ($totalTax == 0){
											$totalTax = $row->Tax;
										}

										else {
											$totalTax = $totalTax + $row->Tax;
										}

										if ($totalPagibigLoan == 0){
											$totalPagibigLoan = $loan;
										}

										else {
											$totalPagibigLoan = $totalPagibigLoan + $loan;
										}

										if ($totalCashAdvance == 0){
											$totalCashAdvance = $row->cashAdvance;
										}

										else {
											$totalCashAdvance = $totalCashAdvance + $row->cashAdvance;
										}

										if ($totalCashBond == 0){
											$totalCashBond = $row->CashBond;
										}

										else {
											$totalCashBond = $totalCashBond + $row->CashBond;
										}

										if ($totalAdjustmentAfterTax == 0){
											$totalAdjustmentAfterTax = $row->adjustmentAfter;
										}

										else {
											$totalAdjustmentAfterTax = $totalAdjustmentAfterTax + $row->adjustmentAfter;
										}

										if ($totalNetSalaries == 0){
											$totalNetSalaries = $netSalaries;
										}

										else {
											$totalNetSalaries = $totalNetSalaries + $netSalaries;
										}

										if ($totalAllowance == 0){
											$totalAllowance = $row->NontaxAllowance;
										}

										else {
											$totalAllowance = $totalAllowance + $row->NontaxAllowance;
										}


										//$totalTardy = 0;
										
										if ($day == "25") {
											if ($totalSSS == 0){
												$totalSSS = $row->sssDeduction;
											}

											else {
												$totalSSS = $row->sssDeduction + $totalSSS_1;
											}
										}

										
										if ($totalSSSLoan == 0){
											$totalSSSLoan = $loan;
										}

										else {
											$totalSSSLoan = $totalSSSLoan + $loan;
										}

										// for getting total ot
										$totalOt = round($row->regularOT + $row->restdayOT + $row->reg_holidayOT + $row->special_holidayOT + $row->rd_reg_holidayOT + $row->rd_special_holidayOT,2);

										$fullName = utf8_decode($fullName);
										if ($day == "10") {
											$pdf->Row(array($fullName . " " .$dateHired ,$civilStatus,$this->getMoney($halfMonth),$this->getMoney($dailyRate),$this->getMoney($row->Absences),$this->getMoney($row->Tardiness),$this->getMoney($totalOt),$this->getMoney($row->reg_holidayOT),$this->getMoney($row->special_holidayOT),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->totalGrossIncome),$this->getMoney($row->pagibigDeduction),$this->getMoney($row->philhealthDeduction),$this->getMoney($taxableIncome),$this->getMoney($row->Tax),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),$this->getMoney($row->adjustmentAfter),$this->getMoney($netSalaries),$this->getMoney($row->NontaxAllowance),$this->getMoney($row->netPay)));
										}
										if ($day == "25") {
											$pdf->Row(array($fullName . " " .$dateHired ,$civilStatus,$this->getMoney($halfMonth),$this->getMoney($dailyRate),$this->getMoney($row->Absences),$this->getMoney($row->Tardiness),$this->getMoney($totalOt),$this->getMoney($row->reg_holidayOT),$this->getMoney($row->special_holidayOT),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->totalGrossIncome),$this->getMoney($row->sssDeduction),$this->getMoney($taxableIncome),$this->getMoney($row->Tax),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),$this->getMoney($row->adjustmentAfter),$this->getMoney($netSalaries),$this->getMoney($row->NontaxAllowance),$this->getMoney($row->netPay)));
										}
									}
								}

								
							}
						}
				    	
						if ($day == "10"){

							$objPHPExcel->setActiveSheetIndex(0) 
												->setCellValue('A'.++$count, "TOTAL")
												->setCellValue('B'.$count,"")
												->setCellValue('C'.$count,"")
												->setCellValue('D'.$count,"")
												->setCellValue('E'.$count,"")
												->setCellValue('F'.$count,"")
												->setCellValue('G'.$count,"")
												->setCellValue('H'.$count,"")
												->setCellValue('I'.$count,"")
												->setCellValue('J'.$count,"")
												->setCellValue('K'.$count,($totalGrossIncome))
												->setCellValue('L'.$count,($totalPagibigContrib))
												->setCellValue('M'.$count,($totalPhilhealthContrib))
												->setCellValue('N'.$count,($totalTaxableIncome))
												->setCellValue('O'.$count,($totalTax))
												->setCellValue('P'.$count,($totalSSSLoan))
												->setCellValue('Q'.$count,($totalCashAdvance))
												->setCellValue('R'.$count,($totalCashBond))
												->setCellValue('S'.$count,($totalAdjustmentAfterTax))
												->setCellValue('T'.$count,($totalNetSalaries))
												->setCellValue('U'.$count,($totalAllowance))
												->setCellValue('V'.$count,($grandTotal));

							$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome),$this->getMoney($totalPagibigContrib),$this->getMoney($totalPhilhealthContrib),$this->getMoney($totalTaxableIncome),$this->getMoney($totalTax),$this->getMoney($totalSSSLoan),$this->getMoney($totalCashAdvance),$this->getMoney($totalCashBond),$this->getMoney($totalAdjustmentAfterTax),$this->getMoney($totalNetSalaries),$this->getMoney($totalAllowance),$this->getMoney($grandTotal)));
						
							$pdf->Row(array("","","","","","","","","","","","","","","","","","","","",""));

							$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome + $totalGrossIncome_1),$this->getMoney($totalPagibigContrib + $totalPagibigContrib_1),$this->getMoney($totalPhilhealthContrib + $totalPhilhealthContrib_1),$this->getMoney($totalTaxableIncome + $totalTaxableIncome_1),$this->getMoney($totalTax + $totalTax_1),$this->getMoney($totalSSSLoan + $totalSSSLoan_1),$this->getMoney($totalCashAdvance + $totalCashAdvance_1),$this->getMoney($totalCashBond + $totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax + $totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries + $totalNetSalaries_1),$this->getMoney($totalAllowance + $totalAllowance_1),$this->getMoney($grandTotal+$grandTotal_1)));
						}
						else {
							$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome),$this->getMoney($totalSSS),$this->getMoney($totalTaxableIncome),$this->getMoney($totalTax),$this->getMoney($totalSSSLoan),$this->getMoney($totalCashAdvance),$this->getMoney($totalCashBond),$this->getMoney($totalAdjustmentAfterTax),$this->getMoney($totalNetSalaries),$this->getMoney($totalAllowance),$this->getMoney($grandTotal)));


							$pdf->Row(array("","","","","","","","","","","","","","","","","","","","",""));

							$pdf->Row(array("GRAND TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome + $totalGrossIncome_1),$this->getMoney($totalSSS + $totalSSS_1),$this->getMoney($totalTaxableIncome + $totalTaxableIncome_1),$this->getMoney($totalTax + $totalTax_1),$this->getMoney($totalSSSLoan + $totalSSSLoan_1),$this->getMoney($totalCashAdvance + $totalCashAdvance_1),$this->getMoney($totalCashBond + $totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax + $totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries + $totalNetSalaries_1),$this->getMoney($totalAllowance + $totalAllowance_1),$this->getMoney($grandTotal + $grandTotal_1)));
						}
					} // end of num rows without at */
				//} // end of if only LFC

				// not LFC employee
				


			}
		}
		


		

		if ($day == "10"){
			/*$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,"")
								->setCellValue('F'.$count,"")
								->setCellValue('G'.$count,"")
								->setCellValue('H'.$count,"")
								->setCellValue('I'.$count,"")
								->setCellValue('J'.$count,"")
								->setCellValue('K'.$count,($totalGrossIncome_1))
								->setCellValue('L'.$count,($totalPagibigContrib_1))
								->setCellValue('M'.$count,($totalPhilhealthContrib_1))
								->setCellValue('N'.$count,($totalTaxableIncome_1))
								->setCellValue('O'.$count,($totalTax_1))
								->setCellValue('P'.$count,($totalSSSLoan_1))
								->setCellValue('Q'.$count,($totalCashAdvance_1))
								->setCellValue('R'.$count,($totalCashBond_1))
								->setCellValue('S'.$count,($totalAdjustmentAfterTax_1))
								->setCellValue('T'.$count,($totalNetSalaries_1))
								->setCellValue('U'.$count,($totalAllowance_1))
								->setCellValue('V'.$count,($grandTotal_1));*/
			
			$pdf->Cell(65,5,"",0,1,"C"); // for margin

			

			
			$pdf->Cell(273,5,"",0,0,"C"); // for margin
			$pdf->Cell(26,5,"GRAND TOTAL",0,0,"C"); // for margin
			$pdf->Cell(26,5,number_format($grand_total_pay,2),0,1,"C"); // for margin

			
		
		}
		else {

			$pdf->Cell(65,5,"",0,1,"C"); // for margin

			$pdf->Cell(257,5,"",0,0,"C"); // for margin
			$pdf->Cell(26,5,"GRAND TOTAL",0,0,"C"); // for margin
			$pdf->Cell(26,5,number_format($grand_total_pay,2),0,1,"C"); // for margin

			
		}

		



		





		//$pdf->SetFont("Arial","B","9");
		//$pdf->Cell(305,5,"GRAND TOTAL:",0,0,"R");

		/*$pdf->Cell(20,5,$this->getMoney($grandTotal),0,1,"C");*/
	    $pdf->output();

	}


	// for generate payroll ready, under process , or already generate
	public function insertGeneratePayroll($cutoffPeriod,$dateCreated){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_ready_generate_payroll (ready_generate_payroll_id,CutOffPeriod,DateCreated) VALUES ('','$cutoffPeriod','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// if exist cut off period wlang mangyayare
	public function existGeneratePayrollcutOff($cutoffPeriod){
		$connect = $this->connect();
		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_ready_generate_payroll WHERE CutOffPeriod = '$cutoffPeriod'"));
        return $num_rows;
	}


	// for update information for the current period if the input is not correct
	public function getAllEmployeeToUpdatePayrollInfo($cutoffPeriod){
		$connect = $this->connect();
		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutoffPeriod' AND payrollStatus = '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " .$row_emp->Firstname . " " .  $row_emp->Middlename;

				echo "<tr id='".$row->emp_id."'>";
					echo "<td><div style='cursor:pointer;text-align:center;color:#3498db;' id='update_payroll_info'>" . $fullName . "</div></td>";
				echo "</tr>";
			}
		}

	}



	// for checking if exist the id
	public function checkExistEmpIdPayrollInfo($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id'"));

		return $num_rows;

	}


	// for getting informations by emp id and cut off period
	public function getPayrollInfoByEmpIdCutOffPeriod($emp_id,$cutOffPeriod){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	} 


	// for checking if the no change was made
	public function checkExistPayrollInfoToUpdate($emp_id,$cutOffPeriod,$regOT,$rdOT,$reg_holidayOT,$special_holidayOT,$rd_regularHolidayOT,$rd_specialHolidayOT,$tardiness,$absences,$sssDeduction,$sssLoan,$philhealthDeduction,$pagibigContribution,$pagibigLoan,$cashbond,$cashAdvance,$totalGrossIncome,$earningsAdjustment,
									$totalDeductions,$deductionAdjustment,$tax,$nonTaxAllowance,$afterAdjustment,$netPay,$remarks,$present){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$regOT = mysqli_real_escape_string($connect,$regOT);
		$rdOT = mysqli_real_escape_string($connect,$rdOT);
		$reg_holidayOT = mysqli_real_escape_string($connect,$reg_holidayOT);
		$special_holidayOT = mysqli_real_escape_string($connect,$special_holidayOT);
		$rd_regularHolidayOT = mysqli_real_escape_string($connect,$rd_regularHolidayOT);
		$rd_specialHolidayOT = mysqli_real_escape_string($connect,$rd_specialHolidayOT);
		$tardiness = mysqli_real_escape_string($connect,$tardiness);
		$absences = mysqli_real_escape_string($connect,$absences);
		$sssDeduction = mysqli_real_escape_string($connect,$sssDeduction);
		$sssLoan = mysqli_real_escape_string($connect,$sssLoan);
		$philhealthDeduction = mysqli_real_escape_string($connect,$philhealthDeduction);
		$pagibigContribution = mysqli_real_escape_string($connect,$pagibigContribution);
		$pagibigLoan = mysqli_real_escape_string($connect,$pagibigLoan);
		$cashbond = mysqli_real_escape_string($connect,$cashbond);
		$cashAdvance = mysqli_real_escape_string($connect,$cashAdvance);




		$totalGrossIncome = mysqli_real_escape_string($connect,$totalGrossIncome);
		$earningsAdjustment = mysqli_real_escape_string($connect,$earningsAdjustment);
		$totalDeductions = mysqli_real_escape_string($connect,$totalDeductions);
		$deductionAdjustment = mysqli_real_escape_string($connect,$deductionAdjustment);
		$tax = mysqli_real_escape_string($connect,$tax);
		$nonTaxAllowance = mysqli_real_escape_string($connect,$nonTaxAllowance);
		$afterAdjustment = mysqli_real_escape_string($connect,$afterAdjustment);
		$netPay = mysqli_real_escape_string($connect,$netPay);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$present = mysqli_real_escape_string($connect,$present);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'
													AND totalGrossIncome = '$totalGrossIncome' AND adjustmentEarnings = '$earningsAdjustment' AND totalDeductions = '$totalDeductions'
													AND adjustmentDeductions = '$deductionAdjustment' AND Tax = '$tax' AND NontaxAllowance = '$nonTaxAllowance' AND adjustmentAfter = '$afterAdjustment'
													AND netPay = '$netPay' AND regularOT = '$regOT' AND restdayOT = '$rdOT' AND reg_holidayOT = '$reg_holidayOT'
													AND special_holidayOT = '$special_holidayOT' AND rd_reg_holidayOT = '$rd_regularHolidayOT' AND rd_special_holidayOT = '$rd_specialHolidayOT'
													AND Tardiness = '$tardiness' AND Absences = '$absences' AND sssDeduction = '$sssDeduction' AND sssLoan = '$sssLoan' AND philhealthDeduction = '$philhealthDeduction'
													AND pagibigDeduction = '$pagibigContribution' AND pagibigLoan = '$pagibigLoan' AND CashBond = '$cashbond' AND cashAdvance = '$cashAdvance' AND remarks = '$remarks' AND present_amount = '$present'"));

		return $num_rows;
	}


	// for updating payroll information
	public function updatePayrollInfo($emp_id,$cutOffPeriod,$regOT,$rdOT,$reg_holidayOT,$special_holidayOT,$rd_regularHolidayOT,$rd_specialHolidayOT,$tardiness,
									$absences,$sssDeduction,$sssLoan,$philhealthDeduction,$pagibigContribution,$pagibigLoan,$cashbond,$cashAdvance,$totalGrossIncome,$earningsAdjustment,
									$totalDeductions,$deductionAdjustment,$tax,$nonTaxAllowance,$adjustmentBefore,$afterAdjustment,$netPay,$remarks,$present){

		$connect = $this->connect();


		$regOT = mysqli_real_escape_string($connect,$regOT);
		$rdOT = mysqli_real_escape_string($connect,$rdOT);
		$reg_holidayOT = mysqli_real_escape_string($connect,$reg_holidayOT);
		$special_holidayOT = mysqli_real_escape_string($connect,$special_holidayOT);
		$rd_regularHolidayOT = mysqli_real_escape_string($connect,$rd_regularHolidayOT);
		$rd_specialHolidayOT = mysqli_real_escape_string($connect,$rd_specialHolidayOT);
		$tardiness = mysqli_real_escape_string($connect,$tardiness);
		$absences = mysqli_real_escape_string($connect,$absences);
		$sssDeduction = mysqli_real_escape_string($connect,$sssDeduction);
		$sssLoan = mysqli_real_escape_string($connect,$sssLoan);
		$philhealthDeduction = mysqli_real_escape_string($connect,$philhealthDeduction);
		$pagibigContribution = mysqli_real_escape_string($connect,$pagibigContribution);
		$pagibigLoan = mysqli_real_escape_string($connect,$pagibigLoan);
		$cashbond = mysqli_real_escape_string($connect,$cashbond);
		$cashAdvance = mysqli_real_escape_string($connect,$cashAdvance);

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);
		$totalGrossIncome = mysqli_real_escape_string($connect,$totalGrossIncome);
		$earningsAdjustment = mysqli_real_escape_string($connect,$earningsAdjustment);
		$totalDeductions = mysqli_real_escape_string($connect,$totalDeductions);
		$deductionAdjustment = mysqli_real_escape_string($connect,$deductionAdjustment);
		$tax = mysqli_real_escape_string($connect,$tax);
		$nonTaxAllowance = mysqli_real_escape_string($connect,$nonTaxAllowance);
		$adjustmentBefore = mysqli_real_escape_string($connect,$adjustmentBefore);
		$afterAdjustment = mysqli_real_escape_string($connect,$afterAdjustment);
		$netPay = mysqli_real_escape_string($connect,$netPay);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$present = mysqli_real_escape_string($connect,$present);


		$update_qry = "UPDATE tb_payroll_info SET regularOT = '$regOT',restdayOT = '$rdOT', reg_holidayOT = '$reg_holidayOT',special_holidayOT = '$special_holidayOT', 
													 rd_reg_holidayOT = '$rd_regularHolidayOT', rd_special_holidayOT = '$rd_specialHolidayOT', Tardiness = '$tardiness' , Absences = '$absences' , sssDeduction = '$sssDeduction',
													 sssLoan = '$sssLoan' , philhealthDeduction = '$philhealthDeduction', pagibigDeduction = '$pagibigContribution', pagibigLoan = '$pagibigLoan',
													 CashBond = '$cashbond' , cashAdvance = '$cashAdvance', totalGrossIncome = '$totalGrossIncome' , adjustmentEarnings = '$earningsAdjustment' , totalDeductions = '$totalDeductions',
													 adjustmentDeductions = '$deductionAdjustment' , Tax = '$tax' , NontaxAllowance = '$nonTaxAllowance' , adjustmentAfter = '$afterAdjustment',
													 netPay = '$netPay', adjustmentBefore = '$adjustmentBefore', remarks = '$remarks', present_amount = '$present' WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'";
		$sql = mysqli_query($connect,$update_qry);
												 


	}



	// check exist payroll information
	public function checkExistPayrollInformation($employeeName,$cutOffPeriod){
		$connect = $this->connect();

		$employeeName = mysqli_real_escape_string($connect,$employeeName);

		$emp_id = "";
		$select_qry = "SELECT * FROM tb_employee_info WHERE (role_id != '1' OR dept_id != '1')";// AND ActiveStatus = '1' 
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				if ($employeeName == $name){
					$emp_id = $row->emp_id;
				}	

			}
		}

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'"));

		return $num_rows;
	}


	// for getting current information from the filter fields
	public function getPayrollInfoByCutOffPeriodEmpName($employeeName,$cutOffPeriod){
		$connect = $this->connect();

		$employeeName = mysqli_real_escape_string($connect,$employeeName);

		$emp_id = "";
		$select_qry = "SELECT * FROM tb_employee_info WHERE (role_id != '1' OR dept_id != '1')"; // AND ActiveStatus = '1'
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				if ($employeeName == $name){
					$emp_id = $row->emp_id;
				}	

			}
		}

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}



	// for getting the information by cut off period
	public function getPayrollApprovalByCutOffPeriod($cutoffPeriod){
		$connect = $this->connect();

		$cutoffPeriod = mysqli_real_escape_string($connect,$cutoffPeriod);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE CutOffPeriod = '$cutoffPeriod' AND approveStat = '3'"));

		return $num_rows;

	}


	// for salary info payroll reports
	public function salaryInfoReports($cutOffPeriod){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "payroll_reports";
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A4', 'Cut Off Period')
					->setCellValue('A5', $cutOffPeriod);

		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A6', 'Employee Name')
					->setCellValue('B6', 'Salary')
					->setCellValue('C6', 'Allowance')
					->setCellValue('D6', 'Total');


		$counter = 7;
		$select_emp_qry = "SELECT * FROM tb_employee_info ORDER BY Lastname ASC";
		if ($result_emp = mysqli_query($connect,$select_emp_qry)){
			while ($row_emp = mysqli_fetch_object($result_emp)){

				$emp_id = $row_emp->emp_id;

				$employeeName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
				if ($row_emp->Middlename == ""){
					$employeeName = $row_emp->Lastname . ", " . $row_emp->Firstname;
				}

				$dateHired = $row_emp->DateHired;


				
				$select_payroll_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'";
				$result_payroll = mysqli_query($connect,$select_payroll_qry);
				$row_payroll = mysqli_fetch_object($result_payroll);

				if (isset($row_payroll->salary)){
					//$salary = $row_payroll;
					//$allowance = $row_payroll->Allowance;

					//$total = ;

					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$counter, $employeeName . " " . $dateHired)
					->setCellValue('B'.$counter, $this->getMoney($row_payroll->salary))
					->setCellValue('C'.$counter, $this->getMoney($row_payroll->Allowance))
					->setCellValue('D'.$counter, $this->getMoney($row_payroll->salary + $row_payroll->Allowance));

					$counter++;

				}
				/*
				$employeeName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
				if ($row_emp->Middlename == ""){
					$employeeName = $row_emp->Lastname . ", " . $row_emp->Firstname;
				}

				$dateHired = $row_emp->DateHired;

				$date_create = date_create($row_emp->DateHired);
				$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";

				if ($row_emp->DateHired == "0000-00-00"){
					$dateHired = "";
				}

				$salary = $row_payroll->salary;

				$allowance = $row_payroll->Allowance;

				$total =round($row_payroll->salary + $row_payroll->Allowance,2);

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$counter, $employeeName . " " . $dateHired)
					->setCellValue('B'.$counter, $this->getMoney($salary))
					->setCellValue('C'.$counter, $this->getMoney($allowance))
					->setCellValue('D'.$counter, $this->getMoney($total));


				$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':V'.$counter)->getFont()->setBold(true); //Make heading font bold
				$counter++;*/

				
				

			}
		}
					

		foreach(range('A','V') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true); //Make heading font bold
		$objPHPExcel->getActiveSheet()->getStyle('A6:D6')->getFont()->setBold(true); //Make heading font bold

		//do {
			
		//}
		/*********************Add color to heading START**********************/

		$objPHPExcel->getActiveSheet()
			->getStyle('A4')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('abb2b9');

		$objPHPExcel->getActiveSheet()
			->getStyle('A6:D6')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('abb2b9');

		/*	$objPHPExcel->getActiveSheet()
			->getStyle('A'.$without_atm_cell_count.':V'.$without_atm_cell_count)
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('abb2b9');*/


		$objPHPExcel->getActiveSheet()->setTitle('Salary_Info_Reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

	}



	// for printing payroll reports by pdf
	public function payrollReportsExcel($cutOffPeriod){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$splitCutOff = explode("-",$cutOffPeriod);

		$date_create = date_create($splitCutOff[1]);
		$day = date_format($date_create, 'd');

		$filename = "payroll_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A5', 'WITH ATM');

		if ($day == 10) {
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A6', 'Employee Name')
					->setCellValue('B6', 'Civil Status')
					->setCellValue('C6', 'Salary')
					->setCellValue('D6', 'Daily Rate')
					->setCellValue('E6', 'Absent')
					->setCellValue('F6', 'Tardy/ Undertime')
					->setCellValue('G6', 'Overtime')
					->setCellValue('H6', 'Reg Holiday')
					->setCellValue('I6', 'Special Holiday')
					->setCellValue('J6', 'Adjustment Before Tax')
					->setCellValue('K6', 'Gross income')
					->setCellValue('L6', 'HDMF')
					->setCellValue('M6', 'Philhealth')
					->setCellValue('N6', 'Taxable Income')
					->setCellValue('O6', 'W/ Tax')
					->setCellValue('P6', 'HDMF Loan')
					->setCellValue('Q6', 'Cash Advance')
					->setCellValue('R6', 'Cashbond')
					->setCellValue('S6', 'Adjustment After Tax')
					->setCellValue('T6', 'Net Salaries')
					->setCellValue('U6', 'Allowance')
					->setCellValue('V6', 'Total');
		}

		// kapag 25
		else {
			$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A6', 'Employee Name')
					->setCellValue('B6', 'Civil Status')
					->setCellValue('C6', 'Salary')
					->setCellValue('D6', 'Daily Rate')
					->setCellValue('E6', 'Absent')
					->setCellValue('F6', 'Tardy/ Undertime')
					->setCellValue('G6', 'Overtime')
					->setCellValue('H6', 'Reg Holiday')
					->setCellValue('I6', 'Special Holiday')
					->setCellValue('J6', 'Adjustment Before Tax')
					->setCellValue('K6', 'Gross income')
					->setCellValue('L6', 'SSS')
					->setCellValue('M6', 'Taxable Income')
					->setCellValue('N6', 'W/ Tax')
					->setCellValue('O6', 'SSS Loan')
					->setCellValue('P6', 'Cash Advance')
					->setCellValue('Q6', 'Cashbond')
					->setCellValue('R6', 'Adjustment After Tax')
					->setCellValue('S6', 'Net Salaries')
					->setCellValue('T6', 'Allowance')
					->setCellValue('U6', 'Total');

		}
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		/*$count = 6;
		$select_qry = "SELECT * FROM tb_leave WHERE approveStat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$dateFrom = date_format(date_create($row->dateFrom), 'F d, Y');
				$dateTo = date_format(date_create($row->dateTo), 'F d, Y');

				$approve = "Approve";
				if ($row->approveStat == 2) {
					$approve = "Disapprove";
				}

				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $fullName)
					->setCellValue('B'.$count, $dateFrom)
					->setCellValue('C'.$count, $dateTo)
					->setCellValue('D'.$count, $row->LeaveType)
					->setCellValue('E'.$count, $row->FileLeaveType)
					->setCellValue('F'.$count, $row->Remarks)
					->setCellValue('G'.$count, $approve);
			}
		}*/

		$grandTotal_1 = 0;
		$totalGrossIncome_1 = 0;
		$totalPagibigContrib_1 = 0;
		$totalPhilhealthContrib_1 = 0;
		$totalTaxableIncome_1 = 0;
		$totalTax_1 = 0;
		$totalPagibigLoan_1 = 0;
		$totalCashAdvance_1 = 0;
		$totalCashBond_1 = 0;
		$totalAdjustmentAfterTax_1 = 0;
		$totalNetSalaries_1 = 0;
		$totalAllowance_1 = 0;
		$total_absent_1 = 0;
		$total_tardiness_1 = 0;
		$total_overtime_1 = 0;
		$total_reg_holiday_1 = 0;
		$total_special_holiday_1 = 0;
		$total_adjustment_before_tax_1 = 0;


		$totalSSS_1 = 0;
		$totalSSSLoan_1 = 0;

		// select ung may mga may ATM
		$count = 6;
		$select_with_atm = "SELECT * FROM tb_employee_info WHERE WithAtm = '1' ORDER BY Lastname ASC";
		if ($result_with_atm = mysqli_query($connect,$select_with_atm)){
			while ($row_with_atm = mysqli_fetch_object($result_with_atm)){

				$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id = '$row_with_atm->emp_id'";
				if ($result = mysqli_query($connect,$select_qry)){
					while ($row = mysqli_fetch_object($result)){
					


						// for grand total
						if ($grandTotal_1 == 0){
							$grandTotal_1 = $row->netPay;
						}

						else {
							$grandTotal_1 = $grandTotal_1 + $row->netPay;
						}



						// employee info query
						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);

						$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
						$date_create = date_create($row_emp->DateHired);
						$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";

						if ($row_emp->DateHired == "0000-00-00"){
							$dateHired = "";
						}

						$salary = $row_emp->Salary;

						$select_min_wage_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
						$result_min_wage = mysqli_query($connect,$select_min_wage_qry);
						$row_min_wage = mysqli_fetch_object($result_min_wage);

						$minimumWage = ($row_min_wage->basicWage + $row_min_wage->COLA) * 26;
						$civilStatus = "S";

						if ($row_emp->CivilStatus == "Married") {
							$civilStatus = "ME";
						}

						// if salary is greater than minimum wage ibig sabihin may tax siya
						if ($salary > $minimumWage) {
							$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

							if ($num_rows == 0){
								$num_rows = "";
							}

							$civilStatus = $civilStatus . $num_rows;
						}
						// $pdf->Row(array($fullName . " ".$dateHired,$civilStatus,$this->getMoney($salary),$this->getMoney($allowance),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->adjustmentAfter),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),htmlspecialchars($row->remarks))); 

						$dailyRate = round($salary / 26,2);

						// pag-ibig loan
						if ($day == "10") {
							
							$loan = $row->pagibigLoan;
							

						}

						// sss loan
						if ($day == "25") {
							
							$loan = $row->sssLoan;
							

						}

						$taxableIncome = round($row->totalGrossIncome - $row->pagibigDeduction - $row->philhealthDeduction - $row->sssDeduction,2);

						if ($totalTaxableIncome_1 == 0) {
							$totalTaxableIncome_1 = $taxableIncome;
						}

						else {
							$totalTaxableIncome_1  = $totalTaxableIncome_1 + $taxableIncome;
						}
						
						$netSalaries = round($row->netPay - $row->NontaxAllowance,2);
						$halfMonth = round($salary /2);

						if ($totalGrossIncome_1 == 0){
							$totalGrossIncome_1 = $row->totalGrossIncome;
						}

						else {
							$totalGrossIncome_1 = $totalGrossIncome_1 + $row->totalGrossIncome;
						}


						if ($totalPagibigContrib_1 == 0){
							$totalPagibigContrib_1 = $row->pagibigDeduction;
						}

						else {
							$totalPagibigContrib_1 = $totalPagibigContrib_1 + $row->pagibigDeduction;
						}

						if ($totalPhilhealthContrib_1 == 0){
							$totalPhilhealthContrib_1 = $row->philhealthDeduction;
						}

						else {
							$totalPhilhealthContrib_1 = $totalPhilhealthContrib_1 + $row->philhealthDeduction;
						}


						if ($totalTax_1 == 0){
							$totalTax_1 = $row->Tax;
						}

						else {
							$totalTax_1 = $totalTax_1 + $row->Tax;
						}

						if ($totalPagibigLoan_1 == 0){
							$totalPagibigLoan_1 = $loan;
						}

						else {
							$totalPagibigLoan_1 = $totalPagibigLoan_1 + $loan;
						}

						if ($totalCashAdvance_1 == 0){
							$totalCashAdvance_1 = $row->cashAdvance;
						}

						else {
							$totalCashAdvance_1 = $totalCashAdvance_1 + $row->cashAdvance;
						}

						if ($totalCashBond_1 == 0){
							$totalCashBond_1 = $row->CashBond;
						}

						else {
							$totalCashBond_1 = $totalCashBond_1 + $row->CashBond;
						}

						if ($totalAdjustmentAfterTax_1 == 0){
							$totalAdjustmentAfterTax_1 = $row->adjustmentAfter;
						}

						else {
							$totalAdjustmentAfterTax_1 = $totalAdjustmentAfterTax_1 + $row->adjustmentAfter;
						}

						if ($totalNetSalaries_1 == 0){
							$totalNetSalaries_1 = $netSalaries;
						}

						else {
							$totalNetSalaries_1 = $totalNetSalaries_1 + $netSalaries;
						}

						if ($totalAllowance_1 == 0){
							$totalAllowance_1 = $row->NontaxAllowance;
						}

						else {
							$totalAllowance_1 = $totalAllowance_1 + $row->NontaxAllowance;
						}


						//$totalTardy = 0;
						
						if ($day == "25") {
							if ($totalSSS_1 == 0){
								$totalSSS_1 = $row->sssDeduction;
							}

							else {
								$totalSSS_1 = $row->sssDeduction + $totalSSS_1;
							}
						}


						if ($totalSSSLoan_1 == 0){
							$totalSSSLoan_1 = $loan;
						}

						else {
							$totalSSSLoan_1 = $totalSSSLoan_1 + $loan;
						}

						// for getting total ot
						$total_absent_1 += $row->Absences; 
						$total_tardiness_1 += $row->Tardiness;
						$total_reg_holiday_1 += $row->reg_holidayOT;
						$total_special_holiday_1 += $row->special_holidayOT;
						$total_adjustment_before_tax_1 += $row->adjustmentBefore;

						$totalOt = round($row->regularOT + $row->restdayOT + $row->reg_holidayOT + $row->special_holidayOT + $row->rd_reg_holidayOT + $row->rd_special_holidayOT,2);
						$total_overtime_1 += $totalOt;

						//$fullName = utf8_decode($fullName);
						if ($day == "10") {
							
							
							$count++;
							$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.$count, $fullName . " " .$dateHired)
								->setCellValue('B'.$count, $civilStatus)
								->setCellValue('C'.$count, $halfMonth)
								->setCellValue('D'.$count, $dailyRate)
								->setCellValue('E'.$count, $row->Absences)
								->setCellValue('F'.$count,$row->Tardiness)
								->setCellValue('G'.$count,$totalOt)
								->setCellValue('H'.$count,($row->reg_holidayOT))
								->setCellValue('I'.$count,($row->special_holidayOT))
								->setCellValue('J'.$count,($row->adjustmentBefore))
								->setCellValue('K'.$count,($row->totalGrossIncome))
								->setCellValue('L'.$count,($row->pagibigDeduction))
								->setCellValue('M'.$count,($row->philhealthDeduction))
								->setCellValue('N'.$count,($taxableIncome))
								->setCellValue('O'.$count,($row->Tax))
								->setCellValue('P'.$count,($loan))
								->setCellValue('Q'.$count,($row->cashAdvance))
								->setCellValue('R'.$count,($row->CashBond))
								->setCellValue('S'.$count,($row->adjustmentAfter))
								->setCellValue('T'.$count,($netSalaries))
								->setCellValue('U'.$count,($row->NontaxAllowance))
								->setCellValue('V'.$count,($row->netPay));

						}
						if ($day == "25") {
							
							$count++;
							$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.$count, $fullName . " " .$dateHired)
								->setCellValue('B'.$count, $civilStatus)
								->setCellValue('C'.$count, $halfMonth)
								->setCellValue('D'.$count, $dailyRate)
								->setCellValue('E'.$count, $row->Absences)
								->setCellValue('F'.$count,$row->Tardiness)
								->setCellValue('G'.$count,$totalOt)
								->setCellValue('H'.$count,($row->reg_holidayOT))
								->setCellValue('I'.$count,($row->special_holidayOT))
								->setCellValue('J'.$count,($row->adjustmentBefore))
								->setCellValue('K'.$count,($row->totalGrossIncome))
								->setCellValue('L'.$count,($row->sssDeduction))
								->setCellValue('M'.$count,($taxableIncome))
								->setCellValue('N'.$count,($row->Tax))
								->setCellValue('O'.$count,($loan))
								->setCellValue('P'.$count,($row->cashAdvance))
								->setCellValue('Q'.$count,($row->CashBond))
								->setCellValue('R'.$count,($row->adjustmentAfter))
								->setCellValue('S'.$count,($netSalaries))
								->setCellValue('T'.$count,($row->NontaxAllowance))
								->setCellValue('U'.$count,($row->netPay));
						}
					}
				}
				
			}
		}

		if ($day == "10"){
			//$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome),$this->getMoney($totalPagibigContrib),$this->getMoney($totalPhilhealthContrib),$this->getMoney($totalTaxableIncome),$this->getMoney($totalTax),$this->getMoney($totalSSSLoan),$this->getMoney($totalCashAdvance),$this->getMoney($totalCashBond),$this->getMoney($totalAdjustmentAfterTax),$this->getMoney($totalNetSalaries),$this->getMoney($totalAllowance),$this->getMoney($grandTotal)));
		
			//$total_absent_1 = 0;
		//$total_tardiness_1 = 0;
		//$total_overtime_1 = 0;
		//$total_reg_holiday_1 = 0;
		//$total_special_holiday_1 = 0;
		//$total_adjustment_before_tax_1 = 0;
			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,$total_absent_1)
								->setCellValue('F'.$count,$total_tardiness_1)
								->setCellValue('G'.$count,$total_overtime_1)
								->setCellValue('H'.$count,$total_reg_holiday_1)
								->setCellValue('I'.$count,$total_special_holiday_1)
								->setCellValue('J'.$count,$total_adjustment_before_tax_1)
								->setCellValue('K'.$count,($totalGrossIncome_1))
								->setCellValue('L'.$count,($totalPagibigContrib_1))
								->setCellValue('M'.$count,($totalPhilhealthContrib_1))
								->setCellValue('N'.$count,($totalTaxableIncome_1))
								->setCellValue('O'.$count,($totalTax_1))
								->setCellValue('P'.$count,($totalSSSLoan_1))
								->setCellValue('Q'.$count,($totalCashAdvance_1))
								->setCellValue('R'.$count,($totalCashBond_1))
								->setCellValue('S'.$count,($totalAdjustmentAfterTax_1))
								->setCellValue('T'.$count,($totalNetSalaries_1))
								->setCellValue('U'.$count,($totalAllowance_1))
								->setCellValue('V'.$count,($grandTotal_1));

		}
		else {
			//$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome_1),$this->getMoney($totalSSS_1),$this->getMoney($totalTaxableIncome_1),$this->getMoney($totalTax_1),$this->getMoney($totalSSSLoan_1),$this->getMoney($totalCashAdvance_1),$this->getMoney($totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries_1),$this->getMoney($totalAllowance_1),$this->getMoney($grandTotal_1)));
			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,$total_absent_1)
								->setCellValue('F'.$count,$total_tardiness_1)
								->setCellValue('G'.$count,$total_overtime_1)
								->setCellValue('H'.$count,$total_reg_holiday_1)
								->setCellValue('I'.$count,$total_special_holiday_1)
								->setCellValue('J'.$count,$total_adjustment_before_tax_1)
								->setCellValue('K'.$count,($totalGrossIncome_1))
								->setCellValue('L'.$count,($totalSSS_1))
								->setCellValue('M'.$count,($totalTaxableIncome_1))
								->setCellValue('N'.$count,($totalTax_1))
								->setCellValue('O'.$count,($totalSSSLoan_1))
								->setCellValue('P'.$count,($totalCashAdvance_1))
								->setCellValue('Q'.$count,($totalCashBond_1))
								->setCellValue('R'.$count,($totalAdjustmentAfterTax_1))
								->setCellValue('S'.$count,($totalNetSalaries_1))
								->setCellValue('T'.$count,($totalAllowance_1))
								->setCellValue('U'.$count,($grandTotal_1));
			
		}


		// for spacing purpose
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.++$count, '');


		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.++$count, 'WITHOUT ATM');


		$without_atm_cell_count = ++$count;
		if ($day == 10) {
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, 'Employee Name')
					->setCellValue('B'.$count, 'Civil Status')
					->setCellValue('C'.$count, 'Salary')
					->setCellValue('D'.$count, 'Daily Rate')
					->setCellValue('E'.$count, 'Absent')
					->setCellValue('F'.$count, 'Tardy/ Undertime')
					->setCellValue('G'.$count, 'Overtime')
					->setCellValue('H'.$count, 'Reg Holiday')
					->setCellValue('I'.$count, 'Special Holiday')
					->setCellValue('J'.$count, 'Adjustment Before Tax')
					->setCellValue('K'.$count, 'Gross income')
					->setCellValue('L'.$count, 'HDMF')
					->setCellValue('M'.$count, 'Philhealth')
					->setCellValue('N'.$count, 'Taxable Income')
					->setCellValue('O'.$count, 'W/ Tax')
					->setCellValue('P'.$count, 'HDMF Loan')
					->setCellValue('Q'.$count, 'Cash Advance')
					->setCellValue('R'.$count, 'Cashbond')
					->setCellValue('S'.$count, 'Adjustment After Tax')
					->setCellValue('T'.$count, 'Net Salaries')
					->setCellValue('U'.$count, 'Allowance')
					->setCellValue('V'.$count, 'Total');
		}

		else {
			$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, 'Employee Name')
					->setCellValue('B'.$count, 'Civil Status')
					->setCellValue('C'.$count, 'Salary')
					->setCellValue('D'.$count, 'Daily Rate')
					->setCellValue('E'.$count, 'Absent')
					->setCellValue('F'.$count, 'Tardy/ Undertime')
					->setCellValue('G'.$count, 'Overtime')
					->setCellValue('H'.$count, 'Reg Holiday')
					->setCellValue('I'.$count, 'Special Holiday')
					->setCellValue('J'.$count, 'Adjustment Before Tax')
					->setCellValue('K'.$count, 'Gross income')
					->setCellValue('L'.$count, 'SSS')
					->setCellValue('M'.$count, 'Taxable Income')
					->setCellValue('N'.$count, 'W/ Tax')
					->setCellValue('O'.$count, 'SSS Loan')
					->setCellValue('P'.$count, 'Cash Advance')
					->setCellValue('Q'.$count, 'Cashbond')
					->setCellValue('R'.$count, 'Adjustment After Tax')
					->setCellValue('S'.$count, 'Net Salaries')
					->setCellValue('T'.$count, 'Allowance')
					->setCellValue('U'.$count, 'Total');
		}


		$grandTotal = 0;
		$totalGrossIncome = 0;
		$totalPagibigContrib = 0;
		$totalPhilhealthContrib = 0;
		$totalTaxableIncome = 0;
		$totalTax = 0;
		$totalPagibigLoan = 0;
		$totalCashAdvance = 0;
		$totalCashBond = 0;
		$totalAdjustmentAfterTax = 0;
		$totalNetSalaries = 0;
		$totalAllowance = 0;
		$total_absent = 0;
		$total_tardiness = 0;
		$total_overtime = 0;
		$total_reg_holiday = 0;
		$total_special_holiday = 0;
		$total_adjustment_before_tax = 0;
		//$totalAbset = 0;


		$totalSSS = 0;
		$totalSSSLoan = 0;

		// select ung may mga may ATM
		$count = $count;
		$select_with_atm = "SELECT * FROM tb_employee_info WHERE WithAtm = '0' ORDER BY Lastname ASC";
		if ($result_with_atm = mysqli_query($connect,$select_with_atm)){
			while ($row_with_atm = mysqli_fetch_object($result_with_atm)){

				$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id = '$row_with_atm->emp_id'";
				if ($result = mysqli_query($connect,$select_qry)){
					while ($row = mysqli_fetch_object($result)){
					


						// for grand total
						if ($grandTotal == 0){
							$grandTotal = $row->netPay;
						}

						else {
							$grandTotal = $grandTotal + $row->netPay;
						}



						// employee info query
						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);

						$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
						$date_create = date_create($row_emp->DateHired);
						$dateHired = "(" . date_format($date_create, 'm/d/Y'). ")";

						if ($row_emp->DateHired == "0000-00-00"){
							$dateHired = "";
						}

						$salary = $row_emp->Salary;

						$select_min_wage_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
						$result_min_wage = mysqli_query($connect,$select_min_wage_qry);
						$row_min_wage = mysqli_fetch_object($result_min_wage);

						$minimumWage = ($row_min_wage->basicWage + $row_min_wage->COLA) * 26;
						$civilStatus = "S";

						if ($row_emp->CivilStatus == "Married") {
							$civilStatus = "ME";
						}

						// if salary is greater than minimum wage ibig sabihin may tax siya
						if ($salary > $minimumWage) {
							$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$row->emp_id'"));

							if ($num_rows == 0){
								$num_rows = "";
							}

							$civilStatus = $civilStatus . $num_rows;
						}
						// $pdf->Row(array($fullName . " ".$dateHired,$civilStatus,$this->getMoney($salary),$this->getMoney($allowance),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->adjustmentAfter),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),htmlspecialchars($row->remarks))); 

						$dailyRate = round($salary / 26,2);

						// pag-ibig loan
						if ($day == "10") {
							
							$loan = $row->pagibigLoan;
							

						}

						// sss loan
						if ($day == "25") {
							
							$loan = $row->sssLoan;
							

						}

						$taxableIncome = round($row->totalGrossIncome - $row->pagibigDeduction - $row->philhealthDeduction - $row->sssDeduction,2);

						if ($totalTaxableIncome == 0) {
							$totalTaxableIncome = $taxableIncome;
						}

						else {
							$totalTaxableIncome  = $totalTaxableIncome + $taxableIncome;
						}
						
						$netSalaries = round($row->netPay - $row->NontaxAllowance,2);
						$halfMonth = round($salary /2);

						if ($totalGrossIncome == 0){
							$totalGrossIncome = $row->totalGrossIncome;
						}

						else {
							$totalGrossIncome= $totalGrossIncome + $row->totalGrossIncome;
						}


						if ($totalPagibigContrib == 0){
							$totalPagibigContrib = $row->pagibigDeduction;
						}

						else {
							$totalPagibigContrib = $totalPagibigContrib + $row->pagibigDeduction;
						}

						if ($totalPhilhealthContrib == 0){
							$totalPhilhealthContrib = $row->philhealthDeduction;
						}

						else {
							$totalPhilhealthContrib = $totalPhilhealthContrib + $row->philhealthDeduction;
						}


						if ($totalTax == 0){
							$totalTax = $row->Tax;
						}

						else {
							$totalTax = $totalTax + $row->Tax;
						}

						if ($totalPagibigLoan == 0){
							$totalPagibigLoan = $loan;
						}

						else {
							$totalPagibigLoan = $totalPagibigLoan + $loan;
						}

						if ($totalCashAdvance == 0){
							$totalCashAdvance = $row->cashAdvance;
						}

						else {
							$totalCashAdvance = $totalCashAdvance + $row->cashAdvance;
						}

						if ($totalCashBond == 0){
							$totalCashBond = $row->CashBond;
						}

						else {
							$totalCashBond = $totalCashBond + $row->CashBond;
						}

						if ($totalAdjustmentAfterTax == 0){
							$totalAdjustmentAfterTax = $row->adjustmentAfter;
						}

						else {
							$totalAdjustmentAfterTax = $totalAdjustmentAfterTax + $row->adjustmentAfter;
						}

						if ($totalNetSalaries == 0){
							$totalNetSalaries = $netSalaries;
						}

						else {
							$totalNetSalaries = $totalNetSalaries + $netSalaries;
						}

						if ($totalAllowance == 0){
							$totalAllowance = $row->NontaxAllowance;
						}

						else {
							$totalAllowance = $totalAllowance + $row->NontaxAllowance;
						}


						//$totalTardy = 0;
						
						if ($day == "25") {
							if ($totalSSS == 0){
								$totalSSS = $row->sssDeduction;
							}

							else {
								$totalSSS = $row->sssDeduction + $totalSSS_1;
							}
						}

						
						if ($totalSSSLoan == 0){
							$totalSSSLoan = $loan;
						}

						else {
							$totalSSSLoan = $totalSSSLoan + $loan;
						}

						$total_absent += $row->Absences; 
						$total_tardiness += $row->Tardiness;
						$total_reg_holiday += $row->reg_holidayOT;
						$total_special_holiday += $row->special_holidayOT;
						$total_adjustment_before_tax += $row->adjustmentBefore;
						// for getting total ot
						$totalOt = round($row->regularOT + $row->restdayOT + $row->reg_holidayOT + $row->special_holidayOT + $row->rd_reg_holidayOT + $row->rd_special_holidayOT,2);
						$total_overtime += $totalOt;

						//$fullName = utf8_decode($fullName);
						if ($day == "10") {
							
							
							$count++;
							$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.$count, $fullName . " " .$dateHired)
								->setCellValue('B'.$count, $civilStatus)
								->setCellValue('C'.$count, $halfMonth)
								->setCellValue('D'.$count, $dailyRate)
								->setCellValue('E'.$count, $row->Absences)
								->setCellValue('F'.$count,$row->Tardiness)
								->setCellValue('G'.$count,$totalOt)
								->setCellValue('H'.$count,($row->reg_holidayOT))
								->setCellValue('I'.$count,($row->special_holidayOT))
								->setCellValue('J'.$count,($row->adjustmentBefore))
								->setCellValue('K'.$count,($row->totalGrossIncome))
								->setCellValue('L'.$count,($row->pagibigDeduction))
								->setCellValue('M'.$count,($row->philhealthDeduction))
								->setCellValue('N'.$count,($taxableIncome))
								->setCellValue('O'.$count,($row->Tax))
								->setCellValue('P'.$count,($loan))
								->setCellValue('Q'.$count,($row->cashAdvance))
								->setCellValue('R'.$count,($row->CashBond))
								->setCellValue('S'.$count,($row->adjustmentAfter))
								->setCellValue('T'.$count,($netSalaries))
								->setCellValue('U'.$count,($row->NontaxAllowance))
								->setCellValue('V'.$count,($row->netPay));

						}
						if ($day == "25") {
							//$pdf->Row(array($fullName . " " .$dateHired ,$civilStatus,$this->getMoney($halfMonth),$this->getMoney($dailyRate),$this->getMoney($row->Absences),$this->getMoney($row->Tardiness),$this->getMoney($totalOt),$this->getMoney($row->reg_holidayOT),$this->getMoney($row->special_holidayOT),$this->getMoney($row->adjustmentBefore),$this->getMoney($row->totalGrossIncome),$this->getMoney($row->sssDeduction),$this->getMoney($taxableIncome),$this->getMoney($row->Tax),$this->getMoney($loan),$this->getMoney($row->cashAdvance),$this->getMoney($row->CashBond),$this->getMoney($row->adjustmentAfter),$this->getMoney($netSalaries),$this->getMoney($row->NontaxAllowance),$this->getMoney($row->netPay)));
							$count++;
							$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.$count, $fullName . " " .$dateHired)
								->setCellValue('B'.$count, $civilStatus)
								->setCellValue('C'.$count, $halfMonth)
								->setCellValue('D'.$count, $dailyRate)
								->setCellValue('E'.$count, $row->Absences)
								->setCellValue('F'.$count,$row->Tardiness)
								->setCellValue('G'.$count,$totalOt)
								->setCellValue('H'.$count,($row->reg_holidayOT))
								->setCellValue('I'.$count,($row->special_holidayOT))
								->setCellValue('J'.$count,($row->adjustmentBefore))
								->setCellValue('K'.$count,($row->totalGrossIncome))
								->setCellValue('L'.$count,($row->sssDeduction))
								->setCellValue('M'.$count,($taxableIncome))
								->setCellValue('N'.$count,($row->Tax))
								->setCellValue('O'.$count,($loan))
								->setCellValue('P'.$count,($row->cashAdvance))
								->setCellValue('Q'.$count,($row->CashBond))
								->setCellValue('R'.$count,($row->adjustmentAfter))
								->setCellValue('S'.$count,($netSalaries))
								->setCellValue('T'.$count,($row->NontaxAllowance))
								->setCellValue('U'.$count,($row->netPay));	
						}
					}
				}
				
			}
		}

		if ($day == "10"){
			//$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome),$this->getMoney($totalPagibigContrib),$this->getMoney($totalPhilhealthContrib),$this->getMoney($totalTaxableIncome),$this->getMoney($totalTax),$this->getMoney($totalSSSLoan),$this->getMoney($totalCashAdvance),$this->getMoney($totalCashBond),$this->getMoney($totalAdjustmentAfterTax),$this->getMoney($totalNetSalaries),$this->getMoney($totalAllowance),$this->getMoney($grandTotal)));
	
			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,$total_absent)
								->setCellValue('F'.$count,$total_tardiness)
								->setCellValue('G'.$count,$total_overtime)
								->setCellValue('H'.$count,$total_reg_holiday)
								->setCellValue('I'.$count,$total_special_holiday)
								->setCellValue('J'.$count,$total_adjustment_before_tax)
								->setCellValue('K'.$count,($totalGrossIncome))
								->setCellValue('L'.$count,($totalPagibigContrib))
								->setCellValue('M'.$count,($totalPhilhealthContrib))
								->setCellValue('N'.$count,($totalTaxableIncome))
								->setCellValue('O'.$count,($totalTax))
								->setCellValue('P'.$count,($totalSSSLoan))
								->setCellValue('Q'.$count,($totalCashAdvance))
								->setCellValue('R'.$count,($totalCashBond))
								->setCellValue('S'.$count,($totalAdjustmentAfterTax))
								->setCellValue('T'.$count,($totalNetSalaries))
								->setCellValue('U'.$count,($totalAllowance))
								->setCellValue('V'.$count,($grandTotal));

		}
		else {
			//$pdf->Row(array("TOTAL","","","","","","","","","",$this->getMoney($totalGrossIncome_1),$this->getMoney($totalSSS_1),$this->getMoney($totalTaxableIncome_1),$this->getMoney($totalTax_1),$this->getMoney($totalSSSLoan_1),$this->getMoney($totalCashAdvance_1),$this->getMoney($totalCashBond_1),$this->getMoney($totalAdjustmentAfterTax_1),$this->getMoney($totalNetSalaries_1),$this->getMoney($totalAllowance_1),$this->getMoney($grandTotal_1)));
			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,$total_absent)
								->setCellValue('F'.$count,$total_tardiness)
								->setCellValue('G'.$count,$total_overtime)
								->setCellValue('H'.$count,$total_reg_holiday)
								->setCellValue('I'.$count,$total_special_holiday)
								->setCellValue('J'.$count,$total_adjustment_before_tax)
								->setCellValue('K'.$count,($totalGrossIncome))
								->setCellValue('L'.$count,($totalSSS))
								->setCellValue('M'.$count,($totalTaxableIncome))
								->setCellValue('N'.$count,($totalTax))
								->setCellValue('O'.$count,($totalSSSLoan))
								->setCellValue('P'.$count,($totalCashAdvance))
								->setCellValue('Q'.$count,($totalCashBond))
								->setCellValue('R'.$count,($totalAdjustmentAfterTax))
								->setCellValue('S'.$count,($totalNetSalaries))
								->setCellValue('T'.$count,($totalAllowance))
								->setCellValue('U'.$count,($grandTotal));

		}


		$count = $count+2;
		if ($day == "10"){

			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.$count, "GRAND TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,($total_absent + $total_absent_1))
								->setCellValue('F'.$count,($total_tardiness + $total_tardiness_1))
								->setCellValue('G'.$count,($total_overtime + $total_overtime_1))
								->setCellValue('H'.$count,($total_reg_holiday + $total_reg_holiday_1))
								->setCellValue('I'.$count,($total_special_holiday + $total_special_holiday_1))
								->setCellValue('J'.$count,($total_adjustment_before_tax + $total_adjustment_before_tax_1))
								->setCellValue('K'.$count,($totalGrossIncome + $totalGrossIncome_1))
								->setCellValue('L'.$count,($totalPagibigContrib + $totalPagibigContrib_1))
								->setCellValue('M'.$count,($totalPhilhealthContrib + $totalPhilhealthContrib_1))
								->setCellValue('N'.$count,($totalTaxableIncome + $totalTaxableIncome_1))
								->setCellValue('O'.$count,($totalTax + $totalTax_1))
								->setCellValue('P'.$count,($totalSSSLoan + $totalSSSLoan_1))
								->setCellValue('Q'.$count,($totalCashAdvance + $totalCashAdvance_1))
								->setCellValue('R'.$count,($totalCashBond + $totalCashBond_1))
								->setCellValue('S'.$count,($totalAdjustmentAfterTax + $totalAdjustmentAfterTax_1))
								->setCellValue('T'.$count,($totalNetSalaries + $totalNetSalaries_1))
								->setCellValue('U'.$count,($totalAllowance + $totalAllowance_1))
								->setCellValue('V'.$count,($grandTotal + $grandTotal_1));

		}

		else {
			$objPHPExcel->setActiveSheetIndex(0) 
								->setCellValue('A'.++$count, "GRAND TOTAL")
								->setCellValue('B'.$count,"")
								->setCellValue('C'.$count,"")
								->setCellValue('D'.$count,"")
								->setCellValue('E'.$count,($total_absent + $total_absent_1))
								->setCellValue('F'.$count,($total_tardiness + $total_tardiness_1))
								->setCellValue('G'.$count,($total_overtime + $total_overtime_1))
								->setCellValue('H'.$count,($total_reg_holiday + $total_reg_holiday_1))
								->setCellValue('I'.$count,($total_special_holiday + $total_special_holiday_1))
								->setCellValue('J'.$count,($total_adjustment_before_tax + $total_adjustment_before_tax_1))
								->setCellValue('K'.$count,($totalGrossIncome + $totalGrossIncome_1))
								->setCellValue('L'.$count,($totalSSS + $totalSSS_1))
								->setCellValue('M'.$count,($totalTaxableIncome +$totalTaxableIncome_1))
								->setCellValue('N'.$count,($totalTax+ $totalTax_1))
								->setCellValue('O'.$count,($totalSSSLoan+$totalSSSLoan_1))
								->setCellValue('P'.$count,($totalCashAdvance+$totalCashAdvance_1))
								->setCellValue('Q'.$count,($totalCashBond + $totalCashBond_1))
								->setCellValue('R'.$count,($totalAdjustmentAfterTax + $totalAdjustmentAfterTax_1))
								->setCellValue('S'.$count,($totalNetSalaries + $totalNetSalaries_1))
								->setCellValue('T'.$count,($totalAllowance + $totalAllowance_1))
								->setCellValue('U'.$count,($grandTotal + $grandTotal_1));
		}


		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		
		
		if ($day == 10) {
			/*********************Autoresize column width depending upon contents START**********************/
	        foreach(range('A','V') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			/*********************Autoresize column width depending upon contents END***********************/
			$objPHPExcel->getActiveSheet()->getStyle('A6:V6')->getFont()->setBold(true); //Make heading font bold
			$objPHPExcel->getActiveSheet()->getStyle('A'.$without_atm_cell_count.':V'.$without_atm_cell_count)->getFont()->setBold(true); //Make heading font bold
			
			/*********************Add color to heading START**********************/
			$objPHPExcel->getActiveSheet()
						->getStyle('A6:V6')
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('abb2b9');

			$objPHPExcel->getActiveSheet()
						->getStyle('A'.$without_atm_cell_count.':V'.$without_atm_cell_count)
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('abb2b9');
			/*********************Add color to heading END***********************/
		}
		// kapag 25
		else {
			/*********************Autoresize column width depending upon contents START**********************/
	        foreach(range('A','U') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			/*********************Autoresize column width depending upon contents END***********************/
			$objPHPExcel->getActiveSheet()->getStyle('A6:U6')->getFont()->setBold(true); //Make heading font bold
			$objPHPExcel->getActiveSheet()->getStyle('A'.$without_atm_cell_count.':U'.$without_atm_cell_count)->getFont()->setBold(true); //Make heading font bold
			
			/*********************Add color to heading START**********************/
			$objPHPExcel->getActiveSheet()
						->getStyle('A6:U6')
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('abb2b9');

			$objPHPExcel->getActiveSheet()
						->getStyle('A'.$without_atm_cell_count.':U'.$without_atm_cell_count)
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('abb2b9');
			/*********************Add color to heading END***********************/
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('payroll_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}


	// for printing salary info reports



	public function countPayrollCreatedToEmployee($emp_id){
		$connect = $this->connect();
		//$select_last_id_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' ORDER BY payroll_id DESC LIMIT 1";
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' "));
        return $num_rows;
	}
	

	public function getCutOff13MonthPayOld($emp_id,$cutOffPeriod){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		
		return $row;

	}

	public function getCountCutOff13MonthPayOld($emp_id,$cutOffPeriod){
		$connect = $this->connect();
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$emp_id' AND CutOffPeriod = '$cutOffPeriod'"));
		
		return $num_rows;

	}


	// for money output with comma
	public function getMoney($value){

		if ($value < 0) { // if 0   
			 $final_value = $value;    
		}

		else if ($value < 1 && $value > 0) { // if 0   
			 $final_value = $value;    
		}

        else if ($value == 0) { // if 0       
            
            $final_value = "0";                   
        }


		else if ($value >= 1 && $value < 10) { // for 1 digit
          
          	$decimal = "";

          	$one = substr($value,0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,1);
            	$final_value = $one . $decimal;
            }

            else {
            	$final_value =  $one . ".00";
            }

            
        }

		else if ($value >= 10 && $value < 100) { // for 2 digits 
          
          	$decimal = "";
            $ten = substr($value,0,1);
            $one = substr(substr($value,1),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,2);
            	$final_value = $ten . $one . $decimal;
            }
            else {
            	$final_value = $ten . $one . ".00";
            }

            
        }


		else if ($value >= 100 && $value < 1000) { // for 3 digits 
          
          	$decimal = "";
            $hundred = substr($value,0,1);
            $ten = substr(substr($value,1),0,1);
            $one = substr(substr($value,2),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,3);
            	$final_value =$hundred . $ten . $one . $decimal;
            }

            else {
            	 $final_value =  $hundred . $ten . $one . ".00";
            }

           
        }


        else if ($value >= 1000 && $value < 10000) { // for 4 digits 
          
          	$decimal = "";
            $thousand = substr($value,0,1);
            $hundred = substr(substr($value,1),0,1);
            $ten = substr(substr($value,2),0,1);
            $one = substr(substr($value,3),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,4);
            	$final_value =  $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value =  $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
        }

        else if ($value >= 10000 && $value < 100000) { // for 5 digits
        	$ten_thousand = substr($value,0,1);
        	$thousand = substr(substr($value,1),0,1);
        	$hundred = substr(substr($value,2),0,1);
        	$ten = substr(substr($value,3),0,1);
        	$one = substr(substr($value,4),0,1);

        	$decimal = "";
        	 if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,5);
            	$final_value =  $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value =  $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
           
        }

        else if ($value>= 100000 && $value < 1000000) { // 6 digits
        	$hundred_thousand = substr($value,0,1);
            $ten_thousand = substr(substr($value,1),0,1);
        	$thousand = substr(substr($value,2),0,1);
        	$hundred = substr(substr($value,3),0,1);
        	$ten = substr(substr($value,4),0,1);
        	$one = substr(substr($value,5),0,1);

        	$decimal = "";
        	 if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,6);
            	$final_value = $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value =  $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }
        }

         else if ($value>= 1000000 && $value < 10000000) { // 7 digits

        	$million = substr($value,0,1);
            $hundred_thousand = substr(substr($value,1),0,1);
        	$ten_thousand = substr(substr($value,2),0,1);
        	$thousand = substr(substr($value,3),0,1);
        	$hundred = substr(substr($value,4),0,1);
        	$ten = substr(substr($value,5),0,1);
        	$one = substr(substr($value,6),0,1);

        	$decimal = "";
        	 if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,6);
            	$final_value = $million . ", ".$hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value = $million . ", ". $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }
        }

        return $final_value;
	}


	// if has decimal
	function is_decima($val)
	{
	    return is_numeric($val) && floor($val) != $val;
	}

   
}

?>