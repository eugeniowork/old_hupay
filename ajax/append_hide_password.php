<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])){
	$emp_info_class = new EmployeeInformation;

	$emp_id = $_POST["emp_id"];

	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}
	else {
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$password_length = strlen($row->Password);
		$counter = 0;
		$password_asterisk = ""; // for initiazation value
		do {
			if ($password_asterisk == ""){
				$password_asterisk = "*";
			}
			else {
				$password_asterisk = $password_asterisk . "*";
			}
			

			$counter++;
		}while($counter < $password_length);

		echo $password_asterisk;// . "<button type='button' title='click me to show password' id='show_password' class='btn btn-primary btn-sm pull-right' style='padding:2px;'><span class='glyphicon glyphicon-eye-open'><span></button>";
	}
}

else {
	header("Location:../MainForm.php");
}

?>