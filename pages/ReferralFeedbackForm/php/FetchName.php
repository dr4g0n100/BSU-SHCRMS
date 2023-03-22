<?php
require_once 'Database.php';
require '../../../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $temp = $_POST['temp'];


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        FetchUser($temp);
        
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

  function FetchUser($temp){
    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message;

      $sql = "SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber='$temp'";

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

?>