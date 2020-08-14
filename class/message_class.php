<?php
class Message extends Connect_db {

	public function insertMessage($from_emp_id,$to_emp_id,$subject,$message,$dateCreated){
		$connect = $this->connect();

		$from_emp_id = mysqli_real_escape_string($connect,$from_emp_id);
		$to_emp_id = mysqli_real_escape_string($connect,$to_emp_id);
		$subject = mysqli_real_escape_string($connect,$subject);
		$message = mysqli_real_escape_string($connect,$message);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_message_logs (message_id,from_emp_id,to_emp_id,subject,message,dateCreated)
					VALUES ('','$from_emp_id','$to_emp_id','$subject','$message','$dateCreated')";

		$sql = mysqli_query($connect,$insert_qry);
	}


	// for checking the count of message notifications unread
	public function unreadMessagesCount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_message_logs WHERE to_emp_id = '$emp_id' AND to_readStatus = '0'"));

		return $num_rows;
	}


	// for getting all message from the database
	public function getAllMessageToTable($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_message_logs WHERE to_emp_id = '$emp_id' OR from_emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				//$date_create = date_create($date);
				$date = date_format(date_create($row->dateCreated), 'm/d/Y');

				// from
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->from_emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				// to
				$select_emp_to_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->to_emp_id'";
				$result_emp_to = mysqli_query($connect,$select_emp_to_qry);
				$row_emp_to = mysqli_fetch_object($result_emp_to);


				$toName = "You";

				//$read_style = "";
				if ($emp_id != $row->to_emp_id){
					$toName = $row_emp_to->Firstname . " " . $row_emp_to->Lastname;
					
					///if($row->to_readStatus == '0'){
				//		$read_style= "color:#2980b9";
				//	}
				}


				$fromName = "You";

				$read_style = "";
				if ($emp_id != $row->from_emp_id){
					$fromName = $row_emp->Firstname . " " . $row_emp->Lastname;
					
					if($row->to_readStatus == '0'){
						$read_style= "color:#2980b9";
					}
				}

				


				echo "<tr id='".$row->message_id."'>";
					echo "<td>".$date."</td>";
					echo "<td>".$fromName."</td>";
					echo "<td>".$toName."</td>";
					echo "<td>".$row->subject."</td>";
					echo "<td id='readmoreValueMemo' style='".$read_style."'>" .nl2br($row->message). "</td>";
					echo "<td>";
						echo "<div style='cursor:pointer;color:#158cba;' id='read_message'><span class='glyphicon glyphicon-eye-open' style='color:#186a3b'></span> Read</div>";
					echo "</td>";

				echo "</tr>";
			}
		}
	}


	// for checking if id is existing
	public function checkExistMessageId($message_id){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_message_logs WHERE message_id = '$message_id'"));

		return $num_rows;
	}


	// for una ung main message
	public function getInfoByMessageId($message_id){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);

		$select_qry = "SELECT * FROM tb_message_logs WHERE message_id = '$message_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;


	}


	// for inserting into message reply
	public function insertMessageReply($message_id,$from_emp_id,$to_emp_id,$reply,$dateCreated){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);
		$from_emp_id = mysqli_real_escape_string($connect,$from_emp_id);
		$to_emp_id = mysqli_real_escape_string($connect,$to_emp_id);
		$reply = mysqli_real_escape_string($connect,$reply);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);
		
		$insert_qry = "INSERT INTO tb_message_reply (message_reply_id,message_id,from_emp_id,to_emp_id,reply,dateCreated) 
						VALUES ('','$message_id','$from_emp_id','$to_emp_id','$reply','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}


	// for getting all the reply
	public function getReplyMessages($message_id){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);

		$select_qry = "SELECT * FROM tb_message_reply WHERE message_id = '$message_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_message_qry = "SELECT * FROM tb_message_logs WHERE message_id = '$message_id'";
				$result_message = mysqli_query($connect,$select_message_qry);
				$row_message = mysqli_fetch_object($result_message);

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->from_emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$from_name = $row_emp->Firstname . " " . $row_emp->Lastname;

				///$date_create = ;
				$date = date_format(date_create($row->dateCreated), 'F d, Y');
				$time = date_format(date_create($row->dateCreated), 'g:i A');

				echo '<div class="col-md-10 col-md-offset-1" style="border:1px solid #BDBDBD;padding:5px;background-color:#fff">

						<div class="col-md-1">
							<img src="'.$row_emp->ProfilePath.'" class="events-profile-pic"/>
						</div>
						<div class="col-md-8">
							<div class="col-md-12">
								<b>'.$from_name.'</b>
							</div>
							<div class="col-md-12">
								<span style="word-wrap: break-word" id="readmoreReply">'.nl2br(htmlspecialchars($row->reply)).'</span>
							</div>
						</div>
						<div class="col-md-3" style="">
							<small style="color:#707b7c"><i>'.$date.' , '.$time.'</i></small>
						</div>
					</div>';
			}
		}


		
	}


	// if message is from you
	public function readMessage($message_id,$own_message){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);
		$own_message = mysqli_real_escape_string($connect,$own_message);

		// ibig sabihin d sa kanya message
		if ($own_message == 0){
			$update_qry = "UPDATE tb_message_logs SET to_readStatus = '1' WHERE message_id = '$message_id'";
		}
		// if sa kanya
		else {
			$update_qry = "UPDATE tb_message_logs SET from_readStatus = '1' WHERE message_id = '$message_id'";
		}

		$sql = mysqli_query($connect,$update_qry);
	}


	// if message is from you
	public function unreadMessage($message_id,$own_message){
		$connect = $this->connect();

		$message_id = mysqli_real_escape_string($connect,$message_id);
		$own_message = mysqli_real_escape_string($connect,$own_message);

		// ibig sabihin d sa kanya message
		if ($own_message == 0){
			$update_qry = "UPDATE tb_message_logs SET from_readStatus = '0' WHERE message_id = '$message_id'";
		}
		// if sa kanya
		else {
			$update_qry = "UPDATE tb_message_logs SET to_readStatus = '0' WHERE message_id = '$message_id'";
		}

		$sql = mysqli_query($connect,$update_qry);
	}
}




?>