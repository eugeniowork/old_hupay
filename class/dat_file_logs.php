<?php
// this class is for datfiles logs
class DatFilesLog extends Connect_db{
	public function numrowsDatFiles(){
		$connect = $this->connect();
		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_dat_files_log"));
		return $num_rows;
	}


	// for insert
	public function insertDatFiles($dat_files_name,$date_uploaded){
		$connect = $this->connect();
		$dat_files_name = mysqli_real_escape_string($connect,$dat_files_name);
		$date_uploaded = mysqli_real_escape_string($connect,$date_uploaded);
		$insert_qry = "INSERT INTO tb_dat_files_log (dat_files_id,DatFilesName,DateUploaded) VALUES('','$dat_files_name','$date_uploaded')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// for get the last id
	public function lastIdDatFiles(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_dat_files_log ORDER BY dat_files_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->dat_files_id;
		return $last_id;
	}
}

?>