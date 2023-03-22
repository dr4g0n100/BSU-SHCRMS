<?php
require_once 'Database.php';
require '../../../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $studentID = $_POST['studentID'];
  /*$studentID = '123';*/

  $TxtFirstN  = '';
  $TxtMiddleN  = '';
  $TxtLastN = '';
  $TxtExtens = '';
  $TxtAges = '';
  $TxtSexs = '';
  $TxtCourseStrand = '';
  $TxtYears = '';
  $TxtDates = '';


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        if(checkIfExistArchive($studentID)){
          FetchStudent($studentID);
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
	$XMLData .= ' FirstN = ' . '"'.$TxtFirstN.'"';
  $XMLData .= ' MiddleN = ' . '"'.$TxtMiddleN.'"';
  $XMLData .= ' LastN = ' . '"'.$TxtLastN.'"';
  $XMLData .= ' Extens = ' . '"'.$TxtExtens.'"';
  $XMLData .= ' Ages = ' . '"'.$TxtAges.'"';
  $XMLData .= ' Sexs = ' . '"'.$TxtSexs.'"';
  $XMLData .= ' CourseStrand = ' . '"'.$TxtCourseStrand.'"';
  $XMLData .= ' Years = ' . '"'.$TxtYears.'"';
  $XMLData .= ' Dates = ' . '"'.$TxtDates.'"';
	$XMLData .= ' />';
	
	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $XMLData;
	echo '</Document>';

  function FetchStudent($studentID){
    $sql;

    //Access Global Variables
    global $connection, $Error, $ClinicRecordsDB, $Message, $TxtFirstN, $TxtMiddleN, $TxtLastN, $TxtExtens, $TxtAges, $TxtSexs, $TxtCourseStrand, $TxtYears, $TxtDates; 
  

      $sql = "SELECT * FROM personalmedicalrecord WHERE StudentIDNumber = '$studentID'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql); 
      $Row = $ClinicRecordQuery->fetch_array(); 

      if(empty($Row)){
          $sql = "SELECT * FROM archivedstudent WHERE StudentIDNumber='$studentID'";

          $Result = $ClinicRecordsDB->Execute($sql);
          
          $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
          $Row = $ClinicRecordQuery->fetch_array();                
          
      }
    
      if($ClinicRecordQuery)
      {
        
        if($Row)
          {        
            
            $TxtFirstN  =stripcslashes($Row['Firstname']);; 
            $TxtMiddleN  = stripcslashes($Row['Middlename']);;
            $TxtLastN = stripcslashes($Row['Lastname']);; 
            $TxtExtens = stripslashes($Row['Extension']);;
            $TxtAges = stripslashes($Row['Age']);;
            $TxtSexs = stripslashes($Row['Sex']);;
            $TxtCourseStrand = stripslashes($Row['Course']);;
            $TxtYears = stripslashes($Row['Year']);;
            //$TxtDates = stripslashes($Row['Dates']);;

            $query = "SELECT * FROM consultationinfo WHERE IdNumb = '$studentID'";
            $result = $connection->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $TxtDates .= $row['Dates'] . ' ';
                }
            }else{
                $Message = "No consultation record found.";
                $Error = "1";
            }

            
             
          }else{
            $Message = "No student record found.";
            $Error = "1";
          }            
      }
  }

  function checkIfExistArchive($studentID){
      global $ClinicRecordsDB, $Message, $Error;

      $sql = "SELECT StudentIDNumber FROM archivedstudent WHERE StudentIDNumber = '$studentID' ";

      $Result = $ClinicRecordsDB->Execute($sql);

      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'Student Record exists on archive. Please restore Student Record first.';
            $Error = "1";
            return false;     
        }else{
            return true;
        }       
      }
  }

?>