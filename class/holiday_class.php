<?php

class Holiday extends Connect_db{


	// for getting holiday information
	public function getHolidayInfoByRow($holiday_id){
		$connect = $this->connect();
		
		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);

		$select_qry = "SELECT * FROM tb_holiday WHERE holiday_id = '$holiday_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}


	// for getting the info to table
	public function getHolidayToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				

				echo "<tr id=".$row->holiday_id.">";
					echo "<td>".$row->holiday_date."</td>";
					echo "<td id='readmoreValue'>".$row->holiday_value."</td>";
					echo "<td>".$row->holiday_type."</td>";
					echo "<td>";
							echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_holiday' class='action-a'>Edit</a>";
							echo "<span> | </span>";
							echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_holiday' class='action-a'>Delete</a>";
					echo "</td>";
				echo "</tr>";
			
			}

		}

	} // end of function


	// for exist holiday id
	public function existHolidayId($holiday_id){
		$connect = $this->connect();
		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_id = '$holiday_id'"));
		return $num_rows;

	}


	// for same info 
	public function sameHolidayInfo($holiday_id,$month,$day,$holidayName,$holidayType){
		$connect = $this->connect();

		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);
		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$holidayName = mysqli_real_escape_string($connect,$holidayName);
		$holidayType = mysqli_real_escape_string($connect,$holidayType);


		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday_date' AND
														 holiday_value = '$holidayName' AND holiday_type = '$holidayType' AND 
														 holiday_id = '$holiday_id'"));
		return $num_rows;

	}


	// for update holiday info
	public function updateHolidayInfo($holiday_id,$month,$day,$holidayName,$holidayType){
		$connect = $this->connect();

		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);
		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$holidayName = mysqli_real_escape_string($connect,$holidayName);
		$holidayType = mysqli_real_escape_string($connect,$holidayType);

		$insert_qry = "UPDATE tb_holiday SET holiday_date='$holiday_date', holiday_value = '$holidayName' , holiday_type = '$holidayType' 
													WHERE holiday_id='$holiday_id'";
		$sql = mysqli_query($connect,$insert_qry);

	}


	// for delete holiday
	public function delete_holiday($holiday_id){
		$connect = $this->connect();

		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);

		$delete_qry = "DELETE FROM tb_holiday WHERE holiday_id='$holiday_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}



	// for adding
	public function insertHoliday($month,$day,$holidayName,$holidayType,$date){
		$connect = $this->connect();

		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$holidayName = mysqli_real_escape_string($connect,$holidayName);
		$holidayType = mysqli_real_escape_string($connect,$holidayType);
		$date = mysqli_real_escape_string($connect,$date);

		$insert_qry = "INSERT INTO tb_holiday (holiday_id,holiday_date,holiday_value,holiday_type,DateCreated) 
						VALUES ('','$holiday_date','$holidayName','$holidayType','$date')";
		$sql = mysqli_query($connect,$insert_qry);
	}



	// for mainform.php
	public function regHolidayToEvent(){
		
		$connect = $this->connect();

		$year = date("Y");

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_type = 'Regular Holiday'"));

		if ($num_rows != 0) {
			echo '<div class="panel panel-warning" style="margin-top:10px;">
					 <div class="panel-heading" style="border-color:ORANGE;border-width:0 1px 4px 1px;padding:3px 10px;">
					 	<center>Regular Holiday</center>
			 		</div>
					 <div class="panel-body">';

			$select_qry = "SELECT * FROM tb_holiday WHERE holiday_type = 'Regular Holiday'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						$date_create = date_create($row->holiday_date .", ".$year);
						$date_format = date_format($date_create, 'l');

					 	echo "<b>".$row->holiday_date . "</b> - " . $row->holiday_value . " (<i>".$date_format."</i>)<br/>";						
				}
			}

			echo '</div>
				</div>';
		}

	} // end of function



	// form mainform.php
	public function specialNonWorkingDaysHolidayToEvent(){
		
		$connect = $this->connect();
		$year = date("Y");
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_type = 'Special non-working day'"));

		if ($num_rows != 0) {
			echo '<div class="panel panel-danger style="margin-top:10px;">
					 <div class="panel-heading" style="border-color:RED;border-width:0 1px 4px 1px;padding:3px 10px;">
					 	<center>Special non-working day Holiday</center>
			 		</div>
					 <div class="panel-body">';


			$select_qry = "SELECT * FROM tb_holiday WHERE holiday_type = 'Special non-working day'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						$date_create = date_create($row->holiday_date .", ".$year);
						$date_format = date_format($date_create, 'l');

					 	echo "<b>".$row->holiday_date . "</b> - " . $row->holiday_value . " (<i>".$date_format."</i>)<br/>";					
				}
			}

			echo '</div>
				</div>';
		}

	} // end of function



	// check if exist holidate
	public function existHolidate($month,$day,$holiday_id){
		$connect = $this->connect();

		$holiday_id = mysqli_real_escape_string($connect,$holiday_id);
		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday_date' AND holiday_id !='$holiday_id'"));

		return $num_rows;

	}


	// for checking if exist ung date sa holiday
	public function dateIsHoliday($month,$day){
		$connect = $this->connect();

		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday_date'"));

		return $num_rows;
	}


	public function getHolidayInfoByMonthDay($month,$day){
		$connect = $this->connect();

		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);

		$holiday_date = $month . " " . $day;

		$select_qry = "SELECT * FROM tb_holiday WHERE holiday_date = '$holiday_date'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}



	public function getCutOffHoliday(){
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


		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$holiday_date = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");
				// if sakop
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to){
					echo "<li><b>" .date_format(date_create($row->holiday_date. ", " . $year),"F d, Y") ."</b> - <span style='color:#317eac;'>" . $row->holiday_value . "</span> (<i>". date_format(date_create($row->holiday_date. ", " . $year),"l") . "</i>)</li>";
					
				}
			}
		}


	}


	// for checking if has a holiday to a cut off
	public function holidayCutOffCount(){
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


		$has_holiday = 0;
		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$holiday_date = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");
				// if sakop
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to){
					$has_holiday = 1;
					
				}
			}
		}

		return $has_holiday;
	}



	// for checking if has a holiday to a cut off
	public function holidayCutOffTotalCount(){
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


		$count = 0;
		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$holiday_date = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");
				// if sakop
				$day = date_format(date_create($holiday_date), 'l');


				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to && $day != "Saturday" && $day != "Sunday"){
					$count++;
					
				}
			}
		}

		return $count;
	}



	// for getting if the holiday date in a cut off
	public function getHolidayDateCutOff(){
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


		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$holiday_date = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");
				// if sakop
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to){
					//echo "<li><b>" .date_format(date_create($row->holiday_date. ", " . $year),"F d, Y") ."</b> - <span style='color:#317eac;'>" . $row->holiday_value . "</span> (<i>". date_format(date_create($row->holiday_date. ", " . $year),"l") . "</i>)</li>";
					echo date_format(date_create($row->holiday_date. ", " . $year),"F d, Y");
				}
			}
		}
	}



	public function checkExistHoliday($holiday){
		$connect = $this->connect();
		$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

		return $holiday_num_rows;
	}

	





}


?>