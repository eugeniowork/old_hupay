<?php
session_start();
include "../class/connect.php";
include "../class/Philhealth_Contribution.php";
include "../class/date.php";

if (isset($_POST["compensationFrom"]) && isset($_POST["compensationTo"]) && isset($_POST["Contribution"])) {


	$philhealth_contribution_class = new Philhealth_Contribution;
	$date_class = new date;


	// if there is no over yet
	if (isset($_POST["checkOver"])){
		$compensationTo = "0";	
	}
	

	$compensationFrom = $_POST["compensationFrom"];
	$compensationTo = $_POST["compensationTo"];
	$Contribution = $_POST["Contribution"];
	$currentDate = $date_class->getDate();

	// if edited no save were taken
	if ($compensationFrom == "" || $compensationTo == "" || $Contribution == "") {
		$_SESSION["error_msg_philhealth_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You must fill up all fields</center>";		
	}

	// if there is exist info
	else if ($philhealth_contribution_class->existContribution($compensationFrom,$compensationTo,$Contribution) != 0){
		$_SESSION["error_msg_philhealth_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Contribution Information</b> is already exist</center>";
	}

	// kapag equal ung nilagay na value sa compensation from - to
	else if ($compensationFrom == $compensationTo){
		$_SESSION["error_msg_philhealth_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> and <b>Compensation to</b> must be not equal</center>";
	}


	// kapag 0
	else if ($philhealth_contribution_class->alreadyData() != 0 && $philhealth_contribution_class->checkLastContributionTo()->compensationTo == 0 && $philhealth_contribution_class->checkLastContributionTo()->compensationFrom < $compensationFrom){
		$_SESSION["error_msg_philhealth_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> must not be larger than the highest compensation</center>";
	}

	else if ($philhealth_contribution_class->alreadyData() != 0 && $philhealth_contribution_class->checkLastContributionTo()->compensationTo == 0 && $philhealth_contribution_class->checkLastContributionTo()->compensationFrom < $compensationTo){
		$_SESSION["error_msg_philhealth_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation To</b> must not be larger than the highest compensation</center>";
	}

	// if success
	else {
		$philhealth_contribution_class->insertPhilhealthcontribution($compensationFrom,$compensationTo,$Contribution,$currentDate);
		$_SESSION["success_msg_philhealthcontrib"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution Information</b> is  successfully added</center>";
	}

	header("Location:../philhealthContributionTable.php");


}
// if only browse
else {
	header("Location:../MainForm.php");
}

?>