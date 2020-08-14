<?php

	class Attendance extends Connect_db{
		/*public $bio_id;
		public $date;
		public $time_in;
		public $time_out;

		public function __construct($bio_id,$date,$time_in,$time_out){
			$this->bio_id = $bio_id;
			$this->date = $date;
			$this->time_in = $time_in;
			$this->time_out = $time_out;
		} */

		// FOR INSERT TIME IN TIME OUT
		public function insert_time_in_time_out($bio_id,$date,$time_in,$time_out,$dateCreated){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$date = mysqli_real_escape_string($connect,$date);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);
			$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

			// insert query
			// the date is enclosed with slant qoutation because it is a php reserve word
			$insert_qry = "INSERT INTO tb_attendance(bio_id,`date`,time_in,time_out,DateCreated) VALUES ('".$bio_id."',
																								'".$date."',
																								'".$time_in."',
																								'".$time_out."',
																								'".$dateCreated."')";
			$sql = mysqli_query($connect,$insert_qry);
		}
		

		public function checkExistRealTimeAttendance($date,$bio_id){
			$connect = $this->connect();
			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			//$time_in = mysqli_real_escape_string($connect,$time_in);
			//$time_out = mysqli_real_escape_string($connect,$time_out);

			//$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date' AND (time_in = '00:00:00' OR time_out = '00:00:00')"));
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
			return $num_rows;
			
		}

		// for getting the rows
		public function getRowsTimeInOut($date,$bio_id){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE `date`='$date' AND bio_id = '$bio_id'"));
			return $num_rows;
		}



		// for getting the rows
		public function getInfoByDateBioId($date,$bio_id){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$select_qry = "SELECT * FROM tb_attendance WHERE `date`='$date' AND bio_id = '$bio_id'";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);

			return $row;
		}


		// for update
		public function updateTimeInTimeOut($date,$bio_id,$time_in,$time_out){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);

			$update_qry = "UPDATE tb_attendance SET time_in = '$time_in', time_out = '$time_out' WHERE `date` = '$date' AND bio_id = '$bio_id'";
			$sql = mysqli_query($connect,$update_qry);

		}


		// for update
		public function updateTimeIn($date,$bio_id,$time_in){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			//$time_out = mysqli_real_escape_string($connect,$time_out);

			$update_qry = "UPDATE tb_attendance SET time_in = '$time_in' WHERE `date` = '$date' AND bio_id = '$bio_id'";
			$sql = mysqli_query($connect,$update_qry);

		}


		public function updateTimeOut($date,$bio_id,$time_out){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			//$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);

			$update_qry = "UPDATE tb_attendance SET time_out = '$time_out' WHERE `date` = '$date' AND bio_id = '$bio_id'";
			$sql = mysqli_query($connect,$update_qry);

		}

		


		// for update when adding attendance by payroll admin
		public function updateAttendanceInfo($date,$bio_id,$time_in,$time_out){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);

			$update_qry = "UPDATE tb_attendance SET time_in = '$time_in', time_out = '$time_out' WHERE `date` = '$date' AND bio_id = '$bio_id'";
			$sql = mysqli_query($connect,$update_qry);

		}

		// for selecting to avoid time out will be his/ her in
		public function selectExtist($date,$bio_id){
			$connect = $this->connect();

			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$select_qry = "SELECT * FROM tb_attendance WHERE `date`='$date' AND bio_id = '$bio_id'";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);
			$time_in = $row->time_in;
			return $time_in;

		}
		
		// for viewing attendance to table
		public function attendanceTotTable($bio_id){
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


			$cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');
			$cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

			$cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;


			// for selecting payroll approve check if the current cut off is already approve or send , so no edit anymore , 3 approve stat ibig sabihin pending pa kay mam julie
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE CutOffPeriod='$cutOffPeriod'"));




			//$day = date_format(date_create($final_date_to),"l");

			//$last_day_edit

			//if ($day == "Friday") {
			//	$last_day_edit = 
			//}

			//echo $final_date_from . "<br/>";
			//echo $final_date_to . "<br/>";
			//echo $date_payroll . "<br/>";

			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' ORDER BY `date` DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");


					//echo $attendance_date . " and " . $final_date_from . " and " . $date_payroll . "<br/>";

					$timeFrom = date_format(date_create($row->time_in), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');
					if ($row->time_out == "00:00:00"){
						$timeTo = "-";
					}

					if ($row->time_in == "00:00:00"){
						$timeFrom = "-";
					}

					echo "<tr id=".$row->attendance_id." style='text-align:center'>";
						echo "<td>".$date_format."</td>";
						echo "<td>".$timeFrom."</td>";
						echo "<td>".$timeTo."</td>";
						echo "<td>";
						//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
							//$date_upload =  date("Y-m-d",strtotime($row->DateCreated) + 86400);
							// 1 day ung pagitan after uploading
							//echo $current_date_time . " and " . $date_upload;
							//if ($current_date_time <= $date_upload) {

							//if ($num_rows == 0){
								echo "<a href='#' id='edit_bio_id' class='action-a' title='Edit Time In Time Out Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
							//}
							//else {
							//	echo "No action";
							//}
							//}

							//else {
							//	echo "No action";
							//}
					//	}

						//else {
					//		echo "No action";
					//	}
							
						echo "</td>";
					echo "</tr>";

				}
			}

		}


		// getting attendance by current cut off
		public function getCurrentCutOff($bio_id){
			$connect = $this->connect();

			/*$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' ORDER BY `date` DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){

					// for current cut off
					date_default_timezone_set("Asia/Manila");
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

					$timeFrom = date_format(date_create($row->time_in), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');
					if ($row->time_out == "00:00:00"){
						$timeTo = "-";
					}


					$cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');
					$cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

					$cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;


					// for selecting payroll approve check if the current cut off is already approve or send , so no edit anymore , 3 approve stat ibig sabihin pending pa kay mam julie
					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE CutOffPeriod='$cutOffPeriod'"));


	
					// for displaying if pasok sa cut off
					if ($row->date >= $final_date_from && $row->date <= $final_date_to){
						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y') . " - <u><i>" .date_format($date_create, 'D') . "</i></u>";


						echo "<tr id=".$row->attendance_id." style='text-align:center'>";
							echo "<td>".$date_format."</td>";
							echo "<td>".$timeFrom."</td>";
							echo "<td>".$timeTo."</td>";

							if ($num_rows == 0) {
								echo "<td>";
									echo "<a href='#' id='edit_bio_id' class='action-a' title='Edit Time In Time Out Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
								echo "</td>";
							}
							else {
								echo "<td>No action</td>";
							}
						echo "</tr>";
					}
					
				}
			}*/

			// for current cut off
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

			//echo $final_date_from . " "  .$final_date_to;

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
		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'l');

		    	if ($day != "Saturday" && $day != "Sunday"){
		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter];    		
		    		$weekdays_count++; // for echo condition

		    		$existAttendance = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
		    		//if ($num_rows == 0) {

		    		if ($existAttendance != 0){

		    			$cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');
						$cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

						$cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;


					// for selecting payroll approve check if the current cut off is already approve or send , so no edit anymore , 3 approve stat ibig sabihin pending pa kay mam julie
						$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE CutOffPeriod='$cutOffPeriod'"));

		    			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' ORDER BY `date` = '$date' DESC";
						$result = mysqli_query($connect,$select_qry);
						$row = mysqli_fetch_object($result);

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y') . " - <u><i>" .date_format($date_create, 'D') . "</i></u>";

						$timeFrom = date_format(date_create($row->time_in), 'g:i A');
						$timeTo = date_format(date_create($row->time_out), 'g:i A');
						if ($row->time_out == "00:00:00"){
							$timeTo = "-";
						}

						if ($row->time_in == "00:00:00"){
							$timeFrom = "-";
						}



						


						echo "<tr id=".$row->attendance_id." style='text-align:center'>";
							echo "<td>".$date_format."</td>";
							echo "<td>".$timeFrom."</td>";
							echo "<td>".$timeTo."</td>";

							if ($num_rows == 0) {
								echo "<td>";
									echo "<a href='#' id='edit_bio_id' class='action-a' title='Edit Time In Time Out Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
								echo "</td>";
							}
							else {
								echo "<td>No action</td>";
							}
						echo "</tr>";

		    		}
		    		else {
		    			$date_format = date_format(date_create($date), 'F d, Y') . " - <u><i>" .date_format($date_create, 'D') . "</i></u>";
			    		
		    			$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31

						$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));


						if ($holiday_num_rows == 0) {


							$row_emp = $this->getEmpInfoByBioId($bio_id);

							// check nating kung leave
							$leave_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$row_emp->emp_id' AND dateFrom <= '$date' AND dateTo >= '$date' AND approveStat = '1'"));

							if ($leave_num_rows == 0){


					    		echo "<tr id='' style='text-align:center'>";
										echo "<td>".$date_format."</td>";
										echo "<td>-</td>";
										echo "<td>-</td>";
										echo "<td><i><small>-</small></i></td>";
								echo "</tr>";
							}

							else {
								$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$row_emp->emp_id' AND dateFrom <= '$date' AND dateTo >= '$date' AND approveStat = '1'";
								$result_leave = mysqli_query($connect,$select_leave_qry);
								$row_leave = mysqli_fetch_object($result_leave);
								echo "<tr id='' style='text-align:center'>";
									echo "<td>".$date_format."</td>";
									echo "<td style='background-color: #27ae60 ;color:#fff'><center><strong>".$row_leave->FileLeaveType."</strong></center></td>";
									echo "<td style='background-color: #27ae60 ;color:#fff'><center><strong>".$row_leave->LeaveType."</strong></center></td>";
									echo "<td>No Action</td>";
								echo "</tr>";

							}
						}
						// if holiday
						else {

							//$holiday_date = $month . " " . $day;

							$select_holiday_qry = "SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'";
							$result_holiday = mysqli_query($connect,$select_holiday_qry);
							$row_holiday = mysqli_fetch_object($result_holiday);

							/*echo "<tr id='' style='text-align:center'>";
									echo "<td>".$date_format."</td>";
									echo "<td colspan='3' style='background-color:#2980b9;color:#fff'><center><strong>".$row_holiday->holiday_type." - " . $row_holiday->holiday_value."</strong></center></td>";
							echo "</tr>";
							*/
							echo "<tr id='' style='text-align:center'>";
									echo "<td>".$date_format."</td>";
									echo "<td style='background-color:#2980b9;color:#fff'><center><strong>".$row_holiday->holiday_type."</strong></center></td>";
									echo "<td style='background-color:#2980b9;color:#fff'><center><strong>".$row_holiday->holiday_value."</strong></center></td>";
									echo "<td>No Action</td>";
							echo "</tr>";
						}
					}

		    			
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    }while($counter <= $count);

		}


		// for specific date
		public function getSpecificDate($bio_id,$dateFrom,$dateTo){
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

			
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
			$dateTo = mysqli_real_escape_string($connect,$dateTo);


			$select_qry = "SELECT * FROM tb_attendance WHERE `date` BETWEEN '$dateFrom' AND '$dateTo' AND bio_id = '$bio_id' ORDER BY `date` DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");


					//echo $attendance_date . " and " . $final_date_from . " and " . $date_payroll . "<br/>";

					$timeFrom = date_format(date_create($row->time_in), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');
					if ($row->time_out == "00:00:00"){
						$timeTo = "-";
					}
					if ($row->time_in == "00:00:00"){
						$timeFrom = "-";
					}

					$cutOff_dateFrom =  date_format(date_create($final_date_from), 'F d, Y');

					$cutOff_dateTo =  date_format(date_create($final_date_to), 'F d, Y');

					$cutOffPeriod = $cutOff_dateFrom . " - " . $cutOff_dateTo;


					// for selecting payroll approve check if the current cut off is already approve or send , so no edit anymore , 3 approve stat ibig sabihin pending pa kay mam julie
					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_approval WHERE CutOffPeriod='$cutOffPeriod'"));

					echo "<tr id=".$row->attendance_id." style='text-align:center'>";
						echo "<td>".$date_format."</td>";
						echo "<td>".$timeFrom."</td>";
						echo "<td>".$timeTo."</td>";
						echo "<td>";
						//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
							//$date_upload =  date("Y-m-d",strtotime($row->DateCreated) + 86400);
							// 1 day ung pagitan after uploading
							//echo $current_date_time . " and " . $date_upload;
							//if ($current_date_time <= $date_upload) {

							//if ($num_rows == 0){
								echo "<a href='#' id='edit_bio_id' class='action-a' title='Edit Time In Time Out Employee Info'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
							//}

							//else {
							//	echo "No action";
							//}
							//}

							//else {
							//	echo "No action";
							//}
						//}

						//else {
							//echo "No action";
						//}
							
						echo "</td>";
					echo "</tr>";

				}
			}

		}

		// for getting amount of tardiness if sakop ng cut off
		public function getTardinessAmount($bio_id){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$late_amount = 0;
			$late = 0;
			$undertime_amount = 0;
			$undertime = 0;



			// select employee for getting working_id
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			$working_id = $row_emp->working_hours_id; // bali eto ung working id nya

			// select working hours for getting time in time out
			$select_workingHours_qry = "SELECT * FROM tb_working_hours WHERE working_hours_id = '$working_id'";
			$result_workingHours = mysqli_query($connect,$select_workingHours_qry);
			$row_workingHours = mysqli_fetch_object($result_workingHours);

			// for time in time out
			$timeIn = $row_workingHours->timeFrom;
			$timeOut = $row_workingHours->timeTo;


			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');



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


						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');

						$attendance_date = date_format(date_create($row->date),"Y-m-d");

						// if sakop ng date
						if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){

							$time_in = substr($row->time_in,0,6) . "00";

							$time_out = substr($row->time_out,0,6) . "00";

							//echo $time_in . "<br/>";

							$date_create = date_create($row->date);
							$day = date_format($date_create,"l");

							


							// check natin kung ung day ng date na un is weekends so d nya to ipeperform
							if ($day != "Saturday" && $day != "Sunday") {

							

							$grace_period = date("H:i:s",strtotime("+15 minutes",strtotime($timeIn)));

							//echo $grace_period . "<br/>";

							$allowance = 0;

								$select_allowance_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row_emp->emp_id'";
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

								//return $allowance;

								//echo $regular_ot_rate;

								//$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);						
							  
							    $minWageEffectiveDate = $this->getRowLatestMinimum()->effectiveDate;
							    $getLastEffectiveDate = $this->getRowLastMinimum()->effectiveDate;


							    $daily_rate = ($row_emp->Salary + $allowance)/ 22;

							    if ($attendance_date < $minWageEffectiveDate) {
							    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
						    		
						    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

						    		
						    		$monthly_increase = ($min_wage_increase * 26);

						    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


						    		//echo $min_wage_increase . "ang increase";
							    }


							    //$daily_rate =  $row->Salary / 26;
							    
								//$hourly_rate = $daily_rate / 8;
								$hourly_rate = round($daily_rate / 9,2);





							// eto ay para sa grace period na 5 minutes
							if ($row->time_in >= $grace_period){

								//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								
								//$new_daily_rate = $hourly_rate * 9;


								//echo $hourly_rate . " " . $attendance_date . " tardiness <br/>"; 
								// for OT's rate
								//$ot_rate = round($hourly_rate + ($hourly_rate * .6),2);

								if ($late_amount == 0){


									// checking if wlang time out
									if ($row->time_out == "00:00:00"){
										$late_amount = ((strtotime($timeOut) - strtotime($timeIn) - 3600)/3600) * $hourly_rate; // ung 3600 katumbas niyan isang oras
										//echo $row->date;
										//echo $late;
										//echo $late;
										//echo "wew";


									}

									else {

										// check tardy in half day from 8:30 - 12:00
										if ($row->time_in > "08:30:00") {
											//$late = strtotime($time_in) - strtotime("08:30:00"); // so first late

											// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
											if ($row->time_in <= "12:00:00"){
												$late_amount = ((strtotime($time_in) - strtotime("08:30:00")) / 3600) * $hourly_rate;
											}

											// ibig sabihin half day siya sa afternoon siya pumasok
											else if ($row->time_in >= "13:00:00"){
												$morning_late = strtotime("12:00:00") - strtotime($timeIn);
												$late_amount = (((strtotime($time_in) - strtotime("13:00:00")) + $morning_late) / 3600) * $hourly_rate;

												//echo $late / 3600 . "<br/>";
												//echo $attendance_date . "<br/>";
											}		

											else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
												$morning_late = strtotime("12:00:00") - strtotime($timeIn);
												$late_amount = ($morning_late / 3600) * $hourly_rate; // so first late
											//	echo $late / 60;
											}


											//echo $time_in;

											//echo $late / 60 . "<br/>";

											//echo $late;
											//echo $row->time_in;
											//echo "wew1";

										}

										/*// check tardy in half day from 1:00 - 6:30
										if ($row->time_in >= "13:00:00") {
											$late = (strtotime($time_in) - strtotime("13:00:00")) + 190;

											//echo "wew2";
											//echo $row->time_in . " " .$late;
											//echo 	
										}

										// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
										if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
											$late = strtotime("12:00:00") - strtotime($timeIn); // so first late
											//echo "wew3";
										//	echo $late / 60;
										}*/

									}

								} //end of if

								else {

									// checking if wlang time out
									if ($row->time_out == "00:00:00"){
										//$late = strtotime("17:30:00") - strtotime("08:30:00");
										$late_amount = $late_amount + (((strtotime($timeOut) - strtotime($timeIn) - 3600) / 3600) * $hourly_rate);
										//echo $late;
										//echo "wew";
									}

									else {

										// late 
										if ($row->time_in > "08:30:00") {
																					
											// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
											if ($row->time_in <= "12:00:00"){
												$late_amount = $late_amount + (((strtotime($time_in) - strtotime("08:30:00")) / 3600) * $hourly_rate);
											}

											// ibig sabihin half day siya sa afternoon siya pumasok
											else if ($row->time_in >= "13:00:00"){
												$morning_late = strtotime("12:00:00") - strtotime($timeIn);
												$late_amount = $late_amount + ((((strtotime($time_in) - strtotime("13:00:00")) + $morning_late) / 3600) * $hourly_rate);
											}		

											else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
												$morning_late = strtotime("12:00:00") - strtotime($timeIn);
												$late_amount = $late_amount + (($morning_late / 3600) * $hourly_rate); // so first late
											//	echo $late / 60;
											}								

										}

										// check tardy in half day from 1:00 - 6:30
										//if ($row->time_in >= "13:00:00") {
											//$late = (strtotime($time_in) - strtotime("12:00:00")) + $late + 190;

											


											//echo 3600
											//echo strtotime($time_in) - strtotime("12:00:00");
											//echo $late ."<br/>";
											//echo "wew";
											
										//}

										// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
										//if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
										//	$late = strtotime("12:00:00") - strtotime($timeIn) + $late;; // so first late
										//	echo $late / 60;
										//}
									}

								} //end of else
							} // end of if for grace period

								
							// if undertime
							if ($undertime_amount == 0) {
								// check if emp has undertime to the current day
								if ($row->time_out == "00:00:00"){

								}

								else {



									if ($row->time_out < $timeOut){
										//echo $attendance_date . "<br/>";
										$undertime_amount = $hourly_rate * ((strtotime($timeOut) - strtotime($time_out))/ 3600);
										//echo ($undertime / 60) . "<br/>";
										//echo "wew1";
										//echo $undertime . "<br/>";
									}
								}
							}

							else {
								if ($row->time_out == "00:00:00"){

								}
								else {
									if ($row->time_out < $timeOut){
										//$undertime = strtotime("18:30:00") - strtotime($time_out);
										$undertime_amount = ($hourly_rate *((strtotime($timeOut) - strtotime($time_out))/3600)) + $undertime_amount;
										//echo "wew2";
									}
								}
								//$undertime = strtotime("18:30:00") - strtotime($time_out) + $undertime;
							}
										
							
						}	// end of if for weekends na pumasok counter as ot kasi d nya normal days


						
					} // end of if ng sakop ng cut off
					//echo $late . "<br/>";

				} //end of while
			} //end of if result

			//reutn $undertime;
			//echo $undertime . " s ";
			//echo $late;
			//return (($late + $undertime) / 60);
			//echo $undertime;
			//return $late / 60;
			//return $undertime + ($late/60);
			//echo ($late / 3600)  . "<br/>";
			//echo ($undertime / 3600) . "<br/>";
			//echo ($late) /60;
			//echo $late_amount . "<br/>";
			//echo $undertime_amount . "<br/>";
			//echo $undertime / 3600;
			//echo ($undertime / 60) . "<br/>";
			//return ($late + $undertime) / 60;

			return ($late_amount + $undertime_amount);

		}


		// for getting attendance overtime of restday holiday ot
		public function getTardiness($bio_id){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);


			$late = 0;
			$undertime = 0;


			// select employee for getting working_id
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);


			$dept_id = $row_emp->dept_id;

			$working_id = $row_emp->working_hours_id; // bali eto ung working id nya

			// select working hours for getting time in time out
			$select_workingHours_qry = "SELECT * FROM tb_working_hours WHERE working_hours_id = '$working_id'";
			$result_workingHours = mysqli_query($connect,$select_workingHours_qry);
			$row_workingHours = mysqli_fetch_object($result_workingHours);

			// for time in time out
			$timeIn = $row_workingHours->timeFrom;
			$timeOut = $row_workingHours->timeTo;

	
			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');



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


						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');

						$attendance_date = date_format(date_create($row->date),"Y-m-d");

						// if sakop ng date
						if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){

							$time_in = substr($row->time_in,0,6) . "00";

							$time_out = substr($row->time_out,0,6) . "00";

							//echo $time_in . "<br/>";

							$date_create = date_create($row->date);
							$day = date_format($date_create,"l");

							//echo $attendance_date ."<br/>";

							$num_rows_half_day = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'"));

							//echo $num_rows_half_day;

							$period_type = "";
							if ($num_rows_half_day != 0){
								$select_half_day = "SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'";
								$result_half_day = mysqli_query($connect,$select_half_day);
								$row_half_day = mysqli_fetch_object($result_half_day);

								$halfdayType = $row_half_day->FileLeaveType;

								// check kung anong period if morning or afternoon
								$period_type = substr($halfdayType,0,-23);
								//echo $period_type;
								
							}


							//echo $num_rows_half_day . " " . $attendance_date . "<br/>";

							// for checking ung total leave count nya
							//$num_rows_half_day = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND FileLeaveType = 'File Halfday Leave' AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date'"));


							//echo ."<br/>";

							// check natin kung ung day ng date na un is weekends so d nya to ipeperform

							$holiday = date_format(date_create($attendance_date), 'F j'); // Day of the month without leading zeros  	1 to 31

							$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));


							if ($day != "Saturday" && $day != "Sunday" && $holiday_num_rows == 0) {

								$grace_period = date("H:i:s",strtotime("+15 minutes",strtotime($timeIn)));

								//echo $row->time_in . " " .$grace_period . "<br/>";


								//echo $dept_id . " " . $row->time_in . "<br/>";

								if ($dept_id == 1 && $row->time_in <= "11:00:00"){
									$late += 0;
								}

								else {

									//echo $row->time_in . "<br/>";
									// eto ay para sa grace period na 5 minutes
									if ($row->time_in > $grace_period || $row->time_in == "00:00:00"){

										

										if ($late == 0){

											//if ($row->time_out == "00:00:00"){

											//}

											// checking if wlang time out
											if ($row->time_in == "00:00:00"){

												//if ($row->time_out == "00:00:00") {
													//$late = strtotime($timeOut) - strtotime($timeIn) - 3600; // ung 3600 katumbas niyan isang oras
												//$late = 16200; // halfday automatic
												//}
												//if ($row->time_in == ) {

												//}
												//echo $row->date;
												//echo $late;
												//echo $late;
												//echo "wew";

												//if ($row->time_out == "00:00:00") {
												//echo $time_in;
												$morning_late = 16200; // + (strtotime($time_in) - strtotime("08:30:00"))
												//}


												//echo "wew"
												//echo $num_rows_half_day . " " . $period_type;

												if ($num_rows_half_day == 1 && $period_type == "Morning"){

													//echo "Wew" . "<br/>";
													$morning_late = $morning_late - (270 * 60);
													if ($morning_late <0){
														$morning_late = 0;
													}

												}

												$late = $morning_late;

												//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

												//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;
											}

											else {

												// check tardy in half day from 8:30 - 12:00
												if ($row->time_in > $timeIn) {
												//	echo "wew";
													//$late = strtotime($time_in) - strtotime("08:30:00"); // so first late

													// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
													if ($row->time_in <= "12:00:00"){
														//echo "wew";
														$morning_late = (strtotime($time_in) - strtotime($timeIn));


														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";

														//echo $num_rows_half_day . " " . $period_type;

														if ($num_rows_half_day == 1 && $period_type == "Morning"){

														//	echo "Wew" . "<br/>";

															$morning_late = $morning_late - (270*60);
															if ($morning_late <0){
																$morning_late = 0;
															}

														}

														$late = $morning_late;

														//echo "This is late " . ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";



														//if ($period_type =) // dito ako huminto sa logic na to
													}

													// ibig sabihin half day siya sa afternoon siya pumasok
													else if ($row->time_in >= "13:00:00"){
														$morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn);

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";
														//echo $num_rows_half_day . " " . $period_type;
														//echo  $attendance_date . "<br/>";

														// if has half day leave
														if ($num_rows_half_day == 1 && $period_type == "Morning"){

														//	echo "Wew" . "<br/>";

															$morning_late = $morning_late - (270*60);
															if ($morning_late <0){
																$morning_late = 0;
															}

														}

														// "morning late=" . $morning_late . "<br/>";


														$late = $morning_late;

														//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";
														
													}		

													else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
														$morning_late = strtotime("12:00:00") - strtotime($timeIn);

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";

														//echo  $attendance_date . "<br/>";
														//echo $num_rows_half_day . " " . $period_type;

														if ($num_rows_half_day == 1 && $period_type == "Morning"){

														//	echo "Wew" . "<br/>";


															$morning_late = $morning_late - (270*60);
															if ($morning_late <0){
																$morning_late = 0;
															}

														}

														$late = $morning_late; // so first late

														//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";
														
													//	echo $late / 60;
													}

													//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;


													//if ()

													//echo $time_in;

													//echo $late / 60 . "<br/>";

													//echo $late;
													//echo $row->time_in;
													//echo "wew1";

												}

												/*// check tardy in half day from 1:00 - 6:30
												if ($row->time_in >= "13:00:00") {
													$late = (strtotime($time_in) - strtotime("13:00:00")) + 190;

													//echo "wew2";
													//echo $row->time_in . " " .$late;
													//echo 	
												}

												// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
												if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
													$late = strtotime("12:00:00") - strtotime($timeIn); // so first late
													//echo "wew3";
												//	echo $late / 60;
												}*/

											}

											//echo $late . "<br/>";

										} //end of if late == 0

										else {




											//echo "wew";

											//echo $attendance_date . "<br/>";
											//if ($row->time_out == "00:00:00"){
												
											//}

											// checking if wlang time out
											if ($row->time_in == "00:00:00"){
												//echo $row->time_in . "<br/>";
												//$late = strtotime("17:30:00") - strtotime("08:30:00");


												//$late = $late + 16200; // halfday automatic
												//}
												//if ($row->time_in == ) {

												//}
												//echo $row->date;
												//echo $late;
												//echo $late;
												//echo "wew";

												//if ($row->time_out == "00:00:00") {
												$morning_late = 16200; // + (strtotime($time_in) - strtotime("08:30:00"))


												//$late = $late + strtotime($timeOut) - strtotime($timeIn) - 3600;

												//echo $num_rows_half_day . " " . $period_type;


												if ($num_rows_half_day == 1 && $period_type == "Morning"){

													$morning_late = $morning_late - (270*60);
													if ($morning_late <0){
														$morning_late = 0;
													}

												}

												$late = $late + $morning_late;

												//echo $late . "<br/>";

												//echo $late;
												//echo "wew";

												//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;
											}

											else {

												//echo $attendance_date . "<br/>";

												// late 
												if ($row->time_in > $timeIn) {
																							
													// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
													if ($row->time_in <= "12:00:00"){
														
														$morning_late = (strtotime($time_in) - strtotime($timeIn));

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";
														//echo $num_rows_half_day . " " . $period_type;

														if ($num_rows_half_day == 1 && $period_type == "Morning"){

														//	echo "Wew" . "<br/>";

															$morning_late = $morning_late - (270*60);

															//echo $late . " " . $attendance_date  . "<br/>";
															if ($morning_late < 0){
																$morning_late = 0;
															}

														}

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

														$late = $late + $morning_late;

														//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

														//echo $late . "<br/>";

														
													}

													// ibig sabihin half day siya sa afternoon siya pumasok
													else if ($row->time_in >= "13:00:00"){
														$morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn); // 45
													//	echo $morning_late . " " . $attendance_date ."<br/>";

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";


														//echo  $attendance_date . "<br/>";
														//echo $num_rows_half_day . " " . $period_type;

														if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//echo "Wew" . "<br/>";

															$morning_late = $morning_late - (270*60);

															//echo $morning_late . "<br/>";

															if ($morning_late <0){
																$morning_late = 0;
															}

														}

														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

														$late = $late + $morning_late;

														//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

														
													}		

													else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
														$morning_late = strtotime("12:00:00") - strtotime($timeIn);

														

														//echo  $attendance_date . "<br/>";

														//echo $num_rows_half_day . " " . $period_type;


														if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//echo "Wew" . "<br/>";

															$morning_late = $morning_late - (270*60);
															if ($morning_late <0){
																$morning_late = 0;
															}

														}

														//echo "morning late=" . $morning_late . "<br/>";
														//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

														$late = $morning_late + $late;; // so first late


														//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

														
													//	echo $late / 60;
													}								

												}

												//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

												// check tardy in half day from 1:00 - 6:30
												//if ($row->time_in >= "13:00:00") {
													//$late = (strtotime($time_in) - strtotime("12:00:00")) + $late + 190;

													


													//echo 3600
													//echo strtotime($time_in) - strtotime("12:00:00");
													//echo $late ."<br/>";
													//echo "wew";
													
												//}

												// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
												//if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
												//	$late = strtotime("12:00:00") - strtotime($timeIn) + $late;; // so first late
												//	echo $late / 60;
												//}
											}

										} //end of else

										//echo $morning_late . "<br/>";


										// if has leave
												//echo $num_rows_half_day . "<br/>";


									} // end of if for grace period
								} // end of else na may late
									
								// if undertime
								if ($undertime == 0) {
									// check if emp has undertime to the current day
									//if ($row->time_in == "00:00:00"){

									//}

									 if ($row->time_out == "00:00:00"){
										//$afternoon_late = 16200;

										$afternoon_late = 16200; // + strtotime($timeOut) - strtotime($time_out)

										if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

												$afternoon_late = $afternoon_late - (270*60);
												if ($afternoon_late <0){
													$afternoon_late = 0;
												}
										}

										$undertime = $afternoon_late;


										//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;
									}

									else {





										if ($row->time_out < $timeOut){

											

											$afternoon_late = strtotime($timeOut) - strtotime($time_out);

											

											//echo $period_type . "<br/>";

											if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

												$afternoon_late = $afternoon_late - (270*60);
												if ($afternoon_late <0){
													$afternoon_late = 0;
												}
											}

											if ($row_emp->emp_id == 5){
												//echo "wew";
												if ($row->time_out >= "18:30:00"){
													//echo "wew";
													//$afternoon_late += 0;
													$undertime += 0;

												}
												else {
													$undertime = $undertime + $afternoon_late;
												}
											}

											// 148
											else if ($row_emp->emp_id == 148){
												if ($row->time_out >= "21:00:00"){
													//echo "wew";
													//$afternoon_late += 0;
													$undertime += 0;

												}
												else {
													$undertime = $undertime + $afternoon_late;
												}
											}


											else {

												$undertime = $undertime + $afternoon_late;
											}

											//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;

										//	echo $undertime . "<br/>";

											//echo ($undertime / 60) . " " . $attendance_date . "<br/>"; 

											//echo $morning_late . " " . $attendance_date .  "<br/>";

											/*if ($num_rows_half_day == 1){
												$morning_late = $morning_late - (270*60);
												if ($morning_late <0){
													$morning_late = 0;
												}

											}*/


											//echo ($undertime / 60) . "<br/>";
											//echo "wew1";
											//echo $undertime . "<br/>";
										}
									}

									//echo $attendance_date . " " . $undertime . "<br/>";
								}

								else {
									//if ($row->time_in == "00:00:00"){

									//}

									if ($row->time_out == "00:00:00"){
										//$afternoon_late = 16200;

										$afternoon_late = 16200; // strtotime($timeOut) - strtotime($time_out)

										if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

												$afternoon_late = $afternoon_late - (270*60);
												if ($afternoon_late <0){
													$afternoon_late = 0;
												}
										}

										$undertime = $undertime + $afternoon_late;
										//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;
									}

									else {
										if ($row->time_out < $timeOut){
											//$undertime = strtotime("18:30:00") - strtotime($time_out);
											$afternoon_late = strtotime($timeOut) - strtotime($time_out);

											
											if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

												$afternoon_late = $afternoon_late - (270*60);
												if ($afternoon_late <0){
													$afternoon_late = 0;
												}
											}

											if ($row_emp->emp_id == 5){
												//echo "wew";
												if ($row->time_out >= "18:30:00"){
													//echo "wew";
													//$afternoon_late += 0;
													$undertime += 0;

												}
												else {
													$undertime = $undertime + $afternoon_late;
												}
											}


											// 148
											else if ($row_emp->emp_id == 148){
												if ($row->time_out >= "21:00:00"){
													//echo "wew";
													//$afternoon_late += 0;
													$undertime += 0;

												}
												else {
													$undertime = $undertime + $afternoon_late;
												}
											}

											else {

												$undertime = $undertime + $afternoon_late;
											}

											//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;

										//	echo $undertime . "<br/>";
											//echo "wew2";
										}
									}
									//$undertime = strtotime("18:30:00") - strtotime($time_out) + $undertime;
								}

								//echo $attendance_date . " " . $undertime . "<br/>";
										
							
							}	// end of if for weekends na pumasok counter as ot kasi d nya normal days

							//echo $attendance_date . "<br/>";
							


						
					} // end of if ng sakop ng cut off
					//echo $late . "<br/>";

				} //end of while
			} //end of if result

			//reutn $undertime;
			//echo $undertime . " s ";
			//echo $late;
			//return (($late + $undertime) / 60);
			//echo $undertime;
			//return $late / 60;
			//return $undertime + ($late/60);
			//echo ($late / 3600)  . "<br/>";
			//echo ($undertime / 3600) . "<br/>";
			//echo (($late + $undertime) / 60) . "<br/>";

			//echo $undertime / 60;
			return ($late + $undertime) / 60;

		}


		// for getting absences amount when in cut off
		public function getAbsencesAmount($bio_id){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);


			// select employee for getting working_id
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			$absent_amount = 0;
			$absent = 0;
			$leave_amount = 0;
			$leave = 0;

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


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";


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
		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'l');

		    	if ($day != "Saturday" && $day != "Sunday"){
		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter];    		
		    		$weekdays_count++; // for echo condition

		    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
		    		if ($num_rows == 0) {




		    			// $date_create = ;
						$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31


						//echo $holiday;
		    			$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

		    			// if the day is holiday
		    			if ($holiday_num_rows == 0) {
		    				
		    				$allowance = 0;

								$select_allowance_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row_emp->emp_id'";
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

								//return $allowance;

								//echo $regular_ot_rate;

								//$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);						
							  
							    $minWageEffectiveDate = $this->getRowLatestMinimum()->effectiveDate;
							    $getLastEffectiveDate = $this->getRowLastMinimum()->effectiveDate;


							    $daily_rate = ($row_emp->Salary + $allowance)/ 22;

							    if ($date < $minWageEffectiveDate) {
							    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
						    		
						    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

						    		
						    		$monthly_increase = ($min_wage_increase * 26);

						    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


						    		//echo $min_wage_increase . "ang increase";
							    }


							    //$daily_rate =  $row->Salary / 26;
							    
								//$hourly_rate = $daily_rate / 8;
								$hourly_rate = $daily_rate / 9;


			    			if ($absent_amount == 0){
			    				$absent_amount = $hourly_rate * 9;

			    				//echo $date . " " . $absent_amount.   "<br/>";

			    				//$

			    			}
			    			else {
			    				$absent_amount = $absent_amount + ($hourly_rate * 9);
			    				//echo $date . " " .$absent_amount. "<br/>";
			    			}
		    			}
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    }while($counter <= $count);




		    // getting emp id kasi eto ung ipangcocondition natin sa pagkuha ng leave
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			// current emp_id ng employee
			$emp_id = $row_emp->emp_id;


			// check natin dito kung nakaleave siya

			//echo $final_date_from . "<br/>";
			//echo $final_date_to;

			// logic ibibigay sa array then cocondition kung pasok ung araw na un sa cut off
			//$leaveRange = array();

			$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

			//echo $num_rows_leave;

			if ($num_rows_leave != 0) {

				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'";
				if ($result_leave = mysqli_query($connect,$select_leave_qry)){
					while($row_leave = mysqli_fetch_object($result_leave)){

						$leaveRange = array();
					    $leaveFrom = strtotime($row_leave->dateFrom);
					    $leaveTo = strtotime($row_leave->dateTo);
					    $leave_output_format = 'Y-m-d';
					    $leave_step = '+1 day';

					    $leave_count = 0;
					    while($leaveFrom <= $leaveTo) {

				    		$leave_count++;
					        $leaveRange[] = date($leave_output_format, $leaveFrom);
					        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
					    }

					    $leave_counter = 0;
					    do {
					    	$leave_date = $leaveRange[$leave_counter];

					    	//echo $leave_date;

					    	//echo $leave_date;
					    	// check na sakop siya ng date of payroll
					    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
					    		$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

					    		//echo $leave_date;

					    		// hindi pa siya nag eexist consider as leave na siya
					    		if ($existAttendanceByDate == 0){

					    			$date_create_leave = date_create($leave_date);
					    			$date_format_leave = date_format($date_create_leave,"l");

					    			// ibig sabihin sakop siya ng leave na mababawasan as late
					    			if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){


					    				$allowance = 0;

										$select_allowance_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$row_emp->emp_id'";
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

										//return $allowance;

										//echo $regular_ot_rate;

										//$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);						
									  
									    $minWageEffectiveDate = $this->getRowLatestMinimum()->effectiveDate;
									    $getLastEffectiveDate = $this->getRowLastMinimum()->effectiveDate;


									    $daily_rate = ($row_emp->Salary + $allowance)/ 22;

									    if ($leave_date < $minWageEffectiveDate) {
									    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
								    		
								    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

								    		
								    		$monthly_increase = ($min_wage_increase * 26);

								    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


								    		//echo $min_wage_increase . "ang increase";
									    }


									    //$daily_rate =  $row->Salary / 26;
									    
										//$hourly_rate = $daily_rate / 8;
										$hourly_rate = $daily_rate / 9;


					    				if ($leave_amount == 0){
					    					$leave_amount = ($hourly_rate * 9);


					    					//echo $leave_date . " " . $hourly_rate ."<br/>";
						    			}

						    			else {
						    				$leave_amount = $leave_amount + ($hourly_rate * 9);
						    				
						    				//echo $leave_date . " ". $hourly_rate. "<br/>";
						    			}



					    			}


					    			
					    		} // end of if
					    	}


					    	


					    	

					    	$leave_counter++;
					    } while($leave_counter < $leave_count);
				    }
			    }

		    }
	    					

		   // echo "absent: " . $absent;
		    //echo "leave:" . $;
					    

		    //echo $leave;
//
		    //echo $leave;

		    	$totalAbsenAmount = $absent_amount - $leave_amount; 
		   
		    	
		    	if ($totalAbsenAmount <= 0) {
		    		$totalAbsenAmount = 0;
		    	}
		    
		 //  echo $totalAbsent;

	   	  //echo round($totalAbsenAmount,2);
		   return $totalAbsenAmount;

		}


		// for getting the basic if sakop ng cutt off
		public function getBasicPayAmount($emp_id){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);

			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);


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


			$daysAffectedIncrease = 0;  
			$daysNotAffectedIncrease = 0;  

		    $weekdays_count = 0;
		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'l');

		    	if ($day != "Saturday" && $day != "Sunday"){
		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter];


	    			$allowance = 0;
					$select_allowance_qry = "SELECT * FROM tb_emp_allowance WHERE emp_id = '$emp_id'";
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

					//return $allowance;

					//echo $regular_ot_rate;

					//$reg_ot_amount = round($regular_ot_rate * $regularOTmin,2);						
				  
				    $minWageEffectiveDate = $this->getRowLatestMinimum()->effectiveDate;
				    $getLastEffectiveDate = $this->getRowLastMinimum()->effectiveDate;


				    //$daily_rate = ($row_emp->Salary + $allowance)/ 22;


				    if ($date >= $minWageEffectiveDate) {
				    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
			    		
			    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

			    		
			    		$monthly_increase = ($min_wage_increase * 26);

			    		//$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;

			    		$daysAffectedIncrease++;



			    		
				    }

				    else {
				    	$daysNotAffectedIncrease++;
				    }



				    //echo $daily_rate . " " . $date. "<br/>" ;

				    //$daily_rate =  $row->Salary / 26;
				    
					//$hourly_rate = $daily_rate / 8;
					//$hourly_rate = $daily_rate / 9;

	    		}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    } while($counter <= $count);


		    $latest_min_wage = round(((($this->getRowLatestMinimum()->basicWage + $this->getRowLatestMinimum()->COLA) * 26)/22)*$daysAffectedIncrease,2);



		    $oldest_min_wage = round(((($this->getRowLastMinimum()->basicWage + $this->getRowLatestMinimum()->COLA) * 26)/22)*$daysNotAffectedIncrease,2);



		  /*  $dailyIncrease = round($monthly_increase / 22,2);


		    $cutOffIncrease = $dailyIncrease * $daysAffectedIncrease;

		    $old_monthlySalary = (($this->getRowLastMinimum()->basicWage + $this->getRowLastMinimum()->COLA) * 13);
		   // echo $daysAffectedIncrease . "<br/>";
	    	//echo  $cutOffIncrease. "<br/>";
	    	//echo $old_monthlySalary;
		*/
		 //   echo round($latest_min_wage + $oldest_min_wage,2);
		    return round($latest_min_wage + $oldest_min_wage,2);
		    
			
			


			
		}

		public function getAbsences($bio_id){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$absent = 0;
			$leave = 0;
			$holiday_not_granted = 0;
			
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


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			



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
		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'l');

		    	if ($day != "Saturday" && $day != "Sunday"){
		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 
		    		//echo $date . "<br/>";  		
		    		$weekdays_count++; // for echo condition

		    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
		    		if ($num_rows == 0) {


		    			// $date_create = ;
						$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31
						//echo $holiday . "<br/>";

						//echo $holiday;
		    			$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

		    			$granted = "Granted";
		    			if ($holiday_num_rows == 1){
		    				//echo "Wew";
		    				$granted = $this->getHolidayDateCutOff($holiday,$bio_id);

		    				//echo $granted . "<br/>";
		    				//echo $granted;
		    				// ibig sabihin hindi granted
		    				if ($granted != "Granted") {
		    					if ($holiday_not_granted == 0){
			    					$holiday_not_granted = 1;
				    			}
				    			else {
				    				$holiday_not_granted = $holiday_not_granted + 1;
				    			}
		    				}

		    				//echo $
		    			}

		    			// if the day is holiday
		    			if ($holiday_num_rows == 0) {
			    			if ($absent == 0){
			    				$absent = 1;
			    			}
			    			else {
			    				$absent = $absent + 1;
			    			}
		    			}
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    }while($counter <= $count);




		    // getting emp id kasi eto ung ipangcocondition natin sa pagkuha ng leave
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			// current emp_id ng employee
			$emp_id = $row_emp->emp_id;


			// check natin dito kung nakaleave siya

			//echo $final_date_from . "<br/>";
			//echo $final_date_to;

			// logic ibibigay sa array then cocondition kung pasok ung araw na un sa cut off
			//$leaveRange = array();

			$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));


			//echo $num_rows_leave . "<br/>";

			//echo $num_rows_leave;

			if ($num_rows_leave != 0) {
				


				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND FileLeaveType = 'Leave with pay'";
				if ($result_leave = mysqli_query($connect,$select_leave_qry)){
					while($row_leave = mysqli_fetch_object($result_leave)){

						$leaveRange = array();
					    $leaveFrom = strtotime($row_leave->dateFrom);
					    $leaveTo = strtotime($row_leave->dateTo);
					    $leave_output_format = 'Y-m-d';
					    $leave_step = '+1 day';

					    $leave_count = 0;
					    while($leaveFrom <= $leaveTo) {

				    		$leave_count++;
					        $leaveRange[] = date($leave_output_format, $leaveFrom);
					        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
					    }

					    $leave_counter = 0;
					    do {
					    	$leave_date = $leaveRange[$leave_counter];

					    	//echo $leave_date;

					    	//echo $leave_date;
					    	// check na sakop siya ng date of payroll
					    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
					    		$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

					    		//echo $leave_date . "<br/>";
					    		//echo $leave_date;

					    		// hindi pa siya nag eexist consider as leave na siya
					    		if ($existAttendanceByDate == 0){

					    			$date_create_leave = date_create($leave_date);
					    			$date_format_leave = date_format($date_create_leave,"l");

					    			// ibig sabihin sakop siya ng leave na mababawasan as late
					    			if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
					    				if ($leave == 0){
					    					$leave = 1;
						    			}

						    			else {
						    				$leave = $leave + 1;
						    			}



					    			}


					    			
					    		} // end of if
					    	}


					    	


					    	

					    	$leave_counter++;
					    } while($leave_counter < $leave_count);
				    }
			    }

		    }



		    // for halfday leave purposes codes goes here ...
		   $halfday_leave_count = 0;
		   $select_half_day_leave_qry = "SELECT * FROM tb_leave WHERE (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND approveStat = '1' AND emp_id = '$emp_id'";
		   if ($result_half_day_leave = mysqli_query($connect,$select_half_day_leave_qry)){
		 	  while($row_half_day_leave = mysqli_fetch_object($result_half_day_leave)){

		 	  		$existAttendanceHalfday = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$row_half_day_leave->dateTo'"));

		 	  		if ($row_half_day_leave->dateTo >= $final_date_from && $row_half_day_leave->dateTo <= $final_date_to && $existAttendanceHalfday == 0) {
		 	  			$halfday_leave_count += .5;
		 	  		}
		 	  }
		   }


	    					

		    //echo "absent: " . $absent . "<br/>";
		    //echo "leave:" . $leave . "<br/>";
		    //echo "holiday granted:" . $holiday_not_granted . "<br/>";
		    //echo "halfday leave count:" . $halfday_leave_count . "<br/>";
					    

		    //echo $leave;
//
		    //echo $leave;

		    	// $totalAbsent = ($absent - 2) - $leave + $holiday_not_granted; // to be remove after payroll payroll oct 26, 2017 - nov 10, 2017
	    	//echo $halfday_leave_count;
		    // echo $holiday_not_granted;
	    	 $totalAbsent = ($absent) - $leave + $holiday_not_granted - $halfday_leave_count; //
	   		 //echo $leave .  $holiday_not_granted;
	    	
	    	if ($totalAbsent <= 0) {
	    		$totalAbsent = 0;
	    	}
		    
		  // echo $totalAbsent . "<br/>";
	      // echo $absent . "<br/>";
	      // echo $leave. "<br/>";
		 // echo $holiday_not_granted. "<br/>";
	    //echo $totalAbsent;
		   return $totalAbsent;

		   



			/*$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');

						$attendance_date = date_format(date_create($row->date),"Y-m-d");

						



						
				}

			}*/
			
			//echo $dates;

		}


		public function getPresent($bio_id,$day_from,$day_to){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$day_from = mysqli_real_escape_string($connect,$day_from);
			$day_to = mysqli_real_escape_string($connect,$day_to);

			$present = 0;
			$leave = 0;
			$holiday_not_granted = 0;
			
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


					//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
					$minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
					//$minus_five_day = date("Y-m-d");
					//echo $minus_five_day;
					//echo $minus_five_day;
					//echo $minus_five_day;

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			



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


		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'w'); // 


				

		    	if ($day >= $day_from && $day <= $day_to){

		    		//echo $day . " " . $day_from . "<br/>";

		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 
		    		//echo $date . "<br/>";  		
		    		$weekdays_count++; // for echo condition

		    		if ($date < date("Y-m-d")){


			    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
			    		if ($num_rows == 0) {


			    			// $date_create = ;
							$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31
							//echo $holiday . "<br/>";

							//echo $holiday;
			    			$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

			    			$granted = "Granted";
			    			if ($holiday_num_rows == 1){
			    				//echo "Wew";
			    				$granted = $this->getHolidayDateCutOff($holiday,$bio_id);

			    				//echo $granted . "<br/>";
			    				//echo $granted;
			    				// ibig sabihin hindi granted
			    				if ($granted == "Granted") {
			    					$present += 1;
			    				}

			    				//echo $
			    			}

			    			
			    		}

			    		else {
			    			$present += 1;
			    		}
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    }while($counter <= $count);




		    // getting emp id kasi eto ung ipangcocondition natin sa pagkuha ng leave
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			// current emp_id ng employee
			$emp_id = $row_emp->emp_id;


			// check natin dito kung nakaleave siya

			//echo $final_date_from . "<br/>";
			//echo $final_date_to;

			// logic ibibigay sa array then cocondition kung pasok ung araw na un sa cut off
			//$leaveRange = array();

			$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));


			//echo $num_rows_leave . "<br/>";

			//echo $num_rows_leave;

			if ($num_rows_leave != 0) {
				

				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND FileLeaveType = 'Leave with pay'";
				if ($result_leave = mysqli_query($connect,$select_leave_qry)){
					while($row_leave = mysqli_fetch_object($result_leave)){

						$leaveRange = array();
					    $leaveFrom = strtotime($row_leave->dateFrom);
					    $leaveTo = strtotime($row_leave->dateTo);
					    $leave_output_format = 'Y-m-d';
					    $leave_step = '+1 day';

					    $leave_count = 0;
					    while($leaveFrom <= $leaveTo) {

				    		$leave_count++;
					        $leaveRange[] = date($leave_output_format, $leaveFrom);
					        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
					    }

					    $leave_counter = 0;
					    do {
					    	$leave_date = $leaveRange[$leave_counter];


					    	if ($leave_date < date("Y-m-d")){

						    	//echo $leave_date;

						    	//echo $leave_date;
						    	// check na sakop siya ng date of payroll
						    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
						    		$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date . "<br/>";
						    		//echo $leave_date;

						    		// hindi pa siya nag eexist consider as leave na siya
						    		if ($existAttendanceByDate == 0){

						    			$date_create_leave = date_create($leave_date);
						    			$date_format_leave = date_format($date_create_leave,"w");

						    			// ibig sabihin sakop siya ng leave na mababawasan as late
						    			if($date_format_leave >= $day_from && $date_format_leave <= $day_to){
						    				$present += 1;



						    			}


						    			
						    		} // end of if
						    	}
					    	}

					    	


					    	

					    	$leave_counter++;
					    } while($leave_counter < $leave_count);
				    }
			    }

		    }



		    // for halfday leave purposes codes goes here ...
		   /*$halfday_leave_count = 0;
		   $select_half_day_leave_qry = "SELECT * FROM tb_leave WHERE (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND approveStat = '1' AND emp_id = '$emp_id'";
		   if ($result_half_day_leave = mysqli_query($connect,$select_half_day_leave_qry)){
		 	  while($row_half_day_leave = mysqli_fetch_object($result_half_day_leave)){

		 	  		$existAttendanceHalfday = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$row_half_day_leave->dateTo'"));

		 	  		if ($row_half_day_leave->dateTo >= $final_date_from && $row_half_day_leave->dateTo <= $final_date_to && $existAttendanceHalfday == 0) {
		 	  			$halfday_leave_count += .5;
		 	  		}
		 	  }
		   }*/


	    					

		    //echo "absent: " . $absent . "<br/>";
		    //echo "leave:" . $leave . "<br/>";
		    //echo "holiday granted:" . $holiday_not_granted . "<br/>";
		    //echo "halfday leave count:" . $halfday_leave_count . "<br/>";
					    

		    //echo $leave;
//
		    //echo $leave;

		    	// $totalAbsent = ($absent - 2) - $leave + $holiday_not_granted; // to be remove after payroll payroll oct 26, 2017 - nov 10, 2017
	    	//echo $halfday_leave_count;
		    // echo $holiday_not_granted;
	    	// $totalAbsent = ($absent) - $leave + $holiday_not_granted - $halfday_leave_count; //
	   		 //echo $leave .  $holiday_not_granted;
	    	
	    	/*if ($totalAbsent <= 0) {
	    		$totalAbsent = 0;
	    	}*/
		    
		  // echo $totalAbsent . "<br/>";
	      // echo $absent . "<br/>";
	      // echo $leave. "<br/>";
		 // echo $holiday_not_granted. "<br/>";
	    //echo $totalAbsent;

	    	//echo $present . "<br/>";
		   return $present;

		   



			/*$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');

						$attendance_date = date_format(date_create($row->date),"Y-m-d");

						



						
				}

			}*/
			
			//echo $dates;

		}



		public function getPresentToPayroll($bio_id,$day_from,$day_to){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$day_from = mysqli_real_escape_string($connect,$day_from);
			$day_to = mysqli_real_escape_string($connect,$day_to);

			$present = 0;
			$leave = 0;
			$holiday_not_granted = 0;
			
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
					//$minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
					//$minus_five_day = date("Y-m-d");
					//echo $minus_five_day;
					//echo $minus_five_day;
					//echo $minus_five_day;

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			



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


		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'w'); // 


				

		    	if ($day >= $day_from && $day <= $day_to){

		    		//echo $day . " " . $day_from . "<br/>";

		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 
		    		//echo $date . "<br/>";  		
		    		$weekdays_count++; // for echo condition

		    		if ($date < date("Y-m-d")){


			    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
			    		if ($num_rows == 0) {


			    			// $date_create = ;
							$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31
							//echo $holiday . "<br/>";

							//echo $holiday;
			    			$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

			    			$granted = "Granted";
			    			if ($holiday_num_rows == 1){
			    				//echo "Wew";
			    				$granted = $this->getHolidayDateCutOff($holiday,$bio_id);

			    				//echo $granted . "<br/>";
			    				//echo $granted;
			    				// ibig sabihin hindi granted
			    				//$granted = "Granted"; // to be comment out after ECQ
			    				if ($granted == "Granted") {
			    					$present += 1;
			    				}

			    				//echo $
			    			}

			    			
			    		}

			    		else {
			    			$present += 1;
			    		}
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	

		    }while($counter <= $count);




		    // getting emp id kasi eto ung ipangcocondition natin sa pagkuha ng leave
			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			// current emp_id ng employee
			$emp_id = $row_emp->emp_id;


			// check natin dito kung nakaleave siya

			//echo $final_date_from . "<br/>";
			//echo $final_date_to;

			// logic ibibigay sa array then cocondition kung pasok ung araw na un sa cut off
			//$leaveRange = array();

			$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));


			//echo $num_rows_leave . "<br/>";

			//echo $num_rows_leave;

			if ($num_rows_leave != 0) {
				

				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND FileLeaveType = 'Leave with pay'";
				if ($result_leave = mysqli_query($connect,$select_leave_qry)){
					while($row_leave = mysqli_fetch_object($result_leave)){

						$leaveRange = array();
					    $leaveFrom = strtotime($row_leave->dateFrom);
					    $leaveTo = strtotime($row_leave->dateTo);
					    $leave_output_format = 'Y-m-d';
					    $leave_step = '+1 day';

					    $leave_count = 0;
					    while($leaveFrom <= $leaveTo) {

				    		$leave_count++;
					        $leaveRange[] = date($leave_output_format, $leaveFrom);
					        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
					    }

					    $leave_counter = 0;
					    do {
					    	$leave_date = $leaveRange[$leave_counter];


					    	if ($leave_date < date("Y-m-d")){

						    	//echo $leave_date;

						    	//echo $leave_date;
						    	// check na sakop siya ng date of payroll
						    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
						    		$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date . "<br/>";
						    		//echo $leave_date;

						    		// hindi pa siya nag eexist consider as leave na siya
						    		if ($existAttendanceByDate == 0){

						    			$date_create_leave = date_create($leave_date);
						    			$date_format_leave = date_format($date_create_leave,"w");

						    			// ibig sabihin sakop siya ng leave na mababawasan as late
						    			if($date_format_leave >= $day_from && $date_format_leave <= $day_to){
						    				$present += 1;



						    			}


						    			
						    		} // end of if
						    	}
					    	}

					    	


					    	

					    	$leave_counter++;
					    } while($leave_counter < $leave_count);
				    }
			    }

		    }



		    // for halfday leave purposes codes goes here ...
		   /*$halfday_leave_count = 0;
		   $select_half_day_leave_qry = "SELECT * FROM tb_leave WHERE (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND approveStat = '1' AND emp_id = '$emp_id'";
		   if ($result_half_day_leave = mysqli_query($connect,$select_half_day_leave_qry)){
		 	  while($row_half_day_leave = mysqli_fetch_object($result_half_day_leave)){

		 	  		$existAttendanceHalfday = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$row_half_day_leave->dateTo'"));

		 	  		if ($row_half_day_leave->dateTo >= $final_date_from && $row_half_day_leave->dateTo <= $final_date_to && $existAttendanceHalfday == 0) {
		 	  			$halfday_leave_count += .5;
		 	  		}
		 	  }
		   }*/


	    					

		    //echo "absent: " . $absent . "<br/>";
		    //echo "leave:" . $leave . "<br/>";
		    //echo "holiday granted:" . $holiday_not_granted . "<br/>";
		    //echo "halfday leave count:" . $halfday_leave_count . "<br/>";
					    

		    //echo $leave;
//
		    //echo $leave;

		    	// $totalAbsent = ($absent - 2) - $leave + $holiday_not_granted; // to be remove after payroll payroll oct 26, 2017 - nov 10, 2017
	    	//echo $halfday_leave_count;
		    // echo $holiday_not_granted;
	    	// $totalAbsent = ($absent) - $leave + $holiday_not_granted - $halfday_leave_count; //
	   		 //echo $leave .  $holiday_not_granted;
	    	
	    	/*if ($totalAbsent <= 0) {
	    		$totalAbsent = 0;
	    	}*/
		    
		  // echo $totalAbsent . "<br/>";
	      // echo $absent . "<br/>";
	      // echo $leave. "<br/>";
		 // echo $holiday_not_granted. "<br/>";
	    //echo $totalAbsent;

	    	//echo $present . "<br/>";
		   return $present;

		   



			/*$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
						

						$date_create = date_create($row->date);
						$date_format = date_format($date_create, 'F d, Y');

						$attendance_date = date_format(date_create($row->date),"Y-m-d");

						



						
				}

			}*/
			
			//echo $dates;

		}


		



		public function availablePayroll(){
			/*$connect = $this->connect();

			date_default_timezone_set("Asia/Manila");
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

			$select_qry = "SELECT * FROM tb_attendance";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);*/


		} // end of function

		// check if the attendance id is exist in the current employee using bio id
		public function existAttendance($attendance_id,$bio_id){
			$connect = $this->connect();

			$attendance_id = mysqli_real_escape_string($connect,$attendance_id);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE attendance_id='$attendance_id' AND bio_id = '$bio_id'"));
			return $num_rows;
		}


		// getting information by attendance id
		public function getInfoByAttendaceId($attendance_id){
			$connect = $this->connect();

			$attendance_id = mysqli_real_escape_string($connect,$attendance_id);

			$select_qry = "SELECT * FROM tb_attendance WHERE attendance_id='$attendance_id'";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);
			return $row;
		}



		// for request update purpose if no changes
		public function sameAttendance($attendance_id,$time_in,$time_out){
			$connect = $this->connect();

			$attendance_id = mysqli_real_escape_string($connect,$attendance_id);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE attendance_id='$attendance_id' AND time_in = '$time_in' AND time_out = '$time_out'"));
			return $num_rows;
		}

		// for updating requested time in time out
		public function updateRequestTimeInTimeOut($attendance_id,$time_in,$time_out){
			$connect = $this->connect();

			$attendance_id = mysqli_real_escape_string($connect,$attendance_id);
			$time_in = mysqli_real_escape_string($connect,$time_in);
			$time_out = mysqli_real_escape_string($connect,$time_out);
			
			$update_qry = "UPDATE tb_attendance SET time_in = '$time_in', time_out = '$time_out' WHERE attendance_id = '$attendance_id'";
			$sql = mysqli_query($connect,$update_qry); 
		}


		// for getting all attendance to table
		public function getAllAttendanceToTable($dateFrom,$dateTo){
			$connect = $this->connect();

			$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
			$dateTo = mysqli_real_escape_string($connect,$dateTo);


			$select_qry = "SELECT * FROM tb_attendance WHERE `date` BETWEEN '$dateFrom' AND '$dateTo' ORDER BY `date` DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					$date_create = date_create($row->date);
					$date = date_format($date_create, 'F d, Y');


					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE bio_id='$row->bio_id'"));


					//echo $num_rows;
					// if exist bibigay lang natin ung active na may bio id to avoid error
					if ($num_rows != 0) {
						// for getting employee information
						$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id='$row->bio_id'";
						$result_emp = mysqli_query($connect,$select_emp_qry);
						$row_emp = mysqli_fetch_object($result_emp);

						$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

						$timeFrom = "-";
						if ($row->time_in != "00:00:00"){
							$timeFrom = date_format(date_create($row->time_in), 'g:i:s A');
						}
						$timeTo = "-";
						if ($row->time_out != "00:00:00") {
							$timeTo = date_format(date_create($row->time_out), 'g:i:s A');
						}

						//if ($)

						echo "<tr>";
							echo "<td>".$fullName."</td>";
							echo "<td>".$date."</td>";
							echo "<td>".$timeFrom."</td>";
							echo "<td>".$timeTo."</td>";
						echo "</tr>";
					}
					/*echo "<tr>";
						echo "<td>".$row->bio_id."</td>";
						echo "<td>".$date."</td>";
						echo "<td>".$row->time_in."</td>";
						echo "<td>".$row->time_out."</td>";
					echo "</tr>";*/
				}
			}



		}


		// for getting all attendance to table
		public function getAllSubordinateAttendanceToTable($dateFrom,$dateTo,$head_emp_id){
			$connect = $this->connect();

			$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
			$dateTo = mysqli_real_escape_string($connect,$dateTo);
			$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);


			$select_qry = "SELECT * FROM tb_attendance INNER JOIN tb_employee_info ON tb_attendance.bio_id = tb_employee_info.bio_id WHERE `date` BETWEEN '$dateFrom' AND '$dateTo' ORDER BY `date` DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					$date_create = date_create($row->date);
					$date = date_format($date_create, 'F d, Y');


					$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE bio_id='$row->bio_id'"));


					//echo $num_rows;
					// if exist bibigay lang natin ung active na may bio id to avoid error
					if ($num_rows != 0) {

						if ($row->head_emp_id == $head_emp_id) {

							// for getting employee information
							$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id='$row->bio_id'";
							$result_emp = mysqli_query($connect,$select_emp_qry);
							$row_emp = mysqli_fetch_object($result_emp);

							$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

							$timeFrom = "-";
							if ($row->time_in != "00:00:00"){
								$timeFrom = date_format(date_create($row->time_in), 'g:i A');
							}
							$timeTo = "-";
							if ($row->time_out != "00:00:00") {
								$timeTo = date_format(date_create($row->time_out), 'g:i A');
							}

							//if ($)

							echo "<tr>";
								echo "<td>".$fullName."</td>";
								echo "<td>".$date."</td>";
								echo "<td>".$timeFrom."</td>";
								echo "<td>".$timeTo."</td>";
							echo "</tr>";
						}
					}
					/*echo "<tr>";
						echo "<td>".$row->bio_id."</td>";
						echo "<td>".$date."</td>";
						echo "<td>".$row->time_in."</td>";
						echo "<td>".$row->time_out."</td>";
					echo "</tr>";*/
				}
			}



		}


		// for checking if the time in and time out is not equal to 00:00:00
		public function checkTimeInTimeOut($date,$bio_id){
			$connect = $this->connect();
			$date = mysqli_real_escape_string($connect,$date);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			//$time_in = mysqli_real_escape_string($connect,$time_in);
			//$time_out = mysqli_real_escape_string($connect,$time_out);

			//$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date' AND (time_in = '00:00:00' OR time_out = '00:00:00')"));
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date' AND time_out = '00:00:00'"));
			return $num_rows;
			
		}


		// for checking if exist data by attendance id
		public function checkExistByAttendanceId($attendance_id){
			$connect = $this->connect();

			$attendance_id = mysqli_real_escape_string($connect,$attendance_id);

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE attendance_id='$attendance_id'"));
			return $num_rows;
		}


		// for reports of leave list history
	public function attendanceListReports($dateFrom,$dateTo){
		$connect = $this->connect();

		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);

		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "attendance_list_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Time In')
					->setCellValue('D1', 'Time Out');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$select_qry = "SELECT * FROM tb_attendance WHERE `date` >= '$dateFrom' AND `date` <= '$dateTo'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$exist_bio_id = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE bio_id = '$row->bio_id'"));

				if ($exist_bio_id != 0){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$row->bio_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);



					$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

					$date = date_format(date_create($row->date), 'F d, Y');

					$time_in = date_format(date_create($row->time_in), 'g:i A');
					$time_out = date_format(date_create($row->time_out), 'g:i A');


					$count++;
					$objPHPExcel->setActiveSheetIndex(0) 
						->setCellValue('A'.$count, $fullName)
						->setCellValue('B'.$count, $date)
						->setCellValue('C'.$count, $time_in)
						->setCellValue('D'.$count, $time_out);

				}
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:D1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('attendance_list_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	public function lateAttendanceListReports($dateFrom,$dateTo){
		$connect = $this->connect();

		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);

		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "late_attendance_list_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Time In')
					->setCellValue('D1', 'Time Out');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$select_qry = "SELECT tb.* FROM tb_attendance tb LEFT OUTER JOIN (SELECT bio_id, dept_id FROM tb_employee_info) tei ON tb.bio_id = tei.bio_id WHERE tb.date >= '$dateFrom' AND tb.date <= '$dateTo' AND tb.time_in > '08:45:59' AND tei.dept_id != '1' ";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$row->bio_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date = date_format(date_create($row->date), 'F d, Y');

				$time_in = date_format(date_create($row->time_in), 'g:i A');
				$time_out = date_format(date_create($row->time_out), 'g:i A');


				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $fullName)
					->setCellValue('B'.$count, $date)
					->setCellValue('C'.$count, $time_in)
					->setCellValue('D'.$count, $time_out);
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:D1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('late_attendance_list_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}


	// for reports of leave list history
	public function absentListReports($dateFrom,$dateTo){

		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "late_attendance_list_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date');





		$connect = $this->connect();

		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);

		ini_set('max_execution_time', -1);
		$count = 1;
		$select_qry = "SELECT * FROM tb_employee_info WHERE role_id != '1' AND dept_id != '4' AND emp_id != '172' AND ActiveStatus = '1' ORDER BY Lastname ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$counter = 0;

				$from = $dateFrom;

				do {

					if ($counter > 0){

						$from = str_replace('-', '/', $from);
						$from = date('Y-m-d',strtotime($from . "+1 days"));
					}

						
					

					$day = date_format(date_create($from), 'l');

					$holiday = date_format(date_create($from), 'F j'); // Day of the month without leading zeros  	1 to 31

					$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));


					$leave_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$row->emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Leave without pay') AND dateFrom <= '$from' AND dateTo >= '$from'"));



			    	if ($day != "Saturday" && $day != "Sunday" && $holiday_num_rows == 0 && $leave_num_rows == 0){
			    		//echo $dateFrom . "<br/>";

			    		$absent = $this->checkExistRealTimeAttendance($from,$row->bio_id);

			    		if ($absent == 0){

			    			//echo $row->Lastname . ", " . $row->Firstname . " " . $from . " " . $count . "<br/>";

			    			$full_name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
			    			$count++;
			    			$rowArray = array($full_name,$from);
                            $objPHPExcel->getActiveSheet()
                                    ->fromArray(
                                        $rowArray,   // The data to set
                                        NULL,        // Array values with this value will not be set
                                        'A'.$count         // Top left coordinate of the worksheet range where
                                                     //    we want to set these values (default is A1)
                                    );
			    		}


		    		}


					$counter++;
				}while($dateTo > $from);


			}
		}


		foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:D1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('late_attendance_list_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}


	// for getting the effectiveDAte of latest minimum wage
	public function getRowLatestMinimum(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}

	// for getting the effectiveDAte of latest minimum wage
	public function getRowLastMinimum(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate ASC LIMIT 2";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

		//return $row->effectiveDate;
	}


	// for checking if the latest minimum wage effectivity date is sakop ng current cut off
	public function checkMinWageEffectiveDateInCutOff(){
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


		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);


		$inCutOff = 0;
		if ($row->effectiveDate >= $final_date_from AND $row->effectiveDate <= $final_date_to){
			$inCutOff = 1;
		}

		return $inCutOff;

	}


	// for updating all bio id in db
	public function updateAttendanceBioId($bio_id,$new_bio_id){
		$connect = $this->connect();

		$bio_id = mysqli_real_escape_string($connect,$bio_id);
		$new_bio_id = mysqli_real_escape_string($connect,$new_bio_id);

		$update_qry = "UPDATE tb_attendance SET bio_id = '$new_bio_id' WHERE bio_id = '$bio_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for checking if exist ung date sa holiday
	public function dateIsHoliday($month,$day,$year){
		$connect = $this->connect();

		$month = mysqli_real_escape_string($connect,$month);
		$day = mysqli_real_escape_string($connect,$day);
		$year = mysqli_real_escape_string($connect,$year);

		$holiday_date = $month . " " . $day;

		$date_create_leave = date_create($month . " " . $day . ", " . $year );
		$date_format_leave = date_format($date_create_leave,"l");

		//echo $date_format_leave;

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday_date'"));

		if ($date_format_leave == "Sunday" || $date_format_leave == "Saturday"){
			$num_rows = 0;
		}

		return $num_rows;
	}


	public function getEmpInfoByBioId($bio_id){
		$connect = $this->connect();
		
		$bio_id = mysqli_real_escape_string($connect,$bio_id);

		$select_qry = "SELECT * FROM tb_employee_info WHERE bio_id='$bio_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for getting if the holiday date in a cut off , kinuha ko lang tong class na to sa class ng holiday
	public function getHolidayDateCutOff($holiday,$bio_id){
		$connect = $this->connect();

		//echo $holiday . "<br/>";
		$row_emp = $this->getEmpInfoByBioId($bio_id);
		$emp_id = $row_emp->emp_id;
		$leave = 0;
		$prev_exist_leave = 0;
		$next_exist_leave = 0;

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




		//echo $final_date_from . " " . $final_date_to . "<br/>";

		//echo $holiday . " " . $bio_id .  "<br/>";
		$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($holiday))));
		$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($holiday))));
		
		//echo "Holiday: " . $holiday." Prev. day: " . $prev_day . " Next day:" . $next_day . "<br/>";

		$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
		$day_prev = date_format(date_create($prev_day. ", " . $year),"j");
		//echo $month_prev . " " . $day_prev;

		$month_next = date_format(date_create($next_day. ", " . $year),"F");
		$day_next = date_format(date_create($next_day. ", " . $year),"j");


		//echo "Holiday:" .$holiday ." Prev day:" . $this->dateIsHoliday($month_prev,$day_prev) . " Next day:" . $this->dateIsHoliday($month_next,$day_next);

		// for prev day
		$prev_isHoliday = $this->dateIsHoliday($month_prev,$day_prev,$year); // ibig sabihin static holiday prev day
		//echo $prev_isHoliday;
		if ($prev_isHoliday == 0){ // ibig sabihin hindi siya holiday
			//echo $holiday . " prev 0 <br/>";

			//echo $prev_day . "<br/>";

			if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
			//echo "wew";
				$year =(date("Y")) - 1;
			}
			else {
				$year = date("Y");
			}

			$holiday_day_type_prev = date_format(date_create($prev_day. ", " . $year), 'l');

			

			//echo $year;
			//echo $holiday_day_type_prev;


			if ($holiday_day_type_prev == "Sunday") {




				$prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
				$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
				$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

				$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev,$year);		

				if ($prev_isHoliday_monday == 0){
					//echo "DITO";
					$prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");

					$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

							//echo $num_rows_leave . "<br/>";

							//echo $num_rows_leave;

					if ($num_rows_leave != 0) {
						//echo "DITO";

					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
					if ($result_leave = mysqli_query($connect,$select_leave_qry)){
							while($row_leave = mysqli_fetch_object($result_leave)){

								$leaveRange = array();
							    $leaveFrom = strtotime($row_leave->dateFrom);
							    $leaveTo = strtotime($row_leave->dateTo);
							    $leave_output_format = 'Y-m-d';
							    $leave_step = '+1 day';

							    $leave_count = 0;
							    while($leaveFrom <= $leaveTo) {

						    		$leave_count++;
							        $leaveRange[] = date($leave_output_format, $leaveFrom);
							        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
							    }

							    $leave_counter = 0;

							    do {
							    	$leave_date = $leaveRange[$leave_counter];
							    	//echo $leave_date . "<br/>";

							    	//echo $leave_date;

							    	//echo $leave_date;
							    	// check na sakop siya ng date of payroll
							    	//echo $final_date_from . " " . $final_date_to;
							    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
							    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

							    		//echo $leave_date . "<br/>";

							    		//echo $leave_date . "<br/>";
							    		//echo $leave_date;

							    		if ($prev_day == $leave_date){
							    			$prev_exist_leave = 1;
							    		}
							    	//}
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
						    }
					    }

				    }

				    if ($prev_exist_leave != 1){
						$num_rows_prev = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$prev_day'"));
					}
					else {
						$num_rows_prev = 1;
					}
				}

				do {
					if ($prev_isHoliday_monday == 1){
						$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day))));
						$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
						$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

						if ($holiday_day_type_prev == "Sunday") {
							$prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
							$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
							$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
						}


						if ($holiday_day_type_prev == "Saturday") {
							$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
							$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
							$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
						}

						/*
						if ($holiday_day_type_prev == "Saturday") {
							$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
							$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
							$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
						}
						*/

						if ($this->dateIsHoliday($month_prev,$day_prev,$year) == 0){

							$holiday_day_type_prev = date_format(date_create($holiday. ", " . $year), 'l');

							//echo "Holiday:" . $holiday . "Holiday Day Type: " .$holiday_day_type_prev . "<br/>";

							//echo "Holiday: " . $holiday . " Type Day:" . $type_day_prev;
							
							$prev_day = date_format(date_create($type_day_prev. ", " . $year),"Y-m-d");

							//echo " " . $prev_day;

							//echo "Holiday:" .$holiday . " Final Prev day:". $prev_day . "<br/>";
							$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

							//echo $num_rows_leave . "<br/>";

							//echo $num_rows_leave;

							if ($num_rows_leave != 0) {
								

							$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
							if ($result_leave = mysqli_query($connect,$select_leave_qry)){
									while($row_leave = mysqli_fetch_object($result_leave)){

										$leaveRange = array();
									    $leaveFrom = strtotime($row_leave->dateFrom);
									    $leaveTo = strtotime($row_leave->dateTo);
									    $leave_output_format = 'Y-m-d';
									    $leave_step = '+1 day';

									    $leave_count = 0;
									    while($leaveFrom <= $leaveTo) {

								    		$leave_count++;
									        $leaveRange[] = date($leave_output_format, $leaveFrom);
									        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
									    }

									    $leave_counter = 0;

									    do {
									    	$leave_date = $leaveRange[$leave_counter];
									    	//echo $leave_date . "<br/>";

									    	//echo $leave_date;

									    	//echo $leave_date;
									    	// check na sakop siya ng date of payroll
									    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
									    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

									    		//echo $leave_date . "<br/>";

									    		//echo $leave_date . "<br/>";
									    		//echo $leave_date;

									    		if ($prev_day == $leave_date){
									    			$prev_exist_leave = 1;
									    		}
									    	//}
								   
									    	$leave_counter++;
									    } while($leave_counter < $leave_count);
								    }
							    }

						    }

						    if ($prev_exist_leave != 1){
								$num_rows_prev = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$prev_day'"));
							}
							else {
								$num_rows_prev = 1;
							}
							$prev_isHoliday_monday = 0;
							//echo $num_rows_prev . "<br/>";
						}
					}

				}while($prev_isHoliday_monday == 1);		
			}
			else {
				$prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
				$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

				//echo $num_rows_leave . "<br/>";

				//echo $num_rows_leave;

				if ($num_rows_leave != 0) {
					

				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
				if ($result_leave = mysqli_query($connect,$select_leave_qry)){
						while($row_leave = mysqli_fetch_object($result_leave)){

							$leaveRange = array();
						    $leaveFrom = strtotime($row_leave->dateFrom);
						    $leaveTo = strtotime($row_leave->dateTo);
						    $leave_output_format = 'Y-m-d';
						    $leave_step = '+1 day';

						    $leave_count = 0;
						    while($leaveFrom <= $leaveTo) {

					    		$leave_count++;
						        $leaveRange[] = date($leave_output_format, $leaveFrom);
						        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
						    }

						    $leave_counter = 0;

						    do {
						    	$leave_date = $leaveRange[$leave_counter];
						    	//echo $leave_date . "<br/>";

						    	//echo $leave_date;

						    	//echo $leave_date;
						    	// check na sakop siya ng date of payroll
						    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
						    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date . "<br/>";

						    		//echo $prev_day. " " . $leave_date . "<br/>";
						    		//echo $leave_date;

						    		if ($prev_day == $leave_date){
						    			$prev_exist_leave = 1;
						    			//echo "wew";
						    		}
						    	//}
					   
						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
					    }
				    }

			    }

			    if ($prev_exist_leave != 1){
			    	//echo $prev_day;
					$num_rows_prev = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$prev_day'"));
					//echo $num_rows_prev;
				}
				else {
					$num_rows_prev = 1;
				}
			}
		}
		do {
			//echo $holiday . " prev 1 <br/>";

			//echo $prev_day . "<br/>";
			//echo $prev_isHoliday . "<br/>";
			if ($prev_isHoliday == 1){

				//echo "Wew </br>";

				//echo $holiday . " " .$prev_day . "<br/>";

				//echo $prev_day . "<br/>";

				$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day))));
				//echo $prev_day . " " . $holiday;



				$month_prev = date_format(date_create($prev_day. ", " . $year),"F");

				if ($final_date_from == ((date("Y")) - 1) . "-12-26" && $month_prev == "December") {
					//echo "wew";
					$year =(date("Y")) - 1;
				}
				else {
					$year = date("Y");
				}
				//echo $prev_day . " " .$year;
				$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

				//echo $month_prev . " " . $day_prev . "<br/>";
				//echo $holiday . " " .$prev_day . " " . $month_prev . " " . $day_prev . "<br/>";

				$holiday_day_type_prev = date_format(date_create($prev_day. ", " . $year), 'l');
				//echo $holiday_day_type_prev . "<br/>";

				if ($holiday_day_type_prev == "Sunday") {
					$prev_day = date('F d',(strtotime ( '-2 day' , strtotime ($prev_day)))); // so friday na to
					$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
					$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

					//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
				}

				if ($holiday_day_type_prev == "Saturday") {
					$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
					$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
					$day_prev = date_format(date_create($prev_day. ", " . $year),"j");

					//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
				}

				/*
				else if ($holiday_day_type_prev == "Saturday"){
					$prev_day = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day)))); // so friday na to
					$month_prev = date_format(date_create($prev_day. ", " . $year),"F");
					$day_prev = date_format(date_create($prev_day. ", " . $year),"j");
				}*/

				$prev_isHoliday = $this->dateIsHoliday($month_prev,$day_prev,$year);
				//echo $prev_day . " " .$prev_isHoliday . "<br/>";
				if ($prev_isHoliday == 0){
					//$holiday_day_type_prev = date_format(date_create($holiday. ", " . $year), 'l');

					//echo "Holiday:" . $holiday . "Holiday Day Type: " .$holiday_day_type_prev . "<br/>";

					//echo "Holiday: " . $holiday . " Type Day:" . $type_day_prev;
						
					//echo "wew";

					$prev_day = date_format(date_create($prev_day. ", " . $year),"Y-m-d");
					//echo $prev_day;
					//echo $prev_day;
					//echo "Holiday:" .$holiday . " Final Prev day:". $prev_day . "<br/>";
					//echo $emp_id;
					$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

					//echo $num_rows_leave . "<br/>";

					//echo $num_rows_leave;

					if ($num_rows_leave != 0) {
						

					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
					if ($result_leave = mysqli_query($connect,$select_leave_qry)){
							while($row_leave = mysqli_fetch_object($result_leave)){

								$leaveRange = array();
							    $leaveFrom = strtotime($row_leave->dateFrom);
							    $leaveTo = strtotime($row_leave->dateTo);
							    $leave_output_format = 'Y-m-d';
							    $leave_step = '+1 day';

							    $leave_count = 0;
							    while($leaveFrom <= $leaveTo) {

						    		$leave_count++;
							        $leaveRange[] = date($leave_output_format, $leaveFrom);
							        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
							    }

							    $leave_counter = 0;

							    do {
							    	$leave_date = $leaveRange[$leave_counter];
							    	//echo $leave_date . "<br/>";

							    	//echo $leave_date;

							    	//echo $leave_date;
							    	// check na sakop siya ng date of payroll
							    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
							    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

							    		//echo $leave_date . "<br/>";

							    		//echo $leave_date . "<br/>";
							    		//echo $leave_date;

							    		if ($prev_day == $leave_date){
							    			$prev_exist_leave = 1;
							    		}
							    	//}
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
						    }
					    }

				    }

				    if ($prev_exist_leave != 1){
						$num_rows_prev = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$prev_day'"));
					}
					else {
						$num_rows_prev = 1;
					}
					//echo $num_rows_prev;
					//echo $num_rows_prev . "<br/>";

					$prev_isHoliday = 0;
					//echo $num_rows_prev . "<br/>";
				}
			}

		}while($prev_isHoliday == 1);

	//	echo $holiday . " " .$prev_day . " " . $num_rows_prev . "<br/>";

		// for next day
		
		$next_isHoliday = $this->dateIsHoliday($month_next,$day_next,$year); // ibig sabihin static holiday prev day
		//echo $month_next . " " . $day_next . " " . $next_isHoliday. "<br/>";

		$year = date("Y");
		//echo $holiday . " " . $month_next ." " .  $day_next . " ". $next_isHoliday . "<br/>";
		//echo $holiday . " " . $next_isHoliday . "<br/>";
		if ($next_isHoliday == 0){	
			//echo "wew";
			//echo $holiday . " next 0 <br/>";
			//echo $next_day . "<br/>";

			$holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');

			//echo $holiday . " " . $next_isHoliday . "<br/>";

			//echo $holiday_day_type_next;

			if ($holiday_day_type_next == "Saturday"){
				$next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day)))); // so friday na to
				//echo $next_day . "<br/>";
				$month_next = date_format(date_create($next_day. ", " . $year),"F");
				$day_next = date_format(date_create($next_day. ", " . $year),"j");

				$next_isHoliday_saturday = $this->dateIsHoliday($month_next,$day_next,$year);
				//echo $next_isHoliday_saturday;
				if ($next_isHoliday_saturday == 0){
					//$num_rows_next = 0;
					$next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
					//echo $next_day . "<br/>";
					$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

					//echo $num_rows_leave . "<br/>";

					//echo $num_rows_leave;

					if ($num_rows_leave != 0) {
						

					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
						if ($result_leave = mysqli_query($connect,$select_leave_qry)){
							while($row_leave = mysqli_fetch_object($result_leave)){

								$leaveRange = array();
							    $leaveFrom = strtotime($row_leave->dateFrom);
							    $leaveTo = strtotime($row_leave->dateTo);
							    $leave_output_format = 'Y-m-d';
							    $leave_step = '+1 day';

							    $leave_count = 0;
							    while($leaveFrom <= $leaveTo) {

						    		$leave_count++;
							        $leaveRange[] = date($leave_output_format, $leaveFrom);
							        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
							    }

							    $leave_counter = 0;

							    do {
							    	$leave_date = $leaveRange[$leave_counter];
							    	//echo $leave_date . "<br/>";

							    	//echo $leave_date;

							    	//echo $leave_date;
							    	// check na sakop siya ng date of payroll
							    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
							    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

							    		//echo $leave_date . "<br/>";

							    		//echo $leave_date . "<br/>";
							    		//echo $leave_date;

							    		if ($next_day == $leave_date){
							    			$next_exist_leave = 1;
							    		}
							    	//}
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
						    }
					    }

				    }

				    if ($next_day <= $final_date_to){

					    if ($next_exist_leave != 1){
							$num_rows_next = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$next_day'"));
						}
						else {
							$num_rows_next = 1;
						}
					}
					else {
						$num_rows_next = 1;
					}
				}
				do {
					if ($next_isHoliday_saturday == 1){
						$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day))));

						$month_next = date_format(date_create($next_day. ", " . $year),"F");
						$day_next = date_format(date_create($next_day. ", " . $year),"j");

						$holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');

						if ($holiday_day_type_next == "Saturday") {
							$next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day)))); // so friday na to
							$month_next = date_format(date_create($next_day. ", " . $year),"F");
							$day_next = date_format(date_create($next_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
						}


						if ($holiday_day_type_next == "Sunday") {
							$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day)))); // so friday na to
							$month_next = date_format(date_create($next_day. ", " . $year),"F");
							$day_next = date_format(date_create($next_day. ", " . $year),"j");

							//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
						}

						if ($this->dateIsHoliday($month_next,$day_next,$year) == 0){
							
					
							//echo "Holiday:" . $holiday . "Holiday Day Type: " .$holiday_day_type_next . "<br/>";

							//echo "Holiday: " . $holiday . " Type Day:" . $type_day_next;

							//echo "Holiday:" .$holiday . " Final Next day:". $next_day . "<br/>";
							$next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
							//echo "Holiday:" .$holiday . " Final Next day:". $next_day . "<br/>";
							//echo $emp_id;
							$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

							//echo $num_rows_leave . "<br/>";

							//echo $num_rows_leave;

							if ($num_rows_leave != 0) {
								

							$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
								if ($result_leave = mysqli_query($connect,$select_leave_qry)){
									while($row_leave = mysqli_fetch_object($result_leave)){

										$leaveRange = array();
									    $leaveFrom = strtotime($row_leave->dateFrom);
									    $leaveTo = strtotime($row_leave->dateTo);
									    $leave_output_format = 'Y-m-d';
									    $leave_step = '+1 day';

									    $leave_count = 0;
									    while($leaveFrom <= $leaveTo) {

								    		$leave_count++;
									        $leaveRange[] = date($leave_output_format, $leaveFrom);
									        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
									    }

									    $leave_counter = 0;

									    do {
									    	$leave_date = $leaveRange[$leave_counter];
									    	//echo $leave_date . "<br/>";

									    	//echo $leave_date;

									    	//echo $leave_date;
									    	// check na sakop siya ng date of payroll
									    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
									    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

									    		//echo $leave_date . "<br/>";

									    		//echo $leave_date . "<br/>";
									    		//echo $leave_date;

									    		if ($next_day == $leave_date){
									    			$next_exist_leave = 1;
									    		}
									    	//}
								   
									    	$leave_counter++;
									    } while($leave_counter < $leave_count);
								    }
							    }

						    }

						    if ($next_day <= $final_date_to){

							    if ($next_exist_leave != 1){
									$num_rows_next = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$next_day'"));
								}
								else {
									$num_rows_next = 1;
								}
							}

							else {
								$num_rows_next = 1;
							}
							//echo $num_rows_next . "<br/>";
							$next_isHoliday_saturday = 0;
						}
					}

				}while($next_isHoliday_saturday == 1);
			}

			//echo "Holiday:" . $holiday . "Holiday Day Type: " .$holiday_day_type_next . "<br/>";

			//echo "Holiday: " . $holiday . " Type Day:" . $type_day_next;
			else {
				$next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");

				//if ()

				//echo $emp_id;
				$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

				//echo $num_rows_leave . "<br/>";

				//echo $num_rows_leave;

				if ($num_rows_leave != 0) {
					

				$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
					if ($result_leave = mysqli_query($connect,$select_leave_qry)){
						while($row_leave = mysqli_fetch_object($result_leave)){

							$leaveRange = array();
						    $leaveFrom = strtotime($row_leave->dateFrom);
						    $leaveTo = strtotime($row_leave->dateTo);
						    $leave_output_format = 'Y-m-d';
						    $leave_step = '+1 day';

						    $leave_count = 0;
						    while($leaveFrom <= $leaveTo) {

					    		$leave_count++;
						        $leaveRange[] = date($leave_output_format, $leaveFrom);
						        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
						    }

						    $leave_counter = 0;

						    do {
						    	$leave_date = $leaveRange[$leave_counter];
						    	//echo $leave_date . "<br/>";

						    	//echo $leave_date;

						    	//echo $leave_date;
						    	// check na sakop siya ng date of payroll
						    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
						    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date . "<br/>";

						    		//echo $leave_date . "<br/>";
						    		//echo $leave_date;

						    		if ($next_day == $leave_date){
						    			$next_exist_leave = 1;
						    		}
						    	//}
					   
						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
					    }
				    }

			    }


			    if ($next_day <= $final_date_to){
				    if ($next_exist_leave != 1){
						$num_rows_next = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$next_day'"));
					}
					else {
						$num_rows_next = 1;
					}
				}

				else {
					$num_rows_next = 1;
				}
				//echo "wew";
			}
			//echo "Holiday:" .$holiday . " Final Next day:". $next_day . "<br/>";
			//echo $num_rows_next . "<br/>";
		}
		do {
			//echo $holiday . " " .$next_isHoliday . "<br/>";
			//echo $holiday . " next 1 <br/>";

			if ($next_isHoliday == 1){

				$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day))));

				$month_next = date_format(date_create($next_day. ", " . $year),"F");
				$day_next = date_format(date_create($next_day. ", " . $year),"j");

				//echo $month_next . " " .$day_next . " sad " .$next_isHoliday . "<br/>";
				//echo $next_day . "<br/>";

				$holiday_day_type_next = date_format(date_create($next_day. ", " . $year), 'l');

				//echo $holiday_day_type_next . "<br/>";

				if ($holiday_day_type_next == "Saturday") {
					$next_day = date('F d',(strtotime ( '+2 day' , strtotime ($next_day)))); // so friday na to
					$month_next = date_format(date_create($next_day. ", " . $year),"F");
					$day_next = date_format(date_create($next_day. ", " . $year),"j");

					//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
				}

				if ($holiday_day_type_next == "Sunday") {
					$next_day = date('F d',(strtotime ( '+1 day' , strtotime ($next_day)))); // so friday na to
					$month_next = date_format(date_create($next_day. ", " . $year),"F");
					$day_next = date_format(date_create($next_day. ", " . $year),"j");

					//$prev_isHoliday_monday = $this->dateIsHoliday($month_prev,$day_prev);	
				}

				if ($this->dateIsHoliday($month_next,$day_next,$year) == 0){
						
					//echo "Holiday:" . $holiday . "Holiday Day Type: " .$holiday_day_type_next . "<br/>";

					//echo "Holiday: " . $holiday . " Type Day:" . $type_day_next;

					//echo "Holiday:" .$holiday . " Final Next day:". $next_day . "<br/>";
					$next_day = date_format(date_create($next_day. ", " . $year),"Y-m-d");
					//echo "Holiday:" .$holiday . " Final Next day:". $next_day . "<br/>";
					//echo $emp_id;
					$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

					//echo $num_rows_leave . "<br/>";

					//echo $num_rows_leave;

					if ($num_rows_leave != 0) {
						

					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay' OR FileLeaveType = 'Leave without pay')";
						if ($result_leave = mysqli_query($connect,$select_leave_qry)){
							while($row_leave = mysqli_fetch_object($result_leave)){

								$leaveRange = array();
							    $leaveFrom = strtotime($row_leave->dateFrom);
							    $leaveTo = strtotime($row_leave->dateTo);
							    $leave_output_format = 'Y-m-d';
							    $leave_step = '+1 day';

							    $leave_count = 0;
							    while($leaveFrom <= $leaveTo) {

						    		$leave_count++;
							        $leaveRange[] = date($leave_output_format, $leaveFrom);
							        $leaveFrom = strtotime($leave_step, $leaveFrom);	       
							    }

							    $leave_counter = 0;

							    do {
							    	$leave_date = $leaveRange[$leave_counter];
							    	//echo $leave_date . "<br/>";

							    	//echo $leave_date;

							    	//echo $leave_date;
							    	// check na sakop siya ng date of payroll
							    	//if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
							    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

							    		//echo $leave_date . "<br/>";

							    		//echo $leave_date . "<br/>";
							    		//echo $leave_date;

							    		if ($next_day == $leave_date){
							    			$next_exist_leave = 1;
							    		}
							    	//}
						   
							    	$leave_counter++;
							    } while($leave_counter < $leave_count);
						    }
					    }

				    }


				    if ($next_day <= $final_date_to){


					    if ($next_exist_leave != 1){
							$num_rows_next = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$next_day'"));
						}
						else {
							$num_rows_next = 1;
						}
					}

					else {
						$num_rows_next = 1;
					}
					//echo $num_rows_next . "<br/>";
					$next_isHoliday = 0;
				}
			}

		}while($next_isHoliday == 1);

		//echo $holiday . " " .$next_day . " " . $num_rows_next . "<br/>";

		// ibig sabihin pumasok siya either sa dalawa

		//echo "Pumasok Before:" . $num_rows_prev . " Pumasok After:" . $num_rows_next . "<br/>";

		//$num_rows_next = 1; // to be aus after payroll
		//echo $holiday . " " .$num_rows_prev . " " . $num_rows_next . "<br/>";
		//$num_rows_prev = 1;
		if ($num_rows_prev == 1 && $num_rows_next == 1){
			$granted =  "Granted";
		}

		else {
			$granted  = "Not Granted";
		}
		

		return $granted;

		
		//$holiday_date = 

		//echo "Holiday: " . $holiday . " Prev day month:" . $month_prev . " Prev day day:" . $day_prev . " Next day month:" . $month_next . " Next day day:" . $day_next .  "<br/>";
		


		/*
		$date_affected_holiday_values = "";
		$select_qry = "SELECT * FROM tb_holiday";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$holiday_date = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");
				// if sakop
				if ($holiday_date >= $final_date_from && $holiday_date <= $final_date_to){
					//echo "<li><b>" .date_format(date_create($row->holiday_date. ", " . $year),"F d, Y") ."</b> - <span style='color:#317eac;'>" . $row->holiday_value . "</span> (<i>". date_format(date_create($row->holiday_date. ", " . $year),"l") . "</i>)</li>";
					//echo date_format(date_create($row->holiday_date. ", " . $year),"F d, Y");
					
					// echo dito ung mga date na dapat pumasok siya ilalagay sa isang array tapos decrypt na lang
					 $holiday = date_format(date_create($row->holiday_date. ", " . $year),"Y-m-d");

					 $prev_day_1 = date('F d',(strtotime ( '-1 day' , strtotime ($holiday))));
					 $next_day_1 = date('F d',(strtotime ( '+1 day' , strtotime ($holiday))));

					 //echo $prev_day_1 . "<br/>";

					 //$holiday_date = $month . " " . $day;

					 $num_rows_prev_holiday_1 = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$prev_day_1'"));
					 $num_rows_next_holiday_1 = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$next_day_1'"));

					 if ($num_rows_prev_holiday_1 != 1){

					 	// for checking if the holiday 
					 	 $prev_day_2 = date('F d',(strtotime ( '-1 day' , strtotime ($prev_day_1))));
					 	 $num_rows_prev_holiday_2 = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$prev_day_2'"));
					 
					 	 if ($num_rows_prev_holiday_2 != 1){

				 	 		echo $prev_day_1 . " prev day<br/>";

					 	 	/*if ($date_affected_holiday_values == ""){
					 	 		$date_affected_holiday_values = $prev_day_1;
					 	 	}
					 	 	else {
					 	 		$date_affected_holiday_values = $date_affected_holiday_values + "#" + $prev_day_1;
					 	 	}
					 	 }
					 }



					 if ($num_rows_next_holiday_1 != 1){

					 	// for checking if the holiday 
					 	 $next_day_2 = date('F d',(strtotime ( '+1 day' , strtotime ($next_day_1))));
					 	 	
					 	 echo $next_day_2 . "next day 2<br/>";

					 	 $num_rows_next_holiday_2 = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$next_day_2'"));					 	
					 	
					 	 if ($num_rows_next_holiday_2 != 1){

				 	 		echo $next_day_1 . " nextday<br/>";
					 	 	/*
					 	 	if ($date_affected_holiday_values == ""){
					 	 		$date_affected_holiday_values = $next_day_1;
					 	 	}
					 	 	else {
					 	 		$date_affected_holiday_values = $date_affected_holiday_values + "#" + $next_day_1;
					 	 	}
					 	 }

					 }

				}
			}
		}*/


		//echo $date_affected_holiday_values . "<br/>";
	}

	

	// for getting attendance overtime of restday holiday ot
		public function getIncentives($bio_id,$daily_rate){
			$connect = $this->connect();

			$bio_id = mysqli_real_escape_string($connect,$bio_id);
			$daily_rate = mysqli_real_escape_string($connect,$daily_rate);


			$late = 0;
			$undertime = 0;

			$incentives = 0;

			
			if ($bio_id != 0){


				// select employee for getting working_id
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);


				$dept_id = $row_emp->dept_id;

				$working_id = $row_emp->working_hours_id; // bali eto ung working id nya

				// select working hours for getting time in time out
				$select_workingHours_qry = "SELECT * FROM tb_working_hours WHERE working_hours_id = '$working_id'";
				$result_workingHours = mysqli_query($connect,$select_workingHours_qry);
				$row_workingHours = mysqli_fetch_object($result_workingHours);

				// for time in time out
				$timeIn = $row_workingHours->timeFrom;
				$timeOut = $row_workingHours->timeTo;

		
				$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id'";
				if ($result = mysqli_query($connect,$select_qry)){
					while($row = mysqli_fetch_object($result)){
							

							$date_create = date_create($row->date);
							$date_format = date_format($date_create, 'F d, Y');



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


							$date_create = date_create($row->date);
							$date_format = date_format($date_create, 'F d, Y');

							$attendance_date = date_format(date_create($row->date),"Y-m-d");

							// if sakop ng date
							if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){

								$time_in = substr($row->time_in,0,6) . "00";

								$time_out = substr($row->time_out,0,6) . "00";

								//echo $time_in . "<br/>";

								$date_create = date_create($row->date);
								$day = date_format($date_create,"l");

								//echo $attendance_date ."<br/>";

								$num_rows_half_day = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'"));

								//echo $num_rows_half_day;

								$period_type = "";
								if ($num_rows_half_day != 0){
									$select_half_day = "SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'";
									$result_half_day = mysqli_query($connect,$select_half_day);
									$row_half_day = mysqli_fetch_object($result_half_day);

									$halfdayType = $row_half_day->FileLeaveType;

									// check kung anong period if morning or afternoon
									$period_type = substr($halfdayType,0,-23);
									//echo $period_type;
									
								}


								//echo $num_rows_half_day . " " . $attendance_date . "<br/>";

								// for checking ung total leave count nya
								//$num_rows_half_day = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND FileLeaveType = 'File Halfday Leave' AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date'"));


								//echo ."<br/>";

								// check natin kung ung day ng date na un is weekends so d nya to ipeperform

								$holiday = date_format(date_create($attendance_date), 'F j'); // Day of the month without leading zeros  	1 to 31

								$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));




								$late_in_monday = 0;
								$undertime_in_monday = 0;
								$late_in_friday = 0;
								$undertime_in_frinday = 0;

								if ($day != "Saturday" && $day != "Sunday" && $holiday_num_rows == 0 && $attendance_date < "2020-06-22") {



						

									$grace_period = date("H:i:s",strtotime("+15 minutes",strtotime($timeIn)));

									//echo $row->time_in . " " .$grace_period . "<br/>";


									//echo $dept_id . " " . $row->time_in . "<br/>";

									$morning_late = 0;

									if ($dept_id == 1000 && $row->time_in <= "11:00:00"){ // for no exception dapat
										$late += 0;
									}

									else {

										//echo $row->time_in . "<br/>";
										// eto ay para sa grace period na 5 minutes
										if ($row->time_in > $grace_period || $row->time_in == "00:00:00"){

											

											if ($late == 0){

												//if ($row->time_out == "00:00:00"){

												//}

												// checking if wlang time out
												if ($row->time_in == "00:00:00"){

													//if ($row->time_out == "00:00:00") {
														//$late = strtotime($timeOut) - strtotime($timeIn) - 3600; // ung 3600 katumbas niyan isang oras
													//$late = 16200; // halfday automatic
													//}
													//if ($row->time_in == ) {

													//}
													//echo $row->date;
													//echo $late;
													//echo $late;
													//echo "wew";

													//if ($row->time_out == "00:00:00") {
													//echo $time_in;
													$morning_late = 16200; // + (strtotime($time_in) - strtotime("08:30:00"))
													//}


													//echo "wew"
													//echo $num_rows_half_day . " " . $period_type;

													if ($num_rows_half_day == 1 && $period_type == "Morning"){

														//echo "Wew" . "<br/>";
														$morning_late = $morning_late - (270 * 60);
														if ($morning_late <0){
															$morning_late = 0;
														}

													}

													$late = $morning_late;

													//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

													//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;
												}

												else {

													// check tardy in half day from 8:30 - 12:00
													if ($row->time_in > $timeIn) {
													//	echo "wew";
														//$late = strtotime($time_in) - strtotime("08:30:00"); // so first late

														// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
														if ($row->time_in <= "12:00:00"){
															//echo "wew";
															$morning_late = (strtotime($time_in) - strtotime($timeIn));


															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";

															//echo $num_rows_half_day . " " . $period_type;

															if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//	echo "Wew" . "<br/>";

																$morning_late = $morning_late - (270*60);
																if ($morning_late <0){
																	$morning_late = 0;
																}

															}

															$late = $morning_late;

															//echo "This is late " . ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";



															//if ($period_type =) // dito ako huminto sa logic na to
														}

														// ibig sabihin half day siya sa afternoon siya pumasok
														else if ($row->time_in >= "13:00:00"){
															$morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn);

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";
															//echo $num_rows_half_day . " " . $period_type;
															//echo  $attendance_date . "<br/>";

															// if has half day leave
															if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//	echo "Wew" . "<br/>";

																$morning_late = $morning_late - (270*60);
																if ($morning_late <0){
																	$morning_late = 0;
																}

															}

															// "morning late=" . $morning_late . "<br/>";


															$late = $morning_late;

															//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";
															
														}		

														else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
															$morning_late = strtotime("12:00:00") - strtotime($timeIn);

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";

															//echo  $attendance_date . "<br/>";
															//echo $num_rows_half_day . " " . $period_type;

															if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//	echo "Wew" . "<br/>";


																$morning_late = $morning_late - (270*60);
																if ($morning_late <0){
																	$morning_late = 0;
																}

															}

															$late = $morning_late; // so first late

															//echo ($late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";
															
														//	echo $late / 60;
														}

														//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;


														//if ()

														//echo $time_in;

														//echo $late / 60 . "<br/>";

														//echo $late;
														//echo $row->time_in;
														//echo "wew1";

													}

													/*// check tardy in half day from 1:00 - 6:30
													if ($row->time_in >= "13:00:00") {
														$late = (strtotime($time_in) - strtotime("13:00:00")) + 190;

														//echo "wew2";
														//echo $row->time_in . " " .$late;
														//echo 	
													}

													// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
													if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
														$late = strtotime("12:00:00") - strtotime($timeIn); // so first late
														//echo "wew3";
													//	echo $late / 60;
													}*/

												}

												//echo $late . "<br/>";

											} //end of if late == 0

											else {




												//echo "wew";

												//echo $attendance_date . "<br/>";
												//if ($row->time_out == "00:00:00"){
													
												//}

												// checking if wlang time out
												if ($row->time_in == "00:00:00"){
													//echo $row->time_in . "<br/>";
													//$late = strtotime("17:30:00") - strtotime("08:30:00");


													//$late = $late + 16200; // halfday automatic
													//}
													//if ($row->time_in == ) {

													//}
													//echo $row->date;
													//echo $late;
													//echo $late;
													//echo "wew";

													//if ($row->time_out == "00:00:00") {
													$morning_late = 16200; // + (strtotime($time_in) - strtotime("08:30:00"))


													//$late = $late + strtotime($timeOut) - strtotime($timeIn) - 3600;

													//echo $num_rows_half_day . " " . $period_type;


													if ($num_rows_half_day == 1 && $period_type == "Morning"){

														$morning_late = $morning_late - (270*60);
														if ($morning_late <0){
															$morning_late = 0;
														}

													}

													$late = $late + $morning_late;

													//echo $late . "<br/>";

													//echo $late;
													//echo "wew";

													//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;
												}

												else {

													//echo $attendance_date . "<br/>";

													// late 
													if ($row->time_in > $timeIn) {
																								
														// ibig sabihin nag in siya before lunch time, d siya half day sa afternonn
														if ($row->time_in <= "12:00:00"){
															
															$morning_late = (strtotime($time_in) - strtotime($timeIn));

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";
															//echo $num_rows_half_day . " " . $period_type;

															if ($num_rows_half_day == 1 && $period_type == "Morning"){

															//	echo "Wew" . "<br/>";

																$morning_late = $morning_late - (270*60);

																//echo $late . " " . $attendance_date  . "<br/>";
																if ($morning_late < 0){
																	$morning_late = 0;
																}

															}

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

															$late = $late + $morning_late;

															//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

															//echo $late . "<br/>";

															
														}

														// ibig sabihin half day siya sa afternoon siya pumasok
														else if ($row->time_in >= "13:00:00"){
															$morning_late = (strtotime($time_in) - strtotime("13:00:00")) + strtotime("12:00:00") - strtotime($timeIn); // 45
														//	echo $morning_late . " " . $attendance_date ."<br/>";

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_in . "<br/>";


															//echo  $attendance_date . "<br/>";
															//echo $num_rows_half_day . " " . $period_type;

															if ($num_rows_half_day == 1 && $period_type == "Morning"){

																//echo "Wew" . "<br/>";

																$morning_late = $morning_late - (270*60);

																//echo $morning_late . "<br/>";

																if ($morning_late <0){
																	$morning_late = 0;
																}

															}

															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

															$late = $late + $morning_late;

															//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

															
														}		

														else if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
															$morning_late = strtotime("12:00:00") - strtotime($timeIn);

															

															//echo  $attendance_date . "<br/>";

															//echo $num_rows_half_day . " " . $period_type;


															if ($num_rows_half_day == 1 && $period_type == "Morning"){

																//echo "Wew" . "<br/>";

																$morning_late = $morning_late - (270*60);
																if ($morning_late <0){
																	$morning_late = 0;
																}

															}

															//echo "morning late=" . $morning_late . "<br/>";
															//echo ($morning_late / 60) . " " . $attendance_date . " " . $row->time_in . " " . $row->time_out . "<br/>";

															$late = $morning_late + $late;; // so first late


															//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

															
														//	echo $late / 60;
														}								

													}

													//echo ($late / 60) . " "  . $attendance_date . "<br/>" ;

													// check tardy in half day from 1:00 - 6:30
													//if ($row->time_in >= "13:00:00") {
														//$late = (strtotime($time_in) - strtotime("12:00:00")) + $late + 190;

														


														//echo 3600
														//echo strtotime($time_in) - strtotime("12:00:00");
														//echo $late ."<br/>";
														//echo "wew";
														
													//}

													// check if pumasok ng mas maaga sa 13:00:00 or 1 pm
													//if ($row->time_in >= "12:00:00" && $row->time_in <= "13:00:00" ) {
													//	$late = strtotime("12:00:00") - strtotime($timeIn) + $late;; // so first late
													//	echo $late / 60;
													//}
												}

											} //end of else

											//echo $morning_late . "<br/>";


											// if has leave
													//echo $num_rows_half_day . "<br/>";


										} // end of if for grace period
									} // end of else na may late


									if ($morning_late != 0){
										$late_in_monday = 1;
									}
										
									// if undertime


									$afternoon_late = 0;

									if ($undertime == 0) {
										// check if emp has undertime to the current day
										//if ($row->time_in == "00:00:00"){

										//}

										 if ($row->time_out == "00:00:00"){
											//$afternoon_late = 16200;

											$afternoon_late = 16200; // + strtotime($timeOut) - strtotime($time_out)

											if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

													$afternoon_late = $afternoon_late - (270*60);
													if ($afternoon_late <0){
														$afternoon_late = 0;
													}
											}

											$undertime = $afternoon_late;


											//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;
										}

										else {





											if ($row->time_out < $timeOut){

												

												$afternoon_late = strtotime($timeOut) - strtotime($time_out);

												

												//echo $period_type . "<br/>";

												if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

													$afternoon_late = $afternoon_late - (270*60);
													if ($afternoon_late <0){
														$afternoon_late = 0;
													}
												}

												if ($row_emp->emp_id == 5){
													//echo "wew";
													if ($row->time_out >= "18:30:00"){
														//echo "wew";
														//$afternoon_late += 0;
														$undertime += 0;

													}
													else {
														$undertime = $undertime + $afternoon_late;
													}
												}

												// 148
												else if ($row_emp->emp_id == 148){
													if ($row->time_out >= "21:00:00"){
														//echo "wew";
														//$afternoon_late += 0;
														$undertime += 0;

													}
													else {
														$undertime = $undertime + $afternoon_late;
													}
												}


												else {

													$undertime = $undertime + $afternoon_late;
												}

												//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;

											//	echo $undertime . "<br/>";

												//echo ($undertime / 60) . " " . $attendance_date . "<br/>"; 

												//echo $morning_late . " " . $attendance_date .  "<br/>";

												/*if ($num_rows_half_day == 1){
													$morning_late = $morning_late - (270*60);
													if ($morning_late <0){
														$morning_late = 0;
													}

												}*/


												//echo ($undertime / 60) . "<br/>";
												//echo "wew1";
												//echo $undertime . "<br/>";
											}
										}

										//echo $attendance_date . " " . $undertime . "<br/>";
									}

									else {
										//if ($row->time_in == "00:00:00"){

										//}

										if ($row->time_out == "00:00:00"){
											//$afternoon_late = 16200;

											$afternoon_late = 16200; // strtotime($timeOut) - strtotime($time_out)

											if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

													$afternoon_late = $afternoon_late - (270*60);
													if ($afternoon_late <0){
														$afternoon_late = 0;
													}
											}

											$undertime = $undertime + $afternoon_late;
											//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;
										}

										else {
											if ($row->time_out < $timeOut){
												//$undertime = strtotime("18:30:00") - strtotime($time_out);
												$afternoon_late = strtotime($timeOut) - strtotime($time_out);

												
												if ($num_rows_half_day == 1 && $period_type == "Afternoon"){

													$afternoon_late = $afternoon_late - (270*60);
													if ($afternoon_late <0){
														$afternoon_late = 0;
													}
												}

												if ($row_emp->emp_id == 5){
													//echo "wew";
													if ($row->time_out >= "18:30:00"){
														//echo "wew";
														//$afternoon_late += 0;
														$undertime += 0;

													}
													else {
														$undertime = $undertime + $afternoon_late;
													}
												}


												// 148
												else if ($row_emp->emp_id == 148){
													if ($row->time_out >= "21:00:00"){
														//echo "wew";
														//$afternoon_late += 0;
														$undertime += 0;

													}
													else {
														$undertime = $undertime + $afternoon_late;
													}
												}

												else {

													$undertime = $undertime + $afternoon_late;
												}

												//echo ($undertime / 60) . " "  . $attendance_date . "<br/>" ;

											//	echo $undertime . "<br/>";
												//echo "wew2";
											}
										}
										//$undertime = strtotime("18:30:00") - strtotime($time_out) + $undertime;
									}


									if ($afternoon_late != 0){
										$undertime_in_monday = 1;
									}



									if ($day == "Monday"){

											
										//echo $morning_late . " " . $afternoon_late . "Monday " . $attendance_date . "<br/>";

										if ($morning_late == 0 && $afternoon_late == 0){

											$adjustment = $daily_rate * .10;


											//echo "Monday " . $attendance_date  . " "  . $adjustment . " " . $late_in_monday . " " . $undertime_in_monday . "<br/>";

											$incentives += $adjustment;

										}
									}


									if ($day == "Friday"){


										//echo $morning_late . " " . $afternoon_late . "Friday " . $attendance_date . "<br/>";
										

										if ($morning_late == 0 && $afternoon_late == 0){

											$adjustment = $daily_rate * .05;

											//echo "Friday " . $attendance_date . " " . $adjustment . " " . $late_in_friday . " " . $undertime_in_frinday . "<br/>";

											$incentives += $adjustment;

										}
									}


									//echo $attendance_date . " " . $undertime . "<br/>";
											
								
								}	// end of if for weekends na pumasok counter as ot kasi d nya normal days

								//echo $attendance_date . "<br/>";
								


							
						} // end of if ng sakop ng cut off
						//echo $late . "<br/>";

					} //end of while
				} //end of if result
			} // end of bio_id > 0

			//reutn $undertime;
			//echo $undertime . " s ";
			//echo $late;
			//return (($late + $undertime) / 60);
			//echo $undertime;
			//return $late / 60;
			//return $undertime + ($late/60);
			//echo ($late / 3600)  . "<br/>";
			//echo ($undertime / 3600) . "<br/>";
			//echo (($late + $undertime) / 60) . "<br/>";

			//echo $undertime / 60;
			//return ($late + $undertime) / 60;
			return $incentives;

		}



		public function getTardinessToRunningBalance($emp_id,$bio_id,$timeFrom,$timeTo,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);	

			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);
			$dept_id = $row_emp->dept_id;


			$tardiness = 0;

			// for time in time out
			$timeIn = $timeFrom;
			$timeOut = $timeTo;

			//echo $hourly_rate . " " . $daily_rate . "<br/>";


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


					//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
					$minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
					//$minus_five_day = date("Y-m-d");
					//echo $minus_five_day;
					//echo $minus_five_day;
					//echo $minus_five_day;

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			
			//echo $hourly_rate . " ";
			//echo $daily_rate . " ";


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


		    $timeFromPlus30mins = date("H:i:s", strtotime('+30 minutes', strtotime($timeIn)));
		    $timeFromPlus60mins = date("H:i:s", strtotime('+60 minutes', strtotime($timeIn)));

		    //echo $timeFromPlus30mins . " " . $timeFromPlus60mins;

		    
		    $lunch_break_from = "12:00:00";
		    $lunch_break_to = "13:00:00";


		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'w'); // 


		    	if ($day >= $day_from && $day <= $day_to){

		    		//echo $day . " " . $day_from . "<br/>";

		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 
		    				
		    		$weekdays_count++; // for echo condition

		    		if ($date < date("Y-m-d")){

		    			

			    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
			    		if ($num_rows == 1) {

			    			
			    				
			    			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'";
			    			$result = mysqli_query($connect,$select_qry);
			    			$row = mysqli_fetch_object($result);

			    			//echo $row->time_in . " " . $row->time_out . "<br/>";

			    			/*if ($row->time_out == "00:00:00"){
			    				
			    			}*/
			    			$db_time_in = substr($row->time_in,0,6) . "00";

							$db_time_out = substr($row->time_out,0,6) . "00";


							

								

								if ($row->time_out == "00:00:00"){

									if ($row->time_in >= $timeOut){ // 18:40:00 
										$db_time_in = $lunch_break_to;
										$db_time_out = $row->time_in;
									}

									else {
										$db_time_out = $lunch_break_to;
									}
								}


								if ($row->time_in >= $timeOut){ // 18:40:00 

									$db_time_in = $lunch_break_to;
								}




								//echo $db_time_in . " " . $db_time_out . "<br/>";






		    				//if ($row->time_out != "00:00:00"){

				    			// FOR LATE
				    			if ($db_time_in > $timeIn){
				    				//echo "LATE";

				    				// if for per minute late
				    				//echo $timeFromPlus30mins . " " . $db_time_in . " ";
				    				if ($timeFromPlus30mins > $db_time_in){
				    					$date1 = strtotime($db_time_in);  
										$date2 = strtotime($timeIn);  
										  
										// Formulate the Difference between two dates 
										$diff = abs($date1 - $date2) / 60; // to compute per minute

										$diff = ($diff / 60); // to compute per hour equivalent

										//echo $diff . " ";

										if ($dept_id == 1){


										}

										else {

											$tardiness += round($diff * $hourly_rate,2);
										}

										//echo $tardiness . " ";



				    				}	

				    				// for 20 %
				    				else if ($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
				    					//echo "wew";

				    					if ($dept_id == 1){


										}
										else {
				    						$tardiness += round($daily_rate * .25,2);
				    					}

				    					//echo $tardiness . " ";
		

				    					//echo round($daily_rate - ($daily_rate * .25),2);
				    				}


				    				// for 50 %
				    				else if ($timeFromPlus60mins <= $db_time_in){


				    					if ($dept_id == 1){

				    					 	if ($db_time_in > "11:00:00"){


				    							$tardiness += round($daily_rate * .50,2);
				    						}


				    					}

				    					else {


					    					//echo $daily_rate . " ";
					    					$tardiness += round($daily_rate * .50,2);
				    					}
				    					//echo $tardiness . "<br/>";
				    				}
			    				}


			    				if ($db_time_out < $timeOut){

				    				// FOR UNDERTIME
				    				if ($timeFromPlus30mins > $db_time_in){
				    					$date1 = strtotime($db_time_out);  // 12:00:00
										$date2 = strtotime($timeOut);  //18:30:00

										if ($db_time_out < $timeOut){
										  
											// Formulate the Difference between two dates 
											$diff = abs($date1 - $date2) / 60; // to compute per minute // 

											$diff = ($diff / 60); // to compute per hour equivalent

											//echo $diff . " ";

											$tardiness += round($diff * $hourly_rate,2);

											//echo $tardiness . " ";
										}



				    				}	

				    				// for 20 %
				    				else if ($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
				    					//echo "wew";
				    					$date1 = strtotime($db_time_out);  // 12:00:00
										$date2 = strtotime($timeOut);  //18:30:00
										  	
									    if ($dept_id == 1){
									    	// Formulate the Difference between two dates 
											$diff = abs($date1 - $date2) / 60; // to compute per minute // 

											$diff = ($diff / 60); // to compute per hour equivalent

											//echo $diff . " ";

											$tardiness += round($diff * $hourly_rate,2);
										}

										else {


											if ($db_time_out < $timeOut){

												// Formulate the Difference between two dates 
												$diff = abs($date1 - $date2) / 60; // to compute per minute

												// /echo $diff . " ";

												$diff = ($diff / 60); // to compute per hour equivalent

												//echo round($daily_rate - ($daily_rate * .25),2) . "<br/>";

												//echo $total_hours - .5 . "<br/>";

												//echo round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2) . "<br/>";

												//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";


												$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2);

						    					//$tardiness += round($diff*(($daily_rate - ($daily_rate * .25)) / ($total_hours - .5)),2);

						    					//echo $tardiness . " ";
												

						    					//echo round($daily_rate - ($daily_rate * .25),2);
					    					}
				    					}
				    				}


				    				// for 50 %
				    				else if ($timeFromPlus60mins <= $db_time_in){

				    					$date1 = strtotime($db_time_out);  // 12:00:00
										$date2 = strtotime($timeOut);  //18:30:00


										if ($dept_id == 1){
											if ($db_time_in > "11:00:00"){
												// Formulate the Difference between two dates 
												$diff = abs($date1 - $date2) / 60; // to compute per minute

												$diff = ($diff / 60); // to compute per hour equivalent
												//echo $diff . "<br/>";

												//echo round($daily_rate - ($daily_rate * .5),2) . "<br/>";

												//echo $total_hours - 1 . "<br/>";

												//echo round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2) . "<br/>";

												//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";

												//echo round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2) . "<br/>";

												$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
											}

											else {
												$diff = abs($date1 - $date2) / 60; // to compute per minute // 

												$diff = ($diff / 60); // to compute per hour equivalent

												//echo $diff . " ";

												$tardiness += round($diff * $hourly_rate,2);
											}
										}

										else {

											if ($db_time_out < $timeOut){
											  
												// Formulate the Difference between two dates 
												$diff = abs($date1 - $date2) / 60; // to compute per minute

												$diff = ($diff / 60); // to compute per hour equivalent
												//echo $diff . "<br/>";

												//echo round($daily_rate - ($daily_rate * .5),2) . "<br/>";

												//echo $total_hours - 1 . "<br/>";

												//echo round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2) . "<br/>";

												//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";

												//echo round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2) . "<br/>";

												$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
										}
										}
				    				
				    				}
			    				}


				    			
				    		//}



			    			// for undertime


 
			    		}		
			    		
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	
		    }while($counter <= $count);
	
			
	    	return $tardiness;
		}


		public function getTardinessLatest($emp_id,$bio_id,$timeFrom,$timeTo,$day_from,$day_to,$hourly_rate,$daily_rate,$total_hours){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);	

			$select_emp_qry = "SELECT * FROM tb_employee_info WHERE bio_id = '$bio_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);
			$dept_id = $row_emp->dept_id;


			$tardiness = 0;

			// for time in time out
			$timeIn = $timeFrom;
			$timeOut = $timeTo;

			//echo $hourly_rate . " " . $daily_rate . "<br/>";


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
					//$minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
					//$minus_five_day = date("Y-m-d");
					//echo $minus_five_day;
					//echo $minus_five_day;
					//echo $minus_five_day;

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			
			//echo $hourly_rate . " ";
			//echo $daily_rate . " ";


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


		    $timeFromPlus30mins = date("H:i:s", strtotime('+30 minutes', strtotime($timeIn)));
		    $timeFromPlus60mins = date("H:i:s", strtotime('+60 minutes', strtotime($timeIn)));

		    //echo $timeFromPlus30mins . " " . $timeFromPlus60mins;

		    
		    $lunch_break_from = "12:00:00";
		    $lunch_break_to = "13:00:00";


		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'w'); // 



		    	if ($day >= $day_from && $day <= $day_to){

		    		//echo $day . " " . $day_from . "<br/>";

		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 

		    		$attendance_date = date_format(date_create($date),"Y-m-d");
		    				
		    		$weekdays_count++; // for echo condition

		    		if ($date < date("Y-m-d")){

		    			

			    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));


			    		$holiday = date_format(date_create($attendance_date), 'F j'); // Day of the month without leading zeros  	1 to 31

						$holiday_num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_holiday WHERE holiday_date = '$holiday'"));

			    		if ($num_rows == 1 && $holiday_num_rows == 0) {

			    			
			    				
			    			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'";
			    			$result = mysqli_query($connect,$select_qry);
			    			$row = mysqli_fetch_object($result);

			    			//echo $row->time_in . " " . $row->time_out . "<br/>";

			    			/*if ($row->time_out == "00:00:00"){
			    				
			    			}*/
			    			$db_time_in = substr($row->time_in,0,6) . "00";

							$db_time_out = substr($row->time_out,0,6) . "00";


													

							if ($row->time_out == "00:00:00"){

								if ($row->time_in >= $timeOut){ // 18:40:00 
									$db_time_in = $lunch_break_to;
									$db_time_out = $row->time_in;
								}

								else {
									$db_time_out = $lunch_break_to;
								}
							}


							if ($row->time_in >= $timeOut){ // 18:40:00 

								$db_time_in = $lunch_break_to;
							}




								//echo $db_time_in . " " . $db_time_out . "<br/>";


							$num_rows_half_day = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'"));

							//echo $num_rows_half_day;

							$period_type = "";
							if ($num_rows_half_day != 0){
								$select_half_day = "SELECT * FROM tb_leave WHERE emp_id='$row_emp->emp_id' AND (FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND dateFrom = '$attendance_date' AND dateTo = '$attendance_date' AND approveStat = '1'";
								$result_half_day = mysqli_query($connect,$select_half_day);
								$row_half_day = mysqli_fetch_object($result_half_day);

								$halfdayType = $row_half_day->FileLeaveType;

								// check kung anong period if morning or afternoon
								$period_type = substr($halfdayType,0,-23);
								//echo $period_type;
								
							}




		    				//if ($row->time_out != "00:00:00"){

				    			// FOR LATE
				    			if ($db_time_in > $timeIn){
				    				//echo "LATE";

				    				if ($period_type == "Morning"){

				    				}
				    				else {

					    				// if for per minute late
					    				//echo $timeFromPlus30mins . " " . $db_time_in . " ";
					    				if ($timeFromPlus30mins > $db_time_in){
					    					$date1 = strtotime($db_time_in);  
											$date2 = strtotime($timeIn);  
											  
											// Formulate the Difference between two dates 
											$diff = abs($date1 - $date2) / 60; // to compute per minute

											$diff = ($diff / 60); // to compute per hour equivalent

											//echo $diff . " ";

											if ($dept_id == 1){


											}

											else {

												$tardiness += round($diff * $hourly_rate,2);
											}

											//echo $tardiness . " ";



					    				}	

					    				// for 20 %
					    				else if ($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
					    					//echo "wew";

					    					if ($dept_id == 1){


											}
											else {
					    						$tardiness += round($daily_rate * .25,2);
					    					}

					    					//echo $tardiness . " ";
			

					    					//echo round($daily_rate - ($daily_rate * .25),2);
					    				}


					    				// for 50 %
					    				else if ($timeFromPlus60mins <= $db_time_in){


					    					if ($dept_id == 1){

					    					 	if ($db_time_in > "11:00:00"){


					    							$tardiness += round($daily_rate * .50,2);
					    						}


					    					}

					    					else {


						    					//echo $daily_rate . " ";
						    					$tardiness += round($daily_rate * .50,2);
					    					}
					    					//echo $tardiness . "<br/>";
					    				}
				    				}
			    				}


			    				if ($db_time_out < $timeOut){

			    					if ($period_type == "Afternoon"){

				    				}
				    				else {

					    				// FOR UNDERTIME
					    				if ($timeFromPlus30mins > $db_time_in){
					    					$date1 = strtotime($db_time_out);  // 12:00:00
											$date2 = strtotime($timeOut);  //18:30:00

											if ($db_time_out < $timeOut){
											  
												// Formulate the Difference between two dates 
												$diff = abs($date1 - $date2) / 60; // to compute per minute // 

												$diff = ($diff / 60); // to compute per hour equivalent

												//echo $diff . " ";

												$tardiness += round($diff * $hourly_rate,2);

												//echo $tardiness . " ";
											}



					    				}	

					    				// for 20 %
					    				else if ($timeFromPlus30mins <= $db_time_in && $timeFromPlus60mins > $db_time_in){
					    					//echo "wew";
					    					$date1 = strtotime($db_time_out);  // 12:00:00
											$date2 = strtotime($timeOut);  //18:30:00
											  	
										    if ($dept_id == 1){
										    	// Formulate the Difference between two dates 
												$diff = abs($date1 - $date2) / 60; // to compute per minute // 

												$diff = ($diff / 60); // to compute per hour equivalent

												//echo $diff . " ";

												$tardiness += round($diff * $hourly_rate,2);
											}

											else {


												if ($db_time_out < $timeOut){

													// Formulate the Difference between two dates 
													$diff = abs($date1 - $date2) / 60; // to compute per minute

													// /echo $diff . " ";

													$diff = ($diff / 60); // to compute per hour equivalent

													//echo round($daily_rate - ($daily_rate * .25),2) . "<br/>";

													//echo $total_hours - .5 . "<br/>";

													//echo round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2) . "<br/>";

													//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";


													$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2);

							    					//$tardiness += round($diff*(($daily_rate - ($daily_rate * .25)) / ($total_hours - .5)),2);

							    					//echo $tardiness . " ";
													

							    					//echo round($daily_rate - ($daily_rate * .25),2);
						    					}
					    					}
					    				}


					    				// for 50 %
					    				else if ($timeFromPlus60mins <= $db_time_in){

					    					$date1 = strtotime($db_time_out);  // 12:00:00
											$date2 = strtotime($timeOut);  //18:30:00


											if ($dept_id == 1){
												if ($db_time_in > "11:00:00"){
													// Formulate the Difference between two dates 
													$diff = abs($date1 - $date2) / 60; // to compute per minute

													$diff = ($diff / 60); // to compute per hour equivalent
													//echo $diff . "<br/>";

													//echo round($daily_rate - ($daily_rate * .5),2) . "<br/>";

													//echo $total_hours - 1 . "<br/>";

													//echo round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2) . "<br/>";

													//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";

													//echo round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2) . "<br/>";

													$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
												}

												else {
													$diff = abs($date1 - $date2) / 60; // to compute per minute // 

													$diff = ($diff / 60); // to compute per hour equivalent

													//echo $diff . " ";

													$tardiness += round($diff * $hourly_rate,2);
												}
											}

											else {

												if ($db_time_out < $timeOut){
												  
													// Formulate the Difference between two dates 
													$diff = abs($date1 - $date2) / 60; // to compute per minute

													$diff = ($diff / 60); // to compute per hour equivalent
													//echo $diff . "<br/>";

													//echo round($daily_rate - ($daily_rate * .5),2) . "<br/>";

													//echo $total_hours - 1 . "<br/>";

													//echo round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2) . "<br/>";

													//echo round($diff *(round(round($daily_rate - ($daily_rate * .25),2) / ($total_hours - .5),2)),2) . " ";

													//echo round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2) . "<br/>";

													$tardiness += round($diff *(round(round($daily_rate - ($daily_rate * .5),2) / ($total_hours - 1),2)),2);
											}
											}
					    				
					    				}
				    				}
			    				}


				    			
				    		//}



			    			// for undertime


 
			    		}		
			    		
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	
		    }while($counter <= $count);
	
			
	    	return $tardiness;
		}


	public function getIncentivesToRunningBalance($emp_id,$bio_id,$timeFrom,$timeTo,$day_from,$day_to,$daily_rate){
			$connect = $this->connect();

			$emp_id = mysqli_real_escape_string($connect,$emp_id);
			$bio_id = mysqli_real_escape_string($connect,$bio_id);

			$incentives = 0;

			// for time in time out
			$timeIn = $timeFrom;
			$timeOut = $timeTo;

			//echo $hourly_rate . " " . $daily_rate . "<br/>";


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


					//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
					$minus_five_day = date('Y-m-d',(strtotime ( '-1 day' , strtotime (date("Y-m-d")) ) ));
					//$minus_five_day = date("Y-m-d");
					//echo $minus_five_day;
					//echo $minus_five_day;
					//echo $minus_five_day;

					
					if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
						$final_date_from = $date_from;
						$final_date_to = $date_to;
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


			//echo $final_date_from . "<br/>";
			//echo $final_date_to. "<br/>";

			
			//echo $hourly_rate . " ";
			//echo $daily_rate . " ";


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


		    $timeFromPlus30mins = date("H:i:s", strtotime('+30 minutes', strtotime($timeIn)));
		    $timeFromPlus60mins = date("H:i:s", strtotime('+60 minutes', strtotime($timeIn)));

		    //echo $timeFromPlus30mins . " " . $timeFromPlus60mins;

		    
		    $lunch_break_from = "12:00:00";
		    $lunch_break_to = "13:00:00";


		    do {
				 $date_create = date_create($dates[$counter]);
				 $day = date_format($date_create, 'w'); // 

				 $day_of_the_week = date_format($date_create,"l");


		    	if ($day >= $day_from && $day <= $day_to && ($day_of_the_week == "Monday" || $day_of_the_week == "Friday")){

		    		//echo $day . " " . $day_from . "<br/>";

		    		$weekdays[] = $dates[$counter];
		    		$date =  $dates[$counter]; 
		    				
		    		$weekdays_count++; // for echo condition

		    		if ($date < date("Y-m-d")){

		    			
		    			//echo $date . " ";

			    		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
			    		if ($num_rows == 1) {

			    			
			    				
			    			$select_qry = "SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'";
			    			$result = mysqli_query($connect,$select_qry);
			    			$row = mysqli_fetch_object($result);

			    			//echo $row->time_in . " " . $row->time_out . "<br/>";

			    			/*if ($row->time_out == "00:00:00"){
			    				
			    			}*/
			    			$db_time_in = substr($row->time_in,0,6) . "00";

							$db_time_out = substr($row->time_out,0,6) . "00";

							if ($timeIn >= $db_time_in && $timeOut <= $db_time_out){ // 08:30:00 ,, 18:30:00 <=
								//echo "Wew";

								if ($day_of_the_week == "Monday"){
									$incentives += round($daily_rate * .10,2);
								}

								else if ($day_of_the_week == "Friday"){
									$incentives += round($daily_rate * .05,2);
								}

								 

							}
 
			    		}		
			    		
		    		}
		    	}

		    	//echo $dates[$counter];
		    	
		    	$counter++;
		    	
		    }while($counter <= $count);
	
			
	    	return $incentives;
		}
}
?>