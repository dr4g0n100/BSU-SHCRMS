<?php
require '../php/centralConnection.php';
date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    } 
$userID = $_SESSION['userID'];
$userFName = strtolower($_SESSION['fullname']);
$userdate = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
            if($_GET["type"] == "viewMC") {
                echo "<title>View Medical Certificate</title>";
            } else if($_GET["type"] == "viewArchivedMC") {
                echo "<title>View Archived Medical Certificate</title>";
            }else{
                echo "<title>New Medical Certificate</title>";
            }
        ?>
        
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies1.php'; ?>

        <link rel="stylesheet" href="../css/medicalCertificate-style.css">

        <script type="text/javascript">
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var getType ="";
            var accessLevel = "";
            var globalAL = "";
            var id_stud = "";

            /* function ValidateEmail(input) {
                var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.com*$/;
                if (input.value.match(validRegex)) {
                    return true;
                } else {
                    return false;
                }
            } */

            function clickedOthers(){
                if(document.getElementById('RadOthers').checked) {
                    document.getElementById("TAOthers").removeAttribute("readonly");
                    document.getElementById("TAOthers").setAttribute('required','required');
                }else{
                    document.getElementById("TAOthers").setAttribute('readonly','readonly');
                    document.getElementById("TAOthers").removeAttribute("required");
                    $('#TAOthers').val('');
                }
            }

            function clickedOthers1(){
                if(document.getElementById('RadOthers1').checked) {
                    document.getElementById("TAOthers1").removeAttribute("readonly");
                    document.getElementById("TAOthers1").setAttribute('required','required');
                }else{
                    document.getElementById("TAOthers1").setAttribute('readonly','readonly');
                    document.getElementById("TAOthers1").removeAttribute("required");
                    $('#TAOthers1').val('');
                }
            }

            function auto_growTextArea(element) {
                element.style.height = "5vh";
                element.style.height = (element.scrollHeight)+"px";
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

            function btnValue(valu){
                TempBtnValue = valu;
            }

            function clickedPrint(){
                var idNum = document.getElementById('TxtStudentIDNumber').value;

                window.open('../php/printMC.php?id=' +idNum, '_blank');
            }

             function clickedEdit(){
            
                //If id number of student exist in the database, the fields will be enabled
                document.getElementById("studentExaminedOn").removeAttribute("disabled");
                document.getElementById("RadEnrollment").removeAttribute("disabled");
                document.getElementById("RadAthletics").removeAttribute("disabled");
                document.getElementById("RadOJT").removeAttribute("disabled");
                document.getElementById("RadOthers").removeAttribute("disabled");
                document.getElementById("RadPhysicallyFit").removeAttribute("disabled");
                document.getElementById("RadPhysicallyUnfit").removeAttribute("disabled");
                document.getElementById("RadAbsence").removeAttribute("disabled");
                document.getElementById("RadSickLeave").removeAttribute("disabled");
                document.getElementById("RadPEExemption").removeAttribute("disabled");
                document.getElementById("RadExcused").removeAttribute("disabled");
                document.getElementById("RadUnexcused").removeAttribute("disabled");
                document.getElementById("RadConditional").removeAttribute("disabled");
                document.getElementById("RadOthers1").removeAttribute("disabled");
                document.getElementById("BtnAdd").removeAttribute("disabled");
                document.getElementById("BtnClear").removeAttribute("disabled");
                document.getElementById("BtnSave").removeAttribute("disabled");

                document.getElementById("BtnEdit1").setAttribute("disabled","disabled");
                document.getElementById("BtnPrint1").setAttribute("disabled","disabled");
                                
                document.getElementById("TxtMCDocumentCode").removeAttribute("readonly");
                document.getElementById("TxtMCRevisionNumber").removeAttribute("readonly");
                document.getElementById("TxtMCEffectivity").removeAttribute("readonly");
                document.getElementById("TxtMCNoLabel").removeAttribute("readonly");
                document.getElementById("TAMCRemarks").removeAttribute("readonly");
                document.getElementById("TAMCDiagnosis").removeAttribute("readonly");
                document.getElementById("TAMCRemarks1").removeAttribute("readonly");

                document.getElementById('studentExaminedOn').style.backgroundColor = "white";
                document.getElementById('TxtMCDocumentCode').style.backgroundColor = "white";
                document.getElementById('TxtMCRevisionNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtMCEffectivity').style.backgroundColor = "white"; 
                document.getElementById('TxtMCNoLabel').style.backgroundColor = "white";

                clickedOthers();
                clickedOthers1();
            } 


            function fetchHistory(studentid,editorID,editDate){

                document.getElementById("TxtStudentIDNumber").setAttribute('readonly','readonly');
                document.getElementById('TxtStudentIDNumber').style.backgroundColor = "transparent";
                document.getElementById('BtnClear').style.display = "none";
                document.getElementById('BtnAdd').style.display = "none";
                
                fetchInfo();

                var form_data = new FormData();

                var editedDate = editDate;

                editedDate = editedDate.toString().replace('_',' ');
                editedDate = editedDate.toString().replace('/',':');

                form_data.append("num", tblNum);
                form_data.append("userid", editorID);
                form_data.append("editdate", editedDate);




                $.ajax(
                { 
                    url:"../php/MC/FetchHistory.php",
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
                            var MCDocumentCode = $(this).attr('MCDocumentCode');
                            var MCRevisionNumber = $(this).attr('MCRevisionNumber');
                            var MCEffectivity = $(this).attr('MCEffectivity');
                            var MCNoLabel = $(this).attr('MCNoLabel');
                            var StudentIDNumber = $(this).attr('StudentIDNumber');
                            var ConsultDate = $(this).attr('ConsultDate');
                            var RadPurpose = $(this).attr('RadPurpose');
                            var Others = $(this).attr('Others');
                            var PhysicallyFit = $(this).attr('PhysicallyFit');
                            var MCRemarks = $(this).attr('MCRemarks');
                            var Reason = $(this).attr('Reason');
                            var MCDiagnosis = $(this).attr('MCDiagnosis');
                            var ExcuseOrNot = $(this).attr('ExcuseOrNot');
                            var Others1 = $(this).attr('Others1');
                            var MCRemarks1 = $(this).attr('MCRemarks1');
                            var MCMSEditor = $(this).attr('MCMSEditor');
                            
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

                                if(getType == 'viewMC'){
                                    tableType = 'checkMC';
                                }else if(getType == 'viewArchivedMC'){
                                    tableType = 'checkArchivedMC';
                                }

                                setTimeout(function(){
                                    window.location.href = 'indexMC.php?type='+tableType;
                                    }, 2000);
                            }else{

                                //document.getElementById('studentExaminedOn').value= ConsultDate;

                                document.getElementById("studentExaminedOn").selectedIndex = "1";
                                //$('#studentExaminedOn').val(ConsultDate);

                                $('#TxtMCDocumentCode').val(MCDocumentCode);
                                $('#TxtMCRevisionNumber').val(MCRevisionNumber);
                                $('#TxtMCEffectivity').val(MCEffectivity);
                                $('#TxtMCNoLabel').val(MCNoLabel);
                                $('#TAOthers').val(Others);
                                $('#TAMCRemarks').val(MCRemarks);
                                $('#TAMCDiagnosis').val(MCDiagnosis);
                                $('#TAOthers1').val(Others1);
                                $('#TAMCRemarks1').val(MCRemarks1);

                                if(RadPurpose == 'Enrollment'){
                                    $('#RadEnrollment').prop('checked', true);
                                }else if(RadPurpose == 'OJT / Practice Teaching / Internship'){
                                    $('#RadOJT').prop('checked', true);
                                }else if(RadPurpose == 'Athletics'){
                                    $('#RadAthletics').prop('checked', true);
                                }else{
                                    $('#RadOthers').prop('checked', true);
                                }

                                if(PhysicallyFit == 'Physically Fit'){
                                    $('#RadPhysicallyFit').prop('checked', true);
                                }else if(PhysicallyFit == 'Physically Unfit'){
                                    $('#RadPhysicallyUnfit').prop('checked', true);
                                }

                                if(Reason == 'Absence'){
                                    $('#RadAbsence').prop('checked', true);
                                }else if(Reason == 'Sick Leave'){
                                    $('#RadSickLeave').prop('checked', true);
                                }else if(Reason == 'PE Exemption'){
                                    $('#RadPEExemption').prop('checked', true);
                                }

                                if(ExcuseOrNot == 'Excused'){
                                    $('#RadExcused').prop('checked', true);
                                }else if(ExcuseOrNot == 'Unexcused'){
                                    $('#RadUnexcused').prop('checked', true);
                                }else if(ExcuseOrNot == 'Conditional'){
                                    $('#RadConditional').prop('checked', true);
                                }else{
                                    $('#RadOthers1').prop('checked', true);
                                }

                                //alert(tblNum +'/' +editorID+'/'+editedDate);

                                var editedByDD = document.getElementById("TxtMSEditorDrop");
                                    editedByDD.options.length = 0;
                                    const MCMSEditorDDArr = MCMSEditor.split("/");

                                    var value = '';
                                    MCMSEditorDDArr.forEach((element) => {
                                        let option_elem = document.createElement('option');

                                        // Add index to option_elem
                                        option_elem.value = element;
                                          
                                        // Add element HTML
                                        option_elem.textContent = element;
                                          
                                        // Append option_elem to select_elem
                                        editedByDD.prepend(option_elem);

                                        if(element.match(editorID)){
                                            if (element.match(editedDate)) {
                                                value = element;
                                            }
                                        }

                                    });

                                    $('#TxtMSEditorDrop').val(value.trim());

                                    var lastOpt = $('#TxtMSEditorDrop option:first').val().trim();
                                    var alertMsg = '';
                                    var alertTitle = '';
                                    if(value == lastOpt){
                                        document.getElementById('BtnPrint1').style.display = 'flex';
                                        document.getElementById('BtnEdit1').style.display = 'flex';
                                        document.getElementById('BtnSave').style.display = 'flex';
                                        document.getElementById('TxtStudentIDNumber').focus();
                                        alertMsg = 'Edited by: <br>' +value;
                                        alertTitle = 'Latest Medical Certificate History';
                                        document.getElementById("BtnEdit1").removeAttribute("disabled");
                                        document.getElementById("BtnPrint1").removeAttribute("disabled");
                                    }else{
                                        document.getElementById('BtnPrint1').style.display = 'none';
                                        document.getElementById('BtnEdit1').style.display = 'none';
                                        document.getElementById('BtnSave').style.display = 'none';
                                        document.getElementById('TxtStudentIDNumber').focus();
                                        alertMsg = 'Edited by: <br>' +value;
                                        alertTitle = 'Past Medical Certificate History'; 
                                    }
                                    

                                    $.alert(
                                        {theme: 'modern',
                                        content:alertMsg,
                                        title:alertTitle, 
                                        useBootstrap: false,
                                        buttons:{
                                            Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-green'
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

            function passIDPHP(studentIDNum){
                $('#TxtStudentIDNumber').val(studentIDNum);
                document.getElementById("TxtStudentIDNumber").setAttribute('readonly','readonly');
                document.getElementById('TxtStudentIDNumber').style.backgroundColor = "transparent";

                document.getElementById('BtnSave').style.display = "flex";
                document.getElementById('BtnEdit1').style.display = "flex";
                document.getElementById('BtnPrint1').style.display = "flex";
                document.getElementById('BtnClear').style.display = "none";
                document.getElementById('BtnAdd').style.display = "none";

                document.getElementById("BtnPrint1").removeAttribute("disabled");
                document.getElementById("BtnSave").removeAttribute("disabled");
                document.getElementById("BtnEdit1").removeAttribute("disabled");
                
                fetchInfo();

                var form_data = new FormData();
                form_data.append("id", tblNum);
                form_data.append("type", getType);


                $.ajax(
                { 
                    url:"../php/MC/FetchRecords.php",
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
                            var MCDocumentCode = $(this).attr('MCDocumentCode');
                            var MCRevisionNumber = $(this).attr('MCRevisionNumber');
                            var MCEffectivity = $(this).attr('MCEffectivity');
                            var MCNoLabel = $(this).attr('MCNoLabel');
                            var StudentIDNumber = $(this).attr('StudentIDNumber');
                            var ConsultDate = $(this).attr('ConsultDate');
                            var RadPurpose = $(this).attr('RadPurpose');
                            var Others = $(this).attr('Others');
                            var PhysicallyFit = $(this).attr('PhysicallyFit');
                            var MCRemarks = $(this).attr('MCRemarks');
                            var Reason = $(this).attr('Reason');
                            var MCDiagnosis = $(this).attr('MCDiagnosis');
                            var ExcuseOrNot = $(this).attr('ExcuseOrNot');
                            var Others1 = $(this).attr('Others1');
                            var MCRemarks1 = $(this).attr('MCRemarks1');
                            var MCMSEditor = $(this).attr('MCMSEditor');
                            
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

                                if(getType == 'viewMC'){
                                    tableType = 'checkMC';
                                }else if(getType == 'viewArchivedMC'){
                                    tableType = 'checkArchivedMC';
                                }

                                setTimeout(function(){
                                    window.location.href = 'indexMC.php?type='+tableType;
                                    }, 2000);
                            }else{

                                //document.getElementById('studentExaminedOn').value= ConsultDate;

                                document.getElementById("studentExaminedOn").selectedIndex = "1";
                                //$('#studentExaminedOn').val(ConsultDate);

                                $('#TxtMCDocumentCode').val(MCDocumentCode);
                                $('#TxtMCRevisionNumber').val(MCRevisionNumber);
                                $('#TxtMCEffectivity').val(MCEffectivity);
                                $('#TxtMCNoLabel').val(MCNoLabel);
                                $('#TAOthers').val(Others);
                                $('#TAMCRemarks').val(MCRemarks);
                                $('#TAMCDiagnosis').val(MCDiagnosis);
                                $('#TAOthers1').val(Others1);
                                $('#TAMCRemarks1').val(MCRemarks1);

                                if(RadPurpose == 'Enrollment'){
                                    $('#RadEnrollment').prop('checked', true);
                                }else if(RadPurpose == 'OJT / Practice Teaching / Internship'){
                                    $('#RadOJT').prop('checked', true);
                                }else if(RadPurpose == 'Athletics'){
                                    $('#RadAthletics').prop('checked', true);
                                }else if(RadPurpose == 'others'){
                                    $('#RadOthers').prop('checked', true);
                                }

                                if(PhysicallyFit == 'Physically Fit'){
                                    $('#RadPhysicallyFit').prop('checked', true);
                                }else if(PhysicallyFit == 'Physically Unfit'){
                                    $('#RadPhysicallyUnfit').prop('checked', true);
                                }

                                if(Reason == 'Absence'){
                                    $('#RadAbsence').prop('checked', true);
                                }else if(Reason == 'Sick Leave'){
                                    $('#RadSickLeave').prop('checked', true);
                                }else if(Reason == 'PE Exemption'){
                                    $('#RadPEExemption').prop('checked', true);
                                }

                                if(ExcuseOrNot == 'Excused'){
                                    $('#RadExcused').prop('checked', true);
                                }else if(ExcuseOrNot == 'Unexcused'){
                                    $('#RadUnexcused').prop('checked', true);
                                }else if(ExcuseOrNot == 'Conditional'){
                                    $('#RadConditional').prop('checked', true);
                                }else if(ExcuseOrNot == 'others'){
                                    $('#RadOthers1').prop('checked', true);
                                }


                                var editedByDD = document.getElementById("TxtMSEditorDrop");
                                editedByDD.options.length = 0;
                                const MCMSEditorDDArr = MCMSEditor.split("/");

                                MCMSEditorDDArr.forEach((element) => {
                                    let option_elem = document.createElement('option');

                                    // Add index to option_elem
                                    option_elem.value = element;
                                          
                                    // Add element HTML
                                    option_elem.textContent = element;
                                          
                                    // Append option_elem to select_elem
                                    editedByDD.prepend(option_elem);
                                });

                                var myVal = $('#TxtMSEditorDrop option:first').val();
                                $('#TxtMSEditorDrop').val(myVal);



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

            function fetchInfo(){
                var studentID = document.getElementById('TxtStudentIDNumber').value;
                
                var form_data = new FormData();
                form_data.append("studentID", studentID);

                $.ajax(
                { 
                    url:"../php/MC/fetchName.php",
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
                            var FirstN = $(this).attr('FirstN');
                            var MiddleN = $(this).attr('MiddleN');
                            var LastN = $(this).attr('LastN');
                            var Extens = $(this).attr('Extens');
                            var Ages = $(this).attr('Ages');
                            var Sexs = $(this).attr('Sexs');
                            var CourseStrand = $(this).attr('CourseStrand');
                            var Years = $(this).attr('Years');
                            var Dates = $(this).attr('Dates');
                            var error = $(this).attr('Error');
                            
                            //alert(Dates);

                            document.getElementById('studentExaminedOn').options.length = 0;
                            var firstOpt = document.createElement('option');
                            firstOpt.value = "";
                            firstOpt.textContent = "Select Consultation Date";
                            firstOpt.setAttribute('disabled','disabled');
                            studentExaminedOn.appendChild(firstOpt);

                            Dates = Dates.trim();

                            const DatesArr = Dates.split(" ");

                            var consultdate = document.getElementById("studentExaminedOn");

                            DatesArr.forEach((element) => {
                              let option_elem = document.createElement('option');
                              
                              // Add index to option_elem
                              option_elem.value = element;
                              
                              // Add element HTML
                              option_elem.textContent = element;
                              
                              // Append option_elem to select_elem
                              consultdate.appendChild(option_elem);
                            });

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

                                $('#TxtStudentIDNumber').val('');

                                document.getElementById("studentLastN").textContent = '___________';
                                document.getElementById("studentFirstN").textContent = '___________';
                                document.getElementById("studentMiddleN").textContent = '___________';
                                document.getElementById("studentExtens").textContent = '___';
                                document.getElementById("studentAge").textContent = '____';
                                document.getElementById("studentSex").textContent = '_________';
                                document.getElementById("studentYear").textContent = '_____';
                                document.getElementById("studentDegree").textContent = '__________________________________';

                                disableEditing();
                            }else{
                                document.getElementById("studentLastN").textContent = LastN.toUpperCase();
                                document.getElementById("studentFirstN").textContent = FirstN.toUpperCase();
                                document.getElementById("studentMiddleN").textContent = MiddleN.toUpperCase();
                                document.getElementById("studentExtens").textContent = Extens.toUpperCase();
                                document.getElementById("studentAge").textContent = Ages;
                                document.getElementById("studentSex").textContent = Sexs.toUpperCase();
                                document.getElementById("studentYear").textContent = Years.toUpperCase();
                                document.getElementById("studentDegree").textContent = CourseStrand.toUpperCase();

                                document.getElementById("studentLastN").style.textDecoration = "underline";
                                document.getElementById("studentFirstN").style.textDecoration = "underline";
                                document.getElementById("studentMiddleN").style.textDecoration = "underline";
                                document.getElementById("studentExtens").style.textDecoration = "underline";
                                document.getElementById("studentAge").style.textDecoration = "underline";
                                document.getElementById("studentSex").style.textDecoration = "underline";
                                document.getElementById("studentYear").style.textDecoration = "underline";
                                document.getElementById("studentDegree").style.textDecoration = "underline";
                                
                                enableEditing();
                                if(getType == "viewMC" || getType == "viewArchivedMC"){
                                    disableEditing();
                                }
                                
                                

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
                
                
            function enableEditing(){
                
                         
                //If id number of student exist in the database, the fields will be enabled
                document.getElementById("studentExaminedOn").removeAttribute("disabled");
                document.getElementById("RadEnrollment").removeAttribute("disabled");
                document.getElementById("RadAthletics").removeAttribute("disabled");
                document.getElementById("RadOJT").removeAttribute("disabled");
                document.getElementById("RadOthers").removeAttribute("disabled");
                document.getElementById("RadPhysicallyFit").removeAttribute("disabled");
                document.getElementById("RadPhysicallyUnfit").removeAttribute("disabled");
                document.getElementById("RadAbsence").removeAttribute("disabled");
                document.getElementById("RadSickLeave").removeAttribute("disabled");
                document.getElementById("RadPEExemption").removeAttribute("disabled");
                document.getElementById("RadExcused").removeAttribute("disabled");
                document.getElementById("RadUnexcused").removeAttribute("disabled");
                document.getElementById("RadConditional").removeAttribute("disabled");
                document.getElementById("RadOthers1").removeAttribute("disabled");
                document.getElementById("BtnAdd").removeAttribute("disabled");
                document.getElementById("BtnClear").removeAttribute("disabled");
                document.getElementById("BtnPrint1").removeAttribute("disabled");
                
                                
                document.getElementById("TxtMCDocumentCode").removeAttribute("readonly");
                document.getElementById("TxtMCRevisionNumber").removeAttribute("readonly");
                document.getElementById("TxtMCEffectivity").removeAttribute("readonly");
                document.getElementById("TxtMCNoLabel").removeAttribute("readonly");
                document.getElementById("TAMCRemarks").removeAttribute("readonly");
                document.getElementById("TAMCDiagnosis").removeAttribute("readonly");
                document.getElementById("TAMCRemarks1").removeAttribute("readonly");

                document.getElementById('TxtMCDocumentCode').style.backgroundColor = "white";
                document.getElementById('TxtMCRevisionNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtMCEffectivity').style.backgroundColor = "white"; 
                document.getElementById('TxtMCNoLabel').style.backgroundColor = "white"; 
            }

            function disableEditing(){
                document.getElementById("studentExaminedOn").setAttribute("disabled","disabled");
                document.getElementById("RadEnrollment").setAttribute("disabled","disabled");
                document.getElementById("RadAthletics").setAttribute("disabled","disabled");
                document.getElementById("RadOJT").setAttribute("disabled","disabled");
                document.getElementById("RadOthers").setAttribute("disabled","disabled");
                document.getElementById("RadPhysicallyFit").setAttribute("disabled","disabled");
                document.getElementById("RadPhysicallyUnfit").setAttribute("disabled","disabled");
                document.getElementById("RadAbsence").setAttribute("disabled","disabled");
                document.getElementById("RadSickLeave").setAttribute("disabled","disabled");
                document.getElementById("RadPEExemption").setAttribute("disabled","disabled");
                document.getElementById("RadExcused").setAttribute("disabled","disabled");
                document.getElementById("RadUnexcused").setAttribute("disabled","disabled");
                document.getElementById("RadConditional").setAttribute("disabled","disabled");
                document.getElementById("RadOthers1").setAttribute("disabled","disabled");
                document.getElementById("BtnAdd").setAttribute("disabled","disabled");
                document.getElementById("BtnClear").setAttribute("disabled","disabled");
                document.getElementById("BtnSave").setAttribute("disabled","disabled");
                
                document.getElementById("TxtMCDocumentCode").setAttribute('readonly','readonly');
                document.getElementById("TxtMCRevisionNumber").setAttribute('readonly','readonly');
                document.getElementById("TxtMCEffectivity").setAttribute('readonly','readonly');
                document.getElementById("TxtMCNoLabel").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks").setAttribute('readonly','readonly');
                document.getElementById("TAMCDiagnosis").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks1").setAttribute('readonly','readonly');
                document.getElementById("TAOthers").setAttribute('readonly','readonly');
                document.getElementById("TAOthers1").setAttribute('readonly','readonly');

                document.getElementById('TxtMCDocumentCode').style.backgroundColor = "transparent";
                document.getElementById('TxtMCRevisionNumber').style.backgroundColor = "transparent"; 
                document.getElementById('TxtMCEffectivity').style.backgroundColor = "transparent"; 
                document.getElementById('TxtMCNoLabel').style.background = "transparent";
            }

            }

            function clearInfo(){

                $('#TxtStudentIDNumber').val('');
                $('#TxtMCDocumentCode').val('');
                $('#TxtMCRevisionNumber').val('');
                $('#TxtMCEffectivity').val('');
                $('#TxtMCNoLabel').val('');
                
                $('#RadEnrollment').prop('checked', false);
                $('#RadOJT').prop('checked', false);
                $('#RadAthletics').prop('checked', false);
                $('#RadOthers').prop('checked', false);
                $('#RadPhysicallyFit').prop('checked', false);
                $('#RadPhysicallyUnfit').prop('checked', false);
                $('#RadAbsence').prop('checked', false);
                $('#RadSickLeave').prop('checked', false);
                $('#RadPEExemption').prop('checked', false);
                $('#RadExcused').prop('checked', false);
                $('#RadUnexcused').prop('checked', false);
                $('#RadConditional').prop('checked', false);
                $('#RadOthers1').prop('checked', false);

                document.getElementById('studentExaminedOn').options.length = 0;
                var firstOpt = document.createElement('option');
                firstOpt.value = "";
                firstOpt.textContent = "Select Consultation Date";
                firstOpt.setAttribute('hidden','hidden');
                studentExaminedOn.appendChild(firstOpt);

                $('#TAMCRemarks').val('');
                $('#TAMCDiagnosis').val('');
                $('#TAMCRemarks1').val('');
                $('#TAOthers').val('');
                $('#TAOthers1').val('');

                document.getElementById("studentLastN").textContent = '___________';
                document.getElementById("studentFirstN").textContent = '___________';
                document.getElementById("studentMiddleN").textContent = '___________';
                document.getElementById("studentExtens").textContent = '___';
                document.getElementById("studentAge").textContent = '____';
                document.getElementById("studentSex").textContent = '_________';
                document.getElementById("studentYear").textContent = '_____';
                document.getElementById("studentDegree").textContent = '__________________________________';

                document.getElementById("TAOthers").setAttribute('readonly','readonly');
                document.getElementById("TAOthers1").setAttribute('readonly','readonly');
                document.getElementById("TxtMCDocumentCode").setAttribute('readonly','readonly');
                document.getElementById("TxtMCRevisionNumber").setAttribute('readonly','readonly');
                document.getElementById("TxtMCEffectivity").setAttribute('readonly','readonly');
                document.getElementById("TxtMCNoLabel").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks").setAttribute('readonly','readonly');
                document.getElementById("TAMCDiagnosis").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks1").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks").setAttribute('readonly','readonly');
                document.getElementById("TAMCDiagnosis").setAttribute('readonly','readonly');
                document.getElementById("TAMCRemarks1").setAttribute('readonly','readonly');

                document.getElementById('TxtMCDocumentCode').style.backgroundColor = "transparent";
                document.getElementById('TxtMCRevisionNumber').style.backgroundColor = "transparent"; 
                document.getElementById('TxtMCEffectivity').style.backgroundColor = "transparent"; 
                document.getElementById('TxtMCNoLabel').style.background = "transparent"; 
                
            }
           
            function saveRecords(form_data)
            {   
                /*var studentLastN = document.getElementById("studentLastN").textContent;
                var studentFirstN = document.getElementById("studentFirstN").textContent;
                var studentMiddleN = document.getElementById("studentMiddleN").textContent;
                var studentExtens = document.getElementById("studentExtens").textContent;
                //var form_data = new FormData();
                form_data.append("studentLastN", studentLastN);
                form_data.append("studentFirstN", studentFirstN);
                form_data.append("studentMiddleN", studentMiddleN);
                form_data.append("studentExtens", studentExtens);*/

                var TxtMCMSEditor = '';
                TxtMCMSEditor = '<?php echo $userID; ?> - <?php echo ucwords($userFName); ?> - <?php echo $userdate; ?>';
                TxtUserEdit = '<?php echo $userID; ?>';
                TxtEditDate = '<?php echo $userdate; ?>';

                form_data.append("TxtMCMSEditor", TxtMCMSEditor);
                form_data.append("TxtUserEdit", TxtUserEdit);
                form_data.append("TxtEditDate", TxtEditDate);

                form_data.append("id", tblNum);
                $.ajax(
                {
                    url:"../php/MC/SaveRecord.php",
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
                            var Error = $(this).attr('Error');

                            if(Error == "1"){
                                //Display Alert Box
                                 $.alert({
                                    theme: 'modern',
                                    content:message,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                }}});
                            }else{
                                //Display Alert Box
                                message = "Edited existing medical certificate record";
                                $.alert({
                                    theme: 'modern',
                                    content:message,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});

                                    setTimeout(function(){
                                    window.history.go(0);
                                    window.scrollTo(0,1);
                                        }, 2000);
                            }
                            logAction(message);

                            
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

             function addRecords(form_data)
            {   

                /*var studentLastN = document.getElementById("studentLastN").textContent;
                var studentFirstN = document.getElementById("studentFirstN").textContent;
                var studentMiddleN = document.getElementById("studentMiddleN").textContent;
                var studentExtens = document.getElementById("studentExtens").textContent;
                //var form_data = new FormData();
                form_data.append("studentLastN", studentLastN);
                form_data.append("studentFirstN", studentFirstN);
                form_data.append("studentMiddleN", studentMiddleN);
                form_data.append("studentExtens", studentExtens);*/

                var TxtMCMSEditor = '';
                TxtMCMSEditor = '<?php echo $userID; ?> - <?php echo ucwords($userFName); ?> - <?php echo $userdate; ?>';
                TxtUserEdit = '<?php echo $userID; ?>';
                TxtEditDate = '<?php echo $userdate; ?>';

                form_data.append("TxtMCMSEditor", TxtMCMSEditor);
                form_data.append("TxtUserEdit", TxtUserEdit);
                form_data.append("TxtEditDate", TxtEditDate);

                $.ajax(
                {
                    url:"../php/MC/addRecords.php",
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
                                logAction(Result);
                            }else{
                                //Display Alert Box
                                Result = "Added new medical certificate record";
                                $.alert({
                                    theme: 'modern',
                                    content:Result,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});
                                    logAction(Result);

                                    setTimeout(function(){
                                    window.location.href = 'indexMC.php?type=checkMC';
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

            function preventEnterSubmit(e) {
                if (e.which == 13) {
                    var $targ = $(e.target);

                    if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                        var focusNext = false;
                        $(this).find(":input:visible:not([disabled],[readonly]), a").each(function () {
                            if (this === e.target) {
                                    focusNext = true;
                            } else {
                                if (focusNext) {
                                    $(this).focus();
                                    return false;
                                }
                            }
                        });

                        return false;
                    }
                }
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
                
                var userFullname = "<?php echo $_SESSION['fullname'] ?>";
                var physicianID = "<?php echo $_SESSION['userID'] ?>";

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();
                }

                if(getType == 'viewArchivedMC'){
                    document.getElementById('BtnSave').style.display = "none";
                    document.getElementById('BtnEdit1').style.display = "none"; 
                    document.getElementById('BtnPrint1').style.display = "none";
                    document.getElementById('BtnAdd').style.display = "none";
                    document.getElementById('BtnClear').style.display = "none";  
                }else if(getType == 'viewMC'){
                    document.getElementById('BtnSave').style.display = "flex";
                    document.getElementById('BtnEdit1').style.display = "flex"; 
                    document.getElementById('BtnPrint1').style.display = "flex";
                    document.getElementById('BtnAdd').style.display = "none";
                    document.getElementById('BtnClear').style.display = "none";
                }else{
                    document.getElementById('BtnSave').style.display = "none";
                    document.getElementById('BtnEdit1').style.display = "none"; 
                    document.getElementById('BtnPrint1').style.display = "none";
                    document.getElementById('BtnAdd').style.display = "flex";
                    document.getElementById('BtnClear').style.display = "flex";
                }

                document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number: ' + physicianID;
                document.getElementById('TxtMSFullName').innerHTML = '&nbsp&nbsp&nbsp' + userFullname.toUpperCase();

                $("#add-medicalcertificate").keypress(preventEnterSubmit);

                $("#add-medicalcertificate").submit(function(event)
                {                
                
                    event.preventDefault();
                    var form_data = new FormData(this);
                    form_data.append("userID", physicianID);
                    form_data.append("userFullN", userFullname);
                   
                    if(TempBtnValue == "save"){
                            saveRecords(form_data);
                    }else{
                            addRecords(form_data);
                    }
                    
                    
                });

                $('#TxtMSEditorDrop').change(function(){
                    var values = $(this).val();

                    
                    var studentid = id_stud;

                    var valuesArr = values.split(" - ");

                    valuesArr.forEach((element) => {
                        element.trim();       
                    });

                    var editorID = valuesArr[0];
                    var editorName = valuesArr[1];
                    var editDate = valuesArr[2];

                    editDate = editDate.replace(' ','_');

                    editDate = editDate.replace(':','/');

                    //alert(studentid+' ' +editorID +' ' +editDate);

                    if(getType == 'viewMC'){
                        fetchHistory(studentid,editorID,editDate);
                    }
                    

                    //window.location.href = 'newRecord.php?id_stud='+id_stud+'&staffIDnum=' +editorID + '&editdate='+editDate+'&type=viewRecordHistory';

                    //alert('id_stud='+id_stud+'staffIDnum=' +editorID + '&editdate='+editDate+'&type=viewRecordHistory');
                })


            });  
        </script>
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?> 
        <div class="container" id="toDownloadPDF">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active"></span>
                    <span id="wholetab" class="tabs-toggle"></span>
                </div>
                <div class="tabs-body" id="tabs-bodyID">
                    <div class="tabs-content is-active">
                    <form action="#" method="post" id="add-medicalcertificate" autocomplete="off">
                        <div class="Two-Info" id="topHeader">
                            <div id="medicalCertificateHeader">
                                <img id="bsuLogo" alt="BSU Logo" src="../images/BSULogo.webp"/>
                                <h3>MEDICAL CERTIFICATE</h3>
                            </div>
                            <div id="mcISO">
                            <table id="tableMedicalCertificate">
                                <tr>
                                    <td class="mcDocumentCode">
                                        <label for="TxtMCDocumentCode"> 
                                            Document Code:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" name="TxtMCDocumentCode" id="TxtMCDocumentCode" readonly>
                                    </td>
                                    <td class="mcRevisionNumber">
                                        <label for="TxtMCRevisionNumber"> 
                                            Revision Number:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="number" id="TxtMCRevisionNumber" name="TxtMCRevisionNumber" onkeypress="return isNumberKey(this,event)" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mcEffectivity">
                                        <label for="TxtMCEffectivity">
                                            Effectivity
                                        </label>
                                    </td>
                                    <td>
                                        <input type="date" name="TxtMCEffectivity" id="TxtMCEffectivity" readonly>
                                    </td>
                                    <td>
                                        <label for="TxtMCNoLabel"></label>
                                        <input type="text" name="TxtMCNoLabel" id="TxtMCNoLabel" readonly>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>
                        <div>
                            <form method="post">
                                <div class="One-Info">
                                    <div class="StudentIDNumber">
                                        <label for="TxtStudentIDNumber">Student ID Number</label><span id="req">*</span>
                                        <input name="TxtStudentIDNumber" type="text" name="TxtStudentIDNumber" id="TxtStudentIDNumber" onchange="fetchInfo()" onkeypress="return isNumberKey(this,event)" style="background-color: white;" required maxlength="7">
                                    </div>
                                </div>
                            </form>
                            
                            <div class="One-Info" id="firstRowCertificate">
                                <p>This is to certify that <span id="studentLastN">___________</span>, <span id="studentFirstN">___________</span> <span id="studentMiddleN">___________</span> <span id="studentExtens">___</span>, <span id="studentAge">____</span> year/s old, <span id="studentSex">_________</span>, currently in the year <span id="studentYear"> _____</span> of <span id="studentDegree">__________________________________</span> was examined on

                                    <select id="studentExaminedOn" name="studentExaminedOn" disabled required>_________________
                                        <option value="\\" disabled>Select Consultation Date</option>
                                    </select> for the following: 
                                </p>


                            </div>
                            <div class="One-Info">
                                <table id="secondTable">
                                    <tr>
                                        <td class="mcPurpose"> 
                                            <div class="purpose">
                                                <label for="RadPurpose"></label>
                                                <label class="SecPurpose">
                                                    <input type="radio" class="RadPurpose" id="RadEnrollment" name="RadPurpose" value="Enrollment" onclick="clickedOthers()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Enrollment</span>
                                                </label><br/>
                                                <label class="SecPurpose">
                                                    <input type="radio" class="RadPurpose" id="RadOJT" name="RadPurpose" value="OJT / Practice Teaching / Internship" onclick="clickedOthers()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">OJT / Practice Teaching / Internship</span>
                                                </label><br/>
                                                <label class="SecPurpose">
                                                    <input type="radio" class="RadPurpose" id="RadAthletics" name="RadPurpose" value="Athletics" onclick="clickedOthers()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Athletics</span>
                                                </label><br/>
                                                <label class="SecPurpose">
                                                    <input type="radio" class="RadPurpose" id="RadOthers" name="RadPurpose" value="others" onclick="clickedOthers()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Others:</span><br/>
                                                    <textarea type="text" name="TAOthers" id="TAOthers" cols="105" rows="10" oninput="auto_growTextArea(this)" readonly></textarea>
                                                </label><br/>
                                           </div>
                                        </td>
                                        <td class="mcSecondTableSecondColumn">
                                            <div class="SecondTableSecondColumn">
                                                <label for="RadPhysicallyFitUnfit">He/she is found to be:</label><br/>
                                                <label class="SecPhysicallyFitUnfit">
                                                    <input type="radio" class="RadPhysicallyFitUnfit" id="RadPhysicallyFit" name="RadPhysicallyFitUnfit" value="Physically Fit" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Physically Fit</span>
                                                </label>&nbsp;
                                                <label class="SecPhysicallyFitUnfit">
                                                    <input type="radio" class="RadPhysicallyFitUnfit" id="RadPhysicallyUnfit" name="RadPhysicallyFitUnfit" value="Physically Unfit" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Physically Unfit</span>
                                                </label><br/>
                                                <div class="mcRemarks">
                                                    <label for="TAMCRemarks">Remarks:</label>
                                                    <textarea type="text" name="TAMCRemarks" id="TAMCRemarks" cols="105" rows="10" oninput="auto_growTextArea(this)" readonly></textarea>
                                                </div>
                                                
                                           </div>
                                        </td>
                                    </tr>
                                </table>    
                            </div>
                            <div class="One-Info">
                                <table id="thirdTable">
                                    <tr>
                                        <td class="mcPurpose2"> 
                                            <div class="purpose2">
                                                <label for="RadPurpose2"></label>
                                                <label class="SecPurpose2">
                                                    <input type="radio" class="RadPurpose2" id="RadAbsence" name="RadPurpose2" value="Absence" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Absence</span>
                                                </label><br/>
                                                <label class="SecPurpose2">
                                                    <input type="radio" class="RadPurpose2" id="RadSickLeave" name="RadPurpose2" value="Sick Leave" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Sick Leave</span>
                                                </label><br/>
                                                <label class="SecPurpose2">
                                                    <input type="radio" class="RadPurpose2" id="RadPEExemption" name="RadPurpose2" value="PE Exemption" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">PE Exemption</span>
                                                </label><br/>                       
                                           </div>
                                        </td>
                                        <td class="mcThirdTableSecondColumn">
                                            <div class="ThirdTableSecondColumn">
                                                <div class="mcDiagnosis">
                                                    <label for="TAMCDiagnosis">Diagnosis:</label>
                                                    <textarea type="text" name="TAMCDiagnosis" id="TAMCDiagnosis" cols="105" rows="10" oninput="auto_growTextArea(this)" readonly></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>    
                            </div>
                            <div class="One-Info">
                                <table id="fourthTable">
                                    <tr>
                                        <td class="mcExcuseOrNot"> 
                                            <div class="ExcuseOrNot">
                                                <label for="RadExcuseOrNot"></label>
                                                <label class="SecExcuseOrNot">
                                                    <input type="radio" class="RadExcuseOrNot" id="RadExcused" name="RadExcuseOrNot" value="Excused" onclick="clickedOthers1()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Excused</span>
                                                </label><br/>
                                                <label class="SecExcuseOrNot">
                                                    <input type="radio" class="RadExcuseOrNot" id="RadUnexcused" name="RadExcuseOrNot" value="Unexcused" onclick="clickedOthers1()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Unexcused</span>
                                                </label><br/>
                                                <label class="SecExcuseOrNot">
                                                    <input type="radio" class="RadExcuseOrNot" id="RadConditional" name="RadExcuseOrNot" value="Conditional" onclick="clickedOthers1()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Conditional</span>
                                                </label><br/>
                                                <label class="SecExcuseOrNot">
                                                    <input type="radio" class="RadExcuseOrNot" id="RadOthers1" name="RadExcuseOrNot" value="others" onclick="clickedOthers1()" disabled>
                                                    <span class="RadDesign"></span>
                                                    <span class="RadText">Others:</span><br/>
                                                    <input type="text1" name="TAOthers1" id="TAOthers1" readonly>
                                                </label><br/>
                                           </div>
                                        </td>
                                        <td class="mcFourthTableSecondColumn">
                                            <div class="FourthTableSecondColumn">
                                                <div class="mcRemarks1">
                                                    <label for="TAMCRemarks1">Remarks:</label>
                                                    <textarea type="text" name="TAMCRemarks1" id="TAMCRemarks1" cols="105" rows="10" readonly>
                                                    </textarea>
                                                </div>
                                           </div>
                                        </td>
                                    </tr>
                                </table>   

                                <div class="One-Info" id="SignPhysician">
                                    <span>___________________________</span>
                                    <span>University Physician</span>
                                </div>
                                <div class="One-Info" id="MedicalStaffInfo">
                                    <div id="MedicalStaffInfo">
                                        <legend>Medical Staff</legend>
                                        <span id="TxtMSIDNumber1">ID Number:</span><br>
                                        <span id="TxtMSChartedBy">Charted By:</span><br>
                                        <span id="TxtMSFullName"></span><br>
                                        <span id="TxtMCMSEditorTitle">Edited By:</span><br>
                                        <select id="TxtMSEditorDrop" name="TxtMSEditorDrop"></select>
                                    </div>
                                </div>
                                

                                <div id="twoButton" data-html2canvas-ignore="true">
                                    <div class="submit">
                                        <button type="button" id ="BtnPrint1" class=form-button onclick="clickedPrint()" disabled><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" style="align-content: center;" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg><p> / Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                                    </div>
                                </div>

                                <div id="twoButton" data-html2canvas-ignore="true">
                                    <div class="submit">
                                        <button type="Submit" id ="BtnAdd" class=form-button name="BtnAdd" onclick="btnValue('add')" disabled><p>Add</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><circle cx="29.22" cy="16.28" r="11.14"/><path d="M41.32,35.69c-2.69-1.95-8.34-3.25-12.1-3.25h0A22.55,22.55,0,0,0,6.67,55h29.9"/><circle cx="45.38" cy="46.92" r="11.94"/><line x1="45.98" y1="39.8" x2="45.98" y2="53.8"/><line x1="38.98" y1="46.8" x2="52.98" y2="46.8"/></svg></button>
                                    </div>
                                    <div class="submit">
                                        <button type="button" id="BtnEdit1" class=form-button onclick="clickedEdit()" disabled><p>Edit</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><polyline points="45.56 46.83 45.56 56.26 7.94 56.26 7.94 20.6 19.9 7.74 45.56 7.74 45.56 21.29"/><polyline points="19.92 7.74 19.9 20.6 7.94 20.6"/><line x1="13.09" y1="47.67" x2="31.1" y2="47.67"/><line x1="13.09" y1="41.14" x2="29.1" y2="41.14"/><line x1="13.09" y1="35.04" x2="33.1" y2="35.04"/><line x1="13.09" y1="28.94" x2="39.1" y2="28.94"/><path d="M34.45,43.23l.15,4.3a.49.49,0,0,0,.62.46l4.13-1.11a.54.54,0,0,0,.34-.23L57.76,22.21a1.23,1.23,0,0,0-.26-1.72l-3.14-2.34a1.22,1.22,0,0,0-1.72.26L34.57,42.84A.67.67,0,0,0,34.45,43.23Z"/><line x1="50.2" y1="21.7" x2="55.27" y2="25.57"/></svg></button>
                                    </div>
                                        <div class="submit">
                                        <button type="Submit" id="BtnSave" class=form-button name="BTN" onclick="btnValue('save')" disabled><p>Save</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><path d="M51,53.48H10.52V13A2.48,2.48,0,0,1,13,10.52H46.07l7.41,6.4V51A2.48,2.48,0,0,1,51,53.48Z" stroke-linecap="round"/><rect x="21.5" y="10.52" width="21.01" height="15.5" stroke-linecap="round"/><rect x="17.86" y="36.46" width="28.28" height="17.02" stroke-linecap="round"/></svg></button>
                                    </div>
                                    <div class="submit">
                                        <button type="button" id="BtnClear" class=form-button onclick="clearInfo()" disabled><p>Clear</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><line x1="8.06" y1="8.06" x2="55.41" y2="55.94"/><line x1="55.94" y1="8.06" x2="8.59" y2="55.94"/></svg></button>
                                    </div>
                                    
                                </div>
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

    $type = "";
    $id = "";
    $tempor =  "";

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        
            if($_GET["type"] == "viewMC"){
                $query ="SELECT * FROM MEDICALCERTIFICATE";  
                $result = mysqli_query($connect, $query);

                $id = $_GET["studentID"];
                $type = $_GET["type"];
                $id_num = $_GET["id"];

                $tempor = "checkRecord";

                echo "<script type='text/javascript'>
                        getType = '$type';
                        tblNum = '$id_num';
                        id_stud = '$id';
                        passIDPHP($id);
                      </script>";
            }else if($_GET["type"] == "viewArchivedMC"){
                $query ="SELECT * FROM ARCHIVEMEDCERTIFICATE"; 
                $result = mysqli_query($connect, $query);

                $id = $_GET["studentID"];
                $type = $_GET["type"];
                $id_num = $_GET["id"];

                $tempor = "checkArchived";

                echo "<script type='text/javascript'>
                        getType = '$type';
                        tblNum = '$id_num';
                        id_stud = '$id';
                        passIDPHP($id);
                      </script>";


            }else{
                echo "<script type='text/javascript'>
                        getType = 'newMC';
                      </script>";
            }

        
             
    }

    

 ?>