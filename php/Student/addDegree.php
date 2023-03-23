<?php
  require_once 'Database.php';
  require '../centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

    $DegreeCategory = $_POST['degree-category'];
    $DegreeName = $_POST['degree-name'];
    $DegreeAcronym = $_POST['degree-acr'];

    /*$DegreeCategory = 'graduate';
    $DegreeName = 'asd';
    $DegreeAcronym = 'asd';*/
    
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        CheckRecord($DegreeName, $DegreeAcronym);
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

  function CheckRecord($DegreeName, $DegreeAcronym){

    //Access Global Variables
    global $ClinicRecordsDB, $Message, $Error;  
    
      $sql = "SELECT id FROM db_degree_list WHERE degree = '$DegreeName' ";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'Degree already exist on the list.';
            $Error = "1";     
        }else{

            $sql = "SELECT id FROM db_degree_list WHERE degree_acr = '$DegreeAcronym' ";

            $Result = $ClinicRecordsDB->Execute($sql);

            $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);

            if($ClinicRecordQuery)
            {
              $Row = $ClinicRecordQuery->fetch_array();
              if($Row)
              {
                $Message = 'Acronym already exist on the list.';
                $Error = "1";
              }else{
                StoreData();
              }
            } 

            
        }       
      }
  }

  function StoreData()
  {
      global $ClinicRecordsDB, $Message, $Error, $DegreeCategory, $DegreeName, $DegreeAcronym;

      $sql = "INSERT INTO db_degree_list (degree_acr,degree,degree_category) VALUES ('$DegreeAcronym','$DegreeName','$DegreeCategory')";  

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
