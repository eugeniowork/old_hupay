<?php
class GovernmentNoFormat{
	public function sssNoFormat($sssNo){
		$a = substr($sssNo,0,2);
		$b = substr(substr($sssNo, 2),0,7);
		$c = substr($sssNo, 9);
		return $a . "-" . $b ."-" . $c;


	}

	public function tinNoFormat($tinNo){
		$a = substr($tinNo,0,3);
		$b = substr(substr($tinNo, 3),0,3);
		$c = substr($tinNo, 6);
		return $a . "-" . $b ."-" . $c;


	}

	public function pagibigNoFormat($pagibigNo){
		$a = substr($pagibigNo,0,4);
		$b = substr(substr($pagibigNo, 4),0,4);
		$c = substr($pagibigNo, 8);
		return $a . "-" . $b ."-" . $c;


	}

	public function philhealthNoFormat($philhealthNo){
		$a = substr($philhealthNo,0,2);
		$b = substr(substr($philhealthNo, 2),0,9);
		$c = substr($philhealthNo, 11);
		return $a . "-" . $b ."-" . $c;


	}
}

?>