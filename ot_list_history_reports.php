<?php
session_start();
include "class/connect.php";
include "class/attendance_overtime.php";


if ($_SESSION["role"] != 4){
	$attendance_ot_class = new Attendance_Overtime;
	$attendance_ot_class->otListHistoryReports();

}
else {
	header("Location:MainForm.php");
}



?>