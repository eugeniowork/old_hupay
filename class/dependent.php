<?php

class Dependent extends Connect_db{


	// for view emp profile info
	public function dependentInfo($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_dependent WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)) {
			while($row = mysqli_fetch_object($result)){

				$date_create = date_create($row->Birthdate);
				$date_format = date_format($date_create, 'F d, Y');


				// for Fullname
				echo '<div class="col-xs-12 col-sm-6 content-view-emp">';
	 				echo '<b><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Fullname:</b> ' . $row->Fullname;;
				echo '</div>';

				// for Birthdate
				echo '<div class="col-xs-12 col-sm-6 content-view-emp">';
	 				echo '<b><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Birthdate:</b> ';
	 				if ($row->Birthdate != "0000-00-00") {
	 					echo $date_format;
	 				}
				echo '</div>';
			}
		}


	}




	// for list of dependant
	public function dependentList($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_dependent WHERE emp_id  = '$emp_id'";
		$sql = mysqli_query($connect,$select_qry);
	}

	// check if have exist dependant
	public function existDependent($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}

	// for adding dependent
	public function addDependent($emp_id,$fullname,$birthdate,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$fullname = mysqli_real_escape_string($connect,$fullname);
		$birthdate = mysqli_real_escape_string($connect,$birthdate);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);


		$insert_qry = "INSERT INTO tb_dependent (dependent_id,emp_id,Fullname,birthdate,DateCreated) VALUES ('','$emp_id','$fullname','$birthdate','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// for deleting dependent
	public function deleteDependent($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$delete_qry = "DELETE FROM tb_dependent WHERE emp_id='$emp_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}

	// for same dependent info of name values
	public function sameDependentNameValues($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$name_values = ""; // for name values
		$select_qry = "SELECT * FROM tb_dependent WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($name_values == "") {
					$name_values = $row ->Fullname;
				}
				else {
					$name_values = $name_values . "#" . $row ->Fullname;
				}
			}
		}
		return $name_values;
	}

	// for same dependent info of birthdate values
	public function sameDependentBirthdateValues($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$birthdate_values = ""; // for name values
		$select_qry = "SELECT * FROM tb_dependent WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($row->Birthdate == "0000-00-00"){
					$date_format = "";
				}
				else {
					$date_create = date_create($row->Birthdate);
					$date_format = date_format($date_create, 'm/d/Y');
				}	
				
				if ($birthdate_values == "") {
					$birthdate_values = $date_format;
				}
				else {
					$birthdate_values = $birthdate_values . "#" . $date_format;
				}
			}
		}
		return $birthdate_values;
	}

	// for getting information by emp_id
	public function getAllDependentInfo($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$emp_id'"));

		$counter = 0;

		$select_qry = "SELECT * FROM tb_dependent WHERE emp_id='$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				if ($row->Birthdate == "0000-00-00"){
					$date_format = "";
				}
				else {
					$date_create = date_create($row->Birthdate);
					$date_format = date_format($date_create, 'm/d/Y');
				}

				$counter++;
				echo '<div id="dependent_'.$counter.'">';
					echo  '<div class="col-sm-6">';
						echo '<label class="control-label">Full Name &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="'.$row->Fullname.'" class="form-control" id="txt_only" name="full_name_'.$counter.'" placeholder="Enter Full Name"/ required="required">';
					echo '</div>'; 
					echo '<div class="col-sm-5">';
						echo '<label class="control-label">Birthdate</label><input type="text" value="'.$date_format.'" class="form-control" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" name="birthdate_'.$counter.'" placeholder="Enter Birthdate"/>';
					echo '</div>';
					echo '<div class="col-sm-1 remove-without-dependent">';
						echo '<a class="btn btn-danger btn-sm" title="Remove" id="remove_dependent_without_btn">&times;</a>';
					echo '</div>';
				echo '</div>';

				
			}
		}
	} // end of function


	// for profile information
	public function getDependentInfoToProfile($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);



		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dependent WHERE emp_id = '$emp_id'"));

		if ($num_rows != 0) {

			$select_qry = "SELECT * FROM tb_dependent WHERE emp_id = '$emp_id'";
			if ($result = mysqli_query($connect,$select_qry)) {
				while($row = mysqli_fetch_object($result)){



					if ($row->Birthdate != "0000-00-00") {
						$date_create = date_create($row->Birthdate);
						$date_format = date_format($date_create, 'F d, Y');
					}

					else {
						$date_format = "No Info";
					}





					echo '<div class="form-group">
			 	 			<div class="col-sm-3 col-sm-offset-1">
					 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-user" style="color: #196f3d "></span>&nbsp;<b>Full Name:</b></label>
					 	 		<div style="margin-left:15px;"><b>'.$row->Fullname.'</b></div>
				 	 		</div>
				 	 		<div class="col-sm-3">
					 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-calendar" style="color: #196f3d "></span>&nbsp;<b>Birthdate:</b></label>
				 	 			<div style="margin-left:15px;"><b>'.$date_format.'</b></div>
				 	 		</div>
			 	 		</div>';



				}
			}
		} // end of if

		// end of else
		else {
			echo '<div class="form-group">
					<div class="col-sm-12">
						<b>There is no declared dependent</b>
					</div>
				  </div>';
		}



	}
}


?>