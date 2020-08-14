<?php
session_start();
include "class/connect.php";
include "class/leave_class.php";


if ($_SESSION["role"] != 4){
	$leave_class = new Leave;
	$leave_class->leaveListCutOffReports();

}
else {
	header("Location:MainForm.php");
}



?>