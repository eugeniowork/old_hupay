<?php

class date{
	public function getDate(){
		// FOR CURRENT DATE AND TIME PURPOSE
		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('16 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		//$sure_date_now = date_create($current_date_time);
		//$sure_current_date_now = date_format($sure_date_now,'Y-m-d');

		return $current_date_time;

	}

	public function getDateTime(){
		// FOR CURRENT DATE AND TIME PURPOSE
		date_default_timezone_set("Asia/Manila");
		//$date = date_create("1/1/1990");

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('16 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d H:i:s');

		//$sure_date_now = date_create($current_date_time);
		//$sure_current_date_now = date_format($sure_date_now,'Y-m-d');

		return $current_date_time;

	}

	// sample January 1, 1990
	public function dateFormat($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'F d, Y');
		return $date_format;
	}

	public function dateFormatMonthYear($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'F Y');
		return $date_format;
	}


	public function dateDefault($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'm/d/Y');
		return $date_format;
	}


	public function dateDefaultDb($date){
		$date_create = date_create($date);
		$date_format = date_format($date_create, 'Y-m-d');
		return $date_format;
	}


	


	

}

?>