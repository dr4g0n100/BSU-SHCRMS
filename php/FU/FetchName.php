<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $temp = $_POST['temp'];
  /*$temp = '123';*/

    $TxtFirstName = "";
    $TxtMiddleName = "";
    $TxtLastName = "";
    $TxtExtension = "";
    $TxtAge = "";
    $TxtSex = "";
    $TxtCourseStrand = "";
    $TxtYear = "";
    $TxtConsultDates = "";
    $TxtConsultTimes = "";

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        //if(checkIfExistArchive($temp)){       
            FetchUser($temp);
        //}
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
  $XMLData .= ' consultDates = ' . '"'.$TxtConsultDates.'"';
  $XMLData .= ' consultTimes = ' . '"'.$TxtConsultTimes.'"';
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

      if(empty($Row)){
          $sql = "SELECT * FROM archivedstudent WHERE StudentIDNumber='$temp'";

          $Result = $ClinicRecordsDB->Execute($sql);
          
          $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
          $Row = $ClinicRecordQuery->fetch_array();                
          
      }

      if($ClinicRecordQuery)
      {
        
        if($Row)
          {  
            if(CheckCons($temp)){      
              $TxtFirstName = stripslashes($Row['Firstname']);;
              $TxtMiddleName = stripslashes($Row['Middlename']);;
              $TxtLastName = stripslashes($Row['Lastname']);;
              $TxtExtension = stripslashes($Row['Extension']);;
              $TxtAge = stripslashes($Row['Age']);;
              $TxtSex = ucwords(stripslashes($Row['Sex']));;
              $TxtCourseStrand = stripslashes($Row['Course']);;
              $TxtYear = stripslashes($Row['Year']);;
              $Message = "Search completed!";
              $Error = "0"; 
            }
          }else{
            $Message = "No Student Record found. Please make sure to create a student record first at Student page.";
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

  function CheckCons($tempID){
      global $connection,$ClinicRecordsDB, $Message,$Error,$TxtConsultDates,$TxtConsultTimes; 

      $sql = "SELECT IdNumb FROM consultationinfo WHERE IdNumb = '$tempID' ";
      $Result = $ClinicRecordsDB->Execute($sql);
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);  

      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();            
        if($Row)
        { 

              $query = "SELECT * FROM consultationinfo WHERE IdNumb = '$tempID'";
              $result = $connection->query($query);

              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $TxtConsultDates .= $row['Dates'] . ' ';
                    $TxtConsultTimes .= $row['Times'] . ' ';
                }
              }else{
                  $Message = "No consultation record found.";
                  $Error = "1";
              }

          return true;  
        }else{

          $query = "SELECT * FROM archivedconsultation WHERE IdNumb = '$tempID'";
          $result = $connection->query($query);

          if ($result->num_rows > 0) {
            $Message = 'Please restore Consultation Record first before you can access the data';
            $Error = "1";        
            return false;
          }else{
            $Message = 'No Consultation record found. Please make sure to create consultation record first at the Consultation page.';
            $Error = "1";        
            return false;
          }

          
        }       
      }
    }



?>