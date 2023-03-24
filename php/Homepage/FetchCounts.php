<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  /*$startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];*/

  /*$startDate = "2023-03-10";
  $endDate = "2023-03-10";*/

  $startDate = '';
  $endDate = date("Y-m-d");

  $sql = "SELECT Date FROM PersonalMedicalRecord ORDER BY Date ASC LIMIT 1";
        $result = mysqli_query($connection, $sql);

  if(mysqli_num_rows($result) > 0){
    $Row = $result->fetch_array(); 
    if($Row){        
        $startDate = stripslashes($Row['Date']);;
        }
    }

  $startDate = date("Y-m-d", strtotime($startDate));
  //$endDate = date("Y-m-d", strtotime($endDate));

    $dates = "";
    $Staffs = "";
    $CountPM = "";
    $CountCons = "";
    $CountFU = "";
    $CountMC = "";
    $CountMale = "";
    $CountFemale = "";
    $CountElem = "";
    $CountHS = "";
    $CountSHS = "";
    $CountCollege = "";
    $CountGrad = "";



    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        
          FetchCount($startDate, $endDate);
        
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
  $XMLData .= ' dates = ' . '"'.$dates.'"';
  $XMLData .= ' Staffs = ' . '"'.$Staffs.'"';
  $XMLData .= ' CountPM = ' . '"'.$CountPM.'"';
  $XMLData .= ' CountCons = ' . '"'.$CountCons.'"';
  $XMLData .= ' CountFU = ' . '"'.$CountFU.'"';
  $XMLData .= ' CountMC = ' . '"'.$CountMC.'"';
  $XMLData .= ' CountMale = ' . '"'.$CountMale.'"';
  $XMLData .= ' CountFemale = ' . '"'.$CountFemale.'"';
  $XMLData .= ' CountElem = ' . '"'.$CountElem.'"';
  $XMLData .= ' CountHS = ' . '"'.$CountHS.'"';
  $XMLData .= ' CountSHS = ' . '"'.$CountSHS.'"';
  $XMLData .= ' CountCollege = ' . '"'.$CountCollege.'"';
  $XMLData .= ' CountGrad = ' . '"'.$CountGrad.'"';
  $XMLData .= ' startDate = ' . '"'.$startDate.'"';
  $XMLData .= ' endDate = ' . '"'.$endDate.'"';

    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';      
    echo $XMLData;
    echo '</Document>';

  function FetchCount($startDate, $endDate){
    $sql;

    //Access Global Variables
    global $connection, $Error, $ClinicRecordsDB, $Message, $Staffs, $CountPM, $CountCons, $CountFU, $CountMC, $CountMale, $CountFemale, $CountElem, $CountHS, $CountSHS, $CountCollege, $CountGrad, $dates;

    $datesArr = array();
    $StaffsArr = array();
    $CountPMArr = array();
    $CountConsArr = array();
    $CountFUArr = array();
    $CountMCArr = array();
    $CountMaleArr = array();
    $CountFemaleArr = array();
    $CountElemArr = array();
    $CountHSArr = array();
    $CountSHSArr = array();
    $CountCollegeArr = array();
    $CountGradArr = array();

    $sql = "SELECT * FROM useraccounts";
    $result = mysqli_query($connection, $sql);

    if(mysqli_num_rows($result) > 0){
        while ($Row = $result->fetch_array()) {
            if($Row){     
                $LastName = stripslashes($Row['LastName']);;
                $FirstName = stripslashes($Row['FirstName']);;
                $StaffsArr[] = "$LastName, $FirstName";
              }
        }
         
                   
    }else{
      $StaffsArr[] = "";
    }

    $startDateObj = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);
    $endDateObj->modify('+1 day');

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($startDateObj, $interval, $endDateObj);

    foreach ($period as $dt) {

        $SubCountPMArr = array();
        $SubCountConsArr = array();
        $SubCountFUArr = array();
        $SubCountMCArr = array();

        $dt1= $dt-> format("Y-m-d");
        $dt2 = date('Y-m-d', strtotime($dt1. ' + 1 days'));
        $datesArr[] =$dt-> format("F-d-Y");
        //$datesArr[] = date("F-d-Y", strtotime($dt1. ' + 1 days'));

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='elementary' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='elementary' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='junior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='junior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='senior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='senior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='college' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='college' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='graduate' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE (Date >= '$dt1' AND Date < '$dt2') AND (StudentCategory='graduate' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountPMArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountPMArr[] = 0;
        }

    $CountPMArr[] = implode("-",$SubCountPMArr);

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='elementary' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='elementary' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='junior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='junior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='senior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='senior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='college' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='college' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='graduate' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM ConsultationInfo LEFT JOIN personalmedicalrecord ON ConsultationInfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='graduate' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountConsArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountConsArr[] = 0;
        }

    $CountConsArr[] = implode("-",$SubCountConsArr);

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='elementary' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='elementary' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='junior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='junior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='senior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='senior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='college' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='college' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='graduate' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (Dates >= '$dt1' AND Dates < '$dt2') AND (StudentCategory='graduate' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountFUArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountFUArr[] = 0;
        }

    $CountFUArr[] = implode("-",$SubCountFUArr);

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='elementary' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='elementary' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='junior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='junior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='senior highschool' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='senior highschool' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='college' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='college' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='graduate' AND Sex='male')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (date_requested >= '$dt1' AND date_requested < '$dt2') AND (StudentCategory='graduate' AND Sex='female')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $SubCountMCArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $SubCountMCArr[] = 0;
        }

    $CountMCArr[] = implode("-",$SubCountMCArr);

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE sex='male' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountMaleArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountMaleArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE sex='female' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountFemaleArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountFemaleArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE StudentCategory='elementary' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountElemArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountElemArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE StudentCategory='junior highschool' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountHSArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountHSArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE StudentCategory='senior highschool' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountSHSArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountSHSArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE StudentCategory='college' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountCollegeArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountCollegeArr[] = 0;
        }

        $sql = "SELECT COUNT(*) FROM PersonalMedicalRecord WHERE StudentCategory='graduate' AND (Date >= '$dt1' AND Date < '$dt2')";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            $Row = $result->fetch_array(); 
            if($Row){        
                $CountGradArr[] = stripslashes($Row['COUNT(*)']);;
              }           
        }else{
          $CountGradArr[] = 0;
        }

        $Staffs = implode("-",$StaffsArr);
        $CountMale = implode(", ",$CountMaleArr);
        $CountFemale = implode(", ",$CountFemaleArr);
        $CountElem = implode(", ",$CountElemArr);
        $CountHS = implode(", ",$CountHSArr);
        $CountSHS = implode(", ",$CountSHSArr);
        $CountCollege = implode(", ",$CountCollegeArr);
        $CountGrad = implode(", ",$CountGradArr);

        $dates = implode(", ",$datesArr);
        $CountPM = implode(", ",$CountPMArr);
        $CountCons = implode(", ",$CountConsArr);
        $CountFU = implode(", ",$CountFUArr);
        $CountMC = implode(", ",$CountMCArr);
        
    }

        

      /*$sql = "SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber='$temp'";

      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
      $Row = $ClinicRecordQuery->fetch_array();                

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
      }*/
  }

?>