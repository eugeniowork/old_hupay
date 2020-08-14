<?php
class Simkimban extends Connect_db{

	
	public function getInfoBySimkimbaId($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		$select_qry = "SELECT * FROM tb_simkimban WHERE simkimban_id='$simkimban_id'";
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


	public function getExistingSimkimbanToTableToFileSalaryLoan($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_simkimban WHERE emp_id = '$emp_id' AND remainingBalance != '0' AND status = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$date_range = date_format(date_create($row->dateFrom),"F d, Y") . " - " .  date_format(date_create($row->dateTo),"F d, Y");

				echo "<tr>";
					echo "<td>SIMKIMBAN</td>";
					echo "<td>" .$date_range."</td>";
					echo "<td>Php " .$this->getMoney($row->amountLoan)."</td>";
					echo "<td>Php " .$this->getMoney($row->amountLoan)."</td>";
					echo "<td>Php " .$this->getMoney($row->remainingBalance)."</td>";
				echo "</tr>"; 
			}
		}
	}

	// for getting own salary loan getOwnSalaryLoanToTable
	public function getOwnSimkimbanHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_simkimban WHERE emp_id = '$emp_id' AND status = '1' ORDER BY DateCreated DESC";
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
						echo "<td>".$row->Items."</td>";
						echo "<td>Php ".$this->getMoney($row->amountLoan)."</td>";
						echo "<td>Php ".$this->getMoney($row->deduction)."</td>";
						echo "<td>Php ".$this->getMoney($row->remainingBalance)."</td>";
						echo "<td>".$status."</td>";
					echo "</tr>";
				


			}
		}
	}


	// for getting infor by emp_id to payroll
	public function getInfoBySimkimbanToPayrollEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_simkimban WHERE emp_id='$emp_id' AND status = '1'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

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


	// for getting info by emp_id for payroll
	public function getInfoBySimkimbanEmpId($emp_id){
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

					$date_from = date_format(date_create($row_cutoff->dateFrom . ", " .$year),'Y-m-d');
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




		$simkimban_amount = 0;
		$select_qry = "SELECT * FROM tb_simkimban WHERE emp_id='$emp_id' AND status = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){



				// september 15, 2017 , september 30, 2017 , may 15, 2018 , september 30, 2017
				if ($row->dateFrom <= $date_payroll && $row->dateTo >= $date_payroll) { // july 30, 2017 , august 26, 2017 {}

					if ($row->deductionType == "Monthly" && $row->deductionDay == $this->getCutOffDay()){
						
						if ($simkimban_amount == 0){
							
							if ($row->deduction >= $row->remainingBalance){
								$simkimban_amount = $row->remainingBalance;
							}

							else {
								$simkimban_amount = $row->deduction;
							}
						}
						else {
				
							if ($row->deduction >= $row->remainingBalance){
								$simkimban_amount = $row->remainingBalance + $simkimban_amount;
							}
							else {
								$simkimban_amount = $row->deduction + $simkimban_amount;
							}

						}
					}

					if ($row->deductionType == "Semi-monthly"){
						if ($simkimban_amount == 0){
							
							if ($row->deduction >= $row->remainingBalance){
								$simkimban_amount = $row->remainingBalance;
							}

							else {
								$simkimban_amount = $row->deduction;
							}
						}
						else {
				
							if ($row->deduction >= $row->remainingBalance){
								$simkimban_amount = $row->remainingBalance + $simkimban_amount;
							}
							else {
								$simkimban_amount = $row->deduction + $simkimban_amount;
							}

						}
					}
				}
			} // end of while
		}

		return $simkimban_amount;

	}


	// for getting all simkimban balance of employee
	public function getAllRemainingBalanceSimkimban($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$simkimban_amount = "";
		$select_qry = "SELECT * FROM tb_simkimban WHERE emp_id='$emp_id' AND status = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($simkimban_amount == ""){
					$simkimban_amount = $row->remainingBalance;
				}

				else {
					$simkimban_amount = $simkimban_amount + $row->remainingBalance;
				}

			}

		}
		return $simkimban_amount;

	}



	public function insertSimkimban($emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$item = mysqli_real_escape_string($connect,$item);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_simkimban (simkimban_id,emp_id,deductionType,deductionDay,totalMonths,dateFrom,dateTo,Items,amountLoan,deduction,remainingBalance,DateCreated)
												VALUES ('','$emp_id','$deductionType','$deductionDay','$totalMonths','$dateFrom','$dateTo','$item','$amountLoan','$deduction','$remainingBalance','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	public function insertSimkimbanManual($emp_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance,$status,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$item = mysqli_real_escape_string($connect,$item);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_simkimban (simkimban_id,emp_id,deductionType,deductionDay,totalMonths,dateFrom,dateTo,Items,amountLoan,deduction,remainingBalance,status,DateCreated)
												VALUES ('','$emp_id','$deductionType','$deductionDay','$totalMonths','$dateFrom','$dateTo','$item','$amountLoan','$deduction','$remainingBalance','1','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	public function getSimkimbanInfoToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_simkimban WHERE status = '1' AND remainingBalance > '0'";
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

				if ($row->remainingBalance != 0) {
					echo "<tr id='".$row->simkimban_id."'>";
						echo "<td><small>".$emp_name."</small></td>";
						echo "<td><small>".$date_range."</small></td>";
						echo "<td><small>".$row->Items."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->remainingBalance)."</small></td>";
						echo "<td><small>";
							echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_simkimban' class='action-a'>Edit</a>";
							echo "<span> | </span>";
							echo "<span class='glyphicon glyphicon-adjust' style='color: #239b56 '></span> <a href='#' id='adjust_simkimban' class='action-a'>Adjustment</a>";
							echo "<span> | </span>";
							//echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_simkimban' class='action-a'>Delete</a>";
							//echo "<span> | </span>";
							echo "<span class='glyphicon glyphicon-eye-open' style='color:#3498db'></span> <a href='#' id='view_simkimban_history' class='action-a'>View</a>";
							echo "<span> | </span>";
							echo "<a href='#' id='printSimkimbanLoan' class='action-a'><span class='glyphicon glyphicon-print' style='color:#515a5a'></span> Print</a>";
						echo "</small></td>";
					echo "</tr>";
				}


			}
		}
	}


	// for getting all simkimban finish to history
	public function getAllSIMKIMBANHistory(){
		$connect = $this->connect();



		$select_qry = "SELECT * FROM tb_simkimban WHERE remainingBalance = '0' AND status = '1'";
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
					echo "<td><small>".$row->Items."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
					echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
				echo "</tr>";

			}
		}



	}


	// check if has an exist pag-bigi loan for update
	public function checkExistSimkimbanUpdate($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_simkimban WHERE simkimban_id = '$simkimban_id'"));
		return $num_rows;
	}


	// check if has an existing loan
	public function existSalaryLoan($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_salary_loan WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	// if no changes was made same info
	public function sameSimkimbanInfo($simkimban_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$item = mysqli_real_escape_string($connect,$item);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_simkimban WHERE 
												deductionType = '$deductionType' AND deductionDay = '$deductionDay' AND totalMonths = '$totalMonths' AND dateFrom = '$dateFrom' AND dateTo = '$dateTo' AND Items = '$item' AND amountLoan = '$amountLoan' AND
												deduction = '$deduction' AND remainingBalance = '$remainingBalance' AND simkimban_id = '$simkimban_id'"));
		return $num_rows;
	}


	// for update information
	public function updateSimkimban($simkimban_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$deductionType = mysqli_real_escape_string($connect,$deductionType);
		$deductionDay = mysqli_real_escape_string($connect,$deductionDay);
		$totalMonths = mysqli_real_escape_string($connect,$totalMonths);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$item = mysqli_real_escape_string($connect,$item);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);

		$update_qry = "UPDATE tb_simkimban SET deductionType='$deductionType', deductionDay = '$deductionDay', totalMonths = '$totalMonths', dateFrom = '$dateFrom',dateTo = '$dateTo', Items = '$item', amountLoan = '$amountLoan', 
												deduction = '$deduction', remainingBalance = '$remainingBalance' 
												WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for deleting pagibigLoan
	public function deleteSimkimban($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		$delete_qry = "DELETE FROM tb_simkimban WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}


	// for checking if has pending simkimban loan to db
	public function existSimkimbanLoan($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_simkimban WHERE emp_id = '$emp_id' AND remainingBalance != '0' AND status = '1'"));
		return $num_rows;
	}



	// for checking if the range of date is sakop and ung remain balance is not 0
	public function existPendingSimkimban($emp_id){
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

				$date_from = date_format(date_create($row_cutoff->dateFrom . ", " .$year),'Y-m-d');
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

       
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_simkimban WHERE dateFrom <= '$date_payroll' AND dateTo >= '$date_payroll' AND emp_id = '$emp_id' AND remainingBalance != '0' AND status = '1'"));
		return $num_rows;
	}


	// for deducting in pag-ibig loan in payroll when apporve
	public function deductSimkimban($cutOffPeriod){
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

					$date_from = date_format(date_create($row_cutoff->dateFrom . ", " .$year),'Y-m-d');
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

		$remainingBalance = 0;
		$select_qry = "SELECT * FROM tb_simkimban WHERE remainingBalance != '0' AND status = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				if ($row->dateFrom <= $date_payroll && $row->dateTo >= $date_payroll) {

					if ($row->deductionType == "Monthly" && $row->deductionDay == $this->getCutOffDay()){
						
						$remainingBalance = $row->remainingBalance - $row->deduction;


						if ($remainingBalance <= 0){
							$remainingBalance = 0;
						}


						// so update qry for new remaining balance
						$update_qry = "UPDATE tb_simkimban SET remainingBalance = '$remainingBalance' WHERE simkimban_id = '$row->simkimban_id'";
						$sql = mysqli_query($connect,$update_qry);

						$this->insertSimkimbanLoanHistory($row->simkimban_id,$remainingBalance,$row->deduction,$cutOffPeriod,$date_payroll,$current_date_time);
					}

					if ($row->deductionType == "Semi-monthly"){
						$remainingBalance = $row->remainingBalance - $row->deduction;

						if ($remainingBalance <= 0){
							$remainingBalance = 0;
						}

						// so update qry for new remaining balance
						$update_qry = "UPDATE tb_simkimban SET remainingBalance = '$remainingBalance' WHERE simkimban_id = '$row->simkimban_id'";
						$sql = mysqli_query($connect,$update_qry);

						$this->insertSimkimbanLoanHistory($row->simkimban_id,$remainingBalance,$row->deduction,$cutOffPeriod,$date_payroll,$current_date_time);
					}
					
				}

			}
		}
	}

	// for pag ibig loan adjustment update only the remaining balance
	public function updateOnlyRemainingBalance($simkimban_id,$remainingBalance){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);

		$update_qry = "UPDATE tb_simkimban SET remainingBalance = '$remainingBalance' WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function getSimkimbanLoanHistory($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		$select_qry = "SELECT * FROM tb_simkimban_loan_history WHERE simkimban_id = '$simkimban_id' ORDER BY dateCreated ASC";
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


	public function insertSimkimbanLoanHistory($simkimban_id,$remainingBalance,$deduction,$CutOffPeriod,$date_payroll,$dateCreated){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$CutOffPeriod = mysqli_real_escape_string($connect,$CutOffPeriod);
		$date_payroll = mysqli_real_escape_string($connect,$date_payroll);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_simkimban_loan_history (simkimban_id,remainingBalance,deduction,CutOffPeriod,date_payroll,dateCreated) 
					VALUES ('$simkimban_id','$remainingBalance','$deduction','$CutOffPeriod','$date_payroll','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);
	}


	// for getting the latest salary loan
	public function simkimbanLoanLastId(){
		$connect = $this->connect();


		$select_last_id_qry = "SELECT * FROM tb_simkimban ORDER BY simkimban_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->simkimban_id;
		return $last_id;
	}


	public function updateRefNo($simkimban_id,$ref_no){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_simkimban SET ref_no = '$ref_no' WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function getFileSimkimbanLoan(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_simkimban WHERE status = '0'";
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

				// for info
				$ref_no = $row->ref_no;


				if ($row->remainingBalance != 0) {
					echo "<tr id='".$row->simkimban_id."'>";
						echo "<td><small>".$emp_name."</small></td>";
						echo "<td><small>".$date_range."</small></td>";
						echo "<td><small>".$row->Items."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
						echo "<td>".$ref_no."</td>";
						//echo "<td><small>".$row->remarks."</td>";
						echo "<td><small>";
							echo "<span style='cursor:pointer;color:#158cba' id='approve_simkimbanLoan' title='Approve'><span class='glyphicon glyphicon-ok' style='color:#239b56'></span> Approve</span>";
							echo "<span> | </span>";
							echo "<span style='cursor:pointer;color:#158cba' id='dis_approve_simkimbanLoan' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color: #ff4136 '></span> Disapprove</span>";
							echo "</small>";
						echo "</small></td>";
					echo "</tr>";
				}


			}
		}
	}


	public function approveSimkimbanLoan($simkimban_id,$date_approved ,$approver_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);
		$date_approved  = mysqli_real_escape_string($connect,$date_approved );
		$approver_id = mysqli_real_escape_string($connect,$approver_id);

		$update_qry = "UPDATE tb_simkimban SET status = '1', date_approved = '$date_approved' , approver_id = '$approver_id' WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function disApproveSimkimbanLoan($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		$update_qry = "UPDATE tb_simkimban SET status = '2' WHERE simkimban_id = '$simkimban_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	// for print salary loan with equal remaining balance at loan amount ibig sabihin bago pa
	public function printFileSimkimbanLoan($simkimban_id){
		$connect = $this->connect();

		$simkimban_id = mysqli_real_escape_string($connect,$simkimban_id);

		require ("reports/fpdf.php");

		$pdf = new PDF_MC_Table("p");
		$pdf->SetMargins("15","10"); // left top

		$pdf->AddPage();

		$row = $this->getInfoBySimkimbaId($simkimban_id);
		$approver_id = $row->approver_id;


		$select_file_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
		$result_file_emp = mysqli_query($connect,$select_file_emp_qry);
		$row_file_emp = mysqli_fetch_object($result_file_emp);


		$select_approver_qry_emp = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->approver_id'";
		$result_approver_emp = mysqli_query($connect,$select_approver_qry_emp);
		$row_approver_emp = mysqli_fetch_object($result_approver_emp);


		$pdf->Image("img/logo/lloyds logo.png",15,10,15,15);// margin-left,margin-top,width,height
		$pdf->SetFont("Arial","B","10");
		$pdf->Cell(15);
		$pdf->Cell(80,5,"LLOYDS FINANCING CORPORATION",0,1,"L"); // for margin
		$pdf->Cell(15);
		$pdf->Cell(80,5,"9532 Taguig Street Brgy Valenzuela Makati City",0,1,"L"); // for margin
		$pdf->Cell(15);
		$pdf->Cell(80,5,"(02) 896 9532",0,1,"L");

		$pdf->Cell(80,5,"",0,1,"L"); // for margin
		
		$pdf->Cell(80,5,"FILE SIMKIMBAN LOAN INFORMATION",0,1,"L");

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

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(36,5,"PRE APPROVE BY: ",0,0,"L");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(36,5, "Patrick Garcia" ,0,1,"L");

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
		if ($row->date_approved != "0000-00-00"){
			$date_approve = date_format(date_create($row->date_approved),"F d, Y");
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

					$first_date = strtotime("+15 day", strtotime($first_date));
					$first_date = date('m/d/Y', $first_date);

					$day_no = date_format(date_create($first_date),"d");
					$month_no = date_format(date_create($first_date),"m");
					$year_no = date_format(date_create($first_date),"Y");

					$day = 0;
					if ($day_no <= 15){
						$day = 15;
					}

					else if ($day_no <= 30){
						$day = 30;
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

	public function getMoney($value){

		if ($value < 0){
			$final_value = $value;
		}

		else if ($value > 0 && $value < 1){
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