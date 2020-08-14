<?php
session_start();
include "../class/connect.php";
include "../class/allowance_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/cashbond_class.php";
include "../class/audit_trail_class.php";

if (isset($_POST["allowance_values"]) && isset($_POST["value_values"]) && isset($_POST["allowance_count"])) {
	$emp_id = $_SESSION["update_emp_id"]; 

	$date_class = new date;
	$allowance_class = new Allowance;
	$current_date = $date_class->getDate();

	$allowace_values_from_db = $allowance_class->sameAllowanceAllowanceValues($emp_id);
	$value_values_from_db = $allowance_class->sameAllowanceValueValues($emp_id);

	$allowance_values =  $_POST["allowance_values"];
	$value_values = $_POST["value_values"];

	$allowance = explode("#",$allowance_values);
	$value = explode("#",$value_values);

	$allowance_count = $_POST["allowance_count"];

	// if same info
	if ($allowace_values_from_db == $allowance_values && $value_values_from_db == $value_values) {
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}

	else if ($allowance_values == "" || $value_values == "") {
		// delete all first
		$allowance_class->deleteAllowance($emp_id);
		echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Allowance Information is Successfully Updated.</center>";
	}

	// if success
	else {

		// delete all first
		$allowance_class->deleteAllowance($emp_id);

		$counter = 0;
		do {

			$allowance_class->addAllowance($emp_id,$allowance[$counter],$value[$counter],$current_date);

			$counter++;
		} while($counter < $allowance_count);


		// for updating cash bond information
		$emp_info_class = new EmployeeInformation;
		$row = $emp_info_class->getEmpInfoByRow($emp_id);
		$salary = $row->Salary;

		$allowance = 0;
	 	if ($allowance_class->existAllowance($emp_id) == 1){
 			$allowance = $allowance_class->getAllowanceInfoToPayslip($emp_id);
	 	}

	 	$cashbond_class = new Cashbond;

	 	$cashbond = $salary + $allowance;
	 	$final_cashbond = round($cashbond_class->cashbondNewEmpFormula($cashbond),2);

	 	// kunin muna natin ung information nya sa db
 		$row_cashbond = $cashbond_class->getInfoByEmpId($emp_id);
 		$total_cashbond = $row_cashbond->totalCashbond;

	 	// for updating
 		$cashbond_class->updateCashbond($emp_id,$final_cashbond,$total_cashbond);

		echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Allowance Information is Successfully Updated.</center>";


		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Update Allowance Information";
		$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Allowance Information",$dateTime);
	}
	
}
else {
	header("Location:../Mainform.php");
}

?>