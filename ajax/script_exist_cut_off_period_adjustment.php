<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";

if (isset($_POST["cut_off_period"])) {
	$cut_off_period = $_POST["cut_off_period"];

	$payroll_class = new Payroll;

	if ($payroll_class->checkExistCutOffPeriod($cut_off_period) != 0) {
		$_SESSION["cut_off_period_adjustment_report"] = $cut_off_period;
		echo "Success";

?>
<?php
	}
	else {
		echo "Error";
	}
	// 

	// checkExistCutOffPeriod
}

else {
	header("Location:../MainForm.php");
}


?>