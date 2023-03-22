<?php
//call the FPDF library
require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
require('../fpdf/mem_image.php');

date_default_timezone_set('Asia/Manila');


$idnumber = $_GET['id'];
$type = $_GET['type'];
/*$idnumber = '2';
$type = 'viewRecord';*/
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

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $type;

      if ($type =='viewArchivedRecord'){
        $sql = "SELECT * FROM ARCHIVEDSTAFF  WHERE IdNum = '$ID'";
      }else if ($type == 'viewRecord' || $type == 'newRecord'){
        $sql = "SELECT * FROM USERACCOUNTS  WHERE IdNum = '$ID'";
      }

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
						//create pdf object
						$pdf = new FPDF('P','mm','Legal');

						$pdf = new PDF_MemImage();
						//add new page
						$pdf->AddPage('P', [215.9, 330.2]);

						$TxtIdNum =stripcslashes($Row['IdNum']);;
						$TxtEmail =stripcslashes($Row['Email']);;
            $TxtUsername = stripcslashes($Row['Username']);;
            $TxtLastname = stripslashes($Row['LastName']);;
            $TxtFirstname = stripslashes($Row['FirstName']);;
            $TxtMiddlename = stripslashes($Row['MiddleName']);;
            $TxtExtension = stripslashes($Row['Extension']);;
            $RadPosition = strtolower(stripslashes($Row['Position']));;
            $TxtRank = stripslashes($Row['Rank']);;
            $TxtContactNumber = stripslashes($Row['ContactNum']);;

						$font = 'Arial';
						$fontSize = '12';
						$pageHeight = '330.2';
						$spacing = 4;

						$pdf->SetTitle('Print Consultation Record');

						//set font to arial, bold, 14pt
						$pdf->SetFont($font,'B',$fontSize);

						//Cell(width , height , text , border , end line , [align] )
						$pdf->SetFont($font,'B',$fontSize+5);
						$pdf->Image('../images/BSULogo.png',65,3,20,20);
						$pdf->Cell(20 ,20,'',0,0);
						$pdf->MultiCells(175 ,8,'USER INFORMATION',0,0,'C');
						$pdf->Cell(15 ,15,'',0,1);//end of line

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(27 ,5,'ID NUMBER: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(48 ,5,$TxtIdNum,0,0);
						$pdf->Cell(40 ,5,'',0,1); //end of line

						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(15 ,5,'NAME: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(50 ,5, ucwords($TxtLastname) ,0,0,'C');
						$pdf->Cell(50 ,5, ucwords($TxtFirstname) ,0,0,'C');
						$pdf->Cell(39 ,5, ucwords($TxtMiddlename) ,0,0,'C');
						$pdf->Cell(40 ,5, ucwords($TxtExtension) ,0,1,'C');//end of line

						$pdf->SetFont($font,'B',$fontSize-2);
						$pdf->Cell(15 ,5,'',0,0);
						$pdf->Cell(50 ,5, '(Family Name)' ,0,0,'C');
						$pdf->Cell(50 ,5, '(First Name)' ,0,0,'C');
						$pdf->Cell(39 ,5, '(Middle Name)' ,0,0,'C');
						$pdf->Cell(40 ,5, '(Extension)' ,0,1,'C');//end of line

						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(12 ,5,'POSITION',0,1);
						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing
						// tick box
						$xValue = 15;
						$check1 = "";
						$check2 = "";
						$check3 = "";
						$check4 = "";
						$check5 = "";

						$pdf->SetX($xValue);
						if($RadPosition == 'doctor'){
							$check1 = "4";
							$check2 = "";
							$check3 = "";
							$check4 = "";
							$check5 = "";
						}else if($RadPosition == 'nurse') {
							$check1 = "";
							$check2 = "4";
							$check3 = "";
							$check4 = "";
							$check5 = "";
						}
						else if($RadPosition == 'administrative aide') {
							$check1 = "";
							$check2 = "";
							$check3 = "4";
							$check4 = "";
							$check5 = "";
						}
						else if($RadPosition == 'medical technologist') {
							$check1 = "";
							$check2 = "";
							$check3 = "";
							$check4 = "4";
							$check5 = "";
						}else if($RadPosition == 'triage officer') {
							$check1 = "";
							$check2 = "";
							$check3 = "";
							$check4 = "";
							$check5 = "4";
						}else if($RadPosition == 'superadmin'){
							$check1 = "4";
						}

						if($RadPosition == 'superadmin'){
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check1, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'SUPERADMIN',0,0);
						}else {
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check1, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'Doctor',0,0);
							$xValue += 23;
							$pdf->SetX($xValue);
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check2, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'Nurse',0,0);
							$xValue += 22;
							$pdf->SetX($xValue);
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check3, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'Administrative Aide',0,0);
							$xValue += 48;
							$pdf->SetX($xValue);
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check4, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'Medical Technologist',0,0);
							$xValue += 52;
							$pdf->SetX($xValue);
							$pdf->SetFont('ZapfDingbats','', 10);
							$pdf->Cell(4, 4, $check5, 1, 0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(12 ,5,'Triage Officer',0,1);
						}

						

						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(18 ,5,'LEVEL: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(48 ,5,$TxtRank,0,0);
						$pdf->Cell(40 ,5,'',0,1); //end of line

						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(45 ,5,'CONTACT NUMBER: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(60 ,5,$TxtContactNumber,0,0);
						
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(18 ,5,'EMAIL: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(48 ,5,$TxtEmail,0,0);
						$pdf->Cell(40 ,5,'',0,1); //end of line

						$pdf->Cell(0 ,$spacing,'',0,1,'C');//Spacing

						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(27 ,5,'USERNAME: ',0,0);
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(48 ,5,$TxtUsername,0,0);
						$pdf->Cell(40 ,5,'',0,1); //end of line

						//output the result
						$pdf->Output($TxtIdNum .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      }
  }

  function showTickBox ($check){

  }



?>