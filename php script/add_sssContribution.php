<?php
session_start();
include "../class/connect.php";
include "../class/SSS_Contribution.php";
include "../class/date.php";

if (isset($_POST["compensationFrom"]) && isset($_POST["compensationTo"]) && isset($_POST["sssContribution"])) {
	
	$sss_contribution_class = new SSS_Contribution;
	$date_class = new date;
	$compensationFrom = $_POST["compensationFrom"];
	$compensationTo = $_POST["compensationTo"];
	$sssContribution = $_POST["sssContribution"];
	$currentDate = $date_class->getDate();



	// if there is no over yet
	if (isset($_POST["checkOver"])){
		$compensationTo = "0";	
	}

	
	// if edited no save were taken
	if ($compensationFrom == "" || $compensationTo == "" || $sssContribution == "") {
		$_SESSION["error_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You must fill up all fields</center>";		
	}

	// if there is exist info
	else if ($sss_contribution_class->existContribution($compensationFrom,$compensationTo,$sssContribution) != 0){
		$_SESSION["error_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Contribution Information</b> is already exist</center>";
	}

	// kapag equal ung nilagay na value sa compensation from - to
	else if ($compensationFrom == $compensationTo){
		$_SESSION["error_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> and <b>Compensation to</b> must be not equal</center>";
	}


	// kapag 0
	else if ($sss_contribution_class->alreadyData() != 0 && $sss_contribution_class->checkLastContributionTo()->compensationTo == 0 && $sss_contribution_class->checkLastContributionTo()->compensationFrom < $compensationFrom){
		$_SESSION["error_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> must not be larger than the highest compensation</center>";
	}

	else if ($sss_contribution_class->alreadyData() != 0 && $sss_contribution_class->checkLastContributionTo()->compensationTo == 0 && $sss_contribution_class->checkLastContributionTo()->compensationFrom < $compensationTo){
		$_SESSION["error_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation To</b> must not be larger than the highest compensation</center>";
	}


	// if success
	else {
		$sss_contribution_class->insertSSScontribution($compensationFrom,$compensationTo,$sssContribution,$currentDate);
		$_SESSION["success_msg_sss_contrib"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution Information</b> is  successfully added</center>";
	}
	header("Location:../sssContributionTable.php");
	
}
else {
	header("Location:../MainForm.php");
}


?>