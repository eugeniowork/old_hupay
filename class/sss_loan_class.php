<?php
class SSSLoan extends Connect_db{

	
	public function getInfoBySSSLoanId($sss_loan_id){
		$connect = $this->connect();

		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);

		$select_qry = "SELECT * FROM tb_sss_loan WHERE sss_loan_id='$sss_loan_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}


	// for getting info by emp_id for payroll
	public function getInfoBySSSLoanEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$total_sss_balance = 0;

		$select_qry = "SELECT * FROM tb_sss_loan WHERE emp_id='$emp_id' AND remainingBalance > '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$total_sss_balance += $row->remainingBalance;
			}
		}

		return $total_sss_balance;

	}


	public function getInfoBySSSLoanEmpIdToDashboard($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$total_sss_balance = 0;

		$select_qry = "SELECT * FROM tb_sss_loan WHERE emp_id='$emp_id' AND remainingBalance > '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$total_sss_balance += $row->remainingBalance;

				echo "<tr>";
					echo "<td style='padding: 5px;'>".$row->loan_type."</td>";
					echo "<td style='padding: 5px;'>".number_format($row->remainingBalance,2)."</td>";
				echo "</tr>";
			}
		}

		

	}


	public function getSSSLoanToPayroll($emp_id){
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




			//$cutOff_day = $cut_off_class->getCutOffDay();

		//if ($cutOff_day == "15") {


		$sss_loan_amount = 0;
		//echo $emp_id . "<br/>";
		$select_qry = "SELECT * FROM tb_sss_loan WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				//$row_salaryLoan = $salary_loan_class->getInfoBySalaryLoanEmpId($emp_id); 

				//echo "wew <br/>";
				// 
				// september 15, 2017 , september 30, 2017 , may 15, 2018 , september 30, 2017
				//if ($row->dateFrom <= $date_payroll && $row->dateTo >= $date_payroll) { // july 30, 2017 , august 26, 2017 {}
				
				if ($row->dateFrom <= $date_payroll) {
					
					if ($row->deduction >= $row->remainingBalance){
						$sss_loan_amount += $row->remainingBalance;
					}

					else {
						$sss_loan_amount += $row->deduction;
					}
				}
				//}// end of if

			}
		}

		return $sss_loan_amount;
	}

	


	public function insertSSSLoan($emp_id,$loan_type,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$loan_type = mysqli_real_escape_string($connect,$loan_type);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_sss_loan (emp_id,loan_type,dateFrom,dateTo,amountLoan,deduction,remainingBalance,DateCreated)
												VALUES ('$emp_id','$loan_type','$dateFrom','$dateTo','$amountLoan','$deduction','$remainingBalance','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	public function getSSSLoanInfoToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_sss_loan";
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
					echo "<tr id='".$row->sss_loan_id."'>";
						echo "<td><small>".$emp_name."</small></td>";
						echo "<td><small>".$row->loan_type."</small></td>";
						echo "<td><small>".$date_range."</small></td>";				
						echo "<td><small>Php ".$this->getMoney($row->amountLoan)."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->deduction)."</small></td>";
						echo "<td><small>Php ".$this->getMoney($row->remainingBalance)."</small></td>";
						echo "<td><small>";
							echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_sssLoan' class='action-a'>Edit</a>";
							echo "<span> | </span>";
							echo "<span class='glyphicon glyphicon-adjust' style='color: #239b56 '></span> <a href='#' id='adjust_sssLoan' class='action-a'>Adjustment</a>";
							echo "<span> | </span>";
							echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_sssLoan' class='action-a'>Delete</a>";
						echo "</small></td>";
					echo "</tr>";
				}


			}
		}
	}


	// check if has an exist pag-bigi loan for update
	public function checkExistSSSLoanUpdate($sss_loan_id){
		$connect = $this->connect();
		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE sss_loan_id = '$sss_loan_id'"));
		return $num_rows;
	}


	// check if has an existing loan
	public function existSSSLoan($emp_id,$loan_type){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$loan_type = mysqli_real_escape_string($connect,$loan_type);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE emp_id = '$emp_id' AND loan_type = '$loan_type' AND remainingBalance != '0'"));
		return $num_rows;
	}


	public function existSSSPendingLoan($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
	

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE emp_id = '$emp_id' AND remainingBalance != '0'"));
		return $num_rows;
	}


	// if no changes was made same info
	public function sameSSSLoanInfo($sss_loan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance){
		$connect = $this->connect();

		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE 
												dateFrom = '$dateFrom' AND dateTo = '$dateTo' AND amountLoan = '$amountLoan' AND
												deduction = '$deduction' AND remainingBalance = '$remainingBalance' AND sss_loan_id = '$sss_loan_id'"));
		return $num_rows;
	}


	// for update information
	public function updateSSSLoan($sss_loan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance){
		$connect = $this->connect();

		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$amountLoan = mysqli_real_escape_string($connect,$amountLoan);
		$deduction = mysqli_real_escape_string($connect,$deduction);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);

		$update_qry = "UPDATE tb_sss_loan SET dateFrom = '$dateFrom',dateTo = '$dateTo', amountLoan = '$amountLoan', 
												deduction = '$deduction', remainingBalance = '$remainingBalance' 
												WHERE sss_loan_id = '$sss_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for deleting pagibigLoan
	public function deleteSSSLoan($sss_loan_id){
		$connect = $this->connect();

		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);

		$delete_qry = "DELETE FROM tb_sss_loan WHERE sss_loan_id = '$sss_loan_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}

	// for checking if the range of date is sakop and ung remain balance is not 0
	public function existPendingSSSLoan($emp_id){
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

       
		//$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE dateFrom <= '$date_payroll' AND dateTo >= '$date_payroll' AND emp_id = '$emp_id' AND remainingBalance != '0'"));
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_sss_loan WHERE dateFrom <= '$date_payroll' AND emp_id = '$emp_id' AND remainingBalance != '0'"));
		//echo $num_rows . " " . $date_payroll;
		return $num_rows;

		//echo $num_rows;
	}



	// for deducting in pag-ibig loan in payroll when apporve
	public function deductionSSSLoan(){
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


		$select_qry = "SELECT * FROM tb_sss_loan WHERE remainingBalance != '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				if ($row->deduction >= $row->remainingBalance){
					$sss_loan_amount += $row->remainingBalance;
				}

				else {
					$sss_loan_amount += $row->deduction;
				}

				/*$select_payroll_info_qry = "SELECT * FROM tb_payroll_info WHERE emp_id = '$row->emp_id' ORDER BY DateCreated DESC LIMIT 1";
				$result_payroll_info = mysqli_query($connect,$select_payroll_info_qry);
				$row_payroll_info = mysqli_fetch_object($result_payroll_info);*/

				//if ($row->dateFrom <= $date_payroll && $row->dateTo >= $date_payroll) { // july 30, 2017 , august 26, 2017 {}
					//$remainingBalance = $row->remainingBalance - $row_payroll_info->sssLoan;
					$remainingBalance = $row->remainingBalance - $sss_loan_amount;

					// so update qry for new remaining balance
					$update_qry = "UPDATE tb_sss_loan SET remainingBalance = '$remainingBalance' WHERE sss_loan_id = '$row->sss_loan_id'";
					$sql = mysqli_query($connect,$update_qry);

				//}
			}
		}
	}


	// for pag ibig loan adjustment update only the remaining balance
	public function updateOnlyRemainingBalance($sss_loan_id,$remainingBalance){
		$connect = $this->connect();

		$sss_loan_id = mysqli_real_escape_string($connect,$sss_loan_id);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);

		$update_qry = "UPDATE tb_sss_loan SET remainingBalance = '$remainingBalance' WHERE sss_loan_id = '$sss_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	// for getting all pagibig loan to table history 
	public function getSSSLoanInfoHistoryToTable($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_sss_loan WHERE emp_id = '$emp_id' ORDER BY DateCreated DESC";
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

				$status = "Current";
				if ($row->remainingBalance == 0){
					$status = "Finish";
				}

					echo "<tr id='".$row->sss_loan_id."'>";
						echo "<td>".$row->loan_type."</td>";
						echo "<td>".$date_range."</td>";
						echo "<td>Php ".$this->getMoney($row->amountLoan)."</td>";
						echo "<td>Php ".$this->getMoney($row->deduction)."</td>";
						echo "<td>Php ".$this->getMoney($row->remainingBalance)."</td>";	
						echo "<td>".$status."</td>";						
					echo "</tr>";
				


			}
		}
	}



	public function getMoney($value){

        if ($value == 0) { // if 0       
            
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