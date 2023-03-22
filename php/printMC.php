<?php
//call the FPDF library
require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
require('../fpdf/mem_image.php');

date_default_timezone_set('Asia/Manila');



$idnumber = $_GET['id'];
$Message = '';


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        FetchUser($idnumber);
      }
      else
      {
        $Message = 'Failed to fetch information!';
        $Error = "1";
      }
    }  
    else
    {
      $Message = 'The database is offline!';
      $Error = "1";    
    } 

	    $XMLData = '';	
		$XMLData .= ' <output ';
		$XMLData .= ' Message = ' . '"'.$Message.'"'; 
		$XMLData .= ' />';

		//Generate XML output
		header('Content-Type: text/xml');
		//Generate XML header
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		echo '<Document>';    	
		echo $XMLData;
		echo '</Document>';


  function FetchUser($ID){
    $sql = '';
	$check1 = '';
	$check2 = '';
	$check3 = '';
	$check4 = '';
	$showXtraInfo = '';
	$pf = '';
	$pu = '';

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $type, $idnumber;

	$sql = "SELECT * FROM medicalcertificate INNER JOIN personalmedicalrecord WHERE medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber AND student_id = $ID";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        
            $id_num = stripslashes($Row['id_num']);;
            $mc_doc_code = stripslashes($Row['mc_doc_code']);;
            $mc_rev_num = stripslashes($Row['mc_rev_num']);;
            $mc_effectivity = stripslashes($Row['mc_effectivity']);;
			$mc_no_label = stripslashes($Row['mc_no_label']);;
            $student_id = stripslashes($Row['student_id']);;
            $first_name = stripslashes($Row['Firstname']);;
            $middle_name = stripslashes($Row['Middlename']);;
            $last_name = stripslashes($Row['Lastname']);;
            $extension = stripslashes($Row['Extension']);;
            $consult_date = stripslashes($Row['consult_date']);;
            $purpose = stripslashes($Row['purpose']);;
            $is_pf = stripslashes($Row['is_pf']);;
            $reason = stripslashes($Row['reason']);;
            $is_excused = stripslashes($Row['is_excused']);;
            $created_at = stripslashes($Row['created_at']);;
			$age_sex = stripslashes($Row['Age']) . " / " . stripslashes($Row['Sex']);;
            $degree = stripslashes($Row['Course']) ;;

            if(!empty($degree)){
           		$degree = explode(')', (explode('(', $degree)[1]))[0];
            }else{
            	$degree = ucwords(stripslashes($Row['StudentCategory']));;;
            }

            $year = stripslashes($Row['Year']);;

			$fullname = $last_name . ", " . $first_name . " " . substr($middle_name, 0, 1) . ". " .$extension;
            

	            $purpose_others = htmlentities($Row['purpose_others']);
	            $pf_remarks = htmlentities($Row['pf_remarks']);
	            $diagnosis = htmlentities($Row['diagnosis']);
	            $general_remarks = htmlentities($Row['general_remarks']);
	            $is_excused_others = htmlentities($Row['is_excused_others']);
            

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
			//create pdf object
			$pdf = new FPDF('P','mm','Legal');

			$pdf = new PDF_MemImage();
			//add new page
			$pdf->AddPage('P', [215.9, 330.2]);

			//Set Title
			$pdf->SetTitle('Print Medical Certificate');

			$font = 'Arial';
			$fontSize = '11';
			$pageHeight = '330.2';


			//set font to arial, bold, 14pt
			$pdf->SetFont($font,'B',$fontSize);

			//Cell(width , height , text , border , end line , [align] )
			$pdf->Image('../images/BSULogo.png',22,3,20,20);
			$pdf->Cell(30 ,20,'',0,0);
			$pdf->SetFont($font,'B',$fontSize+4);
			$pdf->MultiCells(70 ,5,'MEDICAL CERTIFICATE',0,0,'C');
			$pdf->Cell(10 ,20,'',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(23, 5, 'Department Code:', 1,0, 'L'); 
			$pdf->Cell(30, 10, strtoupper($mc_doc_code), 1, 0, 'C'); 
			$pdf->MultiCells(20, 5, 'Revision Number', 1);
			$pdf->Cell(12 ,10,$mc_rev_num,1,0); 

			$pdf->Ln(10);

			$pdf->Cell(23 ,5,'',0,0);
			$pdf->Cell(87 ,5,'',0,0);
			$pdf->Cell(23, 5, 'Effectivity', 1); 
			$pdf->Cell(30, 5, ucwords($mc_effectivity), 1,0,'C'); 
			$pdf->Cell(32, 5, strtoupper($mc_no_label), 1,0,'C');
			
			$pdf->Ln(12);

			$pdf->Cell(12 ,5,'',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(37 ,5,'This is to certify that',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(75 ,5, ucwords($fullname) ,'B',0, 'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(3 ,5,', ',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(25, 5, strtoupper($age_sex), 'B',0, 'C');
			$pdf->Cell(3 ,5,', ',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(42 ,5, ucwords($degree) .' - ' .ucwords($year) ,'B',1,'C');

			$pdf->Cell(0 ,1,'',0,1);
			$pdf->SetFont($font,'I',$fontSize-1);
			$pdf->Cell(52 ,3,'',0,0);
			$pdf->Cell(75, 3, "Name (Surname, First, MI)", 0,0, 'C');

			$pdf->Cell(25, 3, "Age / Sex", 0,0, 'C');
			$pdf->Cell(3 ,3,'',0,0);
			$pdf->SetFont($font,'I',$fontSize-1);
			$pdf->Cell(42, 3, "Degree-Year", 0,0, 'C');

			$pdf->Ln(5);

			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(32 ,5, "was examined on",0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(30 ,5, ucwords($consult_date) ,'B',0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(35 ,5, "for the following:",0,1);

			$pdf->Cell(0 ,1,'',0,1);
			$pdf->SetFont($font,'I',$fontSize-1);
			$pdf->Cell(32, 3, "", 0,0);
			$pdf->Cell(30, 3, "Date", 0,0, 'C');

			$pdf->Ln(5);

			$Y = $pdf->GetY();
			$pdf->SetY($Y+2);

			$lines = preg_split('/\n/',$purpose_others);
			$purpose_others_height = count($lines) * 5; 
			$lines2 = preg_split('/\n/',$pf_remarks);
			$pf_remarks_height = count($lines2) * 5; 
			if($purpose_others_height > $pf_remarks_height){
				$pdf->Line(11, $Y, 205, $Y); //table top border
				$pdf->Line(11, $Y, 11, $Y+37+$purpose_others_height); //table left border
				$pdf->Line(90, $Y, 90, $Y+37+$purpose_others_height); //table middle border
				$pdf->Line(205, $Y, 205, $Y+37+$purpose_others_height); //table right border
				$pdf->Line(11, $Y+37+$purpose_others_height, 205, $Y+37+$purpose_others_height); //table bottom border
			}else{
				$pdf->Line(11, $Y, 205, $Y); //table top border
				$pdf->Line(11, $Y, 11, $Y+37+$pf_remarks_height); //table left border
				$pdf->Line(90, $Y, 90, $Y+37+$pf_remarks_height); //table middle border
				$pdf->Line(205, $Y, 205, $Y+37+$pf_remarks_height); //table right border
				$pdf->Line(11, $Y+37+$pf_remarks_height, 205, $Y+37+$pf_remarks_height); //table bottom border
			}
			
			$pdf->SetFont($font,'',$fontSize);
			if($purpose == 'Enrollment'){
				$check1 = "3";
				$check2 = "";
				$check3 = "";
				$check4 = "";
				$showXtraInfo = false;
			}else if($purpose == 'OJT / Practice Teaching / Internship') {
				$check1 = "";
				$check2 = "3";
				$check3 = "";
				$check4 = "";
				$showXtraInfo = false;
			}else if($purpose == 'Athletics'){
				$check1 = "";
				$check2 = "";
				$check3 = "3";
				$check4 = "";
				$showXtraInfo = false;
			}else if($purpose == 'others'){
				$check1 = "";
				$check2 = "";
				$check3 = "";
				$check4 = "3";
				$showXtraInfo = true;
			}

			if($is_pf == 'Physically Fit'){
				$pf = "3";
				$pu = "";
			}else{
				$pf = "";
				$pu = "3";
			}

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check1, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(70 ,7,'ENROLLMENT',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(195 ,7,'He/she is found to be:',0,0);
			$pdf->Ln(6);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check2, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->MultiCells(65 ,5,'OJT / PRACTICE TEACHING / INTERNSHIP',0,0,'L');
			$pdf->Cell(15, 7, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $pf, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(50 ,7,'PHYSICALLY FIT',0,0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $pu, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(50 ,7,'PHYSICALLY UNFIT',0,0);
			$pdf->Ln(5);

			$pdf->Cell(80 ,7,'',0,0);
			$pdf->Cell(60 ,7,'Remarks:',0,0);

			$pdf->Ln(6);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check3, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(72 ,7,'ATHLETICS',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(110 ,7,$pf_remarks,'B',0);

			$pdf->Ln(6);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check4, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,7,'OTHERS:',0,0);


			if($showXtraInfo){
				$pdf->SetFont($font,'',$fontSize);
				$pdf->MultiCells(48 ,5,$purpose_others,'B',0);
				$pdf->Cell(4, 7, '', 0, 0);//indentation
			}else{
				$pdf->MultiCells(48 ,7,'','B',0,'L');
				$pdf->Cell(4, 7, '', 0, 0);//indentation
			}

			//$pdf->MultiCells(110 ,5,'','B',0);

			$pdf->Ln(6);

			/*$pdf->Cell(30, 7, '', 0, 0);
			$pdf->MultiCells(48 ,7,'','B',0,'L');
			$pdf->Cell(4, 5, '', 0, 0);//indentation
			$pdf->MultiCells(110 ,5,'','B',0);*/

			
			
			$spacing = 7;
			if($purpose_others_height > $pf_remarks_height){
				$pdf->Ln($spacing + $purpose_others_height);
			}else{	
				$pdf->Ln($spacing + $pf_remarks_height);
			}
			
			$Y = $pdf->GetY();
			
			$pdf->SetY($Y+2);
			
			$lines = preg_split('/\n/',$diagnosis);
			$diagnosis_height = 8 + count($lines) * 5;
			$pdf->Line(11, $Y, 205, $Y); //table top border
			$pdf->Line(11, $Y, 11, 18+$Y+$diagnosis_height); //table left border
			$pdf->Line(55, $Y, 55, 18+$Y+$diagnosis_height); //table middle border
			$pdf->Line(205, $Y, 205, 18+$Y+$diagnosis_height); //table right border
			$pdf->Line(11, 18+$Y+$diagnosis_height, 205, 18+$Y+$diagnosis_height); //table bottom border
			

			$pdf->SetFont($font,'',$fontSize);
			if($reason == 'Absence'){
				$check1 = "3";
				$check2 = "";
				$check3 = "";
				$showXtraInfo = false;
			}else if($reason == 'Sick Leave') {
				$check1 = "";
				$check2 = "3";
				$check3 = "";
				$showXtraInfo = false;
			}else if($reason == 'PE Exemption'){
				$check1 = "";
				$check2 = "";
				$check3 = "3";
				$showXtraInfo = false;
			}

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check1, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(38 ,7,'ABSENCE',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(100 ,7,'Diagnosis:',0,1);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check2, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(38 ,7,'SICK LEAVE',0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(144 ,7,$diagnosis,'B',0);
			

			$pdf->Cell(5, 7, '', 0, 1);
			$DiagnosisY = $pdf->GetY();

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check3, 1, 0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(38 ,7,'PE EXEPTION',0,0);

			$pdf->Ln(7);
			
			$Y = $pdf->GetY();

			//if($DiagnosisY > $Y){
				$Y = 2+$DiagnosisY+$diagnosis_height;
			//}
			$pdf->SetY($Y+2);

			$lines = preg_split('/\n/',$is_excused_others);
			$is_excused_others_height = count($lines) * 5; 
			$lines2 = preg_split('/\n/',$general_remarks);
			$general_remarks_height = count($lines2) * 5;
				$pdf->Line(11, $Y, 205, $Y); //table top border
				$pdf->Line(11, $Y, 11, $Y+22+$is_excused_others_height+$general_remarks_height); //table left border
				$pdf->Line(205, $Y, 205, $Y+22+$is_excused_others_height+$general_remarks_height); //table right border
				$pdf->Line(11, $Y+22+$is_excused_others_height+$general_remarks_height, 205, $Y+22+$is_excused_others_height+$general_remarks_height); //table bottom border

			$pdf->SetFont($font,'',$fontSize);
			if($is_excused == 'Excused'){
				$check1 = "3";
				$check2 = "";
				$check3 = "";
				$check4 = "";
				$showXtraInfo = false;
			}else if($is_excused == 'Unexcused') {
				$check1 = "";
				$check2 = "3";
				$check3 = "";
				$check4 = "";
				$showXtraInfo = false;
			}else if($is_excused == 'Conditional'){
				$check1 = "";
				$check2 = "";
				$check3 = "3";
				$check4 = "";
				$showXtraInfo = false;
			}else{
				$check1 = "";
				$check2 = "";
				$check3 = "";
				$check4 = "3";
				$showXtraInfo = true;
			}

			$pdf->Cell(25 ,7,'REMARKS:',0,0,'C');
			$pdf->MultiCells(167 ,7,$general_remarks,'B',1);

			$pdf->Ln(5);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check1, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(25 ,7,'EXCUSED',0,0);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check2, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(30 ,7,'UNEXCUSED',0,0);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check3, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(30 ,7,'CONDITIONAL',0,0);

			$pdf->Cell(5, 5, '', 0, 0);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check4, 1, 0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(15 ,7,'Others: ',0,0);

			if($showXtraInfo){
				$pdf->SetFont($font,'',$fontSize);
				$pdf->MultiCells(52 ,7,$is_excused_others,'B',0);
				$pdf->Cell(21, 5, '', 0, 0);//indentation
			}else{ 
				$pdf->MultiCells(52 ,7,'','B',0,'L');
				$pdf->Cell(21, 5, '', 0, 0);//indentation
			}

			$pdf->Ln(15);

			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(135 ,7,'',0,0,'C');
			$pdf->Cell(60 ,7,'','B',1,'C');

			$pdf->Cell(135 ,7,'',0,0,'C');
			$pdf->Cell(60 ,7,'UNIVERSITY PHYSICIAN',0,1,'C');
		

			/*$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(60 ,5,'License Number: ',0,0,);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(172 ,5,$LicenseNumber,0,1,'L');//end of line*/



			//output the result
			$pdf->Output($idnumber .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      }
  }



?>