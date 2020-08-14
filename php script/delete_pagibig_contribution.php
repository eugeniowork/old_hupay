<?php
session_start();
include "../class/connect.php";
include "../class/Pagibig_Contribution.php";

if (isset($_SESSION["delete_pagibig_contribution"])){
	$pagibig_contrib_id = $_SESSION["delete_pagibig_contribution"];

	$pagibig_contrib_class = new Pagibig_Contribution;
	$pagibig_contrib_class->deleteContributionInfo($pagibig_contrib_id);

	$_SESSION["success_msg_del_pagibig_contrib"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution</b> is successfully deleted</center>";
	header("Location:../pagibigContributionTable.php");

}

else {
	header("Location:../MainForm.php");
}

?>