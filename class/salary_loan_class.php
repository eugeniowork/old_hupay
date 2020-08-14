<?php
class SalaryLoan extends Connect_db{

	
	public function getInfoBySalaryLoanId($salary_loan_id){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);

		$select_qry = "SELECT * FROM tb_salary_loan WHERE salary_loan_id='$salary_loan_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}


	// for specific person log in
	public function getEmpInfoByRow($id){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for getting info by emp_id for payroll
	public function getInfoBySalaryLoanEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_salary_loan WHERE emp_id='$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}


	// for getting all salary loan
	public function getAllSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$remainingBalance = 0;
		$select_qry = "SELECT * FROM tb_salary_loan WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				$remainingBalance += $row->remainingBalance;
			}
		}

		return $remainingBalance;
	}

	public function getSalaryLoanInfoToPayroll($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		//echo $emp_id . " ";

		date_default_timezone_set("Asia/Manila");
			//$date = date_create("1/1/1990");

			$dates = date("Y-m-d H:i:s");
			$date = date_create($dates);
			//date_sub($date, date_interval_create_from_date_string('15 hours'));

			// $current_date_time = date_format($date, 'Y-m-d H:i:s');
			$current_date_time = date_format($date, 'Y-m-d');

			//echo $current_date_time;
			$year = date("Y");

			// for cutoff
			$select_cutoff_qry = "SELECT * FROM tb_cut_off";
			if ($result_cutoff = mysqli_query($connect,$select_cutoff_qry)){
				while($row_cutoff = mysqli_fetch_object($result_cutoff)){
					//$date_to = $row_cutoff->dateTo . ", " .$year;

				$date_from = date_format(date_create($row_cutoff->dateFrom),'Y-m-d');
				if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
                    //echo "wew";
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($row_cutoff->dateFrom),'m-d');
                    //echo $date_from . "sad";
                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row_cutoff->dateTo. ", " .$year),'Y-m-d');
					//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
					//echo $to . "<br/>";


					$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}




			$cutOff_day = $this->getCutOffDay();

		//if ($cutOff_day == "15") {


		$salary_loan_amount = 0;
		$select_qry = "SELECT * FROM tb_salary_loan WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row_salaryLoan = mysqli_fetch_object($result)){
				//$row_salaryLoan = $salary_loan_class->getInfoBySalaryLoanEmpId($emp_id); 

				// 
				// september 15, 2017 , september 30, 2017 , may 15, 2018 , september 30, 2017
				if ($row_salaryLoan->dateFrom <= $date_payroll && $row_salaryLoan->dateTo >= $date_payroll) { // july 30, 2017 , august 26, 2017 {}
					
					//echo "wew " . $row_salaryLoan->salary_loan_id . " " . $row_salaryLoan->deduction . "<br/>";

					if ($row_salaryLoan->deductionType == "Monthly" && $row_salaryLoan->deductionDay == $cutOff_day) {
						

						if ($row_salaryLoan->deduction >= $row_salaryLoan->remainingBalance){
							$salary_loan_amount += $row_salaryLoan->remainingBalance;
						}

						else {
							$salary_loan_amount += $row_salaryLoan->deduction;
						}
					}

					if ($row_salaryLoan->deductionType == "Semi-monthly"){
						

						if ($row_salaryLoan->deduction >= $row_salaryLoan->remainingBalance){

							//echo "trigger";
							$salary_loan_amount += $row_salaryLoan->remainingBalance;
						}

						else {
							$salary_loan_amount += $row_salaryLoan->deduction;
						}

						//echo $salary_loan_amount . "<br/>";
					}
				}// end of if

			}
		}

		return $salary_loan_amount;
	}

	// for getting day
	public function getCutOffDay(){
		$connect = $this->connect();
		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');


		$year = date("Y");

		$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$date_from = date_format(date_create($row->dateFrom),'Y-m-d');
				if (date_format(date_create($row->dateFrom),'m-d') == "12-26"){
                    //echo "wew";
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($row->dateFrom),'m-d');
                    //echo $date_from . "sad";
                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row->dateTo),'Y-m-d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";


				$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		return $date_payroll;
	} // end of function


	public function insertSalaryLoan($emp_id,$approver_id,$pre_approver_id,$pre_approval_date,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$remainingBalance,$remarks,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$approver_id = mysqli_real_escape_string($connect,$approver_id);
		$pre_approver_id = mysqli_real_escape_string($connect,$pre_approver_id);
		$pre_approval_date = mysqli_real_escape_string($connect,$pre_approval_date);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$totalPayment = mysqli_real_escape_string($connect,$totalPayment);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_salary_loan (emp_id,approver_id,pre_approver_id,pre_approval_date,deductionType,deductionDay,totalMonths,dateFrom,dateTo,amountLoan,totalPayment,deduction,remainingBalance,remarks,DateCreated)
					VALUES ('$emp_id','$approver_id','$pre_approver_id','$pre_approval_date','$deductionType','$deductionDay','$totalMonths','$dateFrom','$dateTo','$amountLoan','$totalPayment','$deduction','$remainingBalance','$remarks','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	public function insertSalaryLoanManual($emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$remainingBalance,$remarks,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$totalPayment = mysqli_real_escape_string($connect,$totalPayment);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_salary_loan (emp_id,deductionType,deductionDay,totalMonths,dateFrom,dateTo,amountLoan,totalPayment,deduction,remainingBalance,remarks,DateCreated)
					VALUES ('$emp_id','$deductionType','$deductionDay','$totalMonths','$dateFrom','$dateTo','$amountLoan','$totalPayment','$deduction','$remainingBalance','$remarks','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	public function getSalaryLoanInfoToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_salary_loan";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_query_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_query_emp);
				$row_emp = mysqli_fetch_object($result_emp);

				$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$date_range = $dateFrom . "- " .$dateTo;


				$ref_no = $row->ref_no;
				$loan_type = "Salary Loan";
				if ($ref_no != ""){

					$row_fl = $this->getFileLoanInfoByRefNo($ref_no);
					
					if ($row_fl->type == 3){
						$loan_type = "Employee Benifit Program";
					}
				}

				$info = "";
				$info .=  "<b>".$ref_no ."</b>" . "<br/>";
				$info .= $loan_type;

				if ($row->remainingBalance != 0) {

					$day = "";
					if ($row->deductionType == "Monthly"){
						$day = "("  .$row->deductionDay.")";
					}

					//if ($row->remainingBalance >= $row->amountLoan){
						echo "<tr id='".$row->salary_loan_id."'>";
							echo "<td><small>".$emp_name."</small></td>";
							echo "<td><small>".$date_range."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->remainingBalance)."</small></td>";
							echo "<td><small>".$row->deductionType."" .$day."</small></td>";
							echo "<td><small>".$info."</small></td>";
							echo "<td><small>";
								echo "<a href='#' id='edit_salaryLoan' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='adjust_salaryLoan' class='action-a'><span class='glyphicon glyphicon-adjust' style='color: #239b56 '></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='delete_salaryLoan' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='view_salaryLoan_history' class='action-a'><span class='glyphicon glyphicon-eye-open' style='color: #3498db '></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='printSalaryLoan' class='action-a'><span class='glyphicon glyphicon-print' style='color:#515a5a'></span></a>";
							echo "</small></td>";
						echo "</tr>";
					//}

					/*else {

						echo "<tr id='".$row->salary_loan_id."'>";
							echo "<td><small>".$emp_name."</small></td>";
							echo "<td><small>".$date_range."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
							echo "<td><small>Php ".$this->getMoney($row->remainingBalance)."</small></td>";
							echo "<td><small>".$row->deductionType."" .$day."</small></td>";
							echo "<td><small>".$row->remarks."</small></td>";
							echo "<td><small>";
								echo "<a href='#' id='edit_salaryLoan' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='adjust_salaryLoan' class='action-a'><span class='glyphicon glyphicon-adjust' style='color: #239b56 '></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='delete_salaryLoan' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span></a>";
								echo "<span> | </span>";
								echo "<a href='#' id='view_salaryLoan_history' class='action-a'><span class='glyphicon glyphicon-eye-open' style='color: #3498db '></span></a>";	
							echo "</small></td>";
						echo "</tr>";
					}*/
				}


			}
		}
	}


	// for getting all loan na tapos na for history purpose
	public function getAllSalaryLoanHistory(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_salary_loan WHERE remainingBalance = '0'";
		if($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$select_query_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_query_emp);
				$row_emp = mysqli_fetch_object($result_emp);

				$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$date_range = $dateFrom . "- " .$dateTo;

				$day = "";
				if ($row->deductionType == "Monthly"){
					$day = "("  .$row->deductionDay.")";
				}

				echo "<tr>";
					echo "<td><small>".$emp_name."</small></td>";
					echo "<td><small>".$date_range."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
					echo "<td><small>".$row->deductionType."" .$day."</small></td>";
				echo "</tr>";

			}
		}
	}



	// for getting own salary loan getOwnSalaryLoanToTable
	public function getOwnSalaryLoanToTable($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_salary_loan WHERE emp_id = '$emp_id' ORDER BY DateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_query_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_query_emp);
				$row_emp = mysqli_fetch_object($result_emp);

				$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$date_range = $dateFrom . "- " .$dateTo;

				$status = "Finish";
				if ($row->remainingBalance != 0) {
					$status = "Current";
				}
					echo "<tr>";
						echo "<td>".$date_range."</td>";
						echo "<td>Php ".$this->getMoney($row->amountLoan)."</td>";
						echo "<td>Php ".$this->getMoney($row->deduction)."</td>";
						echo "<td>Php ".$this->getMoney($row->remainingBalance)."</td>";
						echo "<td>".$row->remarks."</td>";
						echo "<td>".$status."</td>";
					echo "</tr>";
				


			}
		}
	}


	// check if has an exist pag-bigi loan for update
	public function checkExistSalaryLoanUpdate($salary_loan_id){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_salary_loan WHERE salary_loan_id = '$salary_loan_id'"));
		return $num_rows;
	}


	// check if has an existing loan
	public function existSalaryLoan($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_salary_loan WHERE emp_id = '$emp_id' AND remainingBalance != '0'"));
		return $num_rows;
	}


	// if no changes was made same info
	public function sameSalaryLoanInfo($salary_loan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$remarks){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$remarks = mysqli_real_escape_string($connect,$remarks);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_salary_loan WHERE 
												deductionType = '$deductionType' AND deductionDay = '$deductionDay' AND totalMonths = '$totalMonths' AND dateFrom = '$dateFrom' AND dateTo = '$dateTo' AND amountLoan = '$amountLoan' AND
												deduction = '$deduction' AND remainingBalance = '$remainingBalance' AND remarks = '$remarks' AND salary_loan_id = '$salary_loan_id'"));
		return $num_rows;
	}


	// if no changes was made same info in filing salary loan
	public function sameFileSalaryLoanInfo($file_salary_loan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$totalPayment = mysqli_real_escape_string($connect,$totalPayment);
		$deduction = mysqli_real_escape_string($connect,$deduction);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_salary_loan WHERE 
												deductionType = '$deductionType' AND deductionDay = '$deductionDay' AND totalMonths = '$totalMonths' AND dateFrom = '$dateFrom' AND dateTo = '$dateTo' AND amountLoan = '$amountLoan' AND totalPayment = '$totalPayment' AND
												deduction = '$deduction' AND file_salary_loan_id = '$file_salary_loan_id'"));
		return $num_rows;
	}


	// for update information
	public function updateSalaryLoan($salary_loan_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$remarks){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$remarks = mysqli_real_escape_string($connect,$remarks);

		$update_qry = "UPDATE tb_salary_loan SET deductionType = '$deductionType', deductionDay = '$deductionDay', totalMonths = '$totalMonths', dateFrom = '$dateFrom',dateTo = '$dateTo', amountLoan = '$amountLoan', 
												deduction = '$deduction', remainingBalance = '$remainingBalance', remarks = '$remarks' 
												WHERE salary_loan_id = '$salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for deleting pagibigLoan
	public function deleteSalaryLoan($salary_loan_id){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);

		$delete_qry = "DELETE FROM tb_salary_loan WHERE salary_loan_id = '$salary_loan_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}



	// for checking if the range of date is sakop and ung remain balance is not 0
	public function existPendingSalaryLoan($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);


		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		//echo $current_date_time;
		$year = date("Y");

		// for cutoff
		$select_cutoff_qry = "SELECT * FROM tb_cut_off";
		if ($result_cutoff = mysqli_query($connect,$select_cutoff_qry)){
			while($row_cutoff = mysqli_fetch_object($result_cutoff)){
				//$date_to = $row_cutoff->dateTo . ", " .$year;

				$date_from = date_format(date_create($row_cutoff->dateFrom),'Y-m-d');
				 if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
                    //echo "wew";
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($row_cutoff->dateFrom),'m-d');
                    //echo $date_from . "sad";
                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row_cutoff->dateTo. ", " .$year),'Y-m-d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";


				$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
				}


			}

		}

       
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_salary_loan WHERE dateFrom <= '$date_payroll' AND dateTo >= '$date_payroll' AND emp_id = '$emp_id' AND remainingBalance != '0'"));
		return $num_rows;
	}


	// for deducting in pag-ibig loan in payroll when apporve
	public function deductSalaryLoan($cutOffPeriod){
		$connect = $this->connect();

		date_default_timezone_set("Asia/Manila");
			//$date = date_create("1/1/1990");

			$dates = date("Y-m-d H:i:s");
			$date = date_create($dates);
			//date_sub($date, date_interval_create_from_date_string('15 hours'));

			// $current_date_time = date_format($date, 'Y-m-d H:i:s');
			$current_date_time = date_format($date, 'Y-m-d');

			//echo $current_date_time;
			$year = date("Y");

			// for cutoff
			$select_cutoff_qry = "SELECT * FROM tb_cut_off";
			if ($result_cutoff = mysqli_query($connect,$select_cutoff_qry)){
				while($row_cutoff = mysqli_fetch_object($result_cutoff)){
					//$date_to = $row_cutoff->dateTo . ", " .$year;

					$date_from = date_format(date_create($row_cutoff->dateFrom),'Y-m-d');
					 if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
	                    //echo "wew";
	                    $prev_year = $year - 1;
	                    $date_from = $prev_year . "-" .date_format(date_create($row_cutoff->dateFrom),'m-d');
	                    //echo $date_from . "sad";
	                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

	                }
	                $date_from = date_format(date_create($date_from),"Y-m-d");
					$date_to = date_format(date_create($row_cutoff->dateTo. ", " .$year),'Y-m-d');
					//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
					//echo $to . "<br/>";


					$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}




		$cutOff_day = $this->getCutOffDay();


		$remainingBalance = 0;
		$select_qry = "SELECT * FROM tb_salary_loan WHERE remainingBalance != '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				$active_status = $this->getEmpInfoByRow($row->emp_id)->ActiveStatus;


				if ($active_status == 1){

					if ($row->dateFrom <= $date_payroll) { // july 30, 2017 , august 26, 2017 {} // && $row->dateTo >= $date_payroll
						

						

						if ($row->deductionType == "Monthly" && $row->deductionDay == $cutOff_day) {
							$remainingBalance = $row->remainingBalance - $row->deduction;
						// so update qry for new remaining balance

							if ($remainingBalance <= 0){
								$remainingBalance = 0;
							}

							$update_qry = "UPDATE tb_salary_loan SET remainingBalance = '$remainingBalance' WHERE salary_loan_id = '$row->salary_loan_id'";
							$sql = mysqli_query($connect,$update_qry);
							
							//echo "wew <br/>";
							$this->insertSalaryLoanHistory($row->salary_loan_id,$remainingBalance,$row->deduction,$cutOffPeriod,$date_payroll,$current_date_time);

						}

						if ($row->deductionType == "Semi-monthly"){
							$remainingBalance = $row->remainingBalance - $row->deduction;


							if ($remainingBalance <= 0){
								$remainingBalance = 0;
							}

						// so update qry for new remaining balance
							$update_qry = "UPDATE tb_salary_loan SET remainingBalance = '$remainingBalance' WHERE salary_loan_id = '$row->salary_loan_id'";
							$sql = mysqli_query($connect,$update_qry);

							//echo "wew <br/>";
							$this->insertSalaryLoanHistory($row->salary_loan_id,$remainingBalance,$row->deduction,$cutOffPeriod,$date_payroll,$current_date_time);
						}


						

					}
				}
			}
		}
	}


	// for pag ibig loan adjustment update only the remaining balance
	public function updateOnlyRemainingBalance($salary_loan_id,$remainingBalance){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);

		$update_qry = "UPDATE tb_salary_loan SET remainingBalance = '$remainingBalance' WHERE salary_loan_id = '$salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}





	// for file salary loan of employee
	public function insertFileSalaryLoan($emp_id,$pre_approver_id,$pre_approval_date,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$remarks,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$pre_approver_id = mysqli_real_escape_string($connect,$pre_approver_id);
		$pre_approval_date = mysqli_real_escape_string($connect,$pre_approval_date);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$totalPayment = mysqli_real_escape_string($connect,$totalPayment);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_file_salary_loan (emp_id,pre_approver_id,pre_approval_date,deductionType,deductionDay,totalMonths,dateFrom,dateTo,amountLoan,totalPayment,deduction,remarks,apporveStat,dateCreated) 
						VALUES ('$emp_id','$pre_approver_id','$pre_approval_date','$deductionType','$deductionDay','$totalMonths','$dateFrom','$dateTo','$amountLoan','$totalPayment','$deduction','$remarks','0','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);

	}

	public function updateReferenceNo($file_salary_loan_id,$ref_no){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_file_salary_loan SET ref_no = '$ref_no' WHERE file_salary_loan_id = '$file_salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}

	// for checking if has already an existing file salary loan that is under proccess
	public function checkExistFileSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_salary_loan WHERE emp_id = '$emp_id' AND (apporveStat = '0' OR apporveStat = '1')"));
		return $num_rows;
	}

	// for checking kapag may exist tiyang 1 approve stat for accepting
	public function checkExistAcceptFileSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_salary_loan WHERE emp_id = '$emp_id' AND apporveStat = '1'"));
		return $num_rows;
	}



	// for getting filed salary loan
	public function getFiledSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_file_salary_loan WHERE emp_id = '$emp_id' AND (apporveStat = '0' OR apporveStat = '1')";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}


	// for checking file salary loan id if exist
	public function checkExistFileSalaryLoanId($file_salary_loan_id){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_salary_loan WHERE file_salary_loan_id = '$file_salary_loan_id'"));
		return $num_rows;
	}


	// for getting the count of pending file salary loan
	public function getFileSalaryLoanPendingCount(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_salary_loan WHERE apporveStat = '0'"));
		return $num_rows;
	}


	// check if exist nga pero approve na or disapprove
	//public function checkFileSalaryLoanStats($file_salary_loan_id){

	//}


	// for specific person log in
	public function getFileLoanInfoByRefNo($ref_no){
		$connect = $this->connect();
		
		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$select_qry = "SELECT * FROM tb_emp_file_loan WHERE ref_no = '$ref_no'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}

	// for getting filed salary loan where approve stat is 0
	public function getFiledSalaryLoanToApprove(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_file_salary_loan WHERE apporveStat ='0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_query_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_query_emp);
				$row_emp = mysqli_fetch_object($result_emp);

				$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$date_range = $dateFrom . "- " .$dateTo;


				$interest_amount = $row->totalPayment - $row->amountLoan;


				$ref_no = $row->ref_no;

				//echo $ref_no;

				$row_fl = $this->getFileLoanInfoByRefNo($ref_no);
				$loan_type = "Salary Loan";
				if ($row_fl->type == 3){
					$loan_type = "Employee Benifit Program";
				}

				$info = "";
				$info .=  "<b>".$ref_no ."</b>" . "<br/>";
				$info .= $loan_type;

				echo "<tr id='".$row->file_salary_loan_id."'>";
					echo "<td><small>".$emp_name."</small></td>";
					echo "<td><small>".$date_range."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
					echo "<td>".$info."</td>";
					echo "<td><small>".$row->remarks."</small></td>";
					echo "<td><small>";

						//if ($_SESSION["role"] == 1){

						echo "<span style='cursor:pointer;color:#158cba' id='approve_salaryLoan' title='Approve'><span class='glyphicon glyphicon-ok' style='color:#239b56'></span> Approve</span>";
						echo "<span> | </span>";
						echo "<span style='cursor:pointer;color:#158cba' id='dis_approve_salaryLoan' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color: #ff4136 '></span> Disapprove</span>";
						echo "</small>";

						//}

						//else if ($_SESSION["role"] == 3){
						//	echo "No action";
						//}


					echo "</td>";
				echo "</tr>";
				
			}
		}

	}


	// for getting file salary loan info by id
	public function getFileSalaryLoanById($file_salary_loan_id){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);

		$select_qry = "SELECT * FROM tb_file_salary_loan WHERE file_salary_loan_id ='$file_salary_loan_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}

	// for approving of admin and update all info from tb_file_salary_loan
	public function approveFileSalaryLoan($file_salary_loan_id,$approver_id,$deductionType,$deductionDay,$totalMonths,
										$dateFrom,$dateTo,$amountLoan,$totalPayment,$deduction,$apporveStat,$dateApprove){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$approver_id = mysqli_real_escape_string($connect,$approver_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$totalPayment = mysqli_real_escape_string($connect,$totalPayment);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$apporveStat = mysqli_real_escape_string($connect,$apporveStat);
		$dateApprove = mysqli_real_escape_string($connect,$dateApprove);

		$update_qry = "UPDATE tb_file_salary_loan SET approver_id = '$approver_id', deductionType = '$deductionType', deductionDay = '$deductionDay', totalMonths = '$totalMonths',
						dateFrom = '$dateFrom', dateTo = '$dateTo' , amountLoan = '$amountLoan' ,totalPayment = '$totalPayment', deduction = '$deduction', apporveStat = '$apporveStat' , dateApprove = '$dateApprove'
						WHERE file_salary_loan_id = '$file_salary_loan_id'";

		$sql = mysqli_query($connect,$update_qry);

	}


	// for dispprving file salary loan
	public function disapproveFileSalaryLoan($file_salary_loan_id,$approver_id,$approveStat,$dateApprove){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$approver_id = mysqli_real_escape_string($connect,$approver_id);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$dateApprove = mysqli_real_escape_string($connect,$dateApprove);

		$update_qry = "UPDATE tb_file_salary_loan SET approver_id = '$approver_id',  apporveStat = '$approveStat',	dateApprove = '$dateApprove' WHERE file_salary_loan_id = '$file_salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for getting the latest salary loan
	public function fileSalaryLoanLastId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_last_id_qry = "SELECT * FROM tb_file_salary_loan WHERE emp_id = '$emp_id' AND apporveStat = '1' ORDER BY file_salary_loan_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->file_salary_loan_id;
		return $last_id;
	}

	// for getting the latest file salary loan by last id
	public function lastIdFileSalaryLoan(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_file_salary_loan ORDER BY file_salary_loan_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->file_salary_loan_id;
		return $last_id;
	}




	// for accepting
	public function acceptFileSalaryLoan($file_salary_loan_id,$approveStat,$dateAccept){
		$connect = $this->connect();

		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$dateAccept = mysqli_real_escape_string($connect,$dateAccept);

		$update_qry = "UPDATE tb_file_salary_loan SET apporveStat = '$approveStat', dateAccept = '$dateAccept' WHERE file_salary_loan_id = '$file_salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for print salary loan with equal remaining balance at loan amount ibig sabihin bago pa
	public function printFileSalaryLoan($salary_loan_id){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);

		require ("reports/fpdf.php");

		$pdf = new PDF_MC_Table("p");
		$pdf->SetMargins("15","10"); // left top

		$pdf->AddPage();

		$row = $this->getInfoBySalaryLoanId($salary_loan_id);
		$approver_id = $row->approver_id;
		$pre_approver_id = $row->pre_approver_id;


		$select_file_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
		$result_file_emp = mysqli_query($connect,$select_file_emp_qry);
		$row_file_emp = mysqli_fetch_object($result_file_emp);


		$select_approver_qry_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->approver_id'";
		$result_approver_emp = mysqli_query($connect,$select_approver_qry_emp);
		$row_approver_emp = mysqli_fetch_object($result_approver_emp);

		$select_pre_approver_qry_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->pre_approver_id'";
		$result_pre_approver_emp = mysqli_query($connect,$select_pre_approver_qry_emp);
		$row_pre_approver_emp = mysqli_fetch_object($result_pre_approver_emp);


		$ref_no = $row->ref_no;


		$loan_type = "SALARY LOAN";
		if ($ref_no != ""){
			$row_emp_loan = $this->getFileLoanInfoByRefNo($ref_no);
			if ($row_emp_loan->type == 3){
				$loan_type = "EMPLOYEE BENIFIT PROGRAM";

				$program = $row_emp_loan->program;

				$program_str = "";
				if ($program == 1){
					$program_str = "Service Rewards";
				}


				else if ($program == 2){
				   $program_str = "Tulong Pangkabuhayan Program";
				}


				else if ($program == 3){
					$program_str = "Education Assistance Program";
				}

				else if ($program == 4){
					$program_str = "Housing Renovation Program";
				}

				else if ($program == 5){
					$program_str = "Emergency and Medical Assistance Program";
				}

				$loan_type .= "(".$program_str.")";  
			}
		}
		
		



		$pdf->Image("img/logo/lloyds logo.png",15,10,15,15);// margin-left,margin-top,width,height
		$pdf->SetFont("Arial","B","10");
		$pdf->Cell(15);
		$pdf->Cell(80,5,"LLOYDS FINANCING CORPORATION",0,1,"L"); // for margin
		$pdf->Cell(15);
		$pdf->Cell(80,5,"9532 Taguig Street Brgy Valenzuela Makati City",0,1,"L"); // for margin
		$pdf->Cell(15);
		$pdf->Cell(80,5,"(02) 896 9532",0,1,"L");

		$pdf->Cell(80,5,"",0,1,"L"); // for margin
		
		$pdf->Cell(80,5,"FILE ".$loan_type." INFORMATION",0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"REFERENCE NO: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5, $row->ref_no,0,1,"L");

		$pdf->SetFont("Arial","B","10");
		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"EMPLOYEE NAME: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5, $row_file_emp->Lastname . ", " . $row_file_emp->Firstname . " " . $row_file_emp->Middlename ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DEDUCTION TYPE: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5, $row->deductionType,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DEDUCTION DAY: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$deductionDay = "15th of the 30th of the month";
		if ($row->deductionDay != 0){
			$deductionDay = $row->deductionDay;
		}
		$pdf->Cell(36,5,$deductionDay ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"TOTAL MONTHS: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5,$row->totalMonths ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DATE FROM: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5,date_format(date_create($row->dateFrom),"F d, Y") ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DATE TO: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5,date_format(date_create($row->dateTo),"F d, Y") ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"AMOUNT LOAN: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5,"Php " . $this->getMoney($row->amountLoan) ,0,1,"L");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DEDUCTION: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5,"Php " . $this->getMoney($row->deduction) ,0,1,"L");


		if ($pre_approver_id != 0){
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(36,5,"PRE-APPROVE BY: ",0,0,"L");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(36,5, $row_pre_approver_emp->Lastname . ", " . $row_pre_approver_emp->Firstname . " " . $row_pre_approver_emp->Middlename ,0,1,"L");
		}


		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DATE PRE-APPROVED: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);

		$date_approve = "-";
		if ($row->pre_approval_date != "0000-00-00"){
			$date_approve = date_format(date_create($row->pre_approval_date),"F d, Y");
		}

		$pdf->Cell(36,5, $date_approve ,0,1,"L");

		if ($approver_id != 0){


			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(36,5,"APPROVE BY: ",0,0,"L");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(36,5, $row_approver_emp->Lastname . ", " . $row_approver_emp->Firstname . " " . $row_approver_emp->Middlename ,0,1,"L");
		}





		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"DATE APPROVED: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);

		$date_approve = "-";
		if ($row->DateCreated != "0000-00-00"){
			$date_approve = date_format(date_create($row->DateCreated),"F d, Y");
		}




		$pdf->Cell(36,5, $date_approve ,0,1,"L");



		$pdf->Cell(36,5,"" ,0,1,"L"); // for margin

		// payment schedule

		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(30,5,"#",0,0,"L");
		$pdf->Cell(60,5,"PAYMENT DATE",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(60,5,"AMOUNT" ,0,1,"L");



		$first_date = $row->dateFrom;
		$frequency_terms = $row->totalMonths;

		if ($row->deductionType == "Semi-monthly"){
			$frequency_terms = $frequency_terms * 2;
		}

		$year = "";
		$month = "";

		$counter = 0;
		$count = 0;
		do {

			if ($counter == 0){
				$first_date = date_format(date_create($first_date),"m/d/Y");
			}

			else {

				if ($row->deductionType == "Weeks"){
					$first_date = date_format(date_create($first_date),"Y-m-d");

					$first_date = strtotime("+7 day", strtotime($first_date));
					$first_date = date('m/d/Y', $first_date);
				}

				if ($row->deductionType == "Semi-monthly"){
					$first_date = date_format(date_create($first_date),"Y-m-d");

					$year = date_format(date_create($first_date),"Y");
					$month = date_format(date_create($first_date),"m");

					if ($year % 4 == 0 && $month == "02"){
						$first_date = strtotime("+14 day", strtotime($first_date));
					}

					else if ($year % 4 == 1 && $month == "02"){
						$first_date = strtotime("+13 day", strtotime($first_date));
					}
					else {

						$first_date = strtotime("+15 day", strtotime($first_date));
					}


					$first_date = date('m/d/Y', $first_date);

					$day_no = date_format(date_create($first_date),"d");
					$month_no = date_format(date_create($first_date),"m");
					$year_no = date_format(date_create($first_date),"Y");

					$day = 0;
					if ($day_no <= 15){
						$day = 15;
					}

					else if ($day_no <= 30){

						if ($year % 4 == 0 && $month == "02"){
							$day = 29;
						}

						else if ($year % 4 == 1 && $month == "02"){
							$day = 28;
						}

						else {

							$day = 30;
						}
					}

					$first_date = date_format(date_create($month_no."/".$day."/".$year_no),"m/d/Y");
					


				}

				if ($row->deductionType == "Monthly"){
					$first_date = date_format(date_create($first_date),"Y-m-d");

					$first_date = strtotime("+1 month", strtotime($first_date));
					$first_date = date('m/d/Y', $first_date);
				}

			}



			$pdf->Cell(30,5,++$count,0,0,"L");
			$pdf->Cell(60,5,$first_date,0,0,"L");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(60,5, number_format($row->deduction,2),0,1,"L");
			


			$counter++;
		}while($frequency_terms > $counter);



		$pdf->output();


	}



	// for getting salary loan to history
	public function getExistingLoanToTableToFileSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_salary_loan WHERE emp_id = '$emp_id' AND remainingBalance != '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$date_range = date_format(date_create($row->dateFrom),"F d, Y") . " - " .  date_format(date_create($row->dateTo),"F d, Y");

				echo "<tr>";
					echo "<td>Salary Loan/ CA</td>";
					echo "<td>" .$date_range."</td>";
					echo "<td>Php " .$this->getMoney($row->amountLoan)."</td>";
					echo "<td>Php " .$this->getMoney($row->totalPayment)."</td>";
					echo "<td>Php " .$this->getMoney($row->remainingBalance)."</td>";
				echo "</tr>"; 
			}
		}
	}



	public function getSalaryLoanHistory($salary_loan_id){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);

		$select_qry = "SELECT * FROM tb_salary_loan_history WHERE salary_loan_id = '$salary_loan_id' ORDER BY dateCreated ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				
				echo "<tr>";
					echo "<td>".$row->date_payroll."</td>";
					echo "<td>Php ".$this->getMoney($row->deduction)."</td>";
					echo "<td>Php ".$this->getMoney($row->remainingBalance)."</td>";
				echo "</tr>";
			}
		}
	}


	public function insertSalaryLoanHistory($salary_loan_id,$remainingBalance,$deduction,$CutOffPeriod,$date_payroll,$dateCreated){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$CutOffPeriod = mysqli_real_escape_string($connect,$CutOffPeriod);
		$date_payroll = mysqli_real_escape_string($connect,$date_payroll);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_salary_loan_history (salary_loan_id,remainingBalance,deduction,CutOffPeriod,date_payroll,dateCreated) 
					VALUES ('$salary_loan_id','$remainingBalance','$deduction','$CutOffPeriod','$date_payroll','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);
	}



	// for getting the latest file salary loan by last id
	public function lastIdInsertSalaryLoan(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_salary_loan ORDER BY salary_loan_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->salary_loan_id;
		return $last_id;
	}


	public function updateSalaryLoanReferenceNo($salary_loan_id,$ref_no){
		$connect = $this->connect();

		$salary_loan_id = mysqli_real_escape_string($connect,$salary_loan_id);
		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_salary_loan SET ref_no = '$ref_no' WHERE salary_loan_id = '$salary_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}

	public function getMoney($value){

		//$final_value = $value;
		if ($value < 0){
			//echo "Wew";
			$final_value = $value;
		}

		else if ($value < 1 && $value > 0){
			$final_value = $value;
		}

        else if ($value == 0) { // if 0       
            
            $final_value = "0.00";                   
        }


        else if ($value >= 1 && $value < 10) { // for 1 digit
          
            $decimal = "";

            $one = substr($value,0,1);

            if ($this->is_decima($value) == 1) {
                $decimal = substr($value,1);
                $final_value = $one . $decimal;
            }

            else {
                $final_value = $one . ".00";
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
                $final_value = $hundred . $ten . $one . $decimal;
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
                $final_value = $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $thousand . "," . $hundred . $ten . $one . ".00";
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
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
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

        return $final_value;
    }


    // if has decimal
    function is_decima($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }


}



?>