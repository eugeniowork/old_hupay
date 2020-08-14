<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";


$cashbond_class = new Cashbond;
$emp_id = $_SESSION["id"];
$cashbond_class->readAllNotif($emp_id);


?>