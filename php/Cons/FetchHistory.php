<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $num = $_POST['num'];
  $userid = $_POST['userid'];
  $editdate = $_POST['editdate'];

    $TxtStudentIDNumber = "";
    $TxtPhysician = "";
    $TxtPhysicianIDNumber = "";
    $TxtDate = "";
    $TxtTime = "";
    $TxtComplaints = "";
    $TxtDiagnosis = "";
    $TxtDiagnosticTest = "";
    $TxtMedicineGiven = "";
    $TxtTemperature = "";
    $TxtBP = "";
    $TxtPR = "";
    $RadSmoker = "";
    $RadSanger = "";
    $RadMoma = "";
    $RadVS = "";
    $TxtNumOfStick = "";
    $TxtNumOfYearAsSmoker = "";
    $TxtAgeStartedAsDrinker = "";
    $TxtOthers = "";
    $TxtHowLongAsChewer = "";
    $TxtBooster = "";
    $TxtVaccineBrand = "";
    $TxtRemarks = "";
    $TxtPhysicalFindings = "";
    $TxtConsMSEditor = "";
    
    

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        FetchUser($num,$userid,$editdate);
        
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
  $XMLData .= ' StudentIDNumber = ' . '"'.$TxtStudentIDNumber.'"';
  $XMLData .= ' Physician = ' . '"'.$TxtPhysician.'"';
  $XMLData .= ' PhysicianIDNumber = ' . '"'.$TxtPhysicianIDNumber.'"';
  $XMLData .= ' Date = ' . '"'.$TxtDate.'"';
  $XMLData .= ' Time = ' . '"'.$TxtTime.'"';
  $XMLData .= ' Complaints = ' . '"'.$TxtComplaints.'"';
  $XMLData .= ' Diagnosis = ' . '"'.$TxtDiagnosis.'"';
  $XMLData .= ' DiagnosticTest = ' . '"'.$TxtDiagnosticTest.'"';
  $XMLData .= ' MedicineGiven = ' . '"'.$TxtMedicineGiven.'"';
  $XMLData .= ' Temperature = ' . '"'.$TxtTemperature.'"';
  $XMLData .= ' BP = ' . '"'.$TxtBP.'"';
  $XMLData .= ' PR = ' . '"'.$TxtPR.'"';
  $XMLData .= ' Smoker = ' . '"'.$RadSmoker.'"';
  $XMLData .= ' Sanger = ' . '"'.$RadSanger.'"';
  $XMLData .= ' Moma = ' . '"'.$RadMoma.'"';
  $XMLData .= ' VS = ' . '"'.$RadVS.'"';
  $XMLData .= ' NumOfStick = ' . '"'.$TxtNumOfStick.'"';
  $XMLData .= ' NumOfYearAsSmoker = ' . '"'.$TxtNumOfYearAsSmoker.'"';
  $XMLData .= ' AgeStartedAsDrinker = ' . '"'.$TxtAgeStartedAsDrinker.'"';
  $XMLData .= ' Others = ' . '"'.$TxtOthers.'"';
  $XMLData .= ' HowLongAsChewer = ' . '"'.$TxtHowLongAsChewer.'"';
  $XMLData .= ' Booster = ' . '"'.$TxtBooster.'"';
  $XMLData .= ' VaccineBrand = ' . '"'.$TxtVaccineBrand.'"';
  $XMLData .= ' Remarks = ' . '"'.$TxtRemarks.'"';
  $XMLData .= ' PhysicalFindings = ' . '"'.$TxtPhysicalFindings.'"';
  $XMLData .= ' ConsMSEditor = ' . '"'.$TxtConsMSEditor.'"';
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function FetchUser($num,$userid,$editdate){
    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message, $TxtStudentIDNumber,$TxtPhysician,$TxtPhysicianIDNumber,$TxtDate,$TxtTime,$TxtComplaints,$TxtDiagnosis,$TxtDiagnosticTest,$TxtMedicineGiven,$TxtTemperature,$TxtBP,$TxtPR,$RadSmoker,$RadSanger,$RadMoma,$RadVS,$TxtNumOfStick,$TxtNumOfYearAsSmoker,$TxtAgeStartedAsDrinker,$TxtOthers,$TxtHowLongAsChewer,$TxtBooster,$TxtVaccineBrand,$TxtRemarks,$TxtPhysicalFindings,$TxtConsMSEditor;


      $sql = "SELECT * FROM ConsultationInfo  WHERE Num = '$num'";
      $Result = $ClinicRecordsDB->Execute($sql);
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
      $Row = $ClinicRecordQuery->fetch_array();

      $TxtConsMSEditor = stripslashes($Row['cons_record_edit']);;


      $sql = "SELECT * FROM history_consultation  WHERE cons_editor = '$userid' AND cons_edited_at = '$editdate'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        
            $TxtStudentIDNumber = stripslashes($Row['IdNumb']);;
            $TxtPhysician = stripslashes($Row['Physician']);;
            $TxtPhysicianIDNumber = stripslashes($Row['PhysicianID']);;
            $TxtDate = stripslashes($Row['Dates']);;
            $TxtTime = stripslashes($Row['Times']);;
            $TxtComplaints = htmlentities($Row['Complaints']);
            $TxtComplaints = str_replace("<br />", "&#13;&#10;", nl2br($TxtComplaints));
            $TxtComplaints = preg_replace('/\s\s*/', ' ',$TxtComplaints);
            $TxtDiagnosis = htmlentities($Row['Diagnosis']);
            $TxtDiagnosis = str_replace("<br />", "&#13;&#10;", nl2br($TxtDiagnosis));
            $TxtDiagnosis = preg_replace('/\s\s*/', ' ',$TxtDiagnosis);
            $TxtDiagnosticTest = htmlentities($Row['DiagnosticTestNeeded']);
            $TxtDiagnosticTest = str_replace("<br />", "&#13;&#10;", nl2br($TxtDiagnosticTest));
            $TxtDiagnosticTest = preg_replace('/\s\s*/', ' ',$TxtDiagnosticTest);
            $TxtMedicineGiven = htmlentities($Row['MedicineGiven']);
            $TxtMedicineGiven = str_replace("<br />", "&#13;&#10;", nl2br($TxtMedicineGiven));
            $TxtMedicineGiven = preg_replace('/\s\s*/', ' ',$TxtMedicineGiven);
            $TxtTemperature = stripslashes($Row['cons_Temperature']);;
            $TxtBP = stripslashes($Row['cons_BloodPressure']);;
            $TxtPR = stripslashes($Row['cons_PulseRate']);;
            $RadSmoker = stripslashes($Row['Smoker']);;
            $RadSanger = stripslashes($Row['AlcoholDrinker']);;
            $RadMoma = stripslashes($Row['Moma']);;
            $RadVS = stripslashes($Row['Vaccination']);;
            $TxtNumOfStick = stripslashes($Row['NumOfStick']);;
            $TxtNumOfYearAsSmoker = stripslashes($Row['NumOfYearAsSmoker']);;
            $TxtAgeStartedAsDrinker = stripslashes($Row['AgeStartedAsDrinker']);;
            $TxtOthers = stripslashes($Row['Others']);;
            $TxtHowLongAsChewer = stripslashes($Row['HowLongAsChewer']);;
            $TxtBooster = stripslashes($Row['Booster']);;
            $TxtVaccineBrand = stripslashes($Row['Vaccine']);;
            
            $TxtRemarks = htmlentities($Row['Remarks']);
            $TxtRemarks = str_replace("<br />", "&#13;&#10;", nl2br($TxtRemarks));
            $TxtRemarks = preg_replace('/\s\s*/', ' ',$TxtRemarks);
            $TxtPhysicalFindings = htmlentities($Row['PhysicalFindings']);
            $TxtPhysicalFindings = str_replace("<br />", "&#13;&#10;", nl2br($TxtPhysicalFindings));
            $TxtPhysicalFindings = preg_replace('/\s\s*/', ' ',$TxtPhysicalFindings);
            $Message = "Search completed!";
            $Error = "0"; 
          }else{
            $Message = "No user found. Please try again.";
            $Error = "1";
          }            
      }
  }

  function br2nl( $input ) {
    return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
}

?>