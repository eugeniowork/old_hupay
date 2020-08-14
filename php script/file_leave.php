<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/attendance_notifications.php";
include "../class/working_days_class.php";
include "../class/time_in_time_out.php";
include "../class/holiday_class.php";
// leaveType
if (isset($_POST["leaveType"]) && isset($_POST["dateFrom_Leave"]) && isset($_POST["dateTo_Leave"]) && isset($_POST["remarks_Leave"])){

	$emp_id = $_SESSION["id"];
	$date_class = new date;

	$leaveType = $_POST["leaveType"];
	//$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom_Leave"]);
	//$dateTo = $date_class->dateDefaultDb($_POST["dateTo_Leave"]);
	$remarks = $_POST["remarks_Leave"];

	$dateFrom_month = substr($_POST["dateFrom_Leave"],0,2);
	$dateFrom_day = substr(substr($_POST["dateFrom_Leave"], -7), 0,2);
	$dateFrom_year = substr($_POST["dateFrom_Leave"], -4);

	$dateTo_month = substr($_POST["dateTo_Leave"],0,2);
	$dateTo_day = substr(substr($_POST["dateTo_Leave"], -7), 0,2);
	$dateTo_year = substr($_POST["dateTo_Leave"], -4);

	/*echo $leaveType . "<br/>";
	echo $dateFrom . "<br/>";
	echo $dateTo . "<br/>";
	echo $remarks . "<br/>";
	*/


	$date1=date_create(date_format(date_create($_POST["dateFrom_Leave"]),"Y-m-d"));
	$date2=date_create(date_format(date_create(date("Y-m-d")),"Y-m-d"));
	$diff =date_diff($date1,$date2);
	$wew =  $diff->format("%R%a");
	$days = str_replace("+","",$wew);

	$emp_info_class = new EmployeeInformation;
	$leave_class = new Leave;
	$working_days_class = new WorkingDays;
	$attendance_class = new Attendance;
	$holiday_class = new Holiday;

	$exist_leave_type = 0;
	$no_days_to_file = 0;
	$lv_id = 0;
	$name = "";
	
	if ($leave_class->checkExistLeaveType($leaveType) == 1){

		$row_leave_type = $leave_class->getLeaveTypeById($leaveType);

		$lv_id = $row_leave_type->lv_id;
		$no_days_to_file = $row_leave_type->no_days_to_file;
		$name = $row_leave_type->name;
		//$db_leave_count = $row_leave_type->count;

		$exist_leave_type = 1;
	}

	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$bio_id = $row->bio_id;
	$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

	$day_from = $row_wd->day_from;
	$day_to = $row_wd->day_to;




	// first check muna antin kung exist ung leave type
	if ($exist_leave_type == 0){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during filing of leave";
	}


	else if ($leave_class->leaveValidationScript($lv_id,$_POST["dateFrom_Leave"],$_POST["dateTo_Leave"],$no_days_to_file,$emp_id) != ""){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> " .  $leave_class->leaveValidationScript($lv_id,$_POST["dateFrom_Leave"],$_POST["dateTo_Leave"],$no_days_to_file,$emp_id);
	}


	/*else if ($leaveType == "Vacation Leave" && $days >= -1){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be 2 days before hands";
	}*/

	/*else if ($leaveType == "Reserve Emergency Leave" && $_POST["dateFrom_Leave"] != $_POST["dateTo_Leave"]){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be equal to <b>Date To</b>";
	}*/


	/*else if ($leaveType == "Birthday Leave" && $_POST["dateFrom_Leave"] != $_POST["dateTo_Leave"]){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> must be equal to <b>Date To</b>";
	}*/

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["dateFrom_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> not match to the current format mm/dd/yyyy";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["dateTo_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date To</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($dateFrom_year % 4 == 0 && $dateFrom_month == 2 && $dateFrom_day >= 30){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	// for validating leap year also
	else if ($dateFrom_year % 4 != 0 && $dateFrom_month == 2 && $dateFrom_day >= 29){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	// mga month na may 31
	else if (($dateFrom_month == 4 || $dateFrom_month == 6 || $dateFrom_month == 9 || $dateFrom_month == 11)
			&& $dateFrom_day  >= 31){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	

	// for validating leap year
	else if ($dateTo_year % 4 == 0 && $dateTo_month == 2 && $dateTo_day >= 30){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	// for validating leap year also
	else if ($dateTo_year % 4 != 0 && $dateTo_month == 2 && $dateTo_day >= 29){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	// mga month na may 31
	else if (($dateTo_month == 4 || $dateTo_month == 6 || $dateTo_month == 9 || $dateTo_month == 11)
			&& $dateTo_day  >= 31){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	else if ($_POST["dateFrom_Leave"] > $_POST["dateTo_Leave"]) {
		$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}




	// for checking if exist na ung date from at date to
	//else if (){
	//	echo "Has Exist!";
	//}


	else {

		//echo "wew";


		$lt_id = $leaveType;



		$remaining_leave_count = $leave_class->getEmpLeaveCountByEmpIdLtId($emp_id,$lt_id);
		// success
		
		//echo "wew";
		

		$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom_Leave"]);
		$dateTo = $date_class->dateDefaultDb($_POST["dateTo_Leave"]);







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

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
		$head_emp_id = $row_emp->head_emp_id;
		//echo $head_emp_id . "<br/>";
		$fileLeaveType = $_GET["FileLeaveType"];



		// for formal leave
		$can_file = false;
		if ($name == "Formal Leave"){
			$fileLeaveType = "Leave without pay";
			$can_file = true;
		}

		$notifFileLeave = "File Leave";
		//if ($fileLeaveType == "Leave without pay") {
		//	$notifFileLeave = "File Formal Leave";
		//}


		//$leave_count = $row_emp->leave_count + $row_emp->reserve_emergency_leave + $row_emp->birthday_leave;
		//echo $leave_count . "<br/>";


		//$leave_range_count = ((strtotime($dateTo) - strtotime($dateFrom)) / 86400) + 1;
		//echo $leave_range_count . "<br/>";

		
		if ($days_count <= $remaining_leave_count || $can_file == true){
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

			$lt_id = $leaveType;



			if ($leave_class->existDateFromDateTo($emp_id,$date_class->dateDefaultDb($_POST["dateFrom_Leave"]),$date_class->dateDefaultDb($_POST["dateTo_Leave"]),$fileLeaveType) != 0){



				$leave_class->updateLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$name,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated);
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

				//echo "Wew";

				$leave_class->insertLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$name,$lt_id,$remarks,$fileLeaveType,$approveStat,$dateCreated); 
			}
			

			// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));
			//echo $emp_id_values[0];

			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;
			//echo $count;
			$final_date_from = $date_class->dateFormat($dateFrom);
			$final_date_to = $date_class->dateFormat($dateTo);

			

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				//echo $emp_id . "<br/>";
				
				$approver_id = $_SESSION["id"];
				$notifType = "File ".$name." from $final_date_from to $final_date_to";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				// wla muna notification sa file leave
				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,'0','0',$leave_class->leaveLastId(),$notifType,$notifFileLeave,$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)
				

				$counter++;
			}while($counter <= $count);

			$_SESSION["file_leave_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> File <b>$name</b> From <b>$final_date_from</b> to <b>$final_date_to</b> is successfully filed</center>";
		} // end of if 
		else {
			$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You cannot file leave with pay because your remaining leave count is less than the date range you've enter.</center>";
		}

		
		

	}


	header("Location:../view_attendance.php");
}

else {
	header("Location:../MainForm.php");
}

?>