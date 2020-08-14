<?php
session_start();
ini_set('max_execution_time', 300);
include "../class/connect.php";
include "../class/events.php";


// if not isset
if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ //catch file overload error...
        $postMax = ini_get('post_max_size'); //grab the size limits...
        //echo "<p style=\"color: #F00;\">\nPlease note files larger than {$postMax} will result in this error!<br>Please be advised this is not a limitation in the CMS, This is a limitation of the hosting server.<br>For various reasons they limit the max size of uploaded files, if you have access to the php ini file you can fix this by changing the post_max_size setting.<br> If you can't then please ask your host to increase the size limits, or use the FTP uploaded form</p>"; // echo out error and solutions...
       // addForm(); //bounce back to the just filled out form.
        $_SESSION["update_event_upload_error"] = "The file size you have uploaded is larger than the maximum size limit of {$postMax}b";
        header("Location:../event_list.php");

}

else {
	// if edited in the inspect element

	if (isset($_POST["event"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_FILES["image"]["name"]))
	{
	
		$events_id = $_SESSION["update_events_id"];
		$_SESSION["update_events_id"] = null;

		// for declaring class
		$date_class = new date;
		$event_class = new Events;

		$event = $_POST["event"];
		$title = $_POST["title"];

		$date = $_POST["date"];
		if ($_POST["date"] != ""){
			$date = $date_class->dateDefaultDb($_POST["date"]);
		}

		$num_files = count($_FILES["image"]["tmp_name"]);

		


		
		$current_date = $date_class->getDate();

		// for retrieval of information if has error
		//$_SESSION["event"] = $event;
		//$_SESSION["event_title"] = $title;
		//$_SESSION["event_date"] = $_POST["date"];
		

		// if edited in the inspect element
		if ($event == "" || $title == ""){
			$_SESSION["update_event_upload_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There are error during saving of data, please sure you fill up required fields.</center>";
		}

		// if uploaded not an image file
		/*else if ($base_name != "" && $file_type != "jpg" && $file_type != "jpeg" && $file_type !="png"){
			$_SESSION["update_event_upload_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Please upload only image file (file type:jpg/ jpeg/ png)</center>";
		}*/
		// if the date is current date is beyond the date choose for event
		else if ($date != "" && ($current_date > $date)){
			$_SESSION["update_event_upload_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Please choose date from date today onwards.</center>";
		}

		else {
			/*
			// if has no error set all session to null
			$_SESSION["event"] = null;
			$_SESSION["event_title"] = null;
			$_SESSION["event_date"] = null;

			


			$last_id =  $event_class->lastIdEvents();



			// path
			//img/event images
			$final_final_name = $last_id . "_" . $file_name;
			$path = "../img/events images/";
			$location = $path . $final_final_name;
			move_uploaded_file($file_tmp_name,$location);


			$db_path = "img/events images/";
			$db_location = $db_path . $final_final_name;


			// insert into datase for images
			$event_class->insertImages($last_id,$final_final_name,$db_location,$current_date);

		*/

				
			// for delete images muna sa data base
			$event_class->deleteEventsImage($events_id);

			
			$emp_id = $_SESSION["id"];

			/*echo $events_id . "<br/>";
			echo $emp_id . "<br/>";
			echo $event . "<br/>";
			echo $title . "<br/>";
			echo $date . "<br/>";*/

			// for update
			$event_class->updateEvents($events_id,$emp_id,$event,$title,$date);

			for($i=0; $i<$num_files; $i++) {

				$file_tmp_name = $_FILES["image"]["tmp_name"][$i];
				$base_name = basename($_FILES["image"]["name"][$i]);
				$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
				$file_name = "event_image" . $i . "."  . $file_type;
				

				// no need to change the info
				if ($base_name != ""){

				
					$existImage = $event_class->checkExistImage($events_id);

					$final_final_name = $events_id . "_" . $file_name;
					$path = "../img/events images/";
					$location = $path . $final_final_name;
					move_uploaded_file($file_tmp_name,$location);


					// so kapag wla pang info save
					//if ($existImage == 0){
						$db_path = "img/events images/";
						$db_location = $db_path . $final_final_name;


						// insert into datase for images
						$event_class->insertImages($events_id,$final_final_name,$db_location,$current_date);
					//}
				}
			}


			// 

			$_SESSION["update_event_success_msg"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The event is successfully updated.</center>";
		
		}

		header("Location:../event_list.php"); 
	}

	//
	else {
		header("header:../MainForm.php");
	}


}

?>