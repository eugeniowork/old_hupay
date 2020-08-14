<?php

class Leave extends Connect_db{

	public function insertLeave($empId,$headEmpId,$dateFrom,$dateTo,$leaveType,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated){
		$connect = $this->connect();

		$empId = mysqli_real_escape_string($connect,$empId);
		$headEmpId = mysqli_real_escape_string($connect,$headEmpId);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$leaveType = mysqli_real_escape_string($connect,$leaveType);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$fileLeaveType = mysqli_real_escape_string($connect,$fileLeaveType);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_leave (emp_id,head_emp_id,dateFrom,dateTo,LeaveType,lt_id,Remarks,FileLeaveType,approveStat,DateCreated)
													VALUES ('$empId','$headEmpId','$dateFrom','$dateTo','$leaveType','$lt_id','$remarks','$fileLeaveType','$approveStat','$dateCreated')";

		//echo $insert_qry;
		$sql = mysqli_query($connect,$insert_qry);

	}


	public function getLeaveInfoToTable($emp_id,$role){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$role = mysqli_real_escape_string($connect,$role);

		if ($role == 4 || $role == 3) {
			$select_qry = "SELECT * FROM tb_leave WHERE approveStat != '3' AND approveStat = '4' AND head_emp_id = '$emp_id'";
			
		}

		else {
			$select_qry = "SELECT * FROM tb_leave WHERE approveStat = '0' AND emp_id != '$emp_id'";
			
		}

		
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->DateCreated);
				$dateFile = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$dateRange = $dateFrom . " - " . $dateTo;


				if ($row->approveStat != 2 && $row->approveStat != 1) {

					echo "<tr id='".$row->leave_id."'>";
						echo "<td>" .$fullName ."</td>";
						echo "<td>" .$dateFile ."</td>";
						echo "<td>" .$dateRange ."</td>";
						echo "<td>" .$row->LeaveType ."</td>";
						echo "<td>" .$row->FileLeaveType ."</td>";
						echo "<td id='readmoreValue'>" . nl2br(htmlspecialchars($row->Remarks)) ."</td>";
						echo "<td>";


						if ($_SESSION["id"] != 21){

								echo "<a href='#' id='approve_request_leave_old' class='action-a' title='Approve'><span class='glyphicon glyphicon-check' style='color:#186a3b'></span> Approve</a>";
								echo "<span> | </span>";
								echo "<a href='#' id='approve_request_leave_old' class='action-a' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Disapprove</a>";
							echo "</td>";
						}
						else {
							echo "No action";
						}
					echo "</tr>";
				}
			}
		}




	}



	public function getLeaveInfoHistory(){
		$connect = $this->connect();

	
		$select_qry = "SELECT * FROM tb_leave WHERE approveStat = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date_create = date_create($row_emp->DateHired);
				$dateHired = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$dateRange = $dateFrom . " - " . $dateTo;


				$date_create = date_create($row_emp->DateHired);
				$anniv_day = date_format($date_create, 'd');
				$anniv_month = date_format($date_create, 'm');


				date_default_timezone_set("Asia/Manila");
				//$date = date_create("1/1/1990");

				$dates = date("Y-m-d H:i:s");
				$date = date_create($dates);
				//date_sub($date, date_interval_create_from_date_string('16 hours'));

				// $current_date_time = date_format($date, 'Y-m-d H:i:s');
				$current_date_time = date_format($date, 'Y-m-d');


				//echo $current_date_time;

				$date_create = date_create($row_emp->DateHired);
				$year = date("Y");
				$anniversary = date_format($date_create, 'm-d');
				$anniversary_1 = date_format(date_create($year."-".$anniversary), 'Y-m-d');

				$year_prev = $year - 1;
				$year_next = $year + 1;

				


				$anniv_from = date_format(date_create($year_prev."-".$anniversary), 'Y-m-d');
				$anniv_to = date_format(date_create($year."-".$anniv_month . "-".$anniv_day), 'Y-m-d');

				if ($current_date_time > $anniversary_1){
					$anniv_from = date_format(date_create($year."-".$anniversary), 'Y-m-d');
					$anniv_to = date_format(date_create($year_next."-".$anniversary), 'Y-m-d');
				}


				if ($anniv_day == 1){
					$anniv_to = date("Y-m-d",strtotime($anniv_to) - (86400));
				}


				//echo $row->dateFrom . " " . $anniv_from . " " . $row->dateTo . " " . $anniv_to . "<br/>";



				if ($row->dateFrom >= $anniv_from && $row->dateTo <= $anniv_to){

					$status = "Approve";
					if ($row->approveStat == "2"){
						$status = "Disapprove";
					}


					echo "<tr id='".$row->leave_id."'>";
						echo "<td>" .$fullName ."</td>";
						echo "<td>" .$dateHired ."</td>";
						echo "<td>" .$dateRange ."</td>";
						echo "<td>" .$row->LeaveType ."</td>";
						echo "<td>" .$row->FileLeaveType ."</td>";
						echo "<td id='readmoreValue'>" . nl2br(htmlspecialchars($row->Remarks)) ."</td>";
						echo "<td>".$status."</td>";
					echo "</tr>";
				}
				
			}
		}




	}

	// for checking if exist leave id
	public function checkExistLeaveId($leave_id){
		$connect = $this->connect();
		$leave_id = mysqli_real_escape_string($connect,$leave_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE leave_id = '$leave_id'"));
		return $num_rows;
	}

	// for checking kung kanya tlga leave un
	public function checkOwnLeave($leave_id,$emp_id){
		$connect = $this->connect();
		$leave_id = mysqli_real_escape_string($connect,$leave_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE leave_id = '$leave_id'"));
		return $num_rows;
	}


	// for approving leave
	public function approveLeave($leave_id,$approveStat,$approveDate){
		$connect = $this->connect();
		$leave_id = mysqli_real_escape_string($connect,$leave_id);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$approveDate = mysqli_real_escape_string($connect,$approveDate);

		$update_qry = "UPDATE tb_leave SET approveStat = '$approveStat',dateApprove = '$approveDate' WHERE leave_id  = '$leave_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for disapprove
	public function disapproveLeave($leave_id,$approveDate){
		$connect = $this->connect();
		$leave_id = mysqli_real_escape_string($connect,$leave_id);
		$approveDate = mysqli_real_escape_string($connect,$approveDate);

		$update_qry = "UPDATE tb_leave SET approveStat = '2', dateApprove = '$approveDate' WHERE leave_id  = '$leave_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for getting the information by leave id
	public function getInfoByLeaveId($leave_id){
		$connect = $this->connect();
		$leave_id = mysqli_real_escape_string($connect,$leave_id);

		$select_qry = "SELECT * FROM tb_leave WHERE leave_id ='$leave_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;

	}


		// for getting the last id in database
	public function leaveLastId(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_leave ORDER BY leave_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->leave_id;
		return $last_id;
	}


	public function getFileLeaveHistoryStatus($emp_id){
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


		$select_qry = "SELECT * FROM tb_leave WHERE emp_id ='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				//echo "wew";

				$date_create = date_create($row->dateFrom);
				$dateFrom = date_format($date_create, 'F d, Y');

				$date_create = date_create($row->dateTo);
				$dateTo = date_format($date_create, 'F d, Y');

				$approveStat = "Pending";
				if ($row->approveStat == 1){
					$approveStat = "Approve";
				}

				if ($row->approveStat == 2){
					$approveStat = "Dispprove";
				}


				if ($row->approveStat == 3){
					$approveStat = "Cancelled";
				}


				//$final_date_from = $date_from;
				//$final_date_to = $date_to;


				// LEAVE CANCEL FACILITY IS FOR THE NEXT PAYROLL

				echo "<tr id='".$row->leave_id."'>";
					echo "<td>" . $row->LeaveType . "</td>";
					echo "<td>" . $dateFrom . " - " . $dateTo . "</td>";
					echo "<td id='readmoreValue'>" . htmlspecialchars($row->Remarks) . "</td>";
					echo "<td>" . $row->FileLeaveType . "</td>";
					echo "<td>" . $approveStat . "</td>";
					echo "<td><center>";
						if ($row->approveStat == 0 || $row->approveStat == 4) {
							echo "<span style='color:#317eac;cursor:pointer;' id='edit_file_leave'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></span>";
							//echo "<span>&nbsp;|&nbsp;</span>";
						}
						/*
						if ($row->approveStat != 3 &&  ($row->dateFrom >= $final_date_from && $row->dateTo >= $final_date_from)) {
							echo "<span style='color:#317eac;cursor:pointer;' id='cancel_leave'><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span></span>";
						}
						*/

						//
						if (($row->approveStat != 0 && $row->approveStat != 4) || ($row->dateFrom < $final_date_from && $row->dateTo < $final_date_from)) {
							echo "No actions";
						}
					echo "</center></td>";
				echo "</tr>";

			}
		}
	}


	// for reports of leave list history
	public function leaveListHistoryReports(){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "leave_list_history";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date From')
					->setCellValue('C1', 'Date To')
					->setCellValue('D1', 'Leave Type')
					->setCellValue('E1', 'File Leave Type')
					->setCellValue('F1', 'Remarks')
					->setCellValue('G1', 'Status');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
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
		
		$objPHPExcel->getActiveSheet()->setTitle('leave_list_history_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}



	// for reports of leave list of current cut off
	public function leaveListCutOffReports(){
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


		$filename = "leave_list_cut_off_".date_format(date_create($final_date_from), 'F_d_Y'). "-" . date_format(date_create($final_date_to), 'F_d_Y');
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Date From')
					->setCellValue('C1', 'Date To')
					->setCellValue('D1', 'Leave Type')
					->setCellValue('E1', 'File Leave Type')
					->setCellValue('F1', 'Remarks')
					->setCellValue('G1', 'Status');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$leave = 0;
		$select_leave_qry = "SELECT * FROM tb_leave WHERE approveStat = '1'";
		if ($result_leave = mysqli_query($connect,$select_leave_qry)){
			while ($row_leave = mysqli_fetch_object($result_leave)){


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

			   // echo $leave_count . " " . $row_leave->emp_id."<br/>";
			    $db_FromLeave = "";
			    $db_LeaveTo = "";
			    $has_leave = 0;
			    $leave_counter = 0;
			    do {
			    	$leave_date = $leaveRange[$leave_counter];



			    	//echo $leave_date;

			    	//echo $leave_date;
			    	// check na sakop siya ng date of payroll
			    	if ($leave_date >= $final_date_from && $leave_date <= $final_date_to) {
			    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));
			    		$has_leave = 1;
			    		// for getting the leave from
		    			if ($db_FromLeave == "") {
		    				$db_FromLeave = $leave_date;
		    			}

		    			// for getting the leave to
		    			if ($db_FromLeave != ""){
		    				$db_LeaveTo = $leave_date;
		    			}

			    		//echo $leave_date . " ". $row_leave->emp_id. "<br/>" ;
			    		//ech $

			    		// hindi pa siya nag eexist consider as leave na siya
			    		//if ($existAttendanceByDate == 0){

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


			    			
			    	//	} // end of if
			    	}


			    	


			    	

			    	$leave_counter++;
			    } while($leave_counter < $leave_count);


			    if ($has_leave == 1){
			   		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_leave->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

					$dateFrom = date_format(date_create($db_FromLeave), 'F d, Y');
					$dateTo = date_format(date_create($db_LeaveTo), 'F d, Y');

					$approve = "Approve";
					if ($row_leave->approveStat == 2) {
						$approve = "Disapprove";
					}

					$count++;
					$objPHPExcel->setActiveSheetIndex(0) 
						->setCellValue('A'.$count, $fullName)
						->setCellValue('B'.$count, $dateFrom)
						->setCellValue('C'.$count, $dateTo)
						->setCellValue('D'.$count, $row_leave->LeaveType)
						->setCellValue('E'.$count, $row_leave->FileLeaveType)
						->setCellValue('F'.$count, $row_leave->Remarks)
						->setCellValue('G'.$count, $approve);
			    }

				
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



	// for selecting leave count list to table from tb_employee_info
	public function getAvailableLeaveCountToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
				if ($row->Middlename == ""){
					$name = $row->Lastname . ", " . $row->Firstname;
				}

				$dateHired =date_format(date_create($row->DateHired), 'F d, Y');

				echo "<tr id='".$row->emp_id."'>";
					echo "<td>" .$name. "</td>";
					echo "<td>".$dateHired."</td>";
					echo "<td>";
						echo "<table class='table table-bordered'>";
					    echo "<thead>";
					      echo "<tr>";
					        echo "<th class='color-white bg-color-gray'><small>Leave Type</small></th>";
					        echo "<th class='color-white bg-color-gray'><small>Leave Count</small></th>";
					      echo "</tr>";
					    echo "</thead>";
					    echo "<tbody>";
					      
					      	$this->getEmpLeaveCount($row->emp_id);
					     
					    echo "</tbody>";
					  echo "</table>";
					echo"</td>";
					/*echo "<td>";
						echo "<div id='edit_leave_count' style='cursor:pointer;color:#158cba'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</div>";
					echo "</td>";*/
				echo "</tr>";

			}

		}

	}


	public function getEmpLeaveCount($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_emp_leave WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$leave_array_explode =explode("," ,$row->leave_array);
				$leave_count_array_explode =explode("," ,$row->leave_count_array);

				$counter = 0;

				$count = count($leave_array_explode);

				do{

					$lt_id = $leave_array_explode[$counter];

					//echo $lt_id . " ";
					$row = $this->getLeaveTypeById($lt_id);



					echo "<tr>";
						echo "<td><small>".$row->name."</small></td>";
						echo "<td><small>".$leave_count_array_explode[$counter]."</small></td>";
					echo "</tr>";



					$counter++;
				}while($count > $counter);


			}

		}
	}



	// for updating leave count 
	public function updateLeaveCount($emp_id,$leave_count){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$leave_count = mysqli_real_escape_string($connect,$leave_count);

		$update_qry = "UPDATE tb_employee_info SET leave_count = '$leave_count' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}
		

	// for no changes
	public function noChangesUpdateLeaveCount($emp_id,$leave_count){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$leave_count = mysqli_real_escape_string($connect,$leave_count);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE leave_count = '$leave_count' AND emp_id = '$emp_id'"));
		return $num_rows;

	}


	// for checking if exist ung user ng datefrom at date to
	public function existDateFromDateTo($emp_id,$dateFrom,$dateTo,$fileLeaveType){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$fileLeaveType = mysqli_real_escape_string($connect,$fileLeaveType);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE FileLeaveType = '$fileLeaveType' AND dateFrom = '$dateFrom' AND dateTo = '$dateTo' AND emp_id = '$emp_id'"));
		
		//echo $num_rows;

		return $num_rows;

	}


	// for updating leave info if exist 
	public function updateLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$leaveType,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$head_emp_id = mysqli_real_escape_string($connect,$head_emp_id);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$leaveType = mysqli_real_escape_string($connect,$leaveType);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$fileLeaveType = mysqli_real_escape_string($connect,$fileLeaveType);
		$approveStat = mysqli_real_escape_string($connect,$approveStat);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		//$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		//$dateTo = mysqli_real_escape_string($connect,$dateTo);

		$update_qry = "UPDATE tb_leave SET head_emp_id = '$head_emp_id', LeaveType = '$leaveType', lt_id = '$lt_id', Remarks = '$remarks', FileLeaveType = '$fileLeaveType', approveStat = '$approveStat' 
						WHERE emp_id = '$emp_id' AND dateFrom = '$dateFrom' AND dateTo = '$dateTo'";
		$sql = mysqli_query($connect,$update_qry);


	}


	// for deducting the total leave count
	public function deductLeaveCount(){
		$connect = $this->connect();

		//$emp_id = mysqli_real_escape_string($connect,$emp_id);

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

		//echo $final_date_from . "<br/>";
		//echo $final_date_to;

		$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1' AND (role_id != '1' OR dept_id != '1')";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				//echo $row->emp_id . "<br/>";
				$leave = 0;
				$db_leave_count = $row->leave_count;
				


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
			    

			   
			    $emp_id = $row->emp_id;


				// check natin dito kung nakaleave siya

				//echo $final_date_from . "<br/>";
				//echo $final_date_to;

				// logic ibibigay sa array then cocondition kung pasok ung araw na un sa cut off
				//$leaveRange = array();

				$num_rows_leave = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1'"));

				//echo $num_rows_leave;

				if ($num_rows_leave != 0) {
					
					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' OR  FileLeaveType = 'Morning Halfday Leave with pay' OR FileLeaveType = 'Afternoon Halfday Leave with pay') AND LeaveType != 'Reserve Emergency Leave' AND LeaveType != 'Birthday Leave'";
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
						    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date;

						    		// hindi pa siya nag eexist consider as leave na siya
						    		//if ($existAttendanceByDate == 0){

						    			$date_create_leave = date_create($leave_date);
						    			$date_format_leave = date_format($date_create_leave,"l");

						    			// ibig sabihin sakop siya ng leave na mababawasan as late
						    			if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
						    				if ($leave == 0){

						    					if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
						    						$leave = 0.5;
					    						}
					    						else {
						    						$leave = 1;
						    					}
							    			}

							    			else {
							    				if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
							    					$leave = $leave + 0.5;
						    					}
						    					else {
					    							$leave = $leave + 1;
						    					}
							    				
							    			}



						    			}


						    			
						    		//} // end of if
						    	}


						    	


						    	

						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
					    }
				    }

			    }
		    					
			    $new_leave_count = $db_leave_count - $leave;
			  //echo  $emp_id . " " .$leave . "<br/>";
			    $update_qry = "UPDATE tb_employee_info SET leave_count = '$new_leave_count' WHERE emp_id = '$emp_id'";
			    $sql = mysqli_query($connect,$update_qry);



			    $reserve_leave = 0;
			    // for reserve leave
			    if ($num_rows_leave != 0) {
					
					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' AND LeaveType = 'Reserve Emergency Leave')";
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
						    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date;

						    		// hindi pa siya nag eexist consider as leave na siya
						    		//if ($existAttendanceByDate == 0){

						    			$date_create_leave = date_create($leave_date);
						    			$date_format_leave = date_format($date_create_leave,"l");

						    			// ibig sabihin sakop siya ng leave na mababawasan as late
						    			if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
						    				if ($leave == 0){

						    					if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
						    						$reserve_leave = 0.5;
					    						}
					    						else {
						    						$reserve_leave = 1;
						    					}
							    			}

							    			else {
							    				if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
							    					$reserve_leave = $reserve_leave + 0.5;
						    					}
						    					else {
					    							$reserve_leave = $reserve_leave + 1;
						    					}
							    				
							    			}



						    			}


						    			
						    		//} // end of if
						    	}


						    	


						    	

						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
					    }
				    }

			    }


			    if ($reserve_leave != 0){
			    	$update_qry = "UPDATE tb_employee_info SET reserve_emergency_leave = '0' WHERE emp_id = '$emp_id'";
			   	 	$sql = mysqli_query($connect,$update_qry);
			    }




			    $birthday_leave = 0;
			    // for reserve leave
			    if ($num_rows_leave != 0) {
					
					$select_leave_qry = "SELECT * FROM tb_leave WHERE emp_id = '$emp_id' AND approveStat = '1' AND (FileLeaveType = 'Leave with pay' AND LeaveType = 'Birthday Leave')";
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
						    		//$existAttendanceByDate = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id = '$bio_id' AND `date` = '$leave_date'"));

						    		//echo $leave_date;

						    		// hindi pa siya nag eexist consider as leave na siya
						    		//if ($existAttendanceByDate == 0){

						    			$date_create_leave = date_create($leave_date);
						    			$date_format_leave = date_format($date_create_leave,"l");

						    			// ibig sabihin sakop siya ng leave na mababawasan as late
						    			if($date_format_leave != "Saturday" && $date_format_leave != "Sunday"){
						    				if ($leave == 0){

						    					if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
						    						$birthday_leave = 0.5;
					    						}
					    						else {
						    						$birthday_leave = 1;
						    					}
							    			}

							    			else {
							    				if ($row_leave->FileLeaveType == "Morning Halfday Leave with pay" || $row_leave->FileLeaveType == "Afternoon Halfday Leave with pay"){
							    					$birthday_leave = $reserve_leave + 0.5;
						    					}
						    					else {
					    							$birthday_leave = $reserve_leave + 1;
						    					}
							    				
							    			}



						    			}


						    			
						    		//} // end of if
						    	}


						    	


						    	

						    	$leave_counter++;
						    } while($leave_counter < $leave_count);
					    }
				    }

			    }


			    if ($birthday_leave != 0){
			    	$update_qry = "UPDATE tb_employee_info SET birthday_leave = '0' WHERE emp_id = '$emp_id'";
			   	 	$sql = mysqli_query($connect,$update_qry);
			    }





			} // end of if result
		}
	} // end of function



	// for getting the count of pending file salary loan
	public function getFileLeavePendingCount($role,$emp_id){
		$connect = $this->connect();

		//echo $role . "<br/>";

		
		if ($role == 4 || $role == 3){
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE approveStat != '3' AND approveStat = '4' AND head_emp_id = '$emp_id'"));
			return $num_rows;
		}

		else {
			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE approveStat = '0' AND emp_id != '$emp_id'"));
			return $num_rows;
		}
	}


	// update file leave
	public function updateFileLeaveWithPay($leave_id,$leave_type,$lt_id,$dateFrom,$dateTo,$remarks){
		$connect = $this->connect();

		$leave_id = mysqli_real_escape_string($connect,$leave_id);
		$leave_type = mysqli_real_escape_string($connect,$leave_type);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);
		$dateFrom = mysqli_real_escape_string($connect,$dateFrom);
		$dateTo = mysqli_real_escape_string($connect,$dateTo);
		$remarks = mysqli_real_escape_string($connect,$remarks);
	
		$update_qry = "UPDATE tb_leave SET LeaveType = '$leave_type', lt_id = '$lt_id', dateFrom = '$dateFrom', dateTo = '$dateTo', Remarks = '$remarks' WHERE leave_id = '$leave_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for updating leave type halfday leave
	public function updateFileHalfLeaveWithPay($leave_id,$leave_type,$file_leave_type,$date,$remarks){
		$connect = $this->connect();

		$leave_id = mysqli_real_escape_string($connect,$leave_id);
		$leave_type = mysqli_real_escape_string($connect,$leave_type);
		$file_leave_type = mysqli_real_escape_string($connect,$file_leave_type);
		$date = mysqli_real_escape_string($connect,$date);
		$remarks = mysqli_real_escape_string($connect,$remarks);
	
		$update_qry = "UPDATE tb_leave SET LeaveType = '$leave_type', FileLeaveType = '$file_leave_type', dateFrom = '$date', dateTo = '$date', Remarks = '$remarks' WHERE leave_id = '$leave_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// this facility is for 
	public function refereshLeaveCountAnniversary($emp_id,$dateHire,$year_date_hired){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$dateHire = mysqli_real_escape_string($connect,$dateHire);
		$year_date_hired = mysqli_real_escape_string($connect,$year_date_hired);

		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('16 hours'));


		$year = date("Y");

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'm-d');


		if ($dateHire <= $current_date_time && $year_date_hired < $year){

			//echo $dateHire . " " . $current_date_time . "<br/>";

			//echo $year_date_hired . " " . $year;
			//echo "wew";

			//echo $this->checkExistRefreshLeave($emp_id,$year);
			if ($this->checkExistRefreshLeave($emp_id,$year) == 0){
				$this->insertRefreshListLogs($emp_id,$year);
				//echo "wew";
				
				/*$update_qry = "UPDATE tb_employee_info SET leave_count = '10' WHERE emp_id = '$emp_id'";
				$sql = mysqli_query($connect,$update_qry);

				$update_qry = "UPDATE tb_employee_info SET reserve_emergency_leave = '1' WHERE emp_id = '$emp_id'";
				$sql = mysqli_query($connect,$update_qry);


				$update_qry = "UPDATE tb_employee_info SET birthday_leave = '1' WHERE emp_id = '$emp_id'";
				$sql = mysqli_query($connect,$update_qry);*/

		
		
				$leave_array = "";
				$leave_count_array = "";


				$select_query = "SELECT * FROM `tb_leave_type` WHERE status = '1'";
				if ($result_a = mysqli_query($connect,$select_query)){
					while ($row_a = mysqli_fetch_object($result_a)) {



						if ($leave_array == ""){
							$leave_array = $row_a->lt_id; // 1
						}

						else {
							$leave_array .= ",". $row_a->lt_id;
						}

						if ($leave_count_array == ""){
							$leave_count_array = round($row_a->count);
						}

						else {
							$leave_count_array .= ",". round($row_a->count);
						}
						


						
						# code...
					}

					 

				}

				//echo $emp_id;
				//echo " ";
				//print_r($leave_array);
				//echo " ";
				//print_r($leave_count_array);
				//echo "<br/>";

			$update_data_array = "UPDATE tb_emp_leave SET leave_array = '$leave_array', leave_count_array = '$leave_count_array' WHERE emp_id = '$emp_id'";
													
			$sql = mysqli_query($connect,$update_data_array);
			}
		}



	}


	public function insertRefreshListLogs($emp_id,$year){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$year = mysqli_real_escape_string($connect,$year);


		


		$insert_qry = "INSERT INTO tb_refresh_leave (emp_id,year) VALUES ('$emp_id','$year')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// for checking if nabigyan na siya
	public function checkExistRefreshLeave($emp_id,$year){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$year = mysqli_real_escape_string($connect,$year);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_refresh_leave WHERE emp_id = '$emp_id' and year = '$year'"));
		return $num_rows;
	}


	// for checking if nabigyan na siya
	public function checkExistFileReserveEmergencyLeave($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' and LeaveType = 'Reserve Emergency Leave' AND (approveStat = '0' OR approveStat = '4')"));
		return $num_rows;
	}


	// for checking if nabigyan na siya
	public function checkExistFileBirthdayLeave($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave WHERE emp_id = '$emp_id' and LeaveType = 'Birthday Leave' AND (approveStat = '0' OR approveStat = '4')"));
		return $num_rows;
	}


	public function getLeaveValidationToDropdown($lv_id,$status){
		$connect = $this->connect();


		if ($status == "Add"){
	
			$select_qry = "SELECT * FROM tb_leave_validation";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					echo "<option title='".$row->information."' value='".$row->lv_id."'>".$row->name."</option>";
							
				}
			}

		}


		if ($status == "Edit"){
	
			$select_qry = "SELECT * FROM tb_leave_validation";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					$selected = "";
					if ($row->lv_id == $lv_id){
						$selected = "selected='selected'";
					}

					echo "<option ".$selected." title='".$row->information."' value='".$row->lv_id."'>".$row->name."</option>";
							
				}
			}

		}

	}


	public function checkExistLeaveTypeName($name,$lt_id,$status){
		$connect = $this->connect();

		$name = mysqli_real_escape_string($connect,$name);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		if ($status == "Add"){

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave_type WHERE name = '$name'"));
			return $num_rows;
		}

		else if ($status == "Edit"){

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave_type WHERE name = '$name' AND lt_id != '$lt_id'"));
			return $num_rows;
		}
	}

	public function checkExistLeaveValidation($lv_id){
		$connect = $this->connect();

		$lv_id = mysqli_real_escape_string($connect,$lv_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave_validation WHERE lv_id = '$lv_id'"));
		return $num_rows;
	}


	public function checkExistLeaveType($lt_id){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_leave_type WHERE lt_id = '$lt_id'"));
		return $num_rows;
	}


	public function insertLeaveType($name,$lv_id,$no_days_to_file,$count,$is_convertable_to_cash){
		$connect = $this->connect();

		$name = mysqli_real_escape_string($connect,$name);
		$lv_id = mysqli_real_escape_string($connect,$lv_id);
		$no_days_to_file = mysqli_real_escape_string($connect,$no_days_to_file);
		$count = mysqli_real_escape_string($connect,$count);
		$is_convertable_to_cash = mysqli_real_escape_string($connect,$is_convertable_to_cash);


		$insert_qry = "INSERT INTO tb_leave_type (name,lv_id,no_days_to_file,count,is_convertable_to_cash) VALUES ('$name','$lv_id','$no_days_to_file','$count','$is_convertable_to_cash')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	public function getLeaveValidationById($lv_id){
		$connect = $this->connect();

		$lv_id = mysqli_real_escape_string($connect,$lv_id);

		$select_qry = "SELECT * FROM tb_leave_validation WHERE lv_id = '$lv_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	public function getLeaveTypeById($lt_id){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$select_qry = "SELECT * FROM tb_leave_type WHERE lt_id = '$lt_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}

	public function getLeaveType(){
		$connect = $this->connect();

		
		
		$select_qry = "SELECT * FROM tb_leave_type";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$info = "";


				$row_lv = $this->getLeaveValidationById($row->lv_id);

				$info .= "<b class='color-blue'>Validation:</b> " . $row_lv->name . "&nbsp;<a data-toggle='popover' id='hover_info' title='Validation Information' data-content='".$row_lv->information."' style='color:#337ab7;cursor:pointer;'><span class='glyphicon glyphicon-question-sign'></span></a>";

				$info .= "<br/>";

				if ($row->no_days_to_file != 0){
					$info .= "<b class='color-blue'>Set Days: </b>" . $row->no_days_to_file;
					$info .= "<br/>";


				}

				$info .= "<b class='color-blue'>Leave Count:</b> " . $row->count;


				$status = "<label class='label label-success'>Active</label>";

				$status_txt = "Inactive";

				if ($row->status == 0){
					$status = "<label class='label label-warning'>Inactive</label>";
					$status_txt = "Activate";
				}

				$is_convertable_to_cash = "Yes";
				if ($row->is_convertable_to_cash == 0){
					$is_convertable_to_cash = "No";
				}

				$info .= "<br/>";
				$info .= "<b class='color-blue'>Convertable To Cash: </b>" . $is_convertable_to_cash;



				echo "<tr id='".$row->lt_id."'>";
					echo "<td><b>".$row->name."</b></td>";
					echo "<td>".$info."</td>";
					echo "<td>".$status."</td>";
					echo "<td>";

						if ($row->name != "Formal Leave"){
							echo "<button id='edit_leave_type' class='btn btn-success btn-xs' type='button'>Edit</button>";
							echo "&nbsp;";
							echo "<button id='active_leave_type' class='btn btn-default btn-xs' type='button'>".$status_txt."</button>";
							echo "&nbsp;";

							// check tb_emp_leave

							if ($this->checkExistEmpLeaveInfo($row->lt_id) == 0){

								echo "<button class='btn btn-danger btn-xs' type='button' id='delete_leave_type'>Delete</button>";
							}
						}
						else {
							echo "No Action";
						}
					echo "</td>";
				echo "</tr>";
								
			}
		}


		
		

	}


	public function editLeaveType($name,$lv_id,$no_days_to_file,$count,$is_convertable_to_cash,$lt_id){
		$connect = $this->connect();

		$name = mysqli_real_escape_string($connect,$name);
		$lv_id = mysqli_real_escape_string($connect,$lv_id);
		$no_days_to_file = mysqli_real_escape_string($connect,$no_days_to_file);
		$count = mysqli_real_escape_string($connect,$count);
		$is_convertable_to_cash = mysqli_real_escape_string($connect,$is_convertable_to_cash);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);


		$update_qry = "UPDATE tb_leave_type SET name = '$name',lv_id = '$lv_id',no_days_to_file = '$no_days_to_file',count = '$count',is_convertable_to_cash = '$is_convertable_to_cash' WHERE lt_id = '$lt_id'";
		$sql = mysqli_query($connect,$update_qry);
	}




	public function checkExistEmpLeaveInfo($lt_id){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

			
		$exist = false;
		$select_qry = "SELECT * FROM tb_emp_leave";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$leave_array = $row->leave_array;

				$leave_array_explode = explode(",", $leave_array);

				$count = count($leave_array_explode);

				$counter = 0;

				

				if ($exist == false){

					do {

						if ($leave_array_explode[$counter] == $lt_id){
							$exist = true;
						}

						$counter++;
					}while($count > $counter);
				}


			}
		}

		$exist_count = 0;
		if ($exist == true){
			$exist_count = 1;
		}

		return $exist_count;

	}


	public function deleteLeaveType($lt_id){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$delete_qry = "DELETE FROM tb_leave_type WHERE lt_id='$lt_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	public function activeLeaveType($lt_id,$status){
		$connect = $this->connect();

		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$delete_qry = "UPDATE tb_leave_type SET status = '$status' WHERE lt_id='$lt_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	public function getLeaveTypeToDropdown($lt_id,$status){
		$connect = $this->connect();


		if ($status == "Add"){
			

			$select_qry = "SELECT * FROM tb_leave_type WHERE status = '1'";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					echo "<option value='".$row->lt_id."'>".$row->name."</option>";
							
				}
			}

		}


		if ($status == "Edit"){
	
			$select_qry = "SELECT * FROM tb_leave_type";
			if ($result = mysqli_query($connect,$select_qry)){
				while ($row = mysqli_fetch_object($result)){

					$selected = "";
					if ($row->lt_id == $lt_id){
						$selected = "selected='selected'";
					}

					echo "<option ".$selected." value='".$row->lt_id."'>".$row->name."</option>";
							
				}
			}

		}

	}




	public function leaveValidationScript($lv_id,$date_from,$date_to,$no_days_to_file,$emp_id){


		$date_today = date("Y-m-d");


		$message = "";
		if ($lv_id == 1){

			$date_to_file = date('Y-m-d', strtotime('-'.$no_days_to_file.' day', strtotime($date_from)));

			if ($date_today <= $date_to_file){ // 2020-02-10 <= 2020-01-11
				//$can_file = true;
			}

			else {
				$message = "Must File Before <b>".$no_days_to_file."</b> days and above";
			}

		}

		else if ($lv_id == 2){

			$date_to_file = date('Y-m-d', strtotime('+'.$no_days_to_file.' day', strtotime($date_to))); // 

			if ($date_today <= $date_to_file){ // 2020-02-17 <= 2020-02-16
				//$can_file = true;
			}

			else {
				$message = "Must File After <b>".$no_days_to_file."</b> days and below";
			}

		}

		else if ($lv_id == 3){

			if ($this->checkExistPetInfoByEmpId($emp_id) == 1){
				//$can_file = true;
			}

			else {
				$message = "There is no registed pet in the system";
			}
		}

		else if ($lv_id == 4){
			//$can_file = true;
		}


		return $message;

	}



	public function checkExistPetInfoByEmpId($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pet_info WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	public function LeaveArray(){

		$connect = $this->connect();
		

		$check_exist = 0;
		$sql_query =  "SELECT * FROM `tb_employee_info` WHERE ActiveStatus = '1' AND emp_id > '1'";
		if ($result = mysqli_query($connect,$sql_query)){
				while($row = mysqli_fetch_object($result)){
							

					if($row->emp_id != 0 ){

							$emp_id = $row->emp_id;
							
							$leave_array = "";
							$leave_count_array = "";




						$select_query = "SELECT * FROM `tb_leave_type` WHERE status = '1'";
						if ($result_a = mysqli_query($connect,$select_query)){
							while ($row_a = mysqli_fetch_object($result_a)) {



								if ($leave_array == ""){
									$leave_array = $row_a->lt_id; // 1
								}

								else {
									$leave_array .= ",". $row_a->lt_id;
								}

								if ($leave_count_array == ""){
									$leave_count_array = round($row_a->count);
								}

								else {
									$leave_count_array .= ",". round($row_a->count);
								}
								


								
								# code...
							}

							 

						}


						//echo $emp_id;
						//echo " ";
						//print_r($leave_array);
						//echo " ";
						//print_r($leave_count_array);
						//echo "<br/>";

					$insert_data_array = "INSERT INTO tb_emp_leave (emp_id,leave_array,leave_count_array)
															VALUES ('$emp_id','$leave_array','$leave_count_array')";

					$sql = mysqli_query($connect,$insert_data_array);


					}



					
		    // Success! 
		

					   								
			}
		}		

	}


	public function getEmpLeaveCountByEmpIdLtId($emp_id,$lt_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$lt_id = mysqli_real_escape_string($connect,$lt_id);

		$remaining_leave = 0;

		$select_qry = "SELECT * FROM tb_emp_leave WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$leave_array_explode =explode("," ,$row->leave_array);
				$leave_count_array_explode =explode("," ,$row->leave_count_array);

				$counter = 0;

				$count = count($leave_array_explode);


				do{

					

					if ($leave_array_explode[$counter] == $lt_id){
						$remaining_leave = $leave_count_array_explode[$counter];
					}

					


					$counter++;
				}while($count > $counter);


			}

		}
		return $remaining_leave;
	}


}


?>