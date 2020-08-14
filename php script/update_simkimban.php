<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/date.php";

if (isset($_POST["update_empName"]) && isset ($_POST["deductionTypeExist"]) && isset($_POST["totalMonthsExist"]) && isset($_POST["update_dateFrom"]) && isset($_POST["update_dateTo"])
	&& isset($_POST["update_item"]) && isset($_POST["update_amountLoan"]) && isset($_POST["update_decution"]) 
	&& isset($_POST["update_remainingBalance"])){


	$simkimban_class = new Simkimban;
	$date_class = new date;

	$deductionType = $_POST["deductionTypeExist"];
	$totalMonths = $_POST["totalMonthsExist"];
	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	$simkimban_id = $_POST["update_simkimbanId"];
	$empName = $_POST["update_empName"];
	$dateFrom = $date_class->dateDefaultDb($_POST["update_dateFrom"]);
	$dateTo = $date_class->dateDefaultDb($_POST["update_dateTo"]);
	$item = $_POST["update_item"];
	$amountLoan = $_POST["update_amountLoan"];
	$deduction = $_POST["update_decution"];
	$remainingBalance = $_POST["update_remainingBalance"];



	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["update_simkimban_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	else if ($simkimban_class->sameSimkimbanInfo($simkimban_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance) == 1){ // $pagibig_loan_id,$dateFrom,$dateTo,$amountLoan,$deduction,$remainingBalance
		$_SESSION["update_simkimban_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken</center>";
	}

	// if the date from is bigger than the date to
	else if ($dateFrom >= $dateTo) {
		$_SESSION["update_simkimban_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	else {
		$simkimban_class->updateSimkimban($simkimban_id,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance);
		$_SESSION["update_simkimban_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Simkimban info of <b>$empName</b> is successfully updated.</center>";
	}


	header("Location:../simkimban.php");

}

else {
	header("Location:../MainForm.php");
}


?>