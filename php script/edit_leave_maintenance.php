<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";


//echo "Wew";


if (isset($_POST["name"]) && isset($_POST["validation"]) && isset($_POST["no_days_to_file"]) && isset($_POST["leave_count"]) && isset($_POST["is_convetable_to_cash"])
	&& isset($_POST["lt_id"])){

	$name = $_POST["name"];
	$validation = $_POST["validation"];
	$no_days_to_file = $_POST["no_days_to_file"];
	$leave_count = $_POST["leave_count"];
	$is_convetable_to_cash = $_POST["is_convetable_to_cash"];
	$lt_id = $_POST["lt_id"];

	$leave_class = new Leave;


	/*select all employee where activestatus = '1' AND emp_id > 1
	{

		$emp_id = $row->emp_id;
	
		$leave_array = array();
		$leave_count_array = array();

		select all leave_type where status = '1' {
			
			array_push($leave_array,$lt_id);
			array_push($leave_count_array,$leave_count);

			//$leave_array = array("1");
			// $leave_array = array("1,2");
		}	


		// insert
		insert($emp_id,$leave_array,$leave_count_array);
	}





	select tb_emp_leave whewe emp_id = '$emp_id'
	{
		$leave_array = 1,2,3,4,5,6
		$leave_count_array = 5,5,1,1,1,3

		$leave_array_explode = explode(",", leave_array);
		$leave_array_count_explode = explode(",", leave_count_array);

		$count = count($leave_array_explode);

		$counter = 0;


		do {

			$lt_id = $leave_array_explode[$counter]; // 1 , 2 , 3, 4, 5, 6

			select from tb_leave_type where lt_id  = '$lt_id'


			echo "<tr>";
				echo "<td>".$name."</td>";
				echo "<td>".$leave_array_count_explode[$counter]."</td>";

			echo "</tr>";



			$counter++; // 1 , 2, 3, 4, 5
		}while($count > $counter);



	}*/







	// check exist name
	if ($leave_class->checkExistLeaveTypeName($name,$lt_id,"Edit") == 1){
		echo "<span class='color-red'>Leave Type <b>$name</b> is already exist in the list</span>";
	}


	// check if not exist validation id
	else if ($leave_class->checkExistLeaveValidation($validation) == 0){
		echo "<span class='color-red'>There's an error during saving of file.</span>";
	}

	else {
		$leave_class->editLeaveType($name,$validation,$no_days_to_file,$leave_count,$is_convetable_to_cash,$lt_id);

		$_SESSION["leave_type"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Leave Type is  successfully updated</center>";
		echo "Success";

	}
}

else {
	header("Location:../Mainform.php");
}

?>