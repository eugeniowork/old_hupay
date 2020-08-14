<?php
session_start();
include "../class/connect.php";
include "../class/minimum_wage_class.php";
include "../class/date.php";

// if ok
if (isset($_POST["basicWage"]) && isset($_POST["cola"]) && isset($_POST["effectiveDate"])){

	$min_wage_class = new MinimumWage;
	$date_class = new date;

	$basicWage = $_POST["basicWage"]; 
	$cola = $_POST["cola"];
	
	$currentDate = $date_class->getDate();

	$effective_date_month = substr($_POST["effectiveDate"],0,2);
	$effective_date_day = substr(substr($_POST["effectiveDate"], -7), 0,2);
	$effective_date_year = substr($_POST["effectiveDate"], -4);

	
	// if edited in the inspect element and not fill up required fields
	if ($basicWage == "" || $cola == "" || $_POST["effectiveDate"] == ""){
		$_SESSION["add_error_min_wage"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's is an error during saving</center>";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["effectiveDate"])) {
    	$_SESSION["add_error_min_wage"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Effective Date</b> not match to the current format mm/dd/yyyy</center>";
	}

	// for validating leap year
	else if ($effective_date_year % 4 == 0 && $effective_date_month == 2 && $effective_date_day >= 30){
		$_SESSION["add_error_min_wage"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Effective Date</b> date</center>";
	}

	// for validating leap year also
	else if ($effective_date_year % 4 != 0 && $effective_date_month == 2 && $effective_date_day >= 29){
		$_SESSION["add_error_min_wage"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Effective Date</b> date</center>";
	}

	// mga month na may 31
	else if (($effective_date_month == 4 || $effective_date_month == 6 || $effective_date_month == 9 || $effective_date_month == 11)
			&& $effective_date_day  >= 31){
		$_SESSION["add_error_min_wage"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Effective Date</b> date</center>";
	}


	// kapag may kaparehas
	// if has same info
	else if ($min_wage_class->existSameInfo($date_class->dateDefaultDb($_POST["effectiveDate"]),$basicWage,$cola) == 1){
		$_SESSION["add_error_min_wage"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The minimum wage information is already exist.</center>";
	}


	// if success
	else {
		$effectiveDate = $date_class->dateDefaultDb($_POST["effectiveDate"]);
		$min_wage_class->insertMinimumWage($basicWage,$cola,$effectiveDate,$currentDate);
		$_SESSION["add_success_min_wage"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The new minimum wage information is successfully added</center>";
	}




	header("Location:../minimum_wage.php");

}
// if edited in the inspect element
else {
	header("Location:../MainForm.php");
}

?>