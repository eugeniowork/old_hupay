<?php
session_start();
include "../class/connect.php";
include "../class/dependent.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["name_values"]) && isset($_POST["birthdate_values"]) && isset($_POST["dependent_count"])) {

	$emp_id = $_SESSION["update_emp_id"]; 

	$date_class = new date;
	$dependent_class = new Dependent;
	$current_date = $date_class->getDate();


	$name_values_from_db = $dependent_class->sameDependentNameValues($emp_id);
	$birthdate_values_from_db = $dependent_class->sameDependentBirthdateValues($emp_id);



	//echo $current_date;

	$name_values =  $_POST["name_values"];
	$birthdate_values = $_POST["birthdate_values"];


	$name = explode("#",$name_values);
	$birthdate = explode("#",$birthdate_values);

	$dependent_count = $_POST["dependent_count"];


	$has_error = 0; // for security issue
	$counter = 0;
	do {

		if ($birthdate[$counter] != "" && $has_error == 0){

			$birthday_month = substr($birthdate[$counter],0,2);
			$birthday_day = substr(substr($birthdate[$counter], -7), 0,2);
			$birthday_year = substr($birthdate[$counter], -4);

			if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$birthdate[$counter])) {
		    	echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> <b>Birthdate</b> not match to the current format mm/dd/yyyy</center>";
				$has_error = 1;
			}

			// for validating leap year
			else if ($birthday_year % 4 == 0 && $birthday_month == 2 && $birthday_day >= 30){
				echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
				$has_error = 1;
			}

			// for validating leap year also
			else if ($birthday_year % 4 != 0 && $birthday_month == 2 && $birthday_day >= 29){
				echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
				$has_error = 1;
			}

			// mga month na may 31
			else if (($birthday_month == 4 || $birthday_month == 6 || $birthday_month == 9 || $birthday_month == 11)
					&& $birthday_day  >= 31){
				echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
				$has_error = 1;
			}

		}
		$counter++;

	} while($counter < $dependent_count);


	//echo $dependent_count;

	if ($has_error == 0) {
		// if same info
		if ($name_values_from_db == $name_values && $birthdate_values_from_db == $birthdate_values) {
			echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
		}


		else if ($name_values == "") {
			// delete all first
			$dependent_class->deleteDependent($emp_id);
			echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Dependent Information is Successfully Updated.</center>";
		}


		// if success
		else {

			// delete all first
			$dependent_class->deleteDependent($emp_id);

			$counter = 0;
			do {
				//echo "name_" . $counter . ":" . $name[$counter] . "<br/>";

				//echo "birthdate_" . $counter . ":" . $birthdate[$counter] . "<br/>";
				//echo $emp_id,
				if ($birthdate[$counter] == ""){
					$dependent_class->addDependent($emp_id,$name[$counter],"",$current_date);
				}

				else {
					$dependent_class->addDependent($emp_id,$name[$counter],$date_class->dateDefaultDb($birthdate[$counter]),$current_date);
				}

				$counter++;
			} while($counter < $dependent_count);
			echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Dependent Information is Successfully Updated.</center>";


			$dateTime = $date_class->getDateTime();
			$audit_trail_class = new AuditTrail;
			$module = "Update Dependant Information";
			$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Dependant Information",$dateTime);
		}
	}
}

else {
	header("Location:../Mainform.php");
}



?>