<?php

class Universal extends Connect_db{


	public function cannotBeDeleted($tb_name_array,$col_name,$unique_id){

		$connect = $this->connect();

		//$tb_name_array = mysqli_real_escape_string($connect,$tb_name_array);
		$col_name = mysqli_real_escape_string($connect,$col_name);
		$unique_id = mysqli_real_escape_string($connect,$unique_id);

		$count = count($tb_name_array);
		$counter = 0;

		$can_delete = 0;
		do {	

			$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM $tb_name_array[$counter] WHERE $col_name = '$unique_id'"));

			if ($num_rows > 0){
				$can_delete = 1;
			}

			$counter++;
		}while($count > $counter);

		return $can_delete;


	}


}


?>