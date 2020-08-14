<?php

class files201_class extends Connect_db{

	// for getting 201 files count of employee to database
	public function getCountEmp201Files($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_201_files_count WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for inserting to 201_files_count
	public function insertFiles201Count($emp_id,$count){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$count = mysqli_real_escape_string($connect,$count);

		$insert_qry = "INSERT INTO tb_emp_201_files_count (files201_count_id,emp_id,count) VALUES ('','$emp_id','$count')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// for updating 201 files count
	public function updateFiles201Count($emp_id,$count){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$count = mysqli_real_escape_string($connect,$count);

		$update_qry = "UPDATE tb_emp_201_files_count SET count ='$count' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);

	}

	// for getting the 201 files count , count value
	public function getCount201FilesByEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$select_qry = "SELECT * FROM tb_emp_201_files_count WHERE emp_id = '$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row;


	}


	// for inserting 201 files images
	public function insert201FilesImage($emp_id,$name,$file_name,$file_path,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$name = mysqli_real_escape_string($connect,$name);
		$file_name = mysqli_real_escape_string($connect,$file_name);
		$file_path = mysqli_real_escape_string($connect,$file_path);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_emp_201_files (files201_id,emp_id,name,file_name,file_path,dateCreated) VALUES ('','$emp_id','$name','$file_name','$file_path','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}


	// for checking to 201 files images
	public function checkExist201FilesImages($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_201_files WHERE emp_id = '$emp_id'"));
		return $num_rows;
	}


	// for getting all images to carousel
	public function getAll201FilesToCarouselIndicators($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$counter = 0;
		$images_count = $this->checkExist201FilesImages($emp_id); // for getting the count

		do {
			// do here ..

			// for giving the active to 0 count
			if ($counter == 0){
				echo '<li data-target="#myCarousel" data-slide-to="'.$counter.'" class="active"></li>';
			}
			else {
				echo '<li data-target="#myCarousel" data-slide-to="'.$counter.'"></li>';
			}


			$counter++;
		}while($counter < $images_count);

	}


	// for getting to carousel in
	public function getAll201FilesToCarouselInner($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$counter = 0;

		$select_qry = "SELECT * FROM tb_emp_201_files WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				// for giving the item active to first images
				if ($counter ==0){
					echo '<div class="item active" id="'.$row->files201_id.'">';
						 echo '<div class="" style="border-color: #23a127 ;background-color: #196f3d ;border-radius:2px;padding:3px;color:#fff;">';
						 	echo $row->name;
						 echo '</div>';

						echo '<img src="'.$row->file_path.'" alt="201 Files Image" style="width:100%;border:1px solid  #BDBDBD;height:400px;cursor:pointer" title="View Full Image" id="view_full_files201_image">';
					echo '</div>';
				}

				else {
					echo '<div class="item" id="'.$row->files201_id.'">';
						echo '<div class="" style="border-color: #23a127 ;background-color: #196f3d ;border-radius:2px;padding:3px;color:#fff;">';
						 	echo $row->name;
						 echo '</div>';
						echo '<img src="'.$row->file_path.'" alt="201 Files Image" style="width:100%;height:400px;1px solid  #BDBDBD;cursor:pointer" title="View Full Image" id="view_full_files201_image">';
					echo '</div>';
				}


				$counter++;
			}
		}
	}


	// for getting the first images name
	public function getFirstImagesName($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_emp_201_files WHERE emp_id = '$emp_id' LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$fileName = $row->file_name;

		return $fileName;


	}


	// for checking if the 201 files image is existing
	public function checkExistFiles201Id($files201_id){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_201_files WHERE files201_id = '$files201_id'"));
		return $num_rows;
	}


	// for getting images files info by id
	public function getInfoById($files201_id){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);

		$select_qry = "SELECT * FROM tb_emp_201_files WHERE files201_id = '$files201_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
			
		
	}


	// for checking if no changes was made
	public function noChangesUpdateDescription($files201_id,$description){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);
		$description = mysqli_real_escape_string($connect,$description);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_emp_201_files WHERE files201_id = '$files201_id' AND name = '$description'"));
		return $num_rows;

	}


	// for updating 201 files description
	public function updateFiles201Description($files201_id,$description){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);
		$description = mysqli_real_escape_string($connect,$description);

		$update_qry = "UPDATE tb_emp_201_files SET name = '$description' WHERE files201_id = '$files201_id'";
		$sql = mysqli_query($connect,$update_qry);

	}



	// for updating only file path in files201
	public function updateFiles201FilePath($files201_id,$filePath){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);
		$filePath = mysqli_real_escape_string($connect,$filePath);

		$update_qry = "UPDATE tb_emp_201_files SET file_path = '$filePath' WHERE files201_id = '$files201_id'";
		$sql = mysqli_query($connect,$update_qry);
	}
	

	// for deleting 201 files images
	public function deleteFiles201Images($files201_id){
		$connect = $this->connect();

		$files201_id = mysqli_real_escape_string($connect,$files201_id);

		$delete_qry = "DELETE FROM tb_emp_201_files WHERE files201_id = '$files201_id'";
		$sql = mysqli_query($connect,$delete_qry);

	}







}

?>