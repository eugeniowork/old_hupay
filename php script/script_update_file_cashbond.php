<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";

if (isset($_POST["update_amount_withdraw"])){
	$amount_withdraw = $_POST["update_amount_withdraw"];

	$emp_id = $_SESSION["id"];
	$cashbond_class = new Cashbond;

	$cashbond_class->updateFileCashbondWithrawal($emp_id,$amount_withdraw);

	$_SESSION["success_crud_cashbond"] = "<center><span class='glyphicon glyphicon-ok' style='color: #229954'></span>&nbsp;Your file cashbond withdrawal is successfully updated.</center>";

	header("Location:../cashbond.php");
}
else {
	header("Location:../dashboard.php");
}

?>