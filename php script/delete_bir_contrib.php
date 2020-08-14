<?php
session_start();
include "../class/connect.php";
include "../class/BIR_Contribution.php";

if (isset($_SESSION["delete_bir_contribution"])){
	$bir_contrib_id = $_SESSION["delete_bir_contribution"];

	$bir_contrib_class = new BIR_Contribution;
	$bir_contrib_class->deleteContributionInfo($bir_contrib_id);

	$_SESSION["success_msg_del_bir_contrib"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Contribution</b> is successfully deleted</center>";
	header("Location:../birContributionTable.php");

}

else {
	header("Location:../MainForm.php");
}

?>