<?php
session_start();
ini_set('max_execution_time', 300);
include "../class/connect.php";
include "../class/events.php";
include "../class/events_notifications.php";
include "../class/emp_information.php";

// if not isset
if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ //catch file overload error...
        $postMax = ini_get('post_max_size'); //grab the size limits...
        //echo "<p style=\"color: #F00;\">\nPlease note files larger than {$postMax} will result in this error!<br>Please be advised this is not a limitation in the CMS, This is a limitation of the hosting server.<br>For various reasons they limit the max size of uploaded files, if you have access to the php ini file you can fix this by changing the post_max_size setting.<br> If you can't then please ask your host to increase the size limits, or use the FTP uploaded form</p>"; // echo out error and solutions...
       // addForm(); //bounce back to the just filled out form.
        $_SESSION["event_upload_error"] = "The file size you have uploaded is larger than the maximum size limit of {$postMax}b";
        header("Location:../add_events.php");

}

else {
	// if edited in the inspect element
	if (isset($_POST["event"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_FILES["image"]["name"]) )
	{

		// for declaring class
		$date_class = new date;
		$event_class = new Events;

		$event = $_POST["event"];
		$title = $_POST["title"];

		//$date = $_POST["date"];
		//if ($_POST["date"] != ""){
		//	$date = $date_class->dateDefaultDb($_POST["date"]);
		//}


		$num_files = count($_FILES["image"]["tmp_name"]);

		


		$events_month = substr($_POST["date"],0,2);
		$events_day = substr(substr($_POST["date"], -7), 0,2);
		$events_year = substr($_POST["date"], -4);


		
		$current_date = $date_class->getDate();

		// for retrieval of information if has error
		$_SESSION["event"] = $event;
		$_SESSION["event_title"] = $title;
		$_SESSION["event_date"] = $_POST["date"];
		

		// if edited in the inspect element
		if ($event == "" || $title == ""){
			$_SESSION["upload_img_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There are error during saving of data, please sure you fill up required fields.</center>";
		}

		else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["date"])) {
	    	$_SESSION["upload_img_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Events Date</b> not match to the current format mm/dd/yyyy";
		}

		// for validating leap year
		else if ($events_year % 4 == 0 && $events_month == 2 && $events_day >= 30){
			$_SESSION["upload_img_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Events Date</b> date";
		}

		// for validating leap year also
		else if ($events_year % 4 != 0 && $events_month == 2 && $events_day >= 29){
			$_SESSION["upload_img_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Events Date</b> date";
		}

		// mga month na may 31
		else if (($events_month == 4 || $events_month == 6 || $events_month == 9 || $events_month == 11)
				&& $events_day  >= 31){
			$_SESSION["upload_img_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Events Date</b> date";
		}

		// if uploaded not an image file
		/*else if ($base_name != "" && $file_type != "jpg" && $file_type != "jpeg" && $file_type !="png"){
			$_SESSION["upload_img_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Please upload only image file (file type:jpg/ jpeg/ png)</center>";
		}*/
		// if the date is current date is beyond the date choose for event
		else if ($_POST["date"] != "" && ($current_date > $date_class->dateDefaultDb($_POST["date"]))){
			$_SESSION["upload_img_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Please choose date from date today onwards.</center>";
		}


		else {
			// if has no error set all session to null
			$_SESSION["event"] = null;
			$_SESSION["event_title"] = null;
			$_SESSION["event_date"] = null;


			$emp_id = $_SESSION["id"];
			$date = $date_class->dateDefaultDb($_POST["date"]);
			$event_class->insertEvents($emp_id,$event,$title,$date,$current_date);

			 // Loop through each file
			for($i=0; $i<$num_files; $i++) {


				$file_tmp_name = $_FILES["image"]["tmp_name"][$i];
				$base_name = basename($_FILES["image"]["name"][$i]);
				$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
				$file_name = "event_image" . $i . "."  . $file_type;


				

				

				// for insert
				


				$last_id =  $event_class->lastIdEvents();



				// path
				//img/event images kapag merong inupload
				if ($base_name != ""){
					$final_final_name = $last_id . "_" . $file_name;
					$path = "../img/events images/";
					$location = $path . $final_final_name;
					move_uploaded_file($file_tmp_name,$location);


					$db_path = "img/events images/";
					$db_location = $db_path . $final_final_name;


					// insert into datase for images
					$event_class->insertImages($last_id,$final_final_name,$db_location,$current_date);
				}
			}


			// for inserting notifications
			$events_notif_class = new EventsNotification;

			$notif_id = $_SESSION["id"];
			$events_id = $last_id;
			$notif_type = "posted an events for " . $title;
			$readStatus = 0;
			$dateTimeCreated = $date_class->getDateTime();


			// FOR NOTIFICATION
			// first check all count of active employee
			$emp_info_class = new EmployeeInformation;
			$emp_count = $emp_info_class->getCountActiveEmp();

			$notfi_emp_id = explode("#",$emp_info_class->getEmpIdAllActiveEmp());
			$emp_counter = 0;
			do {

					// for getting the employee information , dapat d mabigyan ng notification ung mga empleyadong wlang bio_id
					$row = $emp_info_class->getEmpInfoByRow($notfi_emp_id[$emp_counter]);

				//if ($row->bio_id != 0){

					$events_notif_class->insertNotifications($notif_id,$notfi_emp_id[$emp_counter],$events_id,$notif_type,$readStatus,$dateTimeCreated);
					//}
				//}

				$emp_counter++;
			}while($emp_counter < $emp_count);


			$_SESSION["event_success_msg"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>event $title</b> is successfully added to event list.</center>";
		}




		header("Location:../add_events.php");
	}

	//
	else {
		header("header:../MainForm.php");
	}


}

?>