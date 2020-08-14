<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/date.php";
include "../class/money.php";

if (isset($_POST["amount_withdraw"])){
	$date_class = new date;
	$cashbond_class = new Cashbond;
	$money_class = new Money;

	$amount_withdraw = $_POST["amount_withdraw"];
	$emp_id = $_SESSION["id"];
	$dateCreated = $date_class->getDate();

	if ($cashbond_class->checkExistFileCashbondWithdrawal($emp_id) == 1){

		$_SESSION["error_file_cashbond_withdrawal"] = "<center><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span> You have an existing <b>pending</b> file <b>cashbond withdrawal</b>.</center>";

	}
	else {

		$cashbond_class->insertFileCashbondWithdrawal($emp_id,$amount_withdraw,$dateCreated);

		// notif will go here
 
		$_SESSION["success_file_cashbond_withdrawal"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954 '></span> You successfully file cashbond withdrawal with the amount of <b>Php " . $money_class->getMoney($amount_withdraw) . "</b></center>";
	}
	header("Location:../cashbond.php");
}

else {
	header("Location:../MainForm.php");
}
?>