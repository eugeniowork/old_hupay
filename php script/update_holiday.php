<?php
session_start();
include "../class/connect.php";
include "../class/date.php";
include "../class/holiday_class.php";

// if ok
if (isset($_POST["holidayDate_month"]) && isset($_POST["holidayDate_day"]) && isset($_POST["holidayName"]) && isset($_POST["holidayDate_type"])) {

	$holiday_id = $_SESSION["update_holiday_info"];

	$month = $_POST["holidayDate_month"];
	$day = $_POST["holidayDate_day"];
	$holidayName = $_POST["holidayName"];
	$holidayType = $_POST["holidayDate_type"];

	$date_class = new date;
	$current_date = $date_class->getDate();

	$holiday_class = new Holiday;


	// FOR FEBRUARY
	$year = date("Y");
	// if has leap year
	if ($year % 4 == 0) {
		$total_day = 29;
	}
	// if not a leap year
	else {
		$total_day = 28;
	}


	// if edited in the inpect element
	if ($month == "" || $day == "" || $holidayName == "" || $holidayType =="") {
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error saving getting of data, Please refresh the page</center>";
	}


	// if month != the list of months
	else if ($month != "January" && $month != "February" && $month != "March" && $month != "April" && $month != "May" && $month != "June"
			&& $month != "July" && $month != "August" && $month != "September" && $month != "October" && $month != "November" && $month != "December"){
			$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	}

	// if day != the list according to months
	else if (($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") && ($day <=0 || $day >= 32)){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	}

	// if day != the list according to months
	else if (($month == "February" && $total_day == 28) && ($day <=0 || $day >=29) || ($month == "February" && $total_day == 29) && ($day <=0 || $day >=30)){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	}

	// if day != the list according to months
	else if (($month == "April" || $month == "June" || $month == "September" || $month == "November") && ($day <=0 || $day >= 31)){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	}

	// chec if the type of holiday is equal to the needed type
	else if ($holidayType != "Regular Holiday" && $holidayType != "Special non-working day"){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	}

	// if no changes was made $holiday_id,$month,$day,holiday_value,holiday_type
	else if ($holiday_class->sameHolidayInfo($holiday_id,$month,$day,$holidayName,$holidayType) != 0){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made. </center>";
	}

	// check if the date is exist in the database
	else if ($holiday_class->existHolidate($month,$day,$holiday_id)){
		$_SESSION["update_holiday_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The date of holiday <b>$month $day</b> is already existing</center>";
	}


	// if success update
	else {
		
		$holiday_class->updateHolidayInfo($holiday_id,$month,$day,$holidayName,$holidayType);

		$_SESSION["update_holiday_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> HolidayInformation is successfully updated</center>";
	}
	
	header("Location:../holiday.php");
}

// if edited in the inspect element
else {
	header("Location:../MainForm.php");
}

?>