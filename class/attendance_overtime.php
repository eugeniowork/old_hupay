<?php

// 0 pending to hr
// 1 approve
// 2 disapprove
// 3 cancel
// 4 pending to head

class Attendance_Overtime extends Connect_db{

	// for inserting
	public function insertOvertime($emp_id,$head_emp_id,$date,$time_from,$time_out,$overtime_type,$remarks,$approve_stat,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$date = mysqli_real_escape_string($connect,$date);
		$time_from = mysqli_real_escape_string($connect,$time_from);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$overtime_type = mysqli_real_escape_string($connect,$overtime_type);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$approve_stat = mysqli_real_escape_string($connect,$approve_stat);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_attendance_overtime (attendance_ot_id,emp_id,head_emp_id,`date`,time_from,time_out,type_ot,remarks,approve_stat,DateCreated) VALUES ('','$emp_id','$head_emp_id','$date','$time_from','$time_out','$overtime_type','$remarks','$approve_stat','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}



	// for updating

	// for updating specific OT
	public function updateAttendanceSpecificOT($time_from,$time_out,$overtime_type,$remarks,$approve_stat,$dateCreated,$date,$emp_id,$head_emp_id){
		$connect = $this->connect();

		$time_from = mysqli_real_escape_string($connect,$time_from);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$overtime_type = mysqli_real_escape_string($connect,$overtime_type);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$approve_stat = mysqli_real_escape_string($connect,$approve_stat);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$date = mysqli_real_escape_string($connect,$date);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);

		$update_qry = "UPDATE tb_attendance_overtime SET time_from = '$time_from', time_out = '$time_out', type_ot = '$overtime_type', remarks='$remarks', approve_stat = '$approve_stat', DateCreated = '$dateCreated', head_emp_id = '$head_emp_id' WHERE `date` = '$date' AND emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// check if exist overtime id so update lang , if wla pa insert
	public function existOvertime($emp_id,$date){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$date = mysqli_real_escape_string($connect,$date);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_overtime WHERE emp_id = '$emp_id' AND `date` = '$date'"));
		return $num_rows;
	}


	// for getting attendance overtime table overtime
	public function getOvertimeInfoToTable($emp_id,$role){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		if ($role == 4 || $role == 3) {
			$select_qry = "SELECT * FROM tb_attendance_overtime WHERE head_emp_id = '$emp_id' AND approve_stat != '3' AND approve_stat = '4'";
		}

		else {

			if ($emp_id == 167 || $emp_id == 168){
				$select_qry = "SELECT * FROM tb_attendance_overtime WHERE (emp_id != '$emp_id' AND approve_stat ='0') OR (head_emp_id = '$emp_id' AND approve_stat != '3' AND approve_stat = '4')";
			}

			else {

				$select_qry = "SELECT * FROM tb_attendance_overtime WHERE emp_id != '$emp_id' AND approve_stat ='0'";
			}
		}
		
		
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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

					$date_create_file = date_create($row->DateCreated);
					$date_format_file = date_format($date_create_file, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");

					$timeFrom = date_format(date_create($row->time_from), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');

					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){

						if ($row->approve_stat != 2 && $row->approve_stat != 1) {
							echo "<tr id=".$row->attendance_ot_id.">";
							echo "<td>" .$row_emp->Lastname . ", " .$row_emp->Firstname . " " . $row_emp->Middlename."</td>";
							echo "<td>".$date_format_file."</td>";
							echo "<td>".$date_format."</td>";
							echo "<td>".$timeFrom."</td>";
							echo "<td>".$timeTo."</td>";
							echo "<td>".nl2br(htmlspecialchars($row->remarks))."</td>";
							echo "<td>";

								if ($_SESSION["id"] != 21 && ((($emp_id == 167 || $emp_id == 168) && $row->approve_stat == 4)) || $emp_id == 71 || ($row->head_emp_id == $_SESSION["id"] && $row->approve_stat == 4) || $role == 1 || $role == 2){




									echo "<a href='#' id='approve_ot_request' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
										echo "<span> | </span>";
										echo "<a href='#' id='approve_ot_request' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
								}
								else {
									echo "No action";
								}
							echo "</td>";
							echo "</tr>";
						}

				//	}	


					
			}

		}
	}


	// for finding the count of request ot
	// for getting attendance overtime table overtime
	public function getOvertimePendingCount($role,$emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		$count = 0;
		if ($role == 4 || $role == 3) {
			$select_qry = "SELECT * FROM tb_attendance_overtime WHERE head_emp_id = '$emp_id' AND approve_stat != '3' AND approve_stat = '4'";
			
			//echo "wew";
		}

		else {
			$select_qry = "SELECT * FROM tb_attendance_overtime WHERE emp_id != '$emp_id' AND approve_stat ='0'";
		}
		
		
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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

					$timeFrom = date_format(date_create($row->time_from), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');

					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){

						
						if ($row->approve_stat != 1 && $row->approve_stat != 2) {
							$count++;
							/*
							echo "<tr id=".$row->attendance_ot_id.">";
							echo "<td>" .$row_emp->Lastname . ", " .$row_emp->Firstname . " " . $row_emp->Middlename."</td>";
							echo "<td>".$date_format."</td>";
							echo "<td>".$timeFrom."</td>";
							echo "<td>".$timeTo."</td>";
							echo "<td>".nl2br(htmlspecialchars($row->remarks))."</td>";
							echo "<td>";
								echo "<a href='#' id='approve_ot_request' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
									echo "<span> | </span>";
									echo "<a href='#' id='approve_ot_request' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
							echo "</td>";
							echo "</tr>";
							*/
						}
						

				//	}	


					
			}

		}

		return $count;
	}




	// for getting attendance overtime of regular ot
	public function getOvertimeRegularOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";


		// select employee for getting working_id
		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
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


		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
						//echo $row->time_out . "<br/>";
						if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}
						

					}	


					
			}

		}
		//echo $ot;
		return $ot;

	}



	


	// for getting attendance overtime of regular ot
	public function getOvertimeRegularOtRunningBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";


		// select employee for getting working_id
		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
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


		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


							//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
							$minus_five_day = date("Y-m-d");

							
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


						$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
						//echo $row->time_out . "<br/>";
						if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}
						

					}	


					
			}

		}
		//echo $ot;
		return $ot;

	}


	// for getting ot amount na mismo dahil sa pagchange ng minimum wage
	public function getRegOtAmount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		//$ot = "0";
		$ot_amount = "0";

		// select employee for getting working_id
		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
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


		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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



						// for checking has change in min wage within that cut off

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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $monthly_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate + ($hourly_rate * .25),2);

						//echo $ot_rate . " " . $attendance_date . "<br/>";

						$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 3600; // devided by 3600 para devided by 1 hour

						//echo $total_hours . "<br/>";

						if ($ot_amount == "0"){	
							$ot_amount = $total_hours * $ot_rate;
						//	echo $ot_amount . "<br/>";
						}
						else {
							$ot_amount = $ot_amount + ($total_hours * $ot_rate);
						//	echo $ot_amount . "<br/>";
						}


					   


					   /* echo $minWageEffectiveDate . "<br/>";
					    echo $row_emp->Salary . "<br/>";
					    echo $getLastEffectiveDate . "<br/>";
					    echo $regular_ot_rate . "<br/>";
					    echo $attendance_date . "<br/>";
					    echo "<br/>";
					    */

					   // echo $this->




						//echo $row->time_out . "<br/>";
						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		//echo "wew";
		//echo $ot_amount;
		return $ot_amount;


	}


	// for getting ot amount na mismo dahil sa pagchange ng minimum wage
	public function getRdOtAmount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$morning_ot = 0;
		$afternoon_ot = 0;

		$ot_amount = "0";

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $min_wage_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate + ($hourly_rate * .3),2);


						//echo $regular_ot_rate . " sa " . $attendance_date . "<br/>";


						//echo $regular_ot_rate . "<br/>";
					
						if ($ot_amount == "0"){	

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = (($morning_ot + $afternoon_ot)/ 60) * $ot_rate;

							//if ($ot_amount == "0"){

							//}
						}
						else {

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							//$ot = $ot + $morning_ot + $afternoon_ot;


							$ot_amount = ((($morning_ot + $afternoon_ot) / 60) * $ot_rate) + $ot_amount;
						}
						

					}	


					
			}

		}
		//echo $ot;
		//echo $ot_amount . "<br/>";
		return $ot_amount;


	}


	// for regular holiday OT
	public function getRegHolidayOtAmount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$ot_amount = "0";

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;

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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $min_wage_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate,2);





						if ($ot_amount == "0"){	

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = (($morning_ot + $afternoon_ot)/60) * $ot_rate;
						}
						else {

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = ((($morning_ot + $afternoon_ot)/60) * $ot_rate) + $ot_amount;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot_amount;
	}



	// for getting special holiday OT
	public function getSpecialHolidayOtAmount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot_amount = "0";
		$ot = "0";

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $min_wage_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate * .3,2);



						if ($ot_amount == "0"){	

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = (($morning_ot + $afternoon_ot)/60) * $ot_rate;
						}
						else {

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = ((($morning_ot + $afternoon_ot)/60) * $ot_rate) + $ot_amount;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot_amount;
	}


	// for getting rdRegularHolidayOtAmount
	public function getRdRegularHolidayOtAmount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot_amount = "0";
		$ot = "0";

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;

						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $min_wage_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate * 2.6,2);


						if ($ot_amount == "0"){	

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = ($morning_ot + $afternoon_ot)*$ot_rate;
						}
						else {

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = $ot_amount + ($morning_ot + $afternoon_ot * ($ot_rate));
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot_amount;
	}



	// for getting rd_specialHolidayOTamount
	public function getRdSpecialHolidayOTamount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot_amount = "0";
		$ot = "0";

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;

						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
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


					    $daily_rate = ($row_emp->Salary + $allowance )/ 22;

					    if ($attendance_date < $minWageEffectiveDate) {
					    	//echo $attendance_date . " ay dapat sa kanya na magededuct ng updated salary";
				    		
				    		$min_wage_increase = $this->getRowLatestMinimum()->basicWage - $this->getRowLastMinimum()->basicWage;

				    		
				    		$monthly_increase = ($min_wage_increase * 26);

				    		$daily_rate = ($row_emp->Salary + $allowance - $monthly_increase)/ 22;


				    		//echo $min_wage_increase . "ang increase";
					    }


					    //$daily_rate =  $row->Salary / 26;
					    
						//$hourly_rate = $daily_rate / 8;
						$hourly_rate = $daily_rate / 9;
						//$new_daily_rate = $hourly_rate * 9;


						// for OT's rate
						$ot_rate = round($hourly_rate + ($hourly_rate * .6),2);


						if ($ot_amount == "0"){	

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = ($morning_ot + $afternoon_ot)*$ot_rate;
						}
						else {

							if ($row->time_from < "12:00:00") {
								$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
							}

							if ($row->time_out > "13:00:00") {
								$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
							}

							$ot_amount = $ot_amount + (($morning_ot + $afternoon_ot) * $ot_rate);
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot_amount;
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




	// for getting attendance overtime of holiday ot
	public function getOvertimeRegularHolidayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";

		
		$afternoon_ot = 0;
		$morning_ot = 0;

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {
								
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}

							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of holiday ot
	public function getOvertimeRegularHolidayOtRunningBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
	
		$afternoon_ot = 0;
		$morning_ot = 0;

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
				$minus_five_day = date("Y-m-d");

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
				}


			}

		}


		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');



					


					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");



					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {
								
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}

							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					//}	


					
			}

		}
		//echo $ot . "<br/>";
		return $ot;
	}






	// for getting attendance overtime of special ot
	public function getOvertimeSpecialHolidayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {


								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot;
	}




	// for getting attendance overtime of special ot
	public function getOvertimeSpecialHolidayOtRunningBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;

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

				$minus_five_day = date("Y-m-d");

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
				}


			}

		}

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');



					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");



					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {


								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					//}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of special ot
	public function getOvertimeRDRegularHolidayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		//echo $ot / 60;
		return $ot;
	}


	public function getOvertimeRDRegularHolidayOtToRunningBalance($emp_id){
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


				//$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
				$minus_five_day = date("Y-m-d");

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
				}


			}

		}


		

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Regular Holiday' AND emp_id='$emp_id' AND approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");

					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					//}	


					
			}

		}
		//echo $ot / 60;
		return $ot;
	}


	// for getting attendance overtime of special ot
	public function getOvertimeRDSpecialHolidayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}

							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of special ot
	public function getOvertimeRDSpecialHolidayOtRunningBalance($emp_id){
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

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Special Holiday' AND emp_id='$emp_id' AND approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){



					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');



					


					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");



					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){


						//$total_hours = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}

							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}




						/*if ($ot == "0"){	
							$ot = ($total_hours);
						}
						else {
							$ot = $ot + $total_hours;
						}*/
						

					//}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of restday ot
	public function getOvertimeRestdayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$morning_ot = 0;
		$afternoon_ot = 0;

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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
					
						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								//$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								//$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}
						

					}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of restday ot
	public function getOvertimeRestdayOtRunningBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$morning_ot = 0;
		$afternoon_ot = 0;

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

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday' AND emp_id='$emp_id' AND approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$date_create = date_create($row->date);
					$date_format = date_format($date_create, 'F d, Y');

					$attendance_date = date_format(date_create($row->date),"Y-m-d");

					// if sakop ng date
					//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
					
						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {

								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								//$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}

								else {
									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								//$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {
									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}
						

					//}	


					
			}

		}
		return $ot;
	}


	// for getting attendance overtime of restday holiday ot
	public function getOvertimeRestdayHolidayOt($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$ot = "0";
		$afternoon_ot = 0;
		$morning_ot = 0;
		
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE type_ot='Restday / Holiday' AND emp_id='$emp_id' AND approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

					$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

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


						if ($ot == "0"){	

							if ($row->time_from < "12:00:00") {

								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {


								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $morning_ot + $afternoon_ot;
						}
						else {

							if ($row->time_from < "12:00:00") {
								if ($row->time_out < "12:00:00"){
									$morning_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {

									$morning_ot = (strtotime("12:00:00") - strtotime($row->time_from)) / 60;
								}
							}

							if ($row->time_out > "13:00:00") {
								if ($row->time_from > "13:00:00"){
									$afternoon_ot = (strtotime($row->time_out) - strtotime($row->time_from)) / 60;
								}
								else {

									$afternoon_ot = (strtotime($row->time_out) - strtotime("13:00:00")) / 60;
								}
							}

							$ot = $ot + $morning_ot + $afternoon_ot;
						}
						

					}	


					
			}

		}
		return $ot;
	}



	// for security issue check if the attendance notif id is existed
	public function checkExistAttendanceOtId($attendance_ot_id){
		$connect = $this->connect();

		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_overtime WHERE attendance_ot_id = '$attendance_ot_id'"));
		return $num_rows;
	}


	// for check existattendanceot if owned
	public function checkAttendanceOtIdOwned($attendance_ot_id,$emp_id){
		$connect = $this->connect();

		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_overtime WHERE attendance_ot_id = '$attendance_ot_id' AND emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for approving attendance requested by employee
	public function updateApproveRequest($attendance_ot_id,$approve_stat){
		$connect = $this->connect();

		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');



		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		$approve_stat = mysqli_real_escape_string($connect,$approve_stat);

		$update_qry = "UPDATE tb_attendance_overtime SET approve_stat = '$approve_stat', DateApprove = '$current_date_time' WHERE attendance_ot_id = '$attendance_ot_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for disapproving attendance notif request update notif_status = 2
	public function updateDisapproveRequest($attendance_ot_id){
		$connect = $this->connect();

		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');


		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		
		$update_qry = "UPDATE tb_attendance_overtime SET approve_stat = '2', DateApprove = '$current_date_time'  WHERE attendance_ot_id = '$attendance_ot_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	// for approve ot list
	public function getAllApproveOTListCurrentCutOff($role,$emp_id){
		$connect = $this->connect();


		$role = mysqli_real_escape_string($connect,$role);
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





		if ($role == 4 || $role == 3){
			$select_qry = "SELECT * FROM tb_attendance_overtime WHERE head_emp_id = '$emp_id' AND approve_stat = '1' AND `date` BETWEEN '$final_date_from' AND '$final_date_to' ORDER BY `date` DESC";
		}

		else {
			$select_qry = "SELECT * FROM tb_attendance_overtime WHERE head_emp_id = '0' AND approve_stat = '1' AND `date` BETWEEN '$final_date_from' AND '$final_date_to' ORDER BY `date` DESC";
		}
		
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->date);
				$date = date_format($date_create, 'F d, Y');

				$timeFrom = date_format(date_create($row->time_from), 'g:i A');
				$timeTo = date_format(date_create($row->time_out), 'g:i A');

				echo "<tr>";
					echo "<td>" .$fullName. "</td>";
					echo "<td>" .$date. "</td>";
					echo "<td>" .$timeFrom. "</td>";
					echo "<td>" .$timeTo. "</td>";
					echo "<td>" .$row->type_ot. "</td>";
				echo "</tr>";
			}
		}



	}


	// for approve ot list overall
	public function getAllApproveOTListOverAllCurrentCutOff(){
		$connect = $this->connect();


		//$role = mysqli_real_escape_string($connect,$role);
		//$emp_id = mysqli_real_escape_string($connect,$emp_id);

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





		//if ($role == 4 || $role == 3){
		//	$select_qry = "SELECT * FROM tb_attendance_overtime WHERE head_emp_id = '$emp_id' AND approve_stat = '1' AND `date` BETWEEN '$final_date_from' AND '$final_date_to' ORDER BY `date` DESC";
		//}

		//else {
		//$select_qry = "SELECT * FROM tb_attendance_overtime WHERE approve_stat = '1' AND `date` BETWEEN '$final_date_from' AND '$final_date_to' ORDER BY `date` DESC";
		//}
		

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE approve_stat = '1' AND `date` BETWEEN '$final_date_from' AND '$final_date_to' ORDER BY `date` DESC";

		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$date_create = date_create($row->date);
				$date = date_format($date_create, 'F d, Y');

				$timeFrom = date_format(date_create($row->time_from), 'g:i A');
				$timeTo = date_format(date_create($row->time_out), 'g:i A');

				echo "<tr>";
					echo "<td>" .$fullName. "</td>";
					echo "<td>" .$date. "</td>";
					echo "<td>" .$timeFrom. "</td>";
					echo "<td>" .$timeTo. "</td>";
					echo "<td>" .$row->type_ot. "</td>";
				echo "</tr>";
			}
		}



	}

	// for getting information of the current filed ot
	public function getInfoByAttendanceOtId($attendance_ot_id){
		$connect = $this->connect();
		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE attendance_ot_id = '$attendance_ot_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for no changes
	// check for updates no changes
	public function updatesOTInfoNoChanges($attendance_ot_id,$time_from,$time_out,$remarks){
		$connect = $this->connect();

		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		//$date = mysqli_real_escape_string($connect,$date);
		$time_from = mysqli_real_escape_string($connect,$time_from);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_overtime WHERE attendance_ot_id = '$attendance_ot_id' AND time_from = '$time_from'
					AND time_out = '$time_out' AND remarks = '$remarks'"));
		return $num_rows;

	}


	public function updateFileOvertime($attendance_ot_id,$time_from,$time_out,$remarks){
		$connect = $this->connect();

		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);
		//$date = mysqli_real_escape_string($connect,$date);
		$time_from = mysqli_real_escape_string($connect,$time_from);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);

		$update_qry = "UPDATE tb_attendance_overtime SET time_from = '$time_from',time_out = '$time_out', remarks = '$remarks' WHERE attendance_ot_id = '$attendance_ot_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for getting attendance ot information for the current off
	public function getOTStatusCurrentCutOff($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

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

				//echo $date_from . " " . $date_to . "<br/>";
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

		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				if ($final_date_from <= $row->date && $final_date_to >= $row->date){
					
					$date_create = date_create($row->date);
					$date = date_format($date_create, 'F d, Y');

					$timeFrom = date_format(date_create($row->time_from), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');
								
					if ($row->approve_stat == 1){
						$approve_stat = "Approved";
					}
					else if ($row->approve_stat == 2){
						$approve_stat = "Disapproved";
					}
					else if ($row->approve_stat == 0){
						$approve_stat = "Pending";
					}

					else if ($row->approve_stat == 4){
						$approve_stat = "Pending";
					}

					else if ($row->approve_stat == 3){
						$approve_stat = "Cancelled";
					}


					echo "<tr id='".$row->attendance_ot_id."'>";
						echo "<td>" . $date . "</td>";
						echo "<td>" . $timeFrom . " - " . $timeTo . "</td>";
						echo "<td>" . $row->type_ot . "</td>";
						echo "<td>" . $approve_stat . "</td>";
						echo "<td>";
							if ($row->approve_stat == 0) {
								echo "<span style='color:#317eac;cursor:pointer;' id='edit_file_overtime'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
								echo "<span>&nbsp;|&nbsp;</span>";
							}

							if ($row->approve_stat == 4) {
								echo "<span style='color:#317eac;cursor:pointer;' id='edit_file_overtime'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
								echo "<span>&nbsp;|&nbsp;</span>";
							}

							if ($row->approve_stat != 3 && $row->approve_stat != 2 && $row->approve_stat != 1) {
								echo "<span style='color:#317eac;cursor:pointer;' id='cancel_file_overtime'><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span>&nbsp;Cancel</span>";
							}

							if ($row->approve_stat == 3 || $row->approve_stat == 2 || $row->approve_stat == 1) {
								echo "No actions";
							}


							
						echo "</td>";
					echo "</tr>";
				}

			}
		}

	}

	// for getting the last id in database
	public function attendanceOTLastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_attendance_overtime ORDER BY attendance_ot_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->attendance_ot_id;
		return $last_id;
	}
	


	// for reports of leaotve list history
	public function otListHistoryReports(){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "ot_list_history";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Time From')
					->setCellValue('D1', 'Time Out')
					->setCellValue('E1', 'Type of OT')
					->setCellValue('F1', 'Remarks')
					->setCellValue('G1', 'Date Approved');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$select_qry = "SELECT * FROM tb_attendance_overtime WHERE approve_stat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date = date_format(date_create($row->date), 'F d, Y');
				$timeFrom = date_format(date_create($row->time_from), 'g:i A');
				$timeOut = date_format(date_create($row->time_out), 'g:i A');

				$dateApprove = date_format(date_create($row->DateApprove), 'F d, Y');

				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $fullName)
					->setCellValue('B'.$count, $date)
					->setCellValue('C'.$count, $timeFrom)
					->setCellValue('D'.$count, $timeOut)
					->setCellValue('E'.$count, $row->type_ot)
					->setCellValue('F'.$count, $row->remarks)
					->setCellValue('G'.$count, $dateApprove);
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','G') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:G1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('ot_list_history_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}



	// for reports of leave list of current cut off
	public function otistCutOffReports(){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

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


		$filename = "ot_list_cut_off_".date_format(date_create($final_date_from), 'F_d_Y'). "-" . date_format(date_create($final_date_to), 'F_d_Y');
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Time From')
					->setCellValue('D1', 'Time Out')
					->setCellValue('E1', 'Type of OT')
					->setCellValue('F1', 'Remarks')
					->setCellValue('G1', 'Date Approved');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		$count = 1;
		$select_ot_qry = "SELECT * FROM tb_attendance_overtime WHERE approve_stat = '1' AND `date` >= '$final_date_from' AND `date` <= '$final_date_to'";
		if ($result_ot = mysqli_query($connect,$select_ot_qry)){
			while ($row_ot = mysqli_fetch_object($result_ot)){


		   		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_ot->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date = date_format(date_create($row_ot->date), 'F d, Y');
				$timeFrom = date_format(date_create($row_ot->time_from), 'g:i A');
				$timeOut = date_format(date_create($row_ot->time_out), 'g:i A');

				$dateApprove = date_format(date_create($row_ot->DateApprove), 'F d, Y');


				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $fullName)
					->setCellValue('B'.$count, $date)
					->setCellValue('C'.$count, $timeFrom)
					->setCellValue('D'.$count, $timeOut)
					->setCellValue('E'.$count, $row_ot->type_ot)
					->setCellValue('F'.$count, $row_ot->remarks)
					->setCellValue('G'.$count, $dateApprove);		    
				
			}
		}

					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','G') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:G1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('cut_off_leave_list_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

		
		
	}

	// for cancelling attendance notifications
	public function cancelFileOT($attendance_ot_id){
		$connect = $this->connect();

		$attendance_ot_id = mysqli_real_escape_string($connect,$attendance_ot_id);

		$update_qry = "UPDATE tb_attendance_overtime SET approve_stat = '3' WHERE attendance_ot_id = '$attendance_ot_id'";
		$sql = mysqli_query($connect,$update_qry);
	}

	


}
?>