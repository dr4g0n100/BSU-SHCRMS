<?php
  require_once 'Database.php';
  require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

    $VaccineName = $_POST['vaccineName'];

    /*$DegreeCategory = 'test vaccine';*/
    
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($VaccineName);
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

  function CheckRecord($VaccineName){

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $Error;  
    
      $sql = "SELECT id FROM db_vaccine_list WHERE vaccine = '$VaccineName' ";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'Vaccine already exist on the list.';
            $Error = "1";     
        }else{
            StoreData($VaccineName);
        }       
      }
  }

  function StoreData($VaccineName)
  {
      global $ClinicRecordsDB, $Message, $Error;

      $sql = "INSERT INTO db_vaccine_list (vaccine) VALUES ('$VaccineName')";  

      $Result = $ClinicRecordsDB->GetRows($sql);
      if ($Result) {
        $Message = 'Successfully added the information!'; 
        $Error = "0";
      }else{
        $Message = 'Database Storing Error!'; 
        $Error = "1";
      }
          
  }
?>
