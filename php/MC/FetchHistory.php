<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";
  $type = '';

  $num = $_POST['num'];
  $userid = $_POST['userid'];
  $editdate = $_POST['editdate'];

  /*$idnumber = '1';
  $userid = '2';
  $editdate = '2023-02-17 12:20:31';*/

    $TxtMCDocumentCode = "";
    $TxtMCRevisionNumber = "";
    $TxtMCEffectivity = "";
    $TxtMCNoLabel = "";
    $TxtStudentIDNumber = "";
    $ConsultDate = "";
    $RadPurpose = "";
    $TAOthers = "";
    $RadPhysicallyFit = "";
    $TAMCRemarks = "";
    $RadReason = "";
    $TAMCDiagnosis = "";
    $RadExcuseOrNot = "";
    $TAOthers1 = "";
    $TAMCRemarks1 = "";
    $TxtMCMSEditor = "";
    


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
   $XMLData .= ' MCDocumentCode = ' . '"'.$TxtMCDocumentCode.'"'; 
   $XMLData .= ' MCRevisionNumber = ' . '"'.$TxtMCRevisionNumber.'"'; 
   $XMLData .= ' MCEffectivity = ' . '"'.$TxtMCEffectivity.'"'; 
   $XMLData .= ' MCNoLabel = ' . '"'.$TxtMCNoLabel.'"'; 
   $XMLData .= ' StudentIDNumber = ' . '"'.$TxtStudentIDNumber.'"';  
   $XMLData .= ' ConsultDate = ' . '"'.$ConsultDate.'"'; 
   $XMLData .= ' RadPurpose = ' . '"'.$RadPurpose.'"'; 
   $XMLData .= ' Others = ' . '"'.$TAOthers.'"'; 
   $XMLData .= ' PhysicallyFit = ' . '"'.$RadPhysicallyFit.'"'; 
   $XMLData .= ' MCRemarks = ' . '"'.$TAMCRemarks.'"'; 
   $XMLData .= ' Reason = ' . '"'.$RadReason.'"'; 
   $XMLData .= ' MCDiagnosis = ' . '"'.$TAMCDiagnosis.'"'; 
   $XMLData .= ' ExcuseOrNot = ' . '"'.$RadExcuseOrNot.'"'; 
   $XMLData .= ' Others1 = ' . '"'.$TAOthers1.'"'; 
   $XMLData .= ' MCRemarks1 = ' . '"'.$TAMCRemarks1.'"';
   $XMLData .= ' MCMSEditor = ' . '"'.$TxtMCMSEditor.'"';     
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
    global $Error, $ClinicRecordsDB, $Message, $TxtMCDocumentCode, $TxtMCRevisionNumber, $TxtMCEffectivity ,$TxtMCNoLabel, $TxtStudentIDNumber, $ConsultDate, $RadPurpose, $TAOthers, $RadPhysicallyFit, $TAMCRemarks, $RadReason, $TAMCDiagnosis, $RadExcuseOrNot, $TAOthers1, $TAMCRemarks1, $type, $TxtMCMSEditor;

      $sql = "SELECT * FROM medicalcertificate  WHERE mc_id_num = '$num'";
      $Result = $ClinicRecordsDB->Execute($sql);
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
      $Row = $ClinicRecordQuery->fetch_array();

      $TxtMCMSEditor = stripslashes($Row['mc_record_edit']);;


      $sql = "SELECT * FROM history_medical  WHERE mc_editor = '$userid' AND mc_edited_at = '$editdate'";
      
      
      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {     
            $TxtMCDocumentCode =stripcslashes($Row['mc_doc_code']);; 
            $TxtMCRevisionNumber =stripcslashes($Row['mc_rev_num']);; 
            $TxtMCEffectivity = stripcslashes($Row['mc_effectivity']);;
            $TxtMCNoLabel = stripcslashes($Row['mc_no_label']);;
            $TxtStudentIDNumber = stripslashes($Row['student_id']);;
            $ConsultDate = stripslashes($Row['consult_date']);;
            $RadPurpose = stripslashes($Row['purpose']);;
            $TAOthers =stripcslashes($Row['purpose_others']);; 
            $RadPhysicallyFit = stripcslashes($Row['is_pf']);;
            $TAMCRemarks = stripcslashes($Row['pf_remarks']);; 
            $RadReason = stripslashes($Row['reason']);;
            $TAMCDiagnosis = stripslashes($Row['diagnosis']);;
            $RadExcuseOrNot = stripslashes($Row['is_excused']);;
            $TAOthers1 = stripslashes($Row['is_excused_others']);;
            $TAMCRemarks1 = stripslashes($Row['general_remarks']);;
            
            
            $Message = "Search completed!";
            $Error = "0"; 

          }else{
            $Message = "No user found. Please try again.";
            $Error = "1";
          }            
      }
  }

?>