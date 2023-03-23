<?php
  require_once 'Database.php';
  require '../centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";
  $id = $_POST['id'];

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
        UpdateRecord();
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

    function UpdateRecord(){

   
      global $ClinicRecordsDB,$Message,$Error,$TxtMCDocumentCode, $TxtMCRevisionNumber, $TxtMCEffectivity ,$TxtMCNoLabel, $TxtStudentIDNumber, $ConsultDate, $RadPurpose, $TAOthers, $RadPhysicallyFit, $TAMCRemarks, $RadReason, $TAMCDiagnosis, $RadExcuseOrNot, $TAOthers1, $TAMCRemarks1, $id, $TxtMCMSEditor,$TxtUserEdit,$TxtEditDate,$StaffID, $StaffName;

      
      
      $sql = "UPDATE medicalcertificate SET 
                    mc_doc_code='$TxtMCDocumentCode',
                    mc_rev_num='$TxtMCRevisionNumber',
                    mc_effectivity='$TxtMCEffectivity',
                    mc_no_label='$TxtMCNoLabel',
                    student_id='$TxtStudentIDNumber',
                    consult_date='$ConsultDate',
                    /*mc_physician_id='$StaffID',
                    mc_physician='$StaffName',*/
                    purpose='$RadPurpose',
                    purpose_others='$TAOthers',
                    is_pf='$RadPhysicallyFit',
                    pf_remarks='$TAMCRemarks',
                    reason='$RadReason',
                    diagnosis='$TAMCDiagnosis',
                    is_excused='$RadExcuseOrNot',
                    is_excused_others='$TAOthers1',
                    general_remarks='$TAMCRemarks1',
                    mc_record_edit=CONCAT_WS('/',mc_record_edit,'$TxtMCMSEditor'),
                    mc_editor='$TxtUserEdit',
                    mc_edited_at='$TxtEditDate'
              WHERE mc_id_num='$id'";


      $Result = $ClinicRecordsDB->Execute($sql);
      if($Result){
            $Message = 'Successfully updated Medical Certificate!'; 
            $Error = "0";
        }else{
            $Message = 'Database error!'; 
            $Error = "1";
        }

      $sql = "INSERT INTO history_medical
              (mc_doc_code,mc_rev_num,mc_effectivity,mc_no_label,student_id,consult_date,date_requested,mc_physician_id,mc_physician,purpose,purpose_others,is_pf,pf_remarks,reason,diagnosis,is_excused,is_excused_others,general_remarks,mc_record_edit,mc_editor,mc_edited_at,created_at,updated_at,archived_at) 
              SELECT 
              mc_doc_code,mc_rev_num,mc_effectivity,mc_no_label,student_id,consult_date,date_requested,mc_physician_id,mc_physician,purpose,purpose_others,is_pf,pf_remarks,reason,diagnosis,is_excused,is_excused_others,general_remarks,mc_record_edit,mc_editor,mc_edited_at,created_at,updated_at,archived_at
              FROM medicalcertificate
              WHERE mc_id_num='$id'";
        $Result1 = $ClinicRecordsDB->GetRows($sql);
      
    }
?>
