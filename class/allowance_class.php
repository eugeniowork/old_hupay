<?php

class Allowance extends Connect_db{


	// for view emp profile info
	public function allowanceInfo($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		
		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)) {
			while($row = mysqli_fetch_object($result)){

				// for allowance typr
				echo '<div class="col-xs-12 col-sm-8 content-view-emp">';
	 				echo '<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Allowance Type:</b> ' . $row->AllowanceType;;
				echo '</div>';

				// for value
				echo '<div class="col-xs-12 col-sm-4 content-view-emp">';
	 				echo '<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Value:</b> Php ' . $row->AllowanceValue;
				echo '</div>';
			}
		}


	}


	// for checking if there is already existing allowance for this employee
	public function existAllowance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for same allowance type info
	public function sameAllowanceAllowanceValues($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$allowance_values = ""; // for name values
		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($allowance_values == "") {
					$allowance_values = $row ->AllowanceType;
				}
				else {
					$allowance_values = $allowance_values . "#" . $row ->AllowanceType;
				}
			}
		}
		return $allowance_values;
	}

	// for same allowance value info
	public function sameAllowanceValueValues($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$value_values = ""; // for name values
		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($value_values == "") {
					$value_values = $row ->AllowanceValue;
				}
				else {
					$value_values = $value_values . "#" . $row ->AllowanceValue;
				}
			}
		}
		return $value_values;
	}


	// for deleting allowance
	public function deleteAllowance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$delete_qry = "DELETE FROM tb_emp_allowance WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}

	// for adding dependent
	public function addAllowance($emp_id,$allowance,$value,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$allowance = mysqli_real_escape_string($connect,$allowance);
		$value = mysqli_real_escape_string($connect,$value);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_emp_allowance (allowance_id,emp_id,AllowanceType,AllowanceValue,DateCreated) VALUES ('','$emp_id','$allowance','$value','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}



	// for getting information by emp_id
	public function getAllAllowanceInfo($emp_id){
		$connect = $this->connect();

		$counter = 0;

		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){
				$counter++;
				echo '<div id="allowance_'.$counter.'">';
					echo  '<div class="col-sm-6">';
						echo '<label class="control-label">Allowance Type &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="'.$row->AllowanceType.'" class="form-control" id="txt_only" name="allowanceType_'.$counter.'" placeholder="Enter Allowance Type"/ required="required">';
					echo '</div>'; 
					echo '<div class="col-sm-5">';
						echo '<label class="control-label">Value &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="'.$row->AllowanceValue.'" class="form-control" id="number_only" name="allowanceValue_'.$counter.'" placeholder="Enter Allowance Value" required="required"/>';
					echo '</div>';
					echo '<div class="col-sm-1 remove-without-dependent">';
						echo '<a class="btn btn-danger btn-sm" title="Remove" id="remove_allowance">&times;</a>';
					echo '</div>';
				echo '</div>';

				
			}
		}
	} // end of function


	// for getting allowance info by rows
	public function getAllowanceInfoByEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$allowance = 0;

		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){	
							
					if ($allowance == 0){
						$allowance = $row->AllowanceValue;		
					}
					else {
						$allowance = $allowance + $row->AllowanceValue;
					}
				}
		}


		//$allowance = ($allowance / 13);

		/*date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		date_sub($date, date_interval_create_from_date_string('15 hours'));

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

		$dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';


	    $count = 0;
	    while( $from <= $last) {
    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);    
	    }


	    $count = $count- 1;
		    
		    $weekdays = array();

		    $counter = 0;

		    $weekdays_count = 0;
		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'l');

		    	if ($day != "Sunday"){
		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter];    		
		    		$weekdays_count++; // for echo condition
  	
		    	}

		    	//echo $dates[$counter];	    	
		    	$counter++;
		    	
		    }while($counter <= $count);*/

		//return ($allowance * $weekdays_count);
		return $allowance;

	}



	// for getting allowance info by rows
	public function getAllowanceInfoToPayslip($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$allowance = 0;

		$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){	
							
					if ($allowance == ""){
						$allowance = $row->AllowanceValue;		
					}
					else {
						$allowance = $allowance + $row->AllowanceValue;
					}
				}
		}

		return $allowance;

	}


	// for allowanceinfor to profile
	public function getAllowanceInfoToProfile($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);



		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'"));

		if ($num_rows != 0) {

			$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
			if ($result = mysqli_query($connect,$select_qry)) {
				while($row = mysqli_fetch_object($result)){


					echo '<div class="col-sm-3">
					 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-user" style="color: #196f3d "></span>&nbsp;<b>Allowance Type:</b></label>
					 	 		<div style="margin-left:15px;"><b>'.$row->AllowanceType.'</b></div>
				 	 		</div>
				 	 		<div class="col-sm-3">
					 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-calendar" style="color: #196f3d "></span>&nbsp;<b>Amount:</b></label>
				 	 			<div style="margin-left:15px;"><b> Php '.$this->getMoney($row->AllowanceValue).'</b></div>
				 	 		</div>';



				}
			}
		} // end of if

		// end of else
		else {
			echo '<div class="form-group">
					<div class="col-sm-12">
						<b><center>There is no allowance</center></b>
					</div>
				  </div>';
		}
	}



	// for getting the total allowance
	public function getTotalAllowance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		//echo $emp_id;

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'"));

		$total_allowance = 0;
		$allowance = 0;
		if ($num_rows != 0) {

			$select_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
			if ($result = mysqli_query($connect,$select_qry)) {
				while($row = mysqli_fetch_object($result)){

					//echo "wew";
					if ($total_allowance == 0){
						$total_allowance = $row->AllowanceValue;
					}

					else {
						$total_allowance = ($total_allowance) + ($row->AllowanceValue);
					}

					//echo $total_allowance . "<br/>";

				}


			}

		}

	 return $total_allowance;



	}



	// for inserting to payslip allowance
	public function insertPayslipAllowance($CutOffPeriod,$date_created){
		$connect = $this->connect();

		$CutOffPeriod = mysqli_real_escape_string($connect,$CutOffPeriod);
		$date_created = mysqli_real_escape_string($connect,$date_created);

		$select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$CutOffPeriod'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'"));

				if ($num_rows != 0){
					$select_pay_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row->emp_id'";
					
					if ($result_pay = mysqli_query($connect,$select_pay_qry)){
						while($row_pay = mysqli_fetch_object($result_pay)){

							$allowance = $row_pay->AllowanceValue / 2;

							$insert_qry = "INSERT INTO tb_payslip_allowance (payroll_id,CutOffPeriod,emp_id,allowanceType,allowanceValue,date_created) 
							VALUES ('$row->payroll_id','$CutOffPeriod','$row->emp_id','$row_pay->AllowanceType','$allowance','$date_created')";
							$sql = mysqli_query($connect,$insert_qry);

							//echo $
						}
					}
				}
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