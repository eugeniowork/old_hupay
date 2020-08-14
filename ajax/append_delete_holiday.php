<?php
session_start();
include "../class/connect.php";
include "../class/holiday_class.php";
include "../class/date.php";

if (isset($_POST["holiday_id"])) {
	$holiday_id = $_POST["holiday_id"];

	$holiday_class = new Holiday;

	// if exist
	if ($holiday_class->existHolidayId($holiday_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $holiday_class->getHolidayInfoByRow($holiday_id);
		$_SESSION["delete_holiday_id"] = $holiday_id;
	
?>
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b><?php echo $row->holiday_date . " - " . $row->holiday_value;  ?></b>?</span>
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