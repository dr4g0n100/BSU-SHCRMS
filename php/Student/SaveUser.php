<?php
  require_once 'Database.php';
  require '../centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error;

  // Receive Data from Client
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
        UpdateUser();
      }
      else
      {
        $Message = 'Failed to save information!';
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
    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';    	
    echo $XMLData;
    echo '</Document>';

    function UpdateUser(){
      $sql;

      global $ClinicRecordsDB,$Message,$Error, $TxtStudentImage, $TxtStudentIDNumber, $TxtStudentCategory, $TxtCourse, $TxtYear, $TxtSection, $TxtLastname, $TxtFirstname, $TxtMiddlename, $TxtExtension, $TxtAge, $TxtBirthdate, $RadSex, $TxtAddress,$TxtProvAdd, $TxtStudentContactNumber, $RadGuardianParent, $TxtGPCategory, $TxtContactPerson, $TxtPGContactNumber, $RadStatus, $RadGuardianParent1, $TxtGPCategory1, $TxtContactPerson1, $TxtPGContactNumber1,$RadGuardianParent2, $TxtGPCategory2, $TxtContactPerson2, $TxtPGContactNumber2, $TxtDate, $TxtTime, $TxtStaffIDNumber, $TxtStaffLastname, $TxtStaffFirstname, $TxtStaffMiddlename, $TxtStaffExtension, $TxtLMP, $TxtPregnancy, $TxtAllergies, $TxtSurgeries, $TxtInjuries, $TxtIllness,$TxtMedicalOthers,$TxtRLOA, $TxtSchoolYear,$TxtStudentTerm, $TxtHeight, $TxtWeight, $TxtBMI, $TxtBloodPressure, $TxtTemperature, $TxtPulseRate, $TxtVisionWithoutGlassesOD, $TxtVisionWithoutGlassesOS, $TxtVisionWithGlassesOD, $TxtVisionWithGlassesOS,$TxtVisionWithContLensOD,$TxtVisionWithContLensOS, $TxtRemarks,$TxtMSEditor,$TxtUserEdit,$TxtEditDate, $TxtRecommendation, $TxtOthers, $TxtHearingDistanceOption, $TxtSpeechOption, $TxtEyesOption, $TxtEarsOption, $TxtNoseOption, $TxtHeadOption, $TxtAbdomenOption, $TxtGenitoUrinaryOption, $TxtLymphGlandsOption, $TxtSkinOption, $TxtExtremitiesOption, $TxtDeformitiesOption, $TxtCavityAndThroatOption, $TxtLungsOption, $TxtHeartOption, $TxtBreastOption, $TxtHBSAgOption, $TxtPregnancyTestOption, $TxtFecalysisOption, $TxtUrinalysisOption, $TxtBloodAnalysisOption, $TxtRadiologicExamsOption, $TAHearingDistance, $TASpeech, $TAEyes, $TAEars, $TANose, $TAHead, $TAAbdomen, $TAGenitoUrinary, $TALymphGlands, $TASkin, $TAExtremities, $TADeformities, $TACavityAndThroat, $TALungs, $TAHeart, $TABreast, $TAHBSAg, $TAPregnancyTest, $TAFecalysis, $TAUrinalysis, $TABloodAnalysis, $TARadiologicExams,$TxtDocumentCode, $TxtRevisionNumber, $TxtEffectivity, $TxtNoLabel;

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

    

      if($_FILES["TxtStudentImage"]["tmp_name"])
      { 
        $TxtStudentImage = addslashes(file_get_contents($_FILES["TxtStudentImage"]["tmp_name"])); 

        $sql = "UPDATE PersonalMedicalRecord 
                SET 
                  StudentIDNumber='$TxtStudentIDNumber', 
                  StudentImage=NULLIF('$TxtStudentImage','null'), 
                  Status='$RadStatus', 
                  StudentCategory='$TxtStudentCategory', 
                  Course='$TxtCourse', 
                  Year='$TxtYear', 
                  Section='$TxtSection', 
                  Lastname='$TxtLastname', 
                  Firstname='$TxtFirstname', 
                  Middlename='$TxtMiddlename', 
                  Extension='$TxtExtension', 
                  Age='$TxtAge', 
                  Birthdate='$TxtBirthdate', 
                  Sex='$RadSex', 
                  Address='$TxtAddress',
                  ProvAdd='$TxtProvAdd', 
                  StudentContactNumber='$TxtStudentContactNumber', 
                  GuardianParent='$RadGuardianParent', 
                  GPCategory='$TxtGPCategory', 
                  ContactPerson='$TxtContactPerson', 
                  PGContactNumber='$TxtPGContactNumber', 
                  GuardianParent1='$RadGuardianParent1', 
                  GPCategory1='$TxtGPCategory1', 
                  ContactPerson1='$TxtContactPerson1', 
                  PGContactNumber1='$TxtPGContactNumber1',
                  GuardianParent2='$RadGuardianParent2', 
                  GPCategory2='$TxtGPCategory2', 
                  ContactPerson2='$TxtContactPerson2', 
                  PGContactNumber2='$TxtPGContactNumber2', 
                  Date='$TxtDate', 
                  Time='$TxtTime', 
                  StaffIDNumber='$TxtStaffIDNumber', 
                  StaffLastname='$TxtStaffLastname', 
                  StaffFirstname='$TxtStaffFirstname', 
                  StaffMiddlename='$TxtStaffMiddlename', 
                  StaffExtension='$TxtStaffExtension', 
                  LMP='$TxtLMP', 
                  Pregnancy='$TxtPregnancy', 
                  Allergies='$TxtAllergies', 
                  Surgeries='$TxtSurgeries', 
                  Injuries='$TxtInjuries', 
                  Illness='$TxtIllness',
                  MedicalOthers='$TxtMedicalOthers',
                  RLOA='$TxtRLOA', 
                  SchoolYear='$TxtSchoolYear',
                  Term='$TxtStudentTerm', 
                  Height='$TxtHeight', 
                  Weight='$TxtWeight', 
                  BMI='$TxtBMI', 
                  BloodPressure='$TxtBloodPressure', 
                  Temperature='$TxtTemperature', 
                  PulseRate='$TxtPulseRate', 
                  VisionWithoutGlassesOD='$TxtVisionWithoutGlassesOD', 
                  VisionWithoutGlassesOS='$TxtVisionWithoutGlassesOS', 
                  VisionWithGlassesOD='$TxtVisionWithGlassesOD', 
                  VisionWithGlassesOS='$TxtVisionWithGlassesOS',
                  VisionWithContLensOD='$TxtVisionWithContLensOD',
                  VisionWithContLensOS='$TxtVisionWithContLensOS', 
                  HearingDistanceOpt='$TxtHearingDistanceOption', 
                  TAHearingDistance='$TAHearingDistance', 
                  SpeechOpt='$TxtSpeechOption', 
                  TASpeech='$TASpeech', 
                  EyesOpt='$TxtEyesOption', 
                  TAEyes='$TAEyes', 
                  EarsOpt='$TxtEarsOption', 
                  TAEars='$TAEars', 
                  NoseOpt='$TxtNoseOption', 
                  TANose='$TANose',
                  HeadOpt='$TxtHeadOption', 
                  TAHead='$TAHead', 
                  AbdomenOpt='$TxtAbdomenOption', 
                  TAAbdomen='$TAAbdomen', 
                  GenitoUrinaryOpt='$TxtGenitoUrinaryOption', 
                  TAGenitoUrinary='$TAGenitoUrinary', 
                  LymphGlandsOpt='$TxtLymphGlandsOption', 
                  TALymphGlands='$TALymphGlands', 
                  SkinOpt='$TxtSkinOption', 
                  TASkin='$TASkin', 
                  ExtremitiesOpt='$TxtExtremitiesOption', 
                  TAExtremities='$TAExtremities', 
                  DeformitiesOpt='$TxtDeformitiesOption', 
                  TADeformities='$TADeformities', 
                  CavityAndThroatOpt='$TxtCavityAndThroatOption', 
                  TACavityAndThroat='$TACavityAndThroat', 
                  LungsOpt='$TxtLungsOption', 
                  TALungs='$TALungs', 
                  HeartOpt='$TxtHeartOption', 
                  TAHeart='$TAHeart', 
                  BreastOpt='$TxtBreastOption', 
                  TABreast='$TABreast', 
                  RadiologicExamsOpt='$TxtRadiologicExamsOption', 
                  TARadiologicExams='$TARadiologicExams', 
                  BloodAnalysisOpt='$TxtBloodAnalysisOption', 
                  TABloodAnalysis='$TABloodAnalysis', 
                  UrinalysisOpt='$TxtUrinalysisOption', 
                  TAUrinalysis='$TAUrinalysis', 
                  FecalysisOpt='$TxtFecalysisOption', 
                  TAFecalysis='$TAFecalysis', 
                  PregnancyTestOpt='$TxtPregnancyTestOption', 
                  TAPregnancyTest='$TAPregnancyTest', 
                  HBSAgOpt='$TxtHBSAgOption', 
                  TAHBSAg='$TAHBSAg', 
                  TAOthers='$TxtOthers', 
                  TARemarks='$TxtRemarks', 
                  stu_record_edit=CONCAT_WS('/',
                  stu_record_edit,'$TxtMSEditor'),
                  stu_editor='$TxtUserEdit',
                  stu_edited_at='$TxtEditDate', 
                  TARecommendation='$TxtRecommendation', 
                  DocumentCode='$TxtDocumentCode', 
                  RevisionNumber='$TxtRevisionNumber', 
                  Effectivity='$TxtEffectivity', 
                  NoLabel='$TxtNoLabel', 
                  created_at = CURRENT_TIMESTAMP 
                WHERE StudentIDNumber='$TxtStudentIDNumber'";

        $TxtUserEdit = $_POST['TxtUserEdit'];
        $TxtEditDate = $_POST['TxtEditDate'];

        $Result = $ClinicRecordsDB->GetRows($sql);
        if($Result){
          $Message = 'Successfully save the information!'; 
          $Error = "0";
        }else{
          $Message = 'Failed to save the Information!'; 
          $Error = "1";
        }

      } 
      else
      {
        $sql = "UPDATE PersonalMedicalRecord 
                SET 
                  StudentIDNumber='$TxtStudentIDNumber', 
                  Status='$RadStatus', 
                  StudentCategory='$TxtStudentCategory', 
                  Course='$TxtCourse', Year='$TxtYear', 
                  Section='$TxtSection', 
                  Lastname='$TxtLastname', 
                  Firstname='$TxtFirstname', 
                  Middlename='$TxtMiddlename', 
                  Extension='$TxtExtension', 
                  Age='$TxtAge', 
                  Birthdate='$TxtBirthdate', 
                  Sex='$RadSex', 
                  Address='$TxtAddress',
                  ProvAdd='$TxtProvAdd', 
                  StudentContactNumber='$TxtStudentContactNumber', 
                  GuardianParent='$RadGuardianParent', 
                  GPCategory='$TxtGPCategory', 
                  ContactPerson='$TxtContactPerson', 
                  PGContactNumber='$TxtPGContactNumber', 
                  GuardianParent1='$RadGuardianParent1', 
                  GPCategory1='$TxtGPCategory1', 
                  ContactPerson1='$TxtContactPerson1', 
                  PGContactNumber1='$TxtPGContactNumber1',
                  GuardianParent2='$RadGuardianParent2', 
                  GPCategory2='$TxtGPCategory2', 
                  ContactPerson2='$TxtContactPerson2', 
                  PGContactNumber2='$TxtPGContactNumber2', 
                  Date='$TxtDate', 
                  Time='$TxtTime', 
                  StaffIDNumber='$TxtStaffIDNumber', 
                  StaffLastname='$TxtStaffLastname', 
                  StaffFirstname='$TxtStaffFirstname', 
                  StaffMiddlename='$TxtStaffMiddlename', 
                  StaffExtension='$TxtStaffExtension', 
                  LMP='$TxtLMP', 
                  Pregnancy='$TxtPregnancy', 
                  Allergies='$TxtAllergies', 
                  Surgeries='$TxtSurgeries', 
                  Injuries='$TxtInjuries', 
                  Illness='$TxtIllness',
                  MedicalOthers='$TxtMedicalOthers',
                  RLOA='$TxtRLOA', 
                  SchoolYear='$TxtSchoolYear',
                  Term='$TxtStudentTerm', 
                  Height='$TxtHeight', 
                  Weight='$TxtWeight', 
                  BMI='$TxtBMI', 
                  BloodPressure='$TxtBloodPressure', 
                  Temperature='$TxtTemperature', 
                  PulseRate='$TxtPulseRate', 
                  VisionWithoutGlassesOD='$TxtVisionWithoutGlassesOD', 
                  VisionWithoutGlassesOS='$TxtVisionWithoutGlassesOS', 
                  VisionWithGlassesOD='$TxtVisionWithGlassesOD', 
                  VisionWithGlassesOS='$TxtVisionWithGlassesOS', 
                  VisionWithContLensOD='$TxtVisionWithContLensOD',
                  VisionWithContLensOS='$TxtVisionWithContLensOS', 
                  HearingDistanceOpt='$TxtHearingDistanceOption', 
                  TAHearingDistance='$TAHearingDistance', 
                  SpeechOpt='$TxtSpeechOption', 
                  TASpeech='$TASpeech', 
                  EyesOpt='$TxtEyesOption', 
                  TAEyes='$TAEyes', 
                  EarsOpt='$TxtEarsOption', 
                  TAEars='$TAEars', 
                  NoseOpt='$TxtNoseOption', 
                  TANose='$TANose', 
                  HeadOpt='$TxtHeadOption', 
                  TAHead='$TAHead', 
                  AbdomenOpt='$TxtAbdomenOption', 
                  TAAbdomen='$TAAbdomen', 
                  GenitoUrinaryOpt='$TxtGenitoUrinaryOption', 
                  TAGenitoUrinary='$TAGenitoUrinary', 
                  LymphGlandsOpt='$TxtLymphGlandsOption', 
                  TALymphGlands='$TALymphGlands', 
                  SkinOpt='$TxtSkinOption', 
                  TASkin='$TASkin', 
                  ExtremitiesOpt='$TxtExtremitiesOption', 
                  TAExtremities='$TAExtremities', 
                  DeformitiesOpt='$TxtDeformitiesOption', 
                  TADeformities='$TADeformities', 
                  CavityAndThroatOpt='$TxtCavityAndThroatOption', 
                  TACavityAndThroat='$TACavityAndThroat', 
                  LungsOpt='$TxtLungsOption', 
                  TALungs='$TALungs', 
                  HeartOpt='$TxtHeartOption', 
                  TAHeart='$TAHeart', 
                  BreastOpt='$TxtBreastOption', 
                  TABreast='$TABreast', 
                  RadiologicExamsOpt='$TxtRadiologicExamsOption', 
                  TARadiologicExams='$TARadiologicExams', 
                  BloodAnalysisOpt='$TxtBloodAnalysisOption', 
                  TABloodAnalysis='$TABloodAnalysis', 
                  UrinalysisOpt='$TxtUrinalysisOption', 
                  TAUrinalysis='$TAUrinalysis', 
                  FecalysisOpt='$TxtFecalysisOption', 
                  TAFecalysis='$TAFecalysis', 
                  PregnancyTestOpt='$TxtPregnancyTestOption', 
                  TAPregnancyTest='$TAPregnancyTest', 
                  HBSAgOpt='$TxtHBSAgOption', 
                  TAHBSAg='$TAHBSAg', TAOthers='$TxtOthers', 
                  TARemarks='$TxtRemarks',
                  stu_record_edit=CONCAT_WS('/',stu_record_edit,'$TxtMSEditor'),
                  stu_editor='$TxtUserEdit',
                  stu_edited_at='$TxtEditDate', 
                  TARecommendation='$TxtRecommendation', 
                  DocumentCode='$TxtDocumentCode', 
                  RevisionNumber='$TxtRevisionNumber', 
                  Effectivity='$TxtEffectivity', 
                  NoLabel='$TxtNoLabel', 
                  created_at = CURRENT_TIMESTAMP 
                WHERE StudentIDNumber='$TxtStudentIDNumber'";

        $Result = $ClinicRecordsDB->GetRows($sql);
        if($Result){
          $Message = 'Successfully save the information!'; 
          $Error = "0";
        }else{
          $Message = 'Failed to save the Information!'; 
          $Error = "1";
        }



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
