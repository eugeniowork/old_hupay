<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/adjustmentLoan.php";
include "../class/money.php";
include "../class/date.php";
include "../class/emp_information.php";

if (isset($_POST["adjust_remainingBalance"]) && isset($_POST["adjust_cashPayment"]) && isset($_POST["adjust_newRemainingBalance"])
	&& isset($_POST["adjust_remarks"])){


	$simkimban_class = new Simkimban;
	$adjustment_loan_class = new AdjustmentLoan;
	$money_class = new Money;
	$date_class = new date;
	$emp_info_class = new EmployeeInformation;

	$datePayment = $date_class->dateDefaultDb($_POST["adjust_datePayment"]);
	$remainingBalance = $_POST["adjust_remainingBalance"];
	$cashPayment = $_POST["adjust_cashPayment"];
	$newRemainingBalance = $_POST["adjust_newRemainingBalance"];
	$remarks = $_POST["adjust_remarks"];

	$simkimban_id = $_POST["adjust_simkimbanId"];

	
	// for validation
	// if the cash payment is greater than remaining balance
	if ($cashPayment > $remainingBalance) {
		$_SESSION["adjustment_simkimban_error"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Cash Payment Php ".$money_class->getMoney($cashPayment)."</b> must be not greater than the <b>Outstanding Balance Php ".$money_class->getMoney($remainingBalance)."</b>.</center>";
	}

	// if 0 the cash payment
	if ($cashPayment == 0) {
		$_SESSION["adjustment_simkimban_error"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Cash Payment</b> must be not equal to <b>0</b>.</center>";
	}

	// if success
	else {
		
		// first update the remaining balance in the pagibig loan table
		$simkimban_class->updateOnlyRemainingBalance($simkimban_id,$newRemainingBalance);

		$emp_id = $simkimban_class->getInfoBySimkimbaId($simkimban_id)->emp_id;
		$pagibig_loan_id = 0;
		//$sss_loan_id = 0;
		$sssLoanId = 0;
		$loanType = "Simkimban";
		$salaryLoanId = 0;
		$current_date_time = $date_class->getDate();


		// for inserting to the adjustment reports
		$adjustment_loan_class->insertAdjustmentLoan($emp_id,$datePayment,$pagibig_loan_id,$sssLoanId,$salaryLoanId,$simkimban_id,$loanType,$cashPayment,$newRemainingBalance,$remarks,$current_date_time);
			
		

		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$fullName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

		$_SESSION["adjustment_simkimban_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Pag-ibig Loan</b> of  <b>Employee $fullName</b> is successfully adjusted.</center>";
		
	}




	header("Location:../simkimban.php");

	/*echo "Remaining Balance: " . $remainingBalance . "<br/>";
	echo "Cash Payment: " . $cashPayment . "<br/>";
	echo "Remaining Balance: " . $newRemainingBalance . "<br/>";
	echo "Remarks: " . $remarks . "<br/>";
	echo "Salary Loan Id:" . $salaryLoanId . "<br/>";*/

}

else {
	header("Location:../MainForm.php");
}

?>