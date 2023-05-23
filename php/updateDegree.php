<?php
  require_once 'Database.php';
  require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

    $degree_name = trim($_POST['degree_name']);
    $degree_col = trim($_POST['degree_col']);
    $degree_id = trim($_POST['degree_id']);
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($degree_id);
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

  function CheckRecord($degree_id){

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $Error;  
    
      $sql = "SELECT COUNT(id) FROM db_degree_list WHERE id = '$degree_id'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {     
            updateData();   
                 
        }else{

            $Message = 'Could not save, Degree data does not exist on the list.';
            $Error = "1";
        }       
      }
  }

  function updateData()
  {
      global $ClinicRecordsDB, $Message, $Error, $degree_name, $degree_col, $degree_id;

      $sql = "UPDATE db_degree_list SET " .$degree_col ." = '$degree_name' WHERE id='$degree_id'  ";   

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
