<?php
include "date.php";

	class Events extends Connect_db {

		// for getting all events render to events dashboard
		public function getAllEvents(){
			
			echo '<div class="col-sm-9">';

			$date_class = new date;
			$connect = $this->connect();
			$select_qry = "SELECT * FROM tb_events ORDER BY dateTimeCreated DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
					// for emp information
					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$row->emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					// for position
					$select_pos_qry = "SELECT * FROM tb_position WHERE position_id='$row_emp->position_id'";
					$result_post = mysqli_query($connect,$select_pos_qry);
					$row_post = mysqli_fetch_object($result_post);


					// for images

					$existEventsImg = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_events_images WHERE events_id='$row->events_id'"));

					$with_image = "";
					if ($existEventsImg != 0) {
						$select_events_img_qry = "SELECT * FROM tb_events_images WHERE events_id='$row->events_id'";
						if ($result_events_img = mysqli_query($connect,$select_events_img_qry)){
							while ($row_events_img = mysqli_fetch_object($result_events_img)){
								$with_image .= '<div class="col-sm-8 col-sm-offset-2" style="text-align:center;">
								 				<a href="'.$row_events_img->imagePath.'"><img src="'.$row_events_img->imagePath.'" class="events-img" /></a>
							 				</div>';
							}
						}

	
						
					}

					// for date of event
					$with_date = "";
					if ($row->events_date != "0000-00-00"){
						$with_date = "<b>Event Date:</b> " . $date_class->dateFormat($row->events_date) . "<br/><br/>";
					}



					
					echo '<div class="panel panel-primary" style="" id="events_'.$row->events_id.'">
								 <div class="panel-heading" style="border-color: #21618c ;border-width:0 1px 4px 1px;padding:3px 10px;">
							 		' .$row->events_title.''
							 		.'<span class="pull-right">Date Posted: '.$date_class->dateFormat($row->dateTimeCreated).'</span>'.
								 '</div>
								 <div class="panel-body">
									<div class="col-sm-1 div-events-profile">
										<img src="'.$row_emp->ProfilePath.'" class="events-profile-pic"/>
									</div>
									<div class="col-sm-11"><strong>'
										.$row_emp->Firstname .$row_emp->Middlename.$row_emp->Lastname.
									'</strong><br/><small>'.$row_post->Position.'</small></div>
									<div class="col-sm-12" style="margin-top:15px">'.
											$with_image
										.'<div class="col-sm-12">
											'. $with_date . '"'.nl2br($row->events_value).'"
										</div>
							 		</div>
								 </div>
							</div>';
				}
			}
		
			echo '</div>';

		}


		// for events to table
		public function eventsInfoToTable(){
			$connect = $this->connect();
			$date_class = new date;

			$user_id = $_SESSION["id"];

			$select_qry = "SELECT * FROM tb_events ORDER BY dateTimeCreated DESC";
			if ($result = mysqli_query($connect,$select_qry)){
				while($row = mysqli_fetch_object($result)){
		



					$events_date = $row->events_date;
					if ($events_date == "0000-00-00"){
						$events_date = "No date";
					}
					else {
						$events_date = $date_class->dateFormat($row->events_date);
					}

					if ($user_id != 21){

						echo "<tr id='".$row->events_id."'>";
							echo "<td id='readmoreValue' width='45%'>".nl2br($row->events_value)."</td>"; 
							echo "<td width='16%'>".$row->events_title."</td>";
							echo "<td width='13%'>".$events_date."</td>";
							echo "<td width='13%'>".$date_class->dateFormat($row->dateTimeCreated)."</td>";
							echo "<td width='15%'>";
								echo "<a href='#' id='edit_events' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
								echo "<span> | </span>";
								echo "<a href='#' id='delete_events' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> Delete</a>";
							echo "</td>";
						echo "</tr>";
					}

					else {
						echo "<tr id='".$row->events_id."'>";
							echo "<td id='readmoreValue' width='45%'>".nl2br($row->events_value)."</td>"; 
							echo "<td width='16%'>".$row->events_title."</td>";
							echo "<td width='13%'>".$events_date."</td>";
							echo "<td width='13%'>".$date_class->dateFormat($row->dateTimeCreated)."</td>";
							echo "<td width='15%'>";
								//echo "<a href='#' id='edit_events' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>";
								//echo "<span> | </span>";
								//echo "<a href='#' id='delete_events' class='action-a'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> Delete</a>";
							echo "No action";
							echo "</td>";
						echo "</tr>";
					}
				}
			}
		}



	// for adding events
	public function insertEvents($emp_id,$event,$title,$date,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$event = mysqli_real_escape_string($connect,$event);
		$title = mysqli_real_escape_string($connect,$title);
		$date = mysqli_real_escape_string($connect,$date);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_events (events_id,emp_id,events_value,events_title,events_date,dateTimeCreated) 
									VALUES ('','$emp_id','$event','$title','$date','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// for adding events
	public function updateEvents($events_id,$emp_id,$event,$title,$date){
		$connect = $this->connect();

		$events_id = mysqli_real_escape_string($connect,$events_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$event = mysqli_real_escape_string($connect,$event);
		$title = mysqli_real_escape_string($connect,$title);
		$date = mysqli_real_escape_string($connect,$date);
		//$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$update_qry = "UPDATE tb_events SET emp_id='$emp_id', events_value = '$event', events_title = '$title', events_date ='$date' WHERE events_id = '$events_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


		// for get the last id
	public function lastIdEvents(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_events ORDER BY events_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->events_id;
		return $last_id;
	}



	// for insert in events images
	public function insertImages($events_id,$imageName,$imagePath,$dateCreated){
		$connect = $this->connect();

		$events_id = mysqli_real_escape_string($connect,$events_id);
		$imageName = mysqli_real_escape_string($connect,$imageName);
		$imagePath = mysqli_real_escape_string($connect,$imagePath);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);


		$insert_qry = "INSERT INTO tb_events_images (events_img_id,events_id,imageName,imagePath,DateCreated) 
										VALUES ('','$events_id','$imageName','$imagePath','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// for exist events id
	public function existEventsId($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_events WHERE events_id = '$events_id'"));
		return $num_rows;

	}

	// for getting information
	public function getEventsInfoById($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$select_qry = "SELECT * FROM tb_events WHERE events_id='$events_id'";
		$result= mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// for checking if has exist image
	public function checkExistImage($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_events_images WHERE events_id='$events_id'"));
		return $num_rows;
	}

	// for getting the image path in events images
	public function getEventsImagesById($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$select_qry = "SELECT * FROM tb_events_images WHERE events_id='$events_id'";
		if ($result= mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				echo '<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<div style="border:1px solid #BDBDBD;" class="div-uploaded-img">
								<label class="label-control">Uploaded Images:</label> <br/>
								<center>
									<img src="'. $row->imagePath.'" class="update-event-img"/>
								</center>
							</div>							
						</div>
					</div>';
			}
		}
		//return $row;
	}

	// for delete events info
	public function deleteEventsInfo($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$delete_qry = "DELETE FROM tb_events WHERE events_id='$events_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}

	// for delete events images
	public function deleteEventsImage($events_id){
		$connect = $this->connect();
		$events_id = mysqli_real_escape_string($connect,$events_id);
		$delete_qry = "DELETE FROM tb_events_images WHERE events_id='$events_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}
}


?>