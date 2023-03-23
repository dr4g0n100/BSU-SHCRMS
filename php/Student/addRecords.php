<?php
  require_once 'Database.php';
  require '../centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  
    if($_FILES["TxtStudentImage"]["tmp_name"])
    { 
      $TxtStudentImage = addslashes(file_get_contents($_FILES["TxtStudentImage"]["tmp_name"])); 
    } 
    else
    {
      $TxtStudentImage = 'null'; 
    }
    $TxtDocumentCode = $_POST['TxtDocumentCode'];
    $TxtRevisionNumber = $_POST['TxtRevisionNumber'];
    $TxtEffectivity = $_POST['TxtEffectivity'];
    $TxtNoLabel = $_POST['TxtNoLabel'];
    $TxtStudentIDNumber = $_POST['TxtStudentIDNumber'];
    $RadStatus = $_POST['RadStatus'];
    $TxtStudentCategory = $_POST['TxtStudentCategory'];
    $TxtCourse = $_POST['TxtCourse'];
    $TxtYear = $_POST['TxtYear'];
    $TxtSection = $_POST['TxtSection'];
    $TxtLastname = $_POST['TxtLastname'];
    $TxtFirstname = $_POST['TxtFirstname'];
    $TxtMiddlename = $_POST['TxtMiddlename'];
    $TxtExtension = $_POST['TxtExtension'];
    $TxtAge = $_POST['TxtAge'];
    $TxtBirthdate = $_POST['TxtBirthdate'];
    $RadSex = $_POST['RadSex'];

    $TxtAddress = $_POST['TxtAddress'];

    $TxtProvAdd = $_POST['TxtProvAdd'];

    $TxtStudentContactNumber = $_POST['TxtStudentContactNumber'];
    $RadGuardianParent = $_POST['RadGuardianParent'];
    $TxtGPCategory = $_POST['TxtGPCategory'];
    $TxtContactPerson = $_POST['TxtContactPerson'];
    $TxtPGContactNumber = $_POST['TxtPGContactNumber'];
    $RadGuardianParent1 = $_POST['RadGuardianParent1'];
    $TxtGPCategory1 = $_POST['TxtGPCategory1'];
    $TxtContactPerson1 = $_POST['TxtContactPerson1'];
    $TxtPGContactNumber1 = $_POST['TxtPGContactNumber1'];

    $RadGuardianParent2 = $_POST['RadGuardianParent2'];
    $TxtGPCategory2 = $_POST['TxtGPCategory2'];
    $TxtContactPerson2 = $_POST['TxtContactPerson2'];
    $TxtPGContactNumber2 = $_POST['TxtPGContactNumber2'];

    $TxtDate = $_POST['TxtDate'];
    $TxtTime = $_POST['TxtTime'];
    $TxtStaffIDNumber = $_POST['userID'];
    $TxtStaffLastname = $_POST['userLN'];
    $TxtStaffFirstname = $_POST['userFN'];
    $TxtStaffMiddlename = $_POST['userMN'];
    $TxtStaffExtension = $_POST['userEN'];
    $TxtLMP = $_POST['TxtLMP'];
    $TxtPregnancy = $_POST['TxtPregnancy'];
    $TxtAllergies = $_POST['TxtAllergies'];
    $TxtSurgeries = $_POST['TxtSurgeries'];
    $TxtInjuries = $_POST['TxtInjuries'];
    $TxtIllness = $_POST['TxtIllness'];
    $TxtMedicalOthers = $_POST['TxtMedicalOthers'];
    $TxtRLOA = $_POST['TxtRLOA'];
    $TxtSchoolYear = $_POST['TxtSchoolYear'];

    if(!empty($_POST['TxtStudentTerm'])){
      $TxtStudentTerm = $_POST['TxtStudentTerm'];
    }else{
      $TxtStudentTerm = "";
    }
    

    $TxtHeight = $_POST['TxtHeight'];
    $TxtWeight = $_POST['TxtWeight'];
    $TxtBMI = $_POST['TxtBMI'];
    $TxtBloodPressure = $_POST['TxtBloodPressure'];
    $TxtTemperature = $_POST['TxtTemperature'];
    $TxtPulseRate = $_POST['TxtPulseRate'];
    $TxtVisionWithoutGlassesOD = $_POST['TxtVisionWithoutGlassesOD'];
    $TxtVisionWithoutGlassesOS = $_POST['TxtVisionWithoutGlassesOS'];
    $TxtVisionWithGlassesOD = $_POST['TxtVisionWithGlassesOD'];
    $TxtVisionWithGlassesOS = $_POST['TxtVisionWithGlassesOS'];

    $TxtVisionWithContLensOD = $_POST['TxtVisionWithContLensOD'];
    $TxtVisionWithContLensOS = $_POST['TxtVisionWithContLensOS'];
    

    $TxtHearingDistanceOption = $_POST['TxtHearingDistanceOption'];
    $TxtSpeechOption = $_POST['TxtSpeechOption'];
    $TxtEyesOption = $_POST['TxtEyesOption'];
    $TxtEarsOption = $_POST['TxtEarsOption'];
    $TxtNoseOption = $_POST['TxtNoseOption'];
    $TxtHeadOption = $_POST['TxtHeadOption'];
    $TxtAbdomenOption = $_POST['TxtAbdomenOption'];
    $TxtGenitoUrinaryOption = $_POST['TxtGenitoUrinaryOption'];
    $TxtLymphGlandsOption = $_POST['TxtLymphGlandsOption'];
    $TxtSkinOption = $_POST['TxtSkinOption'];
    $TxtExtremitiesOption = $_POST['TxtExtremitiesOption'];
    $TxtDeformitiesOption = $_POST['TxtDeformitiesOption'];
    $TxtCavityAndThroatOption = $_POST['TxtCavityAndThroatOption'];
    $TxtLungsOption = $_POST['TxtLungsOption'];
    $TxtHeartOption = $_POST['TxtHeartOption'];
    $TxtBreastOption = $_POST['TxtBreastOption'];
    $TxtRadiologicExamsOption = $_POST['TxtRadiologicExamsOption'];
    $TxtBloodAnalysisOption = $_POST['TxtBloodAnalysisOption'];
    $TxtUrinalysisOption = $_POST['TxtUrinalysisOption'];
    $TxtFecalysisOption = $_POST['TxtFecalysisOption'];
    $TxtPregnancyTestOption = $_POST['TxtPregnancyTestOption'];
    $TxtHBSAgOption = $_POST['TxtHBSAgOption'];
    $TAHearingDistance = $_POST['TAHearingDistance'];
    $TASpeech = $_POST['TASpeech'];
    $TAEyes = $_POST['TAEyes'];
    $TAEars = $_POST['TAEars'];
    $TANose = $_POST['TANose'];
    $TAHead = $_POST['TAHead'];
    $TAAbdomen = $_POST['TAAbdomen'];
    $TAGenitoUrinary = $_POST['TAGenitoUrinary'];
    $TALymphGlands = $_POST['TALymphGlands'];
    $TASkin = $_POST['TASkin'];
    $TAExtremities = $_POST['TAExtremities'];
    $TADeformities = $_POST['TADeformities'];
    $TACavityAndThroat = $_POST['TACavityAndThroat'];
    $TALungs = $_POST['TALungs'];
    $TAHeart = $_POST['TAHeart'];
    $TABreast = $_POST['TABreast'];
    $TARadiologicExams = $_POST['TARadiologicExams'];
    $TABloodAnalysis = $_POST['TABloodAnalysis'];
    $TAUrinalysis = $_POST['TAUrinalysis'];
    $TAFecalysis = $_POST['TAFecalysis'];
    $TAPregnancyTest = $_POST['TAPregnancyTest'];
    $TAHBSAg = $_POST['TAHBSAg'];

    $TxtOthers = $_POST['TxtOthers'];
    $TxtRecommendation = $_POST['TxtRecommendation'];
    $TxtRemarks = $_POST['TxtRemarks'];

    $TxtMSEditor = $_POST['TxtMSEditor'];
    $TxtUserEdit = $_POST['TxtUserEdit'];
    $TxtEditDate = $_POST['TxtEditDate'];
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($TxtStudentIDNumber);
      }
      else
      {
        $Message = 'Failed to add information!';
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
	$XMLData .= ' Result = ' . '"'.$Message.'"';
  $XMLData .= ' Error = ' . '"'.$Error.'"';
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function CheckRecord($IDNum){
    $IDNumber = $IDNum;
    $sql;

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $tab, $Error;  
    
      $sql = "SELECT StudentIDNumber FROM PersonalMedicalRecord WHERE StudentIDNumber = '$IDNumber' ";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'The record already exist. If you want to edit the record, please click the old button.';
            $Error = "1";     
        }else{
            StoreData();
        }       
      }
  }

  function StoreData()
  {
      global $ClinicRecordsDB, $Message, $Error, $TxtStudentImage, $TxtStudentIDNumber, $TxtStudentCategory, $TxtCourse, $TxtYear, $TxtSection, $TxtLastname, $TxtFirstname, $TxtMiddlename, $TxtExtension, $TxtAge, $TxtBirthdate, $RadSex, $TxtAddress,$TxtProvAdd, $TxtStudentContactNumber, $RadGuardianParent, $TxtGPCategory, $TxtContactPerson, $TxtPGContactNumber, $RadStatus, $RadGuardianParent1, $TxtGPCategory1, $TxtContactPerson1, $TxtPGContactNumber1, $RadGuardianParent2, $TxtGPCategory2, $TxtContactPerson2, $TxtPGContactNumber2, $TxtDate, $TxtTime , $TxtStaffIDNumber, $TxtStaffLastname, $TxtStaffFirstname, $TxtStaffMiddlename, $TxtStaffExtension, $TxtLMP, $TxtPregnancy, $TxtAllergies, $TxtSurgeries, $TxtInjuries, $TxtIllness,$TxtMedicalOthers,$TxtRLOA, $TxtSchoolYear,$TxtStudentTerm, $TxtHeight, $TxtWeight, $TxtBMI, $TxtBloodPressure, $TxtTemperature, $TxtPulseRate, $TxtVisionWithoutGlassesOD, $TxtVisionWithoutGlassesOS, $TxtVisionWithGlassesOD, $TxtVisionWithGlassesOS,$TxtVisionWithContLensOD,$TxtVisionWithContLensOS, $TxtRemarks, $TxtMSEditor,$TxtUserEdit,$TxtEditDate, $TxtRecommendation, $TxtOthers, $TxtHearingDistanceOption, $TxtSpeechOption, $TxtEyesOption, $TxtEarsOption, $TxtNoseOption, $TxtHeadOption, $TxtAbdomenOption, $TxtGenitoUrinaryOption, $TxtLymphGlandsOption, $TxtSkinOption, $TxtExtremitiesOption, $TxtDeformitiesOption, $TxtCavityAndThroatOption, $TxtLungsOption, $TxtHeartOption, $TxtBreastOption, $TxtHBSAgOption, $TxtPregnancyTestOption, $TxtFecalysisOption, $TxtUrinalysisOption, $TxtBloodAnalysisOption, $TxtRadiologicExamsOption, $TAHearingDistance, $TASpeech, $TAEyes, $TAEars, $TANose, $TAHead, $TAAbdomen, $TAGenitoUrinary, $TALymphGlands, $TASkin, $TAExtremities, $TADeformities, $TACavityAndThroat, $TALungs, $TAHeart, $TABreast, $TAHBSAg, $TAPregnancyTest, $TAFecalysis, $TAUrinalysis, $TABloodAnalysis, $TARadiologicExams, $TxtDocumentCode, $TxtRevisionNumber, $TxtEffectivity, $TxtNoLabel;

      /*$RadStatus = strtolower($RadStatus);
      $TxtStudentCategory = strtolower($TxtStudentCategory);
      $TxtCourse = strtolower($TxtCourse);
      $TxtSection = strtolower($TxtSection);
      $TxtLastname = strtolower($TxtLastname);
      $TxtFirstname = strtolower($TxtFirstname);
      $TxtMiddlename = strtolower($TxtMiddlename);
      $TxtExtension = strtolower($TxtExtension);
      $RadSex = strtolower($RadSex);
      $TxtAddress = strtolower($TxtAddress);
      $RadGuardianParent = strtolower($RadGuardianParent);
      $TxtGPCategory = strtolower($TxtGPCategory);
      $TxtContactPerson = strtolower($TxtContactPerson);
      $RadGuardianParent1 = strtolower($RadGuardianParent1);
      $TxtGPCategory1 = strtolower($TxtGPCategory1);
      $TxtContactPerson1 = strtolower($TxtContactPerson1);
      
      $TxtStaffLastname = strtolower($TxtStaffLastname);
      $TxtStaffFirstname = strtolower($TxtStaffFirstname);
      $TxtStaffMiddlename = strtolower($TxtStaffMiddlename);
      $TxtStaffExtension = strtolower($TxtStaffExtension);
      $TxtLMP = strtolower($TxtLMP);
      $TxtPregnancy = strtolower($TxtPregnancy);
      $TxtAllergies = strtolower($TxtAllergies);
      $TxtSurgeries = strtolower($TxtSurgeries);
      $TxtInjuries = strtolower($TxtInjuries);
      $TxtIllness = strtolower($TxtIllness);
      $TxtVisionWithoutGlassesOD = strtolower($TxtVisionWithoutGlassesOD);
      $TxtVisionWithoutGlassesOS = strtolower($TxtVisionWithoutGlassesOS);
      $TxtVisionWithGlassesOD = strtolower($TxtVisionWithGlassesOD);
      $TxtVisionWithGlassesOS = strtolower($TxtVisionWithGlassesOS);
      $TxtHearingDistanceOption = strtolower($TxtHearingDistanceOption);
      $TxtSpeechOption = strtolower($TxtSpeechOption);
      $TxtEyesOption = strtolower($TxtEyesOption);
      $TxtEarsOption = strtolower($TxtEarsOption);
      $TxtNoseOption = strtolower($TxtNoseOption);
      $TxtHeadOption = strtolower($TxtHeadOption);
      $TxtAbdomenOption = strtolower($TxtAbdomenOption);
      $TxtGenitoUrinaryOption = strtolower($TxtGenitoUrinaryOption);
      $TxtLymphGlandsOption = strtolower($TxtLymphGlandsOption);
      $TxtSkinOption = strtolower($TxtSkinOption);
      $TxtExtremitiesOption = strtolower($TxtExtremitiesOption);
      $TxtDeformitiesOption = strtolower($TxtDeformitiesOption);
      $TxtCavityAndThroatOption = strtolower($TxtCavityAndThroatOption);
      $TxtLungsOption = strtolower($TxtLungsOption);
      $TxtHeartOption = strtolower($TxtHeartOption);
      $TxtBreastOption = strtolower($TxtBreastOption);
      $TxtHBSAgOption = strtolower($TxtHBSAgOption);
      $TxtPregnancyTestOption = strtolower($TxtPregnancyTestOption);
      $TxtFecalysisOption = strtolower($TxtFecalysisOption);
      $TxtUrinalysisOption = strtolower($TxtUrinalysisOption);
      $TxtBloodAnalysisOption = strtolower($TxtBloodAnalysisOption);
      $TxtRadiologicExamsOption = strtolower($TxtRadiologicExamsOption);*/

      if($TxtStudentCategory == "elementary" || $TxtStudentCategory == "junior highschool"){
        $TxtCourse = "";
      }      

      $sql = "INSERT INTO PersonalMedicalRecord 
              (StudentIDNumber,StudentImage,Status,StudentCategory,Course,Year,Section,Lastname,Firstname,Middlename,Extension,Age,Birthdate,Sex,Address,ProvAdd,StudentContactNumber,GuardianParent,GPCategory,ContactPerson,PGContactNumber,GuardianParent1,GPCategory1,ContactPerson1,PGContactNumber1,GuardianParent2,GPCategory2,ContactPerson2,PGContactNumber2,Date,Time,StaffIDNumber,StaffLastname,StaffFirstname,StaffMiddlename,StaffExtension,LMP,Pregnancy,Allergies,Surgeries,Injuries,Illness,MedicalOthers,RLOA,SchoolYear,Term,Height,Weight,BMI,BloodPressure,Temperature,PulseRate,VisionWithoutGlassesOD,VisionWithoutGlassesOS,VisionWithGlassesOD,VisionWithGlassesOS,VisionWithContLensOD,VisionWithContLensOS,HearingDistanceOpt,TAHearingDistance,SpeechOpt,TASpeech,EyesOpt,TAEyes,EarsOpt,TAEars,NoseOpt,TANose,HeadOpt,TAHead,AbdomenOpt,TAAbdomen,GenitoUrinaryOpt,TAGenitoUrinary,LymphGlandsOpt,TALymphGlands,SkinOpt,TASkin,ExtremitiesOpt,TAExtremities,DeformitiesOpt,TADeformities,CavityAndThroatOpt,TACavityAndThroat,LungsOpt,TALungs,HeartOpt,TAHeart,BreastOpt,TABreast,RadiologicExamsOpt,TARadiologicExams,BloodAnalysisOpt,TABloodAnalysis,UrinalysisOpt,TAUrinalysis,FecalysisOpt,TAFecalysis,PregnancyTestOpt,TAPregnancyTest,HBSAgOpt,TAHBSAg,TAOthers,TARemarks,stu_record_edit,stu_editor,stu_edited_at,TARecommendation,DocumentCode,RevisionNumber,Effectivity,NoLabel,created_at) VALUES ('$TxtStudentIDNumber',NULLIF('$TxtStudentImage','null'),'$RadStatus','$TxtStudentCategory','$TxtCourse','$TxtYear','$TxtSection','$TxtLastname','$TxtFirstname','$TxtMiddlename','$TxtExtension','$TxtAge','$TxtBirthdate','$RadSex','$TxtAddress','$TxtProvAdd','$TxtStudentContactNumber','$RadGuardianParent','$TxtGPCategory','$TxtContactPerson','$TxtPGContactNumber','$RadGuardianParent1','$TxtGPCategory1','$TxtContactPerson1','$TxtPGContactNumber1','$RadGuardianParent2','$TxtGPCategory2','$TxtContactPerson2','$TxtPGContactNumber2','$TxtDate','$TxtTime','$TxtStaffIDNumber','$TxtStaffLastname','$TxtStaffFirstname','$TxtStaffMiddlename','$TxtStaffExtension','$TxtLMP','$TxtPregnancy','$TxtAllergies','$TxtSurgeries','$TxtInjuries','$TxtIllness','$TxtMedicalOthers','$TxtRLOA','$TxtSchoolYear','$TxtStudentTerm','$TxtHeight','$TxtWeight','$TxtBMI','$TxtBloodPressure','$TxtTemperature','$TxtPulseRate','$TxtVisionWithoutGlassesOD','$TxtVisionWithoutGlassesOS','$TxtVisionWithGlassesOD','$TxtVisionWithGlassesOS','$TxtVisionWithContLensOD','$TxtVisionWithContLensOS','$TxtHearingDistanceOption','$TAHearingDistance','$TxtSpeechOption','$TASpeech','$TxtEyesOption','$TAEyes','$TxtEarsOption','$TAEars','$TxtNoseOption','$TANose','$TxtHeadOption','$TAHead','$TxtAbdomenOption','$TAAbdomen','$TxtGenitoUrinaryOption','$TAGenitoUrinary','$TxtLymphGlandsOption','$TALymphGlands','$TxtSkinOption','$TASkin','$TxtExtremitiesOption','$TAExtremities','$TxtDeformitiesOption','$TADeformities','$TxtCavityAndThroatOption','$TACavityAndThroat','$TxtLungsOption','$TALungs','$TxtHeartOption','$TAHeart','$TxtBreastOption','$TABreast','$TxtRadiologicExamsOption','$TARadiologicExams','$TxtBloodAnalysisOption','$TABloodAnalysis','$TxtUrinalysisOption','$TAUrinalysis','$TxtFecalysisOption','$TAFecalysis','$TxtPregnancyTestOption','$TAPregnancyTest','$TxtHBSAgOption','$TAHBSAg','$TxtOthers','$TxtRemarks','$TxtMSEditor','$TxtUserEdit','$TxtEditDate','$TxtRecommendation','$TxtDocumentCode','$TxtRevisionNumber','$TxtEffectivity','$TxtNoLabel',CURRENT_TIMESTAMP)";  

      $Result = $ClinicRecordsDB->GetRows($sql);
      if ($Result) {
        $Message = 'Successfully added the information!'; 
        $Error = "0";
      }else{
        $Message = 'Database Storing Error!'; 
        $Error = "1";
      }

      $sql = "INSERT INTO history_personalmedical 
              (StudentIDNumber,DocumentCode,RevisionNumber,Effectivity,NoLabel,StudentImage,Status,StudentCategory,Course,Year,Section,Lastname,Firstname,Middlename,Extension,Age,Birthdate,Sex,Address,ProvAdd,StudentContactNumber,GuardianParent,GPCategory,ContactPerson,PGContactNumber,GuardianParent1,GPCategory1,ContactPerson1,PGContactNumber1,GuardianParent2,GPCategory2,ContactPerson2,PGContactNumber2,Date,Time,StaffIDNumber,StaffLastname,StaffFirstname,StaffMiddlename,StaffExtension,LMP,Pregnancy,Allergies,Surgeries,Injuries,Illness,MedicalOthers,RLOA,SchoolYear,Height,Weight,BMI,BloodPressure,Temperature,PulseRate,VisionWithoutGlassesOD,VisionWithoutGlassesOS,VisionWithGlassesOD,VisionWithGlassesOS,VisionWithContLensOD,VisionWithContLensOS,HearingDistanceOpt,TAHearingDistance,SpeechOpt,TASpeech,EyesOpt,TAEyes,EarsOpt,TAEars,NoseOpt,TANose,HeadOpt,TAHead,AbdomenOpt,TAAbdomen,GenitoUrinaryOpt,TAGenitoUrinary,LymphGlandsOpt,TALymphGlands,SkinOpt,TASkin,ExtremitiesOpt,TAExtremities,DeformitiesOpt,TADeformities,CavityAndThroatOpt,TACavityAndThroat,LungsOpt,TALungs,HeartOpt,TAHeart,BreastOpt,TABreast,RadiologicExamsOpt,TARadiologicExams,BloodAnalysisOpt,TABloodAnalysis,UrinalysisOpt,TAUrinalysis,FecalysisOpt,TAFecalysis,PregnancyTestOpt,TAPregnancyTest,HBSAgOpt,TAHBSAg,TAOthers,TARemarks,TARecommendation,stu_record_edit,stu_editor,stu_edited_at,created_at,updated_at,archived_at) 
            SELECT 
              StudentIDNumber,DocumentCode,RevisionNumber,Effectivity,NoLabel,StudentImage,Status,StudentCategory,Course,Year,Section,Lastname,Firstname,Middlename,Extension,Age,Birthdate,Sex,Address,ProvAdd,StudentContactNumber,GuardianParent,GPCategory,ContactPerson,PGContactNumber,GuardianParent1,GPCategory1,ContactPerson1,PGContactNumber1,GuardianParent2,GPCategory2,ContactPerson2,PGContactNumber2,Date,Time,StaffIDNumber,StaffLastname,StaffFirstname,StaffMiddlename,StaffExtension,LMP,Pregnancy,Allergies,Surgeries,Injuries,Illness,MedicalOthers,RLOA,SchoolYear,Height,Weight,BMI,BloodPressure,Temperature,PulseRate,VisionWithoutGlassesOD,VisionWithoutGlassesOS,VisionWithGlassesOD,VisionWithGlassesOS,VisionWithContLensOD,VisionWithContLensOS,HearingDistanceOpt,TAHearingDistance,SpeechOpt,TASpeech,EyesOpt,TAEyes,EarsOpt,TAEars,NoseOpt,TANose,HeadOpt,TAHead,AbdomenOpt,TAAbdomen,GenitoUrinaryOpt,TAGenitoUrinary,LymphGlandsOpt,TALymphGlands,SkinOpt,TASkin,ExtremitiesOpt,TAExtremities,DeformitiesOpt,TADeformities,CavityAndThroatOpt,TACavityAndThroat,LungsOpt,TALungs,HeartOpt,TAHeart,BreastOpt,TABreast,RadiologicExamsOpt,TARadiologicExams,BloodAnalysisOpt,TABloodAnalysis,UrinalysisOpt,TAUrinalysis,FecalysisOpt,TAFecalysis,PregnancyTestOpt,TAPregnancyTest,HBSAgOpt,TAHBSAg,TAOthers,TARemarks,TARecommendation,stu_record_edit,stu_editor,stu_edited_at,created_at,updated_at,archived_at
            FROM personalmedicalrecord 
            WHERE StudentIDNumber = '$TxtStudentIDNumber'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);
          
  }
?>
