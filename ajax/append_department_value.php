<?php
session_start();
include "../class/connect.php";
include "../class/department.php";


if (isset($_POST["dept_id"])){
	$dept_id = $_POST["dept_id"];

	$department_class = new Department;


	if ($department_class->existDepartmentById($dept_id) == 1){
		$row = $department_class->getDepartmentValue($dept_id);

		echo $row->Department;
	}

	else {
		echo "Error";
	}
}

else {
	header("Location:../MainForm.php");
}

?>