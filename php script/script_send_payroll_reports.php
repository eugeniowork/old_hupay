<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";
include "../class/payroll_notif_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

	if (isset($_POST["approve_payroll_id"])){
		$approve_payroll_id = $_POST["approve_payroll_id"];

		$payroll_class = new Payroll;


		// if did not exist
		if ($payroll_class->existApprovePayrollId($approve_payroll_id) == 0){
			echo "Error";
		}

		// if exist but already approve
		else if ($payroll_class->checkAlreadyApprove($approve_payroll_id) == 1){
			echo "Error";
		}

		// if success
		else {
			$payroll_class->sendPayrollReports($approve_payroll_id);


			// for inserging payroll notif
			$payroll_notif_class = new PayrollNotif;
			$emp_info_class = new EmployeeInformation;
			$date_class = new date;

			$payroll_admin_id = $_SESSION["id"];

			$emp_id_admin = $emp_info_class->getEmpIdRoleAdmin();
			$emp_id = explode("#",$emp_id_admin);
			$count = $emp_info_class->getAdminCount();
			$payroll_id = 0;
			$notif_type = "Already Sent";

			$row = $payroll_class->getInfoPayrollAppoval($approve_payroll_id);
			$cutOffPeriod = $row->CutOffPeriod;
			$readStatus = 0;

			$dateCreated = $date_class->getDateTime();

			$counter = 0;
			do {
				//echo $emp_id[$counter];
				$payroll_notif_class->insertPayrollNotif($payroll_admin_id,$emp_id[$counter],$payroll_id,$approve_payroll_id,'0',$notif_type,$cutOffPeriod,$readStatus,$dateCreated);
				$counter++;
			}while($counter < $count);

			// for audit trail
			$audit_trail_class = new AuditTrail;
			$module = "Send Payroll Reports";
			$task_description = "Send Payroll Reports, " . $cutOffPeriod;
			$dateTime = $date_class->getDateTime();
			$audit_trail_class->insertAuditTrail('0','0',$_SESSION["id"],$module,$task_description,$dateTime);


			echo "Success";
		}

	}

	else {
		echo "Error";
	}

?>