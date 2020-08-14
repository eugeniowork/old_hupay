<?php

class AuditTrail extends Connect_db{

	public function insertAuditTrail($file_emp_id,$approve_emp_id,$involve_emp_id,$module,$task_description,$dateCreated){
		$connect = $this->connect();

		$file_emp_id = mysqli_real_escape_string($connect,$file_emp_id);
		$approve_emp_id = mysqli_real_escape_string($connect,$approve_emp_id);
		$involve_emp_id = mysqli_real_escape_string($connect,$involve_emp_id);
		$module = mysqli_real_escape_string($connect,$module);
		$task_description = mysqli_real_escape_string($connect,$task_description);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_audit_trail (audit_trail_id,file_emp_id,approve_emp_id,involve_emp_id,module,task_description) 
					VALUES ('','$file_emp_id','$approve_emp_id','$involve_emp_id','$module','$task_description')";

		$sql = mysqli_query($connect,$insert_qry);

	}


	public function getAuditTrailToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_audit_trail ORDER BY dateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$date = date_format(date_create($row->dateCreated), 'F d, Y');
				$time = date_format(date_create($row->dateCreated), 'g:i A');

				$from = "";
				$to = "";
				if ($row->file_emp_id != '0' && $row->approve_emp_id != '0'){

					$select_from_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->file_emp_id'";
					$result_from = mysqli_query($connect,$select_from_qry);
					$row_from = mysqli_fetch_object($result_from);

					$from = " <b>from " .  $row_from->Firstname . " " . $row_from->Lastname ."</b>";


					$select_to_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->approve_emp_id'";
					$result_to = mysqli_query($connect,$select_to_qry);
					$row_to = mysqli_fetch_object($result_to);


					$to = "<b>".$row_to->Firstname . " " . $row_to->Lastname . "</b> ";

				}


				$involve = "";
				$who = "";
				if ($row->involve_emp_id != '0') {


					$select_involve_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->involve_emp_id'";
					$result_involve = mysqli_query($connect,$select_involve_qry);
					$row_involve = mysqli_fetch_object($result_involve);

					$involve = " <b>" .  $row_involve->Firstname . " " . $row_involve->Lastname ." </b>";

					if ($row->file_emp_id != '0'){
						$select_who_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->file_emp_id'";
						$result_who = mysqli_query($connect,$select_who_qry);
						$row_who = mysqli_fetch_object($result_who);

						$who = " of <b>" .  $row_who->Firstname . " " . $row_who->Lastname ." </b>";
						
					}

	
				}

				echo "<tr>";
					echo "<td>" . $row->module . "</td>";
					echo "<td>" .$to. $involve.  $row->task_description . $from. $who . "</td>";
					echo "<td>" . $date . " " . $time . "</td>";
				echo "</tr>";
			}
		}
	}

}

?>