<?php
session_start();
include "../class/connect.php";
include "../class/Philhealth_Contribution.php";
include "../class/date.php";

if (isset($_POST["philhealth_contrib_id"])) {
	$philhealth_contrib_id = $_POST["philhealth_contrib_id"];

	$philhealth_contrib_class = new Philhealth_Contribution;

	// if exist
	if ($philhealth_contrib_class->existContributionId($philhealth_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $philhealth_contrib_class->getInfoByContribId($philhealth_contrib_id);
		$_SESSION["delete_philhealth_contribution"] = $philhealth_contrib_id;

		if ($row->compensationFrom == "0"){
			$compensationFrom = "Php 0.00";
		}
		else {
			$compensationFrom = $philhealth_contrib_class->getMoney($row->compensationFrom);	
		}


	
?>
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $compensationFrom ." - " . $philhealth_contrib_class->getMoney($row->compensationTo); ?></b> range of compensation with the contribution <b><?php echo $philhealth_contrib_class->getMoney($row->Contribution); ?></b>?</span>
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