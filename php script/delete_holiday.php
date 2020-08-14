<?php
session_start();
include "../class/connect.php";
include "../class/holiday_class.php";
$holiday_class = new Holiday;

if (isset($_SESSION["delete_holiday_id"])){
	$holiday_id = $_SESSION["delete_holiday_id"];

	// for information purpose
	$row = $holiday_class->getHolidayInfoByRow($holiday_id);


	$holiday_class->delete_holiday($holiday_id); // deletion query


	$_SESSION["success_msg_del_holiday"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The holiday <b>$row->holiday_date - $row->holiday_value</b> is successfully deleted</center>";
	header("Location:../holiday.php");

}

else {
	header("Location:../MainForm.php");
}

?>