<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/date.php";
include "../class/emp_loan_class.php";	

echo $_POST["decution"];

if (isset($_POST["empName"]) && isset($_POST["deductionType"]) && isset($_POST["totalMonths"]) && isset($_POST["totalMonths"]) && isset($_POST["dateTo"]) 
	&& isset($_POST["item"]) && isset($_POST["amountLoan"]) && isset($_POST["deduction"])) {

	$simkimban_class = new Simkimban;
	$date_class = new date;
	$emp_loan_class = new EmployeeLoan;
	$file_loan_id = $_GET["file_loan_id"];
	$row_fl = $emp_loan_class->getFileLoanInfoById($file_loan_id);

	$deductionType = $_POST["deductionType"];
	$totalMonths = $_POST["totalMonths"];
	$deductionDay = 0;
	if ($deductionType == "Monthly"){
		$deductionDay = $_POST["opt_deductedPayrollDate"];
	}

	$empName = $_POST["empName"];
	$empId = $row_fl->emp_id;

	if (isset($_POST["dateFrom"])){
		$dateFrom = $date_class->dateDefaultDb($dateFrom);
	}

	if (isset($_POST["dateFromMonth"])){

		$dateFrom = $_POST["dateFromMonth"] . "/" .$_POST["dateFromDay"] . "/" . $_POST["dateFromYear"];
		$dateFrom = $date_class->dateDefaultDb($dateFrom);
	}

	$dateTo = $date_class->dateDefaultDb($_POST["dateTo"]);
	$item = $_POST["item"];
	$amountLoan = $_POST["amountLoan"];
	$deduction = $_POST["deduction"];

	if (isset($_POST["remainingBalance"])){
		$remainingBalance = $_POST["remainingBalance"];
	}

	if (isset($_POST["totalPayment"])){
		$remainingBalance = $_POST["totalPayment"];
	}
	$dateCreated = $date_class->getDate();

	
	//echo "DITO BA!";

	// if remove the required attribute
	//if ($empName == "" || $empId == "" || $dateFrom == "" || $dateTo == "" || $amountLoan == "" || $decution == ""){
	//	$_SESSION["add_pagibigloan_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during saving of data, Please refresh the page</center>";
	//}

	if ($deductionDay != 0 && $deductionDay != 15 && $deductionDay != 30){
		$_SESSION["add_simkimban_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Theres an error during saving of data.";
	}

	// if the date from is bigger than the date to
	else if ($dateFrom > $dateTo) {
		$_SESSION["add_simkimban_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	// this facility is for erroring when has exist salary loan
	//else if ($salary_loan_class->existSalaryLoan($empId) != 0){
	//	$_SESSION["add_simkimban_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Employee $empName</b> has already an existing salary loan.</center>";
	//}

	// success so add
	else {





		//echo $empName . "<br/>";
		//echo $empId . "<br/>";
		//echo $dateFrom . "<br/>";
		//echo $dateTo . "<br/>";
		//echo $amountLoan . "<br/>";
		//echo $decution . "<br/>";
		//echo $remainingBalance . "<br/>";
		//echo $dateCreated . "<br/>";

		// ($emp_id, $dateFrom, $dateTo, $amountLoan, $deduction, $remainingBalance,$dateCreated)
		$simkimban_class->insertSimkimban($empId,$deductionType,$deductionDay,$totalMonths,$dateFrom,$dateTo,$item,$amountLoan,$deduction,$remainingBalance,$dateCreated);


		

		$ref_no = $row_fl->ref_no;

		// we need to update the file loan id to process
		$emp_loan_class->onProcesFileLoan($file_loan_id);


		// update the inserted loan simkimban	
		$simkimban_class->updateRefNo($simkimban_class->simkimbanLoanLastId(),$ref_no);



		$_SESSION["file_success_emp_loan"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Employee $empName</b> is successfully file a <b>Simkimban Loan</b></center>";

	}

	header("Location:../file_loan.php");

}

else {
	header("Location:../MainForm.php");
}



?>