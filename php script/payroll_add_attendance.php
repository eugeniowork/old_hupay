<?php
session_start();
include "../class/connect.php";
include "../class/cut_off.php";
include "../class/emp_information.php";
include "../class/time_in_time_out.php";
include "../class/date.php";


$cut_off_class = new CutOff;
$emp_info_class = new EmployeeInformation;
$attendance_class = new Attendance;
$date_class = new date;



$emp_id = $_GET["emp_id"];
$row = $emp_info_class->getEmpInfoByRow($emp_id);

$name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;
//echo $name;

$bio_id = $row->bio_id;
//echo $row->bio_id;


//echo $emp_id;
//echo $emp_id . "<br/>";

$count = $cut_off_class->getCutOffAttendanceDateCount();

$counter = 0;
do {

	$counter++;

	//if (isset($_POST["attendance_date".$counter]) && isset($_POST["time_in_hour_attendance".$counter]) && isset($_POST["time_in_min_attendance".$counter])
	//	&& isset($_POST["time_in_period".$counter]) && isset($_POST["time_out_hour_attendance".$counter]) && isset($_POST["time_out_min_attendance".$counter]) 
	//	&& isset($_POST["time_out_period".$counter])){
	if ($_POST["time_in_hour_attendance".$counter] != "" && $_POST["time_in_min_attendance".$counter] && $_POST["time_out_hour_attendance".$counter] != "" && $_POST["time_out_min_attendance".$counter] != ""){

		//echo $counter ."<br/>";


		$attendance_date = $_POST["attendance_date".$counter];

		//$attendance_date = $_POST[""];

		$time_in = date_format(date_create($_POST["time_in_hour_attendance".$counter] . ":" . $_POST["time_in_min_attendance".$counter] ),"H:i:s");
		
		$time_in_period = $_POST["time_in_period".$counter];
		//echo $time_in_period . "<br/>";

		if ($time_in_period == "PM"){
			$time_in  = date("H:i:s",strtotime($time_in ." +12 hours"));
		}

		$time_out = date_format(date_create($_POST["time_out_hour_attendance".$counter] . ":" . $_POST["time_out_min_attendance".$counter]),"H:i:s");
		$time_out_period = $_POST["time_out_period".$counter];
		if ($time_out_period == "PM"){
			$time_out  =  date("H:i:s",strtotime($time_out ." +12 hours"));
		}


		//echo $time_out_period . "<br/>";

		//echo $time_in_period . "<br/>";
		//echo $time_out_period . "<br/>";

		if ($time_in_period != "AM" && $time_in_period != "PM"){
			//echo $attendance_date . " " . $time_in . " " .$time_out ."Hindi masisave";
		}

		else if ($time_in >= $time_out){
			//echo $attendance_date . " " . $time_in . " " .$time_out ."Hindi masisave";
		}

		else {
			//echo $time_in;
			//echo $time_out . "<br/>";

			$dateCreated = $date_class->getDate();

			$attendance_date = $date_class->dateDefaultDb($attendance_date);

			//echo $attendance_date . "<br/>";
			//echo $bio_id . "<br/>";
			//echo $time_in . "<br/>";
			//echo $time_out;


			
			// check muna natin kung exist ung attendance date at bio id para update lang
			if ($attendance_class->getRowsTimeInOut($attendance_date,$bio_id) == 1){
				$attendance_class->updateAttendanceInfo($attendance_date,$bio_id,$time_in,$time_out);
			}
			// insert kapag wla pa
			else {
				$attendance_class->insert_time_in_time_out($bio_id,$attendance_date,$time_in,$time_out,$dateCreated);
			}


		}

	/*$time_in = $_POST["time_in_hour_attendance".$counter] . ":" . $_POST["time_in_min_attendance".$counter] . " " . $_POST["time_in_period".$counter];
	$time_out = $_POST["time_out_hour_attendance".$counter] . ":" . $_POST["time_out_min_attendance".$counter] . " " . $_POST["time_out_period".$counter];

	echo $attendance_date . " ";
	echo $time_in. " ";
	echo $time_out . "<br/>";
	*/
	} // end of if isset
	

	//header("Location:../add_attendance.php");
}while($count > $counter);


$cut_off_period = $cut_off_class->getCutOffPeriodLatest();
$_SESSION["success_add_attendance_no_bio"] = "<span class='glyphicon glyphicon-ok' style='color:#229954'></span> <b>".$name."</b> attendance for the cut off <b>".$cut_off_period."</b> is successfully added";
header("Location:../add_attendance.php");
//echo $_SESSION["add_attendance_no_reg_bio"];
?>