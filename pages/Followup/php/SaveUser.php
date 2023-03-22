<?php
  require_once 'Database.php';
  require '../../../php/centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  

  $numb = $_POST['numb'];

  // Receive Data from Client
  $TxtStudentIDNumber2 = $_POST['TxtStudentIDNumber2'];
  $TxtPhysician = $_POST['userFullN'];
  $TxtPhysicianIDNumber = $_POST['userID'];
  $TxtDate = $_POST['TxtDate'];
  $TxtTime = $_POST['TxtTime'];
  $TxtConsDate = $_POST['TxtConsDate'];
  $TxtConsTime = $_POST['TxtConsTime'];
  $TxtTemperature = $_POST['TxtTemperature'];
  $TxtBP = $_POST['TxtBP'];
  $TxtPR = $_POST['TxtPR'];

  $TxtComplaints = $_POST['TxtComplaints'];
  $TxtDiagnosis = $_POST['TxtDiagnosis'];
  $TxtDiagnosticTest = $_POST['TxtDiagnosticTest'];
  $TxtMedicineGiven = $_POST['TxtMedicineGiven'];
  $TxtRemarks = $_POST['TxtRemarks'];
  $TxtPhysicalFindings = $_POST['TxtPhysicalFindings'];

  $TxtConsMSEditor = $_POST['TxtConsMSEditor'];
  $TxtUserEdit = $_POST['TxtUserEdit'];
  $TxtEditDate = $_POST['TxtEditDate'];

  $Error ='';
  $Message = '';
  

  
    
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
      global $ClinicRecordsDB,$Message,$Error,$TxtStudentIDNumber2,$TxtDate,$TxtTime,$TxtConsDate,$TxtConsTime,$TxtPhysician,$TxtPhysicianIDNumber,$TxtComplaints,$TxtDiagnosis,$TxtDiagnosticTest,$TxtMedicineGiven, $TxtRemarks,$TxtPhysicalFindings,$TxtTemperature,$TxtBP,$TxtPR, $numb,  $TxtConsMSEditor,$TxtUserEdit,$TxtEditDate ;

      $sql;

      /*$TxtStudentIDNumber2 = strtolower($TxtStudentIDNumber2);
      $TxtDate = strtolower($TxtDate);
      $TxtLastName = strtolower($TxtLastName);
      $TxtFirstName = strtolower($TxtFirstName);
      $TxtMiddleName = strtolower($TxtMiddleName);
      $TxtExtension = strtolower($TxtExtension);
      $TxtSex = strtolower($TxtSex);
      $TxtCourseStrand = strtolower($TxtCourseStrand);
      $TxtPhysician = strtolower($TxtPhysician);
      $TxtPhysicianIDNumber = strtolower($TxtPhysicianIDNumber);
      $TxtTemperature = strtolower($TxtTemperature);
      $TxtBP = strtolower($TxtBP);
      $TxtPR = strtolower($TxtPR);
      $RadSmoker = strtolower($RadSmoker);
      $RadSanger = strtolower($RadSanger);
      $RadMoma = strtolower($RadMoma);
      $RadVS = strtolower($RadVS);
      $TxtBooster = strtolower($TxtBooster);
      $TxtNumberOfStick = strtolower($TxtNumberOfStick);
      $TxtNumberOfYears = strtolower($TxtNumberOfYears);
      $TxtAgeStarted = strtolower ($TxtAgeStarted);
      $Others = strtolower ($Others);
      $TxtVaccineBrand = strtolower ($TxtVaccineBrand);
      $TxtMomaSpan = strtolower ($TxtMomaSpan);*/

      $sql = "UPDATE followup 
              SET 
                IdNumb='$TxtStudentIDNumber2', 
                Dates='$TxtDate', 
                fu_time='$TxtTime', 
                cons_date='$TxtConsDate', 
                cons_time='$TxtConsTime', 
                Physician='$TxtPhysician', 
                PhysicianID='$TxtPhysicianIDNumber', 
                Complaints='$TxtComplaints', 
                Diagnosis='$TxtDiagnosis', 
                DiagnosticTestNeeded='$TxtDiagnosticTest', 
                MedicineGiven='$TxtMedicineGiven', 
                cons_Temperature='$TxtTemperature', 
                cons_BloodPressure='$TxtBP', 
                cons_PulseRate='$TxtPR', 

                PhysicalFindings='$TxtPhysicalFindings',
                cons_record_edit=CONCAT_WS('/',
                  cons_record_edit,'$TxtConsMSEditor'),
                cons_editor='$TxtUserEdit',
                cons_edited_at='$TxtEditDate', 
                Remarks='$TxtRemarks', 
                created_at = CURRENT_TIMESTAMP 
              WHERE Num='$numb'";

      $Result = $ClinicRecordsDB->Execute($sql);


      $Message = 'Successfully stored!';   
      $Error = "0";
      

      $sql = "INSERT INTO history_followup
              (IdNumb,Dates,fu_time,cons_date,cons_time,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at) 
              SELECT 
              IdNumb,Dates,fu_time,cons_date,cons_time,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at
              FROM followup 
              WHERE Num='$numb'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);
    }
?>
