<?php
session_start();
include "class/connect.php";
include "class/cashbond_class.php";

if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
	$cashbond_class = new Cashbond;
	$cashbond_class->getCashBondReports();
}

else {
	header("Location:MainForm.php");
}









?>