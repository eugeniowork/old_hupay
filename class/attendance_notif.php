<?php


// 0 pending for hr
// 1 approve
// 2 disapprove
// 4 staff to head


// for attendance notif info
class AttendanceNotif extends Connect_db{

	// for insert
	function insertAttendanceNotif($emp_id,$head_emp_id,$attendance_id,$date,$time_in,$time_out,$remarks,$notif_status,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$attendance_id = mysqli_real_escape_string($connect,$attendance_id);
		$date = mysqli_real_escape_string($connect,$date);
		$time_in = mysqli_real_escape_string($connect,$time_in);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$notif_status = mysqli_real_escape_string($connect,$notif_status);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_attendance_notif (attendance_notif_id,emp_id,head_emp_id,attendance_id,`date`,time_in,time_out,remarks,notif_status,DateCreated) VALUES('','$emp_id','$head_emp_id','$attendance_id','$date','$time_in','$time_out','$remarks','$notif_status','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// check if exist attendance id so update lang , if wla pa insert
	public function existAttendanceId($attendance_id){
		$connect = $this->connect();

		$attendance_id = mysqli_real_escape_string($connect,$attendance_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE attendance_id = '$attendance_id'"));
		return $num_rows;
	}


	// check if exist date and emp_id so update lang , if wla pa insert
	public function existDateEmpId($emp_id,$date){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$date = mysqli_real_escape_string($connect,$date);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE emp_id = '$emp_id' AND `date`= '$date'"));
		return $num_rows;
	}


	//
	public function checkExistDateEmpIdApprove($emp_id,$date){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$date = mysqli_real_escape_string($connect,$date);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE emp_id = '$emp_id' AND `date`= '$date' AND notif_status = '1'"));
		return $num_rows;
	}


	// for getting attendance notif id
	public function getAttendanceNotifIdByEmpIdDate($emp_id,$date){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$date = mysqli_real_escape_string($connect,$date);

		$select_qry = "SELECT * FROM tb_attendance_notif WHERE emp_id = '$emp_id' AND `date`= '$date'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row->attendance_notif_id;
	}




	// for updating
	public function updateAttendanceNotif($attendance_id,$time_in,$time_out,$remarks,$notif_status,$dateCreated){
		$connect = $this->connect();

		$attendance_id = mysqli_real_escape_string($connect,$attendance_id);
		$time_in = mysqli_real_escape_string($connect,$time_in);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$notif_status = mysqli_real_escape_string($connect,$notif_status);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$update_qry = "UPDATE tb_attendance_notif SET time_in = '$time_in' , time_out = '$time_out', remarks = '$remarks', notif_status = '$notif_status', DateCreated = '$dateCreated' WHERE attendance_id = '$attendance_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function updateAddAttendanceNotif($attendance_notif_id,$time_in,$time_out,$remarks,$notif_status,$dateCreated){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		$time_in = mysqli_real_escape_string($connect,$time_in);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$notif_status = mysqli_real_escape_string($connect,$notif_status);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$update_qry = "UPDATE tb_attendance_notif SET time_in = '$time_in' , time_out = '$time_out', remarks = '$remarks', notif_status = '$notif_status', DateCreated = '$dateCreated' WHERE attendance_notif_id = '$attendance_notif_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for getting attendance notif table count
	public function attendanceNotifToTableCount($emp_id,$role){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		$counter = 1;
		if ($role == 3 || $role == 4){
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE head_emp_id = '$emp_id' AND notif_status = '0'"));
		}
		else {
			$num_rows = $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE emp_id !='$emp_id' AND head_emp_id = '0' AND notif_status = '0'"));
		}

		return $num_rows;
	}


	// for putting values to table
	public function attendanceNotifToTable($emp_id,$role){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		$counter = 1;
		if ($role == 3 || $role == 4){
			$select_qry = "SELECT * FROM tb_attendance_notif WHERE head_emp_id = '$emp_id' AND notif_status != '3' AND notif_status = '4'";
		}
		else {

			//$num_rows = $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE head_emp_id = '$emp_id' AND notif_status != '3' AND notif_status = '4'"));


			//if ($num_rows == 0){
				//$select_qry = "SELECT * FROM tb_attendance_notif WHERE (emp_id !='$emp_id' AND notif_status = '0') OR (head_emp_id = '$emp_id' AND notif_status != '3' AND notif_status = '4')"; // AND head_emp_id = '0'
			//}

			if ($emp_id == 167 || $emp_id == 168 || $emp_id == 174 ){
				$select_qry = "SELECT * FROM tb_attendance_notif WHERE (emp_id !='$emp_id' AND notif_status = '0') OR (head_emp_id = '$emp_id' AND notif_status != '3' AND notif_status = '4')"; // AND head_emp_id = '0'
			}

			else {

				$select_qry = "SELECT * FROM tb_attendance_notif WHERE (emp_id !='$emp_id' AND notif_status = '0')"; // AND head_emp_id = '0'
			}

			
		}
		//echo $select_qry . "<br/>";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				$date_create = date_create($row->date);
				$date_format = date_format($date_create, 'F d, Y');



				// for emp info
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id ='$row->emp_id'";
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



				$attendance_date = date_format(date_create($row->date),"Y-m-d");

				$timeFrom = date_format(date_create($row->time_in), 'g:i A');
				$timeTo = date_format(date_create($row->time_out), 'g:i A');

				// ibig sabihin add attendance
				$attendance_status = "Add Attendance";
				if ($row->attendance_id != 0){
					// get natin ung information sa tb_attendance by attendance id

					//echo $row->attendance_id . "<br/>";

					$select_attendance_qry = "SELECT * FROM tb_attendance WHERE attendance_id='$row->attendance_id'";
					$result_attendance = mysqli_query($connect,$select_attendance_qry);
					$row_attendance = mysqli_fetch_object($result_attendance);

					$orig_timeFrom = date_format(date_create($row_attendance->time_in), 'g:i A');
					$orig_timeTo = date_format(date_create($row_attendance->time_out), 'g:i A');

					if ($row->time_out == "00:00:00") {
						$orig_timeTo = "No Time Out";
					}

					$attendance_status = $orig_timeFrom . " - " . $orig_timeTo;
				}


			

				// if sakop ng date
				//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
					// lahat lng ng mga d pa naaproved ang magpapakita
					if ($row->notif_status != 1 && $row->notif_status != 2) {
						echo "<tr id='".$row->attendance_notif_id."''>";
							echo "<td><input type='checkbox' class='form-control' value='".$row->attendance_notif_id."' name='attendance_request".$counter."' /></td>";
							echo "<td>". $row_emp->Firstname . " " . $row_emp->Middlename . " " . $row_emp->Lastname . "</td>";
							echo "<td>" . $date_format . "</td>";
							echo "<td>" . $attendance_status . "</td>";
							echo "<td>" . $timeFrom . " - ". $timeTo . "</td>";
							echo "<td id='readmoreValue'>" . nl2br(htmlspecialchars($row->remarks)) . "</td>";
							echo "<td>";
								
								if ($_SESSION["id"] != 21 && ((($emp_id == 167 || $emp_id == 168 || $emp_id == 174) && $row->notif_status == 4) || $emp_id == 71 || ($row->head_emp_id == $_SESSION["id"] && $row->notif_status == 4)) || $role == 1){
									echo "<a href='#' id='approve_request_attendance' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
									echo "<span> | </span>";
									echo "<a href='#' id='approve_request_attendance' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
								}
								else {
									if ($row->emp_id == 71){
										echo "<a href='#' id='approve_request_attendance' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
										echo "<span> | </span>";
										echo "<a href='#' id='approve_request_attendance' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
									}
									else {
										echo "No action";
									}
								}
							echo "</td>";
						echo "</tr>";
						$counter++;
					}
				//}

			}
		}
	}


	// for putting values to table
	public function attendanceNotifPendingCount($role,$emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		$count = 0;
		if ($role == 3 || $role == 4){
			$select_qry = "SELECT * FROM tb_attendance_notif WHERE head_emp_id = '$emp_id' AND notif_status != '3' AND notif_status = '4'";
		}
		else {
			$select_qry = "SELECT * FROM tb_attendance_notif WHERE emp_id !='$emp_id' AND notif_status = '0'"; // AND head_emp_id = '0'
		}
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				$date_create = date_create($row->date);
				$date_format = date_format($date_create, 'F d, Y');



				// for emp info
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id ='$row->emp_id'";
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



				$attendance_date = date_format(date_create($row->date),"Y-m-d");

				$timeFrom = date_format(date_create($row->time_in), 'g:i A');
				$timeTo = date_format(date_create($row->time_out), 'g:i A');

				// ibig sabihin add attendance
				$attendance_status = "Add Attendance";
				if ($row->attendance_id != 0){
					// get natin ung information sa tb_attendance by attendance id
					$select_attendance_qry = "SELECT * FROM tb_attendance WHERE attendance_id='$row->attendance_id'";
					$result_attendance = mysqli_query($connect,$select_attendance_qry);
					$row_attendance = mysqli_fetch_object($result_attendance);

					$orig_timeFrom = date_format(date_create($row_attendance->time_in), 'g:i A');

					
					$orig_timeTo = date_format(date_create($row_attendance->time_out), 'g:i A');

					if ($row->time_out == "00:00:00") {
						$orig_timeTo = "No Time Out";
					}

					$attendance_status = $orig_timeFrom . " - " . $orig_timeTo;
				}


			

				// if sakop ng date
				//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
					// lahat lng ng mga d pa naaproved ang magpapakita
					if ($row->notif_status != 1 && $row->notif_status != 2) {
						
						/*echo "<tr id='".$row->attendance_notif_id."''>";
							echo "<td><input type='checkbox' class='form-control' value='".$row->attendance_notif_id."' name='attendance_request".$counter."' /></td>";
							echo "<td>". $row_emp->Firstname . " " . $row_emp->Middlename . " " . $row_emp->Lastname . "</td>";
							echo "<td>" . $date_format . "</td>";
							echo "<td>" . $attendance_status . "</td>";
							echo "<td>" . $timeFrom . " - ". $timeTo . "</td>";
							echo "<td id='readmoreValue'>" . nl2br(htmlspecialchars($row->remarks)) . "</td>";
							echo "<td>";
								echo "<a href='#' id='approve_request_attendance' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
								echo "<span> | </span>";
								echo "<a href='#' id='approve_request_attendance' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
							echo "</td>";
						echo "</tr>";
						*/
						$count++;
					}
				//}

			}
		}

		return $count;
	}



	// for getting all attendance request updates link to user
	public function getAllRequestUpdatesMultipleApprove($emp_id,$role){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);


		$counter = 1;
		if ($role == 3 || $role == 4){
			$select_qry = "SELECT * FROM tb_attendance_notif WHERE head_emp_id = '$emp_id'";
		}
		else {
			$select_qry = "SELECT * FROM tb_attendance_notif WHERE emp_id !='$emp_id' AND head_emp_id = '0'";
		}
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				$date_create = date_create($row->date);
				$date_format = date_format($date_create, 'F d, Y');


				// if sakop ng date
				//if ($attendance_date >= $final_date_from && $attendance_date <= $final_date_to){
					// lahat lng ng mga d pa naaproved ang magpapakita
					if ($row->notif_status != 1 && $row->notif_status != 2) {
						echo "<tr id='".$row->attendance_notif_id."''>";
							echo "<td><input type='checkbox' class='form-control' name='attendance_request".$counter."' /></td>";
							echo "<td>". $row_emp->Firstname . " " . $row_emp->Middlename . " " . $row_emp->Lastname . "</td>";
							echo "<td>" . $date_format . "</td>";
							echo "<td>" . $attendance_status . "</td>";
							echo "<td>" . $timeFrom . " - ". $timeTo . "</td>";
							echo "<td id='readmoreValue'>" . nl2br(htmlspecialchars($row->remarks)) . "</td>";
							echo "<td>";
								echo "<a href='#' id='approve_request_attendance' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
								echo "<span> | </span>";
								echo "<a href='#' id='approve_request_attendance' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
							echo "</td>";
						echo "</tr>";
					}
				//}
			}
		}
	}



	// for security issue check if the attendance notif id is existed
	public function checkExistAttendanceNotifId($attendance_notif_id){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE attendance_notif_id = '$attendance_notif_id'"));
		return $num_rows;
	}

	// for checking if exist nga pero hindi sa kanya ung attendance_notif_id
	public function checkOwnAttendanceNotif($attendance_notif_id,$emp_id){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE attendance_notif_id = '$attendance_notif_id' AND emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for approving attendance requested by employee
	public function updateApproveRequest($attendance_notif_id,$notif_status){
		$connect = $this->connect();

		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');



		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		$notif_status = mysqli_real_escape_string($connect,$notif_status);

		$update_qry = "UPDATE tb_attendance_notif SET notif_status = '$notif_status', DateApprove = '$current_date_time' WHERE attendance_notif_id = '$attendance_notif_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for getting information of attendance notif using attendance notif id
	public function getRequestAttendanceById($attendance_notif_id){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);

		$select_qry = "SELECT * FROM tb_attendance_notif WHERE attendance_notif_id = '$attendance_notif_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}

	// for disapproving attendance notif request update notif_status = 2
	public function updateDisapproveRequest($attendance_notif_id){
		$connect = $this->connect();

		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');


		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		
		$update_qry = "UPDATE tb_attendance_notif SET notif_status = '2', DateApprove = '$current_date_time'  WHERE attendance_notif_id = '$attendance_notif_id'";
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

		$select_qry = "SELECT * FROM tb_attendance_notif WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				if ($final_date_from <= $row->date && $final_date_to >= $row->date){
					
					$date_create = date_create($row->date);
					$date = date_format($date_create, 'F d, Y');

					$timeIn = date_format(date_create($row->time_in), 'g:i A');
					$timeTo = date_format(date_create($row->time_out), 'g:i A');
								
					if ($row->notif_status == 1){
						$approve_stat = "Approved";
					}
					else if ($row->notif_status == 2){
						$approve_stat = "Disapproved";
					}
					else if ($row->notif_status == 0){
						$approve_stat = "Pending";
					}
					else if ($row->notif_status == 4){
						$approve_stat = "Pending";
					}

					else if ($row->notif_status == 3){
						$approve_stat = "Cancelled";
					}

			


					echo "<tr id='".$row->attendance_notif_id."'>";
						echo "<td>" . $date . "</td>";
						echo "<td>" . $timeIn . " - " . $timeTo . "</td>";
						echo "<td>" . $approve_stat . "</td>";
						echo "<td>";



							if ($row->notif_status == 0) {
								echo "<span style='color:#317eac;cursor:pointer;' id='edit_file_attendance_updates'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
								echo "<span>&nbsp;|&nbsp;</span>";
							}

							if ($row->notif_status == 4) {
								echo "<span style='color:#317eac;cursor:pointer;' id='edit_file_attendance_updates'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span>&nbsp;Edit</span>";
								echo "<span>&nbsp;|&nbsp;</span>";
							}

							if ($row->notif_status != 3 && $row->notif_status != 2 && $row->notif_status != 1) {
								echo "<span style='color:#317eac;cursor:pointer;' id='cancel_file_attendance_updates'><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span>&nbsp;Cancel</span>";
							}

							if ($row->notif_status == 3 || $row->notif_status == 1 || $row->notif_status == 2) {
								echo "No actions";
							}
						echo "</td>";
					echo "</tr>";
				}

			}
		}

	}


	// for getting the last id in database
	public function attendanceNotifLastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_attendance_notif ORDER BY attendance_notif_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->attendance_notif_id;
		return $last_id;
	}


	// get the attendance notif id by attendance id
	public function getAttendanceNotifIdByAttendanceId($attendance_id){
		$connect = $this->connect();

		$attendance_id = mysqli_real_escape_string($connect,$attendance_id);

		$select_qry = "SELECT * FROM tb_attendance_notif WHERE attendance_id = '$attendance_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row->attendance_notif_id;
	}



	// check for updates no changes
	public function updatesAttendanceInfoNoChanges($attendance_notif_id,$time_in,$time_out,$remarks){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		//$date = mysqli_real_escape_string($connect,$date);
		$time_in = mysqli_real_escape_string($connect,$time_in);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance_notif WHERE attendance_notif_id = '$attendance_notif_id' AND time_in = '$time_in'
					AND time_out = '$time_out' AND remarks = '$remarks'"));
		return $num_rows;

	}

	// for updating filed attendance info
	public function updateFileAttendanceInfo($attendance_notif_id,$time_in,$time_out,$remarks){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);
		//$date = mysqli_real_escape_string($connect,$date);
		$time_in = mysqli_real_escape_string($connect,$time_in);
		$time_out = mysqli_real_escape_string($connect,$time_out);
		$remarks = mysqli_real_escape_string($connect,$remarks);

		$update_qry = "UPDATE tb_attendance_notif SET time_in = '$time_in' , time_out = '$time_out', remarks = '$remarks' WHERE attendance_notif_id = '$attendance_notif_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for cancelling attendance notifications
	public function cancelFileAttendanceRequestUpdates($attendance_notif_id){
		$connect = $this->connect();

		$attendance_notif_id = mysqli_real_escape_string($connect,$attendance_notif_id);

		$update_qry = "UPDATE tb_attendance_notif SET notif_status = '3' WHERE attendance_notif_id = '$attendance_notif_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


}

?>