<?php
require '../php/centralConnection.php';
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    } 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Staff</title>
        
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies1.php'; ?>

        <link rel="stylesheet" href="../css/addStaff-style.css">

        <script type="text/javascript">
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var getType ="";
            var accessLevel = "";
            var globalAL = "";

            /* function ValidateEmail(input) {

                var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.com*$/;

                if (input.value.match(validRegex)) {
                    return true;
                } else {
                    return false;
                }
            } */

            async function clickedPDF(){
                const element = document.getElementById('toDownloadPDF');

                $.confirm({
                        title: '',
                        content: 'Exporting file. Please wait.',
                        theme: 'supervan',
                        buttons: 
                        {
                            Yes:{ 
                                text: ' ',                          
                                btnClass: 'btn-orange'
                            }
                        }
                    }); 

                document.getElementById('tabs-bodyID').style.backgroundColor = "white";
                document.getElementById('toDownloadPDF').style.marginTop = "0";
                document.getElementById('toDownloadPDF').style.paddingTop = "0";
                document.getElementById('tab1').style.display = "none";
                document.getElementById('wholetab').style.display = "flex";
                document.getElementById('wholetab').style.width = "100%";
                document.getElementById('wholetab').style.padding = "2%";
                document.getElementById('wholetab').style.alignItems = "center";
                document.getElementById('wholetab').style.justifyContent = "center";
                document.getElementById('wholetab').style.cursor = "pointer";
                document.getElementById('wholetab').style.fontSize = "1.8vw";
                document.getElementById('wholetab').style.fontWeight = "bold";
                document.getElementById('wholetab').style.background = "white";
                document.getElementById('wholetab').style.color = "#062102";
				
                var opt = {
                    margin: 0.5,
                    filename: 'User Information.pdf',
                    jsPDF:{
                        unit: 'in'
                    }
                };

				html2pdf().set(opt).from(element).save().then(
                    function(){
                        setTimeout(function(){
                            location.reload();
                        }, 5000);
                    }
                );
            }

            function clickedPrint(printID){
                var idNum = document.getElementById('TxtStaffIDNumber').value;
                var type = getType;


                window.location.href = '../php/printUser.php?id=' +idNum +'&type=' +type;
            }


            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
                /*alert("logs success");*/
                  $.ajax({
                    url:"../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../index.html';
                        }, 100);
                    }
                  })
            }

            //main function for user activity logging
            function logAction(userAction){
                act = userAction;
                $.ajax({
                    url:"../php/logAction.php",
                    method:"POST",
                    data:jQuery.param({ action: act, isSuccess:"1" }),
                    dataType: "xml",
                    success:function(xml){

                    }
                  })
            }

            //called to log user clicking "logs" tab
            function userCheckLogs(){
                act = "Checked user activities." 
                logAction(act);
            }
        // ---------------------------end functions for System Logs---------------------------------------

            var TempPosition;
            var TempBtnValue;

            /* function logout(){
            sessionStorage.clear();
            } */

            function styleInput(idnum){
                document.getElementById(idnum).style.background = "none";  
                document.getElementById(idnum).style.borderBottom = "solid 2px black";    
                document.getElementById(idnum).style.borderTop = "solid 1px gray"; 
                document.getElementById(idnum).style.borderRight = "solid 1px gray"; 
                document.getElementById(idnum).style.borderLeft = "solid 1px gray";  
            }

            function alphaName(event){
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 189);
            }

            function checkNameLength(name){
                var origName = name.value.trim();

                var nameVal = name.value.replace(/-/g, "");
                nameval = nameVal.trim();

                if(nameVal.length < 1){
                    $.alert(
                        {theme: 'modern',
                        content:'Name should be atleast 1 character',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    name.value = '';
                }else{
                    name.value = origName;
                }
            }

            //This function allows numbers, letters, enye, -
            function allowLetterNumber(event){
                var chara = "";
                var key = event.keyCode;
                var shift = event.shiftKey;

                if (key == 48){
                    if(shift){
                        return false;
                    }else{
                        return "0";
                    }
                }else if(key == 49){
                    if(shift){
                        return false;
                    }else{
                        return "1";
                    }
                }else if(key == 50){
                    if(shift){
                        return false;
                    }else{
                        return "2";
                    }
                }else if(key == 51){
                    if(shift){
                        return false;
                    }else{
                        return "3";
                    }
                }else if(key == 52){
                    if(shift){
                        return false;
                    }else{
                        return "4";
                    }
                }else if(key == 53){
                    if(shift){
                        return false;
                    }else{
                        return "5";
                    }
                }else if(key == 54){
                    if(shift){
                        return false;
                    }else{
                        return "6";
                    }
                }else if(key == 55){
                    if(shift){
                        return false;
                    }else{
                        return "7";
                    }
                }else if(key == 56){
                    if(shift){
                        return false;
                    }else{
                        return "8";
                    }
                }else if(key == 57){
                    if(shift){
                        return false;
                    }else{
                        return "9";
                    }
                }else if(key == 189){
                    if(shift){
                        return false;
                    }else{
                        return "_";
                    }
                }else{
                    return ((key >= 65 && key <= 90) || (key >= 96 && key <= 105) || key == 18 || key == 8 || key == 32 || key == 165 || key == 164);
                }
            }

            function alphaOnly(event){
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8 || key == 32);
            }

            function checkContNumFormat(Num){
                

                var mobileNum = Num.value;
                
                var fThreeMobileNum = mobileNum.substring(0,4);

                var ContNumerror = false;
                var msg = '';

                if (fThreeMobileNum != '+639'){
                    ContNumerror = true;
                    msg = 'Contact number should begin with +639';
                    
                }else if(mobileNum.length != 13){
                    ContNumerror = true;
                    msg = 'Contact number is incomplete';
                }

                if (ContNumerror){
                    $.alert({
                        theme: 'modern',
                        content: msg,
                        title: '',
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass:'btn-red'
                        }}});
                    Num.value = '+639';
                    
                }


                
            }

             function editTableNav(y){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived User Information&nbsp;&bull;';
                    document.getElementById('userlistID').classList.remove('active');
                    document.getElementById('archivedID').classList.add('active');
                    document.getElementById('maint').classList.add("active");
                    document.getElementById('maint').style.color = "white";
                }else{
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;User Information&nbsp;&bull;';
                }
            }

            function passIDPHP(x){
                var form_data = new FormData();
                var Num = x;
                
                $('#TxtStaffIDNumber').val(Num);
                radio = document.getElementById("RadOld");
                radio.checked = true;

                clickedOld();
            }

            function isAdmin(x){
                if (accessLevel == "admin" && x == "admin"){
                    document.getElementById("twoButton").style.display = "none";
                    document.getElementById('exportButton').style.display = 'none';

                    document.getElementById("RadNew").setAttribute("disabled","disabled");
                    document.getElementById("RadOld").setAttribute("disabled","disabled");
                    document.getElementById("TxtStaffIDNumber").setAttribute('readonly','readonly');
                    styleInput("TxtStaffIDNumber");
                }
            }

            function hideButton(){
                document.getElementById("twoButton").style.display = "none";
                document.getElementById('exportButton').style.display = 'none';

                document.getElementById("RadNew").setAttribute("disabled","disabled");
                document.getElementById("RadOld").setAttribute("disabled","disabled");
                document.getElementById("TxtStaffIDNumber").setAttribute('readonly','readonly');
                styleInput("TxtStaffIDNumber");
            }


            function btnValue(valu){
                TempBtnValue = valu;
            }

            function clickedEdit(){
               
                /*document.getElementById("TxtStaffIDNumber").setAttribute("readonly","readonly");
                document.getElementById("RadStatus").setAttribute("readonly","readonly");*/
                document.getElementById("BtnClear").removeAttribute("disabled");  
                document.getElementById("BtnSave").removeAttribute("disabled");  
                document.getElementById("TxtFirstname").removeAttribute("readonly");       
                document.getElementById("TxtLastname").removeAttribute("readonly");   
                document.getElementById("TxtMiddlename").removeAttribute("readonly");  
                document.getElementById("TxtExtension").removeAttribute("readonly");  
                document.getElementById("TxtRank").removeAttribute("readonly");  
                document.getElementById("TxtEmail").removeAttribute("readonly");  
                document.getElementById("RadDoctor").removeAttribute("disabled");  
                document.getElementById("RadNurse").removeAttribute("disabled");  
                document.getElementById("RadClinicAid").removeAttribute("disabled");  
                document.getElementById("RadMT").removeAttribute("disabled");  
                document.getElementById("RadTO").removeAttribute("disabled"); 
                document.getElementById("TxtUsername").removeAttribute("readonly");  
                document.getElementById("TxtPassword").removeAttribute("readonly");  
                document.getElementById("TxtConfirmPassword").removeAttribute("readonly"); 
                document.getElementById("TxtContactNumber").removeAttribute("readonly"); 

                /*document.getElementById('TxtStaffIDNumber').style.backgroundColor = "white";*/
                document.getElementById('TxtLastname').style.backgroundColor = "white";    
                document.getElementById('TxtFirstname').style.backgroundColor = "white"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "white"; 
                document.getElementById('TxtExtension').style.backgroundColor = "white"; 
                document.getElementById('TxtRank').style.backgroundColor = "white"; 
                document.getElementById('TxtContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtEmail').style.backgroundColor = "white"; 
                document.getElementById('TxtUsername').style.backgroundColor = "white"; 
                document.getElementById('TxtPassword').style.backgroundColor = "white";
                document.getElementById('TxtConfirmPassword').style.backgroundColor = "white";  
            }


            function setAttr(){
                
                document.getElementById("TxtLastname").setAttribute('readonly','readonly');
                document.getElementById("TxtFirstname").setAttribute('readonly','readonly');
                document.getElementById("TxtMiddlename").setAttribute('readonly','readonly');
                document.getElementById("TxtExtension").setAttribute('readonly','readonly');
                if(TempPosition == "doctor"){
                    $('#RadDoctor').prop('checked', true);
                   
                }else if(TempPosition == "nurse"){
                    $('#RadNurse').prop('checked', true);
                    
                }else if(TempPosition == "administrative aide"){
                    $('#RadClinicAid').prop('checked',true);   
                }else if(TempPosition == "medical technologist"){
                    $('#RadMT').prop('checked',true);
                }else if(TempPosition == "triage officer"){
                    $('#RadTO').prop('checked',true);
                }
                document.getElementById("RadDoctor").setAttribute('disabled','disabled');
                document.getElementById("RadNurse").setAttribute('disabled','disabled');
                document.getElementById("RadClinicAid").setAttribute('disabled','disabled');
                document.getElementById("RadMT").setAttribute('disabled','disabled');
                document.getElementById("RadTO").setAttribute('disabled','disabled');
                document.getElementById("TxtRank").setAttribute('readonly','readonly');
                document.getElementById("TxtContactNumber").setAttribute('readonly','readonly');
                document.getElementById("TxtEmail").setAttribute('readonly','readonly');
                document.getElementById("TxtUsername").setAttribute('readonly','readonly');
                document.getElementById("TxtPassword").setAttribute('readonly','readonly');
                document.getElementById("TxtConfirmPassword").setAttribute('readonly','readonly');
                document.getElementById("BtnEdit").removeAttribute("disabled");
                document.getElementById("BtnSave").setAttribute('disabled','disabled');
                document.getElementById("BtnClear").removeAttribute("disabled");
                document.getElementById("BtnAdd").style.display = 'none';
                document.getElementById("BtnEdit").style.display = 'flex';
                document.getElementById('BtnPrint').style.display = "flex"; 
                document.getElementById('BtnPDF').style.display = "none";
                document.getElementById("BtnSave").style.display = 'flex';
                document.getElementById("BtnClear").style.display = 'flex';
            }

            function fetchName(){
                var idnum = document.getElementById('TxtStaffIDNumber').value;
                
                var form_data = new FormData();
                form_data.append("idnum", idnum);

                $.ajax(
                { 
                    url:"../php/Staff/FetchName.php",
                    method:"POST",
                    data:form_data, 
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "xml",
                    success:function(xml)
                    {
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            var error = $(this).attr('Error');
                        
                            if(error == "1"){
                            //Display Alert Box
                                $.alert(
                                {theme: 'modern',
                                    content: message,
                                    title:'', 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                    }}});

                                   
                            }

                                
                        });
                    },  
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to fetch information due to error',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }

            function fetchEmail(){
                var temp = document.getElementById('TxtEmail').value;
                
                var form_data = new FormData();
                form_data.append("TxtEmail", temp);

                $.ajax(
                { 
                    url:"../php/Staff/FetchEmail.php",
                    method:"POST",
                    data:form_data, 
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "xml",
                    success:function(xml)
                    {
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            var error = $(this).attr('Error');
                        
                            if(error == "1"){
                            //Display Alert Box
                                $.alert(
                                {theme: 'modern',
                                    content: message,
                                    title:'', 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                    }}});
                                $('#TxtEmail').val(''); 
                            }

                                
                        });
                    },  
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to fetch information due to error',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }
           
            function clickedNew(){
                fetchName();
                
                $('#RadNew').prop('checked', true);
                $('#RadOld').prop('checked', false);

                $('#TxtLastname').val('');
                $('#TxtFirstname').val('');
                $('#TxtMiddlename').val('');
                $('#TxtExtension').val('');
                $('#TxtAge').val('');
                $('#RadDoctor').prop('checked', false);
                $('#RadNurse').prop('checked', false);
                $('#RadClinicAid').prop('checked', false);
                $('#RadMT').prop('checked', false);
                $('#RadTO').prop('checked', false);
                $('#TxtRank').val('');
                $('#TxtContactNumber').val("+639");
                $('#TxtEmail').val('');
                $('#TxtUsername').val('');
                $('#TxtConfirmPassword').val('');
                $('#TxtPassword').val('');

                document.getElementById('BtnSave').style.display = "none";     
                document.getElementById('BtnEdit').style.display = "none";    
                document.getElementById('BtnPrint').style.display = "none"; 
                document.getElementById('BtnPDF').style.display = "none";
                document.getElementById('BtnAdd').style.display = "flex";     
                document.getElementById('BtnClear').style.display = "flex";    

                document.getElementById("BtnAdd").removeAttribute("disabled");
                document.getElementById("BtnClear").removeAttribute("disabled");  
                document.getElementById("TxtFirstname").removeAttribute("readonly");       
                document.getElementById("TxtLastname").removeAttribute("readonly");   
                document.getElementById("TxtMiddlename").removeAttribute("readonly");  
                document.getElementById("TxtExtension").removeAttribute("readonly");  
                document.getElementById("TxtRank").removeAttribute("readonly");  
                document.getElementById("TxtEmail").removeAttribute("readonly");  
                document.getElementById("RadDoctor").removeAttribute("disabled");  
                document.getElementById("RadNurse").removeAttribute("disabled");  
                document.getElementById("RadClinicAid").removeAttribute("disabled");  
                document.getElementById("RadMT").removeAttribute("disabled");  
                document.getElementById("RadTO").removeAttribute("disabled");  
                document.getElementById("TxtUsername").removeAttribute("readonly");  
                document.getElementById("TxtPassword").removeAttribute("readonly"); 
                document.getElementById("TxtConfirmPassword").removeAttribute("readonly");   
                document.getElementById("TxtContactNumber").removeAttribute("readonly");  

                document.getElementById('TxtLastname').style.backgroundColor = "white";    
                document.getElementById('TxtFirstname').style.backgroundColor = "white"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "white"; 
                document.getElementById('TxtExtension').style.backgroundColor = "white"; 
                document.getElementById('TxtRank').style.backgroundColor = "white"; 
                document.getElementById('TxtContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtEmail').style.backgroundColor = "white"; 
                document.getElementById('TxtUsername').style.backgroundColor = "white"; 
                document.getElementById('TxtPassword').style.backgroundColor = "white"; 
                document.getElementById('TxtConfirmPassword').style.backgroundColor = "white"; 
              /*  removeAttr();*/
            }

            function clickedOld(){
                document.getElementById('BtnSave').style.display = "flex";   
                document.getElementById('BtnPDF').style.display = "none";
                document.getElementById('BtnPrint').style.display = "flex";     
                document.getElementById('BtnEdit').style.display = "flex";    
                document.getElementById('BtnAdd').style.display = "none";     
                document.getElementById('BClear').style.display = 'none';

                var form_data = new FormData();
                var Num = document.getElementById('TxtStaffIDNumber').value;

                form_data.append("idnumber", Num);
                form_data.append("temp", "1");
                form_data.append("type", getType);

                
                $.ajax(
                {
                    url:"../php/Staff/FetchRecords.php",
                    method:"POST",
                    data:form_data, 
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "xml",
                    success:function(xml)
                    {
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            var error = $(this).attr('Error');
                            var Lastname = $(this).attr('Lastname');
                            var Firstname = $(this).attr('Firstname');
                            var Middlename = $(this).attr('Middlename');
                            var Extension = $(this).attr('Extension');
                            var Position = $(this).attr('Position');
                            var Rank = $(this).attr('Rank');
                            var ContactNumber = $(this).attr('ContactNumber');
                            var Email = $(this).attr('Email');
                            var Username = $(this).attr('Username');
                            var Password = $(this).attr('Password');
                            

                            TempPosition = Position;

                            if(error == "1"){
                             //Display Alert Box
                                $.alert(
                                {theme: 'modern',
                                    content: message,
                                    title:'', 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                    }}}); 

                                    clearPersonal();
                                   

                            }else{ 
                                    $('#TxtLastname').val(Lastname);
                                    $('#TxtFirstname').val(Firstname);
                                    $('#TxtMiddlename').val(Middlename);
                                    $('#TxtExtension').val(Extension);
                                    if(Position == "Doctor"){
                                        $('#RadDoctor').prop('checked', true);
                                        
                                    }else if(Position == "Nurse"){
                                        $('#RadNurse').prop('checked', true);
                                        
                                    }else if(Position == "Administrative Aide"){
                                        $('#RadClinicAid').prop('checked',true);
                                        
                                    }else if(Position == "Medical Technologist"){
                                        $('#RadMT').prop('checked',true);
                                    }else if(Position == "Triage Officer"){
                                        $('#RadTO').prop('checked',true);
                                    }
                                    $('#TxtRank').val(Rank);
                                    $('#TxtContactNumber').val(ContactNumber);
                                    $('#TxtEmail').val(Email);
                                    $('#TxtUsername').val(Username);
                                    $('#TxtPassword').val(Password);
                                    $('#TxtConfirmPassword').val(Password);
                                

                                    document.getElementById("BtnClear").setAttribute("disabled", "disabled");  
                                    document.getElementById("BtnSave").setAttribute("disabled", "disabled");   
                                    document.getElementById("TxtFirstname").setAttribute("readonly", "readonly");        
                                    document.getElementById("TxtLastname").setAttribute("readonly", "readonly");        
                                    document.getElementById("TxtMiddlename").setAttribute("readonly", "readonly");        
                                    document.getElementById("TxtExtension").setAttribute("readonly", "readonly");       
                                    document.getElementById("TxtRank").setAttribute("readonly", "readonly");        
                                    document.getElementById("TxtEmail").setAttribute("readonly", "readonly");         
                                    document.getElementById("RadDoctor").setAttribute("disabled", "disabled");  
                                    document.getElementById("RadNurse").setAttribute("disabled", "disabled");   
                                    document.getElementById("RadClinicAid").setAttribute("disabled", "disabled");   
                                    document.getElementById("RadMT").setAttribute("disabled", "disabled");   
                                    document.getElementById("RadTO").setAttribute("disabled", "disabled"); 
                                    document.getElementById("TxtUsername").setAttribute("readonly", "readonly");       
                                    document.getElementById("TxtPassword").setAttribute("readonly", "readonly");       
                                    document.getElementById("TxtConfirmPassword").setAttribute("readonly", "readonly");   
                                    document.getElementById("TxtContactNumber").setAttribute("readonly", "readonly");    
                                       
                                    

                                    

                                 //Display Alert Box
/*                                $.alert(
                                {theme: 'modern',
                                content: message,
                                title:'', 
                                buttons:{
                                    Ok:{
                                    text:'Ok',
                                    btnClass: 'btn-green'
                                }}}); */
                            }
                                setAttr();
                                styleInput('TxtLastname');
                                styleInput('TxtFirstname');
                                styleInput('TxtMiddlename');
                                styleInput('TxtExtension');
                                styleInput('TxtRank');
                                styleInput('TxtContactNumber');
                                styleInput('TxtEmail');
                                styleInput('TxtUsername');
                                styleInput('TxtPassword');
                                styleInput('TxtConfirmPassword');
                    
                        });
                    },  
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to fetch information due to error',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }

           

            

            function clearPersonal(){
                /*$('#TxtStaffIDNumber').val('');
                $('#RadNew').prop('checked', false);
                $('#RadOld').prop('checked', false);*/
                $('#TxtLastname').val('');
                $('#TxtFirstname').val('');
                $('#TxtMiddlename').val('');
                $('#TxtExtension').val('');
                
                
                $('#RadDoctor').prop('checked', false);
                $('#RadNurse').prop('checked', false);
                $('#RadClinicAid').prop('checked', false);
                $('#RadMT').prop('checked', false);
                $('#RadTO').prop('checked', false);

                $('#TxtRank').val('');
                $('#TxtContactNumber').val('+639');
                $('#TxtEmail').val('');
                $('#TxtUsername').val('');
                $('#TxtPassword').val('');
                $('#TxtConfirmPassword').val('');
                
            }

            function checkIfNameEqual(){
                var isNameEqual = false;

                var fName = document.getElementById("TxtFirstname").value;
                var mName = document.getElementById("TxtMiddlename").value;
                var lName = document.getElementById("TxtLastname").value;

                //lowercase the names
                fName = fName.toLowerCase();
                mName = mName.toLowerCase();
                lName = lName.toLowerCase();

                if ((fName != "" && mName != "") && lName != ""){
                    if (fName == mName){
                        isNameEqual = true;
                    }else if (mName == lName) {
                        isNameEqual = true;
                    }else if (lName == fName) {
                        isNameEqual = true;
                    }

                    if (isNameEqual == true){
                        $.alert(
                            {theme: 'modern',
                            content:'First, Middle and Last Name should not be equal',
                            title:'', 
                            useBootstrap: false,
                            buttons:{
                                Ok:{
                                text:'Ok',
                                btnClass: 'btn-red'
                            }}});
                        $('#TxtFirstname').val('');
                        $('#TxtMiddlename').val('');
                        $('#TxtLastname').val('');
                    }
                }
                

                    
                
            }

            function checkblanks(){

                //contact number
                var isCorrect = false;
                var contactnum = document.getElementById("TxtContactNumber").value;
                if(contactnum.trim().length == 13){
                    var defCont = contactnum.substring(0,4); //+639
                    if(defCont == '+639'){
                    isCorrect = true;
                    }
                }else{

                }

                if(isCorrect){
                    return isCorrect;
                }else{
                    $.alert(
                        {theme: 'modern',
                        content: 'Invalid Contact Number',
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    return isCorrect;
                }


                

            }

           

            function saveRecords(form_data)
            {   
                $.ajax(
                { 
                    url:"../php/Staff/SaveUser.php",
                    method:"POST",
                    data:form_data, 
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "xml",
                    success:function(xml)
                    {
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            var error = $(this).attr('Error');

                            if(error == "1"){
                                //Display Alert Box
                                $.alert(
                                {theme: 'modern',
                                content: message,
                                title:'', 
                                buttons:{
                                    Ok:{
                                    text:'Ok',
                                    btnClass: 'btn-red'
                                }}});

                                logAction(message);
                            }else{
                                //Display Alert Box
                                message = "Edited existing user account details";
                                $.alert(
                                {theme: 'modern',
                                content: message,
                                title:'', 
                                buttons:{
                                    Ok:{
                                    text:'Ok',
                                    btnClass: 'btn-green'
                                }}});

                                logAction(message);

                                setTimeout(function(){
                                    window.location="indexStaff.php?type=checkRecords";
                                }, 2000);
                            }
                            
                        });
                     },
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to save information due to error',
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }

            function addRecords(form_data)
            {   
                $.ajax(
                {
                    url:"../php/Staff/addRecords.php",
                    method:"POST",
                    data:form_data,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "xml",
                    success:function(xml)
                    {
                        $(xml).find('output').each(function()
                        {
                            var Result = $(this).attr('Result');
                            var Error = $(this).attr('Error');

                            if(Error == "1"){
                                //Display Alert Box
                                 $.alert({
                                    theme: 'modern',
                                    content:Result,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                }}});
                            }else{
                                //Display Alert Box
                                message = "Added new user account";
                                $.alert({
                                    theme: 'modern',
                                    content:message,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});
                                logAction(message);
                                setTimeout(function(){
                                    window.location.href = 'indexStaff.php?type=checkRecords';
                                }, 2000);
                            }
                            
                        });
                     },
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to store information due to error',
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }


            $(document).ready(function() 
            {

                if (sessionStorage.getItem("isLoggedIn") == null){

                alert('User has been logged out. Please login again');

                $.ajax({
                    url:"../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../index.html';
                        }, 100);
                    }
                  })
                }
                
                const togglePassword = document.getElementById('togglePassword');
                const togglePassword1 = document.getElementById('togglePassword1');
                const password = document.getElementById('TxtPassword');    
                const TxtConfirmPassword = document.getElementById('TxtConfirmPassword');

                togglePassword.addEventListener("click", function () {
                    // toggle the type attribute
                    const type = password.getAttribute("type") === "password" ? "text" : "password";
                    password.setAttribute("type", type);
            
                    // toggle the icon
                    this.classList.toggle("bi-eye");
                });

                togglePassword1.addEventListener("click", function () {
                    // toggle the type attribute
                    const type = TxtConfirmPassword.getAttribute("type") === "password" ? "text" : "password";
                    TxtConfirmPassword.setAttribute("type", type);
            
                    // toggle the icon
                    this.classList.toggle("bi-eye");
                });

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();
                }

                $("#add-personal-information").submit(function(event)
                {                
                    /* stop form from submitting normally */
                    event.preventDefault();
                    var form_data = new FormData(this);
                    /*form_data.append("tab", "0");*/

                    if(document.getElementById('TxtPassword').value == document.getElementById('TxtConfirmPassword').value){
                        //alert(form_data.get("btnvalue"));
                        if(checkblanks()){
                            if(TempBtnValue == "save"){
                                saveRecords(form_data);
                            }else{
                                addRecords(form_data);
                            }
                        }
                        
                    }else{
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Password does not match. Please try again.',
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});

                        $('#TxtPassword').val('');
                        $('#TxtConfirmPassword').val('');
                    }
                    

                    
                });


            }); 
        </script>
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>      
        <div class="container" id="toDownloadPDF">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;User Information&nbsp;&bull;</span>
                    <span id="wholetab" class="tabs-toggle">&bull;&nbsp;User Information&nbsp;&bull;</span>
                </div>
                <div class="tabs-body" id="tabs-bodyID">
                    <div class="tabs-content is-active">
                        <form action="#" method="post" id="add-personal-information" autocomplete="off">
                            <div class="One-Info">
                                <div class="StaffIDNumber">
                                    <label for="TxtStaffIDNumber">Staff ID Number</label> <span id="req">*</span>
                                    <input name="TxtStaffIDNumber" type="text" id="TxtStaffIDNumber" onkeypress="return isNumberKey(this,event)" onchange="fetchName()" style="background-color: white;" required maxlength="7">
                                </div>
                                
                            </div>
                            <div class="Two-Info">
                                <div class="Status">
                                    <label for="RadStatus">Status</label><span id="req">*</span><br>
                                    <label class="SecStatus">
                                        <input type="radio" class="RadStatus" id="RadNew" name="RadStatus" value="New" onclick="clickedNew()" readonly>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">New</span>
                                    </label>
                                    &nbsp
                                    <label class="SecStatus">
                                        <input type="radio" class="RadStatus" id="RadOld" name="RadStatus" value="Old" onclick="clickedOld()">
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Old</span>
                                    </label>
                                </div>
                                
                            </div>
                            
                            <div class="One-Info">
                                <legend>Personal Information</legend>
                            </div>
                            <div class="Four-Info">
                                    <div class="Lastname">
                                        <label for="TxtLastname">Last Name</label><span id="req">*</span>
                                        <input type="text" name="TxtLastname" id="TxtLastname" onkeydown="return alphaName(event);" onchange="checkNameLength(this)" required minlength="2" readonly>
                                    </div>
                                    <div class="Firstname">
                                        <label for="TxtFirstname">First Name</label><span id="req">*</span>
                                        <input type="text" name="TxtFirstname" id="TxtFirstname" onkeydown="return alphaName(event);" onchange="checkNameLength(this)" required minlength="2" readonly>
                                    </div>
                                    <div class="Middlename">
                                        <label for="TxtMiddlename">Middle Name</label><span id="req">*</span>
                                        <input type="text" name="TxtMiddlename" id="TxtMiddlename" onkeydown="return alphaName(event);" onchange="checkNameLength(this)" required minlength="2" readonly>
                                    </div>
                                    <div class="Extension">
                                        <label for="TxtExtension">Extension Name</label>
                                        <input type="text" name="TxtExtension" id="TxtExtension" onkeydown="return alphaName(event);" maxlength="3" readonly>
                                    </div>
                            </div>
                            <div class="Three-Info">
                                    <div class="Position">
                                        <label for="RadPosition">Position</label><span id="req">*</span><br>
                                        <label class="SecPosition">
                                            <input type="radio" class="RadPosition" id="RadDoctor" name="RadPosition" value="Doctor" onclick="changeAccessLevel('Doctor');"  required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Doctor</span>
                                        </label>
                                        &nbsp
                                        <label class="SecPosition">
                                            <input type="radio" class="RadPosition" id="RadNurse" name="RadPosition" value="Nurse" onclick="changeAccessLevel('Nurse');" required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Nurse</span>
                                        </label>
                                        &nbsp
                                        <label class="SecPosition">
                                            <input type="radio" class="RadPosition" id="RadClinicAid" name="RadPosition" value="Administrative Aide" onclick="changeAccessLevel('Administrative Aide');" required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Administrative Aide</span>
                                        </label>
                                        &nbsp
                                        <label class="SecPosition">
                                            <input type="radio" class="RadPosition" id="RadMT" name="RadPosition" value="Medical Technologist" onclick="changeAccessLevel('Medical Technologist');" required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Medical Technologist</span>
                                        </label>
                                        &nbsp
                                        <label class="SecPosition">
                                            <input type="radio" class="RadPosition" id="RadTO" name="RadPosition" value="Triage Officer" onclick="changeAccessLevel('Triage Officer');" required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Triage Officer</span>
                                        </label>
                                    </div>
                            </div>
                            <br>
                            <div class="Two-Info">
                                    <div class="Rank">
                                        <label for="TxtRank">Level</label>
                                        <input type="text" name="TxtRank" id="TxtRank" onkeydown="return isNumberKey(this,event)" maxlength="3" readonly>
                                    </div>
                            </div>
                       

                             <br>
                            <div class="Two-Info">
                                    <div class="ContactNumber">
                                        <label for="TxtContactNumber">Contact Number</label> <span id="req">*</span>
                                        <input type="text" name="TxtContactNumber" id="TxtContactNumber" onchange="checkContNumFormat(this)" onkeypress="return isNumberKey(this,event)" required minlength="13" maxlength="13" readonly>
                                    </div>
                            
                                <div  class="Email1">
                                    <label for="TxtEmail">Email Address</label> <span id="req">*</span>
                                    <input type="Email" name="TxtEmail" id="TxtEmail" onchange="fetchEmail()" pattern= "[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$" required readonly>

                                   
                                </div>
                            </div>
                            <br>

                            <div class="One-Info">
                                <div  class="Username">
                                    <label for="TxtUsername">Username</label> <span id="req">*</span>
                                    <input type="text" name="TxtUsername" id="TxtUsername" required readonly>
                                </div>
                            </div>
                            <div class="Two-Info">
                                <div  class="Password1">
                                    <label for="TxtPassword">Password</label> <span id="req">*</span>
                                    <input type="password" name="TxtPassword" id="TxtPassword"  minlength="8" maxlength="16" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required readonly>
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </div>
                                <div  class="ConfirmPassword1">
                                    <label for="TxtConfirmPassword">Confirm Password</label> <span id="req">*</span>
                                    <input type="password" name="TxtConfirmPassword" id="TxtConfirmPassword" required="" readonly>
                                    <i class="bi bi-eye-slash" id="togglePassword1"></i>
                                </div>
                            </div>

                            <div id="exportButton" data-html2canvas-ignore="true">
                                <div class="submit">
                                    <button type="button" id ="BtnPrint" class=form-button onclick="clickedPrint('BtnPrint')"><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg><p> / Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="button" id ="BtnPDF" class=form-button onclick="clickedPDF()"><p>Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                                </div>
                            </div>
                            <div id="twoButton" data-html2canvas-ignore="true">
                                <div class="submit">
                                    <button type="Submit" id ="BtnAdd" class=form-button name="BTN" onclick="btnValue('add')" disabled><p>Add Record</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><polyline points="34.48 54.28 11.06 54.28 11.06 18.61 23.02 5.75 48.67 5.75 48.67 39.42"/><polyline points="23.04 5.75 23.02 18.61 11.06 18.61"/><line x1="16.21" y1="45.68" x2="28.22" y2="45.68"/><line x1="16.21" y1="39.15" x2="31.22" y2="39.15"/><line x1="16.21" y1="33.05" x2="43.22" y2="33.05"/><line x1="16.21" y1="26.95" x2="43.22" y2="26.95"/><circle cx="42.92" cy="48.24" r="10.01" stroke-linecap="round"/><line x1="42.92" y1="42.76" x2="42.92" y2="53.72"/><line x1="37.45" y1="48.24" x2="48.4" y2="48.24"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="button" id="BtnEdit" class=form-button onclick="clickedEdit()"><p>Edit</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><polyline points="45.56 46.83 45.56 56.26 7.94 56.26 7.94 20.6 19.9 7.74 45.56 7.74 45.56 21.29"/><polyline points="19.92 7.74 19.9 20.6 7.94 20.6"/><line x1="13.09" y1="47.67" x2="31.1" y2="47.67"/><line x1="13.09" y1="41.14" x2="29.1" y2="41.14"/><line x1="13.09" y1="35.04" x2="33.1" y2="35.04"/><line x1="13.09" y1="28.94" x2="39.1" y2="28.94"/><path d="M34.45,43.23l.15,4.3a.49.49,0,0,0,.62.46l4.13-1.11a.54.54,0,0,0,.34-.23L57.76,22.21a1.23,1.23,0,0,0-.26-1.72l-3.14-2.34a1.22,1.22,0,0,0-1.72.26L34.57,42.84A.67.67,0,0,0,34.45,43.23Z"/><line x1="50.2" y1="21.7" x2="55.27" y2="25.57"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="Submit" id="BtnSave" class=form-button name="BTN" onclick="btnValue('save')" disabled><p>Save</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><path d="M51,53.48H10.52V13A2.48,2.48,0,0,1,13,10.52H46.07l7.41,6.4V51A2.48,2.48,0,0,1,51,53.48Z" stroke-linecap="round"/><rect x="21.5" y="10.52" width="21.01" height="15.5" stroke-linecap="round"/><rect x="17.86" y="36.46" width="28.28" height="17.02" stroke-linecap="round"/></svg></button>
                                </div>
                                <div id="BClear" class="submit">
                                    <button type="button" id="BtnClear" class=form-button onclick="clearPersonal()" disabled><p>Clear</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><line x1="8.06" y1="8.06" x2="55.41" y2="55.94"/><line x1="55.94" y1="8.06" x2="8.59" y2="55.94"/></svg></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button type="Submit" class="Upbtn" id="btnUp" name="btnUp" onclick="window.scrollTo(0, 0)"/>
        </div>
        <script src="../js/script-tab.js"></script>
    </body>
</html>


<?php

    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";

    if($_SESSION["typed"] == 'checkArchivedStaff'){
        $tempor = "checkArchived";
    }else{
        $tempor = "checkRecord";
    }

     echo "<script type='text/javascript'>
        globalAL = '$tempo';
        editTableNav('$tempor');
    </script>";


    $id = "";

    $accessLevel = $_SESSION['accesslevel'];

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "newRecord"){
            
            echo "
            <script type='text/javascript'>
            accessLevel = '$accessLevel';
            getType = 'newRecord';
                                                
            </script>";

        }else if($_GET["type"] == "viewRecord"){

            $id = $_GET["id"];

            $sql = "SELECT * FROM USERACCOUNTS WHERE IdNum='$id'";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();
            $tempsAL = stripslashes($Row['AccessLevel']);;

            $type ='';
            $type = $_GET["type"];

            echo "
            <script type='text/javascript'>
            accessLevel = '$accessLevel';
            getType = 'viewRecord';
            passIDPHP($id);
            isAdmin('$tempsAL');
            document.getElementById('BClear').style.display = 'none';
                                                
            </script>";
        }else if($_GET["type"] == "viewArchivedRecord"){

            $id = $_GET["id"];

            $sql = "SELECT * FROM ARCHIVEDSTAFF WHERE IdNum='$id'";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();

            $type ='';
            $type = $_GET["type"];

            echo "
            <script type='text/javascript'>
            accessLevel = '$accessLevel';
            getType = 'viewArchivedRecord';
            passIDPHP($id);
            hideButton();
                                                
            </script>";
        }
    }

?>