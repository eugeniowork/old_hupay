<?php
session_start();
include "../class/connect.php";
include "../class/pagibig_loan_class.php";
include "../class/adjustmentLoan.php";
include "../class/money.php";
include "../class/date.php";
include "../class/emp_information.php";

if (isset($_POST["adjust_remainingBalance"]) && isset($_POST["adjust_cashPayment"]) && isset($_POST["adjust_newRemainingBalance"])
	&& isset($_POST["adjust_remarks"])){


	$pagibig_loan_class = new PagibigLoan;
	$adjustment_loan_class = new AdjustmentLoan;
	$money_class = new Money;
	$date_class = new date;
	$emp_info_class = new EmployeeInformation;

	$datePayment = $date_class->dateDefaultDb($_POST["adjust_datePayment"]);
	$remainingBalance = $_POST["adjust_remainingBalance"];
	$cashPayment = $_POST["adjust_cashPayment"];
	$newRemainingBalance = $_POST["adjust_newRemainingBalance"];
	$remarks = $_POST["adjust_remarks"];

	$pagibigLoanId = $_POST["adjust_pagibigLoanId"];

	// for validation
	// if the cash payment is greater than remaining balance
	if ($cashPayment > $remainingBalance) {
		$_SESSION["adjustment_pagibigLoan_error"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Cash Payment Php ".$money_class->getMoney($cashPayment)."</b> must be not greater than the <b>Outstanding Balance Php ".$money_class->getMoney($remainingBalance)."</b>.</center>";
	}

	// if 0 the cash payment
	if ($cashPayment == 0) {
		$_SESSION["adjustment_pagibigLoan_error"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Cash Payment</b> must be not equal to <b>0</b>.</center>";
	}

	// if success
	else {
		// first update the remaining balance in the pagibig loan table
		$pagibig_loan_class->updateOnlyRemainingBalance($pagibigLoanId,$newRemainingBalance);

		$emp_id = $pagibig_loan_class->getInfoByPagibigLoanId($pagibigLoanId)->emp_id;
		$sss_loan_id = 0;
		$salary_loan_id = 0;
		$simkimban_id = 0;
		$loanType = "Pagibig Loan";
		//$sss_loan_id = 0;
		$current_date_time = $date_class->getDate();


		// for inserting to the adjustment reports
		$adjustment_loan_class->insertAdjustmentLoan($emp_id,$datePayment,$pagibigLoanId,$sss_loan_id,$salary_loan_id,$simkimban_id,$loanType,$cashPayment,$newRemainingBalance,$remarks,$current_date_time);
			
		

		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$fullName = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

		$_SESSION["adjustment_pagibigLoan_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Pag-ibig Loan</b> of  <b>Employee $fullName</b> is successfully adjusted.</center>";
	}




	header("Location:../pagibig_loan.php");

	/*echo "Remaining Balance: " . $remainingBalance . "<br/>";
	echo "Cash Payment: " . $cashPayment . "<br/>";
	echo "Remaining Balance: " . $newRemainingBalance . "<br/>";
	echo "Remars: " . $remarks . "<br/>";
	echo "Pag-ibig Loan Id:" . $pagibigLoanId . "<br/>";*/

}

else {
	header("Location:../MainForm.php");
}

?>