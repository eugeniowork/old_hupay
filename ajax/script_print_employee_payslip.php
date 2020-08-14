<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";

if (isset($_POST["payroll_id"])){

	$payroll_id = $_POST["payroll_id"];
	//$emp_id = $_SESSION["id"];

	$payroll_class = new Payroll;


	// if edited in the inspect element
	/*if ($payroll_class->existPayrollId($emp_id,$payroll_id)== 0) {
		echo "Error";
	}*/

	// if success;
	//else {
		$_SESSION["current_payroll_id"] = $payroll_id; 
?>
	 <!--<form id="print_payslip_form" method="post" action="my_payslip_reports.php">
        <input type="text" name="payroll_id" value="<?php echo $payroll_id; ?>">

  	</form> -->
<?php
	//} // end of else
}
else {
	header("Location:../MainForm.php");
}


?>