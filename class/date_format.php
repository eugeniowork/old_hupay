<?php

class DateFormat{
	public function setDateFormat($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'Y-m-d');
		return $date_format;
	}
}

?>