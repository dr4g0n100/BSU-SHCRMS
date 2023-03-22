<?php
require_once 'Database.php';
require '../../../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";
  $type = '';

  $idnumber = $_POST['id'];
  $type = $_POST['type'];

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

  function FetchUser($ID){

    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message, $TxtMCDocumentCode, $TxtMCRevisionNumber, $TxtMCEffectivity ,$TxtMCNoLabel, $TxtStudentIDNumber, $ConsultDate, $RadPurpose, $TAOthers, $RadPhysicallyFit, $TAMCRemarks, $RadReason, $TAMCDiagnosis, $RadExcuseOrNot, $TAOthers1, $TAMCRemarks1, $type, $TxtMCMSEditor;

      if ($type =='viewArchivedMC'){
        $sql = "SELECT * FROM archivemedcertificate  WHERE mc_id_num = '$ID'";
      }else if ($type == 'viewMC' || $type == 'newMC'){
        $sql = "SELECT * FROM medicalcertificate  WHERE mc_id_num = '$ID'";
      }
      
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

            $TAOthers = htmlentities($Row['purpose_others']);
            $TAOthers = str_replace("<br />", "&#13;&#10;", nl2br($TAOthers));
            $TAOthers = preg_replace('/\s\s*/', ' ',$TAOthers);

            $RadPhysicallyFit = stripcslashes($Row['is_pf']);;

            $TAMCRemarks = htmlentities($Row['pf_remarks']);
            $TAMCRemarks = str_replace("<br />", "&#13;&#10;", nl2br($TAMCRemarks));
            $TAMCRemarks = preg_replace('/\s\s*/', ' ',$TAMCRemarks);

            $RadReason = stripslashes($Row['reason']);;

            $TAMCDiagnosis = htmlentities($Row['diagnosis']);
            $TAMCDiagnosis = str_replace("<br />", "&#13;&#10;", nl2br($TAMCDiagnosis));
            $TAMCDiagnosis = preg_replace('/\s\s*/', ' ',$TAMCDiagnosis);

            $RadExcuseOrNot = stripslashes($Row['is_excused']);;

            $TAOthers1 = htmlentities($Row['is_excused_others']);
            $TAOthers1 = str_replace("<br />", "&#13;&#10;", nl2br($TAOthers1));
            $TAOthers1 = preg_replace('/\s\s*/', ' ',$TAOthers1);

            $TAMCRemarks1 = htmlentities($Row['general_remarks']);
            $TAMCRemarks1 = str_replace("<br />", "&#13;&#10;", nl2br($TAMCRemarks1));
            $TAMCRemarks1 = preg_replace('/\s\s*/', ' ',$TAMCRemarks1);

            $TxtMCMSEditor = stripslashes($Row['mc_record_edit']);;
            
            $Message = "Search completed!";
            $Error = "0"; 

          }else{
            $Message = "No user found. Please try again.";
            $Error = "1";
          }            
      }
  }

?>