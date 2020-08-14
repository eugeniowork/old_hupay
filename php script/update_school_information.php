<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["emp_id"]) && isset($_POST["school_name_array"]) && isset($_POST["course_array"])
	&& isset($_POST["year_from_array"]) && isset($_POST["year_to_array"])){

	$emp_info_class = new EmployeeInformation;

	$emp_id = $_POST["emp_id"];
	$school_name_values = explode(",", $_POST["school_name_array"]);
	$count = count($school_name_values);
	$course_values = explode(",", $_POST["course_array"]);
	$year_from_values = explode(",", $_POST["year_from_array"]);
	$year_to_values = explode(",", $_POST["year_to_array"]);
	

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	if ($row_emp->highest_educational_attain == ""){
		$educational_attain = "";
		if (isset($_POST["update_education_attain"])){
			$educational_attain = $_POST["update_education_attain"];

			$emp_info_class->updateDucationalAttainment($emp_id,$educational_attain);
		}

	}

	// for delete first
	$emp_info_class->deleteSchoolInformation($emp_id);

	$counter = 0;
	do {

		$school_name = $school_name_values[$counter];
		$course = $course_values[$counter];
		$year_from = $year_from_values[$counter];
		$year_to = $year_to_values[$counter];

		//echo $school_name . " " . $course . " " . $year_from . " " . $year_to . "<br/>";

		// ibig sabihin secondary
		if ($counter == 0){
			$type = 0;
		}

		// ibig sabihin tertiary
		else {
			$type = 1;

		}

		$emp_info_class->insertEmployeeEducation($emp_id,$type,$school_name,$course,$year_from,$year_to);


		

		$counter++;
	}while($count > $counter);

		
	echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee School Information is Successfully Updated.</center";


	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Update School Information";
	$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update School Information",$dateTime);
		

}
else {
	header("Location:../MainForm.php");
}

	

?>