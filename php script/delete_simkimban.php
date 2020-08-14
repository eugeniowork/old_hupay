<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/emp_information.php";


if (isset($_POST["delete_simkimbanId"])){
	$simkimban_id = $_POST["delete_simkimbanId"];
	
	$simkimban_class = new Simkimban;
	$emp_info_class = new EmployeeInformation;

	$row = $simkimban_class->getInfoBySimkimbaId($simkimban_id);

	$emp_id = $row->emp_id;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

	$simkimban_class->deleteSimkimban($simkimban_id);

	$_SESSION["success_delete_simkimban"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Simkimban</b> of <b>$fullName</b> is successfully deleted.</center>";

	header("Location:../simkimban.php");


}

else {
	header("Location:../MainForm.php");
}

?>