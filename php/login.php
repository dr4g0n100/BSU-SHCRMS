<?php
  require_once 'Database.php';
  require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');
  session_start();

  $Message = "Incorrect username or password. Please try again.";
  $Verify = false;
  $LoginChances;
  $AccStatus="notExist";
  $errors = array();
  $Fullname = "";

    // Receive Data from Client
    $TxtUserName = $_POST['TxtUsername'];
    $TxtPassword = $_POST['TxtPassword'];
    $Level = $_POST['Level'];
    $AccStatus = "";
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        VerifyUser();
      }
      else
      {
        $Message = 'Failed to search user!';
        $Verify = false;
      }
    }  
    else
    {
      $Message = 'The database is offline!';
      $Verify = false;   
    } 

    $XMLData = '';	
    $XMLData .= ' <output ';
    $XMLData .= ' Message = ' . '"'.$Message.'"';
    $XMLData .= ' Verify = ' . '"'.$Verify.'"';
    $XMLData .= ' AccStatus = ' . '"'.$AccStatus.'"';
    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';    	
    echo $XMLData;
    echo '</Document>';

  function VerifyUser(){
    $sql;

    //Access Global Variables
    global $Verify, $ClinicRecordsDB, $Message, $AccessLevel, $TxtUserName, $TxtPassword, $Level, $AccStatus;  

    /*$TxtUserName = strtolower($TxtUserName);*/
    
    if($Level == "0"){
      $sql = "SELECT * FROM USERACCOUNTS WHERE UserName = '$TxtUserName' AND AccessLevel = 'standard'";
      $_SESSION['isStandard'] = true;
    }else{
      $sql = "SELECT * FROM USERACCOUNTS WHERE UserName = '$TxtUserName' AND (AccessLevel = 'admin' || AccessLevel = 'superadmin')";
      $_SESSION['isStandard'] = false;
    }
      $_SESSION['user'] = "$TxtUserName"; // used for logging purposes
      
      $Result = $ClinicRecordsDB->Execute($sql);
      
      $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);                
    
      if($ClinicRecordQuery)
      {            
        $Row = $ClinicRecordQuery->fetch_array();
        if($Row)
          {   
            
            $AccStatus = stripslashes($Row['AccStatus']);

            // Store a string into the variable which need to be Encrypted
            $simple_string = stripslashes($Row['Password']);
  
            // Store the cipher method
            $ciphering = "AES-128-CTR";
  
            // Use OpenSSl Encryption method
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
  
            // Non-NULL Initialization Vector for decryption
            $decryption_iv = '1234567891011121';
  
            // Store the decryption key
            $decryption_key = "BenguetStateUniversityMedicalClinic";
  
            // Use openssl_decrypt() function to decrypt the data
            $decryption=openssl_decrypt ($simple_string, $ciphering, $decryption_key, $options, $decryption_iv);

            if($decryption == $TxtPassword){


              $sql = "UPDATE USERACCOUNTS SET LoginChance = 3 WHERE UserName = '$TxtUserName'";
              $Result = $ClinicRecordsDB->Execute($sql);


 
              $position = stripslashes($Row['Position']);
              if($position == 'Doctor'){
                $position = 'Dr';
              }else if($position == 'Nurse'){
                $position = 'Nurse';
              }else if($position == 'Medical Technologist'){
                $position = 'Med Tech';
              }else if($position == 'Administrative Aide'){
                $position = 'Admin Aide';
              }else if($position == 'Triage Officer'){
                $position = 'T.O.';
              }
              $_SESSION['homePosDisp'] = $position;
              $_SESSION['userID'] = stripslashes($Row['IdNum']);
              $_SESSION['logged_in'] = 1;
              $_SESSION['user'] = "$TxtUserName";
              $_SESSION['position'] = "$Level";
              
              $First = stripslashes($Row['FirstName']);
              $_SESSION['userFirstname'] = $First;
              $Middle = substr(stripslashes($Row['MiddleName']), 0, 1);
              if($Middle != ''){
                $Middle .= '.';
              }
              $_SESSION['userMiddlename'] = $Middle;
              $Last = stripslashes($Row['LastName']);
              $_SESSION['userLastname'] = $Last;
              $Extension = stripslashes($Row['Extension']);
              $_SESSION['userExtension'] = $Extension;

              $Fullname = "$First $Middle $Last $Extension";
              $_SESSION['fullname'] = strtoupper($Fullname);

              $acclevel = stripslashes($Row['AccessLevel']);
              $_SESSION['accesslevel'] = "$acclevel";


              $Message = "Login Successfully.";
              $Verify = true;
            }
            //if wrong login info entered
            else{
              //reduce login chances by 1 when login fail using username as reference
              $LoginChances = stripslashes($Row['LoginChance']);
              if($LoginChances > 1){
                //test if user is staff to reduce login chances
                if($_SESSION['isStandard']){
                  $sql = "UPDATE USERACCOUNTS SET LoginChance = (LoginChance - 1) WHERE UserName = '$TxtUserName'";
                  $Result = $ClinicRecordsDB->Execute($sql);

                  $LoginChances--;
                }
                  $Message = "Incorrect username or password. Please try again.";
                  $Verify = false;
                
              }
              //set account to inactive and send OTP to Admin
              else{
                $sql = "UPDATE USERACCOUNTS SET AccStatus = 'Blocked' WHERE UserName = '$TxtUserName'";
                $Result = $ClinicRecordsDB->Execute($sql);
                
                $Message = "Your account is locked. To unlock it, please contact your Admin";
                $Verify = false;

                //used to skip enter email for staff account to verify code immediately
                if ($_SESSION['isStandard']) {
                  $_SESSION['email'] = stripslashes($Row['Email']);
                  $info = "We've sent a password reset code to the admin. Please contact the admin for the code";
                  $_SESSION['info'] = $info;

                  //will only permit one code at a time
                  if(stripslashes($Row['code']) == null){
                      $email = $_SESSION['email'];
                      $code = rand(999999, 111111);
                      $sql = "UPDATE USERACCOUNTS SET code = $code WHERE email = '$email'";
                      $Result = $ClinicRecordsDB->GetRows($sql);
                      //if result from query exist
                      /*if($Result){
                          //get email of ADMIN.... (remove comment brackets if want to send email to admin)
                          $sql = "SELECT Email FROM USERACCOUNTS WHERE AccessLevel = 'admin'";
                          $Result = $ClinicRecordsDB->Execute($sql);
                          $ClinicRecordQuery = $ClinicRecordsDB->GetRows($sql);
                          $Result = $ClinicRecordQuery->fetch_array();
                          $email = stripslashes($Result['Email']);

                          $subject = "Password Reset Code";
                          $message = "Your password reset code is $code";
                          $sender = "From: cadungoedrianjoepen@gmail.com";
                          if(mail($email, $subject, $message, $sender)){
                              $info = "We've sent a password reset otp to your email. Contact admin for verification code if your email didn't received a code.";
                              $_SESSION['info'] = $info;
                          }
                      }*/
                  }
                  
                }
              }
            }

            //used to skip enter email for staff account to verify code immediately
            if ($_SESSION['isStandard']) {
                $_SESSION['email'] = stripslashes($Row['Email']);
            }
          }else{
            $Message = "Staff account does not exist";
            $Verify = false;
          }           
      }
  }
?>
