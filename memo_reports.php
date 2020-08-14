<?php
session_start();
include "class/connect.php";
include "class/memorandum_class.php";
include "class/emp_information.php";
include "class/position_class.php";
include "class/date.php";



if ($_SESSION["role"] != 1 &&  $_SESSION["role"] != 2){
	header("Location:MainForm.php");
}


if (isset($_GET["memo_id"])){
	$memo_id = $_GET["memo_id"];
	
	require ("reports/fpdf.php");

	$pdf = new PDF_MC_Table();
	$pdf->SetMargins("20","35"); // left top

	$pdf->AddPage();

	// for logo
    $pdf->Image("http://philip-pc:91/img/logo/lloyds_report_logo.jpeg",73,10,65,25);// margin-left,margin-top,width,height
    $pdf->SetFont("helvetica","B","7.5");
    $pdf->Cell(0,3,"1255 Cardona St. Rizal Village, Makati City",0,1,"C");
    $pdf->Cell(0,3,"897 66 44 - 46 / 897 62 76 - 77",0,1,"C");


    $memo_class = new Memorandum;
    $row = $memo_class->getMemoInfoById($memo_id);

    $emp_info_class = new EmployeeInformation;
    $row_memo_from = $emp_info_class->getEmpInfoByRow($row->memoFrom);

    $position_class = new Position;
    $row_position = $position_class->getPositionById($row_memo_from->position_id);

    $date_class = new date;

    $pdf->Cell(0,8,"",0,1); // FOR SPACING

    $pdf->SetFont("helvetica","BU","13");
	//$pdf->SetTextColor(0,0,255);
	$pdf->Cell(0,5,"MEMORANDUM",0,1,"L");

	$pdf->Cell(0,8,"",0,1); // FOR SPACING


    $pdf->SetFont("helvetica","","11");
	//$pdf->SetTextColor(0,0,255);
	$pdf->Cell(35,5,"TO:",0,0,"L");
	$pdf->SetFont("helvetica","","11");
	$pdf->Cell(135,5,$row->recipient,0,1);

	$pdf->Cell(0,4,"",0,1); // FOR SPACING


	$pdf->SetFont("helvetica","","11");
	//$pdf->SetTextColor(0,0,255);
	$pdf->Cell(35,5,"FROM:",0,0,"L");
	$pdf->SetFont("helvetica","","11");
	$pdf->Cell(135,5,$row_position->Position,0,1);

	$pdf->Cell(0,4,"",0,1); // FOR SPACING


	$pdf->SetFont("helvetica","","11");
	//$pdf->SetTextColor(0,0,255);
	$pdf->Cell(35,5,"ISSUE DATE:",0,0,"L");
	$pdf->SetFont("helvetica","","11");
	$pdf->Cell(135,5,$date_class->dateFormat($row->DateCreated),0,1);



	$pdf->Cell(0,4,"",0,1); // FOR SPACING

	$pdf->SetFont("helvetica","","11");
	//$pdf->SetTextColor(0,0,255);
	$pdf->Cell(35,8,"SUBJECT:","B",0,"L");
	$pdf->SetFont("helvetica","B","11");
	$pdf->Cell(135,8,$row->Subject,"B",1);

	$pdf->Cell(0,.1,"","B",1); // FOR BORDER
	//$pdf->Cell(0,.1,"","B",1); // FOR BORDER


	$pdf->Cell(0,4,"",0,1); // FOR SPACING

	$pdf->SetFont("Arial","","11");
	//$pdf->Multicell(0,0,255);

	//$content = str_ireplace("<br>", "\r\n", $row->Content);
	//$content = str_ireplace("&nbsp;", " ", $row->Content);
	//$content = str_ireplace("<li>", "<br>" .chr(127), $row->Content);
	//$content = str_ireplace("<ul>", "<br>", $row->Content);
	//$pdf->WriteHTML($content);

	$pdf->MultiCell(0,5,$row->Content,0,false);
//	$pdf->SetFont("helvetica","","11");
//	$pdf->Cell(155,5,$row->Subject,1,1);



	$pdf->output();
}

else {
	header("Location:MainForm.php");
}




?>