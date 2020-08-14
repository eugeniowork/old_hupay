<?php
include "../class/connect.php";
include "../class/working_days_class.php";


if (isset($_POST["working_days_id"])){
	$working_days_id = $_POST["working_days_id"];

	$working_days_class = new WorkingDays;

	if ($working_days_class->checkExistWorkingDaysId($working_days_id) == 0){
		echo "Error";
	}

	else {

		$row = $working_days_class->getWorkingDaysInfoById($working_days_id);
		$day_from = $row->day_from;
		$day_to = $row->day_to;

		$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		$day_of_the_week_value = [0,1,2,3,4,5,6];

		$count = count($day_of_the_week);

		$counter = 0;

		$day_from_value = "";
		$day_to_value = "";
		do {

			if ($day_of_the_week_value[$counter] == $day_from){
				$day_from_value = $day_of_the_week[$counter];
			}

			if ($day_of_the_week_value[$counter] == $day_to){
				$day_to_value = $day_of_the_week[$counter];
			}


			$counter++;
		}while($count > $counter);

?>
	<form class="" action="" method="post" id="form_deleteWorkingDays">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b>Working Days</b> of <b><?php echo $day_from_value . "-" . $day_to_value; ?></b> ?</b></span>
			</div>						
		</div>
	</form>

<?php
	} // end of else
} // end of if isset

else {
	header("Location:../MainForm.php");
}


?>