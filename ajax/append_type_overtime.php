<?php
session_start();
include "../class/connect.php";
include "../class/holiday_class.php";
include "../class/emp_information.php";
include "../class/working_days_class.php";

if (isset($_POST["date"])){

	$emp_id = $_SESSION["id"];



	$emp_info_class = new EmployeeInformation;
	$working_days_class = new WorkingDays;

	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$bio_id = $row->bio_id;
	$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

	$day_from = $row_wd->day_from;
	$day_to = $row_wd->day_to;




	$date_create = date_create($_POST["date"]);
	$day = date_format($date_create, 'l');

    $day_of_the_week = date_format($date_create, 'w'); // 


    // not restday
    if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){

	}


	//rest day
	else {

	}


	//echo $day;

	
	$day_month = date_format($date_create, 'j');
	$month = date_format($date_create, 'F');

	//echo $day_month;
	//echo $month;

	$holiday_class = new Holiday;

	//check if exist holiday
	$num_rows = $holiday_class->dateIsHoliday($month,$day_month);
	//echo "<option value=''></option>"; // for header

	// ibig sabihin holiday un
	if ($num_rows == 1) {
		$row = $holiday_class->getHolidayInfoByMonthDay($month,$day_month);
		$holidayType = $row->holiday_type;

		if ($holidayType != "Regular Holiday") {
			$holidayType = "Special Holiday";
		}


		/*if ($day == "Saturday" || $day == "Sunday") {
			echo 'Restday / '.$holidayType;
			//echo '<option value="Restday / '.$holidayType.'">Restday / '.$holidayType.'</option>';
			//echo '<option value="Restday / Holiday">Restday / Holiday</option>';
		}
		else {
			echo $holidayType;
			//echo '<option value="'.$holidayType.'">'.$holidayType.'</option>';
			// check ko kung regular day lang, special holiday or regular holiday
			//echo '<option value="Regular">Regular</option>';
			//echo '<option value="Holiday">Holiday</option>';
			//echo '<option value="Restday">Restday</option>';
		}*/


		 // not restday
	    if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
	    	echo $holidayType;
		}


		//rest day
		else {
			echo 'Restday / '.$holidayType;
		}
	}

	else {


		if ($day_of_the_week >= $day_from && $day_of_the_week <= $day_to){
			echo "Regular";
		}

		else {
			echo "Restday";
		}

		/*if ($day == "Saturday" || $day == "Sunday") {
			echo "Restday";
			//echo '<option value="Restday">Restday</option>';
			//echo '<option value="Restday / Holiday">Restday / Holiday</option>';
		}
		else {
			echo "Regular";
			// check ko kung regular day lang, special holiday or regular holiday
			//echo '<option value="Regular">Regular</option>';
			//echo '<option value="Holiday">Holiday</option>';
			//echo '<option value="Restday">Restday</option>';
		}*/


	}
	
	

	
}
else {
	header("Location:../MainForm.php");
}

?>