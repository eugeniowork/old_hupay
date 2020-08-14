<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["generate_request"])){
	$generate_request = $_POST["generate_request"];

	if ($generate_request == "1"){
		$emp_info_class = new EmployeeInformation;
		$count = $emp_info_class->getCountActiveEmp();
		echo $count;
?>
<?php
	} // end of if

	else {
		header("Location:../MainForm.php");
	}
}
else {
	header("Location:../MainForm.php");
}

?>
