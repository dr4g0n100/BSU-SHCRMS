<?php
    require_once 'Database.php';
    require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error;

    // Receive Data from Client
    $Num = $_POST['Num'];
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        DeleteUser();
      }
      else
      {
        $Message = 'Failed to delete account!';
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

    function DeleteUser(){
    $sql;

    //Access Global Variables
    global $Error, $ClinicRecordsDB, $Message, $Num;  
    
      $sql = "DELETE FROM USERACCOUNTS WHERE Num='$Num'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $Message = 'The user account is deleted successfully!'; 
      $Error = "0"; 
    }
?>
