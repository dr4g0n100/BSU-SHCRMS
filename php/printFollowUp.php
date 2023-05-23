<?php
//call the FPDF library
require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
require('../fpdf/mem_image.php');

date_default_timezone_set('Asia/Manila');



$idnumber = $_GET['id'];
$type = $_GET['type'];
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
    global $ClinicRecordsDB, $connect, $Message, $type;

      /*if ($type =='viewArchivedCons'){
        $sql = "SELECT * FROM archivedconsultation  WHERE IdNumb = '$ID'";
      }else if ($type == 'viewCons'){
        $sql = "SELECT * FROM ConsultationInfo  WHERE IdNumb = '$ID'";
      }*/
  
      $sql = "SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE Num = '$ID'";

      $result = mysqli_query($connect, $sql);
      
      $Row = mysqli_fetch_array($result);
      
      if(empty($Row['Lastname'])){
          $sql = "SELECT * FROM consultationinfo LEFT JOIN archivedstudent ON consultationinfo.IdNumb = archivedstudent.StudentIDNumber WHERE Num = '$ID'";

          $result = mysqli_query($connect, $sql);
          
          $Row = mysqli_fetch_array($result);
                      
          
      }            
      
        if($Row)
          {        

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
						//create pdf object
						$pdf = new FPDF('P','mm','Legal');

						$pdf = new PDF_MemImage();
						//add new page
						$pdf->SetAutoPageBreak('off',1);
						$pdf->AddPage('P', [215.9, 330.2]);

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
						if(!empty($CourseStrand)){
							$Course = explode(')', (explode('(', $CourseStrand)[1]))[0];
						}else{
							$Course = ucwords(stripslashes($Row['StudentCategory']));;;
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
						$pdf->Ln($spacing);

				//5th row
						//1st Column
						$pdf->SetFont($font,'B',$fontSize);
						$pdf->Cell(20 ,5,'Age/Sex:',0,0);
						$pdf->SetFont($font,'',$fontSize);
						$pdf->Cell(18 ,5,$Age .' / ' .ucwords($Sex),'B',0);
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
						$pdf->Cell(45 ,5,$Others,'B',0);
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

							$pdf->SetY($Y);
							$pdf->SetX($X);

							//2nd Column
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Complaints:',0,0);
							$X3rdCol = $pdf->GetX();
							$pdf->Ln($spacing);
							$pdf->SetX($X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Complaints,0);

							$pdf->SetX($X);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Physical Findings:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$PhysicalFindings,0,0);

							$pdf->SetX($X);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Diagnosis:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Diagnosis,0,0);
							$Y2ndCol = $pdf->GetY();

							$pdf->SetY($Y3rdCol);
							$pdf->SetX($X3rdCol);

							//3rd Column
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Treatment:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Treatment,0);

							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Medicine Given:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$MedicineGiven,0,0);

							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Remarks:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Remarks,0,0);
							$Y3rdCol = $pdf->GetY();

							if($Y2ndCol > $Y3rdCol){
								$pdf->SetY($Y2ndCol);
							}else{
								$pdf->SetY($Y3rdCol);
							}

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
						$pdf->Cell(55 ,5,ucwords($PhysicianName),0,0,'L');
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
						
						$pdf->Ln($spacing);
						$bottomY =$pdf->GetY();

						
						$pdf->Line($col2_X, $headerY, $col2_X, $bottomY);
						$pdf->Line($col3_X, $headerY, $col3_X, $bottomY);

						$pdf->Ln($spacing);

//------------------------------line---------------------

						$sql = "SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE Num = '$ID'";

			      $result = mysqli_query($connect, $sql);
			      
			      $Row = mysqli_fetch_array($result);

			      if(empty($Row['Lastname'])){
			          $sql = "SELECT * FROM followup LEFT JOIN archivedstudent ON followup.IdNumb = archivedstudent.StudentIDNumber WHERE Num = '$ID'";

			          $result = mysqli_query($connect, $sql);
			          
			          $Row = mysqli_fetch_array($result);
			                      
			      }

			      if($Row){

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
							if(!empty($CourseStrand)){
								$Course = explode(')', (explode('(', $CourseStrand)[1]))[0];
							}else{
								$Course = ucwords(stripslashes($Row['StudentCategory']));;;
							}
							
							$Year = stripslashes($Row['Year']);;
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

							$picY = $pdf->GetY();
							$pdf->SetFont($font,'B',$fontSize+5);
							$pdf->Image('../images/BSULogo.png',60,$picY,20,20);
						  $pdf->MultiCells(185 ,20,'Follow Up',0,0,'C');
						  $pdf->Ln($spacing*4.5);

							$headerY = $pdf->GetY();
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
							$pdf->Ln($spacing);

							//4th row
							//1st Column
							$pdf->Cell(40 ,5,'',0,0);
							//2nd Column
							$pdf->Ln($spacing);
							//3rd Column
							

							//5th row
							//1st Column
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(20 ,5,'Age/Sex:',0,0);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->Cell(18 ,5,$Age .' / ' .ucwords($Sex),'B',0);
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

							$lines = preg_split('/\n/',$Complaints);
							$ComplaintsHeight = count($lines) * 5;
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(100 ,5,$Complaints,1,0);

							$lines = preg_split('/\n/',$Treatment);
							$TreatmentsHeight = count($lines) * 5;
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(55 ,5,$Treatment,1,0);

							/*if($ComplaintsHeight > $TreatmentsHeight){
								$pdf->Ln($ComplaintsHeight);
							}else{
								$pdf->Ln($TreatmentsHeight);
							}*/

							$pdf->SetX($X);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Physical Findings:',0,0);
							$pdf->Cell(55 ,5,'Medicine Given:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(100 ,5,$PhysicalFindings,0,0);
							$pdf->MultiCells(55 ,5,$MedicineGiven,0,0);
							$pdf->Ln($spacing);

							$pdf->SetX($X);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Diagnosis:',0,0);
							$pdf->Cell(55 ,5,'Remarks:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCells(100 ,5,$Diagnosis,0,0);
							$pdf->MultiCells(55 ,5,$Remarks,0,0);
							$Y2ndCol = $pdf->GetY();

							/*$pdf->SetY($Y);
							$pdf->SetX($X3rdCol);*/

							/*//3rd Column
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(100 ,5,'Treatment:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Treatment,0);

							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Medicine Given:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$MedicineGiven,0,0);

							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'B',$fontSize);
							$pdf->Cell(55 ,5,'Remarks:',0,0);
							$pdf->Ln($spacing);
							$pdf->SetX($X3rdCol);
							$pdf->SetFont($font,'',$fontSize);
							$pdf->MultiCell(55 ,5,$Remarks,0,0);
							$Y3rdCol = $pdf->GetY();*/



							/*if($Y2ndCol > $Y3rdCol){
								$pdf->SetY($Y2ndCol);
							}else{
								$pdf->SetY($Y3rdCol);
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
						$pdf->Cell(55 ,5,ucwords($PhysicianName),0,0,'L');
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

						
						$pdf->Line($col2_X, $headerY, $col2_X, $bottomY);
						$pdf->Line($col3_X, $headerY, $col3_X, $bottomY);
				      }

				      

							

						


						//output the result
						$pdf->Output($IDNumber .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      
  }



?>