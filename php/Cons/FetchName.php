<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $temp = $_POST['temp'];

    $TxtFirstName = "";
    $TxtMiddleName = "";
    $TxtLastName = "";
    $TxtExtension = "";
    $TxtAge = "";
    $TxtSex = "";
    $TxtCourseStrand = "";
    $TxtYear = "";

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        if(checkIfExistArchive($temp)){
          FetchUser($temp);
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
  $XMLData .= ' FirstName = ' . '"'.$TxtFirstName.'"';
  $XMLData .= ' MiddleName = ' . '"'.$TxtMiddleName.'"';
  $XMLData .= ' LastName = ' . '"'.$TxtLastName.'"';
  $XMLData .= ' Extension = ' . '"'.$TxtExtension.'"';
  $XMLData .= ' Age = ' . '"'.$TxtAge.'"';
  $XMLData .= ' Sex = ' . '"'.$TxtSex.'"';
  $XMLData .= ' CourseStrand = ' . '"'.$TxtCourseStrand.'"';
  $XMLData .= ' Year = ' . '"'.$TxtYear.'"';
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
    global $Error, $ClinicRecordsDB, $Message,$TxtFirstName,$TxtMiddleName,$TxtLastName,$TxtExtension,$TxtAge,$TxtSex,$TxtCourseStrand,$TxtYear;

      $sql = "SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber='$temp'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
      $Row = $ClinicRecordQuery->fetch_array();                

      /*if(empty($Row)){
          $sql = "SELECT * FROM archivedstudent WHERE StudentIDNumber='$temp'";

          $Result = $ClinicRecordsDB->Execute($sql);
          
          $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
          $Row = $ClinicRecordQuery->fetch_array();                
          
      }*/

      if($ClinicRecordQuery)
      {
        
        if($Row)
          {        
            $TxtFirstName = stripslashes($Row['Firstname']);;
            $TxtMiddleName = stripslashes($Row['Middlename']);;
            $TxtLastName = stripslashes($Row['Lastname']);;
            $TxtExtension = stripslashes($Row['Extension']);;
            $TxtAge = stripslashes($Row['Age']);;
            $TxtSex = stripslashes($Row['Sex']);;
            $TxtCourseStrand = stripslashes($Row['Course']);;
            $TxtYear = stripslashes($Row['Year']);;
            $Message = "Search completed!";
            $Error = "0"; 
          }else{
            $Message = "No user found. Please try again.";
            $Error = "1";
          }            
      }
  }


  function checkIfExistArchive($temp){
      global $ClinicRecordsDB, $Message, $Error;

      $sql = "SELECT StudentIDNumber FROM archivedstudent WHERE StudentIDNumber = '$temp' ";

      $Result = $ClinicRecordsDB->Execute($sql);

      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        {         
            $Message = 'Student Record exists on Archive. Please restore Student Record first.';
            $Error = "1";
            return false;     
        }else{
            return true;
        }       
      }
  }



?>