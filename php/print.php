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

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $type;

      
        $sql = "SELECT * FROM PersonalMedicalRecord  WHERE StudentIDNumber = '$ID'";
      

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        
            //$DocumentCode = stripslashes($Row['DocumentCode']);;
            $DocumentCode = 'QF-UHS-MC-01';
            $RevisionNumber = stripslashes($Row['RevisionNumber']);;
            $Effectivity = stripslashes($Row['Effectivity']);;
            	$TxtNoLabel = stripslashes($Row['NoLabel']);;
            $isNullStudentImage = true;
            if ($Row['StudentImage'] != null) {
            	  $isNullStudentImage = false;
            }
            $StudentStatus = $Row['Status'];
            $StudentImage = $Row['StudentImage'];
            	/*$StudentImage = $Row['StudentImage'];*/
            $StudentIDNumber = stripslashes($Row['StudentIDNumber']);;
            $StudentCategory = stripslashes($Row['StudentCategory']);;
            $Course = stripslashes($Row['Course']);;
            if (!empty($Course)) {
						    $openParenPos = strpos($Course, '(');
						    $closeParenPos = strpos($Course, ')');
						    if ($openParenPos !== false && $closeParenPos !== false && $openParenPos < $closeParenPos) {
						        $Course = explode(')', (explode('(', $Course)[1]))[0];
						    } else {
						        $Course = ucwords(stripslashes($Row['Course']));
						    }
						} else {
						    $Course = ucwords(stripslashes($Row['StudentCategory']));
						}

            $Year = stripslashes($Row['Year']);;
            $Section = stripslashes($Row['Section']);;
            $LastName = stripslashes($Row['Lastname']);;
            $FirstName = stripslashes($Row['Firstname']);;
            $MiddleName = stripslashes($Row['Middlename']);;
            $Extension = stripslashes($Row['Extension']);;
            $Age = stripslashes($Row['Age']);;
            $DOBirth = stripslashes($Row['Birthdate']);;
            $Sex = stripslashes($Row['Sex']);;
            $PresentAddress = stripslashes($Row['Address']);;
            $PresentAddress = explode("||", $PresentAddress);

            ////////////////////
            $ProvincialAddress = stripslashes($Row['ProvAdd']);;
            $ProvincialAddress = explode("||", $ProvincialAddress);
            //////////////////

            $StudentContNum = stripslashes($Row['StudentContactNumber']);;
            if($StudentContNum == '+639'){
	            	$StudentContNum = '';
	            }
            $GuardianParent = stripslashes($Row['GuardianParent']);;
            $GPCategory = stripslashes($Row['GPCategory']);;
            $ParentOrGuardian = stripslashes($Row['ContactPerson']);;
            $PGContNum = stripslashes($Row['PGContactNumber']);;
	            if($PGContNum == '+639'){
	            	$PGContNum = '';
	            }
            $GuardianParent1 = stripslashes($Row['GuardianParent1']);;
            $GPCategory1 = stripslashes($Row['GPCategory1']);;
            $ContactPerson1 = stripslashes($Row['ContactPerson1']);;
            $PGContactNumber1 = stripslashes($Row['PGContactNumber1']);;
            if($PGContactNumber1 == '+639'){
            	$PGContactNumber1 = '';
            }
            $GuardianParent2 = stripslashes($Row['GuardianParent2']);;
            $GPCategory2 = stripslashes($Row['GPCategory2']);;
            $ContactPerson2 = stripslashes($Row['ContactPerson2']);;
            $PGContactNumber2 = stripslashes($Row['PGContactNumber2']);;
           	if($PGContactNumber2 == '+639'){
            	$PGContactNumber2 = '';
            }
            $Date = stripslashes($Row['Date']);;
            $Time = stripslashes($Row['Time']);;
            $StaffIDNumber = stripslashes($Row['StaffIDNumber']);;
            $PhysicianName = stripslashes($Row['StaffFirstname']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffMiddlename']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffLastname']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffExtension']);;
            $LMP = stripslashes($Row['LMP']);;
            if($LMP == ""){
            		$LMP = "N/A";
            }
            $Pregnancy = stripslashes($Row['Pregnancy']);;
            if($Pregnancy == ""){
            		$Pregnancy = "N/A";
            }
            $Allergies = stripslashes($Row['Allergies']);;
            if($Allergies == ""){
            		$Allergies = "N/A";
            }
            $Surgeries = stripslashes($Row['Surgeries']);;
            if($Surgeries == ""){
            		$Surgeries = "N/A";
            }
            $Injuries = stripslashes($Row['Injuries']);;
            if($Injuries == ""){
            		$Injuries = "N/A";
            }
            $Illness = stripslashes($Row['Illness']);;
            if($Illness == ""){
            		$Illness = "N/A";
            }

            ////////////////
            $PastOthers = stripslashes($Row['MedicalOthers']);;
            if($PastOthers == ""){
            		$PastOthers = "N/A";
            }
            $RLOA = stripslashes($Row['RLOA']);;
            if($RLOA == ""){
            		$RLOA = "N/A";
            }
            ////////////////

            $SchoolYear = stripslashes($Row['SchoolYear']);;
            $term = stripslashes($Row['Term']);;
            $Height = stripslashes($Row['Height']);;
            if($Height == 0){
            	$Height = '';
            }
            $Weight = stripslashes($Row['Weight']);;
            if($Weight == 0){
            	$Weight = '';
            }
            $BMI = stripslashes($Row['BMI']);;
            $BMINum = explode('(', $BMI)[0];
            if($Height == '' && $Weight == ''){
            	$BMINum = '';
            }
            $BloodPressure = stripslashes($Row['BloodPressure']);;
            $Temperature = stripslashes($Row['Temperature']);;
            $PulseRate = stripslashes($Row['PulseRate']);;
            if($PulseRate == 0){
            	$PulseRate = '';
            }
            $VisionWithoutGlassesOD = stripslashes($Row['VisionWithoutGlassesOD']);;
            $VisionWithoutGlassesOS = stripslashes($Row['VisionWithoutGlassesOS']);;
            $VisionWithGlassesOD = stripslashes($Row['VisionWithGlassesOD']);;
            $VisionWithGlassesOS = stripslashes($Row['VisionWithGlassesOS']);;
            
            $VisionWithContLensOD = stripslashes($Row['VisionWithContLensOD']);;
            $VisionWithContLensOS = stripslashes($Row['VisionWithContLensOS']);;

            $HearingDistance = stripslashes($Row['HearingDistanceOpt']);;
            $Speech = stripslashes($Row['SpeechOpt']);;
            $Eyes = stripslashes($Row['EyesOpt']);;
            $Ears = stripslashes($Row['EarsOpt']);;
            $Nose = stripslashes($Row['NoseOpt']);;
            $Head = stripslashes($Row['HeadOpt']);;
            $Abdomen = stripslashes($Row['AbdomenOpt']);;
            $GenitoUrinary = stripslashes($Row['GenitoUrinaryOpt']);;
            $Lymph = stripslashes($Row['LymphGlandsOpt']);;
            $Skin = stripslashes($Row['SkinOpt']);;
            $Extremities = stripslashes($Row['ExtremitiesOpt']);;
            $Deformities = stripslashes($Row['DeformitiesOpt']);;
            $BCT = stripslashes($Row['CavityAndThroatOpt']);;
            $Lungs = stripslashes($Row['LungsOpt']);;
            $Heart = stripslashes($Row['HeartOpt']);;
            $Breast = stripslashes($Row['BreastOpt']);;
            $RadExams = stripslashes($Row['RadiologicExamsOpt']);;
            $BloodAnalysis = stripslashes($Row['BloodAnalysisOpt']);;
            $Urinalysis = stripslashes($Row['UrinalysisOpt']);;
            $Fecalysis = stripslashes($Row['FecalysisOpt']);;
            $PregnancyTest = stripslashes($Row['PregnancyTestOpt']);;
            $HBSAg = stripslashes($Row['HBSAgOpt']);;

            $StudentSignature = stripslashes($Row['Firstname']);;
            $StudentSignature .= ' ' . stripslashes($Row['Middlename']);;
            $StudentSignature .= ' ' .stripslashes($Row['Lastname']);;
            $StudentSignature .= ' ' .stripslashes($Row['Extension']);;


	            $TAHearingDistance = htmlentities($Row['TAHearingDistance']);
	            $TASpeech = htmlentities($Row['TASpeech']);
	            $TAEyes = htmlentities($Row['TAEyes']);
	            $TAEars = htmlentities($Row['TAEars']);
	            $TANose = htmlentities($Row['TANose']);
	            $TAHead = htmlentities($Row['TAHead']);
	            $TAAbdomen = htmlentities($Row['TAAbdomen']);
	            $TAGenitoUrinary = htmlentities($Row['TAGenitoUrinary']);
	            $TALymphGlands = htmlentities($Row['TALymphGlands']);
	            $TASkin = htmlentities($Row['TASkin']);
	            $TAExtremities = htmlentities($Row['TAExtremities']);
	            $TADeformities = htmlentities($Row['TADeformities']);
	            $TACavityAndThroat = htmlentities($Row['TACavityAndThroat']);
	            $TALungs = htmlentities($Row['TALungs']);
	            $TAHeart = htmlentities($Row['TAHeart']);
	            $TABreast = htmlentities($Row['TABreast']);
	            $TARadiologicExams = htmlentities($Row['TARadiologicExams']);
	            $TABloodAnalysis = htmlentities($Row['TABloodAnalysis']);
	            $TAUrinalysis = htmlentities($Row['TAUrinalysis']);
	            $TAFecalysis = htmlentities($Row['TAFecalysis']);
	            $TAPregnancyTest = htmlentities($Row['TAPregnancyTest']);
	            $TAHBSAg = htmlentities($Row['TAHBSAg']);
	            $Others = htmlentities($Row['TAOthers']);

            $Recommendation = htmlentities($Row['TARecommendation']);
            
            $Remarks = htmlentities($Row['TARemarks']);
            

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
			//create pdf object
			$pdf = new FPDF('P','mm','Legal');

			$pdf = new PDF_MemImage();
			$pdf->SetAutoPageBreak('on', 1);
			//add new page
			$pdf->AddPage('P', [215.9, 330.2]);

			//Set Title
			$pdf->SetTitle('Print Student Record');

			$font = 'Arial';
			$fontSize = '10';
			$pageHeight = '330.2';

			$pdf->AddFont('OLD','','OLD.php');

			//Fields spacing per row
			$fieldsSpacing = 4.5;

			////////////undefined variables//////////////
			$LicenseNumber = 'Placeholder License Number';
			////////////////////////////////////////////

			$pdf->SetY(3);
			$pdf->SetX(50);

			$pdf->SetFont($font,'',$fontSize-2);
			$pdf->Cell(60 ,5,'Republic of the Philippines',0,0,'C');
			$pdf->Ln(($fieldsSpacing));

			$pdf->SetFont('OLD','',$fontSize+4);
			$pdf->SetTextColor(21,71,52);
			$pdf->SetX(50);
			$pdf->Cell(60 ,5,'Benguet State University',0,0,'C');
			$pdf->Ln(($fieldsSpacing));

			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont($font,'BI',$fontSize-2);
			$pdf->SetX(50);
			$pdf->Cell(60 ,5,'UHS - MEDICAL CLINIC',0,0,'C');
			$pdf->Ln(($fieldsSpacing-1));

			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont($font,'',$fontSize-2);
			$pdf->SetX(50);
			$pdf->Cell(60 ,5,'La Trinidad, Benguet',0,0,'C');
			$pdf->Ln(($fieldsSpacing*1.5));

			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont($font,'B',$fontSize+2);
			$pdf->SetX(50);
			$pdf->Cell(60 ,5,'PHYSICAL EXAMINATION',0,0,'C');

			//set font to arial, bold, 14pt
			$pdf->SetFont($font,'',$fontSize);
			$pdf->SetY(6);

			//Cell(width , height , text , border , end line , [align] )
			$pdf->Image('../images/BSULogo.png',20,3,23,23);
			$pdf->Cell(35 ,20,'',0,0);
			$pdf->SetFont('Times','B',$fontSize+4);
			$pdf->Cell(70 ,10,'',0,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(25, 5, 'Document Code:', 1,0, 'L'); 
			$pdf->Cell(30, 10, strtoupper($DocumentCode), 1, 0, 'C'); 
			$pdf->MultiCells(16, 5, 'Revision Number:', 1);
			$pdf->Cell(19 ,10,$RevisionNumber,1,0); 
			$pdf->Cell(2, 10, '',0,);
			if ($isNullStudentImage == true) {
					$pdf->Image('../images/id picture.png',164,43, 41, 40);
			}else{
					$pdf->MemImage($StudentImage,164,43, 41, 40);
			}
			$pdf->Ln(10);

			$pdf->Cell(35 ,10,'',0,0);
			$pdf->Cell(60 ,10,'',0,0);
			$pdf->Cell(10 ,10,'',0,0);
			$pdf->MultiCells(25, 5, 'Effectivity Date:', 1); 
			$pdf->Cell(30, 10, ucwords($Effectivity), 1,0,'C'); 
			$pdf->Cell(35, 10, $TxtNoLabel, 1,0,'L');
			$pdf->Cell(0, 10, '',0,0); //end of line

			
			$pdf->Ln(($fieldsSpacing+10));

			$Y = $pdf->GetY();

			$DataPrivacy = '                                           The collected personal information is utilized solely for documentation and processing purposes of your Admission / Enrolment in the University and will not be shared with any outside parties. This is in accordance with the provisions of Data Privacy Act of 2012 (R.A. 10173). Download the BSU Data Privacy Notice at';
			$pdf->SetFont($font,'',$fontSize-.85);

			$titleDataPriv = 'DATA PRIVACY NOTICE: ';
			$link = 'http://www.bsu.edu.ph/dpa/data-privacy-notice';
			$pdf->MultiCells(195 ,4,$DataPrivacy,0,0, 'J');
			$pdf->SetY($Y);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(40 ,4,$titleDataPriv,0,0, 'J');
			$pdf->SetY($Y+$fieldsSpacing*1.75);
			$pdf->SetX(128);
			$pdf->SetFont($font,'U',$fontSize-1);
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(50 ,4,$link,0,0, 'J');
			$pdf->SetTextColor(0,0,0);
			

			$pdf->Ln(($fieldsSpacing));

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,$fieldsSpacing*4,'Name:','L T',0, 'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(40 ,$fieldsSpacing,'','T',0, 'L');
			$pdf->Cell(40 ,$fieldsSpacing,'','T',0, 'L');
			$pdf->Cell(10 ,$fieldsSpacing,'','T R',0, 'L');

			$X = $pdf->GetX();

			$pdf->SetFont($font,'B',$fontSize-1);
			$pdf->Cell(43 ,$fieldsSpacing,'  Pls. check(    )','R T',0, 'L');
			$pdf->SetX($X+19);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, '3', 0, 0);
			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,$fieldsSpacing,'','',0, 'L');
			$pdf->MultiCells(40 ,$fieldsSpacing/2,ucwords($LastName),0,0, 'L');
			$pdf->MultiCells(40 ,$fieldsSpacing/2,ucwords($FirstName),0,0, 'L');
			$MiddleInitial =  substr($MiddleName, 0, 1) . '.';
			$pdf->MultiCells(10 ,$fieldsSpacing/2,strtoupper($MiddleInitial),'R',0, 'C');

			$pdf->SetX($X+2);
			if($StudentStatus == 'new'){
				$check1 = "3";
				$check2 = "";
			}else {
				$check1 = "";
				$check2 = "3";
			}
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check1, 0, 0);
			$pdf->SetX($X+2);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(4 ,4,'',1,0, 'C');
			$pdf->SetFont($font,'B',$fontSize-1);
			$pdf->Cell(37 ,$fieldsSpacing,'  New Student','R',0, 'L');
			
			$pdf->Ln($fieldsSpacing);
			$pdf->SetFont($font,'I',$fontSize);
			$pdf->Cell(20 ,$fieldsSpacing,'',0,0);
			$pdf->Cell(40 ,$fieldsSpacing,'(Surname)','T',0, 'L');
			$pdf->Cell(38 ,$fieldsSpacing,'(First Name)','T',0, 'L');
			$pdf->Cell(12 ,$fieldsSpacing,'(M.I.)','T R',0, 'L');
			$pdf->Cell(1 ,($fieldsSpacing*2)-1,'','L',0);

			$pdf->SetY($pdf->GetY()+1);
			$pdf->SetX($X+2);
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->Cell(5, 5, $check2, 0, 0);
			$pdf->SetX($X+2);
			$pdf->Cell(4 ,4,'',1,0, 'C');
			$pdf->SetFont($font,'B',$fontSize-1);
			$pdf->SetY($pdf->GetY()-1);
			$pdf->SetX($X+8);
			$pdf->MultiCell(35 ,4,'Old Returning Students','R','L');

			$pdf->SetY($pdf->GetY());
			$pdf->Cell(153,1,'','T',1);
			$pdf->SetY($pdf->GetY()-1);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->MultiCells(25,$fieldsSpacing,'  Date of Birth:',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(34,$fieldsSpacing*2,$DOBirth,1,0,'C');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(13,$fieldsSpacing*2,'Age:',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(7,$fieldsSpacing*2,$Age,1,0,'C');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(13,$fieldsSpacing*2,'Sex:',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(5,$fieldsSpacing*2,strtoupper(substr($Sex, 0, 1)),1,0,'C');
			$X = $pdf->GetX();
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(56,$fieldsSpacing*2,'Degree:',1,0,'L');
			$pdf->SetX($X+16);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(39,$fieldsSpacing*2,$Course,0,0,'L');
			$pdf->Ln($fieldsSpacing*2);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(25,1+$fieldsSpacing*2,'Address',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(10,$fieldsSpacing+1,$PresentAddress[0],'T B',0,'C');
			$pdf->Cell(33,$fieldsSpacing+1,$PresentAddress[1],'T B',0,'C');
			$pdf->Cell(30,$fieldsSpacing+1,$PresentAddress[2],'T B',0,'C');
			$pdf->Cell(25,$fieldsSpacing+1,$PresentAddress[3],'T B',0,'C');
			$pdf->Cell(30,$fieldsSpacing+1,$PresentAddress[4],'T B R',0,'C');
			$pdf->Ln($fieldsSpacing+1);
			$pdf->Cell(25,$fieldsSpacing,'',0,0,'C');
			$pdf->SetFont($font,'I',$fontSize-2);
			$pdf->Cell(10,$fieldsSpacing,'(House No.)','T B',0,'L');
			$pdf->Cell(33,$fieldsSpacing,'(Street)','T B',0,'C');
			$pdf->Cell(32,$fieldsSpacing,'(Barangay)','T B',0,'C');
			$pdf->Cell(25,$fieldsSpacing,'(Municipality)','T B',0,'C');
			$pdf->Cell(28,$fieldsSpacing,'(Province)','T B R',0,'C');
			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(25,$fieldsSpacing,'Contact No.','T B L',0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(128,$fieldsSpacing,$StudentContNum,'T B R',0,'L');

			$pdf->Ln($fieldsSpacing);
			$pdf->SetY($pdf->GetY()+1);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50,$fieldsSpacing,' Name of Parent Guardian:',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(80,$fieldsSpacing,$ParentOrGuardian,1,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(25,$fieldsSpacing,'Contact No.',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(40,$fieldsSpacing,$PGContNum,1,0,'L');

			$pdf->Ln($fieldsSpacing);
			$pdf->SetTextColor(255,0,0);
			$pdf->SetFont($font,'B',$fontSize-1);
			$pdf->Cell(66,$fieldsSpacing,' Person to CONTACT in case of emergency','T L B',0,'L');
			$pdf->SetFont($font,'BI',$fontSize-1);
			$pdf->Cell(129,$fieldsSpacing,'if Parent/Guardian are not available:','T R B',0,'L');

			$pdf->Ln($fieldsSpacing);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(66,$fieldsSpacing,'Name',1,0,'C');
			$pdf->Cell(40,$fieldsSpacing,'Contact No.',1,0,'C');
			$pdf->Cell(30,$fieldsSpacing,'Please identify if: ','L T B',0);
			$pdf->SetFont($font,'I',$fontSize);
			$pdf->Cell(59,$fieldsSpacing,'Cousin, Landlord/Landlady etc.','R B T',0);

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(66,$fieldsSpacing,$ContactPerson1,1,0,'L');
			$pdf->Cell(40,$fieldsSpacing,$PGContactNumber1,1,0,'L');
			$pdf->Cell(89,$fieldsSpacing,$GPCategory1,1,0,'L');

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(66,$fieldsSpacing,$ContactPerson2,1,0,'L');
			$pdf->Cell(40,$fieldsSpacing,$PGContactNumber2,1,0,'L');
			$pdf->Cell(89,$fieldsSpacing,$GPCategory2,1,0,'L');

			$pdf->Ln($fieldsSpacing);

			$hereby = 'I hereby certify that the above information provided is complete and true and correct to the best of my knowledge.';
			$pdf->SetFont($font,'BI',$fontSize-.5);
			$pdf->Cell(195,$fieldsSpacing,$hereby,0,0,'L');

			$pdf->Ln($fieldsSpacing+2);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(130,$fieldsSpacing,'Signature:',0,0,'R');
			$pdf->Cell(30,$fieldsSpacing,'','B',0);
			$pdf->Cell(13,$fieldsSpacing,'Date:',0,0,'R');
			$pdf->Cell(22,$fieldsSpacing,'','B');




			$pdf->Ln($fieldsSpacing);

			$fieldsSpacing = 4.3;
			$fontSize = '9.5';
			
			/////////////////////////////////////////////////////
			$pdf->SetFont($font,'BU',$fontSize+2);
			$pdf->Cell(195 ,6,'PERTINENT PAST MEDICAL HISTORY:',0,0,'C');//end of line

			$pdf->Ln($fieldsSpacing+1);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(12 ,$fieldsSpacing,'LMP:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(58 ,$fieldsSpacing, ucwords($LMP) ,'B',0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(23 ,$fieldsSpacing,'Pregnancy:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(102 ,$fieldsSpacing, $Pregnancy ,'B',0,'L');
			$pdf->Ln($fieldsSpacing);
			$pdf->Cell(196 ,$fieldsSpacing,'' ,'B',0,'L');
			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,$fieldsSpacing,'Allergies:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(175 ,$fieldsSpacing, $Allergies ,'B',0,'L');

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(21 ,$fieldsSpacing,'Surgeries:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(174 ,$fieldsSpacing, $Surgeries ,'B',0,'L');

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(18 ,$fieldsSpacing,'Injuries:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(177 ,$fieldsSpacing, $Injuries ,'B',0,'L');

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(16 ,$fieldsSpacing,'Illness:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(179 ,$fieldsSpacing, $Illness ,'B',0,'L');
			$pdf->Ln($fieldsSpacing);
			$pdf->Cell(195 ,$fieldsSpacing,'','B',0);


			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(16 ,$fieldsSpacing,'Others:','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(179 ,$fieldsSpacing, $PastOthers ,'B',0,'L');

			$pdf->Ln($fieldsSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(77,$fieldsSpacing,'Reason for leave of Absence (LOA) for ORS: ','B',0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(118 ,$fieldsSpacing, $RLOA ,'B',0,'L');

			$pdf->Ln($fieldsSpacing+1);

			$tblSpacing = 4.3;

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'SCHOOL YEAR ',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(36 ,$tblSpacing,$SchoolYear ,'T B',0,'L');

			$check1 = "";
			$check2 = "";
			$check3 = "";
			if($term == 'First Semester'){
				$check1 = "3";
				$check2 = " ";
				$check3 = " ";
			}else if($term == 'Midyear'){
				$check1 = " ";
				$check2 = "3";
				$check3 = " ";
			}else if($term == 'Second Semester'){
				$check1 = " ";
				$check2 = " ";
				$check3 = "3";
			}

			$pdf->SetFont('ZapfDingbats','U', 10);
			$pdf->Cell(10 ,$tblSpacing,'   '.$check1.'   ','T B',0,'R');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(30 ,$tblSpacing,'First Semester' ,'T B',0,'L');
			$pdf->SetFont('ZapfDingbats','U', 10);
			$pdf->Cell(10 ,$tblSpacing,'   '.$check2.'   ','T B',0,'R');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,'Midyear' ,'T B',0,'L');
			$pdf->SetFont('ZapfDingbats','U', 10);
			$pdf->Cell(10 ,$tblSpacing,'   '.$check3.'   ' ,'T B',0,'R');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(30 ,$tblSpacing,'Second Semester' ,'T B R',0,'L');

			$pdf->Ln($tblSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'Height',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(10 ,$tblSpacing,'Cms.' ,'T B',0,'L');
			$pdf->Cell(136 ,$tblSpacing,$Height,'T R B',0,'L');


			$pdf->Ln($tblSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'Weight',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(10 ,$tblSpacing,'Kgs.' ,'T B',0,'L');
			$pdf->Cell(136 ,$tblSpacing,$Weight,'T R B',0,'L');

			$pdf->Ln($tblSpacing);

			$check1 = '';
			$check2 = '';
			$check3 = '';
			$check4 = '';
			$check5 = '';
			$check6 = '';
			/*if($BMINum = ''){
				$check1 = '';
				$check2 = '';
				$check3 = '';
				$check4 = '';
				$check5 = '';
				$check6 = '';
			}else*/ if($BMINum < 18.5 && $BMINum != ''){
        $check1 = '';
				$check2 = '3';
				$check3 = '';
				$check4 = '';
				$check5 = '';
				$check6 = '';
      }else if($BMINum >= 18.5 && $BMINum <= 24.9){
        $check1 = '3';
				$check2 = '';
				$check3 = '';
				$check4 = '';
				$check5 = '';
				$check6 = '';
      }else if($BMINum >= 25 && $BMINum <= 29.9){
        $check1 = '';
				$check2 = '';
				$check3 = '3';
				$check4 = '';
				$check5 = '';
				$check6 = '';
      }else if($BMINum >= 30 && $BMINum <= 34.9){
        $check1 = '';
				$check2 = '';
				$check3 = '';
				$check4 = '3';
				$check5 = '';
				$check6 = '';
      }else if($BMINum >= 35 && $BMINum <= 39.9){
        $check1 = '';
				$check2 = '';
				$check3 = '';
				$check4 = '';
				$check5 = '3';
				$check6 = '';
      }else if($BMINum > 40){
        $check1 = '';
				$check2 = '';
				$check3 = '';
				$check4 = '';
				$check5 = '';
				$check6 = '3';
      }


			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'BMI',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,$BMINum,'T B',0,'C');

			$Y = $pdf->GetY();

			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check1,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(10 ,$tblSpacing,'NW',0,0);


			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check2,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(10 ,$tblSpacing,'UW',0,0);

			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check3,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(15 ,$tblSpacing,'OW',0,0);

			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check4,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,'Obese I',0,0);

			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check5,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,'Obese II',0,0);

			//get x coords
			$X = $pdf->GetX();
			//spacing
			$pdf->Cell(5 ,$tblSpacing,'',0,0,'L');
			$pdf->SetY($Y+.5);
			$pdf->SetX($X+1);
			//checkbox
			$pdf->Cell(3 ,3,'',1,0);
			//check mark
			$pdf->SetFont('ZapfDingbats','', 10);
			$pdf->SetX($X+.5);
			$pdf->Cell(3 ,3,$check6,0,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(30 ,$tblSpacing,'Morbidly Obese','R',0);

			$pdf->SetY($pdf->GetY()+.5);



			$pdf->Ln($tblSpacing-1);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'Blood Pressure (mmhg)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(146 ,$tblSpacing,$BloodPressure,1,0,'J');
			

			$pdf->Ln($tblSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'Pulse Rate(bpm)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(146 ,$tblSpacing,$PulseRate,1,0,'J');

			$pdf->Ln($tblSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'Vision (Snellen\'s)',1,0);
			$pdf->Cell(28,$tblSpacing,'Without glasses:','T B L',0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(40 ,$tblSpacing,'OD: ' .$VisionWithoutGlassesOD,'T B',0,'L');
			$pdf->Cell(78 ,$tblSpacing,'OS: ' .$VisionWithoutGlassesOS,'T B R',0,'L');


			$pdf->Ln($tblSpacing);

			$pdf->Cell(50 ,$tblSpacing,'',1,0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(23 ,$tblSpacing,'With glasses:','T B',0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(24 ,$tblSpacing,'OD: ' .$VisionWithGlassesOD,'T B',0,'L');
			$pdf->Cell(24 ,$tblSpacing,'OS: ' .$VisionWithGlassesOS,'T B',0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(35 ,$tblSpacing,'With Contact Lenses:','T B',0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,'OD: ' .$VisionWithContLensOD,'T B',0,'L');
			$pdf->Cell(20 ,$tblSpacing,'OS: ' .$VisionWithContLensOS,'T B R',0,'L');//end of line

			$pdf->Ln($tblSpacing);

			$lines = preg_split('/\n/',$TAHearingDistance);
			$TAHeight = count($lines) * $tblSpacing; 
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Hearing Distance',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($HearingDistance == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($HearingDistance),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAHearingDistance,1,0,'J');
			}
			

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TASpeech);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Speech',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Speech == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Speech),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TASpeech,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAHead);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {

				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Head',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Head == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Head),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAHead,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAEyes);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Eyes (Conjunctiva)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Eyes == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Eyes),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAEyes,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAEars);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Ears',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Ears == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Ears),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAEars,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TANose);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Nose',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Nose == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Nose),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TANose,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TACavityAndThroat);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			/*$pdf->Cell(0 ,10,'',0,1,'C');	//used
			$pdf->Cell(0 ,10,'',0,1,'C');	//used
			$pdf->Cell(0 ,2,'',0,1,'C');	//used
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders*/
			$pdf->SetFont($font,'B',$fontSize);
			/*$pdf->Cell(0 ,25,'',1,1,'C');*/
			$pdf->Cell(50 ,$TAHeight,'Buccal Cavity & Throat',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($BCT == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($BCT),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TACavityAndThroat,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TALungs);
			$TAHeight1 = count($lines) * $tblSpacing;
			$lines = preg_split('/\n/',$TAHeart);
			$TAHeight2 = count($lines) * $tblSpacing; 
			$lines = preg_split('/\n/',$TABreast);
			$TAHeight3 = count($lines) * $tblSpacing; 
			//total height for Thorax Cell
			$TATotalHeight = ($TAHeight1 + $TAHeight2 + $TAHeight3);

			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,$tblSpacing,'THORAX:','T L B',0,'C');
			$pdf->Cell(30 ,$TAHeight,'Lungs','T R B',0,'C');
			$pdf->SetFont($font,'',$fontSize);
			if($Lungs == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Lungs),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TALungs,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->Cell(30 ,.01,'',0,0,'J');//removes unwanted borders inside thorax
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,$TAHeight2,'','T L B',0);
			$pdf->Cell(30 ,$TAHeight2,'Heart','T R B',0,'C');
			$pdf->SetFont($font,'',$fontSize);
			if($Heart == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Heart),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAHeart,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(30 ,.01,'',0,0,'J');//removes unwanted borders inside thorax
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->Cell(20 ,$TAHeight3,'','T L B',0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(30 ,$TAHeight3,'Breast','T R B',0,'C');
			$pdf->SetFont($font,'',$fontSize);
			if($Breast == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Breast),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TABreast,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAAbdomen);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Abdomen',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Abdomen == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Abdomen),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAAbdomen,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAGenitoUrinary);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight*2,'Genito-urinary',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($GenitoUrinary == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight*2,ucwords($GenitoUrinary),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing*2,$TAGenitoUrinary,1,0,'J');
			}

			$pdf->Ln($tblSpacing*2);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TALymphGlands);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Lymph glands',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Lymph == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Lymph),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TALymphGlands,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TASkin);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Skin',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Skin == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Skin),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TASkin,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAExtremities);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Extremities',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Extremities == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Extremities),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAExtremities,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TADeformities);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Deformities',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Deformities == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Deformities),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TADeformities,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$Y = $pdf->GetY();
			if (($Y+5) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$tblSpacing,'LABORATORY EXAMS:',1,0,'L');
			$pdf->Cell(146 ,$tblSpacing,'',1,0,'L');

			$pdf->Ln($tblSpacing);

			$lines = preg_split('/\n/',$TARadiologicExams);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->Cell(50 ,$TAHeight,'  Radiologic Exams',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($RadExams == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($RadExams),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TARadiologicExams,1,0,'J');
			}

			$pdf->Ln($tblSpacing);

			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			
			/*$pdf->Cell(0 ,10,'',0,1,'C');//extra space at bottom to prevent cell breaking
			$pdf->Cell(0 ,7,'',0,1,'C');//extra space at bottom to prevent cell breaking

			$pdf->Cell(0 ,2,'',0,1,'C');//extra space at to for border
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders*/

			$lines = preg_split('/\n/',$TABloodAnalysis);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'  Blood Analysis (CBC)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($BloodAnalysis == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($BloodAnalysis),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TABloodAnalysis,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAUrinalysis);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'  Urinalysis',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Urinalysis == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Urinalysis),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAUrinalysis,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAFecalysis);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'  Fecalysis',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($Fecalysis == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($Fecalysis),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAFecalysis,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAPregnancyTest);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'  PregnancyTest',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($PregnancyTest == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($PregnancyTest),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAPregnancyTest,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$lines = preg_split('/\n/',$TAHBSAg);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'  HBSAg',1,0);
			$pdf->SetFont($font,'',$fontSize);
			if($HBSAg == 'unremarkable'){
				$pdf->Cell(146 ,$TAHeight,ucwords($HBSAg),1,0,'J');
			}else{
				$pdf->MultiCells(146 ,$tblSpacing,$TAHBSAg,1,0,'J');
			}

			$pdf->Ln($tblSpacing);
			
			$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			/*$lines = preg_split('/\n/',$Others);
			$TAHeight = count($lines) * $tblSpacing; 
			$Y = $pdf->GetY();
			if (($Y+$TAHeight+20) > $pageHeight) {
				 $pdf->AddPage('P', [215.9, 330.2]);
				 $pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders
			}
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,$TAHeight,'Others',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(146 ,$tblSpacing,ucwords($Others),0,0,'J');
			$pdf->Cell(0 ,$TAHeight,'',0,0);//end of line

			$pdf->Ln($tblSpacing);*/

			//$pdf->Cell(0 ,.01,'',1,1,'C');//used to prevent invisible borders

			$LastInfoSpacing = 4;
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(22 ,$LastInfoSpacing,'REMARKS: ',0,0);
			$pdf->Ln($LastInfoSpacing);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(196 ,$LastInfoSpacing,$Remarks,'B',0,'L');//end of line

			$pdf->Ln($LastInfoSpacing);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(40 ,$LastInfoSpacing,'RECOMMENDATION: ',0,0);
			$pdf->Ln($LastInfoSpacing);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->MultiCells(196 ,$LastInfoSpacing,$Recommendation,'B',0,'J');//end of line

			$pdf->Ln($LastInfoSpacing*1.5);

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(55 ,$LastInfoSpacing,'Physician\'s Name & Signature: ',0,0,);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(80 ,$LastInfoSpacing,'','B',0,'C');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(30 ,$LastInfoSpacing,'License Number: ',0,0,);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(31 ,$LastInfoSpacing,'','B',0,'L');//end of line

			$pdf->Ln($LastInfoSpacing*1.5);

			$pdf->SetFont($font,'',$fontSize-1);
			$pdf->SetTextColor(255,0,0);
			$pdf->Cell(118 ,$LastInfoSpacing,'Released QF Form:',0,0,'R');
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(29 ,$LastInfoSpacing,'QH-UHS-MC-03-21-',0,0,'R');
			$pdf->SetFont($font,'',$fontSize-1);
			$pdf->Cell(10 ,$LastInfoSpacing,'','B',0,'C');
			$pdf->Cell(29 ,$LastInfoSpacing,'QH-UHS-MC-03-21-',0,0,'R');
			$pdf->SetFont($font,'',$fontSize-1);
			$pdf->Cell(10 ,$LastInfoSpacing,'','B',0,'C');

			



			//output the result
			$pdf->Output($StudentIDNumber .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      }
  }



?>