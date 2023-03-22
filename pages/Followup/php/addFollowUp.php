<?php
  require_once 'Database.php';
  require '../../../php/centralConnection.php';
  date_default_timezone_set('Asia/Manila');

  $TxtStudentIDNumber2 = $_POST['TxtStudentIDNumber2'];

  /*$TxtFirstName = $_POST['TxtFirstName'];
  $TxtMiddleName = $_POST['TxtMiddleName'];
  $TxtLastName = $_POST['TxtLastName'];
  $TxtExtension = $_POST['TxtExtension'];
  $TxtAge = $_POST['TxtAge'];
  $TxtSex = $_POST['TxtSex'];
  $TxtCourseStrand = $_POST['TxtCourseStrand'];
  $TxtYear = $_POST['TxtYear'];*/

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
  
  $Error= '';
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

      $sql = "SELECT IdNumb FROM consultationinfo WHERE IdNumb = '$tempID' ";
      $Result = $ClinicRecordsDB->Execute($sql);
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);  

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
          StoreConsultation();   
        }else{
          $Message = 'No Consultation record found. Please make sure to create consultation record first at the Consultation page.';
          $Error = "1";
        }       
      }
    }

    function StoreConsultation()
    {
    //Access Global Variables

    global $TxtStudentIDNumber2,$TxtDate,$TxtTime,$TxtConsDate,$TxtConsTime,$TxtPhysician,
    $TxtPhysicianIDNumber,$TxtComplaints,$TxtDiagnosis,$TxtDiagnosticTest,$TxtMedicineGiven,$TxtTemperature,$TxtBP,$TxtPR,$TxtRemarks,$TxtPhysicalFindings, $TxtConsMSEditor,$TxtUserEdit,$TxtEditDate;

    global $connect, $ClinicRecordsDB, $Message,$Error;   
    //global $TxtLastName,$TxtFirstName,$TxtMiddleName, $TxtExtension, $TxtAge,$TxtSex,$TxtCourseStrand, $TxtYear;
            
        /*$TxtStudentIDNumber2 = strtolower($TxtStudentIDNumber2);
        $TxtExtension = strtolower($TxtExtension);
        $TxtDate = strtolower($TxtDate);
        $TxtLastName = strtolower($TxtLastName);
        $TxtFirstName = strtolower($TxtFirstName);
        $TxtMiddleName = strtolower($TxtMiddleName);
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
        $TxtMomaSpan = strtolower ($TxtMomaSpan);
        $TxtVaccineBrand = strtolower ($TxtVaccineBrand);*/
    
        $sql = "INSERT INTO followup 
                      (IdNumb, 
                      Dates, 
                      fu_time,
                      cons_date, 
                      cons_time, 
                      Physician, 
                      PhysicianID, 
                      Complaints, 
                      Diagnosis, 
                      DiagnosticTestNeeded, 
                      MedicineGiven, 
                      cons_Temperature, 
                      cons_BloodPressure, 
                      cons_PulseRate, 
                      created_at, 
                      PhysicalFindings, 
                      Remarks,
                      cons_record_edit,
                      cons_editor,
                      cons_edited_at
                      ) 
                VALUES 
                    ('$TxtStudentIDNumber2', 
                      '$TxtDate', 
                      '$TxtTime', 
                      '$TxtConsDate', 
                      '$TxtConsTime',
                      '$TxtPhysician',
                      '$TxtPhysicianIDNumber ', 
                      '$TxtComplaints',
                      '$TxtDiagnosis',
                      '$TxtDiagnosticTest',
                      '$TxtMedicineGiven', 
                      '$TxtTemperature', 
                      '$TxtBP', 
                      '$TxtPR', 
                      CURRENT_TIMESTAMP, 
                      '$TxtPhysicalFindings', 
                      '$TxtRemarks',
                      '$TxtConsMSEditor',
                      '$TxtUserEdit',
                      '$TxtEditDate'
                    )";    
    
        $Result = mysqli_query($connect, $sql);
        if($Result){
            $Message = 'Successfully stored!';   
            $Error = "0";
        }else{
            $Message = 'Database storing error!';   
            $Error = "1";
        }

        $id = mysqli_insert_id($connect);

        $sql = "INSERT INTO history_followup
              (IdNumb,Dates,fu_time,cons_date,cons_time,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at) 
              SELECT 
              IdNumb,Dates,fu_time,cons_date,cons_time,Physician,PhysicianID,cons_Temperature,cons_BloodPressure,cons_PulseRate,Complaints,Diagnosis,DiagnosticTestNeeded,MedicineGiven,PhysicalFindings,Remarks,cons_record_edit,cons_editor,cons_edited_at,created_at,updated_at,archived_at
              FROM followup 
              WHERE Num='$id'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);

    }

   
?>