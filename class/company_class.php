<?php
class Company extends Connect_db {

	// for getting all company to table
	public function getAllCompanyToDropdown(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_company ORDER BY dateCreated ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){
				echo "<option value='".$row->company_id."'>".$row->company."</option>";
			}
		}
	}

	// for checking exist company id
	public function checkExistCompanyId($company_id){
		$connect = $this->connect();

		$company_id = mysqli_real_escape_string($connect,$company_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_company WHERE company_id = '$company_id'"));

		return $num_rows;
	}

	// for getting info by company id
	public function getInfoByCompanyId($company_id){
		$connect = $this->connect();

		$company_id = mysqli_real_escape_string($connect,$company_id);

		$select_qry = "SELECT * FROM tb_company WHERE company_id = '$company_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// selecting company to dropdown
	public function selectCompanyToDropdown($company_id){
		$connect = $this->connect();

		$company_id = mysqli_real_escape_string($connect,$company_id);

		$select_qry = "SELECT * FROM tb_company ORDER BY dateCreated ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$selected = "";
				if ($row->company_id == $company_id){
					$selected = "selected='selected'";
				}

				echo "<option ".$selected." value='".$row->company_id."'>".$row->company."</option>";
			}
		}
	}

	
}
?>