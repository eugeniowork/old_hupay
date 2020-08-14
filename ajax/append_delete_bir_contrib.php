<?php
session_start();
include "../class/connect.php";
include "../class/BIR_Contribution.php";
include "../class/date.php";

if (isset($_POST["bir_contrib_id"])) {

	$bir_contrib_id = $_POST["bir_contrib_id"];

	$bir_contrib_class = new BIR_Contribution;

	// if exist
	if ($bir_contrib_class->existContributionId($bir_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $bir_contrib_class->getInfoByContribId($bir_contrib_id);
		$_SESSION["delete_bir_contribution"] = $bir_contrib_id;
	
?>
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $row->Status; ?></b> with <b> amount - <?php echo "Php " . $row->amount; ?>, contribution - <?php echo "Php " . $row->Contribution; ?></b> and <b>percentage - <?php echo $row->percentage . "%"; ?></b> ?</span>
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