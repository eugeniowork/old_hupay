<?php
include "../class/connect.php";
include "../class/emp_information.php";


if (isset($_POST["emp_id"])){	
	$emp_info_class = new EmployeeInformation;
	$emp_id = $_POST["emp_id"];

	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		echo "<div class='custom-panel-heading' style='color:#317eac;'><center><small style='padding:1px;'><span class='glyphicon glyphicon-edit'></span> Unfillup Fields</small></center></div>";
		echo $emp_info_class->getUnfillUpFields($emp_id);
	}

	else {
		echo "Error";
	}
}
else {
	header("Location:../MainForm.php");
}


?>
