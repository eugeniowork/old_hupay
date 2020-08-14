<?php
session_start();
include "../class/connect.php";
include "../class/minimum_wage_class.php";


$min_wage_class = new MinimumWage;
$min_wage_class->deleteLatestMinWage();
$_SESSION["delete_success_min_wage"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The latest minimum wage is successfully deleted.</center>";
header("Location:../minimum_wage.php");



?>