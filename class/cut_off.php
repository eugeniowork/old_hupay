<?php

class CutOff extends Connect_db{

	public function getCutOffPeriod(){
		$connect = $this->connect();
		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');


		$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



				$date_from = date_format(date_create($row->dateFrom),'Y-m-d');
				if (date_format(date_create($row->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$year = date("Y") - 1;
					$date_from = $year . "-" .date_format(date_create($row->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

				}
				$date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row->dateTo),'Y-m-d');
				//echo $date_to;
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";


				$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				//echo $minus_five_day;
				//echo $date_from . " wew";
				//echo $date_to . "<br/>";


				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					//echo $minus_five_day;
					//echo "wew";
					//echo $date_from . "<br/>";
					//echo $date_to . "<br/>";
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}
		//echo "wew";
	echo date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y') ;
	} // end of function
	

	public function getCutOffPeriodLatest(){
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
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}

	return date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');
	} // end of function


	public function getDatePayroll(){
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
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}

		return $date_payroll;

	//echo date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y') ;
	} // end of function


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


	public function getAllCutOffPeriod(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$date_from = date_format(date_create($row->dateFrom),'F d');
				$date_to = date_format(date_create($row->dateTo),'F d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";

				echo "<option value='".$date_from . " - " . $date_to."'>" . $date_from . " - " . $date_to ."</option>";

				

			}

		}
	}


	public function checkExistCutOffPeriod($cutOffPeriod){
		$connect = $this->connect();

		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

		$has_error = 1;
		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$date_from = date_format(date_create($row->dateFrom),'F d');
				$date_to = date_format(date_create($row->dateTo),'F d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";

				$cut_off_period_db = $date_from . " - " . $date_to;

				if ($cutOffPeriod == $cut_off_period_db){
					$has_error = 0;
				}

			
			}

		}

		return $has_error;

	}

	// get information by cutt off period
	// for generating attendance to be inputed by payroll admin
	public function generateCutOffAttendance(){
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
					//$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		$dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
	    }


	    $count = $count- 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
	    $name_count = 0;
	    do {
			 $date_create = date_create($dates[$counter]);
			 $attendance_date = date_format($date_create, 'M d, Y');

			 $day = date_format($date_create, 'l');

	    	if ($day != "Saturday" && $day != "Sunday"){
	    		$name_count++;
	    	
		    	echo "<table style='background-color:#d6eaf8' border='1'>";
		    		echo "<tbody>";
		    			echo "<tr>";
		    				echo "<td style='padding:5px;' width='15%'><input type='text' class='form-control' id='input_payroll' name='attendance_date".$name_count."' value='".$attendance_date."'</td>";
		    				echo "<td style='padding:5px;' width='8%'><input type='text' title='hour' id='number_only' name='time_in_hour_attendance".$name_count ."' class='form-control' placeholder='Hour'/></td>";
		    				echo "<td style='padding:5px;' width='8%'><input type='text' title='min' id='number_only' name='time_in_min_attendance".$name_count ."' class='form-control' placeholder='Min'/></td>";
		    				echo "<td style='padding:1px;' width='8%'>";
		    					echo "<select class='' name='time_in_period".$name_count."'>
		    							<option value=''></option>
		    							<option value='AM'>AM</option>
		    							<option value='PM'>PM</option>
	    							</select>";
		    				echo "</td>";
		    				echo "<td style='padding:5px;' width='8%'><input type='text' title='hour' id='number_only' name='time_out_hour_attendance".$name_count ."' class='form-control' placeholder='Hour'/></td>";
		    				echo "<td style='padding:5px;' width='8%'><input type='text' title='min' id='number_only' name='time_out_min_attendance".$name_count ."' class='form-control' placeholder='Min'/></td>";
		    				echo "<td style='padding:1px;' width='8%'>";
		    					echo "<select class='' name='time_out_period".$name_count."'>
		    							<option value=''></option>
		    							<option value='AM'>AM</option>
		    							<option value='PM'>PM</option>
	    							</select>";
		    				echo "</td>";
		    			echo "</tr>";
		    		echo "</tbody>";
		    	echo "</table>";
	    	}

	    	/*echo '<div class="col-sm-3">';
	    		echo '<b>' . $attendance_date . " :</b>";
	    	echo "</div>";
	    	*/

	    	 //$attendance_date . "<br/>";

	    	//echo $dates[$counter];
	    	
	    	$counter++;
	    	

	    }while($counter <= $count);

		
	}


	// for getting cut off attendance date count
	public function getCutOffAttendanceDateCount(){
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
					//$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		$dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
	    }


	    $count = $count- 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
	    $name_count = 0;
	    do {
			 $date_create = date_create($dates[$counter]);
			 $attendance_date = date_format($date_create, 'F d, Y');

			 $day = date_format($date_create, 'l');

	    	if ($day != "Saturday" && $day != "Sunday"){
	    		$name_count++;    			    	
	    	}

	    	/*echo '<div class="col-sm-3">';
	    		echo '<b>' . $attendance_date . " :</b>";
	    	echo "</div>";
	    	*/

	    	 //$attendance_date . "<br/>";

	    	//echo $dates[$counter];
	    	
	    	$counter++;
	    	

	    }while($counter <= $count);

	    return $name_count;
	}


	// for getting cut off attendance date count
	public function getCutOffAttendanceDateCountToRunningBalance($day_from,$day_to){
		$connect = $this->connect();

		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

		//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
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


				//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
				$minus_five_day = date("Y-m-d");

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					//$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		$dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
	    }


	    $count = $count- 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
	    $name_count = 0;
	    do {
			 $date_create = date_create($dates[$counter]);
			 $attendance_date = date_format($date_create, 'F d, Y');

			 $day = date_format($date_create, 'w');

	    	if ($day >= $day_from && $day <= $day_to){
	    		$name_count++;    			    	
	    	}

	    	/*echo '<div class="col-sm-3">';
	    		echo '<b>' . $attendance_date . " :</b>";
	    	echo "</div>";
	    	*/

	    	 //$attendance_date . "<br/>";

	    	//echo $dates[$counter];
	    	
	    	$counter++;
	    	

	    }while($counter <= $count);

	    return $name_count;
	}


	// for getting cut off attendance date count
	public function getCutOffAttendanceDateCountToPayroll($day_from,$day_to){
		$connect = $this->connect();

		$day_from = mysqli_real_escape_string($connect,$day_from);
		$day_to = mysqli_real_escape_string($connect,$day_to);

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

		//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
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
				//$minus_five_day = date("Y-m-d");

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					//$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		//echo $final_date_from . " " . $final_date_to . "<br/>";

		$dates = array();
	    $from = strtotime($final_date_from);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
	    }


	    $count = $count;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
	    $name_count = 0;
	    do {
			 $date_create = date_create($dates[$counter]);
			 $attendance_date = date_format($date_create, 'F d, Y');

			 $day = date_format($date_create, 'w');

			 //echo $day . "<br/>";

	    	if ($day >= $day_from && $day <= $day_to){
	    		$name_count++;    			    	
	    	}

	    	/*echo '<div class="col-sm-3">';
	    		echo '<b>' . $attendance_date . " :</b>";
	    	echo "</div>";
	    	*/

	    	 //$attendance_date . "<br/>";

	    	//echo $dates[$counter];
	    	
	    	$counter++;
	    	

	    }while($counter < $count);

	    return $name_count;
	}



	// for getting ilang days meron sa cut off
	public function getDaysAffectIncrease($date_increase){
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
					//$date_payroll = date_format(date_create($row->datePayroll),'d');
				}


			}

		}

		$dates = array();
	    $from = strtotime($date_increase);
	    $last = strtotime($final_date_to);
	    $output_format = 'Y-m-d';
	    $step = '+1 day';

	    $count = 0;
	    while( $from <= $last ) {

    		$count++;
	        $dates[] = date($output_format, $from);
	        $from = strtotime($step, $from);
	       
	    }


	    $count = $count- 1;
	    
	    $weekdays = array();

	    $counter = 0;

	    $weekdays_count = 0;
	    $name_count = 0;
	    do {
			 $date_create = date_create($dates[$counter]);
			 $attendance_date = date_format($date_create, 'F d, Y');

			 $day = date_format($date_create, 'l');

	    	if ($day != "Saturday" && $day != "Sunday"){
	    		$name_count++;    			    	
	    	}

	    	/*echo '<div class="col-sm-3">';
	    		echo '<b>' . $attendance_date . " :</b>";
	    	echo "</div>";
	    	*/

	    	 //$attendance_date . "<br/>";

	    	//echo $dates[$counter];
	    	
	    	$counter++;
	    	

	    }while($counter <= $count);

	    return $name_count;
	}


	public function checkIfHiredWithinCutOff($date_hired){
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
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}

		$status = 0;

		if ($date_hired >= $final_date_from && $date_hired <= $final_date_to){
			$status = 1;
		}


		return $status;

		//echo $final_date_from . " " . $final_date_to;

	//return date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');
	} // end of function

}


?>