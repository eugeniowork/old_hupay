<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";


if (isset($_POST["emp_id"])){
	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;
	$date_class = new date;

	$row = $emp_info_class->getEmpInfoByRow($emp_id);

	$firstname = $row->Firstname;
	$middlename = $row->Middlename;
	$lastname = $row->Lastname;

	$old_salary = $row->Salary;
	$new_salary = $_POST["new_salary"];
	$date_increase = $date_class->dateDefaultDb($_POST["date_increase"]);

	// for insert in increase table
	$emp_info_class->insertIncreaseSalary($emp_id,$old_salary,$new_salary,$date_increase);

	// for update salary
	$emp_info_class->updateSalary($emp_id,$new_salary);

	$_SESSION["success_msg_update_basic_info"] = "<span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Employee <b>$firstname $middlename $lastname</b> salary of <b>$new_salary</b>. is successfully updated.";

	header("Location:../employee_list.php");

}
else {
	header("Location:../dashboard.php");
}


?>