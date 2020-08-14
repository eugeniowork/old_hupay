<?php
session_start();
ini_set('max_execution_time', 300);
include "../class/connect.php";

// if not isset
if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ //catch file overload error...
        $postMax = ini_get('post_max_size'); //grab the size limits...
        //echo "<p style=\"color: #F00;\">\nPlease note files larger than {$postMax} will result in this error!<br>Please be advised this is not a limitation in the CMS, This is a limitation of the hosting server.<br>For various reasons they limit the max size of uploaded files, if you have access to the php ini file you can fix this by changing the post_max_size setting.<br> If you can't then please ask your host to increase the size limits, or use the FTP uploaded form</p>"; // echo out error and solutions...
       // addForm(); //bounce back to the just filled out form.
        $_SESSION["attendance_upload_error"] = "The file size you have uploaded is larger than the maximum size limit of {$postMax}b";
        header("Location:../attendance_upload.php");

}
else {

?>
<?php

	
	if (isset($_FILES["dat_file"]["name"])){
		$file_tmp_name = $_FILES["dat_file"]["tmp_name"];
		$base_name = basename($_FILES["dat_file"]["name"]);
		$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
		$file_name = "dtr_dat_files" . "." . $file_type;

		// if the uploaded files is not dat
		if ($file_type != "dat"){
			$_SESSION["attendance_upload_error"] = "Please upload only dat files";			
			header("Location:../attendance_upload.php");
		}
		// success
		else {
			include "../class/dat_file_logs.php"; // for database purpose
			include "../class/date.php"; // for date purpose
			include "../class/time_in_time_out.php"; // for insert time in time out
			include "../class/Payroll.php";
			include "../class/cut_off.php";
			include "../class/emp_information.php";
			include "../class/attendance_notifications.php"; // fpr notifying
			include "../class/attendance_notif.php"; // for getting attendance notif purposes

			$date = new date;

			$dateCreated = $date->getDate();
			$dateTimeUploaded = $date->getDateTime();

			//echo $dateTimeUploaded;


			$dat_files = new DatFilesLog;
			// save the first entry
			if ($dat_files->numrowsDatFiles() == 0){
				
				$final_final_name = "1_".$file_name; 
				$dat_files->insertDatFiles($final_final_name,$date->getDate());
			}
			// id database has already has entry/entrys
			else {
				$last_id = $dat_files->lastIdDatFiles();
				$final_final_name = ++$last_id . "_".$file_name; 
				$dat_files->insertDatFiles($final_final_name,$date->getDate());
			}

			// uploading
			$path = "../dat files/";
			$location = $path . $final_final_name;
			move_uploaded_file($file_tmp_name,$location);

			// for saving the attendance info
			header('Content-Type: text/plain');
			$lines = file($location);
			$count = count($lines);
			$counter = 1;

			do {
				//echo $counter;
				//echo $lines[$counter];
			
				$data = explode("\t",$lines[$counter]);

				$bio_id = str_replace(" ","",$data[0]);

				$date =  (string)$data[1];

				$final_date_time = explode(" ",$date); // the index 0 is for date , the index 1 is used for time in or time out

				//echo $date . "\n";
				//echo $counter . ": BIO ID: ". $bio_id . ", DATE:" .$final_date_time[0] . ", TIME IN/OUT:" .$final_date_time[1] . "\n";

				$final_bio_id = (int)$bio_id;
				$final_date = $final_date_time[0];
				$final_time = $final_date_time[1]; // it will choose if it is time in or time out

				$time_type = "time in";
				if ((string)$data[3] . (string)$data[4] == "11"){
					$time_type = "time out";
				}


				$time_in_time_out = new Attendance();
				$emp_info_class = new EmployeeInformation;
				$attendance_notif_class = new AttendanceNotif;


				//echo $final_bio_id . "<br/>";
				// for getting emp info by bio id
				//echo $final_bio_id . "<br/>";
				if ($emp_info_class->checkExistBioId($final_bio_id) != 0) {
					$row_emp = $emp_info_class->getEmpInfoByBioId($final_bio_id);




					$final_emp_id = $row_emp->emp_id;

					//echo $final_bio_id . " " .$final_emp_id . "\n";
					
					// for security purpose
					if ($final_bio_id != 0) {

						//echo $final_bio_id . " " .$final_emp_id .  $time_type . " " . $final_time . " ". $time_in_time_out->getRowsTimeInOut($final_date,$final_bio_id) . "\n";



						// for inserting time in time out , if 0 num rows insert if exist update
						if ($time_in_time_out->getRowsTimeInOut($final_date,$final_bio_id) == 0){
								

							if ($time_type == "time in"){

								$time_in_time_out->insert_time_in_time_out($final_bio_id,$final_date,$final_time,'',$dateCreated);
							}

							// ibig sabihin time out
							if ($time_type == "time out"){

								$time_in_time_out->insert_time_in_time_out($final_bio_id,$final_date,'',$final_time,$dateCreated);
							}
						}
						else {

							// check if the time in and time out is not equal to 00:00:00 checkTimeInTimeOut($date,$bio_id,$time_in,$time_out)
							//if ($time_in_time_out->checkTimeInTimeOut($final_date,$final_bio_id) != 0) {
								//if ($time_in_time_out->selectExtist($final_date,$final_bio_id) != $final_time) {


									// check ko muna kung exist ung attendance date at emp id sa attendance_notif at notif_status =  1
							if ($attendance_notif_class->checkExistDateEmpIdApprove($final_emp_id,$final_date) == 0){

								//echo "DITO" . "\n";

								if ($time_type == "time in") {

									//echo "DITO TIME IN";

									//$time_in_time_out->updateTimeIn($final_date,$final_bio_id,$final_time);
								}

								if ($time_type == "time out"){ 
									//echo "DITO TIME OUT";
									$time_in_time_out->updateTimeOut($final_date,$final_bio_id,$final_time);
								}
							}
									
									/*else {
										
									}*/
									
								//}
							//}


						}
					}
				}
				
				
				$counter++;
			}while($counter < ($count - 1));
			
			$_SESSION["upload_success"] = "Successfully Uploaded";
			// for inserting generate payroll reports
			$payroll_class = new Payroll;
			$cut_off_class = new CutOff;

			$cutOffPeriod = $cut_off_class->getCutOffPeriodLatest();

			//echo $cutOffPeriod;

			// ibig sabihin d pa exist
			if ($payroll_class->existGeneratePayrollcutOff($cutOffPeriod) == 0){
				$payroll_class->insertGeneratePayroll($cutOffPeriod,$dateCreated);
			}

			//$payroll_class->insertGeneratePayroll($cutOffPeriod,$dateCreated);
			
			// for attendance notifications
			// $emp_id,$notif_emp_id,$notifType,$status,$dateTime
			

			// FOR NOTIFICATION
			// first check all count of active employee
			$emp_count = $emp_info_class->getCountActiveEmp();

			$attendance_notifications_class = new Attendance_Notifications; // for attendance notifications

			$notfi_emp_id = explode("#",$emp_info_class->getEmpIdAllActiveEmp());
			$emp_id = $_SESSION["id"];
			$notifType = "Uploaded attendance for the cut off period " . $cutOffPeriod;
			$emp_counter = 0;
			do {

				// for getting the employee information , dapat d mabigyan ng notification ung mga empleyadong wlang bio_id
				$row = $emp_info_class->getEmpInfoByRow($notfi_emp_id[$emp_counter]);

				if ($row->bio_id != 0){
					//echo . 
					//echo $attendance_notifications_class->existUploadAttendanceNotif($notfi_emp_id[$emp_counter],$emp_id,$notifType);
					// check if exist so nothing will be done
					if ($attendance_notifications_class->existUploadAttendanceNotif($notfi_emp_id[$emp_counter],$emp_id,$notifType) == 0){
						$attendance_notifications_class->insertNotifications($notfi_emp_id[$emp_counter],$emp_id,"0","0","0",$notifType,"Upload Attendance","",$dateTimeUploaded);
					}
				}

				$emp_counter++;
			}while($emp_counter < $emp_count); 

			//echo $emp_info_class->getEmpIdAllActiveEmp() ."<br/>";

			//echo $emp_count;



			
			/*$emp_id = $_SESSION["id"];
			$insertNotifications($emp_id);*/




			header("Location:../attendance_upload.php");



		}

		


		
	}
	else {
		header("Location:../MainForm.php");
	}
}
// if not isset

//$_SESSION["attendance_upload_error"] = "There an error during uploading";

?>