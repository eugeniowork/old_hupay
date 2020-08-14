<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/working_days_class.php";
include "../class/holiday_class.php";

if (isset($_POST["leave_id"]) && isset($_POST["update_leaveType"]) && isset($_POST["update_dateFrom_Leave"]) 
	&& isset($_POST["update_dateTo_Leave"]) && isset($_POST["update_remarks_Leave"])){

	$date_class = new date;
	$leave_id = $_POST["leave_id"];
	$leave_type = $_POST["update_leaveType"];
	$dateFrom = $date_class->dateDefaultDb($_POST["update_dateFrom_Leave"]);
	$dateTo = $date_class->dateDefaultDb($_POST["update_dateTo_Leave"]);
	$remarks = $_POST["update_remarks_Leave"];

	$leave_class = new Leave;
	$emp_info_class = new EmployeeInformation;
	$working_days_class = new WorkingDays;
	$holiday_class = new Holiday;

	$row = $leave_class->getInfoByLeaveId($leave_id);
	$fileLeaveType = $row->FileLeaveType;
	
	$date1=date_create(date_format(date_create($_POST["update_dateFrom_Leave"]),"Y-m-d"));
	$date2=date_create(date_format(date_create($row->DateCreated),"Y-m-d"));
	$diff =date_diff($date1,$date2);
	$wew =  $diff->format("%R%a");
	$days = str_replace("+","",$wew);


	$exist_leave_type = 0;
	$no_days_to_file = 0;
	$lv_id = 0;
	$name = "";
	
	if ($leave_class->checkExistLeaveType($leave_type) == 1){

		$row_leave_type = $leave_class->getLeaveTypeById($leave_type);

		$lv_id = $row_leave_type->lv_id;
		$no_days_to_file = $row_leave_type->no_days_to_file;
		$name = $row_leave_type->name;
		//$db_leave_count = $row_leave_type->count;

		$exist_leave_type = 1;
	}

	$row = $emp_info_class->getEmpInfoByRow($row->emp_id);
	$bio_id = $row->bio_id;
	$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

	$day_from = $row_wd->day_from;
	$day_to = $row_wd->day_to;

	// first check muna antin kung exist ung leave type
	if ($exist_leave_type == 0){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during filing of leave";
	}

	/*if ($leave_type == "Vacation Leave" && $days >= -1){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be 2 days before hands";
	}*/

	/*else if ($leave_type == "Reserve Emergency Leave" && $dateFrom != $dateTo){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be equal to <b>Date To</b>";
	}


	else if ($leave_type == "Birthday Leave" && $dateFrom != $dateTo){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be equal to <b>Date To</b>";
	}*/

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["update_dateFrom_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> not match to the current format mm/dd/yyyy";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["update_dateTo_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date To</b> not match to the current format mm/dd/yyyy";
	}

	else if ($dateFrom > $dateTo) {
		$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}
	else {

		//$leave_class->updateFileLeaveWithPay($leave_id,$leave_type,$dateFrom,$dateTo,$remarks);
		//$_SESSION["success_crud_leave"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File Leave is successfully updated.</center>";

	

		$lt_id = $leave_type;



		$remaining_leave_count = $leave_class->getEmpLeaveCountByEmpIdLtId($row->emp_id,$lt_id);
		// success
		
		//echo "wew";
		

		$dateFrom = $date_class->dateDefaultDb($_POST["update_dateFrom_Leave"]);
		$dateTo = $date_class->dateDefaultDb($_POST["update_dateTo_Leave"]);







		$dates = array();
		$from = strtotime($dateFrom);
		$last = strtotime($dateTo);
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


		$days_count = 0;
		do {
			 $date_create = date_create($dates[$counter]);
			 $day = date_format($date_create, 'w'); // 


			

			if ($day >= $day_from && $day <= $day_to){

				//echo $day . " " . $day_from . "<br/>";

				$weekdays[] = $dates[$counter];
				$date =  $dates[$counter]; 
				//echo $date . "<br/>";  		
				$weekdays_count++; // for echo condition

				//if ($date < date("Y-m-d")){


		    		//$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_attendance WHERE bio_id='$bio_id' AND `date` = '$date'"));
		    		//if ($num_rows == 0) {


		    			// $date_create = ;
				$holiday = date_format(date_create($date), 'F j'); // Day of the month without leading zeros  	1 to 31
				//echo $holiday . "<br/>";

				//echo $holiday;
				$holiday_num_rows = $holiday_class->checkExistHoliday($holiday);
    			

    			//$granted = "Granted";
    			if (!$holiday_num_rows == 1){
    				//echo "Wew";
    				$days_count++;

    				//echo $
    			}

    			
		    			
		    		//}

		    		/*(else {
		    			$present += 1;
		    		}*/
				//}
			}

			//echo $dates[$counter];
			
			$counter++;
			

		}while($counter <= $count);

		//echo $dateFrom . "<br/>";
		//echo $dateTo;
	
		$dateCreated = $date_class->getDate();

		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);
		$head_emp_id = $row_emp->head_emp_id;
		//echo $head_emp_id . "<br/>";
		//$fileLeaveType = $_GET["FileLeaveType"];

		$notifFileLeave = "File Leave";
		//if ($fileLeaveType == "Leave without pay") {
		//	$notifFileLeave = "File Formal Leave";
		//}


		//$leave_count = $row_emp->leave_count + $row_emp->reserve_emergency_leave + $row_emp->birthday_leave;
		//echo $leave_count . "<br/>";


		//$leave_range_count = ((strtotime($dateTo) - strtotime($dateFrom)) / 86400) + 1;
		//echo $leave_range_count . "<br/>";

		
		if ($days_count <= $remaining_leave_count){
			// for inserting

			// approveStat
			$approveStat = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$approveStat = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$approveStat = 0;
			}

			$lt_id = $leave_type;



			if ($leave_class->existDateFromDateTo($row->emp_id,$date_class->dateDefaultDb($_POST["update_dateFrom_Leave"]),$date_class->dateDefaultDb($_POST["update_dateTo_Leave"]),$fileLeaveType) != 0){



				//echo "wew 1";
				$leave_class->updateLeave($row->emp_id,$head_emp_id,$dateFrom,$dateTo,$name,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated);
			}

			else {

				//echo "wew";

				// approveStat
				$approveStat = 0;
				// ibig sabihin staff xa
				if ($head_emp_id != 0){
					$approveStat = 4;
				}

				// ibig sabihin head xa
				else if ($head_emp_id == 0){
					$approveStat = 0;
				}


				//$leave_class->insertLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$name,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated); 
				$leave_class->updateFileLeaveWithPay($leave_id,$name,$lt_id,$dateFrom,$dateTo,$remarks);
				//echo "Wew 2";
			}
			


			$_SESSION["success_crud_leave"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File Leave is successfully updated.</center>";
		} // end of if 
		else {
			$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You cannot file leave with pay because your remaining leave count is less than the date range you've enter.</center>";
		}
	}
	//echo $leave_id;
	header("Location:../view_attendance.php");
}
else {
	header("Location:../MainForm.php");
}

?>