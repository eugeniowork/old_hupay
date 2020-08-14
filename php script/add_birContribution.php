<?php
session_start();
include "../class/connect.php";
include "../class/BIR_Contribution.php";
include "../class/date.php";

if (isset($_POST["status"]) && isset($_POST["amount"]) && isset($_POST["contribution"]) && isset($_POST["percentage"])) {


	$bir_contribution_class = new BIR_Contribution;
	$date_class = new date;


	// if there is no over yet
	//if (isset($_POST["checkOver"])){
	//	$compensationTo = "0";	
	//}
	

	$status  =$_POST["status"];
	$amount = $_POST["amount"];
	$contribution = $_POST["contribution"];
	$percentage = $_POST["percentage"];
	$currentDate = $date_class->getDate();

	// if edited no save were taken
	if ($amount == "" || $contribution == "" || $percentage == "") {
		$_SESSION["error_msg_bir_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You must fill up all fields</center>";		
	}

	else if ($bir_contribution_class->existBIRStatus($status) == 0){
		$_SESSION["error_msg_bir_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's an error during saving of data</center>";
	}

	
	// if there is exist info
	else if ($bir_contribution_class->existContribution($status,$amount,$contribution,$percentage) != 0){
		$_SESSION["error_msg_bir_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Contribution Information</b> is already exist</center>";
	}

	/*
	// kapag equal ung nilagay na value sa compensation from - to
	else if ($compensationFrom == $compensationTo){
		$_SESSION["error_msg_pagibig_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> and <b>Compensation to</b> must be not equal</center>";
	}


	// kapag 0
	else if ($pagibig_contribution_class->alreadyData() != 0 && $pagibig_contribution_class->checkLastContributionTo()->compensationTo == 0 && $pagibig_contribution_class->checkLastContributionTo()->compensationFrom < $compensationFrom){
		$_SESSION["error_msg_pagibig_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation From</b> must not be larger than the highest compensation</center>";
	}

	else if ($pagibig_contribution_class->alreadyData() != 0 && $pagibig_contribution_class->checkLastContributionTo()->compensationTo == 0 && $pagibig_contribution_class->checkLastContributionTo()->compensationFrom < $compensationTo){
		$_SESSION["error_msg_pagibig_contrib"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Compensation To</b> must not be larger than the highest compensation</center>";
	} */

	// if success
	else {
		$bir_contribution_class->insertBIRcontribution($status,$amount,$contribution,$percentage,$currentDate);
		$_SESSION["success_msg_bircontrib"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution Information</b> is  successfully added</center>";
	}

	header("Location:../birContributionTable.php"); 

}
// if only browse
else {
	header("Location:../MainForm.php");
}

?>