<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/emp_information.php";
include "../class/allowance_class.php";


if (isset($_POST["updateCashbond"]) && isset($_POST["cashbond_id"]) && isset($_POST["totalCashbond"])){
	$cashbond_class = new Cashbond;
	$emp_info_class = new EmployeeInformation;
	$allowance_class = new Allowance;

	$cashbond = $_POST["updateCashbond"];
	$cashbond_id = $_POST["cashbond_id"];
	$totalCashbond = $_POST["totalCashbond"];


	$row = $cashbond_class->getInfoByCashbondId($cashbond_id);

	$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

	$salary = $row_emp->Salary;

	$allowance = $allowance_class->getAllowanceInfoToPayslip($row->emp_id);

	//echo $salary . "<br/>";
	//echo $allowance . "<br/>";

	$cashbond_limit = round($cashbond_class->cashbondNewEmpFormula($salary + $allowance),2);

	//echo $cashbond_limit;
	
	// check if no change were make
	//if ($cashbond == $row->cashbondValue){
	if ($cashbond_class->noChanges($cashbond_id,$cashbond,$totalCashbond) == 1){
		//echo "No update were take, no update were made.";
		//$_SESSION["update_error_msg_cashbond"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No update were take, no update were made.</span></center>";
		echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No update were take, no update were made.</span></center>";
	}

	else if ($cashbond < $cashbond_limit){
		//echo "Cashbond Updates must be greater than the cashbond from formula.";
		$_SESSION["update_error_msg_cashbond"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Cashbond Updates must be greater than the cashbond from formula.</span></center>";
		echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Cashbond Updates must be greater than the cashbond from formula.</span></center>";
	}

	// if success save
	else {
		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		$cashbond_class->updateCashbond($row->emp_id,$cashbond,$totalCashbond);
		//$_SESSION["update_success_msg_cashbond"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> The <b>cashbond information</b> of <b>$fullName</b> is successfully updated.<center>";
		echo "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> The <b>cashbond information</b> of <b>$fullName</b> is successfully updated.<center>";
	}

	// check if the input is less than cashbond limit
	//header("Location:../cashbond.php");

}
else {
	//header("Location:../MainForm.php");
	echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during updating of data.</span></center>";
}


?>