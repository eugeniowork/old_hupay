<?php

class EmployeeLoan extends Connect_db{

	public function insertFileLoan($emp_id,$ref_no,$amount,$type,$program,$purpose){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$ref_no = mysqli_real_escape_string($connect,$ref_no);
		$amount = mysqli_real_escape_string($connect,$amount);
		$type = mysqli_real_escape_string($connect,$type);
		$program = mysqli_real_escape_string($connect,$program);
		$purpose = mysqli_real_escape_string($connect,$purpose);

		$insert_qry = "INSERT INTO tb_emp_file_loan (emp_id,ref_no,amount,type,program,purpose) VALUES ('$emp_id','$ref_no','$amount','$type','$program','$purpose')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// for getting the last id in database
	public function lastID(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_emp_file_loan ORDER BY file_loan_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->file_loan_id;
		return $last_id;
	}


	// function check if the bio id is exist
	public function checkExistFile(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_file_loan"));
		return $num_rows;
	}


	public function getFileLoanList($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		//cho $emp_id;

		$select_qry = "SELECT * FROM tb_emp_file_loan WHERE emp_id = '$emp_id' ORDER BY date_created DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){



				$loan_type = "Salary Loan";
				if ($row->type == 2){
					$loan_type = "SIMKIMBAN";
				}

				else if ($row->type == 3){
					$loan_type = "Employee Benifit Program Loan";

					$program = "Service Rewards";
					if ($row->program == 2){
						$program = "Tulong Pangkabuhayan Program";
					}

					else if ($row->program == 3){
						$program = "Education Assistance Program";
					}

					else if ($row->program == 4){
						$program = "Housing Renovation Program";
					}

					else if ($row->program == 5){
						$program = "Emergency and Medical Assistance Program";
					}

					$loan_type .= "<br/>" ."<span style='color:#b4b5b6'>" .$program . "</span>";

				}

				$status = "Pending";
				if ($row->status == 1){
					$status = "Approve";
				}

				else if ($row->status == 2){
					$status = "Disapprove";
				}

				else if ($row->status == 3){
					$status = "Cancel";
				}

				else if ($row->status == 4){
					$status = "On Process";
				}	

				echo "<tr id='".$row->file_loan_id."'>";
					echo "<td>".$row->ref_no."</td>";
					echo "<td>".number_format($row->amount,2)."</td>";
					echo "<td>".$row->purpose."</td>";
					echo "<td>".$loan_type."</td>";
					echo "<td>".$status."</td>";
					echo "<td>";	
						if ($row->status == 0){
							echo "<button id='update_file_loan' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-edit'></span>&nbsp;Update</button>";
							echo "&nbsp;";
							echo "<button id='cancel_file_loan' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span>&nbsp;Cancel</button>";
						}

						else {
							echo "No Action";
						}
					echo "</td>";
				echo "</tr>";
			}
		}

	}


	// for specific person log in
	public function getFileLoanInfoById($file_loan_id){
		$connect = $this->connect();
		
		$file_loan_id = mysqli_real_escape_string($connect,$file_loan_id);

		$select_qry = "SELECT * FROM tb_emp_file_loan WHERE file_loan_id='$file_loan_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}


	// 
	public function updateFileLoanById($file_loan_id,$amount,$loan_type,$program,$purpose){
		$connect = $this->connect();

		$file_loan_id = mysqli_real_escape_string($connect,$file_loan_id);
		$amount = mysqli_real_escape_string($connect,$amount);
		$loan_type = mysqli_real_escape_string($connect,$loan_type);
		$program = mysqli_real_escape_string($connect,$program);
		$purpose = mysqli_real_escape_string($connect,$purpose);

		$update_qry = "UPDATE tb_emp_file_loan SET amount = '$amount', type = '$loan_type' , program = '$program', purpose = '$purpose' WHERE file_loan_id = '$file_loan_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function cancelFileLoanById($file_loan_id){
		$connect = $this->connect();

		$file_loan_id = mysqli_real_escape_string($connect,$file_loan_id);

		$update_qry = "UPDATE tb_emp_file_loan SET status = '3' WHERE file_loan_id = '$file_loan_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for specific person log in
	public function getEmpInfoByRow($id){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_employee_info WHERE emp_id='$id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;
	}



	public function getAllFileLoanList(){
		$connect = $this->connect();


		//cho $emp_id;

		$select_qry = "SELECT * FROM tb_emp_file_loan WHERE status = '0' ORDER BY date_created DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){



				$loan_type = "Salary Loan";
				if ($row->type == 2){
					$loan_type = "SIMKIMBAN";
				}

				else if ($row->type == 3){
					$loan_type = "Employee Benifit Program Loan";


					$program = "Service Rewards";
					if ($row->program == 2){
						$program = "Tulong Pangkabuhayan Program";
					}

					else if ($row->program == 3){
						$program = "Education Assistance Program";
					}

					else if ($row->program == 4){
						$program = "Housing Renovation Program";
					}

					else if ($row->program == 5){
						$program = "Emergency and Medical Assistance Program";
					}

					$loan_type .= "<br/>" ."<span style='color:#b4b5b6'>" .$program . "</span>";
				}

				$status = "Pending";
				if ($row->status == 1){
					$status = "Approve";
				}

				else if ($row->status == 2){
					$status = "Disapprove";
				}

				else if ($row->status == 3){
					$status = "Cancel";
				}	

				else if ($row->status == 4){
					$status = "On Process";
				}	


				$row_emp = $this->getEmpInfoByRow($row->emp_id);

				echo "<tr id='".$row->file_loan_id."'>";
					echo "<td>".$row_emp->Firstname. " " . $row_emp->Lastname . "</td>";
					echo "<td>".$row->ref_no."</td>";
					echo "<td>".number_format($row->amount,2)."</td>";
					echo "<td>".$row->purpose."</td>";
					echo "<td>".$loan_type."</td>";
					echo "<td>";	
						echo "<button id='create_loan_schedule' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-edit'></span>&nbsp;Create Schedule</button>";
							echo "&nbsp;";
							echo "<button id='disapprove_file_loan' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span>&nbsp;Disapprove</button>";
					echo "</td>";
				echo "</tr>";
			}
		}

	}


	public function disapproveFileLoanById($file_loan_id){
		$connect = $this->connect();

		$file_loan_id = mysqli_real_escape_string($connect,$file_loan_id);

		$update_qry = "UPDATE tb_emp_file_loan SET status = '2' WHERE file_loan_id = '$file_loan_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	public function getPendingFileLoanCount(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_file_loan WHERE status = '0'"));
		return $num_rows;
	}


	public function onProcesFileLoan($file_loan_id){
		$connect = $this->connect();

		$file_loan_id = mysqli_real_escape_string($connect,$file_loan_id);

		$update_qry = "UPDATE tb_emp_file_loan SET status = '4' WHERE file_loan_id = '$file_loan_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	public function getProcessFileLoanCount(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_file_loan WHERE status = '4'"));
		return $num_rows;
	}


	public function approveFileLoan($ref_no){
		$connect = $this->connect();

		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_emp_file_loan SET status = '1' WHERE ref_no = '$ref_no'";
		$sql = mysqli_query($connect,$update_qry);

	}


	public function disApproveFileLoan($ref_no){
		$connect = $this->connect();

		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_emp_file_loan SET status = '2' WHERE ref_no = '$ref_no'";
		$sql = mysqli_query($connect,$update_qry);

	}

}
?>
