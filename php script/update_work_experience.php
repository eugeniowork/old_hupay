<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["emp_id"]) && isset($_POST["work_position_array"]) && isset($_POST["company_name_array"]) && isset($_POST["job_description_array"])
	&& isset($_POST["year_from_array"]) && isset($_POST["year_to_array"])){

	$emp_info_class = new EmployeeInformation;

	$emp_id = $_POST["emp_id"];
	$work_position_values = explode(",", $_POST["work_position_array"]);
	$count = count($work_position_values);
	$company_name_values = explode(",", $_POST["company_name_array"]);
	$job_description_values = explode(",", $_POST["job_description_array"]);
	$year_from_values = explode(",", $_POST["year_from_array"]);
	$year_to_values = explode(",", $_POST["year_to_array"]);
	

	// for delete first
	$emp_info_class->deleteWorkExperience($emp_id);

	$counter = 0;
	do {

		$position = $work_position_values[$counter];
		$company_name = $company_name_values[$counter];
		$job_description = $job_description_values[$counter];
		$year_from = $year_from_values[$counter];
		$year_to = $year_to_values[$counter];

		//echo $position . " " . $company_name . " " . $job_description . " " . $year_from . " " . $year_to . "<br/>";

		

		//$emp_info_class->insertEmployeeEducation($emp_id,$type,$school_name,$course,$year_from,$year_to);
		$emp_info_class->insertEmployeeWorkExperience($emp_id,$position,$company_name,$job_description,$year_from,$year_to);

		

		$counter++;
	}while($count > $counter);

		
	echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Work Experience Information is Successfully Updated.</center";

	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Update Work Experience";
	$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Work Experience",$dateTime);
		

}
else {
	header("Location:../MainForm.php");
}

	

?>