<?php
require_once 'Database.php';
require '../../../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $idnum = $_POST['idnum'];


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        if(checkIfExistArchive($idnum)){
            FetchUser($idnum);
        }
        
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
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function FetchUser($idnum){
    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message;

      $sql = "SELECT * FROM useraccounts WHERE IdNum='$idnum'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {        
            $Message = "User already exist.";
            $Error = "1"; 
          }else{
            $Message = "No error found.";
            $Error = "0";
          }            
      }
  }

  function checkIfExistArchive($idnum){
      global $ClinicRecordsDB, $Message, $Error;

      $sql = "SELECT IdNum FROM archivedstaff WHERE IdNum = '$idnum' ";

      $Result = $ClinicRecordsDB->Execute($sql);

      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'Record exists on archive. Please restore if you want to edit this record.';
            $Error = "1";
            return false;     
        }else{
            return true;
        }       
      }
  }

?>