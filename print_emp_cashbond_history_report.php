<?php
include "class/connect.php";
include "class/cashbond_class.php";
include "class/emp_information.php";
include "class/date.php";

if (isset($_GET["emp_id"])){

	$emp_id = $_GET["emp_id"];

	$cashbond_class = new Cashbond;
	$emp_info_class = new EmployeeInformation;
	$date_class = new date;

	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		header("Location:MainForm.php");
	}

	else {
		$current_date = $date_class->dateFormat($date_class->getDate());
		$cashbond_class->getEmpCashbondHistoryReports($emp_id,$current_date);
		//echo "Ready For Excel Report";
	}


}
else {
	header("Location:MainForm.php");
}

?>