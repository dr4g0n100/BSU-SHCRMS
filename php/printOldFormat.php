<?php
//call the FPDF library
require_once('../fpdf/fpdf.php');
require_once 'Database.php';
require_once '../php/centralConnection.php';
require('../fpdf/mem_image.php');

date_default_timezone_set('Asia/Manila');



$idnumber = $_GET['id'];
$type = $_GET['type'];
/*$idnumber = '1900495';
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
        $sql = "SELECT * FROM archivedstudent  WHERE StudentIDNumber = '$ID'";
      }else if ($type == 'viewRecord' || $type == 'newRecord'){
        $sql = "SELECT * FROM PersonalMedicalRecord  WHERE StudentIDNumber = '$ID'";
      }

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        
            $DocumentCode = stripslashes($Row['DocumentCode']);;
            $RevisionNumber = stripslashes($Row['RevisionNumber']);;
            $Effectivity = stripslashes($Row['Effectivity']);;
            	$TxtNoLabel = stripslashes($Row['NoLabel']);;

            $isNullStudentImage = true;
            if ($Row['StudentImage'] != null) {
            	  $isNullStudentImage = false;
            }
            $StudentImage = $Row['StudentImage'];
            	/*$StudentImage = $Row['StudentImage'];*/
            $StudentIDNumber = stripslashes($Row['StudentIDNumber']);;
            	$TxtStudentCategory = stripslashes($Row['StudentCategory']);;
            $Course = stripslashes($Row['Course']);;
            	$TxtYear = stripslashes($Row['Year']);;
            	$TxtSection = stripslashes($Row['Section']);;
            $LastName = stripslashes($Row['Lastname']);;
            $FirstName = stripslashes($Row['Firstname']);;
            $MiddleName = stripslashes($Row['Middlename']);;
            	$TxtExtension = stripslashes($Row['Extension']);;
            $Age = stripslashes($Row['Age']);;
            $DOBirth = stripslashes($Row['Birthdate']);;
            $Sex = stripslashes($Row['Sex']);;
            $PresentAddress = stripslashes($Row['Address']);;
            $StudentContNum = stripslashes($Row['StudentContactNumber']);;
            	$RadGuardianParent = stripslashes($Row['GuardianParent']);;
            	$TxtGPCategory = stripslashes($Row['GPCategory']);;
            $ParentOrGuardian = stripslashes($Row['ContactPerson']);;
            $PGContNum = stripslashes($Row['PGContactNumber']);;
            	$RadGuardianParent1 = stripslashes($Row['GuardianParent1']);;
            	$TxtGPCategory1 = stripslashes($Row['GPCategory1']);;
            	$TxtContactPerson1 = stripslashes($Row['ContactPerson1']);;
            	$TxtPGContactNumber1 = stripslashes($Row['PGContactNumber1']);;
            $Date = stripslashes($Row['Date']);;
            	$TxtTime = stripslashes($Row['Time']);;
            $LicenseNumber = stripslashes($Row['StaffIDNumber']);;
            $PhysicianName = stripslashes($Row['StaffFirstname']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffMiddlename']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffLastname']);;
            $PhysicianName .= ' ' .stripslashes($Row['StaffExtension']);;
            $LMP = stripslashes($Row['LMP']);;
            $Pregnancy = stripslashes($Row['Pregnancy']);;
            $Allergies = stripslashes($Row['Allergies']);;
            $Surgeries = stripslashes($Row['Surgeries']);;
            $Injuries = stripslashes($Row['Injuries']);;
            $Illness = stripslashes($Row['Illness']);;
            $SchoolYear = stripslashes($Row['SchoolYear']);;
            $Height = stripslashes($Row['Height']);;
            $Weight = stripslashes($Row['Weight']);;
            $BMI = stripslashes($Row['BMI']);;
            $BloodPressure = stripslashes($Row['BloodPressure']);;
            	$TxtTemperature = stripslashes($Row['Temperature']);;
            $PulseRate = stripslashes($Row['PulseRate']);;
            $VisionWithoutGlassesOD = stripslashes($Row['VisionWithoutGlassesOD']);;
            $VisionWithoutGlassesOS = stripslashes($Row['VisionWithoutGlassesOS']);;
            $VisionWithGlassesOD = stripslashes($Row['VisionWithGlassesOD']);;
            $VisionWithGlassesOS = stripslashes($Row['VisionWithGlassesOS']);;
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


	            /*$TAHearingDistance = htmlentities($Row['TAHearingDistance']);
	            $TAHearingDistance = str_replace("<br />", "&#13;&#10;", nl2br($TAHearingDistance));
	            $TASpeech = htmlentities($Row['TASpeech']);
	            $TASpeech = str_replace("<br />", "&#13;&#10;", nl2br($TASpeech));
	            $TAEyes = htmlentities($Row['TAEyes']);
	            $TAEyes = str_replace("<br />", "&#13;&#10;", nl2br($TAEyes));
	            $TAEars = htmlentities($Row['TAEars']);
	            $TAEars = str_replace("<br />", "&#13;&#10;", nl2br($TAEars));
	            $TANose = htmlentities($Row['TANose']);
	            $TANose = str_replace("<br />", "&#13;&#10;", nl2br($TANose));
	            $TAHead = htmlentities($Row['TAHead']);
	            $TAHead = str_replace("<br />", "&#13;&#10;", nl2br($TAHead));
	            $TAAbdomen = htmlentities($Row['TAAbdomen']);
	            $TAAbdomen = str_replace("<br />", "&#13;&#10;", nl2br($TAAbdomen));
	            $TAGenitoUrinary = htmlentities($Row['TAGenitoUrinary']);
	            $TAGenitoUrinary = str_replace("<br />", "&#13;&#10;", nl2br($TAGenitoUrinary));
	            $TALymphGlands = htmlentities($Row['TALymphGlands']);
	            $TALymphGlands = str_replace("<br />", "&#13;&#10;", nl2br($TALymphGlands));
	            $TASkin = htmlentities($Row['TASkin']);
	            $TASkin = str_replace("<br />", "&#13;&#10;", nl2br($TASkin));
	            $TAExtremities = htmlentities($Row['TAExtremities']);
	            $TAExtremities = str_replace("<br />", "&#13;&#10;", nl2br($TAExtremities));
	            $TADeformities = htmlentities($Row['TADeformities']);
	            $TADeformities = str_replace("<br />", "&#13;&#10;", nl2br($TADeformities));
	            $TACavityAndThroat = htmlentities($Row['TACavityAndThroat']);
	            $TACavityAndThroat = str_replace("<br />", "&#13;&#10;", nl2br($TACavityAndThroat));
	            $TALungs = htmlentities($Row['TALungs']);
	            $TALungs = str_replace("<br />", "&#13;&#10;", nl2br($TALungs));
	            $TAHeart = htmlentities($Row['TAHeart']);
	            $TAHeart = str_replace("<br />", "&#13;&#10;", nl2br($TAHeart));
	            $TABreast = htmlentities($Row['TABreast']);
	            $TABreast = str_replace("<br />", "&#13;&#10;", nl2br($TABreast));
	            $TARadiologicExams = htmlentities($Row['TARadiologicExams']);
	            $TARadiologicExams = str_replace("<br />", "&#13;&#10;", nl2br($TARadiologicExams));
	            $TABloodAnalysis = htmlentities($Row['TABloodAnalysis']);
	            $TABloodAnalysis = str_replace("<br />", "&#13;&#10;", nl2br($TABloodAnalysis));
	            $TAUrinalysis = htmlentities($Row['TAUrinalysis']);
	            $TAUrinalysis = str_replace("<br />", "&#13;&#10;", nl2br($TAUrinalysis));
	            $TAFecalysis = htmlentities($Row['TAFecalysis']);
	            $TAFecalysis = str_replace("<br />", "&#13;&#10;", nl2br($TAFecalysis));
	            $TAPregnancyTest = htmlentities($Row['TAPregnancyTest']);
	            $TAPregnancyTest = str_replace("<br />", "&#13;&#10;", nl2br($TAPregnancyTest));
	            $TAHBSAg = htmlentities($Row['TAHBSAg']);
	            $TAHBSAg = str_replace("<br />", "&#13;&#10;", nl2br($TAHBSAg));

	            $TxtOthers = htmlentities($Row['TAOthers']);
	            $TxtOthers = str_replace("<br />", "&#13;&#10;", nl2br($TxtOthers));*/
            $Recommendation = htmlentities($Row['TARecommendation']);
            
            $Remarks = htmlentities($Row['TARemarks']);
            

            $Message = "Search completed!";
            $Error = "0";

            //writable horizontal : 219-(10*2)=189mm
			//create pdf object
			$pdf = new FPDF('P','mm','Legal');

			$pdf = new PDF_MemImage();
			//add new page
			$pdf->AddPage();

			$font = 'Arial';
			$fontSize = '11';

			$ProvincialAddress = 'Placeholder Address';


			/*$DocumentCode = 'QF-UHS-01';
			$RevisionNumber = '1';
			$Effectivity = 'January 1, 2020';
			$Date = 'January 16, 2023';
			$Course = 'Bachelor of Science in Information Technology';
			$Age = '23';
			$Sex = 'Male';
			$LastName = 'Cadungo';
			$FirstName = 'Edrian Joepen';
			$MiddleName = 'A.';
			$DOBirth = 'April 7, 1999';
			$PresentAddress = '#59, Cypress, Irisan, Baguio City, Benguet';
			$ProvincialAddress = 'Sabangan, Sinait, Ilocos Sur';
			$ParentOrGuardian = 'Ma. Angeline Balais Almazan';
			$PGContNum = '+639457148887';
			$StudentContNum = '+639457148887';
			$LMP = 'N/A';
			$Pregnancy = 'N/A';
			$Allergies = 'Empty Wallet';
			$Surgeries = 'N/A';
			$Injuries = 'N/A';
			$Illness = 'Financial';
			$SchoolYear = '2022-2023';
			$Height = '160';
			$Weight = '65';
			$BMI = 'Normal';
			$BloodPressure = '143';
			$PulseRate = '120/90';
			$VisionWithoutGlassesOD = '20';
			$VisionWithoutGlassesOS = '20';
			$VisionWithGlassesOD = '30';
			$VisionWithGlassesOS = '30';
			$HearingDistance = '2 Meters';
			$Speech = 'Good';
			$Head = 'Good';
			$Eyes = 'Good';
			$Ears = 'Good';
			$Nose = 'Good';
			$BCT = 'Good';
			$Lungs = 'Good';
			$Heart = 'Good';
			$Breast = 'Good';
			$Abdomen = 'Good';
			$GenitoUrinary = 'Good';
			$Lymph = 'Good';
			$Skin = 'Good';
			$Extremities = 'Good';
			$Deformities = 'Good';
			$RadExams = 'None';
			$BloodAnalysis = 'None';
			$Urinalysis = 'None';
			$Fecalysis = 'None';
			$PregnancyTest = 'None';
			$HBSAg = 'None';
			$Remarks = 'None'; 
			$Recommendation = 'None NoneNoneNone NoneNone Non eNoneN oneNone NoneNoneNone NoneNon eNoneNoneNoneNoneNone NoneNoneNone NoneNone Non eNoneN oneNone NoneNoneNone NoneNon eNoneNoneNoneNoneNone NoneNoneNone NoneNone Non eNoneN oneNone NoneNoneNone NoneNon eNoneNoneNoneNone';
			$StudentSignature = '___________________________________';
			$PhysicianName = '___________________________________';
			$LicenseNumber = '000000000000';*/



			//set font to arial, bold, 14pt
			$pdf->SetFont($font,'B',$fontSize);

			//Cell(width , height , text , border , end line , [align] )
			$pdf->Image('../images/BSULogo.png',18,3,26,26);
			$pdf->Cell(33 ,20,'',0,0);
			$pdf->MultiCells(36 ,5,'PHYSICAL EXAMINATION',0,0,'C');
			$pdf->MultiCells(23, 5, 'Document Code:', 1,0, 'L'); 
			$pdf->Cell(30, 10, strtoupper($DocumentCode), 1, 0, 'C'); 
			$pdf->MultiCells(22, 5, 'Revision Number:', 1);
			$pdf->Cell(10 ,10,$RevisionNumber,1,0); 
			$pdf->Cell(2, 5, '',0);
			if ($isNullStudentImage == true) {
					$pdf->Image('../images/id picture.png',166,5, 40, 40);
			}else{
					$pdf->MemImage($StudentImage,166,5, 40, 40);
			}
			
			$pdf->Cell(35 ,35,'',0,0);
			$pdf->Cell(0, 10, '',0,1); //end of line

			$pdf->Cell(33 ,10,'',0,0);
			$pdf->Cell(36 ,10,'',0,0);
			$pdf->Cell(23, 10, 'Effectivity:', 1); 
			$pdf->Cell(30, 10, ucwords($Effectivity), 1,0,'C'); 
			$pdf->Cell(32, 10, 'Page 1 of 1', 1,0,'C');
			$pdf->Cell(0, 10, '',0,1); //end of line

			$pdf->Cell(20 ,5,'DATE: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(40 ,5, $Date ,0,1); //end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,10,'COURSE: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(90 ,5, ucwords($Course) ,0,0,'L'); 
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(10 ,10,'Age: ',0,0, 'L');
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(10 ,10, $Age ,0,0,'L'); 
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(10 ,10,'Sex: ',0,0, 'L');
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(20 ,10, ucwords($Sex) ,0,1,'L'); //end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,5,'NAME: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(30 ,5, ucwords($LastName) ,0,0,'C');
			$pdf->Cell(40 ,5, ucwords($FirstName) ,0,0,'C');
			$pdf->Cell(20 ,5, ucwords($MiddleName) ,0,0,'C');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(35 ,10,'DATE OF BIRTH: ',0,0, 'R');
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(40 ,10, $DOBirth ,0,0,'L');
			$pdf->Cell(0 ,5,'',0,1);//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,5,'',0,0);
			$pdf->Cell(30 ,5, '(Family Name)' ,0,0,'C');
			$pdf->Cell(40 ,5, '(First Name)' ,0,0,'C');
			$pdf->Cell(20 ,5, '(M.I.)' ,0,1,'C');//end of line

			$pdf->Cell(43 ,5,'PRESENT ADDRESS: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(150 ,5, ucwords($PresentAddress) ,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(48 ,5,'PROVINCIAL ADDRESS: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(145 ,5, ucwords($ProvincialAddress) ,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(60 ,5,'NAME OF PARENT/GUARDIAN: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(133 ,5, ucwords($ParentOrGuardian) ,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(85 ,5,'CONTACT NUMBER OF PARENT/GUARDIAN: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(32 ,5, $PGContNum ,0,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(40 ,5,'Contact # of Student: ',0,0,'R');
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(32 ,5, $StudentContNum ,0,1,'L');//end of line

			$pdf->Cell(195 ,5,'',0,1,'C');//end of line
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(195 ,5,'PERTINENT PAST MEDICAL HISTORY:',0,1,'C');//end of line

			$pdf->Cell(12 ,5,'LMP:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(53 ,5, ucwords($LMP) ,0,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(23 ,5,'Pregnancy:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(126 ,5, $Pregnancy ,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,5,'Allergies:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(45 ,5, $Allergies ,0,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(21 ,5,'Surgeries:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(112 ,5, $Surgeries ,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(18 ,5,'Injuries:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(47 ,5, $Injuries ,0,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(16 ,5,'Illness:',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(112 ,5, $Illness ,0,1,'L');//end of line

			$pdf->Cell(195 ,5,'',0,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'SCHOOL YEAR ',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$SchoolYear ,1,0,'C');
			$pdf->Cell(73 ,5,$SchoolYear ,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Height',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Height . ' Cms' ,1,0,'C');
			$pdf->Cell(73 ,5,$Height . ' Cms',1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Weight',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Weight . ' Kgs',1,0,'C');
			$pdf->Cell(73 ,5,$Weight . ' Kgs',1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'BMI',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$BMI,1,0,'C');
			$pdf->Cell(73 ,5,$BMI,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Blood Pressure',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$BloodPressure,1,0,'C');
			$pdf->Cell(73 ,5,$BloodPressure,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Pulse Rate',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$PulseRate,1,0,'C');
			$pdf->Cell(73 ,5,$PulseRate,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,10,'Vision (Snellen\'s)',1,0);
			$pdf->Cell(33 ,5,'Without glasses:',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,5,'OD: ' .$VisionWithoutGlassesOD,1,0,'L');
			$pdf->Cell(20 ,5,'OS: ' .$VisionWithoutGlassesOS,1,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(33 ,5,'Without glasses:',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,5,'OD: ' .$VisionWithGlassesOD,1,0,'L');
			$pdf->Cell(20 ,5,'OS: ' .$VisionWithGlassesOS,1,1,'L');//end of line

			$pdf->Cell(50 ,5,'',0,0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(33 ,5,'With glasses:',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,5,'OD: ' .$VisionWithoutGlassesOD,1,0,'L');
			$pdf->Cell(20 ,5,'OS: ' .$VisionWithoutGlassesOS,1,0,'L');
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(33 ,5,'With glasses:',1,0,'L');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(20 ,5,'OD: ' .$VisionWithGlassesOD,1,0,'L');
			$pdf->Cell(20 ,5,'OS: ' .$VisionWithGlassesOS,1,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Hearing Distance',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$HearingDistance,1,0,'C');
			$pdf->Cell(73 ,5,$HearingDistance,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Speech',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Speech,1,0,'C');
			$pdf->Cell(73 ,5,$Speech,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Head',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Head,1,0,'C');
			$pdf->Cell(73 ,5,$Head,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Eyes (Conjunctiva)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Eyes,1,0,'C');
			$pdf->Cell(73 ,5,$Eyes,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Ears',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Ears,1,0,'C');
			$pdf->Cell(73 ,5,$Ears,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Nose',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Nose,1,0,'C');
			$pdf->Cell(73 ,5,$Nose,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->MultiCells(50 ,5,'BUCCAL CAVITY AND THROAT:',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,10,$BCT,1,0,'C');
			$pdf->Cell(73 ,10,$BCT,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,15,'THORAX:',1,0,'C');
			$pdf->Cell(30 ,5,'Lungs',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Lungs,1,0,'C');
			$pdf->Cell(73 ,5,$Lungs,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(20 ,5,'',0,0);
			$pdf->Cell(30 ,5,'Heart',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Heart,1,0,'C');
			$pdf->Cell(73 ,5,$Heart,1,1,'C');//end of line

			$pdf->Cell(20 ,5,'',0,0);
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(30 ,5,'Breast',1,0,'C');
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Breast,1,0,'C');
			$pdf->Cell(73 ,5,$Breast,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Abdomen',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Abdomen,1,0,'C');
			$pdf->Cell(73 ,5,$Abdomen,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Genito-urinary',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$GenitoUrinary,1,0,'C');
			$pdf->Cell(73 ,5,$GenitoUrinary,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Lymph glands',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Lymph,1,0,'C');
			$pdf->Cell(73 ,5,$Lymph,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Skin',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Skin,1,0,'C');
			$pdf->Cell(73 ,5,$Skin,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Extremities',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Extremities,1,0,'C');
			$pdf->Cell(73 ,5,$Extremities,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'Deformities',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Deformities,1,0,'C');
			$pdf->Cell(73 ,5,$Deformities,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'LABORATORY EXAMS:',1,0);
			$pdf->Cell(73 ,5,'',1,0,'C');
			$pdf->Cell(73 ,5,'',1,1,'C');//end of line

			$pdf->Cell(50 ,5,'   Radiologic Exams',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$RadExams,1,0,'C');
			$pdf->Cell(73 ,5,$RadExams,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'   Blood Analysis (CBC)',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$BloodAnalysis,1,0,'C');
			$pdf->Cell(73 ,5,$BloodAnalysis,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'   Urinalysis',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Urinalysis,1,0,'C');
			$pdf->Cell(73 ,5,$Urinalysis,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'   Fecalysis',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$Fecalysis,1,0,'C');
			$pdf->Cell(73 ,5,$Fecalysis,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'   PregnancyTest',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$PregnancyTest,1,0,'C');
			$pdf->Cell(73 ,5,$PregnancyTest,1,1,'C');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(50 ,5,'   HBSAg',1,0);
			$pdf->SetFont($font,'',$fontSize);
			$pdf->Cell(73 ,5,$HBSAg,1,0,'C');
			$pdf->Cell(73 ,5,$HBSAg,1,1,'C');//end of line

			$pdf->Cell(0 ,5,'',0,1);//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(24 ,5,'REMARKS: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(172 ,5,$Remarks,0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(42 ,15,'RECOMMENDATION: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->MultiCells(154 ,5,$Recommendation,0,1,'J');//end of line

			$pdf->Cell(0 ,5,'',0,1);//end of line
			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(42 ,5,'Student\'s Signature: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(172 ,5,ucwords($StudentSignature),0,1,'L');//end of line

			$pdf->Cell(0 ,5,'',0,1);//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(63 ,5,'Physician\'s Name and Signature: ',0,0);
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(172 ,5,ucwords($PhysicianName),0,1,'L');//end of line

			$pdf->SetFont($font,'B',$fontSize);
			$pdf->Cell(60 ,5,'License Number: ',0,0,'R');
			$pdf->SetFont($font,'U',$fontSize);
			$pdf->Cell(172 ,5,$LicenseNumber,0,1,'L');//end of line

			//output the result
			$pdf->Output($StudentIDNumber .'.pdf','I');

            
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      }
  }



?>