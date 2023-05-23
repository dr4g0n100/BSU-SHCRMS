<?php
  require_once 'Database.php';
  require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

    $vaccine_name = trim($_POST['vaccine_name']);
    $vaccine_id = trim($_POST['vaccine_id']);
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($vaccine_name, $vaccine_id);
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

  function CheckRecord($vaccine_name, $vaccine_id){

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $Error;  
    
      $sql = "SELECT COUNT(id) FROM db_vaccine_list WHERE id = '$vaccine_id'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {     
            updateData($vaccine_name, $vaccine_id);   
                 
        }else{

            $Message = 'Could not save, Vaccine does not exist on the list.';
            $Error = "1";
        }       
      }
  }

  function updateData($vaccine_name, $vaccine_id)
  {
      global $ClinicRecordsDB, $Message, $Error;

      $sql = "UPDATE db_vaccine_list SET vaccine = '$vaccine_name' WHERE id='$vaccine_id'  ";   

      $Result = $ClinicRecordsDB->GetRows($sql);
      if ($Result) {
        $Message = 'Successfully updated the information!'; 
        $Error = "0";
      }else{
        $Message = 'Database Storing Error!'; 
        $Error = "1";
      }
          
  }
?>
