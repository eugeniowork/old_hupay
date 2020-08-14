<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/attendance_overtime.php";
include "../class/time_in_time_out.php";
include "../class/date.php";
include "../class/attendance_notifications.php";
include "../class/holiday_class.php";
include "../class/working_hours_class.php";
include "../class/working_days_class.php";
//include "../class/time_in_time_out.php";

if (isset($_POST["attendance_date_ot"]) && isset($_POST["hour_time_out"]) && isset($_POST["min_time_out"]) && isset($_POST["period_time_out"]) && isset($_POST["remarks"])){

	$emp_id = $_SESSION["id"];


	$emp_info_class = new EmployeeInformation;
	$working_days_class = new WorkingDays;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

	$day_from = $row_wd->day_from;
	$day_to = $row_wd->day_to;


	$head_emp_id = $row->head_emp_id;


	$attendace_overtime_class = new Attendance_Overtime;
	$date_class = new date;
	$holiday_class = new Holiday;
	$working_hours_class = new WorkingHours;
	$attendance_class = new Attendance;
	


	$row_working_hours = $working_hours_class->getWorkingHoursInfoById($row->working_hours_id);
	$working_hours_time_out = $row_working_hours->timeTo;

	$date_ot_attendance = date_create($_POST["attendance_date_ot"]);
	$day = date_format($date_ot_attendance, 'l');
    $day_of_the_week = date_format($date_ot_attendance, 'w'); // 

	//echo $day;

	
	$day_month = date_format($date_ot_attendance, 'j');
	$month = date_format($date_ot_attendance, 'F');


	$num_rows = $holiday_class->dateIsHoliday($month,$day_month);
	//echo "<option value=''></option>"; // for header

	// ibig sabihin holiday un
	$otType = "";
	if ($num_rows == 1) {
		$row = $holiday_class->getHolidayInfoByMonthDay($month,$day_month);
		$holidayType = $row->holiday_type;

		if ($holidayType != "Regular Holiday") {
			$holidayType = "Special Holiday";
		}


		/*if ($day == "Saturday" || $day == "Sunday") {
			$otType =  'Restday / '.$holidayType;
			//echo '<option value="Restday / '.$holidayType.'">Restday / '.$holidayType.'</option>';
			//echo '<option value="Restday / Holiday">Restday / Holiday</option>';
		}
		else {
			$otType = $holidayType;
			//echo '<option value="'.$holidayType.'">'.$holidayType.'</option>';
			// check ko kung regular day lang, special holiday or regular holiday
			//echo '<option value="Regular">Regular</option>';
			//echo '<option value="Holiday">Holiday</option>';
			//echo '<option value="Restday">Restday</option>';
		}*/

		if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
	    	$otType = $holidayType;
		}


		//rest day
		else {
			$otType = 'Restday / '.$holidayType;
		}
	}

	else {


		/*if ($day == "Saturday" || $day == "Sunday") {
			$otType = "Restday";
			//echo '<option value="Restday">Restday</option>';
			//echo '<option value="Restday / Holiday">Restday / Holiday</option>';
		}
		else {
			$otType = "Regular";
			// check ko kung regular day lang, special holiday or regular holiday
			//echo '<option value="Regular">Regular</option>';
			//echo '<option value="Holiday">Holiday</option>';
			//echo '<option value="Restday">Restday</option>';
		}*/

		if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
			$otType = "Regular";
		}

		else {
			$otType = "Restday";
		}


	}

	


	//$date_create = date_create($attendance_date);
	//$date_create = ;
	//$day = ;

	//$biod_id = $row->bio_id;

	if ($otType != "Regular" && isset($_POST["hour_time_in"]) && isset($_POST["min_time_in"]) && isset($_POST["period_time_in"])) {
		// time in
		$hour_time_in = $_POST["hour_time_in"];
		if ($hour_time_in < 10 && strlen($hour_time_in) == 1){
			$hour_time_in = "0" . $hour_time_in;
		}
		$min_time_in = $_POST["min_time_in"];
		//echo $min_time_in . "<br/>";
		if ($min_time_in < 10 && strlen($min_time_in) == 1){
			$min_time_in = "0" . $min_time_in;
		}
		//$sec_time_in = $_POST["sec_time_in"];
		$period_time_in = $_POST["period_time_in"];
		if ($period_time_in == "PM" && $hour_time_in != 12){
			$hour_time_in = $hour_time_in + 12;
		}

		$time_from = $hour_time_in . ":" . $min_time_in . ":" . "00";
	}

	else {
		$period_time_in = "PM"; // for condition lang nmn ok lang din kung AM to
		$time_from = $working_hours_time_out;
	}

	// time out
	$hour_time_out = $_POST["hour_time_out"];

	if ($hour_time_out < 10 && strlen($hour_time_out) == 1){
		$hour_time_out = "0" . $hour_time_out;
	}

	$min_time_out = $_POST["min_time_out"];

	if ($min_time_out < 10 && strlen($min_time_out) == 1){
		$min_time_out = "0" . $min_time_out;
	}
	
	//$sec_time_out = $_POST["sec_time_out"];
	$period_time_out = $_POST["period_time_out"];
	if ($period_time_out == "PM" && $hour_time_out != 12){
		$hour_time_out = $hour_time_out + 12;
	}
	
	$time_out = $hour_time_out . ":" . $min_time_out . ":" . "00";

	$type_ot = $otType;
	
	$remarks = $_POST["remarks"];

	$current_date = $date_class->getDate();


	$attendance_date_ot_month = substr($_POST["attendance_date_ot"],0,2);
	$attendance_date_ot_day = substr(substr($_POST["attendance_date_ot"], -7), 0,2);
	$attendance_date_ot_year = substr($_POST["attendance_date_ot"], -4);

	$attendance_class = new Attendance;


	echo $time_from . " " . $time_out;


	// if file is not valid
	/*if ($current_date > $attendance_date) {
		$date_create = date_create($current_date);
		$current_date = date_format($date_create, 'F d, Y');


		$date_create = date_create($attendance_date);
		$attendance_date = date_format($date_create, 'F d, Y');


		$_SESSION["add_error_overtime"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You cannot file overtime with the date <b>".$attendance_date."</b> because it is before <b>$current_date</b>.</center>";
	} */

	// if failed
	//else if ($attendance_class->getRowsTimeInOut($attendance_date,$biod_id) != 0){
	//	$_SESSION["add_error_overtime"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The date <b>".$_POST["attendance_date_ot"]."</b> is already exist in your attendance list.</center>";
	//}

	// if edited in the inspect element
	

	if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["attendance_date_ot"])) {
    	$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>OT Date</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($attendance_date_ot_year % 4 == 0 && $attendance_date_ot_month == 2 && $attendance_date_ot_day >= 30){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>OT Date</b> date";
	}

	// for validating leap year also
	else if ($attendance_date_ot_year % 4 != 0 && $attendance_date_ot_month == 2 && $attendance_date_ot_day >= 29){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>OT Date</b> date";
	}

	// mga month na may 31
	else if (($attendance_date_ot_month == 4 || $attendance_date_ot_month == 6 || $attendance_date_ot_month == 9 || $attendance_date_ot_month == 11)
			&& $attendance_date_ot_day  >= 31){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>OT Date</b> date";
	}


	/*else if ((date_format(date_create($_POST["attendance_date_ot"]), 'l') == "Saturday" || date_format(date_create($_POST["attendance_date_ot"]), 'l') == "Sunday") && ($type_ot != "Restday" && $type_ot != "Restday / Special Holiday" && $type_ot != "Restday / Regular Holiday")){
		//echo $type_ot . "</br>";
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Theres an error during saving of data.</center>";
	}

	else if ((date_format(date_create($_POST["attendance_date_ot"]), 'l') != "Saturday" && date_format(date_create($_POST["attendance_date_ot"]), 'l') != "Sunday") && ($type_ot != "Regular" && $type_ot != "Regular Holiday" && $type_ot != "Special Holiday")){
		

		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Theres an error during saving of data.</center>";
	}*/

	// if the value of period time in and period time out is not AM and PM
	//else if ($period_time_in != "AM" && $period_time_in != "PM" && $period_time_out != "AM" && $period_time_out != "PM"){
	else if (($period_time_in != "AM" && $period_time_in != "PM") || ($period_time_out != "AM" && $period_time_out != "PM")){
		
		//echo $type_ot . "</br>";
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Theres an error during saving of data.</center>";
	}

	// kapag mas malaki ung time out sa time in
	else if ($time_out <= $time_from){
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time out</b> cannot greater than or equal to <b>Time in</b>.</center>";
	}





	else {
		

		$attendance_date = $date_class->dateDefaultDb($_POST["attendance_date_ot"]);
		// if exist update lang
		if ($attendace_overtime_class->existOvertime($emp_id,$attendance_date)) {

			$approve_stat = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$approve_stat = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$approve_stat = 0;
			}

			$attendace_overtime_class->updateAttendanceSpecificOT($time_from,$time_out,$type_ot,$remarks,$approve_stat,$current_date,$attendance_date,$emp_id,$head_emp_id);
		}
		// insert
		else {

			$approve_stat = 0;
			// ibig sabihin staff xa
			if ($head_emp_id != 0){
				$approve_stat = 4;
			}

			// ibig sabihin head xa
			else if ($head_emp_id == 0){
				$approve_stat = 0;
			}


			//insertOvertime($emp_id,$date,$time_from,$time_out,$overtime_type,$remarks,$dateCreated
			$attendace_overtime_class->insertOvertime($emp_id,$head_emp_id,$attendance_date,$time_from,$time_out,$type_ot,$remarks,$approve_stat,$current_date);
		}


		// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
		$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));


		$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;

		$final_attendance_date = $date_class->dateFormat($attendance_date);


		$date_create = date_create($time_from);
		$final_time_from = date_format($date_create, 'g:i A');

		$date_create = date_create($time_out);
		$final_time_out = date_format($date_create, 'g:i A');

		$counter = 0;
		do {

			$emp_id = $emp_id_values[$counter];
			$createNotifEmpId = $_SESSION["id"];
			$notifType = "File Overtime on $final_attendance_date from $final_time_from and time out $final_time_out";
			$status = "Pending";
			$dateTime = $date_class->getDateTime();

			$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
			$attendance_notifications_class->insertNotifications($emp_id,$createNotifEmpId,'0',$attendace_overtime_class->attendanceOTLastId(),'0',$notifType,"Attendance OT",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)


			$counter++;
		}while($counter <= $count);

		$_SESSION["add_success_file_ot"] = "success";
	}



	header("Location:../view_attendance.php");

}
else {
	header("Location:../MainForm.php");
}


?>