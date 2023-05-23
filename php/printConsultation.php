<?php
//call the FPDF library
//require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
include('../fpdf/modified.php');

date_default_timezone_set('Asia/Manila');



$idnumber = $_GET['id'];
$cons_date = $_GET['cons_date'];
$cons_time = $_GET['cons_time'];
$Message = '';


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        FetchUser($idnumber,$cons_date,$cons_time);
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


  function FetchUser($idnumber,$cons_date,$cons_time){
    $sql = '';

    //Access Global Variables
    global $connection, $ClinicRecordsDB, $connect, $Message, $type;

      /*if ($type =='viewArchivedCons'){
        $sql = "SELECT * FROM archivedconsultation  WHERE IdNumb = '$ID'";
      }else if ($type == 'viewCons'){
        $sql = "SELECT * FROM ConsultationInfo  WHERE IdNumb = '$ID'";
      }*/
  
      $sql = "SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE IdNumb = '$idnumber' AND Dates = '$cons_date' AND Times = '$cons_time'";

      $result = mysqli_query($connect, $sql);
      
      $Row = mysqli_fetch_array($result);
      
      if(empty($Row['Lastname'])){
          $sql = "SELECT * FROM consultationinfo LEFT JOIN archivedstudent ON consultationinfo.IdNumb = archivedstudent.StudentIDNumber WHERE IdNumb = '$idnumber' AND Dates = '$cons_date' AND Times = '$cons_time'";

          $result = mysqli_query($connect, $sql);
          
          $Row = mysqli_fetch_array($result);
                      
          
      }            
      
        if($Row)
          {        

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
						//create pdf object
						$pdf = new modifiedFPDF('P','mm','Legal');

						//add new page
						$pdf->AddPage('P', [215.9, 330.2]);
						$pdf->SetAutoPageBreak(true,1); //123123123

						$IDNumber = stripslashes($Row['IdNumb']);;
						$Date = stripslashes($Row['Dates']);;
						$Time = stripslashes($Row['Time']);;
						$LastName = stripslashes($Row['Lastname']);;
						$FirstName = stripslashes($Row['Firstname']);;
						$MiddleName = stripslashes($Row['Middlename']);;
						$Extension = stripslashes($Row['Extension']);;
						$Age = stripslashes($Row['Age']);;
						$Sex = stripslashes($Row['Sex']);;
						$CourseStrand = stripslashes($Row['Course']);;
						if (!empty($CourseStrand)) {
						    $openParenPos = strpos($CourseStrand, '(');
						    $closeParenPos = strpos($CourseStrand, ')');
						    if ($openParenPos !== false && $closeParenPos !== false && $openParenPos < $closeParenPos) {
						        $Course = explode(')', (explode('(', $CourseStrand)[1]))[0];
						    } else {
						        $Course = ucwords(stripslashes($Row['StudentCategory']));
						    }
						} else {
						    $Course = ucwords(stripslashes($Row['StudentCategory']));
						}
						


						$Year = stripslashes($Row['Year']);;
						$Temperature = stripslashes($Row['cons_Temperature']);;
						$BloodPressure = stripslashes($Row['cons_BloodPressure']);;
						$PulseRate = stripslashes($Row['cons_PulseRate']);;
						$Smoker = stripslashes($Row['Smoker']);;
						$NumOfStick = stripslashes($Row['NumOfStick']);;
						$NumOfYearAsSmoker = stripslashes($Row['NumOfYearAsSmoker']);;
						$AlcoholDrinker = stripslashes($Row['AlcoholDrinker']);;
						$AgeStartedAsDrinker = stripslashes($Row['AgeStartedAsDrinker']);;
						$Others = stripslashes($Row['Others']);;
						$BetelNutChewer = stripslashes($Row['Moma']);;
						$MomaDuration = stripslashes($Row['HowLongAsChewer']);;
						$VS = stripslashes($Row['Vaccination']);;
						$VaccineBrand = stripslashes($Row['Vaccine']);;
						$Booster = stripslashes($Row['Booster']);;
						$Complaints = htmlentities($Row['Complaints']);
						$Diagnosis = htmlentities($Row['Diagnosis']);
						$Treatment = htmlentities($Row['DiagnosticTestNeeded']);
						$MedicineGiven = htmlentities($Row['MedicineGiven']);
						$Remarks = htmlentities($Row['Remarks']);
						$PhysicalFindings = htmlentities($Row['PhysicalFindings']);
						$StaffIDNumber = stripslashes($Row['PhysicianID']);;
						$PhysicianName = stripslashes($Row['Physician']);;

						$font = 'Arial';
						$fontSize = '11';
						$pageHeight = '330.2';
						$showXtraInfo = false;
						$spacing = 5;

						$pdf->SetTitle('Print Consultation Record');

						//set font to arial, bold, 14pt
						$pdf->SetFont($font,'B',$fontSize);

						//Cell(width , height , text , border , end line , [align] )
						$pdf->SetFont($font,'B',$fontSize+5);
						$pdf->Image('../images/BSULogo.png',60,3,20,20);
						$pdf->Cell(15 ,20,'',0,0);
						$pdf->MultiCells(175 ,8,'Consultation Sheet',0,0,'C');
						$pdf->Ln(15);


						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(20 ,5,'NAME: ',0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(175 ,5, ucwords($LastName) .', ' .ucwords($FirstName) .' ' .ucwords($MiddleName) .' ' .ucwords($Extension)  ,0,0);
						$pdf->Ln($spacing);

						$headerY = $pdf->GetY();


						//table header
						$pdf->Cell(40 ,5,'','T R B',0);
						$col2_X = $pdf->GetX();
						$pdf->Cell(100 ,5,'COMPLAINTS',1,0,'C');
						$col3_X = $pdf->GetX();
						$pdf->Cell(55 ,5,'TREATMENT','T L B',0,'C');
						$pdf->Ln($spacing+2);

						$pdf->Line($col2_X, $headerY, $col2_X, $pageHeight-10);
						$pdf->Line($col3_X, $headerY, $col3_X, $pageHeight-10);

				//table data

				//first row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(12 ,5,'Date: ',0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(26 ,5,$Date,'B',0);
						$pdf->Cell(2 ,5,'',0,0);
						//2nd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(100 ,5,'Past Medical History:',0,0);
						$Y3rdCol = $pdf->GetY();
						//3rd Column
						$pdf->Ln($spacing);

				//2nd row
						//1st Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(40,5,'',0,0);
						//2nd Column
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(20 ,5,'Smoker?',0,0,'L');
						$pdf->SetFont($font,'',$fontSize);

						$check1 = "";
						$check2 = "";
						if($Smoker == 'Yes'){
							$check1 = "3";
							$check2 = "";
							$showXtraInfo = true;
						}else if($Smoker == 'No'){
							$check1 = "";
							$check2 = "3";
							$showXtraInfo = false;
						}

						$pdf->Cell(10 ,5,'Yes',0,0);
						//set checkmark if yes
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check1, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(7 ,5,'No',0,0);
						//set checkmark if no
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check2, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(35 ,5,'',0,0,'C');//remaining space

				//3rd Column
						$pdf->Ln($spacing);

						//3rd row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize-1);
						$pdf->Cell(19 ,5,'Course/Yr: ',0,0);
						$pdf->SetFont($font,'',$fontSize-1);
						$pdf->MultiCells(21 ,5,$Course .' / ' .$Year,'B',0);
						$pdf->Cell(1 ,5,'',0,0);//spacing
						//2nd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(63 ,5,'If yes, how many sticks per day?',0,0);
						$pdf->Cell(20 ,5,$NumOfStick,'B',0);
						//3rd Column
						$pdf->Ln($spacing);

				//4th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);
						//2nd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(55 ,5,'Number of years as smoker:',0,0);
						$pdf->Cell(30 ,5,$NumOfYearAsSmoker,'B',0);
						//3rd Column
						$pdf->Ln($spacing*2);

				//5th row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(18 ,5,'Age/Sex:',0,0);
						$pdf->SetFont($font,'',$fontSize-1);
						$pdf->Cell(21 ,5,$Age .' / ' .ucwords($Sex),'B',0);
						$pdf->Cell(2 ,5,'',0,0);
						
						
						//2nd Column
						$pdf->Cell(100 ,5,'',0,0);
						//3rd Column
						$pdf->Ln($spacing);

				//6th row
						//1st Column
						$word = 'Temp°:';
						$word = iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $word);          /* º */
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(15 ,5,$word,0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(23 ,5,$Temperature,'B',0);
						$pdf->Cell(2 ,5,'',0,0);
						
						
						//2nd Column
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(37 ,5,'Betel Nut Chewing?',0,0,'L');
						$pdf->SetFont($font,'',$fontSize);

						$check1 = "";
						$check2 = "";
						if($BetelNutChewer == 'Yes'){
							$check1 = "3";
							$check2 = "";
							$showXtraInfo = true;
						}else if($BetelNutChewer == 'No'){
							$check1 = "";
							$check2 = "3";
							$showXtraInfo = false;
						}

						$pdf->Cell(10 ,5,'Yes',0,0);
						//set checkmark if yes
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check1, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(7 ,5,'No',0,0);
						//set checkmark if no
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check2, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(18 ,5,'',0,0,'C');//remaining space
						//3rd Column
						$pdf->Ln($spacing);

				//7th row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(10 ,5,'BP: ',0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(28 ,5,$BloodPressure,'B',0);
						$pdf->Cell(2 ,5,'',0,0);
						
						
						//2nd Column
						$pdf->Cell(40 ,5,'If yes, for how long?',0,0);
						$pdf->Cell(45 ,5,$MomaDuration,'B',0);
						$pdf->Cell(20 ,5,'',0,0);

						//3rd Column
						$pdf->Ln($spacing);

				//8th row
						//1st Column
						$pdf->Ln($spacing-2);

						//2nd Column
						
						//3rd Column
						
				//9th row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(10 ,5,'PR: ',0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(21 ,5,$PulseRate,'B',0);
						$pdf->Cell(9 ,5,'',0,0);
						//2nd Column
						$pdf->SetFont($font,'U',$fontSize);
						$pdf->Cell(34 ,5,'Alcohol Drinker?',0,0,'L');
						$pdf->SetFont($font,'',$fontSize);

						$check1 = "";
						$check2 = "";
						if($AlcoholDrinker == 'Yes'){
							$check1 = "3";
							$check2 = "";
							$showXtraInfo = true;
						}else if($AlcoholDrinker == 'No'){
							$check1 = "";
							$check2 = "3";
							$showXtraInfo = false;
						}

						$pdf->Cell(10 ,5,'Yes',0,0);
						//set checkmark if yes
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check1, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(7 ,5,'No',0,0);
						//set checkmark if no
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check2, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						$pdf->Cell(15 ,5,'',0,0,'C');//remaining space
						//3rd Column
						$pdf->Ln($spacing);

				//10th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->Cell(38 ,5,'If yes, age started: ',0,0);
						$pdf->Cell(45 ,5,$AgeStartedAsDrinker,'B',0);
						$pdf->Cell(17 ,5,'',0,0);

						//3rd Column
						$pdf->Ln($spacing);

				//10th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(15 ,5,'Others: ',0,0);
						$pdf->MultiCells(45 ,5,$Others,'B',1);
						$pdf->Cell(42 ,5,'',0,0);

						//3rd Column
						$pdf->Ln($spacing);

				//10th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(38 ,5,'Vaccination Status: ',0,0);

						$check1 = "";
						$check2 = "";
						if($VS == 'Fully Vaccinated'){
							$check1 = "3";
							$check2 = "";
							$showXtraInfo = true;
						}else if($VS == 'Not Vaccinated'){
							$check1 = "";
							$check2 = "3";
							$showXtraInfo = false;
						}

						$pdf->Cell(34 ,5,'Fully Vaccinated ',0,0);
						//set checkmark if yes
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check1, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						

						$pdf->Cell(15 ,5,'',0,0,'C');//remaining space

						//3rd Column
						$pdf->Ln($spacing);

				//11th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->Cell(38 ,5,'',0,0);
						$pdf->Cell(34 ,5,'Not Vaccinated ',0,0);
						//set checkmark if no
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(5 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check2, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(5 ,5,'','B',0);

						//3rd Column
						$pdf->Ln($spacing);

				//12th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->Cell(38 ,5,'',0,0);
						$pdf->Cell(20 ,5,'Booster 1 ',0,0);

						$check1 = "";
						$check2 = "";
						if($Booster == 'Booster 1'){
							$check1 = "3";
							$check2 = "";
							$showXtraInfo = true;
						}else if($Booster == 'Booster 2') {
							$check1 = "";
							$check2 = "3";
							$showXtraInfo = false;
						}
						//set checkmark if Booster 1
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(1 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check1, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(1 ,5,'','B',0);

						$pdf->Cell(20 ,5,'Booster 2',0,0);

						//set checkmark if Booster 2
						$checkX = $pdf->GetX();
						$pdf->SetX($checkX);
						$pdf->Cell(1 ,5,'','B',0);
						$pdf->SetFont('ZapfDingbats','', 10);
						$pdf->Cell(5, 5, $check2, 'B', 0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(1 ,5,'','B',0);

						$pdf->Ln($spacing);

						$pdf->Cell(40 ,5,'',0,0);
						$X = $pdf->GetX();
						$Y = $pdf->GetY();

							$pdf->SetXY($col3_X,$Y3rdCol);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Treatment:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($col3_X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Treatment,0,0);

							$pdf->SetY($Y);
							$pdf->SetX($X);

							//2nd Column
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Complaints:',0,0);
							$pdf->Cell(55 ,5,'Medicine Given:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);

							/*$lines = preg_split('/\n/',$Complaints);
							$ComplaintsHeight = count($lines) * 5; 
							$lines = preg_split('/\n/',$MedicineGiven);
							$MedicineGivenHeight = count($lines) * 5; */

							$TextLines = $pdf->NbLines(100,$Complaints);
							$ComplaintsHeight = $TextLines;
							$TextLines = $pdf->NbLines(55,$MedicineGiven);
							$MedicineGivenHeight = $TextLines;

							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(100 ,5,$Complaints,0);
							$pdf->MultiCell(55 ,5,$MedicineGiven,0);

							$TASpacing = '0';
							if($ComplaintsHeight > $MedicineGivenHeight){
								$TASpacing = $ComplaintsHeight;
							}else{
								$TASpacing = $MedicineGivenHeight;
							}
							$pdf->Ln($TASpacing); //123123123

							$CurrentY = $pdf->GetY();
							if(($CurrentY + $TASpacing) > 330.2){
								$pdf->AddPage('P', [215.9, 330.2]);
								$pdf->Line($col2_X, 10, $col2_X, 320.2);
								$pdf->Line($col3_X, 10, $col3_X, 320.2);
							}

							$pdf->SetX($X);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Physical Findings:',0,0);
							$pdf->Cell(55 ,5,'Remarks:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);

							/*$lines = preg_split('/\n/',$PhysicalFindings);
							$PhysicalFindingsHeight = count($lines) * 5; 
							$lines = preg_split('/\n/',$Remarks);
							$RemarksHeight = count($lines) * 5; */
							$TextLines = $pdf->NbLines(100,$PhysicalFindings);
							$PhysicalFindingsHeight = $TextLines;
							$TextLines = $pdf->NbLines(55,$Remarks);
							$RemarksHeight = $TextLines;

							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(100 ,5,$PhysicalFindings,0,0);
							$pdf->MultiCell(55 ,5,$Remarks,0,0);

							$TASpacing = '0';
							if($PhysicalFindingsHeight > $RemarksHeight){
								$TASpacing = $PhysicalFindingsHeight;
							}else{
								$TASpacing = $RemarksHeight;
							}
							$pdf->Ln($TASpacing); //123123123

							$CurrentY = $pdf->GetY();
							if(($CurrentY + $TASpacing) > 330.2){
								$pdf->AddPage('P', [215.9, 330.2]);
								$pdf->Line($col2_X, 10, $col2_X, 320.2);
								$pdf->Line($col3_X, 10, $col3_X, 320.2);
							}

							$pdf->Cell(40 ,5,'',0,0);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Diagnosis:',0,1);
							$pdf->Cell(40 ,5,'',0,0);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Diagnosis,0,0);
							$pdf->Ln($spacing*3);

				//17th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(100 ,5,'MEDICAL STAFF',0,0,'R');

						//3rd Column
						$pdf->Ln($spacing);

				//17th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);

						//2nd Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(100 ,5,'Charted By:',0,0,'R');

						//3rd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->MultiCell(55 ,5,ucwords($PhysicianName),0,'L');
						$pdf->Ln($spacing*2);

				//17th row
						//1st Column
						$pdf->Cell(40 ,5,'',0,0);
						
						//2nd Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(100 ,5,'Examined By:',0,0,'R');

						//3rd Column
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(55 ,5,'',0,0,'L');
						
						$pdf->Ln($spacing*3);


//------------------------------line---------------------

						$sql = "SELECT * FROM followup WHERE IdNumb = '$idnumber' AND (cons_date = '$cons_date' AND cons_time = '$cons_time')";


			      $result = $connection->query($sql);

			      if ($result->num_rows > 0) {

			      	while($Row = $result->fetch_assoc()) {
			              $IDNumber = stripslashes($Row['IdNumb']);;
										$Date = stripslashes($Row['Dates']);;
										$Time = stripslashes($Row['fu_time']);;

										/*$LastName = stripslashes($Row['Lastname']);;
										$FirstName = stripslashes($Row['Firstname']);;
										$MiddleName = stripslashes($Row['Middlename']);;
										$Extension = stripslashes($Row['Extension']);;
										$Age = stripslashes($Row['Age']);;
										$Sex = stripslashes($Row['Sex']);;
										$CourseStrand = stripslashes($Row['Course']);;
										if(!empty($CourseStrand)){
											$Course = explode(')', (explode('(', $CourseStrand)[1]))[0];
										}else{
											$Course = ucwords(stripslashes($Row['StudentCategory']));;;
										}
										
										$Year = stripslashes($Row['Year']);;*/

										$Temperature = stripslashes($Row['cons_Temperature']);;
										$BloodPressure = stripslashes($Row['cons_BloodPressure']);;
										$PulseRate = stripslashes($Row['cons_PulseRate']);;

										$Complaints = htmlentities($Row['Complaints']);
										$Diagnosis = htmlentities($Row['Diagnosis']);
										$Treatment = htmlentities($Row['DiagnosticTestNeeded']);
										$MedicineGiven = htmlentities($Row['MedicineGiven']);
										$Remarks = htmlentities($Row['Remarks']);
										$PhysicalFindings = htmlentities($Row['PhysicalFindings']);
										$StaffIDNumber = stripslashes($Row['PhysicianID']);;
										$PhysicianName = stripslashes($Row['Physician']);;

										//$pdf->AddPage('P', [215.9, 330.2]);
										//$pdf->SetAutoPageBreak(true); //123123123

										$picY = $pdf->GetY();
										$pdf->SetFont($font,'B',$fontSize+7);
									  $pdf->MultiCells(185 ,10,'  Follow Up',0,0,'L');
									  $pdf->Ln($spacing*2);

										$headerY = $pdf->GetY();
										$pdf->Line($col2_X, $headerY, $col2_X, 320.2);
										$pdf->Line($col3_X, $headerY, $col3_X, 320.2);
							      //table header
							      $pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(40 ,5,'','T R B',0);
										$col2_X = $pdf->GetX();
										$pdf->Cell(100 ,5,'COMPLAINTS',1,0,'C');
										$col3_X = $pdf->GetX();
										$pdf->Cell(55 ,5,'TREATMENT','T L B',0,'C');
										$pdf->Ln($spacing+2);

										//first row
										//1st Column
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(12 ,5,'Date: ',0,0);
										$pdf->SetFont($font,'',$fontSize);
										$pdf->Cell(26 ,5,$Date,'B',0);
										$pdf->Cell(2 ,5,'',0,0);

										$Y = $pdf->GetY();
										

										//2nd row
										//1st Column
										$pdf->SetFont($font,'',$fontSize);
										$pdf->Cell(40,5,'',0,0);

										//2nd Column
										$pdf->Ln($spacing);

										//3rd row
										//1st Column
										$pdf->SetFont($font,'B',$fontSize-1);
										$pdf->Cell(19 ,5,'Course/Yr: ',0,0);
										$pdf->SetFont($font,'',$fontSize-1);
										$pdf->MultiCells(21 ,5,$Course .' / ' .$Year,'B',0);
										$pdf->Cell(1 ,5,'',0,0);//spacing
										//2nd Column
										$pdf->Cell(83 ,5,'',0,0);
										//3rd Column
										$pdf->Ln($spacing*2);

										//4th row
										//1st Column
										$pdf->Cell(40 ,5,'',0,0);
										//2nd Column
										$pdf->Ln($spacing);
										//3rd Column
										

										//5th row
										//1st Column
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(18 ,5,'Age/Sex:',0,0);
										$pdf->SetFont($font,'',$fontSize-1);
										$pdf->Cell(21 ,5,$Age .' / ' .ucwords($Sex),'B',0);
										$pdf->Cell(2 ,5,'',0,0);
										
										
										//2nd Column
										$pdf->Ln($spacing);

										//6th row
										//1st Column
										$word = 'Temp°:';
										$word = iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $word);          /* º */
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(15 ,5,$word,0,0);
										$pdf->SetFont($font,'',$fontSize);
										$pdf->Cell(23 ,5,$Temperature,'B',0);
										$pdf->Cell(2 ,5,'',0,0);
										
										
										//2nd Column
										$pdf->Ln($spacing);

										//7th row
										//1st Column
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(10 ,5,'BP: ',0,0);
										$pdf->SetFont($font,'',$fontSize);
										$pdf->Cell(28 ,5,$BloodPressure,'B',0);
										$pdf->Cell(2 ,5,'',0,0);
										
										
										//2nd Column
										$pdf->Ln($spacing);

										//8th row
										//1st Column
										$pdf->Ln($spacing-2);

										//2nd Column
										
										//3rd Column
										
										//9th row
										//1st Column
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(10 ,5,'PR: ',0,0);
										$pdf->SetFont($font,'',$fontSize);
										$pdf->Cell(21 ,5,$PulseRate,'B',0);
										$pdf->Cell(9 ,5,'',0,0);
										//2nd Column
										$X = $pdf->GetX();
										$pdf->Ln($spacing);

										$pdf->SetY($Y);
										$pdf->SetX($X);

										//2nd Column
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(100 ,5,'Complaints:',0,0);
										$pdf->Cell(55 ,5,'Treatment:',0,0);
										$pdf->Ln($spacing);
										$pdf->SetX($X);

										/*$lines = preg_split('/\n/',$Complaints);
										$ComplaintsHeight = count($lines) * 5; 
										$lines = preg_split('/\n/',$Treatment);
										$TreatmentHeight = count($lines) * 5; */
										$TextLines = $pdf->NbLines(100,$Complaints);
										$ComplaintsHeight = $TextLines*5;
										$TextLines = $pdf->NbLines(55,$Treatment);
										$TreatmentHeight = $TextLines*5;

										$pdf->SetFont($font,'',$fontSize);
										$pdf->MultiCells(100 ,5,$Complaints,0);
										$pdf->MultiCells(55 ,5,$Treatment,0);

										$TASpacing = '0';
										if($ComplaintsHeight > $TreatmentHeight){
											$TASpacing = $ComplaintsHeight;
										}else{
											$TASpacing = $TreatmentHeight;
										}
										$pdf->Ln($TASpacing); //123123123

										$CurrentY = $pdf->GetY();
										if(($CurrentY + $TASpacing) > 330.2){
											$pdf->AddPage('P', [215.9, 330.2]);
											$pdf->Line($col2_X, 10, $col2_X, 320.2);
											$pdf->Line($col3_X, 10, $col3_X, 320.2);
										}

										$pdf->SetX($X);
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(100 ,5,'Physical Findings:',0,0);
										$pdf->Cell(55 ,5,'Medicine Given:',0,0);
										$pdf->Ln($spacing);
										$pdf->SetX($X);

										/*$lines = preg_split('/\n/',$PhysicalFindings);
										$PhysicalFindingsHeight = count($lines) * 5; 
										$lines = preg_split('/\n/',$MedicineGiven);
										$MedicineGivenHeight = count($lines) * 5;*/ 
										$TextLines = $pdf->NbLines(100,$PhysicalFindings);
										$PhysicalFindingsHeight = $TextLines*5;
										$TextLines = $pdf->NbLines(55,$MedicineGiven);
										$MedicineGivenHeight = $TextLines*5;

										$pdf->SetFont($font,'',$fontSize);
										$pdf->MultiCells(100 ,5,$PhysicalFindings,0,0);
										$pdf->MultiCells(55 ,5,$MedicineGiven,0,0);

										$TASpacing = '0';
										if($PhysicalFindingsHeight > $MedicineGivenHeight){
											$TASpacing = $PhysicalFindingsHeight;
										}else{
											$TASpacing = $MedicineGivenHeight;
										}
										$pdf->Ln($TASpacing); //123123123

										$CurrentY = $pdf->GetY();
										if(($CurrentY + $TASpacing) > 330.2){
											$pdf->AddPage('P', [215.9, 330.2]);
											$pdf->Line($col2_X, 10, $col2_X, 320.2);
											$pdf->Line($col3_X, 10, $col3_X, 320.2);
										}

										$pdf->SetX($X);
										$pdf->SetFont($font,'B',$fontSize);
										$pdf->Cell(100 ,5,'Diagnosis:',0,0);
										$pdf->Cell(55 ,5,'Remarks:',0,0);
										$pdf->Ln($spacing);
										$pdf->SetX($X);

										/*$lines = preg_split('/\n/',$Diagnosis);
										$DiagnosisHeight = count($lines) * 5; 
										$lines = preg_split('/\n/',$Remarks);
										$RemarksHeight = count($lines) * 5;*/
										$TextLines = $pdf->NbLines(100,$Diagnosis);
										$DiagnosisHeight = $TextLines*5;
										$TextLines = $pdf->NbLines(55,$Remarks);
										$RemarksHeight = $TextLines*5;

										$pdf->SetFont($font,'',$fontSize);
										$pdf->MultiCells(100 ,5,$Diagnosis,0,0);
										$pdf->MultiCells(55 ,5,$Remarks,0,0);

										$TASpacing = '0';
										if($DiagnosisHeight > $RemarksHeight){
											$TASpacing = $DiagnosisHeight;
										}else{
											$TASpacing = $RemarksHeight;
										}
										$pdf->Ln($TASpacing); //123123123

										/*$CurrentY = $pdf->GetY();
										if(($CurrentY + $TASpacing) > 330.2){
											$pdf->AddPage('P', [215.9, 330.2]);
											$pdf->Line($col2_X, 10, $col2_X, 320.2);
											$pdf->Line($col3_X, 10, $col3_X, 320.2);
										}*/


										$pdf->Ln($spacing);

									//1st Column
									$pdf->Cell(40 ,5,'',0,0);

									//2nd Column
									$pdf->SetFont($font,'B',$fontSize);
									$pdf->Cell(100 ,5,'MEDICAL STAFF',0,0,'R');

									//3rd Column
									$pdf->Ln($spacing);

									//1st Column
									$pdf->Cell(40 ,5,'',0,0);

									//2nd Column
									$pdf->SetFont($font,'B',$fontSize);
									$pdf->Cell(100 ,5,'Charted By:',0,0,'R');

									//3rd Column
									$pdf->SetFont($font,'',$fontSize);
									$pdf->MultiCell(55 ,5,ucwords($PhysicianName),0,'L');
									$pdf->Ln($spacing*2);

									//1st Column
									$pdf->Cell(40 ,5,'',0,0);

									//2nd Column
									$pdf->SetFont($font,'B',$fontSize);
									$pdf->Cell(100 ,5,'Examined By:',0,0,'R');

									//3rd Column
									$pdf->SetFont($font,'',$fontSize);
									$pdf->Cell(55 ,5,'',0,0,'L');
									$pdf->Ln($spacing);

									$bottomY =$pdf->GetY();
							      }
              }



				      

				      

							

						


						//output the result
						$pdf->Output($IDNumber .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      
  }



?>