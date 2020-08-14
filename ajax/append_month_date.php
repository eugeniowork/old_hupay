<?php
include "../class/month_day.php";


if (isset($_POST["month"])){
	$month = $_POST["month"];

	// if edited in the inspect element
	if ($month != "" && $month != "January" && $month != "February" && $month != "March" 
		&& $month != "April" && $month != "May" && $month != "June" && $month != "July"
		 && $month != "August" && $month != "September" && $month != "October" 
		 && $month != "November" && $month != "December") {
		echo "Error";
	}

	else {
		$month_day_class = new MonthDay;
		$month_day_class->getDayOfMonth($month);
	}

}
else {
	header("Location:../MainForm.php");
}
	


?>