<?php
session_start();
include "class/connect.php";
include "class/Payroll.php";



if (isset($_SESSION["print_payroll_reports_approve_id"]) && isset($_POST["reports_type"])) {
	 $approve_payroll_id = $_SESSION["print_payroll_reports_approve_id"];

    $payroll_class = new Payroll;

    $reports_type = $_POST["reports_type"];

   // echo $reports_type;

    // if edited in the inspect element
    if ($reports_type != "Excel" && $reports_type != "PDF"){
      header("Location:../payroll_reports.php");
    }
    else {
        $row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);

        if ($reports_type == "Excel"){
           $payroll_class->payrollReportsExcel($row->CutOffPeriod);
        } 

        else {
            
            // getInfoPayrollAppoval
            
           //  echo $approve_payroll_id;
            // echo ;
            $payroll_class->payrollReportsPDF($row->CutOffPeriod);
          //echo $row->CutOffPeriod;
          //  echo $row->CutOffPeriod;
          
        }
    }

    
}

else {
	header("Location:MainForm.php");
}

?>