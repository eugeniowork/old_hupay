<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["pet_type_array"]) && isset($_POST["pet_name_array"]) && isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"]; 
	$pet_type_array = $_POST["pet_type_array"];
	$pet_name_array = $_POST["pet_name_array"];

	$emp_info_class = new EmployeeInformation;
	$date_class = new date;

	$count = count(json_decode($pet_type_array));
	
	
	$pet_count = $emp_info_class->countPetInfo($emp_id);

	$same_info = true;


	/*if ($count == $pet_count || $same_info == true){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}

	else {

	}*/
	$emp_info_class->deletePetInfo($emp_id);

	if ($count > 0){
		$counter = 0;
		do {	

			$pet_type = json_decode($pet_type_array)[$counter];
			$pet_name = json_decode($pet_name_array)[$counter];

			//echo $pet_type . " " . $pet_name . "<br/>";

			$emp_info_class->insertPet($emp_id,$pet_type,$pet_name);

			$counter++;
		}while($count > $counter);
	}

	echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Pet Information is Successfully Updated.</center>";

	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Update Pet Information";
	$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Pet Information",$dateTime);
	
	
}
else {
	header("Location:../Mainform.php");
}

?>