<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";

$emp_id = $_SESSION["id"];

$cashbond_class = new Cashbond;


//echo $cashbond_class->getCountPendingCashbondWithdrawal($emp_id);
if ($cashbond_class->getCountPendingCashbondWithdrawal($emp_id) != 0){

	$cashbond_class->cancelCashbondWithdrawal($emp_id);


	$_SESSION["success_crud_cashbond"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954'></span>&nbsp;Your file cashbond withdrawal is successfully cancelled.</center>";

	header("Location:../cashbond.php");

}
else {
	header("Location:../dashboard.php");
}

//echo $user_id;




?>