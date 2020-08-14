<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["search"])){

	$search = $_POST["search"];

	$emp_info_class = new EmployeeInformation;

	$emp_info_class->searchEmployeeName($search);


}
else {
	header("Location:../MainForm.php");
}


?>