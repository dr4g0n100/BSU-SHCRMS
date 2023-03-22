<?php
  require_once 'Database.php';
  require '../../../php/centralConnection.php';
  date_default_timezone_set('Asia/Manila');

  $TxtStudentIDNumber2 = $_POST['TxtStudentIDNumber2'];
  $TxtPhysician = $_POST['userFullN'];
  $TxtPhysicianIDNumber = $_POST['userID'];
  $TxtDate = $_POST['TxtDate'];
  $TxtTime = $_POST['TxtTime'];
  $TxtComplaints = $_POST['TxtComplaints'];
  $TxtDiagnosis = $_POST['TxtDiagnosis'];
  $TxtDiagnosticTest = $_POST['TxtDiagnosticTest'];
  $TxtMedicineGiven = $_POST['TxtMedicineGiven'];
  $TxtTemperature = $_POST['TxtTemperature'];
  $TxtBP = $_POST['TxtBP'];
  $TxtPR = $_POST['TxtPR'];
  
  if(!empty($_POST['RadSmoker'])){
    $RadSmoker = $_POST['RadSmoker'];
  }else{
    $RadSmoker = '';
  }

  if(!empty($_POST['RadSanger'])){
    $RadSanger = $_POST['RadSanger'];
  }else{
    $RadSanger = '';
  }

  if(!empty($_POST['RadMoma'])){
    $RadMoma = $_POST['RadMoma'];
  }else{
    $RadMoma = '';
  }

  if(!empty($_POST['RadVS'])){
    $RadVS = $_POST['RadVS'];
  }else{
    $RadVS = '';
  }
  
  $TxtBooster = $_POST['TxtBooster'];
  $TxtVaccineBrand = $_POST['TxtVaccineBrand'];
  $TxtNumberOfStick = $_POST['TxtNumberOfStick'];
  $TxtNumberOfYears = $_POST['TxtNumberOfYears'];
  $TxtAgeStarted = $_POST['TxtAgeStarted'];
  $Others = $_POST['TxtOthers'];
  $TxtMomaSpan = $_POST['TxtMomaSpan'];
  $TxtRemarks = $_POST['TxtRemarks'];
  $TxtPhysicalFindings = $_POST['TxtPhysicalFindings'];

  $TxtConsMSEditor = $_POST['TxtConsMSEditor'];
  $TxtUserEdit = $_POST['TxtUserEdit'];
  $TxtEditDate = $_POST['TxtEditDate'];

  /*$TxtStudentIDNumber2 = '123';
  $TxtPhysician = '';
  $TxtPhysicianIDNumber = '';
  $TxtDate = '';
  $TxtTime = '';
  $TxtComplaints = '';
  $TxtDiagnosis = '';
  $TxtDiagnosticTest = '';
  $TxtMedicineGiven = '';
  $TxtTemperature = '';
  $TxtBP = '';
  $TxtPR = '';
  
  if(!empty($_POST['RadSmoker'])){
    $RadSmoker = '';
  }else{
    $RadSmoker = '';
  }

  if(!empty($_POST['RadSanger'])){
    $RadSanger = '';
  }else{
    $RadSanger = '';
  }

  if(!empty($_POST['RadMoma'])){
    $RadMoma = '';
  }else{
    $RadMoma = '';
  }

  if(!empty($_POST['RadVS'])){
    $RadVS = '';
  }else{
    $RadVS = '';
  }
  
  $TxtBooster = '';
  $TxtVaccineBrand = '';
  $TxtNumberOfStick = '';
  $TxtNumberOfYears = '';
  $TxtAgeStarted = '';
  $Others = '';
  $TxtMomaSpan = '';
  $TxtRemarks = '';
  $TxtPhysicalFindings = '';

  $TxtConsMSEditor = '';
  $TxtUserEdit = '';
  $TxtEditDate = '';*/

  $Error;
  $Message = '';  



    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
        {     
          CheckID($TxtStudentIDNumber2);
        }
      else
        {
          $Message = 'Failed to store consultation!';
          $Error = "1";
        }
    }  
    else
    {
      $Message = 'Database offline!';    
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

    function CheckID($tempID){
      global $ClinicRecordsDB, $Message,$Error; 

      $sql = "SELECT StudentIDNumber FROM PersonalMedicalRecord WHERE StudentIDNumber = '$tempID' ";
      $Result = $ClinicRecordsDB->Execute($sql);
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);  

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
          StoreConsultation();   
        }else{
          $Message = 'No student record found. Please make sure to register first at the student page.';
          $Error = "1";
        }       
      }
    }

    function StoreConsultation()
    {
    //Access Global Variables

    global $TxtStudentIDNumber2,$TxtDate,$TxtTime,$TxtPhysician,
    $TxtPhysicianIDNumber,$RadSmoker,$RadSanger,$RadMoma,$RadVS,$TxtBooster,$TxtComplaints,$TxtDiagnosis,$TxtDiagnosticTest,$TxtMedicineGiven,$TxtTemperature,$TxtBP,$TxtPR, $TxtNumberOfStick,$TxtNumberOfYears,$TxtAgeStarted,$Others,$TxtMomaSpan,$TxtVaccineBrand,$TxtRemarks,$TxtPhysicalFindings, $TxtConsMSEditor, $TxtUserEdit, $TxtEditDate;

    global $connect, $ClinicRecordsDB, $Message,$Error;   
    
        $sql = "INSERT INTO ConsultationInfo 
                  (IdNumb, Dates, Times, Physician, PhysicianID, Complaints, Diagnosis, DiagnosticTestNeeded, MedicineGiven, cons_Temperature, cons_BloodPressure, cons_PulseRate, Smoker, AlcoholDrinker, Moma, Vaccination, Booster, NumOfStick, NumOfYearAsSmoker, AgeStartedAsDrinker, Others, HowLongAsChewer, Vaccine, created_at, PhysicalFindings, Remarks, cons_record_edit, cons_editor, cons_edited_at) 
                  VALUES ('$TxtStudentIDNumber2', '$TxtDate', '$TxtTime', '$TxtPhysician','$TxtPhysicianIDNumber ', '$TxtComplaints','$TxtDiagnosis','$TxtDiagnosticTest','$TxtMedicineGiven', '$TxtTemperature', '$TxtBP', '$TxtPR', NULLIF('$RadSmoker','null'), NULLIF('$RadSanger','null'), NULLIF('$RadMoma','null'), NULLIF('$RadVS','null'), '$TxtBooster', '$TxtNumberOfStick', '$TxtNumberOfYears', '$TxtAgeStarted', '$Others', '$TxtMomaSpan', '$TxtVaccineBrand',CURRENT_TIMESTAMP, '$TxtPhysicalFindings', '$TxtRemarks', '$TxtConsMSEditor', '$TxtUserEdit', '$TxtEditDate')";    
    
        $Result = mysqli_query($connect, $sql);

        if($Result){
            $Message = 'Successfully stored!';   
            $Error = "0";
        }else{
            $Message = 'Database storing error!';   
            $Error = "1";
        }

        $id = mysqli_insert_id($connect);

        $sql = "INSERT INTO history_consultation 
              (IdNumb,Dates,Times,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Smoker,NumOfStick,NumOfYearAsSmoker,AlcoholDrinker,AgeStartedAsDrinker,Others,Moma,HowLongAsChewer,Vaccination,Vaccine,Booster,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at) 
              SELECT 
              IdNumb,Dates,Times,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Smoker,NumOfStick,NumOfYearAsSmoker,AlcoholDrinker,AgeStartedAsDrinker,Others,Moma,HowLongAsChewer,Vaccination,Vaccine,Booster,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at
              FROM ConsultationInfo 
              WHERE Num='$id'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);

    }

   
?>