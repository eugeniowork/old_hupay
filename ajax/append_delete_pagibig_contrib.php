<?php
session_start();
include "../class/connect.php";
include "../class/Pagibig_Contribution.php";
include "../class/date.php";

if (isset($_POST["pagibig_contrib_id"])) {
	$pagibig_contrib_id = $_POST["pagibig_contrib_id"];

	$pagibig_contrib_class = new Pagibig_Contribution;

	// if exist
	if ($pagibig_contrib_class->existContributionId($pagibig_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $pagibig_contrib_class->getInfoByContribId($pagibig_contrib_id);
		$_SESSION["delete_pagibig_contribution"] = $pagibig_contrib_id;

		if ($row->compensationFrom == "0"){
			$compensationFrom = "Php 0.00";
		}
		else {
			$compensationFrom = $pagibig_contrib_class->getMoney($row->compensationFrom);	
		}
	
?>
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $compensationFrom ." - " . $pagibig_contrib_class->getMoney($row->compensationTo); ?></b> range of compensation with the contribution <b><?php echo $pagibig_contrib_class->getMoney($row->Contribution); ?></b>?</span>
		</div>						
	</div>


	<script>
		$(document).ready(function(){
			

	    });
	</script>

<?php
	} // end if else
}
else {
	header("Location:../Mainform.php");
}
?>