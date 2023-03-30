<?php 
session_start();
require 'centralConnection.php';
$email = "";
$name = "";
$errors = array();

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM useraccounts WHERE email='$email' AND (AccessLevel = 'admin' || AccessLevel = 'superadmin')";
        $run_sql = mysqli_query($con, $check_email);
        

            
             

        if(mysqli_num_rows($run_sql) > 0){

            $row_user_info = $run_sql -> fetch_array(MYSQLI_ASSOC);
            $userID = $row_user_info['IdNum'];
            $Username = $row_user_info['Username'];
            $AccessLevel = $row_user_info['AccessLevel'];

            $_SESSION['userID'] = $userID;
            $_SESSION['Username'] = $Username;
            $_SESSION['AccessLevel'] = $AccessLevel;

            $code = rand(999999, 111111);
            $insert_code = "UPDATE useraccounts SET code = $code WHERE email = '$email' AND (AccessLevel = 'admin' || AccessLevel = 'superadmin')";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "From: cadungoedrianjoepen@gmail.com";
                if(mail($email, $subject, $message, $sender)){
                    $info = "We've sent a password reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;

                    //for logging purposes
                    $logAction = "INSERT INTO SYSTEMLOGS (userID,username,action, date, position) 
                            VALUES ('$userID','$Username','User email $email requested OTP code', CURRENT_TIMESTAMP,'$AccessLevel')";
                    $run_sql = mysqli_query($con, $logAction);

                    header('location: reset-code.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code! Check your internet connection";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM useraccounts WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['Email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            $_SESSION['isCorrectOTP'] = true;
            
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session

            // Store a string into the variable which need to be Encrypted
            $simple_string = $password;
      
            // Store the cipher method
            $ciphering = "AES-128-CTR";
      
            // Use OpenSSl Encryption method
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
      
            // Non-NULL Initialization Vector for encryption
            $encryption_iv = '1234567891011121';
      
            // Store the encryption key
            $encryption_key = "BenguetStateUniversityMedicalClinic";
      
            // Use openssl_encrypt() function to encrypt the data
            $encpass = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);

            /*$encpass = password_hash($password, PASSWORD_DEFAULT);*/
            /*$update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";*/
            
            $update_pass = "UPDATE useraccounts SET password = '$encpass', AccStatus = 'Active', LoginChance = 3, code = null WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;

                //for logging purposes
                $userID = $_SESSION['userID'];
                $Username = $_SESSION['Username'];
                $AccessLevel = $_SESSION['AccessLevel'];
                $logAction = "INSERT INTO SYSTEMLOGS (userID,username,action, date, position) 
                            VALUES ('$userID','$Username','User email $email Successfully changed password', CURRENT_TIMESTAMP,'$AccessLevel')";
                $run_sql = mysqli_query($con, $logAction);

                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: ../index.html');
    }
?>