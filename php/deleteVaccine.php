<?php
  require_once 'Database.php';
  require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

    $id = $_POST['id'];

    //$id = '50';
    
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($id);
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

  function CheckRecord($id){

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $Error;  
    
      $sql = "SELECT COUNT(id) FROM db_vaccine_list WHERE id = '$id'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row['COUNT(id)'] > 0)
        {     
            deleteData();   
                 
        }else{

            $Message = 'Could not delete, vaccine does not exist on the list.';
            $Error = "1";
        }       
      }
  }

  function deleteData()
  {
      global $ClinicRecordsDB, $Message, $Error, $id;

      $sql = "DELETE FROM db_vaccine_list WHERE id='$id'  ";   

      $Result = $ClinicRecordsDB->GetRows($sql);
      if ($Result) {
        $Message = 'Successfully deleted the information!'; 
        $Error = "0";
      }else{
        $Message = 'Database Storing Error!'; 
        $Error = "1";
      }
          
  }
?>
