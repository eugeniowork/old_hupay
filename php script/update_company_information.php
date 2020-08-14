<?php
session_start();
include "../class/connect.php";
include "../class/department.php";
include "../class/position_class.php";
include "../class/emp_information.php";
include "../class/history_position.php";
include "../class/date.php";
include "../class/allowance_class.php";
include "../class/cashbond_class.php";
include "../class/working_hours_class.php";
include "../class/audit_trail_class.php";
include "../class/working_days_class.php";

$emp_id = $_SESSION["update_emp_id"];


if (isset($_POST["update_department"]) && isset($_POST["update_position"]) && isset($_POST["update_salary"]) && isset($_POST["update_dateHired"]) && isset($_POST["with_history"]) && isset($_POST["update_workingHours"]) && isset($_POST["update_headName"]) && isset($_POST["update_company_id"]) && isset($_POST["update_employment_type"]) && isset($_POST["update_workingDays"])) {
	$department = $_POST["update_department"];
	$position = $_POST["update_position"];
	$salary = $_POST["update_salary"];
	$date_class = new date;
	//$dateHired = $date_class->dateDefaultDb($_POST["update_dateHired"]);
	$with_history = $_POST["with_history"];
	$workingHours = $_POST["update_workingHours"];
	$headName = $_POST["update_headName"];
	$company_id = $_POST["update_company_id"];
	$employment_type = $_POST["update_employment_type"];
	$workingDays = $_POST["update_workingDays"];

	$emp_info_class = new EmployeeInformation;
	//$num_rows = $emp_info_class->sameCompanyInfo($emp_id,$department,$position,$salary,$dateHired,$workingHours);




	$department_class = new department;
	$exist_department = $department_class->existDepartmentById($department);

	$position_class = new Position;
	$exist_position = $position_class->checkExistPositionId($position);


	$working_hours_class = new WorkingHours;
	$working_days_class = new WorkingDays;
	


	$datehired_month = substr($_POST["update_dateHired"],0,2);
	$datehired_day = substr(substr($_POST["update_dateHired"], -7), 0,2);
	$datehired_year = substr($_POST["update_dateHired"], -4);



	$employment_type_stat = 1;
	if ($employment_type == "Provisional"){
		$employment_type_stat = 0;
	}


	// if required is edited to the inspect element security purpose
	if ($department == "" || $position == "" || $salary == "" || $_POST["update_dateHired"] == ""){
		//$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during updating employee info, Information did not update.</center>";
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}

	

	// check if department is exist and not edited the value in inspect element, // check if position is exist and not edited the value in inspect element
	else if ($exist_department == 0 || $exist_position == 0){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}


	// for checking if the working id is not existing
	else if ($working_hours_class->checkExistWorkingHoursId($workingHours) == 0){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}


	// for checking if the working id is not existing
	else if ($working_days_class->checkExistWorkingDaysId($workingDays) == 0){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["update_dateHired"])) {
    	echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> <b>Date Hired</b> not match to the current format mm/dd/yyyy</center>";
	}

	// for validating leap year
	else if ($datehired_year % 4 == 0 && $datehired_month == 2 && $datehired_day >= 30){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Date Hired</b> date</center>";
	}

	// for validating leap year also
	else if ($datehired_year % 4 != 0 && $datehired_month == 2 && $datehired_day >= 29){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Date Hired</b> date</center>";
	}

	// mga month na may 31
	else if (($datehired_month == 4 || $datehired_month == 6 || $datehired_month == 9 || $datehired_month == 11)
			&& $datehired_day  >= 31){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Date Hired</b> date</center>";
	}


	// if head name does not exist
	else if ($headName != "" && $emp_info_class->checkExistEmployeeName($headName) == 1){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Immediate Head's Name $headName</b> is not exist in the employee list name</center>";
	}


	// if no changes was made
	else if ($emp_info_class->sameCompanyInfo($emp_id,$department,$position,$salary,$date_class->dateDefaultDb($_POST["update_dateHired"]),$workingHours,$emp_info_class->getEmpIdByEmployeeName($headName),$company_id,$employment_type_stat,$workingDays) == 1){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}
	

	// if success
	else {
		
		
		$dateHired = $date_class->dateDefaultDb($_POST["update_dateHired"]);




		$emp_info_class->updateCompanyInfo($emp_id,$department,$position,$salary,$workingHours,$emp_info_class->getEmpIdByEmployeeName($headName),$company_id,$employment_type_stat,$workingDays); // dateHired
		//$_SESSION["success_msg_update_basic_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Employee Information is Successfully Updated.</center";
		
		
		// for updating latest emp history
		$history_position_class = new HistoryPosition;

		$row_history_position = $history_position_class->getLatestEmpHistory($emp_id); // for getting the latest position history

		$history_position_id = $row_history_position->history_position_id; // for getting the history position



		// if need to save to employment history
		if ($with_history == "here"){
			

			$current_date = $date_class->getDate();

			$history_position_class->insertHistoryPosition($emp_id,$department,$position,$salary,$dateHired,$current_date); // $emp_id,$dept_id,$position_id,$salary,$dateHired,$dateCreated
			echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Company Information is Successfully Updated and Save details to LFC employment History.</center";
		}

		else {
			$history_position_class->updateEmpHistory($history_position_id,$department,$position,$salary,$dateHired); // if update only update only
			echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Company Information is Successfully Updated.</center";
		}

		// for updating cashbond info
	 	// first check muna kung may allowance ba siya
	 	$allowance_class = new Allowance;
	 	$cashbond_class = new Cashbond;
	 	// ibig sabihin may allowance siya
	 	$allowance = 0;
	 	if ($allowance_class->existAllowance($emp_id) == 1){
 			$allowance = $allowance_class->getAllowanceInfoToPayslip($emp_id);
	 	}
 		
 		$cashbond = $salary + $allowance;
 		$final_cashbond = round($cashbond_class->cashbondNewEmpFormula($cashbond),2);


 		// kunin muna natin ung information nya sa db
 		$row_cashbond = $cashbond_class->getInfoByEmpId($emp_id);
 		$total_cashbond = $row_cashbond->totalCashbond;
 		$cashbondValue = $row_cashbond->cashbondValue;

 		if ($cashbondValue < $final_cashbond){
 			// for updating
 			$cashbond_class->updateCashbond($emp_id,$final_cashbond,$total_cashbond);
 		}

 		
 		
 		//echo "<center>Success!</center>";


 		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Update Company Information";
		$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Company Information",$dateTime);
	}

}

else {
	header("Location:../MainForm.php");
}


//header("Location:../employee_list.php");





?>