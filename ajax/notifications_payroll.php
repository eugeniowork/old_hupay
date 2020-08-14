<?php
session_start();
include "../class/connect.php";
include "../class/payroll_notif_class.php";


$payroll_notif_class = new PayrollNotif;
$emp_id = $_SESSION["id"];
$payroll_notif_class->readAllNotif($emp_id);


?>