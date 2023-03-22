<?php
require_once 'Database.php';
require '../../../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";
  $type="";

  $idnumber = $_POST['idnumber'];
  $type = $_POST['type'];

  $TxtDocumentCode = "";
  $TxtRevisionNumber = "";
  $TxtEffectivity = "";
  $TxtNoLabel = "";
    $TxtStudentImage = "";
    $TxtStudentIDNumber = "";
    $TxtStudentCategory = "";
    $TxtCourse = "";
    $TxtYear = "";
    $TxtSection = "";
    $TxtLastname = "";
    $TxtFirstname = "";
    $TxtMiddlename = "";
    $TxtExtension = "";
    $TxtAge = "";
    $TxtBirthdate = "";
    $RadSex = "";
    $TxtAddress = "";
    $TxtStudentContactNumber = "";
    $RadGuardianParent = "";
    $TxtGPCategory = "";
    $TxtContactPerson = "";
    $TxtPGContactNumber = ""; 
    $RadGuardianParent1 = "";
    $TxtGPCategory1 = "";
    $TxtContactPerson1 = "";
    $TxtPGContactNumber1 = ""; 
    $TxtDate = "";
    $TxtTime = "";
    $TxtStaffIDNumber = "";
    $TxtStaffLastname = "";
    $TxtStaffFirstname = "";
    $TxtStaffMiddlename = "";
    $TxtStaffExtension = "";
    $TxtLMP = "";
    $TxtPregnancy = "";
    $TxtAllergies = "";
    $TxtSurgeries = "";
    $TxtInjuries = "";
    $TxtIllness = "";
    $TxtSchoolYear = "";
    $TxtHeight = "";
    $TxtWeight = "";
    $TxtBMI = "";
    $TxtBloodPressure = "";
    $TxtTemperature = "";
    $TxtPulseRate = "";
    $TxtVisionWithoutGlassesOD = "";
    $TxtVisionWithoutGlassesOS = "";
    $TxtVisionWithGlassesOD = "";
    $TxtVisionWithGlassesOS = "";

    $TxtHearingDistanceOption = ""; 
    $TxtSpeechOption = ""; 
    $TxtEyesOption = ""; 
    $TxtEarsOption = ""; 
    $TxtNoseOption = ""; 
    $TxtHeadOption = ""; 
    $TxtAbdomenOption = ""; 
    $TxtGenitoUrinaryOption = ""; 
    $TxtLymphGlandsOption = ""; 
    $TxtSkinOption = ""; 
    $TxtExtremitiesOption = ""; 
    $TxtDeformitiesOption = ""; 
    $TxtCavityAndThroatOption = ""; 
    $TxtLungsOption = ""; 
    $TxtHeartOption = ""; 
    $TxtBreastOption = ""; 
    $TxtRadiologicExamsOption = ""; 
    $TxtBloodAnalysisOption = ""; 
    $TxtUrinalysisOption = ""; 
    $TxtFecalysisOption = ""; 
    $TxtPregnancyTestOption = ""; 
    $TxtHBSAgOption = ""; 
    $TAHearingDistance = ""; 
    $TASpeech = ""; 
    $TAEyes = ""; 
    $TAEars = ""; 
    $TANose = ""; 
    $TAHead = ""; 
    $TAAbdomen = ""; 
    $TAGenitoUrinary = ""; 
    $TALymphGlands = ""; 
    $TASkin = ""; 
    $TAExtremities = ""; 
    $TADeformities = ""; 
    $TACavityAndThroat = ""; 
    $TALungs = ""; 
    $TAHeart = ""; 
    $TABreast = ""; 
    $TARadiologicExams = ""; 
    $TABloodAnalysis = ""; 
    $TAUrinalysis = ""; 
    $TAFecalysis = ""; 
    $TAPregnancyTest = ""; 
    $TAHBSAg = ""; 

    $TxtOthers = ""; 
    $TxtRemarks = "";
    $TxtRecommendation = "";
    
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
  $XMLData .= ' Error = ' . '"'.$Error.'"';
  $XMLData .= ' DocumentCode = ' . '"'.$TxtDocumentCode.'"';
  $XMLData .= ' RevisionNumber = ' . '"'.$TxtRevisionNumber.'"';
  $XMLData .= ' Effectivity = ' . '"'.$TxtEffectivity.'"';
  $XMLData .= ' NoLabel = ' . '"'.$TxtNoLabel.'"';
  $XMLData .= ' StudentImage = ' . '"'.$TxtStudentImage.'"';
  $XMLData .= ' StudentIDNumber = ' . '"'.$TxtStudentIDNumber.'"';
  $XMLData .= ' StudentCategory = ' . '"'.$TxtStudentCategory.'"';
  $XMLData .= ' Course = ' . '"'.$TxtCourse.'"';
  $XMLData .= ' Year = ' . '"'.$TxtYear.'"';
  $XMLData .= ' Section = ' . '"'.$TxtSection.'"';
  $XMLData .= ' Lastname = ' . '"'.$TxtLastname.'"';
  $XMLData .= ' Firstname = ' . '"'.$TxtFirstname.'"';
  $XMLData .= ' Middlename = ' . '"'.$TxtMiddlename.'"';
  $XMLData .= ' Extension = ' . '"'.$TxtExtension.'"';
  $XMLData .= ' Age = ' . '"'.$TxtAge.'"';
  $XMLData .= ' Birthdate = ' . '"'.$TxtBirthdate.'"';
  $XMLData .= ' Sex = ' . '"'.$RadSex.'"';
  $XMLData .= ' Address = ' . '"'.$TxtAddress.'"';
  $XMLData .= ' StudentContactNumber = ' . '"'.$TxtStudentContactNumber.'"';
  $XMLData .= ' GuardianParent = ' . '"'.$RadGuardianParent.'"';
  $XMLData .= ' GPCategory = ' . '"'.$TxtGPCategory.'"';
  $XMLData .= ' ContactPerson = ' . '"'.$TxtContactPerson.'"';
  $XMLData .= ' PGContactNumber = ' . '"'.$TxtPGContactNumber.'"';
  $XMLData .= ' GuardianParent1 = ' . '"'.$RadGuardianParent1.'"';
  $XMLData .= ' GPCategory1 = ' . '"'.$TxtGPCategory1.'"';
  $XMLData .= ' ContactPerson1 = ' . '"'.$TxtContactPerson1.'"';
  $XMLData .= ' PGContactNumber1 = ' . '"'.$TxtPGContactNumber1.'"';
  $XMLData .= ' Date = ' . '"'.$TxtDate.'"';
  $XMLData .= ' Time = ' . '"'.$TxtTime.'"';
  $XMLData .= ' StaffIDNumber = ' . '"'.$TxtStaffIDNumber.'"';
  $XMLData .= ' StaffLastname = ' . '"'.$TxtStaffLastname.'"';
  $XMLData .= ' StaffFirstname = ' . '"'.$TxtStaffFirstname.'"';
  $XMLData .= ' StaffMiddlename = ' . '"'.$TxtStaffMiddlename.'"';
  $XMLData .= ' StaffExtension = ' . '"'.$TxtStaffExtension.'"';
  $XMLData .= ' LMP = ' . '"'.$TxtLMP.'"';
  $XMLData .= ' Pregnancy = ' . '"'.$TxtPregnancy.'"';
  $XMLData .= ' Allergies = ' . '"'.$TxtAllergies.'"';
  $XMLData .= ' Surgeries = ' . '"'.$TxtSurgeries.'"';
  $XMLData .= ' Injuries = ' . '"'.$TxtInjuries.'"';
  $XMLData .= ' Illness = ' . '"'.$TxtIllness.'"';
  $XMLData .= ' SchoolYear = ' . '"'.$TxtSchoolYear.'"';
  $XMLData .= ' Height = ' . '"'.$TxtHeight.'"';
  $XMLData .= ' Weight = ' . '"'.$TxtWeight.'"';
  $XMLData .= ' BMI = ' . '"'.$TxtBMI.'"';
  $XMLData .= ' BloodPressure = ' . '"'.$TxtBloodPressure.'"';
  $XMLData .= ' Temperature = ' . '"'.$TxtTemperature.'"';
  $XMLData .= ' PulseRate = ' . '"'.$TxtPulseRate.'"';
  $XMLData .= ' VisionWithoutGlassesOD = ' . '"'.$TxtVisionWithoutGlassesOD.'"';
  $XMLData .= ' VisionWithoutGlassesOS = ' . '"'.$TxtVisionWithoutGlassesOS.'"';
  $XMLData .= ' VisionWithGlassesOD = ' . '"'.$TxtVisionWithGlassesOD.'"';
  $XMLData .= ' VisionWithGlassesOS = ' . '"'.$TxtVisionWithGlassesOS.'"';
  $XMLData .= ' HearingDistanceOption = ' . '"'.$TxtHearingDistanceOption.'"';
  $XMLData .= ' SpeechOption = ' . '"'.$TxtSpeechOption.'"';
  $XMLData .= ' EyesOption = ' . '"'.$TxtEyesOption.'"';
  $XMLData .= ' EarsOption = ' . '"'.$TxtEarsOption.'"';
  $XMLData .= ' NoseOption = ' . '"'.$TxtNoseOption.'"';
  $XMLData .= ' HeadOption = ' . '"'.$TxtHeadOption.'"';
  $XMLData .= ' AbdomenOption = ' . '"'.$TxtAbdomenOption.'"';
  $XMLData .= ' GenitoUrinaryOption = ' . '"'.$TxtGenitoUrinaryOption.'"';
  $XMLData .= ' LymphGlandsOption = ' . '"'.$TxtLymphGlandsOption.'"';
  $XMLData .= ' SkinOption = ' . '"'.$TxtSkinOption.'"';
  $XMLData .= ' ExtremitiesOption = ' . '"'.$TxtExtremitiesOption.'"';
  $XMLData .= ' DeformitiesOption = ' . '"'.$TxtDeformitiesOption.'"';
  $XMLData .= ' CavityAndThroatOption = ' . '"'.$TxtCavityAndThroatOption.'"';
  $XMLData .= ' LungsOption = ' . '"'.$TxtLungsOption.'"';
  $XMLData .= ' HeartOption = ' . '"'.$TxtHeartOption.'"';
  $XMLData .= ' BreastOption = ' . '"'.$TxtBreastOption.'"'; 
  $XMLData .= ' RadiologicExamsOption = ' . '"'.$TxtRadiologicExamsOption.'"';
  $XMLData .= ' BloodAnalysisOption = ' . '"'.$TxtBloodAnalysisOption.'"';
  $XMLData .= ' UrinalysisOption = ' . '"'.$TxtUrinalysisOption.'"';
  $XMLData .= ' FecalysisOption = ' . '"'.$TxtFecalysisOption.'"';
  $XMLData .= ' PregnancyTestOption = ' . '"'.$TxtPregnancyTestOption.'"';
  $XMLData .= ' HBSAgOption = ' . '"'.$TxtHBSAgOption.'"'; 
  $XMLData .= ' TAHearingDistance = ' . '"'.$TAHearingDistance.'"';
  $XMLData .= ' TASpeech = ' . '"'.$TASpeech.'"';
  $XMLData .= ' TAEyes = ' . '"'.$TAEyes.'"';
  $XMLData .= ' TAEars = ' . '"'.$TAEars.'"';
  $XMLData .= ' TANose = ' . '"'.$TANose.'"';
  $XMLData .= ' TAHead = ' . '"'.$TAHead.'"';
  $XMLData .= ' TAAbdomen = ' . '"'.$TAAbdomen.'"';
  $XMLData .= ' TAGenitoUrinary = ' . '"'.$TAGenitoUrinary.'"';
  $XMLData .= ' TALymphGlands = ' . '"'.$TALymphGlands.'"';
  $XMLData .= ' TASkin = ' . '"'.$TASkin.'"';
  $XMLData .= ' TAExtremities = ' . '"'.$TAExtremities.'"';
  $XMLData .= ' TADeformities = ' . '"'.$TADeformities.'"';
  $XMLData .= ' TACavityAndThroat = ' . '"'.$TACavityAndThroat.'"';
  $XMLData .= ' TALungs = ' . '"'.$TALungs.'"';
  $XMLData .= ' TAHeart = ' . '"'.$TAHeart.'"';
  $XMLData .= ' TABreast = ' . '"'.$TABreast.'"'; 
  $XMLData .= ' TARadiologicExams = ' . '"'.$TARadiologicExams.'"';
  $XMLData .= ' TABloodAnalysis = ' . '"'.$TABloodAnalysis.'"';
  $XMLData .= ' TAUrinalysis = ' . '"'.$TAUrinalysis.'"';
  $XMLData .= ' TAFecalysis = ' . '"'.$TAFecalysis.'"';
  $XMLData .= ' TAPregnancyTest = ' . '"'.$TAPregnancyTest.'"';
  $XMLData .= ' TAHBSAg = ' . '"'.$TAHBSAg.'"'; 
  $XMLData .= ' Others = ' . '"'.$TxtOthers.'"';
  $XMLData .= ' Remarks = ' . '"'.$TxtRemarks.'"';
  $XMLData .= ' Recommendation = ' . '"'.$TxtRecommendation.'"'; 
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function FetchUser($ID){
    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message, $TxtStudentImage, $TxtStudentIDNumber, $TxtStudentCategory, $TxtCourse, $TxtYear, $TxtSection, $TxtLastname, $TxtFirstname, $TxtMiddlename, $TxtExtension, $TxtAge, $TxtBirthdate, $RadSex, $TxtAddress, $TxtStudentContactNumber, $RadGuardianParent, $TxtGPCategory, $TxtContactPerson, $TxtPGContactNumber, $RadStatus, $RadGuardianParent1, $TxtGPCategory1, $TxtContactPerson1, $TxtPGContactNumber1, $TxtDate, $TxtTime, $TxtStaffIDNumber, $TxtStaffLastname, $TxtStaffFirstname, $TxtStaffMiddlename, $TxtStaffExtension, $TxtLMP, $TxtPregnancy, $TxtAllergies, $TxtSurgeries, $TxtInjuries, $TxtIllness, $TxtSchoolYear, $TxtHeight, $TxtWeight, $TxtBMI, $TxtBloodPressure, $TxtTemperature, $TxtPulseRate, $TxtVisionWithoutGlassesOD, $TxtVisionWithoutGlassesOS, $TxtVisionWithGlassesOD, $TxtVisionWithGlassesOS, $TxtRemarks, $TxtRecommendation, $TxtOthers, $TxtHearingDistanceOption, $TxtSpeechOption, $TxtEyesOption, $TxtEarsOption, $TxtNoseOption, $TxtHeadOption, $TxtAbdomenOption, $TxtGenitoUrinaryOption, $TxtLymphGlandsOption, $TxtSkinOption, $TxtExtremitiesOption, $TxtDeformitiesOption, $TxtCavityAndThroatOption, $TxtLungsOption, $TxtHeartOption, $TxtBreastOption, $TxtHBSAgOption, $TxtPregnancyTestOption, $TxtFecalysisOption, $TxtUrinalysisOption, $TxtBloodAnalysisOption, $TxtRadiologicExamsOption, $TAHearingDistance, $TASpeech, $TAEyes, $TAEars, $TANose, $TAHead, $TAAbdomen, $TAGenitoUrinary, $TALymphGlands, $TASkin, $TAExtremities, $TADeformities, $TACavityAndThroat, $TALungs, $TAHeart, $TABreast, $TAHBSAg, $TAPregnancyTest, $TAFecalysis, $TAUrinalysis, $TABloodAnalysis, $TARadiologicExams, $TxtDocumentCode, $TxtRevisionNumber, $TxtEffectivity, $TxtNoLabel, $type;

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
            $TxtDocumentCode = stripslashes($Row['DocumentCode']);;
            $TxtRevisionNumber = stripslashes($Row['RevisionNumber']);;
            $TxtEffectivity = stripslashes($Row['Effectivity']);;
            $TxtNoLabel = stripslashes($Row['NoLabel']);;
            $TxtStudentImage = "data:image;base64,".base64_encode($Row['StudentImage']);
            $TxtStudentIDNumber = stripslashes($Row['StudentIDNumber']);;
            $TxtStudentCategory = stripslashes($Row['StudentCategory']);;
            $TxtCourse = stripslashes($Row['Course']);;
            $TxtYear = stripslashes($Row['Year']);;
            $TxtSection = stripslashes($Row['Section']);;
            $TxtLastname = stripslashes($Row['Lastname']);;
            $TxtFirstname = stripslashes($Row['Firstname']);;
            $TxtMiddlename = stripslashes($Row['Middlename']);;
            $TxtExtension = stripslashes($Row['Extension']);;
            $TxtAge = stripslashes($Row['Age']);;
            $TxtBirthdate = stripslashes($Row['Birthdate']);;
            $RadSex = stripslashes($Row['Sex']);;
            $TxtAddress = stripslashes($Row['Address']);;
            $TxtStudentContactNumber = stripslashes($Row['StudentContactNumber']);;
            $RadGuardianParent = stripslashes($Row['GuardianParent']);;
            $TxtGPCategory = stripslashes($Row['GPCategory']);;
            $TxtContactPerson = stripslashes($Row['ContactPerson']);;
            $TxtPGContactNumber = stripslashes($Row['PGContactNumber']);;
            $RadGuardianParent1 = stripslashes($Row['GuardianParent1']);;
            $TxtGPCategory1 = stripslashes($Row['GPCategory1']);;
            $TxtContactPerson1 = stripslashes($Row['ContactPerson1']);;
            $TxtPGContactNumber1 = stripslashes($Row['PGContactNumber1']);;
            $TxtDate = stripslashes($Row['Date']);;
            $TxtTime = stripslashes($Row['Time']);;
            $TxtStaffIDNumber = stripslashes($Row['StaffIDNumber']);;
            $TxtStaffLastname = stripslashes($Row['StaffLastname']);;
            $TxtStaffFirstname = stripslashes($Row['StaffFirstname']);;
            $TxtStaffMiddlename = stripslashes($Row['StaffMiddlename']);;
            $TxtStaffExtension = stripslashes($Row['StaffExtension']);;
            $TxtLMP = stripslashes($Row['LMP']);;
            $TxtPregnancy = stripslashes($Row['Pregnancy']);;
            $TxtAllergies = stripslashes($Row['Allergies']);;
            $TxtSurgeries = stripslashes($Row['Surgeries']);;
            $TxtInjuries = stripslashes($Row['Injuries']);;
            $TxtIllness = stripslashes($Row['Illness']);;
            $TxtSchoolYear = stripslashes($Row['SchoolYear']);;
            $TxtHeight = stripslashes($Row['Height']);;
            $TxtWeight = stripslashes($Row['Weight']);;
            $TxtBMI = stripslashes($Row['BMI']);;
            $TxtBloodPressure = stripslashes($Row['BloodPressure']);;
            $TxtTemperature = stripslashes($Row['Temperature']);;
            $TxtPulseRate = stripslashes($Row['PulseRate']);;
            $TxtVisionWithoutGlassesOD = stripslashes($Row['VisionWithoutGlassesOD']);;
            $TxtVisionWithoutGlassesOS = stripslashes($Row['VisionWithoutGlassesOS']);;
            $TxtVisionWithGlassesOD = stripslashes($Row['VisionWithGlassesOD']);;
            $TxtVisionWithGlassesOS = stripslashes($Row['VisionWithGlassesOS']);;
            $TxtHearingDistanceOption = stripslashes($Row['HearingDistanceOpt']);;
            $TxtSpeechOption = stripslashes($Row['SpeechOpt']);;
            $TxtEyesOption = stripslashes($Row['EyesOpt']);;
            $TxtEarsOption = stripslashes($Row['EarsOpt']);;
            $TxtNoseOption = stripslashes($Row['NoseOpt']);;
            $TxtHeadOption = stripslashes($Row['HeadOpt']);;
            $TxtAbdomenOption = stripslashes($Row['AbdomenOpt']);;
            $TxtGenitoUrinaryOption = stripslashes($Row['GenitoUrinaryOpt']);;
            $TxtLymphGlandsOption = stripslashes($Row['LymphGlandsOpt']);;
            $TxtSkinOption = stripslashes($Row['SkinOpt']);;
            $TxtExtremitiesOption = stripslashes($Row['ExtremitiesOpt']);;
            $TxtDeformitiesOption = stripslashes($Row['DeformitiesOpt']);;
            $TxtCavityAndThroatOption = stripslashes($Row['CavityAndThroatOpt']);;
            $TxtLungsOption = stripslashes($Row['LungsOpt']);;
            $TxtHeartOption = stripslashes($Row['HeartOpt']);;
            $TxtBreastOption = stripslashes($Row['BreastOpt']);;
            $TxtRadiologicExamsOption = stripslashes($Row['RadiologicExamsOpt']);;
            $TxtBloodAnalysisOption = stripslashes($Row['BloodAnalysisOpt']);;
            $TxtUrinalysisOption = stripslashes($Row['UrinalysisOpt']);;
            $TxtFecalysisOption = stripslashes($Row['FecalysisOpt']);;
            $TxtPregnancyTestOption = stripslashes($Row['PregnancyTestOpt']);;
            $TxtHBSAgOption = stripslashes($Row['HBSAgOpt']);;

            $TAHearingDistance = htmlentities($Row['TAHearingDistance']);
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
            $TxtOthers = str_replace("<br />", "&#13;&#10;", nl2br($TxtOthers));
            $TxtRecommendation = htmlentities($Row['TARecommendation']);
            $TxtRecommendation = str_replace("<br />", "&#13;&#10;", nl2br($TxtRecommendation));
            $TxtRemarks = htmlentities($Row['TARemarks']);
            $TxtRemarks = str_replace("<br />", "&#13;&#10;", nl2br($TxtRemarks));
            $Message = "Search completed!";
            $Error = "0"; 
          }else{
            $Message = "No information found. Please try again.";
            $Error = "1";
          }            
      }
  }

?>