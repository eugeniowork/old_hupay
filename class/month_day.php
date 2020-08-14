<?php

class MonthDay{
	// for get the day of the month
	public function getDayOfMonth($month){

		// 7 months
		if ($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") {
			$total_day = 31;
			$counter = 1;
			do {
				echo "<option value='".$counter."'>";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 1 month
		if ($month == "February") {

			$year = date("Y");

			// if has leap year
			if ($year % 4 == 0) {
				$total_day = 29;
			}
			// if not a leap year
			else {
				$total_day = 28;
			}
			
			$counter = 1;
			do {
				echo "<option value='".$counter."'>";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 4 months
		if ($month == "April" || $month == "June" || $month == "September" || $month == "November") {
			$total_day = 30;
			$counter = 1;
			do {
				echo "<option value='".$counter."'>";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}
	}

	// get day of the month for updating
	public function getDayOfMonthUpdate($month,$day){

		// 7 months
		if ($month == "January" || $month == "March" || $month == "May" || $month == "July" || $month == "August" || $month == "October" || $month == "December") {
			$total_day = 31;
			$counter = 1;
		


			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				echo "<option value='".$counter."' ".$selected.">";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 1 month
		if ($month == "February") {

			$year = date("Y");

			// if has leap year
			if ($year % 4 == 0) {
				$total_day = 29;
			}
			// if not a leap year
			else {
				$total_day = 28;
			}
			
			$counter = 1;
			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				echo "<option value='".$counter."' ".$selected.">";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}

		// 4 months
		if ($month == "April" || $month == "June" || $month == "September" || $month == "November") {
			$total_day = 30;
			$counter = 1;
			do {
				// if equal
				$selected = "";
				if ($day == $counter) {
					$selected = "selected=selected";
				}	
				
				
				echo "<option value='".$counter."' ".$selected.">";
					echo $counter;
				echo "</option>";
				$counter++;
			}while($counter <= $total_day);
		}
	}


}


?>