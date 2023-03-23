<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $cons_date = $_POST['cons_date'];
  $id = $_POST['id'];

    $TxtConsultTimes = "";

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        FetchTime($cons_date,$id);
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
  $XMLData .= ' consultTimes = ' . '"'.$TxtConsultTimes.'"';
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function FetchTime($cons_date,$id){
    $sql;

    //Access Global Variables
    global $connection,$Error, $ClinicRecordsDB, $Message,$TxtConsultTimes;

      $query = "SELECT Times FROM consultationinfo WHERE IdNumb='$id' AND Dates='$cons_date'";

      $result = $connection->query($query);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $TxtConsultTimes .= $row['Times'] . ' ';

        }
        $Message = "Search completed!";
        $Error = "0"; 
      }else{
        $Message = "No consultation time record found.";
        $Error = "1";
      }

      
  }




?>