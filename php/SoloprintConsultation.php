<?php
//call the FPDF library
require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
require('../fpdf/mem_image.php');

date_default_timezone_set('Asia/Manila');



$pdf = new FPDF('P','mm','Legal');

$pdf = new PDF_MemImage();
//add new page
$pdf->AddPage('P', [215.9, 330.2]);

$font = 'Arial';
$fontSize = '11';
$pageHeight = '330.2';
$showXtraInfo = false;
$spacing = 5;

$pdf->SetTitle('Print Consultation Record');

//set font to arial, bold, 14pt
$pdf->SetFont($font,'B',$fontSize);

$column_width = 50;

$string = "asdsadas adsad ad sa showTickBox as asd asd asd as date_default_timezone_set sa showTickBoxdasd sa date_default_timezone_sets adasdate_default_timezone_sets ads da sda sdaas dsda AgeStartedAsDrinkersad asd asdsad sdasa das date_default_timezone_sets asd date_default_timezone_sets";

$total_string_width = $pdf->GetStringWidth($string);
$number_of_lines = $total_string_width / ($column_width - 1);
$number_of_lines = ceil( $number_of_lines );
$pdf->Cell(50,5,"1 lines: " .$number_of_lines,0,1);

$array = explode("\n", $string);


$total_lines = 0;
foreach ($array as $arr) {
	$pdf->MultiCell(50,5,$arr,1);
	$total_string_width = $pdf->GetStringWidth($arr);
	$number_of_lines = $total_string_width / ($column_width - 1);
	$number_of_lines = ceil( $number_of_lines );
	$pdf->Cell(50,5,"lines: " .$number_of_lines,0,1);
	$total_lines += $number_of_lines;
}
$pdf->Ln(10);

//$pdf->MultiCell(50,5,$string,1);
$pdf->Cell(50,5,"Total lines: " .$total_lines,1);


//output the result
$pdf->Output('123123123' .'.pdf','I');



?>