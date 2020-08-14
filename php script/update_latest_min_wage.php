<?php
session_start();
include "../class/connect.php";
include "../class/minimum_wage_class.php";
include "../class/date.php";

// if success
if (isset($_POST["updateEffectiveDate"]) && isset($_POST["updateBasicWage"]) && isset($_POST["updateCOLA"])){
	
	$min_wage_class = new MinimumWage;
	$date_class = new date;

	$effectiveDate = $date_class->dateDefaultDb($_POST["updateEffectiveDate"]);
	$basicWage = $_POST["updateBasicWage"];
	$cola = $_POST["updateCOLA"];

	// if edited in the inspect element and not fill up required fields
	if ($basicWage == "" || $cola == "" || $effectiveDate == ""){
		$_SESSION["update_error_min_wage"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's is an error during saving</center>";
	}

	// if no changes was made
	else if ($min_wage_class->sameMinWageInfo($effectiveDate,$basicWage,$cola) == 1) {
		$_SESSION["update_error_min_wage"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> No updates were taken, No changes was made.</center>";
	}

	// if has same info
	else if ($min_wage_class->existSameInfo($effectiveDate,$basicWage,$cola)){
		$_SESSION["update_error_min_wage"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The minimum wage information is already exist.</center>";
	}

	// if success
	else {
		$min_wage_class->updateLatestMinWage($effectiveDate,$basicWage,$cola);
		$_SESSION["update_success_min_wage"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> The latest minimum wage is successfully updated<center>";
	}

	header("Location:../minimum_wage.php");


}
// if edited in the inspect element or just browse
else {
	header("Location:../MainForm.php");
}



?>