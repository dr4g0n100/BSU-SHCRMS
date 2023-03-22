<?php 
require '../../../php/centralConnection.php';
session_start();
if(empty($_SESSION['logged_in'])){
 header('Location: ../../../index.html');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Student</title>
        <link rel="stylesheet" href="../css/addRecord-style.css">
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        <script src="../dist/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="../dist/jquery-confirm.min.css">
        <script src="../dist/jquery-confirm.min.js"></script>
        <script src="../dist/jspdf.debug.js"></script>
        <script src="../dist/jspdf.min.js"></script>
        <script src="../dist/html2pdf.bundle.min.js"></script>
        <style>
        @media print{
            .tabs-content{
                display: block;
            }

            #wholetab{
                display: flex;
                width: 100%;
                padding: 2%;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1.8vw;
                font-weight: bold;
                background: #f2eeeb;
                color: #062102;
            }

            #twoButton, #exportButton, #exportButton1, #twoButton1, #StudentInfo, nav, #tab1, #tab2{
                display: none;
            }

            .container, .Recommendation{
                margin-top: 0;
                padding-top: 0;
            }

            .Remarks{
                margin-top: 2%;
                padding-top: 0;
            }

            #IDPic{
                width: 2in;
                height: 2in;
                margin-top: -8%;
            }

            #bsuLogo{
                width: 0.60in;
                height: 0.60in;
            }

            #PhysicalExaminationHeader h3{
                padding-top: 9%;
                padding-left: 3%;
            }

            .One-Info, .Two-Info, .Three-Info, .Four-Info{
            margin-top: -1.5%;
            }

            #TxtOthers, #TxtRemarks, #TxtRecommendation{
                height: 5vh;
            }

            #TAHearingDistance, #TASpeech, #TAEyes, #TAEars, #TANose, #TAHead, #TAAbdomen, #TAGenitoUrinary, #TALymphGlands, #TASkin, #TAExtremities, #TADeformities, #TACavityAndThroat, #TALungs, #TAHeart, #TABreast, #TARadiologicExams, #TABloodAnalysis, #TAUrinalysis, #TAFecalysis, #TAPregnancyTest, #TAHBSAg, #TxtHearingDistanceOption, #TxtSpeechOption, #TxtEyesOption, #TxtEarsOption, #TxtNoseOption, #TxtHeadOption, #TxtAbdomenOption, #TxtGenitoUrinaryOption, #TxtLymphGlandsOption, #TxtSkinOption, #TxtExtremitiesOption, #TxtDeformitiesOption, #TxtCavityAndThroatOption, #TxtLungsOption, #TxtHeartOption, #TxtBreastOption, #TxtRadiologicExamsOption, #TxtBloodAnalysisOption, #TxtUrinalysisOption, #TxtFecalysisOption, #TxtPregnancyTestOption, #TxtHBSAgOption{
                height: 2.70vh;
            }
        }
        </style>
        <script type="text/javascript">

            // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var getType ="";
            var globalAL = "";
            var imgSrc = "";

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

                const nodeList= document.querySelectorAll("input");
                for (let i = 0; i < nodeList.length; i++) {
                    nodeList[i].style.fontSize = "10px";
                } 
                const nodeList1= document.querySelectorAll("label");
                for (let i = 0; i < nodeList1.length; i++) {
                    nodeList1[i].style.fontSize = "10px";
                } 
                const nodeList2= document.querySelectorAll("select");
                for (let i = 0; i < nodeList2.length; i++) {
                    nodeList2[i].style.fontSize = "10px";
                    nodeList2[i].style.height = "3vh";
                } 
                const nodeList3= document.querySelectorAll("span");
                for (let i = 0; i < nodeList3.length; i++) {
                    nodeList3[i].style.fontSize = "10px";
                } 
                const nodeList4= document.querySelectorAll("legend");
                for (let i = 0; i < nodeList4.length; i++) {
                    nodeList4[i].style.fontSize = "11px";
                } 
                const nodeList5= document.querySelectorAll("h3");
                for (let i = 0; i < nodeList5.length; i++) {
                    nodeList5[i].style.fontSize = "12px";
                } 
                const nodeList6= document.querySelectorAll("div");
                for (let i = 0; i < nodeList6.length; i++) {
                    nodeList6[i].style.marginTop = "-2.5px";
                } 
                const nodeList7= document.querySelectorAll("textarea");
                for (let i = 0; i < nodeList7.length; i++) {
                    nodeList7[i].style.height = "3vh";
                } 

                document.getElementById('bsuLogo').style.width = "75px";
                document.getElementById('bsuLogo').style.height = "75px";
                document.getElementById('tabs-bodyID').style.backgroundColor = "white";
                document.getElementById('toDownloadPDF').style.marginTop = "0";
                document.getElementById('toDownloadPDF').style.paddingTop = "0";
                document.getElementById('content').style.display = "block";
                document.getElementById('content1').style.display = "block";
                document.getElementById('tab1').style.display = "none";
                document.getElementById('tab2').style.display = "none";
                document.getElementById('wholetab').style.display = "none";
				
                var opt = {
                    margin: 0.5,
                    filename: 'Student Personal and Medical Information.pdf',
                    jsPDF:{
                        orientation: 'p', 
                        unit: 'in',
                        format: 'legal'
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

            function auto_grow(element) {
                element.style.height = "5vh";
                element.style.height = (element.scrollHeight)+"px";
            }

            function auto_growTextArea(element) {
                element.style.height = "10vh";
                element.style.height = (element.scrollHeight)+"px";
            }

            function openManual(){
                if(globalAL == "admin"){
                    window.open("../../../files/ManualAdmin.pdf");
                }else if(globalAL == "superadmin"){
                    window.open("../../../files/ManualSuperadmin.pdf");
                }else{
                    window.open("../../../files/ManualStandard.pdf");                }
            }

            function fetchName(){
                var temp = document.getElementById('TxtStudentIDNumber').value;
                
                var form_data = new FormData();
                form_data.append("temp", temp);

                $.ajax(
                { 
                    url:"../php/FetchName.php",
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

            function clearMedical(){
                $('#TxtDate').val('');
                $('#TxtTime').val('');
                $('#TxtLMP').val('');
                $('#TxtPregnancy').val('');
                $('#TxtAllergies').val('');
                $('#TxtSurgeries').val('');
                $('#TxtInjuries').val('');
                $('#TxtIllness').val('');
                $('#TxtSchoolYear').val('');
                $('#TxtHeight').val('');
                $('#TxtWeight').val('');
                $('#TxtBMI').val('');
                $('#TxtBloodPressure').val('');
                $('#TxtTemperature').val('');
                $('#TxtPulseRate').val('');
                $('#TxtVisionWithoutGlassesOD').val('');
                $('#TxtVisionWithoutGlassesOS').val('');
                $('#TxtVisionWithGlassesOD').val('');
                $('#TxtVisionWithGlassesOS').val('');
                $('#TxtHearingDistanceOption').val('');
                $('#TxtSpeechOption').val('');
                $('#TxtEyesOption').val('');
                $('#TxtEarsOption').val('');
                $('#TxtNoseOption').val('');
                $('#TxtHeadOption').val('');
                $('#TxtAbdomenOption').val('');
                $('#TxtGenitoUrinaryOption').val('');
                $('#TxtLymphGlandsOption').val('');
                $('#TxtSkinOption').val('');
                $('#TxtExtremitiesOption').val('');
                $('#TxtDeformitiesOption').val('');
                $('#TxtCavityAndThroatOption').val('');
                $('#TxtLungsOption').val('');
                $('#TxtHeartOption').val('');
                $('#TxtBreastOption').val('');
                $('#TxtRadiologicExamsOption').val('');
                $('#TxtBloodAnalysisOption').val('');
                $('#TxtUrinalysisOption').val('');
                $('#TxtFecalysisOption').val('');
                $('#TxtPregnancyTestOption').val('');
                $('#TxtHBSAgOption').val('');

                document.getElementById('TAHearingDistance').style.display = "none"; 
                document.getElementById('TASpeech').style.display = "none"; 
                document.getElementById('TAEyes').style.display = "none"; 
                document.getElementById('TAEars').style.display = "none"; 
                document.getElementById('TANose').style.display = "none"; 
                document.getElementById('TAHead').style.display = "none"; 
                document.getElementById('TAAbdomen').style.display = "none"; 
                document.getElementById('TAGenitoUrinary').style.display = "none"; 
                document.getElementById('TALymphGlands').style.display = "none"; 
                document.getElementById('TASkin').style.display = "none"; 
                document.getElementById('TAExtremities').style.display = "none"; 
                document.getElementById('TADeformities').style.display = "none"; 
                document.getElementById('TACavityAndThroat').style.display = "none"; 
                document.getElementById('TALungs').style.display = "none"; 
                document.getElementById('TAHeart').style.display = "none"; 
                document.getElementById('TABreast').style.display = "none"; 
                document.getElementById('TARadiologicExams').style.display = "none"; 
                document.getElementById('TABloodAnalysis').style.display = "none"; 
                document.getElementById('TAUrinalysis').style.display = "none"; 
                document.getElementById('TAFecalysis').style.display = "none"; 
                document.getElementById('TAPregnancyTest').style.display = "none"; 
                document.getElementById('TAHBSAg').style.display = "none"; 
               
                $('#TAHearingDistance').val('');
                $('#TASpeech').val('');
                $('#TAEyes').val('');
                $('#TAEars').val('');
                $('#TANose').val('');
                $('#TAHead').val('');
                $('#TAAbdomen').val('');
                $('#TAGenitoUrinary').val('');
                $('#TALymphGlands').val('');
                $('#TASkin').val('');
                $('#TAExtremities').val('');
                $('#TADeformities').val('');
                $('#TACavityAndThroat').val('');
                $('#TALungs').val('');
                $('#TAHeart').val('');
                $('#TABreast').val('');
                $('#TARadiologicExams').val('');
                $('#TABloodAnalysis').val('');
                $('#TAUrinalysis').val('');
                $('#TAFecalysis').val('');
                $('#TAPregnancyTest').val('');
                $('#TAHBSAg').val('');
                $('#TxtOthers').val('');
                $('#TxtRemarks').val('');
                $('#TxtRecommendation').val('');
            }

            function clearPersonal(){
                $('#TxtDocumentCode').val('');
                $('#TxtRevisionNumber').val('');
                $('#TxtEffectivity').val('');
                $('#TxtNoLabel').val('');
                document.getElementById("IDPic").src = "../images/id picture.webp";
                $('#TxtStudentImage').val('');
                $('#TxtStudentCategory').val('');
                $('#TCourse').val('');
                $('#TYear').val('');
                $('#TSection').val('');
                $('#TxtLastname').val('');
                $('#TxtFirstname').val('');
                $('#TxtMiddlename').val('');
                $('#TxtExtension').val('');
                $('#TxtAge').val('');
                $('#TxtBirthdate').val('');
                $('#RadMale').prop('checked', false);
                $('#RadFemale').prop('checked', false);
                $('#TxtAddress').val('');
                $('#TxtStudentContactNumber').val('');
                $('#RadGuardian').prop('checked', false);
                $('#RadParent').prop('checked', false);
                $('#TGPCategory').val('');
                $('#TxtContactPerson').val('');
                $('#TxtPGContactNumber').val('');
                $('#RadGuardian1').prop('checked', false);
                $('#RadParent1').prop('checked', false);
                $('#TGPCategory1').val('');
                $('#TxtContactPerson1').val('');
                $('#TxtPGContactNumber1').val('');

            }

            function showTA(IDOption, TAID){
                var selectBox = document.getElementById(IDOption);
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                if(selectedValue == "Unremarkable"){
                    document.getElementById(TAID).style.display = "none";
                }else if(selectedValue == "With Findings"){
                    document.getElementById(TAID).style.display = "block";
                }
            }

            function clickedPrint(){

                var idNum = document.getElementById('TxtStudentIDNumber').value;
                var type = getType;

                window.location.href = '../../../php/print.php?id=' +idNum +'&type=' +type;

                    /*$.ajax(
                        { 
                            url:"../../../php/print.php",
                            method:"POST",
                            data:jQuery.param({ 'idnumber': idNum, 'type': type }), 
                            contentType: false,
                            processData: false,
                            cache: false,
                            dataType: "xml",
                            success:function(xml)
                            {
                                $(xml).find('output').each(function()
                                {
                                    var message = $(this).attr('Message');
                                
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
                        });*/
                       
            }

            function showAddMore(){
                document.getElementById('addMore').style.display = 'none';
                document.getElementById('addMoreForm').style.display = 'block';
                document.getElementById('addMoreForm1').style.display = 'block';
                document.getElementById('addMoreForm2').style.display = 'block';
            }

            function styleInput(idnum){
                document.getElementById(idnum).style.background = "none";  
                document.getElementById(idnum).style.borderBottom = "solid 2px black";    
                document.getElementById(idnum).style.borderTop = "solid 1px gray"; 
                document.getElementById(idnum).style.borderRight = "solid 1px gray"; 
                document.getElementById(idnum).style.borderLeft = "solid 1px gray";  
            }

            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
                  $.ajax({
                    url:"../../../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../../../index.html';
                        }, 100);
                    }
                  })
            }

            //main function for user activity logging
            function logAction(userAction){
                act = userAction;
                $.ajax({
                    url:"../../../php/logAction.php",
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

            var TempSex;
            var TempGuardianParent;
            var TempGuardianParent1;
            var TempBtnValue;

            /* function logout(){
            sessionStorage.clear();
            } */

            function alphaOnlySY(){
                var key = event.keyCode;
                return ((key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 189 || key == 8);
            }

            function alphaOnlyCP(){
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 188 || key == 189);
            }

            function changeFunc(){
                var selectBox = document.getElementById("TxtStudentCategory");
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                if(selectedValue == "Elementary"){
                    document.getElementById("Cour").style.display = 'none';
                    document.getElementById('YR').innerHTML = 'Grade';
                    document.getElementById("YO1").setAttribute('value','1');
                    document.getElementById("YO2").setAttribute('value','2');
                    document.getElementById("YO3").setAttribute('value','3');
                    document.getElementById("YO4").setAttribute('value','4');
                    document.getElementById("YO5").setAttribute('value','5');
                    document.getElementById("YO6").setAttribute('value','6'); 

                    document.getElementById("SO1").removeAttribute("value");
                    document.getElementById("SO2").removeAttribute("value");
                    document.getElementById("SO3").removeAttribute("value");
                    document.getElementById("SO4").removeAttribute("value");
                    document.getElementById("SO5").removeAttribute("value");
                    document.getElementById("SO6").removeAttribute("value");

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "Highschool"){
                    document.getElementById("Cour").style.display = 'none';
                    document.getElementById('YR').innerHTML = 'Grade';
                    document.getElementById("YO1").setAttribute('value','7');
                    document.getElementById("YO2").setAttribute('value','8');
                    document.getElementById("YO3").setAttribute('value','9');
                    document.getElementById("YO4").setAttribute('value','10');
                    document.getElementById("YO5").removeAttribute("value");
                    document.getElementById("YO6").removeAttribute("value");

                    document.getElementById("SO1").removeAttribute("value");
                    document.getElementById("SO2").removeAttribute("value");
                    document.getElementById("SO3").removeAttribute("value");
                    document.getElementById("SO4").removeAttribute("value");
                    document.getElementById("SO5").removeAttribute("value");
                    document.getElementById("SO6").removeAttribute("value");

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "Senior Highschool"){
                    document.getElementById("Cour").style.display = 'block';
                    document.getElementById('YR').innerHTML = 'Grade';
                    document.getElementById('CS').innerHTML = 'Strand';
                    document.getElementById("YO1").setAttribute('value','11');
                    document.getElementById("YO2").setAttribute('value','12');
                    document.getElementById("YO3").removeAttribute("value");
                    document.getElementById("YO4").removeAttribute("value");
                    document.getElementById("YO5").removeAttribute("value");
                    document.getElementById("YO6").removeAttribute("value");

                    document.getElementById("SO1").removeAttribute("value");
                    document.getElementById("SO2").removeAttribute("value");
                    document.getElementById("SO3").removeAttribute("value");
                    document.getElementById("SO4").removeAttribute("value");
                    document.getElementById("SO5").removeAttribute("value");
                    document.getElementById("SO6").removeAttribute("value");

                    document.getElementById("CO1").setAttribute('value','Science, Technology, Engineering, and Mathematics (STEM)');
                    document.getElementById("CO2").removeAttribute("value");
                    document.getElementById("CO3").removeAttribute("value");
                    document.getElementById("CO4").removeAttribute("value");
                    document.getElementById("CO5").removeAttribute("value");
                    document.getElementById("CO6").removeAttribute("value");
                    document.getElementById("CO7").removeAttribute("value");
                    document.getElementById("CO8").removeAttribute("value");
                    document.getElementById("CO9").removeAttribute("value");
                    document.getElementById("CO10").removeAttribute("value");
                    document.getElementById("CO11").removeAttribute("value");
                    document.getElementById("CO12").removeAttribute("value");
                    document.getElementById("CO13").removeAttribute("value");
                    document.getElementById("CO14").removeAttribute("value");
                    document.getElementById("CO15").removeAttribute("value");
                    document.getElementById("CO16").removeAttribute("value");
                    document.getElementById("CO17").removeAttribute("value");
                    document.getElementById("CO18").removeAttribute("value");
                    document.getElementById("CO19").removeAttribute("value");
                    document.getElementById("CO20").removeAttribute("value");
                    document.getElementById("CO21").removeAttribute("value");
                    document.getElementById("CO22").removeAttribute("value");
                    document.getElementById("CO23").removeAttribute("value");
                    document.getElementById("CO24").removeAttribute("value");
                    document.getElementById("CO25").removeAttribute("value");
                    document.getElementById("CO26").removeAttribute("value");
                    document.getElementById("CO27").removeAttribute("value");
                    document.getElementById("CO28").removeAttribute("value");
                    document.getElementById("CO29").removeAttribute("value");
                    document.getElementById("CO30").removeAttribute("value");
                    document.getElementById("CO31").removeAttribute("value");
                    document.getElementById("CO32").removeAttribute("value");

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "College"){
                    document.getElementById("Cour").style.display = 'block';
                    document.getElementById('YR').innerHTML = 'Year';
                    document.getElementById('CS').innerHTML = 'Course';
                    document.getElementById("YO1").setAttribute('value','1');
                    document.getElementById("YO2").setAttribute('value','2');
                    document.getElementById("YO3").setAttribute('value','3');
                    document.getElementById("YO4").setAttribute('value','4');
                    document.getElementById("YO5").setAttribute('value','Irregular');
                    document.getElementById("YO6").removeAttribute("value");

                    document.getElementById("SO1").setAttribute('value','A');
                    document.getElementById("SO2").setAttribute('value','B');
                    document.getElementById("SO3").setAttribute('value','C');
                    document.getElementById("SO4").setAttribute('value','D');
                    document.getElementById("SO5").removeAttribute("value");
                    document.getElementById("SO6").removeAttribute("value");

                    document.getElementById("CO1").setAttribute('value','Bachelor of Arts in Communication (BA Com)');
                    document.getElementById("CO2").setAttribute('value','Bachelor of Arts in English Language (BA EL)');
                    document.getElementById("CO3").setAttribute('value','Bachelor of Arts in Filipino Language (BA FL)');
                    document.getElementById("CO4").setAttribute('value','Bachelor of Early Childhood Education (BECEd)');
                    document.getElementById("CO5").setAttribute('value','Bachelor of Elementary Education (BEEd)');
                    document.getElementById("CO6").setAttribute('value','Bachelor of Library and Information Science (BLIS)');
                    document.getElementById("CO7").setAttribute('value','Bachelor of Physical Education (BPEd)');
                    document.getElementById("CO8").setAttribute('value','Bachelor of Public Administration (BPA)');
                    document.getElementById("CO9").setAttribute('value','Bachelor of Science in Agricultural and Biosystems Engineering (BSABE)');
                    document.getElementById("CO10").setAttribute('value','Bachelor of Science in Agriculture (BSA)');
                    document.getElementById("CO11").setAttribute('value','Bachelor of Science in Biology (BS Bio)');
                    document.getElementById("CO12").setAttribute('value','Bachelor of Science in Chemistry (BS Chem)');
                    document.getElementById("CO13").setAttribute('value','Bachelor of Science in Development Communication (BSDC)');
                    document.getElementById("CO14").setAttribute('value','Bachelor of Science in Entrepreneurship (BS Entrep)');
                    document.getElementById("CO15").setAttribute('value','Bachelor of Science in Environmental Science (BSES)');
                    document.getElementById("CO16").setAttribute('value','Bachelor of Science in Exercise and Sports Sciences (BSESS)');
                    document.getElementById("CO17").setAttribute('value','Bachelor of Science in Forestry (BSF)');
                    document.getElementById("CO18").setAttribute('value','Bachelor of Science in Hospitality Management (BSHM)');
                    document.getElementById("CO19").setAttribute('value','Bachelor of Science in Information Technology (BSIT)');
                    document.getElementById("CO20").setAttribute('value','Bachelor of Science in Mathematics (BS Math)');
                    document.getElementById("CO21").setAttribute('value','Bachelor of Science in Nursing (BSN)');
                    document.getElementById("CO22").setAttribute('value','Bachelor of Science in Nutrition and Dietetics (BSND)');
                    document.getElementById("CO23").setAttribute('value','Bachelor of Science in Statistics (BSS)');
                    document.getElementById("CO24").setAttribute('value','Bachelor of Secondary Education major in English (BSEd-English)');
                    document.getElementById("CO25").setAttribute('value','Bachelor of Secondary Education major in Filipino (BSEd-Filipino)');
                    document.getElementById("CO26").setAttribute('value','Bachelor of Secondary Education major in Mathematics (BSEd-Math)');
                    document.getElementById("CO27").setAttribute('value','Bachelor of Secondary Education major in Science (BSEd-Science)');
                    document.getElementById("CO28").setAttribute('value','Bachelor of Secondary Education major in Social Studies (BSEd-SSt)');
                    document.getElementById("CO29").setAttribute('value','Bachelor of Secondary Education major in Values Education (BSEd-VE)');
                    document.getElementById("CO30").setAttribute('value','Bachelor of Technology and Livelihood Education-Home Economics (BTLEd-HE)');
                    document.getElementById("CO31").setAttribute('value','Doctor of Veterinary Medicine (DVM)');
                    document.getElementById("CO32").setAttribute('value','Bachelor of Science in Agribusiness (BSAB)');

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }
            }

            function calculateBMI(){
                var weight = document.getElementById('TxtWeight').value;
                var height = document.getElementById('TxtHeight').value;
                var bmi = weight/((height/100)*(height/100));
                bmi = bmi.toFixed(2);

                if(bmi < 18.5){
                    $('#TxtBMI').val(bmi + " (Underweight)");
                }else if(bmi >= 18.5 && bmi <= 24.9){
                    $('#TxtBMI').val(bmi + " (Normal)");
                }else if(bmi >= 25 && bmi <= 29.9){
                    $('#TxtBMI').val(bmi + " (Overweight)");
                }else if(bmi >= 30 && bmi <= 34.9){
                    $('#TxtBMI').val(bmi + " (Obese Class I)");
                }else if(bmi >= 35 && bmi <= 39.9){
                    $('#TxtBMI').val(bmi + " (Obese Class II)");
                }else if(bmi > 40){
                    $('#TxtBMI').val(bmi + " (Morbid)");
                }
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

            function clickedEdit(){
                document.getElementById('TxtDocumentCode').style.backgroundColor = "white"; 
                document.getElementById('TxtRevisionNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtEffectivity').style.backgroundColor = "white"; 
                document.getElementById('TxtNoLabel').style.backgroundColor = "white"; 
                document.getElementById('TxtStudentCategory').style.backgroundColor = "white";    
                document.getElementById('TCourse').style.backgroundColor = "white"; 
                document.getElementById('TYear').style.backgroundColor = "white"; 
                document.getElementById('TSection').style.backgroundColor = "white"; 
                document.getElementById('TxtLastname').style.backgroundColor = "white"; 
                document.getElementById('TxtFirstname').style.backgroundColor = "white"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "white"; 
                document.getElementById('TxtExtension').style.backgroundColor = "white"; 
                document.getElementById('TxtBirthdate').style.backgroundColor = "white"; 

                document.getElementById('TxtAddress').style.backgroundColor = "white";    
                document.getElementById('TxtStudentContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory1').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson1').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber1').style.backgroundColor = "white";

                document.getElementById('TxtDate').style.backgroundColor = "white";
                document.getElementById('TxtTime').style.backgroundColor = "white"; 
                document.getElementById('TxtLMP').style.backgroundColor = "white"; 
                document.getElementById('TxtPregnancy').style.backgroundColor = "white"; 
                document.getElementById('TxtAllergies').style.backgroundColor = "white"; 
                document.getElementById('TxtSurgeries').style.backgroundColor = "white"; 
                document.getElementById('TxtInjuries').style.backgroundColor = "white"; 
                document.getElementById('TxtIllness').style.backgroundColor = "white"; 
                document.getElementById('TxtSchoolYear').style.backgroundColor = "white"; 
                
                document.getElementById('TxtHeight').style.backgroundColor = "white";    
                document.getElementById('TxtWeight').style.backgroundColor = "white"; 
                document.getElementById('TxtBloodPressure').style.backgroundColor = "white"; 
                document.getElementById('TxtTemperature').style.backgroundColor = "white"; 
                document.getElementById('TxtPulseRate').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOS').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOS').style.backgroundColor = "white"; 
                
                document.getElementById('TxtHearingDistanceOption').style.backgroundColor = "white";    
                document.getElementById('TxtSpeechOption').style.backgroundColor = "white"; 
                document.getElementById('TxtEyesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtEarsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtNoseOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHeadOption').style.backgroundColor = "white"; 
                document.getElementById('TxtAbdomenOption').style.backgroundColor = "white"; 
                document.getElementById('TxtGenitoUrinaryOption').style.backgroundColor = "white"; 
                document.getElementById('TxtLymphGlandsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtSkinOption').style.backgroundColor = "white";    
                document.getElementById('TxtExtremitiesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtDeformitiesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtCavityAndThroatOption').style.backgroundColor = "white"; 
                document.getElementById('TxtLungsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHeartOption').style.backgroundColor = "white"; 
                document.getElementById('TxtBreastOption').style.backgroundColor = "white"; 
                document.getElementById('TxtRadiologicExamsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtBloodAnalysisOption').style.backgroundColor = "white"; 
                document.getElementById('TxtUrinalysisOption').style.backgroundColor = "white";    
                document.getElementById('TxtFecalysisOption').style.backgroundColor = "white"; 
                document.getElementById('TxtPregnancyTestOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHBSAgOption').style.backgroundColor = "white"; 

                document.getElementById('TAHearingDistance').style.backgroundColor = "white";    
                document.getElementById('TASpeech').style.backgroundColor = "white"; 
                document.getElementById('TAEyes').style.backgroundColor = "white"; 
                document.getElementById('TAEars').style.backgroundColor = "white"; 
                document.getElementById('TANose').style.backgroundColor = "white"; 
                document.getElementById('TAHead').style.backgroundColor = "white"; 
                document.getElementById('TAAbdomen').style.backgroundColor = "white"; 
                document.getElementById('TAGenitoUrinary').style.backgroundColor = "white"; 
                document.getElementById('TALymphGlands').style.backgroundColor = "white"; 
                document.getElementById('TASkin').style.backgroundColor = "white";    
                document.getElementById('TAExtremities').style.backgroundColor = "white"; 
                document.getElementById('TADeformities').style.backgroundColor = "white"; 
                document.getElementById('TACavityAndThroat').style.backgroundColor = "white"; 
                document.getElementById('TALungs').style.backgroundColor = "white"; 
                document.getElementById('TAHeart').style.backgroundColor = "white"; 
                document.getElementById('TABreast').style.backgroundColor = "white"; 
                document.getElementById('TARadiologicExams').style.backgroundColor = "white"; 
                document.getElementById('TABloodAnalysis').style.backgroundColor = "white"; 
                document.getElementById('TAUrinalysis').style.backgroundColor = "white";    
                document.getElementById('TAFecalysis').style.backgroundColor = "white"; 
                document.getElementById('TAPregnancyTest').style.backgroundColor = "white"; 
                document.getElementById('TAHBSAg').style.backgroundColor = "white"; 
                document.getElementById('TxtOthers').style.backgroundColor = "white"; 
                
                document.getElementById('TxtRecommendation').style.backgroundColor = "white"; 
                document.getElementById('TxtRemarks').style.backgroundColor = "white"; 

                document.getElementById("TxtDocumentCode").removeAttribute("readonly");
                document.getElementById("TxtRevisionNumber").removeAttribute("readonly");
                document.getElementById("TxtEffectivity").removeAttribute("readonly");
                document.getElementById("TxtNoLabel").removeAttribute("readonly");
                document.getElementById("TxtStudentImage").removeAttribute("disabled");
                document.getElementById("TxtStudentCategory").removeAttribute("disabled");
                document.getElementById("TCourse").removeAttribute("disabled");
                document.getElementById("TYear").removeAttribute("disabled");
                document.getElementById("TSection").removeAttribute("disabled");
                document.getElementById("TxtLastname").removeAttribute("readonly");
                document.getElementById("TxtFirstname").removeAttribute("readonly");
                document.getElementById("TxtMiddlename").removeAttribute("readonly");
                document.getElementById("TxtExtension").removeAttribute("readonly");
                document.getElementById("TxtBirthdate").removeAttribute("readonly");
                document.getElementById("RadMale").removeAttribute("disabled");
                document.getElementById("RadFemale").removeAttribute("disabled");
                document.getElementById("TxtAddress").removeAttribute("readonly");
                document.getElementById("TxtStudentContactNumber").removeAttribute("readonly");
                document.getElementById("RadGuardian").removeAttribute("disabled");
                document.getElementById("RadParent").removeAttribute("disabled");
                document.getElementById("RadNone").removeAttribute("disabled");
                document.getElementById("RadNone1").removeAttribute("disabled");
                document.getElementById("TGPCategory").removeAttribute("disabled");
                document.getElementById("TxtContactPerson").removeAttribute("readonly");
                document.getElementById("TxtPGContactNumber").removeAttribute("readonly");
                document.getElementById("RadGuardian1").removeAttribute("disabled");
                document.getElementById("RadParent1").removeAttribute("disabled");
                document.getElementById("TGPCategory1").removeAttribute("disabled");
                document.getElementById("TxtContactPerson1").removeAttribute("readonly");
                document.getElementById("TxtPGContactNumber1").removeAttribute("readonly");
                document.getElementById("TxtDate").removeAttribute("readonly");
                document.getElementById("TxtTime").removeAttribute("readonly");

                document.getElementById("TxtLMP").removeAttribute("readonly");
                document.getElementById("TxtPregnancy").removeAttribute("readonly");
                document.getElementById("TxtAllergies").removeAttribute("readonly");
                document.getElementById("TxtSurgeries").removeAttribute("readonly");
                document.getElementById("TxtInjuries").removeAttribute("readonly");
                document.getElementById("TxtIllness").removeAttribute("readonly");
                document.getElementById("TxtSchoolYear").removeAttribute("readonly");
                document.getElementById("TxtHeight").removeAttribute("readonly");
                document.getElementById("TxtWeight").removeAttribute("readonly");
                document.getElementById("TxtBloodPressure").removeAttribute("readonly");
                document.getElementById("TxtTemperature").removeAttribute("readonly");
                document.getElementById("TxtPulseRate").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOS").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOS").removeAttribute("readonly");
                
                document.getElementById("TAHearingDistance").removeAttribute("readonly");
                document.getElementById("TASpeech").removeAttribute("readonly");
                document.getElementById("TAEyes").removeAttribute("readonly");
                document.getElementById("TAEars").removeAttribute("readonly");
                document.getElementById("TANose").removeAttribute("readonly");
                document.getElementById("TAHead").removeAttribute("readonly");
                document.getElementById("TAAbdomen").removeAttribute("readonly");
                document.getElementById("TAGenitoUrinary").removeAttribute("readonly");
                document.getElementById("TALymphGlands").removeAttribute("readonly");
                document.getElementById("TASkin").removeAttribute("readonly");
                document.getElementById("TAExtremities").removeAttribute("readonly");
                document.getElementById("TADeformities").removeAttribute("readonly");
                document.getElementById("TACavityAndThroat").removeAttribute("readonly");
                document.getElementById("TALungs").removeAttribute("readonly");
                document.getElementById("TAHeart").removeAttribute("readonly");
                document.getElementById("TABreast").removeAttribute("readonly");
                document.getElementById("TARadiologicExams").removeAttribute("readonly");
                document.getElementById("TABloodAnalysis").removeAttribute("readonly");
                document.getElementById("TAUrinalysis").removeAttribute("readonly");
                document.getElementById("TAFecalysis").removeAttribute("readonly");
                document.getElementById("TAPregnancyTest").removeAttribute("readonly");
                document.getElementById("TAHBSAg").removeAttribute("readonly");
                document.getElementById("TxtHearingDistanceOption").removeAttribute("disabled");
                document.getElementById("TxtSpeechOption").removeAttribute("disabled");
                document.getElementById("TxtEyesOption").removeAttribute("disabled");
                document.getElementById("TxtEarsOption").removeAttribute("disabled");
                document.getElementById("TxtNoseOption").removeAttribute("disabled");
                document.getElementById("TxtHeadOption").removeAttribute("disabled");
                document.getElementById("TxtAbdomenOption").removeAttribute("disabled");
                document.getElementById("TxtGenitoUrinaryOption").removeAttribute("disabled");
                document.getElementById("TxtLymphGlandsOption").removeAttribute("disabled");
                document.getElementById("TxtSkinOption").removeAttribute("disabled");
                document.getElementById("TxtExtremitiesOption").removeAttribute("disabled");
                document.getElementById("TxtDeformitiesOption").removeAttribute("disabled");
                document.getElementById("TxtCavityAndThroatOption").removeAttribute("disabled");
                document.getElementById("TxtLungsOption").removeAttribute("disabled");
                document.getElementById("TxtHeartOption").removeAttribute("disabled");
                document.getElementById("TxtBreastOption").removeAttribute("disabled");
                document.getElementById("TxtRadiologicExamsOption").removeAttribute("disabled");
                document.getElementById("TxtBloodAnalysisOption").removeAttribute("disabled");
                document.getElementById("TxtUrinalysisOption").removeAttribute("disabled");
                document.getElementById("TxtFecalysisOption").removeAttribute("disabled");
                document.getElementById("TxtPregnancyTestOption").removeAttribute("disabled");
                document.getElementById("TxtHBSAgOption").removeAttribute("disabled");
                document.getElementById("TxtOthers").removeAttribute("readonly");

                document.getElementById("TxtRecommendation").removeAttribute("readonly");
                document.getElementById("TxtRemarks").removeAttribute("readonly");
                document.getElementById("BtnAdd").removeAttribute("disabled");
                document.getElementById("BtnClear").removeAttribute("disabled");
                document.getElementById("BtnAdd1").removeAttribute("disabled");
                document.getElementById("BtnClear1").removeAttribute("disabled");
                document.getElementById("BtnSave").removeAttribute("disabled");
                document.getElementById("BtnEdit").removeAttribute("disabled");
                document.getElementById("BtnSave1").removeAttribute("disabled");
                document.getElementById("BtnEdit1").removeAttribute("disabled");
            }

            function clickedGuardian(){
                document.getElementById("father").removeAttribute("value");
                document.getElementById("mother").removeAttribute("value");
                document.getElementById("sibling").setAttribute('value','Sibling');
                document.getElementById("grandparents").setAttribute('value','Grandparents');
                document.getElementById("ward").setAttribute('value','Ward');
                $('#TGPCategory').val('');
            }

            function clickedParent(){
                document.getElementById("sibling").removeAttribute("value");
                document.getElementById("grandparents").removeAttribute("value");
                document.getElementById("ward").removeAttribute("value");
                document.getElementById("father").setAttribute('value','Father');
                document.getElementById("mother").setAttribute('value','Mother');
                $('#TGPCategory').val('');
            }

            function clickedGuardian1(){
                document.getElementById("father1").removeAttribute("value");
                document.getElementById("mother1").removeAttribute("value");
                document.getElementById("sibling1").setAttribute('value','Sibling');
                document.getElementById("grandparents1").setAttribute('value','Grandparents');
                document.getElementById("ward1").setAttribute('value','Ward');
                $('#TGPCategory1').val('');
            }

            function clickedParent1(){
                document.getElementById("sibling1").removeAttribute("value");
                document.getElementById("grandparents1").removeAttribute("value");
                document.getElementById("ward1").removeAttribute("value");
                document.getElementById("father1").setAttribute('value','Father');
                document.getElementById("mother1").setAttribute('value','Mother');
                $('#TGPCategory1').val('');
            }

            function clickedNone1(){
                document.getElementById("sibling1").removeAttribute("value");
                document.getElementById("grandparents1").removeAttribute("value");
                document.getElementById("ward1").removeAttribute("value");
                document.getElementById("father1").removeAttribute("value");
                document.getElementById("mother1").removeAttribute("value");
                $('#TGPCategory1').val('');
            }

            function clickedNone(){
                document.getElementById("sibling").removeAttribute("value");
                document.getElementById("grandparents").removeAttribute("value");
                document.getElementById("ward").removeAttribute("value");
                document.getElementById("father").removeAttribute("value");
                document.getElementById("mother").removeAttribute("value");
                $('#TGPCategory').val('');
            }

            function editTableNav(y){
                if(y == "checkArchived"){
                    document.getElementById('nav4').classList.remove("active");
                    document.getElementById('nav6').classList.add("active");
                    document.getElementById('maint').classList.add("active");
                    document.getElementById('maint').style.color = "white";
                }
            }

            function setAttr(){
                document.getElementById("TxtDocumentCode").setAttribute('readonly','readonly');
                document.getElementById("TxtRevisionNumber").setAttribute('readonly','readonly');
                document.getElementById("TxtEffectivity").setAttribute('readonly','readonly');
                document.getElementById("TxtNoLabel").setAttribute('readonly','readonly');
                document.getElementById("TxtStudentImage").setAttribute('disabled','disabled');
                document.getElementById("TxtStudentCategory").setAttribute('disabled','disabled');
                document.getElementById("TCourse").setAttribute('disabled','disabled');
                document.getElementById("TYear").setAttribute('disabled','disabled');
                document.getElementById("TSection").setAttribute('disabled','disabled');
                document.getElementById("TxtLastname").setAttribute('readonly','readonly');
                document.getElementById("TxtFirstname").setAttribute('readonly','readonly');
                document.getElementById("TxtMiddlename").setAttribute('readonly','readonly');
                document.getElementById("TxtExtension").setAttribute('readonly','readonly');
                document.getElementById("TxtBirthdate").setAttribute('readonly','readonly');
                if(TempSex == "male"){
                    $('#RadMale').prop('checked', true);
                }else if(TempSex == "female"){
                    $('#RadFemale').prop('checked', true);
                }else{
                    $('#RadMale').prop('checked', false);
                    $('#RadFemale').prop('checked', false);
                }
                document.getElementById("RadMale").setAttribute('disabled','disabled');
                document.getElementById("RadFemale").setAttribute('disabled','disabled');
                document.getElementById("TxtAddress").setAttribute('readonly','readonly');
                document.getElementById("TxtStudentContactNumber").setAttribute('readonly','readonly');
                if(TempGuardianParent == "guardian"){
                    $('#RadGuardian').prop('checked', true);
                }else if(TempGuardianParent == "parent"){
                    $('#RadParent').prop('checked', true);
                }else{
                    $('#RadNone').prop('checked', true);
                }
                document.getElementById("RadGuardian").setAttribute('disabled','disabled');
                document.getElementById("RadNone").setAttribute('disabled','disabled');
                document.getElementById("RadParent").setAttribute('disabled','disabled');
                document.getElementById("TGPCategory").setAttribute('disabled','disabled');
                document.getElementById("TxtContactPerson").setAttribute('readonly','readonly');
                document.getElementById("TxtPGContactNumber").setAttribute('readonly','readonly');
                if(TempGuardianParent1 == "guardian"){
                    $('#RadGuardian1').prop('checked', true);
                }else if(TempGuardianParent1 == "parent"){
                    $('#RadParent1').prop('checked', true);
                }else{
                    $('#RadNone1').prop('checked', true);
                }
                document.getElementById("RadNone1").setAttribute('disabled','disabled');
                document.getElementById("RadGuardian1").setAttribute('disabled','disabled');
                document.getElementById("RadParent1").setAttribute('disabled','disabled');
                document.getElementById("TGPCategory1").setAttribute('disabled','disabled');
                document.getElementById("TxtContactPerson1").setAttribute('readonly','readonly');
                document.getElementById("TxtPGContactNumber1").setAttribute('readonly','readonly');
                document.getElementById("TxtDate").setAttribute('readonly','readonly');
                document.getElementById("TxtTime").setAttribute('readonly','readonly');
                document.getElementById("TxtLMP").setAttribute('readonly','readonly');
                document.getElementById("TxtPregnancy").setAttribute('readonly','readonly');
                document.getElementById("TxtAllergies").setAttribute('readonly','readonly');
                document.getElementById("TxtSurgeries").setAttribute('readonly','readonly');
                document.getElementById("TxtInjuries").setAttribute('readonly','readonly');
                document.getElementById("TxtIllness").setAttribute('readonly','readonly');
                document.getElementById("TxtSchoolYear").setAttribute('readonly','readonly');
                document.getElementById("TxtHeight").setAttribute('readonly','readonly');
                document.getElementById("TxtWeight").setAttribute('readonly','readonly');
                document.getElementById("TxtBloodPressure").setAttribute('readonly','readonly');
                document.getElementById("TxtTemperature").setAttribute('readonly','readonly');
                document.getElementById("TxtPulseRate").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithoutGlassesOD").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithoutGlassesOS").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithGlassesOD").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithGlassesOS").setAttribute('readonly','readonly');
                
                document.getElementById("TAHearingDistance").setAttribute('readonly','readonly');
                document.getElementById("TASpeech").setAttribute('readonly','readonly');
                document.getElementById("TAEyes").setAttribute('readonly','readonly');
                document.getElementById("TAEars").setAttribute('readonly','readonly');
                document.getElementById("TANose").setAttribute('readonly','readonly');
                document.getElementById("TAHead").setAttribute('readonly','readonly');
                document.getElementById("TAAbdomen").setAttribute('readonly','readonly');
                document.getElementById("TAGenitoUrinary").setAttribute('readonly','readonly');
                document.getElementById("TALymphGlands").setAttribute('readonly','readonly');
                document.getElementById("TASkin").setAttribute('readonly','readonly');
                document.getElementById("TAExtremities").setAttribute('readonly','readonly');
                document.getElementById("TADeformities").setAttribute('readonly','readonly');
                document.getElementById("TACavityAndThroat").setAttribute('readonly','readonly');
                document.getElementById("TALungs").setAttribute('readonly','readonly');
                document.getElementById("TAHeart").setAttribute('readonly','readonly');
                document.getElementById("TABreast").setAttribute('readonly','readonly');
                document.getElementById("TARadiologicExams").setAttribute('readonly','readonly');
                document.getElementById("TABloodAnalysis").setAttribute('readonly','readonly');
                document.getElementById("TAUrinalysis").setAttribute('readonly','readonly');
                document.getElementById("TAFecalysis").setAttribute('readonly','readonly');
                document.getElementById("TAPregnancyTest").setAttribute('readonly','readonly');
                document.getElementById("TAHBSAg").setAttribute('readonly','readonly');
                document.getElementById("TxtHearingDistanceOption").setAttribute('disabled','disabled');
                document.getElementById("TxtSpeechOption").setAttribute('disabled','disabled');
                document.getElementById("TxtEyesOption").setAttribute('disabled','disabled');
                document.getElementById("TxtEarsOption").setAttribute('disabled','disabled');
                document.getElementById("TxtNoseOption").setAttribute('disabled','disabled');
                document.getElementById("TxtHeadOption").setAttribute('disabled','disabled');
                document.getElementById("TxtAbdomenOption").setAttribute('disabled','disabled');
                document.getElementById("TxtGenitoUrinaryOption").setAttribute('disabled','disabled');
                document.getElementById("TxtLymphGlandsOption").setAttribute('disabled','disabled');
                document.getElementById("TxtSkinOption").setAttribute('disabled','disabled');
                document.getElementById("TxtExtremitiesOption").setAttribute('disabled','disabled');
                document.getElementById("TxtDeformitiesOption").setAttribute('disabled','disabled');
                document.getElementById("TxtCavityAndThroatOption").setAttribute('disabled','disabled');
                document.getElementById("TxtLungsOption").setAttribute('disabled','disabled');
                document.getElementById("TxtHeartOption").setAttribute('disabled','disabled');
                document.getElementById("TxtBreastOption").setAttribute('disabled','disabled');
                document.getElementById("TxtRadiologicExamsOption").setAttribute('disabled','disabled');
                document.getElementById("TxtBloodAnalysisOption").setAttribute('disabled','disabled');
                document.getElementById("TxtUrinalysisOption").setAttribute('disabled','disabled');
                document.getElementById("TxtFecalysisOption").setAttribute('disabled','disabled');
                document.getElementById("TxtPregnancyTestOption").setAttribute('disabled','disabled');
                document.getElementById("TxtHBSAgOption").setAttribute('disabled','disabled');
                document.getElementById("TxtOthers").setAttribute('readonly','readonly');

                document.getElementById("TxtRecommendation").setAttribute('readonly','readonly');
                document.getElementById("TxtRemarks").setAttribute('readonly','readonly');
                document.getElementById("BtnEdit").removeAttribute("disabled");
                document.getElementById("BtnSave").setAttribute('disabled','disabled');
                document.getElementById("BtnEdit1").removeAttribute("disabled");
                document.getElementById("BtnClear").setAttribute('disabled','disabled');
                document.getElementById("BtnClear1").setAttribute('disabled','disabled');
                document.getElementById("BtnSave1").setAttribute('disabled','disabled');
                document.getElementById("BtnAdd").style.display = 'none';
                document.getElementById("BtnAdd1").style.display = 'none';
                document.getElementById("BtnPrint").style.display = 'flex';
                document.getElementById("BtnPrint1").style.display = 'flex';
                document.getElementById("BtnPDF").style.display = 'flex';
                document.getElementById("BtnPDF1").style.display = 'flex';
                document.getElementById("BtnEdit").style.display = 'flex';
                document.getElementById("BtnEdit1").style.display = 'flex';
                document.getElementById("BtnSave").style.display = 'flex';
                document.getElementById("BtnSave1").style.display = 'flex';
                document.getElementById("BtnClear").style.display = 'none';
                document.getElementById("BtnClear1").style.display = 'none';
            }

            function removeAttr(){
                document.getElementById("TxtDocumentCode").removeAttribute("readonly");
                document.getElementById("TxtRevisionNumber").removeAttribute("readonly");
                document.getElementById("TxtEffectivity").removeAttribute("readonly");
                document.getElementById("TxtNoLabel").removeAttribute("readonly");
                document.getElementById("TxtStudentImage").removeAttribute("disabled");
                document.getElementById("TxtStudentCategory").removeAttribute("disabled");
                document.getElementById("TCourse").removeAttribute("disabled");
                document.getElementById("TYear").removeAttribute("disabled");
                document.getElementById("TSection").removeAttribute("disabled");
                document.getElementById("TxtLastname").removeAttribute("readonly");
                document.getElementById("TxtFirstname").removeAttribute("readonly");
                document.getElementById("TxtMiddlename").removeAttribute("readonly");
                document.getElementById("TxtExtension").removeAttribute("readonly");
                document.getElementById("TxtBirthdate").removeAttribute("readonly");
                if(TempSex == "male"){
                    $('#RadMale').prop('checked', false);
                }else{
                    $('#RadFemale').prop('checked', false);
                }
                document.getElementById("RadMale").removeAttribute("disabled");
                document.getElementById("RadFemale").removeAttribute("disabled");
                document.getElementById("TxtAddress").removeAttribute("readonly");
                document.getElementById("TxtStudentContactNumber").removeAttribute("readonly");
                if(TempGuardianParent == "guardian"){
                    $('#RadGuardian').prop('checked', false);
                }else if(TempGuardianParent == "parent"){
                    $('#RadParent').prop('checked', false);
                }
                document.getElementById("RadGuardian").removeAttribute("disabled");
                document.getElementById("RadNone").removeAttribute("disabled");
                document.getElementById("RadParent").removeAttribute("disabled");
                document.getElementById("TGPCategory").removeAttribute("disabled");
                document.getElementById("TxtContactPerson").removeAttribute("readonly");
                document.getElementById("TxtPGContactNumber").removeAttribute("readonly");
                if(TempGuardianParent1 == "guardian"){
                    $('#RadGuardian1').prop('checked', false);
                }else if(TempGuardianParent1 == "parent"){
                    $('#RadParent1').prop('checked', false);
                }
                document.getElementById("RadNone1").removeAttribute("disabled");
                document.getElementById("RadGuardian1").removeAttribute("disabled");
                document.getElementById("RadParent1").removeAttribute("disabled");
                document.getElementById("TGPCategory1").removeAttribute("disabled");
                document.getElementById("TxtContactPerson1").removeAttribute("readonly");
                document.getElementById("TxtPGContactNumber1").removeAttribute("readonly");
                document.getElementById("TxtDate").removeAttribute("readonly");
                document.getElementById("TxtTime").removeAttribute("readonly");
                document.getElementById("TxtLMP").removeAttribute("readonly");
                document.getElementById("TxtPregnancy").removeAttribute("readonly");
                document.getElementById("TxtAllergies").removeAttribute("readonly");
                document.getElementById("TxtSurgeries").removeAttribute("readonly");
                document.getElementById("TxtInjuries").removeAttribute("readonly");
                document.getElementById("TxtIllness").removeAttribute("readonly");
                document.getElementById("TxtSchoolYear").removeAttribute("readonly");
                document.getElementById("TxtHeight").removeAttribute("readonly");
                document.getElementById("TxtWeight").removeAttribute("readonly");
                document.getElementById("TxtBloodPressure").removeAttribute("readonly");
                document.getElementById("TxtTemperature").removeAttribute("readonly");
                document.getElementById("TxtPulseRate").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOS").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOS").removeAttribute("readonly");

                document.getElementById("TAHearingDistance").removeAttribute("readonly");
                document.getElementById("TASpeech").removeAttribute("readonly");
                document.getElementById("TAEyes").removeAttribute("readonly");
                document.getElementById("TAEars").removeAttribute("readonly");
                document.getElementById("TANose").removeAttribute("readonly");
                document.getElementById("TAHead").removeAttribute("readonly");
                document.getElementById("TAAbdomen").removeAttribute("readonly");
                document.getElementById("TAGenitoUrinary").removeAttribute("readonly");
                document.getElementById("TALymphGlands").removeAttribute("readonly");
                document.getElementById("TASkin").removeAttribute("readonly");
                document.getElementById("TAExtremities").removeAttribute("readonly");
                document.getElementById("TADeformities").removeAttribute("readonly");
                document.getElementById("TACavityAndThroat").removeAttribute("readonly");
                document.getElementById("TALungs").removeAttribute("readonly");
                document.getElementById("TAHeart").removeAttribute("readonly");
                document.getElementById("TABreast").removeAttribute("readonly");
                document.getElementById("TARadiologicExams").removeAttribute("readonly");
                document.getElementById("TABloodAnalysis").removeAttribute("readonly");
                document.getElementById("TAUrinalysis").removeAttribute("readonly");
                document.getElementById("TAFecalysis").removeAttribute("readonly");
                document.getElementById("TAPregnancyTest").removeAttribute("readonly");
                document.getElementById("TAHBSAg").removeAttribute("readonly");
                document.getElementById("TxtHearingDistanceOption").removeAttribute("disabled");
                document.getElementById("TxtSpeechOption").removeAttribute("disabled");
                document.getElementById("TxtEyesOption").removeAttribute("disabled");
                document.getElementById("TxtEarsOption").removeAttribute("disabled");
                document.getElementById("TxtNoseOption").removeAttribute("disabled");
                document.getElementById("TxtHeadOption").removeAttribute("disabled");
                document.getElementById("TxtAbdomenOption").removeAttribute("disabled");
                document.getElementById("TxtGenitoUrinaryOption").removeAttribute("disabled");
                document.getElementById("TxtLymphGlandsOption").removeAttribute("disabled");
                document.getElementById("TxtSkinOption").removeAttribute("disabled");
                document.getElementById("TxtExtremitiesOption").removeAttribute("disabled");
                document.getElementById("TxtDeformitiesOption").removeAttribute("disabled");
                document.getElementById("TxtCavityAndThroatOption").removeAttribute("disabled");
                document.getElementById("TxtLungsOption").removeAttribute("disabled");
                document.getElementById("TxtHeartOption").removeAttribute("disabled");
                document.getElementById("TxtBreastOption").removeAttribute("disabled");
                document.getElementById("TxtRadiologicExamsOption").removeAttribute("disabled");
                document.getElementById("TxtBloodAnalysisOption").removeAttribute("disabled");
                document.getElementById("TxtUrinalysisOption").removeAttribute("disabled");
                document.getElementById("TxtFecalysisOption").removeAttribute("disabled");
                document.getElementById("TxtPregnancyTestOption").removeAttribute("disabled");
                document.getElementById("TxtHBSAgOption").removeAttribute("disabled");
                document.getElementById("TxtOthers").removeAttribute("readonly");
                document.getElementById("TxtRecommendation").removeAttribute("readonly");
                document.getElementById("TxtRemarks").removeAttribute("readonly");
                document.getElementById("BtnAdd").removeAttribute("disabled");
                document.getElementById("BtnClear").removeAttribute("disabled");
                document.getElementById("BtnAdd1").removeAttribute("disabled");
                document.getElementById("BtnClear1").removeAttribute("disabled");
                document.getElementById("BtnSave").setAttribute('disabled','disabled');
                document.getElementById("BtnEdit").setAttribute('disabled','disabled');
                document.getElementById("BtnSave1").setAttribute('disabled','disabled');
                document.getElementById("BtnEdit1").setAttribute('disabled','disabled');
                document.getElementById("BtnAdd").style.display = 'flex';
                document.getElementById("BtnAdd1").style.display = 'flex';
                document.getElementById("BtnEdit").style.display = 'none';
                document.getElementById("BtnEdit1").style.display = 'none';
                document.getElementById("BtnPrint").style.display = 'none';
                document.getElementById("BtnPrint1").style.display = 'none';
                document.getElementById("BtnPDF").style.display = 'none';
                document.getElementById("BtnPDF1").style.display = 'none';
                document.getElementById("BtnSave").style.display = 'none';
                document.getElementById("BtnSave1").style.display = 'none';
                document.getElementById("BtnClear").style.display = 'flex';
                document.getElementById("BtnClear1").style.display = 'flex';
            }

            function ageCalculator(){
                var birthDay = document.getElementById("TxtBirthdate").value;
                var DOB = new Date(birthDay);
                var today = new Date();
                var age = today.getTime() - DOB.getTime();
                age = Math.floor(age / (1000 * 60 * 60 * 24 * 365.25));
                // alert(age);
                $('#TxtAge').val(age);

                if (age < 5 || age > 90) {
                    message = "Invalid Age";
                    $.alert({
                        theme: 'modern',
                        content: message,
                        title: '',
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass:'btn-red'
                        }}});
                    $('#TxtAge').val('');
                    $('#TxtBirthdate').val('');
                }
            }

             function checkDate(){
                var currentDate = new Date();
                var dateInput = new Date(document.getElementById('TxtDate').value);

                if(currentDate.getTime() > dateInput) {

                }else{
                    message = "Date input is invalid";
                    $.alert({
                        theme: 'modern',
                        content: message,
                        title: '',
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass:'btn-red'
                        }}});
                    $('#TxtDate').val('');
                }
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

            function clickedNew(){
                removeAttr();
                fetchName();

                document.getElementById('TxtDocumentCode').style.backgroundColor = "white"; 
                document.getElementById('TxtRevisionNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtEffectivity').style.backgroundColor = "white"; 
                document.getElementById('TxtNoLabel').style.backgroundColor = "white"; 

                document.getElementById('addMore').style.display = 'inline-block';

                var img = document.getElementById("IDPic");

                document.getElementById('TxtStudentCategory').style.backgroundColor = "white";    
                document.getElementById('TCourse').style.backgroundColor = "white"; 
                document.getElementById('TYear').style.backgroundColor = "white"; 
                document.getElementById('TSection').style.backgroundColor = "white"; 
                document.getElementById('TxtLastname').style.backgroundColor = "white"; 
                document.getElementById('TxtFirstname').style.backgroundColor = "white"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "white"; 
                document.getElementById('TxtExtension').style.backgroundColor = "white"; 
                document.getElementById('TxtBirthdate').style.backgroundColor = "white"; 
                document.getElementById('TxtAddress').style.backgroundColor = "white";    
                document.getElementById('TxtStudentContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory1').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson1').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber1').style.backgroundColor = "white"; 
                document.getElementById('TxtDate').style.backgroundColor = "white"; 
                document.getElementById('TxtTime').style.backgroundColor = "white";

                document.getElementById('TxtLMP').style.backgroundColor = "white"; 
                document.getElementById('TxtPregnancy').style.backgroundColor = "white"; 
                document.getElementById('TxtAllergies').style.backgroundColor = "white"; 
                document.getElementById('TxtSurgeries').style.backgroundColor = "white"; 
                document.getElementById('TxtInjuries').style.backgroundColor = "white"; 
                document.getElementById('TxtIllness').style.backgroundColor = "white"; 
                document.getElementById('TxtSchoolYear').style.backgroundColor = "white"; 
                document.getElementById('TxtHeight').style.backgroundColor = "white";    
                document.getElementById('TxtWeight').style.backgroundColor = "white"; 
                document.getElementById('TxtBloodPressure').style.backgroundColor = "white"; 
                document.getElementById('TxtTemperature').style.backgroundColor = "white"; 
                document.getElementById('TxtPulseRate').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOS').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOS').style.backgroundColor = "white"; 
                document.getElementById('TxtHearingDistanceOption').style.backgroundColor = "white";    
                document.getElementById('TxtSpeechOption').style.backgroundColor = "white"; 
                document.getElementById('TxtEyesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtEarsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtNoseOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHeadOption').style.backgroundColor = "white"; 
                document.getElementById('TxtAbdomenOption').style.backgroundColor = "white"; 
                document.getElementById('TxtGenitoUrinaryOption').style.backgroundColor = "white"; 
                document.getElementById('TxtLymphGlandsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtSkinOption').style.backgroundColor = "white";    
                document.getElementById('TxtExtremitiesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtDeformitiesOption').style.backgroundColor = "white"; 
                document.getElementById('TxtCavityAndThroatOption').style.backgroundColor = "white"; 
                document.getElementById('TxtLungsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHeartOption').style.backgroundColor = "white"; 
                document.getElementById('TxtBreastOption').style.backgroundColor = "white"; 
                document.getElementById('TxtRadiologicExamsOption').style.backgroundColor = "white"; 
                document.getElementById('TxtBloodAnalysisOption').style.backgroundColor = "white"; 
                document.getElementById('TxtUrinalysisOption').style.backgroundColor = "white";    
                document.getElementById('TxtFecalysisOption').style.backgroundColor = "white"; 
                document.getElementById('TxtPregnancyTestOption').style.backgroundColor = "white"; 
                document.getElementById('TxtHBSAgOption').style.backgroundColor = "white"; 

                document.getElementById('TAHearingDistance').style.backgroundColor = "white";    
                document.getElementById('TASpeech').style.backgroundColor = "white"; 
                document.getElementById('TAEyes').style.backgroundColor = "white"; 
                document.getElementById('TAEars').style.backgroundColor = "white"; 
                document.getElementById('TANose').style.backgroundColor = "white"; 
                document.getElementById('TAHead').style.backgroundColor = "white"; 
                document.getElementById('TAAbdomen').style.backgroundColor = "white"; 
                document.getElementById('TAGenitoUrinary').style.backgroundColor = "white"; 
                document.getElementById('TALymphGlands').style.backgroundColor = "white"; 
                document.getElementById('TASkin').style.backgroundColor = "white";    
                document.getElementById('TAExtremities').style.backgroundColor = "white"; 
                document.getElementById('TADeformities').style.backgroundColor = "white"; 
                document.getElementById('TACavityAndThroat').style.backgroundColor = "white"; 
                document.getElementById('TALungs').style.backgroundColor = "white"; 
                document.getElementById('TAHeart').style.backgroundColor = "white"; 
                document.getElementById('TABreast').style.backgroundColor = "white"; 
                document.getElementById('TARadiologicExams').style.backgroundColor = "white"; 
                document.getElementById('TABloodAnalysis').style.backgroundColor = "white"; 
                document.getElementById('TAUrinalysis').style.backgroundColor = "white";    
                document.getElementById('TAFecalysis').style.backgroundColor = "white"; 
                document.getElementById('TAPregnancyTest').style.backgroundColor = "white"; 
                document.getElementById('TAHBSAg').style.backgroundColor = "white"; 
                document.getElementById('TxtOthers').style.backgroundColor = "white"; 
                document.getElementById('TxtRecommendation').style.backgroundColor = "white"; 
                document.getElementById('TxtRemarks').style.backgroundColor = "white"; 

                $('#TxtDocumentCode').val('');
                $('#TxtRevisionNumber').val('');
                $('#TxtEffectivity').val('');
                $('#TxtNoLabel').val('');
                img.src = "../images/id picture.webp";
                $('#RadNew').prop('checked', true);
                $('#RadOld').prop('checked', false);
                $('#TxtStudentImage').val('');
                $('#TxtStudentCategory').val('');
                $('#TCourse').val('');
                $('#TYear').val('');
                $('#TSection').val('');
                $('#TxtLastname').val('');
                $('#TxtFirstname').val('');
                $('#TxtMiddlename').val('');
                $('#TxtExtension').val('');
                $('#TxtAge').val('');
                $('#TxtBirthdate').val('');
                $('#RadMale').prop('checked', false);
                $('#RadFemale').prop('checked', false);
                $('#TxtAddress').val('');
                $('#TxtStudentContactNumber').val("+639");
                $('#RadParent').prop('checked', false);
                $('#RadGuardian').prop('checked', false);
                $('#RadGuardian1').prop('checked', false);
                $('#TGPCategory').val('');
                $('#TxtContactPerson').val('');
                $('#TxtPGContactNumber').val("+639");
                $('#RadParent1').prop('checked', false);
                $('#TGPCategory1').val('');
                $('#TxtContactPerson1').val('');
                $('#TxtPGContactNumber1').val("+639");

                document.getElementById('TxtStudentFullName').innerHTML = 'Full Name:';
                document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSFullName').innerHTML = 'Charted By:';

                //auto date time set

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                var thisDay = yyyy + '-' + mm + '-' + dd;

                var hh = today.getHours();
                var mm = today.getMinutes();

                var thisTime = hh + ":" + mm;

                $('#TxtDate').val(thisDay);
                $('#TxtTime').val(thisTime);
                $('#TxtLMP').val('');
                $('#TxtPregnancy').val('');
                $('#TxtAllergies').val('');
                $('#TxtSurgeries').val('');
                $('#TxtInjuries').val('');
                $('#TxtIllness').val('');
                $('#TxtSchoolYear').val('');
                $('#TxtHeight').val('');
                $('#TxtWeight').val('');
                $('#TxtBMI').val('');
                $('#TxtBloodPressure').val('');
                $('#TxtTemperature').val('');
                $('#TxtPulseRate').val('');
                $('#TxtVisionWithoutGlassesOD').val('');
                $('#TxtVisionWithoutGlassesOS').val('');
                $('#TxtVisionWithGlassesOD').val('');
                $('#TxtVisionWithGlassesOS').val('');
                $('#TxtHearingDistanceOption').val('');
                $('#TxtSpeechOption').val('');
                $('#TxtEyesOption').val('');
                $('#TxtEarsOption').val('');
                $('#TxtNoseOption').val('');
                $('#TxtHeadOption').val('');
                $('#TxtAbdomenOption').val('');
                $('#TxtGenitoUrinaryOption').val('');
                $('#TxtLymphGlandsOption').val('');
                $('#TxtSkinOption').val('');
                $('#TxtExtremitiesOption').val('');
                $('#TxtDeformitiesOption').val('');
                $('#TxtCavityAndThroatOption').val('');
                $('#TxtLungsOption').val('');
                $('#TxtHeartOption').val('');
                $('#TxtBreastOption').val('');
                $('#TxtRadiologicExamsOption').val('');
                $('#TxtBloodAnalysisOption').val('');
                $('#TxtUrinalysisOption').val('');
                $('#TxtFecalysisOption').val('');
                $('#TxtPregnancyTestOption').val('');
                $('#TxtHBSAgOption').val('');

                $('#TAHearingDistance').val('');
                $('#TASpeech').val('');
                $('#TAEyes').val('');
                $('#TAEars').val('');
                $('#TANose').val('');
                $('#TAHead').val('');
                $('#TAAbdomen').val('');
                $('#TAGenitoUrinary').val('');
                $('#TALymphGlands').val('');
                $('#TASkin').val('');
                $('#TAExtremities').val('');
                $('#TADeformities').val('');
                $('#TACavityAndThroat').val('');
                $('#TALungs').val('');
                $('#TAHeart').val('');
                $('#TABreast').val('');
                $('#TARadiologicExams').val('');
                $('#TABloodAnalysis').val('');
                $('#TAUrinalysis').val('');
                $('#TAFecalysis').val('');
                $('#TAPregnancyTest').val('');
                $('#TAHBSAg').val('');

                $('#TxtOthers').val('');
                $('#TxtRemarks').val('');
                $('#TxtRecommendation').val('');
               
                document.getElementById('MedicalStaffInfo').style.display = 'none';
                document.getElementById('ExaminedBy').style.display = 'none';
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

            function checkArchive(){
                if (getType == "viewArchivedRecord"){
                    document.getElementById('BtnPrint').style.display = 'none';
                    document.getElementById('BtnPDF').style.display = 'none';
                    document.getElementById('BtnAdd').style.display = 'none';
                    document.getElementById('BtnSave').style.display = 'none';
                    document.getElementById('BtnClear').style.display = 'none';
                    document.getElementById('BtnEdit').style.display = 'none';

                    document.getElementById('BtnPrint1').style.display = 'none';
                    document.getElementById('BtnPDF1').style.display = 'none';
                    document.getElementById('BtnAdd1').style.display = 'none';
                    document.getElementById('BtnSave1').style.display = 'none';
                    document.getElementById('BtnClear1').style.display = 'none';
                    document.getElementById('BtnEdit1').style.display = 'none';

                    document.getElementById("RadNew").setAttribute("disabled","disabled");
                    document.getElementById("RadOld").setAttribute("disabled","disabled");
                    document.getElementById("TxtStudentIDNumber").setAttribute('readonly','readonly');
                    styleInput("TxtStudentIDNumber");
                }
            }

            function passIDPHP(x){
                var form_data = new FormData();
                var Num = x;
                form_data.append("idnumber", Num);
                form_data.append("type", getType);

                $.ajax(
                { 
                    url:"../php/FetchRecords.php",
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
                            var DocumentCode = $(this).attr('DocumentCode');
                            var RevisionNumber = $(this).attr('RevisionNumber');
                            var Effectivity = $(this).attr('Effectivity');
                            var NoLabel = $(this).attr('NoLabel');
                            var StudentImage = $(this).attr('StudentImage');
                            var StudentIDNumber = $(this).attr('StudentIDNumber');
                            var StudentCategory = $(this).attr('StudentCategory');
                            var Course = $(this).attr('Course');
                            var Year = $(this).attr('Year');
                            var Section = $(this).attr('Section');
                            var Lastname = $(this).attr('Lastname');
                            var Firstname = $(this).attr('Firstname');
                            var Middlename = $(this).attr('Middlename');
                            var Extension = $(this).attr('Extension');
                            var Age = $(this).attr('Age');
                            var Birthdate = $(this).attr('Birthdate');
                            var Sex = $(this).attr('Sex');
                            var Address = $(this).attr('Address');
                            var StudentContactNumber = $(this).attr('StudentContactNumber');
                            var GuardianParent = $(this).attr('GuardianParent');
                            var GPCategory = $(this).attr('GPCategory');
                            var ContactPerson = $(this).attr('ContactPerson');
                            var PGContactNumber = $(this).attr('PGContactNumber');
                            var GuardianParent1 = $(this).attr('GuardianParent1');
                            var GPCategory1 = $(this).attr('GPCategory1');
                            var ContactPerson1 = $(this).attr('ContactPerson1');
                            var PGContactNumber1 = $(this).attr('PGContactNumber1');
                            var Date = $(this).attr('Date');
                            var Time = $(this).attr('Time');
                            var StaffIDNumber = $(this).attr('StaffIDNumber');
                            var StaffLastname = $(this).attr('StaffLastname');
                            var StaffFirstname = $(this).attr('StaffFirstname');
                            var StaffMiddlename = $(this).attr('StaffMiddlename');
                            var StaffExtension = $(this).attr('StaffExtension');
                            var LMP = $(this).attr('LMP');
                            var Pregnancy = $(this).attr('Pregnancy');
                            var Allergies = $(this).attr('Allergies');
                            var Surgeries = $(this).attr('Surgeries');
                            var Injuries = $(this).attr('Injuries');
                            var Illness = $(this).attr('Illness');
                            var SchoolYear = $(this).attr('SchoolYear');
                            var Height = $(this).attr('Height');
                            var Weight = $(this).attr('Weight');
                            var BMI = $(this).attr('BMI');
                            var BloodPressure = $(this).attr('BloodPressure');
                            var Temperature = $(this).attr('Temperature');
                            var PulseRate = $(this).attr('PulseRate');
                            var VisionWithoutGlassesOD = $(this).attr('VisionWithoutGlassesOD');
                            var VisionWithoutGlassesOS = $(this).attr('VisionWithoutGlassesOS');
                            var VisionWithGlassesOD = $(this).attr('VisionWithGlassesOD');
                            var VisionWithGlassesOS = $(this).attr('VisionWithGlassesOS');
                            var HearingDistanceOption = $(this).attr('HearingDistanceOption');
                            var SpeechOption = $(this).attr('SpeechOption');
                            var EyesOption = $(this).attr('EyesOption');
                            var EarsOption = $(this).attr('EarsOption');
                            var NoseOption = $(this).attr('NoseOption');
                            var HeadOption = $(this).attr('HeadOption');
                            var AbdomenOption = $(this).attr('AbdomenOption');
                            var GenitoUrinaryOption = $(this).attr('GenitoUrinaryOption');
                            var LymphGlandsOption = $(this).attr('LymphGlandsOption');
                            var SkinOption = $(this).attr('SkinOption');
                            var ExtremitiesOption = $(this).attr('ExtremitiesOption');
                            var DeformitiesOption = $(this).attr('DeformitiesOption');
                            var CavityAndThroatOption = $(this).attr('CavityAndThroatOption');
                            var LungsOption = $(this).attr('LungsOption');
                            var HeartOption = $(this).attr('HeartOption');
                            var BreastOption = $(this).attr('BreastOption');
                            var RadiologicExamsOption = $(this).attr('RadiologicExamsOption');
                            var BloodAnalysisOption = $(this).attr('BloodAnalysisOption');
                            var UrinalysisOption = $(this).attr('UrinalysisOption');
                            var FecalysisOption = $(this).attr('FecalysisOption');
                            var PregnancyTestOption = $(this).attr('PregnancyTestOption');
                            var HBSAgOption = $(this).attr('HBSAgOption');
                            var TAHearingDistance = $(this).attr('TAHearingDistance');
                            var TASpeech = $(this).attr('TASpeech');
                            var TAEyes = $(this).attr('TAEyes');
                            var TAEars = $(this).attr('TAEars');
                            var TANose = $(this).attr('TANose');
                            var TAHead = $(this).attr('TAHead');
                            var TAAbdomen = $(this).attr('TAAbdomen');
                            var TAGenitoUrinary = $(this).attr('TAGenitoUrinary');
                            var TALymphGlands = $(this).attr('TALymphGlands');
                            var TASkin = $(this).attr('TASkin');
                            var TAExtremities = $(this).attr('TAExtremities');
                            var TADeformities = $(this).attr('TADeformities');
                            var TACavityAndThroat = $(this).attr('TACavityAndThroat');
                            var TALungs = $(this).attr('TALungs');
                            var TAHeart = $(this).attr('TAHeart');
                            var TABreast = $(this).attr('TABreast');
                            var TARadiologicExams = $(this).attr('TARadiologicExams');
                            var TABloodAnalysis = $(this).attr('TABloodAnalysis');
                            var TAUrinalysis = $(this).attr('TAUrinalysis');
                            var TAFecalysis = $(this).attr('TAFecalysis');
                            var TAPregnancyTest = $(this).attr('TAPregnancyTest');
                            var TAHBSAg = $(this).attr('TAHBSAg');
                            var Others = $(this).attr('Others');
                            var Remarks = $(this).attr('Remarks');
                            var Recommendation = $(this).attr('Recommendation');

                            TempSex = Sex;
                            TempGuardianParent = GuardianParent;
                            TempGuardianParent1 = GuardianParent1;

                            

                            if(error == "1"){
                                    clearPersonalMedical();
                                    $('#RadGuardian').prop('checked', true);
                                    $('#RadGuardian1').prop('checked', true);
                                    document.getElementById('addMore').style.display = 'none';
                                    document.getElementById('addMoreForm').style.display = 'none';
                                    document.getElementById('addMoreForm1').style.display = 'none';
                                    document.getElementById('addMoreForm2').style.display = 'none';
                            }else{
                                $('#TxtDocumentCode').val(DocumentCode);
                                $('#TxtRevisionNumber').val(RevisionNumber);
                                $('#TxtEffectivity').val(Effectivity);
                                $('#TxtNoLabel').val(NoLabel);
                                document.getElementById("RadOld").setAttribute('checked','checked');
                                if(StudentImage.length == 18){
                                    document.getElementById("IDPic").src = "../images/id picture.webp";
                                }else{
                                    document.getElementById("IDPic").src = StudentImage;
                                }
                                    $('#TxtStudentIDNumber').val(StudentIDNumber);
                                    if(StudentCategory == "elementary"){
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("elementary").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'none';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById("YO1").setAttribute('value','1');
                                        document.getElementById("YO2").setAttribute('value','2');
                                        document.getElementById("YO3").setAttribute('value','3');
                                        document.getElementById("YO4").setAttribute('value','4');
                                        document.getElementById("YO5").setAttribute('value','5');
                                        document.getElementById("YO6").setAttribute('value','6'); 

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");
                                    }else if(StudentCategory == "highschool"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("highschool").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'none';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById("YO1").setAttribute('value','7');
                                        document.getElementById("YO2").setAttribute('value','8');
                                        document.getElementById("YO3").setAttribute('value','9');
                                        document.getElementById("YO4").setAttribute('value','10');
                                        document.getElementById("YO5").removeAttribute("value");
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");
                                    }else if(StudentCategory == "senior highschool"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("senior").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'block';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById('CS').innerHTML = 'Strand';
                                        document.getElementById("YO1").setAttribute('value','11');
                                        document.getElementById("YO2").setAttribute('value','12');
                                        document.getElementById("YO3").removeAttribute("value");
                                        document.getElementById("YO4").removeAttribute("value");
                                        document.getElementById("YO5").removeAttribute("value");
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");

                                        document.getElementById("CO1").setAttribute('value','Science, Technology, Engineering, and Mathematics (STEM)');
                                        document.getElementById("CO2").removeAttribute("value");
                                        document.getElementById("CO3").removeAttribute("value");
                                        document.getElementById("CO4").removeAttribute("value");
                                        document.getElementById("CO5").removeAttribute("value");
                                        document.getElementById("CO6").removeAttribute("value");
                                        document.getElementById("CO7").removeAttribute("value");
                                        document.getElementById("CO8").removeAttribute("value");
                                        document.getElementById("CO9").removeAttribute("value");
                                        document.getElementById("CO10").removeAttribute("value");
                                        document.getElementById("CO11").removeAttribute("value");
                                        document.getElementById("CO12").removeAttribute("value");
                                        document.getElementById("CO13").removeAttribute("value");
                                        document.getElementById("CO14").removeAttribute("value");
                                        document.getElementById("CO15").removeAttribute("value");
                                        document.getElementById("CO16").removeAttribute("value");
                                        document.getElementById("CO17").removeAttribute("value");
                                        document.getElementById("CO18").removeAttribute("value");
                                        document.getElementById("CO19").removeAttribute("value");
                                        document.getElementById("CO20").removeAttribute("value");
                                        document.getElementById("CO21").removeAttribute("value");
                                        document.getElementById("CO22").removeAttribute("value");
                                        document.getElementById("CO23").removeAttribute("value");
                                        document.getElementById("CO24").removeAttribute("value");
                                        document.getElementById("CO25").removeAttribute("value");
                                        document.getElementById("CO26").removeAttribute("value");
                                        document.getElementById("CO27").removeAttribute("value");
                                        document.getElementById("CO28").removeAttribute("value");
                                        document.getElementById("CO29").removeAttribute("value");
                                        document.getElementById("CO30").removeAttribute("value");
                                        document.getElementById("CO31").removeAttribute("value");
                                        document.getElementById("CO32").removeAttribute("value");
                                    }else if(StudentCategory == "college"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("college").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'block';
                                        document.getElementById('YR').innerHTML = 'Year';
                                        document.getElementById('CS').innerHTML = 'Course';
                                        document.getElementById("YO1").setAttribute('value','1');
                                        document.getElementById("YO2").setAttribute('value','2');
                                        document.getElementById("YO3").setAttribute('value','3');
                                        document.getElementById("YO4").setAttribute('value','4');
                                        document.getElementById("YO5").setAttribute('value','Irregular');
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").setAttribute('value','A');
                                        document.getElementById("SO2").setAttribute('value','B');
                                        document.getElementById("SO3").setAttribute('value','C');
                                        document.getElementById("SO4").setAttribute('value','D');
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");

                                        document.getElementById("CO1").setAttribute('value','Bachelor of Arts in Communication (BA Com)');
                                        document.getElementById("CO2").setAttribute('value','Bachelor of Arts in English Language (BA EL)');
                                        document.getElementById("CO3").setAttribute('value','Bachelor of Arts in Filipino Language (BA FL)');
                                        document.getElementById("CO4").setAttribute('value','Bachelor of Early Childhood Education (BECEd)');
                                        document.getElementById("CO5").setAttribute('value','Bachelor of Elementary Education (BEEd)');
                                        document.getElementById("CO6").setAttribute('value','Bachelor of Library and Information Science (BLIS)');
                                        document.getElementById("CO7").setAttribute('value','Bachelor of Physical Education (BPEd)');
                                        document.getElementById("CO8").setAttribute('value','Bachelor of Public Administration (BPA)');
                                        document.getElementById("CO9").setAttribute('value','Bachelor of Science in Agricultural and Biosystems Engineering (BSABE)');
                                        document.getElementById("CO10").setAttribute('value','Bachelor of Science in Agriculture (BSA)');
                                        document.getElementById("CO11").setAttribute('value','Bachelor of Science in Biology (BS Bio)');
                                        document.getElementById("CO12").setAttribute('value','Bachelor of Science in Chemistry (BS Chem)');
                                        document.getElementById("CO13").setAttribute('value','Bachelor of Science in Development Communication (BSDC)');
                                        document.getElementById("CO14").setAttribute('value','Bachelor of Science in Entrepreneurship (BS Entrep)');
                                        document.getElementById("CO15").setAttribute('value','Bachelor of Science in Environmental Science (BSES)');
                                        document.getElementById("CO16").setAttribute('value','Bachelor of Science in Exercise and Sports Sciences (BSESS)');
                                        document.getElementById("CO17").setAttribute('value','Bachelor of Science in Forestry (BSF)');
                                        document.getElementById("CO18").setAttribute('value','Bachelor of Science in Hospitality Management (BSHM)');
                                        document.getElementById("CO19").setAttribute('value','Bachelor of Science in Information Technology (BSIT)');
                                        document.getElementById("CO20").setAttribute('value','Bachelor of Science in Mathematics (BS Math)');
                                        document.getElementById("CO21").setAttribute('value','Bachelor of Science in Nursing (BSN)');
                                        document.getElementById("CO22").setAttribute('value','Bachelor of Science in Nutrition and Dietetics (BSND)');
                                        document.getElementById("CO23").setAttribute('value','Bachelor of Science in Statistics (BSS)');
                                        document.getElementById("CO24").setAttribute('value','Bachelor of Secondary Education major in English (BSEd-English)');
                                        document.getElementById("CO25").setAttribute('value','Bachelor of Secondary Education major in Filipino (BSEd-Filipino)');
                                        document.getElementById("CO26").setAttribute('value','Bachelor of Secondary Education major in Mathematics (BSEd-Math)');
                                        document.getElementById("CO27").setAttribute('value','Bachelor of Secondary Education major in Science (BSEd-Science)');
                                        document.getElementById("CO28").setAttribute('value','Bachelor of Secondary Education major in Social Studies (BSEd-SSt)');
                                        document.getElementById("CO29").setAttribute('value','Bachelor of Secondary Education major in Values Education (BSEd-VE)');
                                        document.getElementById("CO30").setAttribute('value','Bachelor of Technology and Livelihood Education-Home Economics (BTLEd-HE)');
                                        document.getElementById("CO31").setAttribute('value','Doctor of Veterinary Medicine (DVM)');
                                        document.getElementById("CO32").setAttribute('value','Bachelor of Science in Agribusiness (BSAB)');
                                    } 
                                    $('#TCourse').val(Course);
                                    $('#TYear').val(Year);
                                    $('#TSection').val(Section);
                                    $('#TxtLastname').val(Lastname);
                                    $('#TxtFirstname').val(Firstname);
                                    $('#TxtMiddlename').val(Middlename);
                                    $('#TxtExtension').val(Extension);
                                    $('#TxtAge').val(Age);
                                    $('#TxtBirthdate').val(Birthdate);
                                    if(Sex == "male"){
                                        $('#RadMale').prop('checked', true);
                                    }else{
                                        $('#RadFemale').prop('checked', true);
                                    }
                                    $('#TxtAddress').val(Address);
                                    $('#TxtStudentContactNumber').val(StudentContactNumber);
                                    if(GPCategory != '' || ContactPerson != ''){
                                        if(GuardianParent == "guardian"){
                                            $('#RadGuardian').prop('checked', true);
                                        }else if(GuardianParent == "parent"){
                                            $('#RadParent').prop('checked', true);
                                        }else{
                                            $('#RadNone').prop('checked', true);
                                        }
                                        $('#TGPCategory').val(GPCategory);
                                        $('#TxtContactPerson').val(ContactPerson);
                                        $('#TxtPGContactNumber').val(PGContactNumber);
                                    }else{
                                        $('#RadNone').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm2').style.display = 'none';
                                    }
                                    $('#TxtDate').val(Date);
                                    $('#TxtTime').val(Time);
                                    document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number: ' + StaffIDNumber;
                                    document.getElementById('TxtMSFullName').innerHTML = 'Charted By: ' + (StaffLastname + ", " + StaffFirstname + " " + StaffMiddlename + " " + StaffExtension).toUpperCase();
                                    $('#TxtLMP').val(LMP);
                                    $('#TxtPregnancy').val(Pregnancy);
                                    $('#TxtAllergies').val(Allergies);
                                    $('#TxtSurgeries').val(Surgeries);
                                    $('#TxtInjuries').val(Injuries);
                                    $('#TxtIllness').val(Illness);
                                    $('#TxtSchoolYear').val(SchoolYear);
                                    $('#TxtHeight').val(Height);
                                    $('#TxtWeight').val(Weight);
                                    $('#TxtBMI').val(BMI);
                                    $('#TxtBloodPressure').val(BloodPressure);
                                    $('#TxtTemperature').val(Temperature);
                                    $('#TxtPulseRate').val(PulseRate);
                                    $('#TxtVisionWithoutGlassesOD').val(VisionWithoutGlassesOD);
                                    $('#TxtVisionWithoutGlassesOS').val(VisionWithoutGlassesOS);
                                    $('#TxtVisionWithGlassesOD').val(VisionWithGlassesOD);
                                    $('#TxtVisionWithGlassesOS').val(VisionWithGlassesOS);

                                    if(HearingDistanceOption == "with findings"){
                                        document.getElementById("unremarkableHD").removeAttribute("selected");
                                        document.getElementById("wFindingsHD").setAttribute('selected','selected');
                                        document.getElementById('TAHearingDistance').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableHD").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHD").removeAttribute("selected");
                                        document.getElementById('TAHearingDistance').style.display = "none"; 
                                    }
                                    if(SpeechOption == "with findings"){
                                        document.getElementById("unremarkableSp").removeAttribute("selected");
                                        document.getElementById("wFindingsSp").setAttribute('selected','selected');
                                        document.getElementById('TASpeech').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableSp").setAttribute('selected','selected');
                                        document.getElementById("wFindingsSp").removeAttribute("selected");
                                        document.getElementById('TASpeech').style.display = "none"; 
                                    }
                                    if(EyesOption == "with findings"){
                                        document.getElementById("unremarkableEy").removeAttribute("selected");
                                        document.getElementById("wFindingsEy").setAttribute('selected','selected');
                                        document.getElementById('TAEyes').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableEy").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEy").removeAttribute("selected");
                                        document.getElementById('TAEyes').style.display = "none"; 
                                    }
                                    if(EarsOption == "with findings"){
                                        document.getElementById("unremarkableEa").removeAttribute("selected");
                                        document.getElementById("wFindingsEa").setAttribute('selected','selected');
                                        document.getElementById('TAEars').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableEa").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEa").removeAttribute("selected");
                                        document.getElementById('TAEars').style.display = "none"; 
                                    }
                                    if(NoseOption == "with findings"){
                                        document.getElementById("unremarkableNo").removeAttribute("selected");
                                        document.getElementById("wFindingsNo").setAttribute('selected','selected');
                                        document.getElementById('TANose').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableNo").setAttribute('selected','selected');
                                        document.getElementById("wFindingsNo").removeAttribute("selected");
                                        document.getElementById('TANose').style.display = "none"; 
                                    }
                                    if(HeadOption == "with findings"){
                                        document.getElementById("unremarkableHe").removeAttribute("selected");
                                        document.getElementById("wFindingsHe").setAttribute('selected','selected');
                                        document.getElementById('TAHead').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableHe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHe").removeAttribute("selected");
                                        document.getElementById('TAHead').style.display = "none"; 
                                    }
                                    if(AbdomenOption == "with findings"){
                                        document.getElementById("unremarkableAb").removeAttribute("selected");
                                        document.getElementById("wFindingsAb").setAttribute('selected','selected');
                                        document.getElementById('TAAbdomen').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableAb").setAttribute('selected','selected');
                                        document.getElementById("wFindingsAb").removeAttribute("selected");
                                        document.getElementById('TAAbdomen').style.display = "none"; 
                                    }
                                    if(GenitoUrinaryOption == "with findings"){
                                        document.getElementById("unremarkableGU").removeAttribute("selected");
                                        document.getElementById("wFindingsGU").setAttribute('selected','selected');
                                        document.getElementById('TAGenitoUrinary').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableGU").setAttribute('selected','selected');
                                        document.getElementById("wFindingsGU").removeAttribute("selected");
                                        document.getElementById('TAGenitoUrinary').style.display = "none"; 
                                    }
                                    if(LymphGlandsOption == "with findings"){
                                        document.getElementById("unremarkableLG").removeAttribute("selected");
                                        document.getElementById("wFindingsLG").setAttribute('selected','selected');
                                        document.getElementById('TALymphGlands').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableLG").setAttribute('selected','selected');
                                        document.getElementById("wFindingsLG").removeAttribute("selected");
                                        document.getElementById('TALymphGlands').style.display = "none"; 
                                    }
                                    if(SkinOption == "with findings"){
                                        document.getElementById("unremarkableSk").removeAttribute("selected");
                                        document.getElementById("wFindingsSk").setAttribute('selected','selected');
                                        document.getElementById('TASkin').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableSk").setAttribute('selected','selected');
                                        document.getElementById("wFindingsSk").removeAttribute("selected");
                                        document.getElementById('TASkin').style.display = "none"; 
                                    }
                                    if(ExtremitiesOption == "with findings"){
                                        document.getElementById("unremarkableEx").removeAttribute("selected");
                                        document.getElementById("wFindingsEx").setAttribute('selected','selected');
                                        document.getElementById('TAExtremities').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableEx").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEx").removeAttribute("selected");
                                        document.getElementById('TAExtremities').style.display = "none"; 
                                    }
                                    if(DeformitiesOption == "with findings"){
                                        document.getElementById("unremarkableDe").removeAttribute("selected");
                                        document.getElementById("wFindingsDe").setAttribute('selected','selected');
                                        document.getElementById('TADeformities').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableDe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsDe").removeAttribute("selected");
                                        document.getElementById('TADeformities').style.display = "none"; 
                                    }
                                    if(CavityAndThroatOption == "with findings"){
                                        document.getElementById("unremarkableCT").removeAttribute("selected");
                                        document.getElementById("wFindingsCT").setAttribute('selected','selected');
                                        document.getElementById('TACavityAndThroat').style.display = "block";
                                    }else{
                                        document.getElementById("unremarkableCT").setAttribute('selected','selected');
                                        document.getElementById("wFindingsCT").removeAttribute("selected");
                                        document.getElementById('TACavityAndThroat').style.display = "none";
                                    }
                                    if(LungsOption == "with findings"){
                                        document.getElementById("unremarkableLu").removeAttribute("selected");
                                        document.getElementById("wFindingsLu").setAttribute('selected','selected');
                                        document.getElementById('TALungs').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableLu").setAttribute('selected','selected');
                                        document.getElementById("wFindingsLu").removeAttribute("selected");
                                        document.getElementById('TALungs').style.display = "none"; 
                                    }
                                    if(HeartOption == "with findings"){
                                        document.getElementById("unremarkableHea").removeAttribute("selected");
                                        document.getElementById("wFindingsHea").setAttribute('selected','selected');
                                        document.getElementById('TAHeart').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableHea").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHea").removeAttribute("selected");
                                        document.getElementById('TAHeart').style.display = "none"; 
                                    }
                                    if(BreastOption == "with findings"){
                                        document.getElementById("unremarkableBr").removeAttribute("selected");
                                        document.getElementById("wFindingsBr").setAttribute('selected','selected');
                                        document.getElementById('TABreast').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableBr").setAttribute('selected','selected');
                                        document.getElementById("wFindingsBr").removeAttribute("selected");
                                        document.getElementById('TABreast').style.display = "none"; 
                                    }
                                    if(RadiologicExamsOption == "with findings"){
                                        document.getElementById("unremarkableRE").removeAttribute("selected");
                                        document.getElementById("wFindingsRE").setAttribute('selected','selected');
                                        document.getElementById('TARadiologicExams').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableRE").setAttribute('selected','selected');
                                        document.getElementById("wFindingsRE").removeAttribute("selected");
                                        document.getElementById('TARadiologicExams').style.display = "none"; 
                                    }
                                    if(BloodAnalysisOption == "with findings"){
                                        document.getElementById("unremarkableBA").removeAttribute("selected");
                                        document.getElementById("wFindingsBA").setAttribute('selected','selected');
                                        document.getElementById('TABloodAnalysis').style.display = "block";
                                    }else{
                                        document.getElementById("unremarkableBA").setAttribute('selected','selected');
                                        document.getElementById("wFindingsBA").removeAttribute("selected");
                                        document.getElementById('TABloodAnalysis').style.display = "none";
                                    }
                                    if(UrinalysisOption == "with findings"){
                                        document.getElementById("unremarkableUr").removeAttribute("selected");
                                        document.getElementById("wFindingsUr").setAttribute('selected','selected');
                                        document.getElementById('TAUrinalysis').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableUr").setAttribute('selected','selected');
                                        document.getElementById("wFindingsUr").removeAttribute("selected");
                                        document.getElementById('TAUrinalysis').style.display = "none"; 
                                    }
                                    if(FecalysisOption == "with findings"){
                                        document.getElementById("unremarkableFe").removeAttribute("selected");
                                        document.getElementById("wFindingsFe").setAttribute('selected','selected');
                                        document.getElementById('TAFecalysis').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkableFe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsFe").removeAttribute("selected");
                                        document.getElementById('TAFecalysis').style.display = "none"; 
                                    }
                                    if(PregnancyTestOption == "with findings"){
                                        document.getElementById("unremarkablePT").removeAttribute("selected");
                                        document.getElementById("wFindingsPT").setAttribute('selected','selected');
                                        document.getElementById('TAPregnancyTest').style.display = "block"; 
                                    }else{
                                        document.getElementById("unremarkablePT").setAttribute('selected','selected');
                                        document.getElementById("wFindingsPT").removeAttribute("selected");
                                        document.getElementById('TAPregnancyTest').style.display = "none"; 
                                    }
                                    if(HBSAgOption == "with findings"){
                                        document.getElementById("unremarkableHB").removeAttribute("selected");
                                        document.getElementById("wFindingsHB").setAttribute('selected','selected');
                                        document.getElementById("TAHBSAg").style.display = "block";
                                    }else{
                                        document.getElementById("unremarkableHB").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHB").removeAttribute("selected");
                                        document.getElementById("TAHBSAg").style.display = "none";
                                    }

                                    $('#TAHearingDistance').val(TAHearingDistance);
                                    $('#TASpeech').val(TASpeech);
                                    $('#TAEyes').val(TAEyes);
                                    $('#TAEars').val(TAEars);
                                    $('#TANose').val(TANose);
                                    $('#TAHead').val(TAHead);
                                    $('#TAAbdomen').val(TAAbdomen);
                                    $('#TAGenitoUrinary').val(TAGenitoUrinary);
                                    $('#TALymphGlands').val(TALymphGlands);
                                    $('#TASkin').val(TASkin);
                                    $('#TAExtremities').val(TAExtremities);
                                    $('#TADeformities').val(TADeformities);
                                    $('#TACavityAndThroat').val(TACavityAndThroat);
                                    $('#TALungs').val(TALungs);
                                    $('#TAHeart').val(TAHeart);
                                    $('#TABreast').val(TABreast);
                                    $('#TARadiologicExams').val(TARadiologicExams);
                                    $('#TABloodAnalysis').val(TABloodAnalysis);
                                    $('#TAUrinalysis').val(TAUrinalysis);
                                    $('#TAFecalysis').val(TAFecalysis);
                                    $('#TAPregnancyTest').val(TAPregnancyTest);
                                    $('#TAHBSAg').val(TAHBSAg);

                                    $('#TxtOthers').val(Others);
                                    $('#TxtRemarks').val(Remarks);
                                    $('#TxtRecommendation').val(Recommendation);

                                    if(GPCategory1 != '' || ContactPerson1 != ''){
                                        showAddMore();
                                        if(GuardianParent1 == "guardian"){
                                            $('#RadGuardian1').prop('checked', true);
                                        }else if(GuardianParent1 == "parent"){
                                            $('#RadParent1').prop('checked', true);
                                        }else{
                                            $('#RadNone1').prop('checked', true);
                                        }
                                        $('#TGPCategory1').val(GPCategory1);
                                        $('#TxtContactPerson1').val(ContactPerson1);
                                        $('#TxtPGContactNumber1').val(PGContactNumber1);
                                    }else{
                                        $('#RadNone1').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm2').style.display = 'none';
                                    }

                                  
                            }
                                    setAttr();
                                    styleInput('TxtDocumentCode');
                                    styleInput('TxtRevisionNumber');
                                    styleInput('TxtEffectivity');
                                    styleInput('TxtNoLabel');
                                    styleInput('TxtStudentCategory');
                                    styleInput('TCourse');
                                    styleInput('TYear');
                                    styleInput('TSection');
                                    styleInput('TxtLastname');
                                    styleInput('TxtFirstname');
                                    styleInput('TxtMiddlename');
                                    styleInput('TxtExtension');
                                    styleInput('TxtBirthdate');

                                    styleInput('TxtAddress');
                                    styleInput('TxtStudentContactNumber');
                                    styleInput('TGPCategory');
                                    styleInput('TxtContactPerson');
                                    styleInput('TxtPGContactNumber');
                                    styleInput('TGPCategory1');
                                    styleInput('TxtContactPerson1');
                                    styleInput('TxtPGContactNumber1');
                                    styleInput('TxtDate');
                                    styleInput('TxtTime');

                                    styleInput('TxtLMP');
                                    styleInput('TxtPregnancy');
                                    styleInput('TxtAllergies');
                                    styleInput('TxtSurgeries');
                                    styleInput('TxtInjuries');
                                    styleInput('TxtIllness');
                                    styleInput('TxtSchoolYear');

                                    styleInput('TxtHeight');
                                    styleInput('TxtWeight');
                                    styleInput('TxtBloodPressure');
                                    styleInput('TxtTemperature');
                                    styleInput('TxtPulseRate');
                                    styleInput('TxtVisionWithoutGlassesOD');
                                    styleInput('TxtVisionWithoutGlassesOS');
                                    styleInput('TxtVisionWithGlassesOD');
                                    styleInput('TxtVisionWithGlassesOS');

                                    styleInput('TxtHearingDistanceOption');
                                    styleInput('TxtSpeechOption');
                                    styleInput('TxtEyesOption');
                                    styleInput('TxtEarsOption');
                                    styleInput('TxtNoseOption');
                                    styleInput('TxtHeadOption');
                                    styleInput('TxtAbdomenOption');
                                    styleInput('TxtGenitoUrinaryOption');
                                    styleInput('TxtLymphGlandsOption');
                                    styleInput('TxtSkinOption');
                                    styleInput('TxtExtremitiesOption');
                                    styleInput('TxtDeformitiesOption');
                                    styleInput('TxtCavityAndThroatOption');
                                    styleInput('TxtLungsOption');
                                    styleInput('TxtHeartOption');
                                    styleInput('TxtBreastOption');
                                    styleInput('TxtRadiologicExamsOption');
                                    styleInput('TxtBloodAnalysisOption');
                                    styleInput('TxtUrinalysisOption');
                                    styleInput('TxtFecalysisOption');
                                    styleInput('TxtPregnancyTestOption');
                                    styleInput('TxtHBSAgOption');

                                    styleInput('TAHearingDistance');
                                    styleInput('TASpeech');
                                    styleInput('TAEyes');
                                    styleInput('TAEars');
                                    styleInput('TANose');
                                    styleInput('TAHead');
                                    styleInput('TAAbdomen');
                                    styleInput('TAGenitoUrinary');
                                    styleInput('TALymphGlands');
                                    styleInput('TASkin');
                                    styleInput('TAExtremities');
                                    styleInput('TADeformities');
                                    styleInput('TACavityAndThroat');
                                    styleInput('TALungs');
                                    styleInput('TAHeart');
                                    styleInput('TABreast');
                                    styleInput('TARadiologicExams');
                                    styleInput('TABloodAnalysis');
                                    styleInput('TAUrinalysis');
                                    styleInput('TAFecalysis');
                                    styleInput('TAPregnancyTest');
                                    styleInput('TAHBSAg');

                                    styleInput('TxtOthers');
                                    styleInput('TxtRecommendation');
                                    styleInput('TxtRemarks');

                                    checkArchive();
                        });
                    },  
                    error: function (e)
                    {
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

            function clickedOld(){
                document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
                document.getElementById('ExaminedBy').style.display = 'inline-block';
                var form_data = new FormData();
                var Num = document.getElementById('TxtStudentIDNumber').value;
                form_data.append("idnumber", Num);
                form_data.append("type", getType);
                $.ajax(
                { 
                    url:"../php/FetchRecords.php",
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
                            var DocumentCode = $(this).attr('DocumentCode');
                            var RevisionNumber = $(this).attr('RevisionNumber');
                            var Effectivity = $(this).attr('Effectivity');
                            var NoLabel = $(this).attr('NoLabel');
                            var StudentImage = $(this).attr('StudentImage');
                            var StudentIDNumber = $(this).attr('StudentIDNumber');
                            var StudentCategory = $(this).attr('StudentCategory');
                            var Course = $(this).attr('Course');
                            var Year = $(this).attr('Year');
                            var Section = $(this).attr('Section');
                            var Lastname = $(this).attr('Lastname');
                            var Firstname = $(this).attr('Firstname');
                            var Middlename = $(this).attr('Middlename');
                            var Extension = $(this).attr('Extension');
                            var Age = $(this).attr('Age');
                            var Birthdate = $(this).attr('Birthdate');
                            var Sex = $(this).attr('Sex');
                            var Address = $(this).attr('Address');
                            var StudentContactNumber = $(this).attr('StudentContactNumber');
                            var GuardianParent = $(this).attr('GuardianParent');
                            var GPCategory = $(this).attr('GPCategory');
                            var ContactPerson = $(this).attr('ContactPerson');
                            var PGContactNumber = $(this).attr('PGContactNumber');
                            var GuardianParent1 = $(this).attr('GuardianParent1');
                            var GPCategory1 = $(this).attr('GPCategory1');
                            var ContactPerson1 = $(this).attr('ContactPerson1');
                            var PGContactNumber1 = $(this).attr('PGContactNumber1');
                            var Date = $(this).attr('Date');
                            var Time = $(this).attr('Time');
                            var StaffIDNumber = $(this).attr('StaffIDNumber');
                            var StaffLastname = $(this).attr('StaffLastname');
                            var StaffFirstname = $(this).attr('StaffFirstname');
                            var StaffMiddlename = $(this).attr('StaffMiddlename');
                            var StaffExtension = $(this).attr('StaffExtension');
                            var LMP = $(this).attr('LMP');
                            var Pregnancy = $(this).attr('Pregnancy');
                            var Allergies = $(this).attr('Allergies');
                            var Surgeries = $(this).attr('Surgeries');
                            var Injuries = $(this).attr('Injuries');
                            var Illness = $(this).attr('Illness');
                            var SchoolYear = $(this).attr('SchoolYear');
                            var Height = $(this).attr('Height');
                            var Weight = $(this).attr('Weight');
                            var BMI = $(this).attr('BMI');
                            var BloodPressure = $(this).attr('BloodPressure');
                            var Temperature = $(this).attr('Temperature');
                            var PulseRate = $(this).attr('PulseRate');
                            var VisionWithoutGlassesOD = $(this).attr('VisionWithoutGlassesOD');
                            var VisionWithoutGlassesOS = $(this).attr('VisionWithoutGlassesOS');
                            var VisionWithGlassesOD = $(this).attr('VisionWithGlassesOD');
                            var VisionWithGlassesOS = $(this).attr('VisionWithGlassesOS');
                            var HearingDistanceOption = $(this).attr('HearingDistanceOption');
                            var SpeechOption = $(this).attr('SpeechOption');
                            var EyesOption = $(this).attr('EyesOption');
                            var EarsOption = $(this).attr('EarsOption');
                            var NoseOption = $(this).attr('NoseOption');
                            var HeadOption = $(this).attr('HeadOption');
                            var AbdomenOption = $(this).attr('AbdomenOption');
                            var GenitoUrinaryOption = $(this).attr('GenitoUrinaryOption');
                            var LymphGlandsOption = $(this).attr('LymphGlandsOption');
                            var SkinOption = $(this).attr('SkinOption');
                            var ExtremitiesOption = $(this).attr('ExtremitiesOption');
                            var DeformitiesOption = $(this).attr('DeformitiesOption');
                            var CavityAndThroatOption = $(this).attr('CavityAndThroatOption');
                            var LungsOption = $(this).attr('LungsOption');
                            var HeartOption = $(this).attr('HeartOption');
                            var BreastOption = $(this).attr('BreastOption');
                            var RadiologicExamsOption = $(this).attr('RadiologicExamsOption');
                            var BloodAnalysisOption = $(this).attr('BloodAnalysisOption');
                            var UrinalysisOption = $(this).attr('UrinalysisOption');
                            var FecalysisOption = $(this).attr('FecalysisOption');
                            var PregnancyTestOption = $(this).attr('PregnancyTestOption');
                            var HBSAgOption = $(this).attr('HBSAgOption');
                            var TAHearingDistance = $(this).attr('TAHearingDistance');
                            var TASpeech = $(this).attr('TASpeech');
                            var TAEyes = $(this).attr('TAEyes');
                            var TAEars = $(this).attr('TAEars');
                            var TANose = $(this).attr('TANose');
                            var TAHead = $(this).attr('TAHead');
                            var TAAbdomen = $(this).attr('TAAbdomen');
                            var TAGenitoUrinary = $(this).attr('TAGenitoUrinary');
                            var TALymphGlands = $(this).attr('TALymphGlands');
                            var TASkin = $(this).attr('TASkin');
                            var TAExtremities = $(this).attr('TAExtremities');
                            var TADeformities = $(this).attr('TADeformities');
                            var TACavityAndThroat = $(this).attr('TACavityAndThroat');
                            var TALungs = $(this).attr('TALungs');
                            var TAHeart = $(this).attr('TAHeart');
                            var TABreast = $(this).attr('TABreast');
                            var TARadiologicExams = $(this).attr('TARadiologicExams');
                            var TABloodAnalysis = $(this).attr('TABloodAnalysis');
                            var TAUrinalysis = $(this).attr('TAUrinalysis');
                            var TAFecalysis = $(this).attr('TAFecalysis');
                            var TAPregnancyTest = $(this).attr('TAPregnancyTest');
                            var TAHBSAg = $(this).attr('TAHBSAg');
                            var Others = $(this).attr('Others');
                            var Remarks = $(this).attr('Remarks');
                            var Recommendation = $(this).attr('Recommendation');

                            TempSex = Sex;
                            TempGuardianParent = GuardianParent;
                            TempGuardianParent1 = GuardianParent1;
                           


                            if(error == "1"){

                                    $.alert(
                                    {theme: 'modern',
                                        content: message,
                                        title:'', 
                                        buttons:{
                                        Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-red'
                                        }}});

                                    clearPersonalMedical();
                                    $('#RadGuardian1').prop('checked', true);
                                    $('#RadGuardian').prop('checked', true);
                                    document.getElementById('addMore').style.display = 'none';
                                    document.getElementById('addMoreForm').style.display = 'none';
                                    document.getElementById('addMoreForm1').style.display = 'none';
                                    document.getElementById('addMoreForm2').style.display = 'none';

                            }else{
                                    $('#TxtDocumentCode').val(DocumentCode);
                                    $('#TxtRevisionNumber').val(RevisionNumber);
                                    $('#TxtEffectivity').val(Effectivity);
                                    $('#TxtNoLabel').val(NoLabel);
                                    if(StudentImage.length == 18){
                                        document.getElementById("IDPic").src = "../images/id picture.webp";
                                    }else{
                                        document.getElementById("IDPic").src = StudentImage;
                                        imgSrc = StudentImage;
                                    }
                                    $('#TxtStudentIDNumber').val(StudentIDNumber);

                                    if(StudentCategory == "elementary"){
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("elementary").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'none';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById("YO1").setAttribute('value','1');
                                        document.getElementById("YO2").setAttribute('value','2');
                                        document.getElementById("YO3").setAttribute('value','3');
                                        document.getElementById("YO4").setAttribute('value','4');
                                        document.getElementById("YO5").setAttribute('value','5');
                                        document.getElementById("YO6").setAttribute('value','6'); 

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");
                                    }else if(StudentCategory == "highschool"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("highschool").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'none';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById("YO1").setAttribute('value','7');
                                        document.getElementById("YO2").setAttribute('value','8');
                                        document.getElementById("YO3").setAttribute('value','9');
                                        document.getElementById("YO4").setAttribute('value','10');
                                        document.getElementById("YO5").removeAttribute("value");
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");
                                    }else if(StudentCategory == "senior highschool"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("college").removeAttribute("selected");
                                        document.getElementById("senior").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'block';
                                        document.getElementById('YR').innerHTML = 'Grade';
                                        document.getElementById('CS').innerHTML = 'Strand';
                                        document.getElementById("YO1").setAttribute('value','11');
                                        document.getElementById("YO2").setAttribute('value','12');
                                        document.getElementById("YO3").removeAttribute("value");
                                        document.getElementById("YO4").removeAttribute("value");
                                        document.getElementById("YO5").removeAttribute("value");
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").removeAttribute("value");
                                        document.getElementById("SO2").removeAttribute("value");
                                        document.getElementById("SO3").removeAttribute("value");
                                        document.getElementById("SO4").removeAttribute("value");
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");

                                        document.getElementById("CO1").setAttribute('value','Science, Technology, Engineering, and Mathematics (STEM)');
                                        document.getElementById("CO2").removeAttribute("value");
                                        document.getElementById("CO3").removeAttribute("value");
                                        document.getElementById("CO4").removeAttribute("value");
                                        document.getElementById("CO5").removeAttribute("value");
                                        document.getElementById("CO6").removeAttribute("value");
                                        document.getElementById("CO7").removeAttribute("value");
                                        document.getElementById("CO8").removeAttribute("value");
                                        document.getElementById("CO9").removeAttribute("value");
                                        document.getElementById("CO10").removeAttribute("value");
                                        document.getElementById("CO11").removeAttribute("value");
                                        document.getElementById("CO12").removeAttribute("value");
                                        document.getElementById("CO13").removeAttribute("value");
                                        document.getElementById("CO14").removeAttribute("value");
                                        document.getElementById("CO15").removeAttribute("value");
                                        document.getElementById("CO16").removeAttribute("value");
                                        document.getElementById("CO17").removeAttribute("value");
                                        document.getElementById("CO18").removeAttribute("value");
                                        document.getElementById("CO19").removeAttribute("value");
                                        document.getElementById("CO20").removeAttribute("value");
                                        document.getElementById("CO21").removeAttribute("value");
                                        document.getElementById("CO22").removeAttribute("value");
                                        document.getElementById("CO23").removeAttribute("value");
                                        document.getElementById("CO24").removeAttribute("value");
                                        document.getElementById("CO25").removeAttribute("value");
                                        document.getElementById("CO26").removeAttribute("value");
                                        document.getElementById("CO27").removeAttribute("value");
                                        document.getElementById("CO28").removeAttribute("value");
                                        document.getElementById("CO29").removeAttribute("value");
                                        document.getElementById("CO30").removeAttribute("value");
                                        document.getElementById("CO31").removeAttribute("value");
                                        document.getElementById("CO32").removeAttribute("value");
                                    }else if(StudentCategory == "college"){
                                        document.getElementById("elementary").removeAttribute("selected");
                                        document.getElementById("senior").removeAttribute("selected");
                                        document.getElementById("highschool").removeAttribute("selected");
                                        document.getElementById("college").setAttribute('selected','selected');
                                        document.getElementById("Cour").style.display = 'block';
                                        document.getElementById('YR').innerHTML = 'Year';
                                        document.getElementById('CS').innerHTML = 'Course';
                                        document.getElementById("YO1").setAttribute('value','1');
                                        document.getElementById("YO2").setAttribute('value','2');
                                        document.getElementById("YO3").setAttribute('value','3');
                                        document.getElementById("YO4").setAttribute('value','4');
                                        document.getElementById("YO5").setAttribute('value','Irregular');
                                        document.getElementById("YO6").removeAttribute("value");

                                        document.getElementById("SO1").setAttribute('value','A');
                                        document.getElementById("SO2").setAttribute('value','B');
                                        document.getElementById("SO3").setAttribute('value','C');
                                        document.getElementById("SO4").setAttribute('value','D');
                                        document.getElementById("SO5").removeAttribute("value");
                                        document.getElementById("SO6").removeAttribute("value");

                                        document.getElementById("CO1").setAttribute('value','Bachelor of Arts in Communication (BA Com)');
                                        document.getElementById("CO2").setAttribute('value','Bachelor of Arts in English Language (BA EL)');
                                        document.getElementById("CO3").setAttribute('value','Bachelor of Arts in Filipino Language (BA FL)');
                                        document.getElementById("CO4").setAttribute('value','Bachelor of Early Childhood Education (BECEd)');
                                        document.getElementById("CO5").setAttribute('value','Bachelor of Elementary Education (BEEd)');
                                        document.getElementById("CO6").setAttribute('value','Bachelor of Library and Information Science (BLIS)');
                                        document.getElementById("CO7").setAttribute('value','Bachelor of Physical Education (BPEd)');
                                        document.getElementById("CO8").setAttribute('value','Bachelor of Public Administration (BPA)');
                                        document.getElementById("CO9").setAttribute('value','Bachelor of Science in Agricultural and Biosystems Engineering (BSABE)');
                                        document.getElementById("CO10").setAttribute('value','Bachelor of Science in Agriculture (BSA)');
                                        document.getElementById("CO11").setAttribute('value','Bachelor of Science in Biology (BS Bio)');
                                        document.getElementById("CO12").setAttribute('value','Bachelor of Science in Chemistry (BS Chem)');
                                        document.getElementById("CO13").setAttribute('value','Bachelor of Science in Development Communication (BSDC)');
                                        document.getElementById("CO14").setAttribute('value','Bachelor of Science in Entrepreneurship (BS Entrep)');
                                        document.getElementById("CO15").setAttribute('value','Bachelor of Science in Environmental Science (BSES)');
                                        document.getElementById("CO16").setAttribute('value','Bachelor of Science in Exercise and Sports Sciences (BSESS)');
                                        document.getElementById("CO17").setAttribute('value','Bachelor of Science in Forestry (BSF)');
                                        document.getElementById("CO18").setAttribute('value','Bachelor of Science in Hospitality Management (BSHM)');
                                        document.getElementById("CO19").setAttribute('value','Bachelor of Science in Information Technology (BSIT)');
                                        document.getElementById("CO20").setAttribute('value','Bachelor of Science in Mathematics (BS Math)');
                                        document.getElementById("CO21").setAttribute('value','Bachelor of Science in Nursing (BSN)');
                                        document.getElementById("CO22").setAttribute('value','Bachelor of Science in Nutrition and Dietetics (BSND)');
                                        document.getElementById("CO23").setAttribute('value','Bachelor of Science in Statistics (BSS)');
                                        document.getElementById("CO24").setAttribute('value','Bachelor of Secondary Education major in English (BSEd-English)');
                                        document.getElementById("CO25").setAttribute('value','Bachelor of Secondary Education major in Filipino (BSEd-Filipino)');
                                        document.getElementById("CO26").setAttribute('value','Bachelor of Secondary Education major in Mathematics (BSEd-Math)');
                                        document.getElementById("CO27").setAttribute('value','Bachelor of Secondary Education major in Science (BSEd-Science)');
                                        document.getElementById("CO28").setAttribute('value','Bachelor of Secondary Education major in Social Studies (BSEd-SSt)');
                                        document.getElementById("CO29").setAttribute('value','Bachelor of Secondary Education major in Values Education (BSEd-VE)');
                                        document.getElementById("CO30").setAttribute('value','Bachelor of Technology and Livelihood Education-Home Economics (BTLEd-HE)');
                                        document.getElementById("CO31").setAttribute('value','Doctor of Veterinary Medicine (DVM)');
                                        document.getElementById("CO32").setAttribute('value','Bachelor of Science in Agribusiness (BSAB)');
                                    } 
                                    $('#TCourse').val(Course);
                                    $('#TYear').val(Year);
                                    $('#TSection').val(Section);
                                    $('#TxtLastname').val(Lastname);
                                    $('#TxtFirstname').val(Firstname);
                                    $('#TxtMiddlename').val(Middlename);
                                    $('#TxtExtension').val(Extension);
                                    $('#TxtAge').val(Age);
                                    $('#TxtBirthdate').val(Birthdate);
                                    if(Sex == "male"){
                                        $('#RadMale').prop('checked', true);
                                    }else{
                                        $('#RadFemale').prop('checked', true);
                                    }
                                    $('#TxtAddress').val(Address);
                                    $('#TxtStudentContactNumber').val(StudentContactNumber);
                                    if(GPCategory != '' || ContactPerson != ''){
                                        if(GuardianParent == "guardian"){
                                            $('#RadGuardian').prop('checked', true);
                                        }else if(GuardianParent == "parent"){
                                            $('#RadParent').prop('checked', true);
                                        }else{
                                            $('#RadNone').prop('checked', true);
                                        }
                                        $('#TGPCategory').val(GPCategory);
                                        $('#TxtContactPerson').val(ContactPerson);
                                        $('#TxtPGContactNumber').val(PGContactNumber);
                                    }else{
                                        $('#RadNone').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm2').style.display = 'none';
                                    }
                                    $('#TxtDate').val(Date);
                                    $('#TxtTime').val(Time);
                                    document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number: ' + StaffIDNumber;
                                    document.getElementById('TxtMSFullName').innerHTML = 'Charted By: ' + (StaffLastname + ", " + StaffFirstname + " " + StaffMiddlename + " " + StaffExtension).toUpperCase();
                                    $('#TxtLMP').val(LMP);
                                    $('#TxtPregnancy').val(Pregnancy);
                                    $('#TxtAllergies').val(Allergies);
                                    $('#TxtSurgeries').val(Surgeries);
                                    $('#TxtInjuries').val(Injuries);
                                    $('#TxtIllness').val(Illness);
                                    $('#TxtSchoolYear').val(SchoolYear);
                                    $('#TxtHeight').val(Height);
                                    $('#TxtWeight').val(Weight);
                                    $('#TxtBMI').val(BMI);
                                    $('#TxtBloodPressure').val(BloodPressure);
                                    $('#TxtTemperature').val(Temperature);
                                    $('#TxtPulseRate').val(PulseRate);
                                    $('#TxtVisionWithoutGlassesOD').val(VisionWithoutGlassesOD);
                                    $('#TxtVisionWithoutGlassesOS').val(VisionWithoutGlassesOS);
                                    $('#TxtVisionWithGlassesOD').val(VisionWithGlassesOD);
                                    $('#TxtVisionWithGlassesOS').val(VisionWithGlassesOS);

                                    if(HearingDistanceOption == "with findings"){
                                        document.getElementById("unremarkableHD").removeAttribute("selected");
                                        document.getElementById("wFindingsHD").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableHD").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHD").removeAttribute("selected");
                                    }
                                    if(SpeechOption == "with findings"){
                                        document.getElementById("unremarkableSp").removeAttribute("selected");
                                        document.getElementById("wFindingsSp").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableSp").setAttribute('selected','selected');
                                        document.getElementById("wFindingsSp").removeAttribute("selected");
                                    }
                                    if(EyesOption == "with findings"){
                                        document.getElementById("unremarkableEy").removeAttribute("selected");
                                        document.getElementById("wFindingsEy").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableEy").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEy").removeAttribute("selected");
                                    }
                                    if(EarsOption == "with findings"){
                                        document.getElementById("unremarkableEa").removeAttribute("selected");
                                        document.getElementById("wFindingsEa").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableEa").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEa").removeAttribute("selected");
                                    }
                                    if(NoseOption == "with findings"){
                                        document.getElementById("unremarkableNo").removeAttribute("selected");
                                        document.getElementById("wFindingsNo").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableNo").setAttribute('selected','selected');
                                        document.getElementById("wFindingsNo").removeAttribute("selected");
                                    }
                                    if(HeadOption == "with findings"){
                                        document.getElementById("unremarkableHe").removeAttribute("selected");
                                        document.getElementById("wFindingsHe").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableHe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHe").removeAttribute("selected");
                                    }
                                    if(AbdomenOption == "with findings"){
                                        document.getElementById("unremarkableAb").removeAttribute("selected");
                                        document.getElementById("wFindingsAb").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableAb").setAttribute('selected','selected');
                                        document.getElementById("wFindingsAb").removeAttribute("selected");
                                    }
                                    if(GenitoUrinaryOption == "with findings"){
                                        document.getElementById("unremarkableGU").removeAttribute("selected");
                                        document.getElementById("wFindingsGU").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableGU").setAttribute('selected','selected');
                                        document.getElementById("wFindingsGU").removeAttribute("selected");
                                    }
                                    if(LymphGlandsOption == "with findings"){
                                        document.getElementById("unremarkableLG").removeAttribute("selected");
                                        document.getElementById("wFindingsLG").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableLG").setAttribute('selected','selected');
                                        document.getElementById("wFindingsLG").removeAttribute("selected");
                                    }
                                    if(SkinOption == "with findings"){
                                        document.getElementById("unremarkableSk").removeAttribute("selected");
                                        document.getElementById("wFindingsSk").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableSk").setAttribute('selected','selected');
                                        document.getElementById("wFindingsSk").removeAttribute("selected");
                                    }
                                    if(ExtremitiesOption == "with findings"){
                                        document.getElementById("unremarkableEx").removeAttribute("selected");
                                        document.getElementById("wFindingsEx").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableEx").setAttribute('selected','selected');
                                        document.getElementById("wFindingsEx").removeAttribute("selected");
                                    }
                                    if(DeformitiesOption == "with findings"){
                                        document.getElementById("unremarkableDe").removeAttribute("selected");
                                        document.getElementById("wFindingsDe").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableDe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsDe").removeAttribute("selected");
                                    }
                                    if(CavityAndThroatOption == "with findings"){
                                        document.getElementById("unremarkableCT").removeAttribute("selected");
                                        document.getElementById("wFindingsCT").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableCT").setAttribute('selected','selected');
                                        document.getElementById("wFindingsCT").removeAttribute("selected");
                                    }
                                    if(LungsOption == "with findings"){
                                        document.getElementById("unremarkableLu").removeAttribute("selected");
                                        document.getElementById("wFindingsLu").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableLu").setAttribute('selected','selected');
                                        document.getElementById("wFindingsLu").removeAttribute("selected");
                                    }
                                    if(HeartOption == "with findings"){
                                        document.getElementById("unremarkableHea").removeAttribute("selected");
                                        document.getElementById("wFindingsHea").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableHea").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHea").removeAttribute("selected");
                                    }
                                    if(BreastOption == "with findings"){
                                        document.getElementById("unremarkableBr").removeAttribute("selected");
                                        document.getElementById("wFindingsBr").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableBr").setAttribute('selected','selected');
                                        document.getElementById("wFindingsBr").removeAttribute("selected");
                                    }
                                    if(RadiologicExamsOption == "with findings"){
                                        document.getElementById("unremarkableRE").removeAttribute("selected");
                                        document.getElementById("wFindingsRE").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableRE").setAttribute('selected','selected');
                                        document.getElementById("wFindingsRE").removeAttribute("selected");
                                    }
                                    if(BloodAnalysisOption == "with findings"){
                                        document.getElementById("unremarkableBA").removeAttribute("selected");
                                        document.getElementById("wFindingsBA").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableBA").setAttribute('selected','selected');
                                        document.getElementById("wFindingsBA").removeAttribute("selected");
                                    }
                                    if(UrinalysisOption == "with findings"){
                                        document.getElementById("unremarkableUr").removeAttribute("selected");
                                        document.getElementById("wFindingsUr").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableUr").setAttribute('selected','selected');
                                        document.getElementById("wFindingsUr").removeAttribute("selected");
                                    }
                                    if(FecalysisOption == "with findings"){
                                        document.getElementById("unremarkableFe").removeAttribute("selected");
                                        document.getElementById("wFindingsFe").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableFe").setAttribute('selected','selected');
                                        document.getElementById("wFindingsFe").removeAttribute("selected");
                                    }
                                    if(PregnancyTestOption == "with findings"){
                                        document.getElementById("unremarkablePT").removeAttribute("selected");
                                        document.getElementById("wFindingsPT").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkablePT").setAttribute('selected','selected');
                                        document.getElementById("wFindingsPT").removeAttribute("selected");
                                    }
                                    if(HBSAgOption == "with findings"){
                                        document.getElementById("unremarkableHB").removeAttribute("selected");
                                        document.getElementById("wFindingsHB").setAttribute('selected','selected');
                                    }else{
                                        document.getElementById("unremarkableHB").setAttribute('selected','selected');
                                        document.getElementById("wFindingsHB").removeAttribute("selected");
                                    }

                                    $('#TAHearingDistance').val(TAHearingDistance);
                                    $('#TASpeech').val(TASpeech);
                                    $('#TAEyes').val(TAEyes);
                                    $('#TAEars').val(TAEars);
                                    $('#TANose').val(TANose);
                                    $('#TAHead').val(TAHead);
                                    $('#TAAbdomen').val(TAAbdomen);
                                    $('#TAGenitoUrinary').val(TAGenitoUrinary);
                                    $('#TALymphGlands').val(TALymphGlands);
                                    $('#TASkin').val(TASkin);
                                    $('#TAExtremities').val(TAExtremities);
                                    $('#TADeformities').val(TADeformities);
                                    $('#TACavityAndThroat').val(TACavityAndThroat);
                                    $('#TALungs').val(TALungs);
                                    $('#TAHeart').val(TAHeart);
                                    $('#TABreast').val(TABreast);
                                    $('#TARadiologicExams').val(TARadiologicExams);
                                    $('#TABloodAnalysis').val(TABloodAnalysis);
                                    $('#TAUrinalysis').val(TAUrinalysis);
                                    $('#TAFecalysis').val(TAFecalysis);
                                    $('#TAPregnancyTest').val(TAPregnancyTest);
                                    $('#TAHBSAg').val(TAHBSAg);

                                    $('#TxtOthers').val(Others);
                                    $('#TxtRemarks').val(Remarks);
                                    $('#TxtRecommendation').val(Recommendation);

                                    if(GPCategory1 != '' || ContactPerson1 != ''){
                                        showAddMore();
                                        if(GuardianParent1 == "guardian"){
                                            $('#RadGuardian1').prop('checked', true);
                                        }else if(GuardianParent1 == "parent"){
                                            $('#RadParent1').prop('checked', true);
                                        }else{
                                            $('#RadNone1').prop('checked', true);
                                        }
                                        $('#TGPCategory1').val(GPCategory1);
                                        $('#TxtContactPerson1').val(ContactPerson1);
                                        $('#TxtPGContactNumber1').val(PGContactNumber1);
                                    }else{
                                        $('#RadNone1').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm2').style.display = 'none';
                                    }

                                  
                            }
                                    setAttr();
                                    styleInput('TxtDocumentCode');
                                    styleInput('TxtRevisionNumber');
                                    styleInput('TxtEffectivity');
                                    styleInput('TxtNoLabel');
                                    styleInput('TxtStudentCategory');
                                    styleInput('TCourse');
                                    styleInput('TYear');
                                    styleInput('TSection');
                                    styleInput('TxtLastname');
                                    styleInput('TxtFirstname');
                                    styleInput('TxtMiddlename');
                                    styleInput('TxtExtension');
                                    styleInput('TxtBirthdate');

                                    styleInput('TxtAddress');
                                    styleInput('TxtStudentContactNumber');
                                    styleInput('TGPCategory');
                                    styleInput('TxtContactPerson');
                                    styleInput('TxtPGContactNumber');
                                    styleInput('TGPCategory1');
                                    styleInput('TxtContactPerson1');
                                    styleInput('TxtPGContactNumber1');
                                    styleInput('TxtDate');
                                    styleInput('TxtTime');


                                    styleInput('TxtLMP');
                                    styleInput('TxtPregnancy');
                                    styleInput('TxtAllergies');
                                    styleInput('TxtSurgeries');
                                    styleInput('TxtInjuries');
                                    styleInput('TxtIllness');
                                    styleInput('TxtSchoolYear');

                                    styleInput('TxtHeight');
                                    styleInput('TxtWeight');
                                    styleInput('TxtBloodPressure');
                                    styleInput('TxtTemperature');
                                    styleInput('TxtPulseRate');
                                    styleInput('TxtVisionWithoutGlassesOD');
                                    styleInput('TxtVisionWithoutGlassesOS');
                                    styleInput('TxtVisionWithGlassesOD');
                                    styleInput('TxtVisionWithGlassesOS');

                                    styleInput('TxtHearingDistanceOption');
                                    styleInput('TxtSpeechOption');
                                    styleInput('TxtEyesOption');
                                    styleInput('TxtEarsOption');
                                    styleInput('TxtNoseOption');
                                    styleInput('TxtHeadOption');
                                    styleInput('TxtAbdomenOption');
                                    styleInput('TxtGenitoUrinaryOption');
                                    styleInput('TxtLymphGlandsOption');
                                    styleInput('TxtSkinOption');
                                    styleInput('TxtExtremitiesOption');
                                    styleInput('TxtDeformitiesOption');
                                    styleInput('TxtCavityAndThroatOption');
                                    styleInput('TxtLungsOption');
                                    styleInput('TxtHeartOption');
                                    styleInput('TxtBreastOption');
                                    styleInput('TxtRadiologicExamsOption');
                                    styleInput('TxtBloodAnalysisOption');
                                    styleInput('TxtUrinalysisOption');
                                    styleInput('TxtFecalysisOption');
                                    styleInput('TxtPregnancyTestOption');
                                    styleInput('TxtHBSAgOption');

                                    styleInput('TAHearingDistance');
                                    styleInput('TASpeech');
                                    styleInput('TAEyes');
                                    styleInput('TAEars');
                                    styleInput('TANose');
                                    styleInput('TAHead');
                                    styleInput('TAAbdomen');
                                    styleInput('TAGenitoUrinary');
                                    styleInput('TALymphGlands');
                                    styleInput('TASkin');
                                    styleInput('TAExtremities');
                                    styleInput('TADeformities');
                                    styleInput('TACavityAndThroat');
                                    styleInput('TALungs');
                                    styleInput('TAHeart');
                                    styleInput('TABreast');
                                    styleInput('TARadiologicExams');
                                    styleInput('TABloodAnalysis');
                                    styleInput('TAUrinalysis');
                                    styleInput('TAFecalysis');
                                    styleInput('TAPregnancyTest');
                                    styleInput('TAHBSAg');

                                    styleInput('TxtOthers');
                                    styleInput('TxtRecommendation');
                                    styleInput('TxtRemarks');

                                    checkArchive();
                        });
                    },  
                    error: function (e)
                    {
                      
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

            function LoadImage(input){
                var tmppath = (window.URL || window.webkitURL).createObjectURL(event.target.files[0]);
                var img = document.getElementById("IDPic");

                //test if file size is greater than 1 MB
                var fileSize = input.files[0].size / 1024 / 1024;
                if (fileSize > 1) {
                    //alerts user if filesize is more than 1 MB
                    $.alert(
                        {theme: 'modern',
                        content: "Filesize must not exceed 1 MB.",
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    document.getElementById("IDPic").src = "../images/id picture.webp";
                    $('#TxtStudentImage').val('');
                }else{
                    //loads image
                    img.src = tmppath;
                }
                
            }

            function changeCSS(){
                document.getElementById('tab2').click();

                document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number: ' + document.getElementById('TxtStudentIDNumber').value;
                document.getElementById('TxtStudentFullName').innerHTML = 'Full Name: ' + (document.getElementById('TxtLastname').value + ", " + document.getElementById('TxtFirstname').value + " " + document.getElementById('TxtMiddlename').value + " " + document.getElementById('TxtExtension').value).toUpperCase();

                
            }

            function clearPersonalMedical(){
                $('#TxtDocumentCode').val('');
                $('#TxtRevisionNumber').val('');
                $('#TxtEffectivity').val('');
                $('#TxtNoLabel').val('');
                $('#RadNew').prop('checked', false);
                $('#RadOld').prop('checked', false);
                $('#TxtStudentIDNumber').val('');
                document.getElementById("IDPic").src = "../images/id picture.webp";
                $('#TxtStudentImage').val('');
                $('#TxtStudentCategory').val('');
                $('#TCourse').val('');
                $('#TYear').val('');
                $('#TSection').val('');
                $('#TxtLastname').val('');
                $('#TxtFirstname').val('');
                $('#TxtMiddlename').val('');
                $('#TxtExtension').val('');
                $('#TxtAge').val('');
                $('#TxtBirthdate').val('');
                $('#RadMale').prop('checked', false);
                $('#RadFemale').prop('checked', false);
                $('#TxtAddress').val('');
                $('#TxtStudentContactNumber').val('');
                $('#RadGuardian').prop('checked', false);
                $('#RadParent').prop('checked', false);
                $('#TGPCategory').val('');
                $('#TxtContactPerson').val('');
                $('#TxtPGContactNumber').val('');
                $('#RadGuardian1').prop('checked', false);
                $('#RadParent1').prop('checked', false);
                $('#TGPCategory1').val('');
                $('#TxtContactPerson1').val('');
                $('#TxtPGContactNumber1').val('');

                document.getElementById('TxtStudentFullName').innerHTML = 'Full Name:';
                document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSFullName').innerHTML = 'Charted By:';
                $('#TxtDate').val('');
                $('#TxtTime').val('');

                $('#TxtLMP').val('');
                $('#TxtPregnancy').val('');
                $('#TxtAllergies').val('');
                $('#TxtSurgeries').val('');
                $('#TxtInjuries').val('');
                $('#TxtIllness').val('');
                $('#TxtSchoolYear').val('');
                $('#TxtHeight').val('');
                $('#TxtWeight').val('');
                $('#TxtBMI').val('');
                $('#TxtBloodPressure').val('');
                $('#TxtTemperature').val('');
                $('#TxtPulseRate').val('');
                $('#TxtVisionWithoutGlassesOD').val('');
                $('#TxtVisionWithoutGlassesOS').val('');
                $('#TxtVisionWithGlassesOD').val('');
                $('#TxtVisionWithGlassesOS').val('');
                $('#TxtHearingDistanceOption').val('');
                $('#TxtSpeechOption').val('');
                $('#TxtEyesOption').val('');
                $('#TxtEarsOption').val('');
                $('#TxtNoseOption').val('');
                $('#TxtHeadOption').val('');
                $('#TxtAbdomenOption').val('');
                $('#TxtGenitoUrinaryOption').val('');
                $('#TxtLymphGlandsOption').val('');
                $('#TxtSkinOption').val('');
                $('#TxtExtremitiesOption').val('');
                $('#TxtDeformitiesOption').val('');
                $('#TxtCavityAndThroatOption').val('');
                $('#TxtLungsOption').val('');
                $('#TxtHeartOption').val('');
                $('#TxtBreastOption').val('');
                $('#TxtRadiologicExamsOption').val('');
                $('#TxtBloodAnalysisOption').val('');
                $('#TxtUrinalysisOption').val('');
                $('#TxtFecalysisOption').val('');
                $('#TxtPregnancyTestOption').val('');
                $('#TxtHBSAgOption').val('');
                $('#TAHearingDistance').val('');
                $('#TASpeech').val('');
                $('#TAEyes').val('');
                $('#TAEars').val('');
                $('#TANose').val('');
                $('#TAHead').val('');
                $('#TAAbdomen').val('');
                $('#TAGenitoUrinary').val('');
                $('#TALymphGlands').val('');
                $('#TASkin').val('');
                $('#TAExtremities').val('');
                $('#TADeformities').val('');
                $('#TACavityAndThroat').val('');
                $('#TALungs').val('');
                $('#TAHeart').val('');
                $('#TABreast').val('');
                $('#TARadiologicExams').val('');
                $('#TABloodAnalysis').val('');
                $('#TAUrinalysis').val('');
                $('#TAFecalysis').val('');
                $('#TAPregnancyTest').val('');
                $('#TAHBSAg').val('');
                $('#TxtOthers').val('');
                $('#TxtRemarks').val('');
                $('#TxtRecommendation').val('');
            }

            function saveRecords(form_data)
            {   
                $.ajax(
                { 
                    url:"../php/SaveUser.php",
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
                              
                                message="Successfully saved student information";
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
                                    window.location="../index.php?type=checkRecords";
                                }, 5000);
                            }
                            
                        });
                     },
                    error: function (e)
                    {
                      
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
                    url:"../php/addRecords.php",
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
                                
                                Result="Successfully added the student information";
                                $.alert({
                                    theme: 'modern',
                                    content:Result,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});
                                setTimeout(function(){
                                    window.location="../index.php?type=checkRecords";
                                }, 5000);
                            }

                            logAction(Result);
                        });
                     },
                    error: function (e)
                    {
                       
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
                const input = document.getElementById('TxtBloodPressure');

                input.oninput = (e) => {  
                    const cursorPosition = input.selectionStart - 1;
                    const hasInvalidCharacters = input.value.match(/[^0-9/]/);

                    if (!hasInvalidCharacters) return;
  
                    // Replace all non-digits:
                    input.value = input.value.replace(/[^0-9/]/g, '');
  
                    // Keep cursor position:
                    input.setSelectionRange(cursorPosition, cursorPosition);
                };

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();

                    document.getElementById("userFullname").style.width = "52%";
                    document.getElementById("nav2").style.width = "9.33%";
                    document.getElementById("nav3").style.width = "9.33%";
                    document.getElementById("nav4").style.width = "9.33%";
                    document.getElementById("nav5").style.width = "9.33%";
                    document.getElementById("nav7").style.width = "9.33%";
                    document.getElementById("nav8").style.width = "9.33%";

                }

                $("#add-personal-information").submit(function(event)
                {                
                    /* stop form from submitting normally */
                    event.preventDefault();
                    var form_data = new FormData(this);

                    form_data.append("userID", "<?php echo $_SESSION['userID'] ?>");
                    form_data.append("userLN", "<?php echo $_SESSION['userLastname'] ?>");
                    form_data.append("userFN", "<?php echo $_SESSION['userFirstname'] ?>");
                    form_data.append("userMN", "<?php echo $_SESSION['userMiddlename'] ?>");
                    form_data.append("userEN", "<?php echo $_SESSION['userExtension'] ?>");
                    
                    //alert(form_data.get("btnvalue"));
                    if(TempBtnValue == "save"){
                        saveRecords(form_data);
                    }else{
                        addRecords(form_data);
                    }
                });
            }); 
        </script>
    </head>
    <body>
    <nav id = "nv">
              
      <span id="userFullname"><b><?php echo ucwords($_SESSION['homePosDisp']) . " ";
      $tempNAME = strtolower($_SESSION['fullname']);
      echo ucwords($tempNAME); 
      ?></b></span>
      <a href="../../Homepage/index.php" id="nav2" class="nav-pages">Home</a>
      <a href="../../userList.php?type=checkRecords" id="nav3" class="nav-pages admin-nav">User List</a>
      <a href="../../Student/index.php?type=checkRecords" id="nav4" class="nav-pages active">Student</a>
      <a href="../../Consultation/index.php?type=checkRecords" id="nav5" class="nav-pages">Consultation</a>
      
      <div class="dropdown nav-pages admin-nav" id="nav6">
          <button id="maint" class="dropbtn">Maintenance</button>
                <div class="dropdown-content">
                    <a class="dropA" href="../../logs.php?type=checkRecords" onclick="userCheckLogs()">Logs</a>
                    <a class="dropA" href="../../Student/index.php?type=checkArchivedStudent">Archived Student Records</a>
                    <a class="dropA" href="../../Consultation/index.php?type=checkArchivedConsultation">Archived Consultation Records</a>
                    <a class="dropA" href="../../userList.php?type=checkArchivedStaff">Archived Staff Accounts</a>
                    <a class="dropA" href="../../logs.php?type=checkArchivedLogs">Archived System Logs</a>
                    
                    <a class="dropA" href="../../backup.php">Backup</a>
                    <a class="dropA" href="../../restore.php">Restore</a>
                </div>
      </div>
      <a href="#" onclick="openManual()" id="nav8" class="nav-pages">Help</a>
      <a href="#" id="nav7" onclick="logout()" class="nav-pages">Logout</a>
    </nav>
        
        <div class="container" id="toDownloadPDF">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Personal Information&nbsp;&bull;</span>
                    <span id="tab2" class="tabs-toggle" onclick="changeCSS()">&bull;&nbsp;Medical Information&nbsp;&bull;</span>
                    <span id="wholetab" class="tabs-toggle">&bull;&nbsp;Student Personal & Medical Record&nbsp;&bull;</span>
                </div>
                <div class="tabs-body" id="tabs-bodyID">
                    <form action="#" method="post" id="add-personal-information" autocomplete="off">
                    <div class="tabs-content is-active" id="content">
                    <div class="Two-Info" id="topHeader">
                            <div id="PhysicalExaminationHeader">
                                <img id="bsuLogo" alt="BSU Logo" src="../images/BSULogo.webp"/>
                                <h3>PHYSICAL EXAMINATION</h3>
                            </div>
                            <div id="ISO">
                            <table id="tablePersonalInfo">
                                <tr>
                                    <td class="DocumentCode">
                                        <label for="TxtDocumentCode"> 
                                            Document Code:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" name="TxtDocumentCode" id="TxtDocumentCode" readonly>
                                    </td>
                                    <td class="RevisionNumber">
                                        <label for="TxtRevisionNumber"> 
                                            Revision Number:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="number" id="TxtRevisionNumber" name="TxtRevisionNumber" onkeypress="return isNumberKey(this,event)" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Effectivity">
                                        <label for="TxtEffectivity">
                                            Effectivity
                                        </label>
                                    </td>
                                    <td>
                                        <input type="date" name="TxtEffectivity" id="TxtEffectivity" readonly>
                                    </td>
                                    <td>
                                        <label for="TxtNoLabel"></label>
                                        <input type="text" name="TxtNoLabel" id="TxtNoLabel" onkeydown="return alphaOnlySY();" readonly>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>
                            <div class="One-Info">
                                <div class="IDPicture">
                                    <img id="IDPic" alt="Student ID Image" src="../images/id picture.webp"/>
                                </div>
                            </div>
                            <div class="Two-Info">
                                <div class="StudentIDNumber">
                                    <label for="TxtStudentIDNumber">Student ID Number</label> <span id="req">*</span>
                                    <input name="TxtStudentIDNumber" type="text" id="TxtStudentIDNumber" onkeypress="return isNumberKey(this,event)" style="background-color: white;" required maxlength="7">
                                </div>
                                <div class="StudentImage">
                                    <label for="TxtStudentImage">Student Image</label><br>
                                    <input name="TxtStudentImage" onchange="LoadImage(this);" accept="Image/*" type="file" id="TxtStudentImage" disabled>
                                </div>
                            </div>
                            <div class="Two-Info">
                                <div class="Status">
                                    <label for="RadStatus">Status</label><span id="req">*</span><br> 
                                    <label class="SecStatus">
                                        <input type="radio" class="RadStatus" id="RadNew" name="RadStatus" value="New" onclick="clickedNew()">
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
                                <div class="StudentCategory">
                                    <label for="TxtStudentCategory">Category</label><span id="req">*</span><br>
                                    <select id="TxtStudentCategory" name="TxtStudentCategory" onchange="changeFunc();" disabled>
                                        <option style="display:none"></option>
                                        <option id="elementary" value="Elementary">Elementary</option>
                                        <option id="highschool" value="Highschool">Highschool</option>
                                        <option id="senior" value="Senior Highschool">Senior Highschool</option>
                                        <option id="college" value="College">College</option>
                                    </select>
                                </div>
                            </div>
                            <div class="One-Info">
                                <div class="Course" id="Cour">
                                    <label id="CS" for="TxtCourse">Course</label> <span id="req">*</span>
                                    <input id="TCourse" list="TxtCourse" name="TxtCourse" onkeydown="return alphaOnly(event);" disabled>
                                    <datalist id="TxtCourse">
                                        <option id="CO1" value=""></option>
                                        <option id="CO2" value=""></option>
                                        <option id="CO3" value=""></option>
                                        <option id="CO4" value=""></option>
                                        <option id="CO5" value=""></option>
                                        <option id="CO6" value=""></option>
                                        <option id="CO7" value=""></option>
                                        <option id="CO8" value=""></option>
                                        <option id="CO9" value=""></option>
                                        <option id="CO10" value=""></option>
                                        <option id="CO11" value=""></option>
                                        <option id="CO12" value=""></option>
                                        <option id="CO13" value=""></option>
                                        <option id="CO14" value=""></option>
                                        <option id="CO15" value=""></option>
                                        <option id="CO16" value=""></option>
                                        <option id="CO17" value=""></option>
                                        <option id="CO18" value=""></option>
                                        <option id="CO19" value=""></option>
                                        <option id="CO20" value=""></option>
                                        <option id="CO21" value=""></option>
                                        <option id="CO22" value=""></option>
                                        <option id="CO23" value=""></option>
                                        <option id="CO24" value=""></option>
                                        <option id="CO25" value=""></option>
                                        <option id="CO26" value=""></option>
                                        <option id="CO27" value=""></option>
                                        <option id="CO28" value=""></option>
                                        <option id="CO29" value=""></option>
                                        <option id="CO30" value=""></option>
                                        <option id="CO31" value=""></option>
                                        <option id="CO32" value=""></option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="Two-Info">
                                <div class="Year">
                                    <label id="YR" for="TxtYear">Year</label>  <span id="req">*</span>
                                    <input id="TYear" list="TxtYear" name="TxtYear" onkeypress="return isNumberKey(this,event)" required disabled>
                                    <datalist id="TxtYear">
                                        <option id="YO1" value=""></option>
                                        <option id="YO2" value=""></option>
                                        <option id="YO3" value=""></option>
                                        <option id="YO4" value=""></option>
                                        <option id="YO5" value=""></option>
                                        <option id="YO6" value=""></option>
                                    </datalist>
                                </div>
                                <div class="Section">
                                    <label for="TxtSection">Section</label> 
                                    <input id="TSection" list="TxtSection" name="TxtSection" onkeydown="return alphaOnly(event);" disabled >
                                    <datalist id="TxtSection">
                                        <option id="SO1" value=""></option>
                                        <option id="SO2" value=""></option>
                                        <option id="SO3" value=""></option>
                                        <option id="SO4" value=""></option>
                                        <option id="SO5" value=""></option>
                                        <option id="SO6" value=""></option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="One-Info">
                                <legend>Personal Information</legend>
                            </div>
                            <div class="Four-Info">
                                    <div class="Lastname">
                                        <label for="TxtLastname">Last Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtLastname" id="TxtLastname" onkeydown="return allowLetterNumber(event);" required minlength="2" readonly>
                                    </div>
                                    <div class="Firstname">
                                        <label for="TxtFirstname">First Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtFirstname" id="TxtFirstname" onkeydown="return allowLetterNumber(event);" onchange="checkIfNameEqual()" required minlength="2" readonly>
                                    </div>
                                    <div class="Middlename">
                                        <label for="TxtMiddlename">Middle Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtMiddlename" id="TxtMiddlename" onkeydown="return allowLetterNumber(event);" onchange="checkIfNameEqual()" required minlength="2" readonly>
                                    </div>
                                    <div class="Extension">
                                        <label for="TxtExtension">Extension Name</label>
                                        <input type="text" name="TxtExtension" id="TxtExtension" onkeydown="return alphaName(event);" onchange="checkIfNameEqual()" maxlength="3" readonly>
                                    </div>
                            </div>
                            <div class="Three-Info">
                                    <div class="Birthdate">
                                        <label for="TxtBirthdate">Birthdate</label> <span id="req">*</span>
                                        <input type="date" name="TxtBirthdate" id="TxtBirthdate" onchange="ageCalculator()"  required readonly>
                                    </div>
                                    <div class="Age"> 
                                        <label for="TxtAge">Age</label>
                                        <input type="number" name="TxtAge" id="TxtAge" onkeypress="return isNumberKey(this,event)" readonly>
                                    </div>
                                    <div class="Sex"> 
                                        <label for="RadSex">Sex</label><span id="req">*</span><br>
                                        <label class="SecSex">
                                            <input type="radio" class="RadSex" id="RadMale" name="RadSex" value="Male"  required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Male</span>
                                        </label>
                                        &nbsp
                                        <label class="SecSex">
                                            <input type="radio" class="RadSex" id="RadFemale" name="RadSex" value="Female" required disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Female</span>
                                        </label>
                                    </div>
                            </div>
                            <div class="Two-Info">
                                    <div class="Address"> 
                                        <label for="TxtAddress">Address</label> <span id="req">*</span>
                                        <input type="text" name="TxtAddress" id="TxtAddress" required readonly>
                                    </div>
                                    <div class="StudentContactNumber">
                                        <label for="TxtStudentContactNumber">Student Contact Number</label>
                                        <input type="text" name="TxtStudentContactNumber" id="TxtStudentContactNumber" onchange="checkContNumFormat(this)" onkeypress="return isNumberKey(this,event)" minlength="13" maxlength="13" readonly>
                                    </div>
                            </div>
                            <div class="One-Info">
                                <legend>Guardian/Parent Information</legend>
                            </div>
                            <div class="Two-Info">
                                <div class="GuardianParent">
                                    <label for="RadGuardianParent"></label>
                                    <label class="SecGuardianParent"> <br>
                                        <input type="radio" class="RadGuardianParent" id="RadGuardian" name="RadGuardianParent" value="Guardian" onclick="clickedGuardian()" disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Guardian</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent"> 
                                        <input type="radio" class="RadGuardianParent" id="RadParent" name="RadGuardianParent"value="Parent" onclick="clickedParent()"  disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Parent</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent"> 
                                        <input type="radio" class="RadGuardianParent" id="RadNone" name="RadGuardianParent" value="None" onclick="clickedNone()" checked disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">None</span>
                                    </label>
                                </div>
                                    <div class="GPCategory">
                                        <label for="TxtGPCategory">Category</label>
                                        <input id="TGPCategory" list="TxtGPCategory" name="TxtGPCategory"  onkeydown="return alphaOnly(event);"  disabled>
                                        <datalist id="TxtGPCategory">
                                            <option id="father" value=""></option>
                                            <option id="mother" value=""></option>
                                            <option id="sibling" value=""></option>
                                            <option id="grandparents" value=""></option>
                                            <option id="ward" value=""></option>
                                        </datalist>
                                    </div>
                                </nobr>
                            </div>
                            <div class="Two-Info">
                                    <div class="ContactPerson"> 
                                        <label for="TxtContactPerson">Contact Person</label>
                                        <input type="text" name="TxtContactPerson" id="TxtContactPerson" onkeydown="return alphaOnlyCP(event);" placeholder="Last Name, First Name"  readonly>
                                    </div>
                                    <div class="PGContactNumber">
                                        <label for="TxtPGContactNumber">Contact Number of Parent/Guardian</label> 
                                        <input type="text" name="TxtPGContactNumber" id="TxtPGContactNumber" onchange="checkContNumFormat(this)" onkeypress="return isNumberKey(this,event)" minlength="13" maxlength="13"  readonly>
                                    </div>
                            </div>
                            <hr id="addMoreForm" style="display: none;">
                            <div class="Two-Info" id="addMoreForm1" style="display: none;">
                                <div class="GuardianParent">
                                    <label for="RadGuardianParent1"></label>
                                    <label class="SecGuardianParent1"> <br>
                                        <input type="radio" class="RadGuardianParent1" id="RadGuardian1" name="RadGuardianParent1" value="Guardian" onclick="clickedGuardian1()" disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Guardian</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent1"> 
                                        <input type="radio" class="RadGuardianParent1" id="RadParent1" name="RadGuardianParent1" value="Parent" onclick="clickedParent1()" disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Parent</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent1"> 
                                        <input type="radio" class="RadGuardianParent1" id="RadNone1" name="RadGuardianParent1" value="None" onclick="clickedNone1()" checked disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">None</span>
                                    </label>
                                </div>
                                    <div class="GPCategory">
                                        <label for="TxtGPCategory1">Category</label> 
                                        <input id="TGPCategory1" list="TxtGPCategory1" name="TxtGPCategory1"  onkeydown="return alphaOnly(event);" disabled>
                                        <datalist id="TxtGPCategory1">
                                            <option id="father1" value=""></option>
                                            <option id="mother1" value=""></option>
                                            <option id="sibling1" value=""></option>
                                            <option id="grandparents1" value=""></option>
                                            <option id="ward1" value=""></option>
                                        </datalist>
                                    </div>
                                </nobr>
                            </div>
                            <div class="Two-Info" id="addMoreForm2" style="display: none;">
                                    <div class="ContactPerson"> 
                                        <label for="TxtContactPerson1">Contact Person</label> 
                                        <input type="text" name="TxtContactPerson1" id="TxtContactPerson1" onkeydown="return alphaOnlyCP(event);" placeholder="Last Name, First Name" readonly>
                                    </div>
                                    <div class="PGContactNumber">
                                        <label for="TxtPGContactNumber1">Contact Number of Parent/Guardian</label> 
                                        <input type="text" name="TxtPGContactNumber1" id="TxtPGContactNumber1" onkeypress="return isNumberKey(this,event)" onchange="checkContNumFormat(this)" minlength="13" maxlength="13" readonly>
                                    </div>
                            </div>
                            <span id="addMore" onclick="showAddMore()">+ Add More</span>
                            <div id="exportButton" data-html2canvas-ignore="true">
                                <div class="submit">
                                    <button type="button" id ="BtnPrint" class=form-button onclick="clickedPrint('BtnPrint')"><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="button" id ="BtnPDF" class=form-button onclick="clickedPDF()"><p>Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                                </div>
                            </div>
                            <div id="twoButton" data-html2canvas-ignore="true">
                                <div class="submit">
                                    <button type="button" id ="BtnAdd" class=form-button name="BTN" onclick="changeCSS()" disabled><p>Next</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><line x1="55.78" y1="32.63" x2="10.33" y2="32.63"/><polyline points="38.55 14.63 55.78 32.79 38.55 49.32"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="button" id="BtnEdit" class=form-button onclick="clickedEdit()" disabled><p>Edit</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><polyline points="45.56 46.83 45.56 56.26 7.94 56.26 7.94 20.6 19.9 7.74 45.56 7.74 45.56 21.29"/><polyline points="19.92 7.74 19.9 20.6 7.94 20.6"/><line x1="13.09" y1="47.67" x2="31.1" y2="47.67"/><line x1="13.09" y1="41.14" x2="29.1" y2="41.14"/><line x1="13.09" y1="35.04" x2="33.1" y2="35.04"/><line x1="13.09" y1="28.94" x2="39.1" y2="28.94"/><path d="M34.45,43.23l.15,4.3a.49.49,0,0,0,.62.46l4.13-1.11a.54.54,0,0,0,.34-.23L57.76,22.21a1.23,1.23,0,0,0-.26-1.72l-3.14-2.34a1.22,1.22,0,0,0-1.72.26L34.57,42.84A.67.67,0,0,0,34.45,43.23Z"/><line x1="50.2" y1="21.7" x2="55.27" y2="25.57"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="Submit" id="BtnSave" class=form-button name="BTN" onclick="btnValue('save')" disabled><p>Save</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><path d="M51,53.48H10.52V13A2.48,2.48,0,0,1,13,10.52H46.07l7.41,6.4V51A2.48,2.48,0,0,1,51,53.48Z" stroke-linecap="round"/><rect x="21.5" y="10.52" width="21.01" height="15.5" stroke-linecap="round"/><rect x="17.86" y="36.46" width="28.28" height="17.02" stroke-linecap="round"/></svg></button>
                                </div>
                                <div class="submit">
                                    <button type="button" id="BtnClear" class=form-button onclick="clearPersonal()" disabled><p>Clear</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><line x1="8.06" y1="8.06" x2="55.41" y2="55.94"/><line x1="55.94" y1="8.06" x2="8.59" y2="55.94"/></svg></button>
                                </div>
                            </div>
                    </div>
                    <div class="tabs-content" id="content1">
                            <div class="One-Info">
                                <div id="StudentInfo">
                                    <legend>Student</legend>
                                    <span id="TxtStudentIDNumber1">ID Number:</span><br>
                                    <span id="TxtStudentFullName">Full Name:</span>
                                </div>
                            </div>
                            <div class="Two-Info">
                                <div class="Date">
                                    <label for="TxtDate">Date</label><span id="req">*</span>
                                    <input type="date" name="TxtDate" id="TxtDate" onchange="checkDate()" required readonly>
                                </div>

                                <div class="Date">
                                    <label for="TxtTime">Time</label><span id="req">*</span>
                                    <input type="Time" name="TxtTime" id="TxtTime" required readonly>
                                </div>
                            
                            </div>
                            
                        <!-- <div class="One-Info" id="MSFields1">
                            <legend>Medical Staff</legend>
                        </div>
                        <div class="One-Info" id="MSFields2">
                                <div class="StaffIDNumber">
                                    <label for="TxtStaffIDNumber">Staff ID Number</label>
                                    <input type="number" name="TxtStaffIDNumber" id="TxtStaffIDNumber" onkeypress="return isNumberKey(this,event)" readonly>
                                </div>
                        </div>
                        <div class="Four-Info" id="MSFields3">
                                <div class="StaffLastname">
                                    <label for="TxtStaffLastname">Last Name</label>
                                    <input type="text" name="TxtStaffLastname" id="TxtStaffLastname" onkeydown="return alphaOnly(event);" readonly>
                                </div>
                                <div class="StaffFirstname">
                                    <label for="TxtStaffFirstname">First Name</label>
                                    <input type="text" name="TxtStaffFirstname" id="TxtStaffFirstname" onkeydown="return alphaOnly(event);" readonly>
                                </div>
                                <div class="StaffMiddlename">
                                    <label for="TxtStaffMiddlename">Middle Name</label>
                                    <input type="text" name="TxtStaffMiddlename" id="TxtStaffMiddlename" onkeydown="return alphaOnly(event);" readonly>
                                </div>
                                <div class="StaffExtension">
                                    <label for="TxtStaffExtension">Extension Name</label>
                                    <input type="text" name="TxtStaffExtension" id="TxtStaffExtension" onkeydown="return alphaOnly(event);" readonly>
                                </div> 
                        </div> -->
                        <div class="One-Info">
                            <legend>Medical History</legend>
                        </div>
                        <div class="Three-Info">
                                <div class="LMP">
                                    <label for="TxtLMP">LMP</label>
                                    <input type="text" name="TxtLMP" id="TxtLMP" readonly>
                                </div>
                                <div class="Pregnancy">
                                    <label for="TxtPregnancy">Pregnancy</label>
                                    <input type="text" name="TxtPregnancy" id="TxtPregnancy" readonly>
                                </div>
                                <div class="Allergies">
                                    <label for="TxtAllergies">Allergies</label>
                                    <input type="text" name="TxtAllergies" id="TxtAllergies" readonly>
                                </div> 
                        </div>
                        <div class="Three-Info">
                                <div class="Surgeries">
                                    <label for="TxtSurgeries">Surgeries</label>
                                    <input type="text" name="TxtSurgeries" id="TxtSurgeries" readonly>
                                </div>
                                <div class="Injuries">
                                    <label for="TxtInjuries">Injuries</label>
                                    <input type="text" name="TxtInjuries" id="TxtInjuries" readonly>
                                </div>
                                <div class="Illness">
                                    <label for="TxtIllness">Illness</label>
                                    <input type="text" name="TxtIllness" id="TxtIllness" readonly>
                                </div> 
                        </div>
                        <div class="One-Info">
                            <legend>Physical Examination</legend>
                        </div>
                        <table id="tableMedicalInfo">
                                <tr>
                                    <td class="SchoolYear">
                                        <label for="TxtSchoolYear"> 
                                            School Year
                                        </label> 
                                    </td>
                                    <td class="secondColumn">
                                        <input type="text" name="TxtSchoolYear" id="TxtSchoolYear" onkeydown="return alphaOnlySY();"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Height">
                                        <label for="TxtHeight">
                                            Height in cm
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="number" name="TxtHeight" id="TxtHeight" onkeypress="return isNumberKey(this,event)" step="any" onchange="calculateBMI()"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Weight">
                                        <label for="TxtWeight">
                                            Weight in kg
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="number" name="TxtWeight" id="TxtWeight" onkeypress="return isNumberKey(this,event)" step="any" onchange="calculateBMI()"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="BMI">
                                        <label for="TxtBMI">
                                            BMI
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="text" name="TxtBMI" id="TxtBMI"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="BloodPressure">
                                        <label for="TxtBloodPressure">
                                            Blood Pressure
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="text" name="TxtBloodPressure" id="TxtBloodPressure"  readonly>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td class="PulseRate">
                                        <label for="TxtPulseRate">
                                            Pulse Rate
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="number" name="TxtPulseRate" id="TxtPulseRate" onkeypress="return isNumberKey(this,event)"  readonly>
                                    </td>
                                </tr>

                                 <tr>
                                    <td class="Temperature">
                                        <label for="TxtTemperature">
                                            Temperature in °C
                                        </label> 
                                    </td>
                                    <td>
                                        <input type="text" name="TxtTemperature" id="TxtTemperature" onkeypress="return isNumberKey(this,event)" step="any"  readonly>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="VisionWithoutGlasses">
                                        <label for="TxtVisionWithoutGlasses">
                                            Vision (Snellen's) without glasses
                                        </label> 
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithoutGlassesOD"></label>
                                        <input type="text" name="TxtVisionWithoutGlassesOD" id="TxtVisionWithoutGlassesOD" placeholder="OD" readonly >
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithoutGlassesOS"></label>
                                        <input type="text" name="TxtVisionWithoutGlassesOS" id="TxtVisionWithoutGlassesOS" placeholder="OS" readonly >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="VisionWithGlasses">
                                        <label for="TxtVisionWithGlasses">
                                            Vision (Snellen's) with glasses
                                        </label>
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithGlassesOD"></label> 
                                        <input type="text" name="TxtVisionWithGlassesOD" id="TxtVisionWithGlassesOD" placeholder="OD" readonly >
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithGlassesOS"></label> 
                                        <input type="text" name="TxtVisionWithGlassesOS" id="TxtVisionWithGlassesOS" placeholder="OS" readonly >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="HearingDistance">
                                        <label for="TxtHearingDistanceOption">
                                            Hearing Distance
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtHearingDistanceOption" name="TxtHearingDistanceOption" onchange="showTA('TxtHearingDistanceOption','TAHearingDistance');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableHD" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsHD" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHearingDistance"></label>
                                        <textarea type="text" name="TAHearingDistance" id="TAHearingDistance" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Speech">
                                        <label for="TxtSpeechOption">
                                            Speech
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtSpeechOption" name="TxtSpeechOption" onchange="showTA('TxtSpeechOption','TASpeech');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableSp" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsSp" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TASpeech"></label>
                                        <textarea type="text" name="TASpeech" id="TASpeech" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Skin">
                                        <label for="TxtSkinOption">
                                            Skin
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtSkinOption" name="TxtSkinOption" onchange="showTA('TxtSkinOption','TASkin');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableSk" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsSk" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TASkin"></label>
                                        <textarea type="text" name="TASkin" id="TASkin" oninput="auto_grow(this)" cols="0" rows="0"  readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Head">
                                        <label for="TxtHeadOption">
                                            Head
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtHeadOption" name="TxtHeadOption" onchange="showTA('TxtHeadOption','TAHead');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableHe" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsHe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHead"></label>
                                        <textarea type="text" name="TAHead" id="TAHead" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Eyes">
                                        <label for="TxtEyesOption">
                                            Eyes (Conjunctiva)
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtEyesOption" name="TxtEyesOption" onchange="showTA('TxtEyesOption','TAEyes');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableEy" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsEy" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAEyes"></label>
                                        <textarea type="text" name="TAEyes" id="TAEyes" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Ears">
                                        <label for="TxtEarsOption">
                                            Ears
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtEarsOption" name="TxtEarsOption" onchange="showTA('TxtEarsOption','TAEars');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableEa" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsEa" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAEars"></label>
                                        <textarea type="text" name="TAEars" id="TAEars" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Nose">
                                        <label for="TxtNoseOption">
                                            Nose
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtNoseOption" name="TxtNoseOption" onchange="showTA('TxtNoseOption','TANose');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableNo" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsNo" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TANose"></label>
                                        <textarea type="text" name="TANose" id="TANose" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="CavityAndThroat">
                                        <label for="TxtCavityAndThroatOption">
                                            Buccal Cavity and Throat
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtCavityAndThroatOption" name="TxtCavityAndThroatOption" onchange="showTA('TxtCavityAndThroatOption','TACavityAndThroat');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableCT" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsCT" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TACavityAndThroat"></label>
                                        <textarea type="text" name="TACavityAndThroat" id="TACavityAndThroat" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Lungs">
                                        <label for="TxtLungsOption">
                                            Thorax: Lungs
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtLungsOption" name="TxtLungsOption" onchange="showTA('TxtLungsOption','TALungs');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableLu" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsLu" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TALungs"></label>
                                        <textarea type="text" name="TALungs" id="TALungs" oninput="auto_grow(this)" cols="0" rows="0"  readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Heart">
                                        <label for="TxtHeartOption">
                                            Thorax: Heart
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtHeartOption" name="TxtHeartOption" onchange="showTA('TxtHeartOption','TAHeart');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableHea" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsHea" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHeart"></label>
                                        <textarea type="text" name="TAHeart" id="TAHeart" oninput="auto_grow(this)" cols="0" rows="0"  readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Breast">
                                        <label for="TxtBreastOption">
                                            Thorax: Breast
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtBreastOption" name="TxtBreastOption" onchange="showTA('TxtBreastOption','TABreast');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableBr" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsBr" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TABreast"></label>
                                        <textarea type="text" name="TABreast" id="TABreast" oninput="auto_grow(this)" cols="0" rows="0"  readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Abdomen">
                                        <label for="TxtAbdomenOption">
                                            Abdomen
                                        </label>
                                    </td>
                                    <td>
                                        <select id="TxtAbdomenOption" name="TxtAbdomenOption" onchange="showTA('TxtAbdomenOption','TAAbdomen');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableAb" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsAb" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAAbdomen"></label>
                                        <textarea type="text" name="TAAbdomen" id="TAAbdomen" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="GenitoUrinary">
                                        <label for="TxtGenitoUrinaryOption">
                                            Genito-urinary
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtGenitoUrinaryOption" name="TxtGenitoUrinaryOption" onchange="showTA('TxtGenitoUrinaryOption','TAGenitoUrinary');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableGU" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsGU" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAGenitoUrinary"></label>
                                        <textarea type="text" name="TAGenitoUrinary" id="TAGenitoUrinary" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="LymphGlands">
                                        <label for="TxtLymphGlandsOption">
                                            Lymph nodes
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtLymphGlandsOption" name="TxtLymphGlandsOption" onchange="showTA('TxtLymphGlandsOption','TALymphGlands');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableLG" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsLG" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TALymphGlands"></label>
                                        <textarea type="text" name="TALymphGlands" id="TALymphGlands" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Extremities">
                                        <label for="TxtExtremitiesOption">
                                            Extremities
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtExtremitiesOption" name="TxtExtremitiesOption" onchange="showTA('TxtExtremitiesOption','TAExtremities');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableEx" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsEx" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAExtremities"></label>
                                        <textarea type="text" name="TAExtremities" id="TAExtremities" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Deformities">
                                        <label for="TxtDeformitiesOption">
                                            Deformities
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtDeformitiesOption" name="TxtDeformitiesOption" onchange="showTA('TxtDeformitiesOption','TADeformities');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableDe" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsDe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TADeformities"></label>
                                        <textarea type="text" name="TADeformities" id="TADeformities" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="RadiologicExams">
                                        <label for="TxtRadiologicExamsOption">
                                            Laboratory Exams: Radiologic Exams
                                        </label>
                                    </td>
                                    <td>
                                        <select id="TxtRadiologicExamsOption" name="TxtRadiologicExamsOption" onchange="showTA('TxtRadiologicExamsOption','TARadiologicExams');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableRE" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsRE" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TARadiologicExams"></label>
                                        <textarea type="text" name="TARadiologicExams" id="TARadiologicExams" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="BloodAnalysis">
                                        <label for="TxtBloodAnalysisOption">
                                            Laboratory Exams: Blood Analysis (CBC)
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtBloodAnalysisOption" name="TxtBloodAnalysisOption" onchange="showTA('TxtBloodAnalysisOption','TABloodAnalysis');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableBA" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsBA" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TABloodAnalysis"></label>
                                        <textarea type="text" name="TABloodAnalysis" id="TABloodAnalysis" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Urinalysis">
                                        <label for="TxtUrinalysisOption">
                                            Laboratory Exams: Urinalysis
                                        </label>
                                    </td>
                                    <td>
                                        <select id="TxtUrinalysisOption" name="TxtUrinalysisOption" onchange="showTA('TxtUrinalysisOption','TAUrinalysis');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableUr" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsUr" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAUrinalysis"></label>
                                        <textarea type="text" name="TAUrinalysis" id="TAUrinalysis" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Fecalysis">
                                        <label for="TxtFecalysisOption">
                                            Laboratory Exams: Fecalysis
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtFecalysisOption" name="TxtFecalysisOption" onchange="showTA('TxtFecalysisOption','TAFecalysis');" disabled>
                                            <option style="display:none"></option>
                                            <option id="unremarkableFe" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsFe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAFecalysis"></label>
                                        <textarea type="text" name="TAFecalysis" id="TAFecalysis" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="PregnancyTest">
                                        <label for="TxtPregnancyTestOption">
                                            Laboratory Exams: Pregnancy Test
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtPregnancyTestOption" name="TxtPregnancyTestOption" onchange="showTA('TxtPregnancyTestOption','TAPregnancyTest');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkablePT" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsPT" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAPregnancyTest"></label>
                                        <textarea type="text" name="TAPregnancyTest" id="TAPregnancyTest" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="HBSAg">
                                        <label for="TxtHBSAgOption">
                                            Laboratory Exams: HBSAg
                                        </label> 
                                    </td>
                                    <td>
                                        <select id="TxtHBSAgOption" name="TxtHBSAgOption" onchange="showTA('TxtHBSAgOption','TAHBSAg');" disabled >
                                            <option style="display:none"></option>
                                            <option id="unremarkableHB" value="Unremarkable">Unremarkable</option>
                                            <option id="wFindingsHB" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHBSAg"></label>
                                        <textarea type="text" name="TAHBSAg" id="TAHBSAg" oninput="auto_grow(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Others">
                                        <label for="TxtOthers">
                                            Others
                                        </label>
                                    </td>
                                    <td>
                                        <label for="TxtOthers"></label>
                                        <textarea type="text" name="TxtOthers" id="TxtOthers" oninput="auto_growTextArea(this)" cols="0" rows="0" readonly></textarea>
                                    </td>
                                </tr>
                        </table>
                        <div class="One-Info" id="pageBreak1">
                            <div class="Remarks" id="RemarksID">
                                <label for="TxtRemarks">Remarks</label> 
                                <textarea type="text" name="TxtRemarks" id="TxtRemarks" oninput="auto_growTextArea(this)" cols="83" rows="10" readonly></textarea>
                            </div>
                        </div>
                        <div class="One-Info" id="pageBreak2">
                            <div class="Recommendation" id="RecommendationID">
                                <label for="TxtRecommendation">Recommendation</label> 
                                <textarea type="text" name="TxtRecommendation" id="TxtRecommendation" oninput="auto_growTextArea(this)" cols="76" rows="10" readonly></textarea>
                            </div>
                        </div>
                        <div class="One-Info">
                            <div id="MedicalStaffInfo">
                                <legend>Medical Staff</legend>
                                <span id="TxtMSIDNumber1">ID Number:</span><br>
                                <span id="TxtMSFullName">Charted By:</span>
                            </div>
                            <div id="ExaminedBy">
                                    <span id="TxtExaminedBy">Examined By:</span><br>
                            </div>
                        </div>
                        <div id="exportButton1" data-html2canvas-ignore="true">
                            <div class="submit">
                                <button type="button" id ="BtnPrint1" class=form-button onclick="clickedPrint()"><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg></button>
                            </div>
                            <div class="submit">
                                <button type="button" id ="BtnPDF1" class=form-button onclick="clickedPDF()"><p>Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                            </div>
                        </div>
                        <div id="twoButton1" data-html2canvas-ignore="true">
                            <div class="submit">
                                <button type="Submit" id ="BtnAdd1" class=form-button name="BTN" onclick="btnValue('add')" disabled><p>Add Record</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><polyline points="34.48 54.28 11.06 54.28 11.06 18.61 23.02 5.75 48.67 5.75 48.67 39.42"/><polyline points="23.04 5.75 23.02 18.61 11.06 18.61"/><line x1="16.21" y1="45.68" x2="28.22" y2="45.68"/><line x1="16.21" y1="39.15" x2="31.22" y2="39.15"/><line x1="16.21" y1="33.05" x2="43.22" y2="33.05"/><line x1="16.21" y1="26.95" x2="43.22" y2="26.95"/><circle cx="42.92" cy="48.24" r="10.01" stroke-linecap="round"/><line x1="42.92" y1="42.76" x2="42.92" y2="53.72"/><line x1="37.45" y1="48.24" x2="48.4" y2="48.24"/></svg></button>
                            </div>
                            <div class="submit">
                                <button type="button" id="BtnEdit1" class=form-button onclick="clickedEdit()" disabled><p>Edit</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><polyline points="45.56 46.83 45.56 56.26 7.94 56.26 7.94 20.6 19.9 7.74 45.56 7.74 45.56 21.29"/><polyline points="19.92 7.74 19.9 20.6 7.94 20.6"/><line x1="13.09" y1="47.67" x2="31.1" y2="47.67"/><line x1="13.09" y1="41.14" x2="29.1" y2="41.14"/><line x1="13.09" y1="35.04" x2="33.1" y2="35.04"/><line x1="13.09" y1="28.94" x2="39.1" y2="28.94"/><path d="M34.45,43.23l.15,4.3a.49.49,0,0,0,.62.46l4.13-1.11a.54.54,0,0,0,.34-.23L57.76,22.21a1.23,1.23,0,0,0-.26-1.72l-3.14-2.34a1.22,1.22,0,0,0-1.72.26L34.57,42.84A.67.67,0,0,0,34.45,43.23Z"/><line x1="50.2" y1="21.7" x2="55.27" y2="25.57"/></svg></button>
                                </div>
                                <div class="submit">
                                <button type="Submit" id="BtnSave1" class=form-button name="BTN" onclick="btnValue('save')" disabled><p>Save</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" stroke-width="5" class="button__svg" stroke="currentColor" fill="none"><path d="M51,53.48H10.52V13A2.48,2.48,0,0,1,13,10.52H46.07l7.41,6.4V51A2.48,2.48,0,0,1,51,53.48Z" stroke-linecap="round"/><rect x="21.5" y="10.52" width="21.01" height="15.5" stroke-linecap="round"/><rect x="17.86" y="36.46" width="28.28" height="17.02" stroke-linecap="round"/></svg></button>
                                </div>
                            <div class="submit">
                                <button type="button" id="BtnClear1" class=form-button onclick="clearMedical()" disabled><p>Clear</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><line x1="8.06" y1="8.06" x2="55.41" y2="55.94"/><line x1="55.94" y1="8.06" x2="8.59" y2="55.94"/></svg></button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="../js/script-tab.js"></script>
    </body>
</html>

<?php


    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";

    if($_SESSION["typed"] == 'checkArchivedStudent'){
        $tempor = "checkArchived";
    }else{
        $tempor = "checkRecord";
    }

     echo "<script type='text/javascript'>
        globalAL = '$tempo';
        editTableNav('$tempor');
    </script>";


    $id = "";

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "newRecord"){
            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'none';
            document.getElementById('ExaminedBy').style.display = 'none';
            document.getElementById('addMore').style.display = 'none';
            getType = 'viewRecord';
            </script>";
        }else if($_GET["type"] == "viewRecord"){
            if(!isset($_GET["id"])){
                header("location: ../index.php");
                exit;
            }

            $id = $_GET["id"];

            $sql = "SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber=$id";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();

            if(!$Row){
                header("location: ../index.php");
                exit;
            }

            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
            document.getElementById('ExaminedBy').style.display = 'inline-block';
            document.getElementById('addMore').style.display = 'none';
            document.getElementById('BtnClear').style.display = 'none';
            document.getElementById('BtnClear1').style.display = 'none';
            getType = 'viewRecord';
            passIDPHP($id);
            </script>";
        }else if($_GET["type"] == "viewArchivedRecord"){
            if(!isset($_GET["id"])){
                header("location: ../index.php");
                exit;
            }

            $id = $_GET["id"];

            $sql = "SELECT * FROM ARCHIVEDSTUDENT WHERE StudentIDNumber=$id";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();

            if(!$Row){
                header("location: ../index.php");
                exit;
            }

            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
            document.getElementById('ExaminedBy').style.display = 'inline-block';
            document.getElementById('addMore').style.display = 'none';
            document.getElementById('BtnClear').style.display = 'none';
            document.getElementById('BtnClear1').style.display = 'none';
            getType = 'viewArchivedRecord';
            passIDPHP($id);
            </script>";
        }    
    }
?>
