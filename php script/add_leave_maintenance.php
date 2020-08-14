<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";


//echo "Wew";


if (isset($_POST["name"]) && isset($_POST["validation"]) && isset($_POST["no_days_to_file"]) && isset($_POST["leave_count"]) && isset($_POST["is_convetable_to_cash"])){

	$name = $_POST["name"];
	$validation = $_POST["validation"];
	$no_days_to_file = $_POST["no_days_to_file"];
	$leave_count = $_POST["leave_count"];
	$is_convetable_to_cash = $_POST["is_convetable_to_cash"];

	$leave_class = new Leave;




	// check exist name
	if ($leave_class->checkExistLeaveTypeName($name,0,"Add") == 1){
		echo "<span class='color-red'>Leave Type <b>$name</b> is already exist in the list</span>";
	}


	// check if not exist validation id
	else if ($leave_class->checkExistLeaveValidation($validation) == 0){
		echo "<span class='color-red'>There's an error during saving of file.</span>";
	}

	else {
		$leave_class->insertLeaveType($name,$validation,$no_days_to_file,$leave_count,$is_convetable_to_cash);

		$_SESSION["leave_type"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Leave Type $name</b> is  successfully added</center>";
		echo "Success";

	}
}

else {
	header("Location:../Mainform.php");
}

?>