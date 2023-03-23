<?php
  require_once 'Database.php';
  require '../centralConnection.php';
	date_default_timezone_set('Asia/Manila');

 
  $Message = '';
  $Error = "0";
  
    
    $TxtMCDocumentCode = $_POST['TxtMCDocumentCode'];
    $TxtMCRevisionNumber = $_POST['TxtMCRevisionNumber'];
    $TxtMCEffectivity = $_POST['TxtMCEffectivity'];
    $TxtMCNoLabel = $_POST['TxtMCNoLabel'];

    $TxtStudentIDNumber = $_POST['TxtStudentIDNumber'];

    $ConsultDate = $_POST['studentExaminedOn'];

    
    if(!empty($_POST['RadPurpose'])){
    $RadPurpose = $_POST['RadPurpose'];
    }else{
      $RadPurpose = '';
    }

    $TAOthers = $_POST['TAOthers'];

    
    if(!empty($_POST['RadPhysicallyFitUnfit'])){
    $RadPhysicallyFit = $_POST['RadPhysicallyFitUnfit'];
    }else{
      $RadPhysicallyFit = '';
    }

    $TAMCRemarks = $_POST['TAMCRemarks'];

    if(!empty($_POST['RadPurpose2'])){
    $RadReason = $_POST['RadPurpose2'];
    }else{
      $RadReason = '';
    }

    $TAMCDiagnosis = $_POST['TAMCDiagnosis'];


    if(!empty($_POST['RadExcuseOrNot'])){
      $RadExcuseOrNot = $_POST['RadExcuseOrNot'];
    }else{
      $RadExcuseOrNot = '';
    }

    $TAOthers1 = $_POST['TAOthers1'];

    $TAMCRemarks1 = $_POST['TAMCRemarks1'];

    $TxtMCMSEditor = $_POST['TxtMCMSEditor'];
    $TxtUserEdit = $_POST['TxtUserEdit'];
    $TxtEditDate = $_POST['TxtEditDate'];

    $StaffID = $_POST['userID'];
    $StaffName = $_POST['userFullN'];
    

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


    //Access Global Variables
    global $connect, $ClinicRecordsDB,$Message,$Error,$TxtMCDocumentCode, $TxtMCRevisionNumber, $TxtMCEffectivity ,$TxtMCNoLabel, $TxtStudentIDNumber, $ConsultDate, $RadPurpose, $TAOthers, $RadPhysicallyFit, $TAMCRemarks, $RadReason, $TAMCDiagnosis, $RadExcuseOrNot, $TAOthers1, $TAMCRemarks1,$TxtMCMSEditor,$TxtUserEdit,$TxtEditDate, $StaffID, $StaffName;
    //global  $TxtFirstname, $TxtMiddlename, $TxtLastname, $TxtExtension;

        $dateNow = date("Y-m-d");
        $sql = "INSERT INTO medicalcertificate (mc_doc_code,
                                                mc_rev_num,
                                                mc_effectivity, 
                                                mc_no_label, 
                                                student_id, 
                                                consult_date,
                                                date_requested, 
                                                mc_physician_id,
                                                mc_physician,
                                                purpose, 
                                                purpose_others, 
                                                is_pf, 
                                                pf_remarks, 
                                                reason, 
                                                diagnosis, 
                                                is_excused, 
                                                is_excused_others, 
                                                general_remarks, 
                                                mc_record_edit,
                                                mc_editor,
                                                mc_edited_at,
                                                created_at) 
                                            VALUES 
                                       ('$TxtMCDocumentCode',
                                        '$TxtMCRevisionNumber',
                                        '$TxtMCEffectivity', 
                                        '$TxtMCNoLabel', 
                                        '$TxtStudentIDNumber', 
                                        '$ConsultDate',
                                        '$dateNow', 
                                        '$StaffID', 
                                        '$StaffName', 
                                        '$RadPurpose', 
                                        '$TAOthers',
                                        '$RadPhysicallyFit',
                                        '$TAMCRemarks',
                                        '$RadReason',
                                        '$TAMCDiagnosis',
                                        '$RadExcuseOrNot',
                                        '$TAOthers1', 
                                        '$TAMCRemarks1',
                                        '$TxtMCMSEditor',
                                        '$TxtUserEdit', 
                                        '$TxtEditDate',
                                        CURRENT_TIMESTAMP)";  
  
        $Result = mysqli_query($connect, $sql);

        if($Result){
            $Message = 'Successfully added the information!'; 
            $Error = "0";
        }else{
            $Message = 'Database error!'; 
            $Error = "1";
        }

        $id = mysqli_insert_id($connect);

        $sql = "INSERT INTO history_medical
              (mc_doc_code,mc_rev_num,mc_effectivity,mc_no_label,student_id,consult_date,date_requested,mc_physician_id,mc_physician,purpose,purpose_others,is_pf,pf_remarks,reason,diagnosis,is_excused,is_excused_others,general_remarks,mc_record_edit,mc_editor,mc_edited_at,created_at,updated_at,archived_at) 
              SELECT 
              mc_doc_code,mc_rev_num,mc_effectivity,mc_no_label,student_id,consult_date,date_requested,mc_physician_id,mc_physician,purpose,purpose_others,is_pf,pf_remarks,reason,diagnosis,is_excused,is_excused_others,general_remarks,mc_record_edit,mc_editor,mc_edited_at,created_at,updated_at,archived_at
              FROM medicalcertificate
              WHERE mc_id_num='$id'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);
          
          
              
      
  }

    
   
?>