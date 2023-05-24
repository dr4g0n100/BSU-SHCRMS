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

$querySenior ="SELECT * FROM db_degree_list WHERE degree_category = 'senior highschool'";  
$resultSenior = mysqli_query($connect, $querySenior);

$queryCollege ="SELECT * FROM db_degree_list WHERE degree_category = 'college'";  
$resultCollege = mysqli_query($connect, $queryCollege);

$queryGrad ="SELECT * FROM db_degree_list WHERE degree_category = 'graduate'";  
$resultGrad = mysqli_query($connect, $queryGrad);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Student</title>
        
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies1.php'; ?>

        <link rel="stylesheet" href="../css/addStudent-style.css">

        <script type="text/javascript">

                // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var getType ="";
            var globalAL = "";
            var imgSrc = "";
            var id_stud = "";

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
                        }, 2000);
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

            function autofetchDate(){
                //remove comment to enable autofill date based on system date
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                var thisDay = yyyy + '-' + mm + '-' + dd;

                //auto date time set
                var hh = today.getHours();
                hh = (hh < 10 ? "0" : "") + hh;
                var mm = today.getMinutes();
                mm = (mm < 10 ? "0" : "") + mm;

                var thisTime = hh + ":" + mm;

                $('#TxtDate').val(thisDay);
                $('#TxtTime').val(thisTime);
            }

            function fetchName(){
                var result = false;
                var temp = document.getElementById('TxtStudentIDNumber').value;
                
                var form_data = new FormData();
                form_data.append("temp", temp);

                $.ajax(
                { 
                    url:"../php/Student/FetchName.php",
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

                                   $('#TxtStudentIDNumber').val('');
                                   clearPersonalMedical();
                                   disableEditing();
                                   modifyVisibleButton('none');
                                    
                            }else{
                                clearPersonalMedical();
                                enableEditing();
                                modifyVisibleButton('add');

                                document.getElementById('TxtStudentFullName').innerHTML = 'Full Name:';
                                document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number:';
                                document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number:';
                                document.getElementById('TxtMSFullName').innerHTML = '';
                                var MSHistory = document.getElementById('TxtMSEditorDrop');
                                MSHistory.options.length = 0;

                                //auto date time set

                                autofetchDate();

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
                $('#TxtMedicalOthers').val('');
                $('#TxtRLOA').val('');
                $('#TxtSchoolYear').val('');
                $('#TxtStudentTerm').val('');
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
                $('#TxtVisionWithContLensOD').val('');
                $('#TxtVisionWithContLensOS').val('');
                $('#TxtHearingDistanceOption').val('Unremarkable');
                $('#TxtSpeechOption').val('Unremarkable');
                $('#TxtEyesOption').val('Unremarkable');
                $('#TxtEarsOption').val('Unremarkable');
                $('#TxtNoseOption').val('Unremarkable');
                $('#TxtHeadOption').val('Unremarkable');
                $('#TxtAbdomenOption').val('Unremarkable');
                $('#TxtGenitoUrinaryOption').val('Unremarkable');
                $('#TxtLymphGlandsOption').val('Unremarkable');
                $('#TxtSkinOption').val('Unremarkable');
                $('#TxtExtremitiesOption').val('Unremarkable');
                $('#TxtDeformitiesOption').val('Unremarkable');
                $('#TxtCavityAndThroatOption').val('Unremarkable');
                $('#TxtLungsOption').val('Unremarkable');
                $('#TxtHeartOption').val('Unremarkable');
                $('#TxtBreastOption').val('Unremarkable');
                $('#TxtRadiologicExamsOption').val('Unremarkable');
                $('#TxtBloodAnalysisOption').val('Unremarkable');
                $('#TxtUrinalysisOption').val('Unremarkable');
                $('#TxtFecalysisOption').val('Unremarkable');
                $('#TxtPregnancyTestOption').val('Unremarkable');
                $('#TxtHBSAgOption').val('Unremarkable');

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

                //$('#TxtAddress').val('');
                $('#TxtPresAddHouseNo').val('');
                $('#TxtPresAddStreet').val('');
                $('#TxtPresAddBrgy').val('');
                $('#TxtPresAddMunicipal').val('');
                $('#TxtPresAddProvince').val('');
                //$('#TxtProvAdd').val('');
                $('#TxtProvAddHouseNo').val('');
                $('#TxtProvAddStreet').val('');
                $('#TxtProvAddBrgy').val('');
                $('#TxtProvAddMunicipal').val('');
                $('#TxtProvAddProvince').val('');
                $('#TxtStudentContactNumber').val('+639');

                $('#RadGuardian').prop('checked', false);
                $('#RadParent').prop('checked', false);
                $('#TGPCategory').val('');
                $('#TxtContactPerson').val('');
                $('#TxtPGContactNumber').val('+639');
                $('#RadGuardian1').prop('checked', false);
                $('#RadParent1').prop('checked', false);
                $('#TGPCategory1').val('');
                $('#TxtContactPerson1').val('');
                $('#TxtPGContactNumber1').val('+639');
                $('#RadGuardian2').prop('checked', false);
                $('#RadParent2').prop('checked', false);
                $('#TGPCategory2').val('');
                $('#TxtContactPerson2').val('');
                $('#TxtPGContactNumber2').val('+639');

            }

            function clearPersonalMedical(){
                clearPersonal();
                clearMedical();
                document.getElementById('TxtStudentFullName').innerHTML = 'Full Name:';
                document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSIDNumber1').innerHTML = 'ID Number:';
                document.getElementById('TxtMSFullName').innerHTML = '';

                document.getElementById('MedicalStaffInfo').style.display = 'none';
                document.getElementById('ExaminedBy').style.display = 'none';
                
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

                window.open('../php/print.php?id=' +idNum, '_blank');
    
            }

            function showAddMore(){
                if(getType = 'newRecord'){
                    document.getElementById('addMore').style.display = 'none';
                    document.getElementById('addMoreForm').style.display = 'block';
                    document.getElementById('addMoreForm1').style.display = 'block';
                    document.getElementById('addMoreForm2').style.display = 'block';

                    document.getElementById('addMoreForm1').style.display = 'block';
                    document.getElementById('addMoreForm3').style.display = 'block';
                    document.getElementById('addMoreForm4').style.display = 'block';
                }
                
            }

            

            function styleInput(idnum){
                document.getElementById(idnum).style.background = "none";  
                document.getElementById(idnum).style.borderBottom = "solid 2px black";    
                document.getElementById(idnum).style.borderTop = "solid 1px gray"; 
                document.getElementById(idnum).style.borderRight = "solid 1px gray"; 
                document.getElementById(idnum).style.borderLeft = "solid 1px gray";  
            }

            function styleAllInput(){
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

                //styleInput('TxtAddress');
                styleInput('TxtPresAddHouseNo');
                styleInput('TxtPresAddStreet');
                styleInput('TxtPresAddBrgy');
                styleInput('TxtPresAddMunicipal');
                styleInput('TxtPresAddProvince');

                //styleInput('TxtProvAdd');
                styleInput('TxtProvAddHouseNo');
                styleInput('TxtProvAddStreet');
                styleInput('TxtProvAddBrgy');
                styleInput('TxtProvAddMunicipal');
                styleInput('TxtProvAddProvince');

                styleInput('TxtStudentContactNumber');
                styleInput('TGPCategory');
                styleInput('TxtContactPerson');
                styleInput('TxtPGContactNumber');
                styleInput('TGPCategory1');
                styleInput('TxtContactPerson1');
                styleInput('TxtPGContactNumber1');
                styleInput('TGPCategory2');
                styleInput('TxtContactPerson2');
                styleInput('TxtPGContactNumber2');
                styleInput('TxtDate');
                styleInput('TxtTime');


                styleInput('TxtLMP');
                styleInput('TxtPregnancy');
                styleInput('TxtAllergies');
                styleInput('TxtSurgeries');
                styleInput('TxtInjuries');
                styleInput('TxtIllness');
                styleInput('TxtMedicalOthers');
                styleInput('TxtRLOA');
                styleInput('TxtSchoolYear');
                styleInput('TxtStudentTerm');

                styleInput('TxtHeight');
                styleInput('TxtWeight');
                styleInput('TxtBloodPressure');
                styleInput('TxtTemperature');
                styleInput('TxtPulseRate');
                styleInput('TxtVisionWithoutGlassesOD');
                styleInput('TxtVisionWithoutGlassesOS');
                styleInput('TxtVisionWithGlassesOD');
                styleInput('TxtVisionWithGlassesOS');
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
            }

            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
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

            var TempSex;
            var TempGuardianParent;
            var TempGuardianParent1;
            var TempGuardianParent2;
            var TempBtnValue;

            /* function logout(){
            sessionStorage.clear();
            } */

            function alphaOnlySY(){
                var key = event.keyCode;
                return ((key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 189 || key == 8 || key==13);
            }

            function alphaOnlyCP(){
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 188 || key == 189 || key==13);
            }

            function changeFunc(selectedValue){
                
                $('#TxtCourse').empty();
                $('#TxtYear').empty();
                $('#TxtSection').empty();
                var categoryOption = document.getElementById('TxtCourse');
                var yearOption = document.getElementById('TxtYear');
                var sectionOption = document.getElementById('TxtSection');

                let option_elem = '';
                var yearStart = 0;
                var yearEnd = 0;
                var sections = [];

                if(selectedValue == "elementary"){
                    document.getElementById("Cour").style.display = 'none';
                    //document.getElementById("NewDegr").style.display = 'none';
                    document.getElementById('YR').innerHTML = 'Grade';

                    yearStart = 1;
                    yearEnd = 6;

                    sections = ["A", "B", "C", "D", "E"];

                    document.getElementById("TxtStudentCategory").selectedIndex = "1";

                    

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "junior highschool"){
                    document.getElementById("Cour").style.display = 'none';
                    //document.getElementById("NewDegr").style.display = 'none';
                    document.getElementById('YR').innerHTML = 'Grade';
                    yearStart = 7;
                    yearEnd = 10;

                    sections = ["A", "B", "C", "D", "E"];

                    document.getElementById("TxtStudentCategory").selectedIndex = "2";

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "senior highschool"){
                    document.getElementById("Cour").style.display = 'inline-block';
                    //document.getElementById("NewDegr").style.display = 'inline-block';
                    document.getElementById('YR').innerHTML = 'Grade';
                    document.getElementById('CS').innerHTML = 'Strand';

                    sections = ["A", "B", "C", "D", "E"];

                    yearStart = 11;
                    yearEnd = 12;

                    <?php  

                    if(mysqli_num_rows($resultSenior) > 0){
                        while($row = mysqli_fetch_array($resultSenior)){
                            echo "
                            option_elem = document.createElement('option');

                            // Add index to option_elem
                            option_elem.value = '$row[degree] ($row[degree_acr])';
                                                  
                            // Add element HTML
                            option_elem.textContent = '$row[degree] ($row[degree_acr])';

                            categoryOption.append(option_elem);
                            ";
                        }
                    }

                    ?>

                    document.getElementById("TxtStudentCategory").selectedIndex = "3";

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "college"){
                    document.getElementById("Cour").style.display = 'inline-block';
                    //document.getElementById("NewDegr").style.display = 'inline-block';
                    document.getElementById('YR').innerHTML = 'Year';
                    document.getElementById('CS').innerHTML = 'Degree';

                    yearStart = 1;
                    yearEnd = 4;

                    sections = ["A", "B", "C", "D", "E"];

                    <?php  

                    if(mysqli_num_rows($resultCollege) > 0){
                        while($row = mysqli_fetch_array($resultCollege)){
                            echo "
                            option_elem = document.createElement('option');

                            // Add index to option_elem
                            option_elem.value = '$row[degree] ($row[degree_acr])';
                                                  
                            // Add element HTML
                            option_elem.textContent = '$row[degree] ($row[degree_acr])';

                            categoryOption.append(option_elem);
                            ";
                        }
                    }

                    ?>

                    document.getElementById("TxtStudentCategory").selectedIndex = "4";

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }else if(selectedValue == "graduate"){
                    document.getElementById("Cour").style.display = 'inline-block';
                    //document.getElementById("NewDegr").style.display = 'inline-block';
                    document.getElementById('YR').innerHTML = 'Year';
                    document.getElementById('CS').innerHTML = 'Degree';

                    yearStart = 1;
                    yearEnd = 2;

                    sections = ["A", "B", "C", "D", "E"];

                    <?php  

                    if(mysqli_num_rows($resultGrad) > 0){
                        while($row = mysqli_fetch_array($resultGrad)){
                            echo "
                            option_elem = document.createElement('option');

                            // Add index to option_elem
                            option_elem.value = '$row[degree] ($row[degree_acr])';
                                                  
                            // Add element HTML
                            option_elem.textContent = '$row[degree] ($row[degree_acr])';

                            categoryOption.append(option_elem);
                            ";
                        }

                    }

                    ?>

                    document.getElementById("TxtStudentCategory").selectedIndex = "5";

                    $('#TCourse').val('');
                    $('#TYear').val('');
                    $('#TSection').val('');
                }


                option_elem = document.createElement('option');

                // Add index to option_elem
                option_elem.value = '-- Add Course to the list --';
                                      
                // Add element HTML
                option_elem.textContent = '-- Add Course to the list --';

                categoryOption.append(option_elem);
                        

                for (let i = yearStart; i <= yearEnd; i++) {
                    option_elem = document.createElement('option');

                    // Add index to option_elem
                    option_elem.value = i;
                                              
                        // Add element HTML
                    option_elem.textContent = i;

                    yearOption.append(option_elem);
                }

                sections.forEach((element) => {
                    let option_elem = document.createElement('option');

                    // Add index to option_elem
                    option_elem.value = element;
                                          
                    // Add element HTML
                    option_elem.textContent = element;
                                          
                    // Append option_elem to select_elem
                    sectionOption.append(option_elem);

                });
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
                return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 189 || key==13);
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
                    return ((key >= 65 && key <= 90) || (key >= 96 && key <= 105) || key == 18 || key == 8 || key == 32 || key == 165 || key == 164 || key==13);
                }
            }

            function alphaOnly(event){
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 37 || key == 39 || key == 13 || event.key == '(' || event.key == ')');
            }

            function btnValue(valu){
                TempBtnValue = valu;
            }

            function clickedEdit(){
                enableEditing();
                modifyVisibleButton('save');
                document.getElementById("BtnSave").removeAttribute("disabled");
                document.getElementById("BtnSave1").removeAttribute("disabled");
                document.getElementById("BtnEdit").setAttribute('disabled','disabled');
                document.getElementById("BtnEdit1").setAttribute('disabled','disabled');
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

            function clickedNone(){
                document.getElementById("sibling").removeAttribute("value");
                document.getElementById("grandparents").removeAttribute("value");
                document.getElementById("ward").removeAttribute("value");
                document.getElementById("father").removeAttribute("value");
                document.getElementById("mother").removeAttribute("value");
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

            function clickedGuardian2(){
                document.getElementById("father2").removeAttribute("value");
                document.getElementById("mother2").removeAttribute("value");
                document.getElementById("sibling2").setAttribute('value','Sibling');
                document.getElementById("grandparents2").setAttribute('value','Grandparents');
                document.getElementById("ward2").setAttribute('value','Ward');
                $('#TGPCategory2').val('');
            }

            function clickedParent2(){
                document.getElementById("sibling2").removeAttribute("value");
                document.getElementById("grandparents2").removeAttribute("value");
                document.getElementById("ward2").removeAttribute("value");
                document.getElementById("father2").setAttribute('value','Father');
                document.getElementById("mother2").setAttribute('value','Mother');
                $('#TGPCategory2').val('');
            }

            function clickedNone2(){
                document.getElementById("sibling2").removeAttribute("value");
                document.getElementById("grandparents2").removeAttribute("value");
                document.getElementById("ward2").removeAttribute("value");
                document.getElementById("father2").removeAttribute("value");
                document.getElementById("mother2").removeAttribute("value");
                $('#TGPCategory2').val('');
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

                //document.getElementById("TxtAddress").setAttribute('readonly','readonly');
                document.getElementById("TxtPresAddHouseNo").setAttribute('readonly','readonly');
                document.getElementById("TxtPresAddStreet").setAttribute('readonly','readonly');
                document.getElementById("TxtPresAddBrgy").setAttribute('readonly','readonly');
                document.getElementById("TxtPresAddMunicipal").setAttribute('readonly','readonly');
                document.getElementById("TxtPresAddProvince").setAttribute('readonly','readonly');
                //document.getElementById("TxtProvAdd").setAttribute('readonly','readonly');
                document.getElementById("TxtProvAddHouseNo").setAttribute('readonly','readonly');
                document.getElementById("TxtProvAddStreet").setAttribute('readonly','readonly');
                document.getElementById("TxtProvAddBrgy").setAttribute('readonly','readonly');
                document.getElementById("TxtProvAddMunicipal").setAttribute('readonly','readonly');
                document.getElementById("TxtProvAddProvince").setAttribute('readonly','readonly');

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

                if(TempGuardianParent2 == "guardian"){
                    $('#RadGuardian2').prop('checked', true);
                }else if(TempGuardianParent2 == "parent"){
                    $('#RadParent2').prop('checked', true);
                }else{
                    $('#RadNone2').prop('checked', true);
                }
                document.getElementById("RadNone2").setAttribute('disabled','disabled');
                document.getElementById("RadGuardian2").setAttribute('disabled','disabled');
                document.getElementById("RadParent2").setAttribute('disabled','disabled');
                document.getElementById("TGPCategory2").setAttribute('disabled','disabled');
                document.getElementById("TxtContactPerson2").setAttribute('readonly','readonly');
                document.getElementById("TxtPGContactNumber2").setAttribute('readonly','readonly');

                document.getElementById("TxtDate").setAttribute('readonly','readonly');
                document.getElementById("TxtTime").setAttribute('readonly','readonly');
                document.getElementById("TxtLMP").setAttribute('readonly','readonly');
                document.getElementById("TxtPregnancy").setAttribute('readonly','readonly');
                document.getElementById("TxtAllergies").setAttribute('readonly','readonly');
                document.getElementById("TxtSurgeries").setAttribute('readonly','readonly');
                document.getElementById("TxtInjuries").setAttribute('readonly','readonly');
                document.getElementById("TxtIllness").setAttribute('readonly','readonly');
                document.getElementById("TxtMedicalOthers").setAttribute('readonly','readonly');
                document.getElementById("TxtRLOA").setAttribute('readonly','readonly');
                document.getElementById("TxtSchoolYear").setAttribute('readonly','readonly');
                document.getElementById("TxtStudentTerm").setAttribute('disabled','disabled');
                document.getElementById("TxtHeight").setAttribute('readonly','readonly');
                document.getElementById("TxtWeight").setAttribute('readonly','readonly');
                document.getElementById("TxtBloodPressure").setAttribute('readonly','readonly');
                document.getElementById("TxtTemperature").setAttribute('readonly','readonly');
                document.getElementById("TxtPulseRate").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithoutGlassesOD").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithoutGlassesOS").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithGlassesOD").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithGlassesOS").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithContLensOD").setAttribute('readonly','readonly');
                document.getElementById("TxtVisionWithContLensOS").setAttribute('readonly','readonly');
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
                    $('#RadMale').prop('checked', true);
                }else if(TempSex == "female"){
                    $('#RadFemale').prop('checked', true);
                }else{
                    $('#RadMale').prop('checked', false);
                    $('#RadFemale').prop('checked', false);
                }
                document.getElementById("RadMale").removeAttribute("disabled");
                document.getElementById("RadFemale").removeAttribute("disabled");

                //document.getElementById("TxtAddress").removeAttribute("readonly");
                document.getElementById("TxtPresAddHouseNo").removeAttribute("readonly");
                document.getElementById("TxtPresAddStreet").removeAttribute("readonly");
                document.getElementById("TxtPresAddBrgy").removeAttribute("readonly");
                document.getElementById("TxtPresAddMunicipal").removeAttribute("readonly");
                document.getElementById("TxtPresAddProvince").removeAttribute("readonly");
                //document.getElementById("TxtProvAdd").removeAttribute("readonly");
                document.getElementById("TxtProvAddHouseNo").removeAttribute("readonly");
                document.getElementById("TxtProvAddStreet").removeAttribute("readonly");
                document.getElementById("TxtProvAddBrgy").removeAttribute("readonly");
                document.getElementById("TxtProvAddMunicipal").removeAttribute("readonly");
                document.getElementById("TxtProvAddProvince").removeAttribute("readonly");

                
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

                if(TempGuardianParent2 == "guardian"){
                    $('#RadGuardian2').prop('checked', false);
                }else if(TempGuardianParent2 == "parent"){
                    $('#RadParent2').prop('checked', false);
                }
                document.getElementById("RadNone2").removeAttribute("disabled");
                document.getElementById("RadGuardian2").removeAttribute("disabled");
                document.getElementById("RadParent2").removeAttribute("disabled");
                document.getElementById("TGPCategory2").removeAttribute("disabled");
                document.getElementById("TxtContactPerson2").removeAttribute("readonly");
                document.getElementById("TxtPGContactNumber2").removeAttribute("readonly");

                document.getElementById("TxtDate").removeAttribute("readonly");
                document.getElementById("TxtTime").removeAttribute("readonly");
                document.getElementById("TxtLMP").removeAttribute("readonly");
                document.getElementById("TxtPregnancy").removeAttribute("readonly");
                document.getElementById("TxtAllergies").removeAttribute("readonly");
                document.getElementById("TxtSurgeries").removeAttribute("readonly");
                document.getElementById("TxtInjuries").removeAttribute("readonly");
                document.getElementById("TxtIllness").removeAttribute("readonly");
                document.getElementById("TxtMedicalOthers").removeAttribute("readonly");
                document.getElementById("TxtRLOA").removeAttribute("readonly");
                document.getElementById("TxtSchoolYear").removeAttribute("readonly");
                document.getElementById("TxtStudentTerm").removeAttribute("disabled");
                document.getElementById("TxtHeight").removeAttribute("readonly");
                document.getElementById("TxtWeight").removeAttribute("readonly");
                document.getElementById("TxtBloodPressure").removeAttribute("readonly");
                document.getElementById("TxtTemperature").removeAttribute("readonly");
                document.getElementById("TxtPulseRate").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithoutGlassesOS").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithGlassesOS").removeAttribute("readonly");
                document.getElementById("TxtVisionWithContLensOD").removeAttribute("readonly");
                document.getElementById("TxtVisionWithContLensOS").removeAttribute("readonly");

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

            function checkTimeInput(time){
                var time_inp = time.value;
                var timeArr = time_inp.split(':');

                if(timeArr == ''){

                }else if(timeArr[0] > 18){
                    $('#TxtTime').val('');
                    $.alert({
                        theme: 'modern',
                        content: 'Time selected is after work hours',
                        title: '',
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass:'btn-red'
                    }}});
                }else if(timeArr[0] < 8){
                    $('#TxtTime').val('');
                    $.alert({
                        theme: 'modern',
                        content: 'Time selected is before work hours',
                        title: '',
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass:'btn-red'
                    }}});
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
                var idnum = document.getElementById('TxtStudentIDNumber').value;
                if(idnum.length > 0){

                    fetchName();

                }

                
            }

            function modifyVisibleButton(type){
                if(type == 'add'){
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
                    document.getElementById("BtnClear").style.display = 'flex';
                    document.getElementById("BtnClear1").style.display = 'flex';
                    document.getElementById("BtnSave").style.display = 'none';
                    document.getElementById("BtnSave1").style.display = 'none';
                    document.getElementById("BtnEdit").style.display = 'none';
                    document.getElementById("BtnEdit1").style.display = 'none';
                    document.getElementById("BtnPrint").style.display = 'none';
                    document.getElementById("BtnPrint1").style.display = 'none';
                    document.getElementById("BtnPDF").style.display = 'none';
                    document.getElementById("BtnPDF1").style.display = 'none';
                    
                    
                }else if(type == 'save'){
                    document.getElementById("BtnAdd").setAttribute('disabled','disabled');
                    document.getElementById("BtnClear").setAttribute('disabled','disabled');
                    document.getElementById("BtnAdd1").setAttribute('disabled','disabled');
                    document.getElementById("BtnClear1").setAttribute('disabled','disabled');
                    document.getElementById("BtnSave").setAttribute('disabled','disabled');
                    document.getElementById("BtnEdit").removeAttribute("disabled");
                    document.getElementById("BtnSave1").setAttribute('disabled','disabled');
                    document.getElementById("BtnEdit1").removeAttribute("disabled");
                    document.getElementById("BtnAdd").style.display = 'none';
                    document.getElementById("BtnAdd1").style.display = 'none';
                    document.getElementById("BtnClear").style.display = 'none';
                    document.getElementById("BtnClear1").style.display = 'none';
                    document.getElementById("BtnSave").style.display = 'flex';
                    document.getElementById("BtnSave1").style.display = 'flex';
                    document.getElementById("BtnEdit").style.display = 'flex';
                    document.getElementById("BtnEdit1").style.display = 'flex';
                    document.getElementById("BtnPrint").style.display = 'flex';
                    document.getElementById("BtnPrint1").style.display = 'flex';
                    document.getElementById("BtnPDF").style.display = 'flex';
                    document.getElementById("BtnPDF1").style.display = 'flex';
                  }else if(type == 'none'){
                    /*document.getElementById("BtnAdd").setAttribute('disabled','disabled');
                    document.getElementById("BtnClear").setAttribute('disabled','disabled');
                    document.getElementById("BtnAdd1").setAttribute('disabled','disabled');
                    document.getElementById("BtnClear1").setAttribute('disabled','disabled');
                    document.getElementById("BtnSave").setAttribute('disabled','disabled');
                    document.getElementById("BtnEdit").setAttribute('disabled','disabled');
                    document.getElementById("BtnSave1").setAttribute('disabled','disabled');
                    document.getElementById("BtnEdit1").setAttribute('disabled','disabled');*/
                    document.getElementById("BtnAdd").style.display = 'none';
                    document.getElementById("BtnAdd1").style.display = 'none';
                    document.getElementById("BtnClear").style.display = 'none';
                    document.getElementById("BtnClear1").style.display = 'none';
                    document.getElementById("BtnSave").style.display = 'none';
                    document.getElementById("BtnSave1").style.display = 'none';
                    document.getElementById("BtnEdit").style.display = 'none';
                    document.getElementById("BtnEdit1").style.display = 'none';
                    document.getElementById("BtnPrint").style.display = 'none';
                    document.getElementById("BtnPrint1").style.display = 'none';
                    document.getElementById("BtnPDF").style.display = 'none';
                    document.getElementById("BtnPDF1").style.display = 'none';
                  }
            }

            function disableEditing(){
                
                setAttr();
                document.getElementById('TxtDocumentCode').style.backgroundColor = "transparent";
                document.getElementById('TxtRevisionNumber').style.backgroundColor = "transparent"; 
                document.getElementById('TxtEffectivity').style.backgroundColor = "transparent"; 
                document.getElementById('TxtNoLabel').style.backgroundColor = "transparent"; 

                document.getElementById('addMore').style.display = 'inline-block';
                document.getElementById('TxtStudentCategory').style.backgroundColor = "transparent";    
                document.getElementById('TCourse').style.backgroundColor = "transparent"; 
                document.getElementById('TYear').style.backgroundColor = "transparent"; 
                document.getElementById('TSection').style.backgroundColor = "transparent"; 
                document.getElementById('TxtLastname').style.backgroundColor = "transparent"; 
                document.getElementById('TxtFirstname').style.backgroundColor = "transparent"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "transparent"; 
                document.getElementById('TxtExtension').style.backgroundColor = "transparent"; 
                document.getElementById('TxtBirthdate').style.backgroundColor = "transparent";
                document.getElementById('TxtPresAddHouseNo').style.backgroundColor = "transparent";
                document.getElementById('TxtPresAddStreet').style.backgroundColor = "transparent";
                document.getElementById('TxtPresAddBrgy').style.backgroundColor = "transparent";
                document.getElementById('TxtPresAddMunicipal').style.backgroundColor = "transparent";
                document.getElementById('TxtPresAddProvince').style.backgroundColor = "transparent";
                document.getElementById('TxtProvAddHouseNo').style.backgroundColor = "transparent";
                document.getElementById('TxtProvAddStreet').style.backgroundColor = "transparent";
                document.getElementById('TxtProvAddBrgy').style.backgroundColor = "transparent";
                document.getElementById('TxtProvAddMunicipal').style.backgroundColor = "transparent";
                document.getElementById('TxtProvAddProvince').style.backgroundColor = "transparent";
                document.getElementById('TxtStudentContactNumber').style.backgroundColor = "transparent"; 
                document.getElementById('TGPCategory').style.backgroundColor = "transparent"; 
                document.getElementById('TxtContactPerson').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPGContactNumber').style.backgroundColor = "transparent"; 
                document.getElementById('TGPCategory1').style.backgroundColor = "transparent"; 
                document.getElementById('TxtContactPerson1').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPGContactNumber1').style.backgroundColor = "transparent";
                document.getElementById('TGPCategory2').style.backgroundColor = "transparent"; 
                document.getElementById('TxtContactPerson2').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPGContactNumber2').style.backgroundColor = "transparent";

                document.getElementById('TxtDate').style.backgroundColor = "transparent"; 
                document.getElementById('TxtTime').style.backgroundColor = "transparent";
                document.getElementById('TxtLMP').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPregnancy').style.backgroundColor = "transparent"; 
                document.getElementById('TxtAllergies').style.backgroundColor = "transparent"; 
                document.getElementById('TxtSurgeries').style.backgroundColor = "transparent"; 
                document.getElementById('TxtInjuries').style.backgroundColor = "transparent"; 
                document.getElementById('TxtIllness').style.backgroundColor = "transparent";
                document.getElementById('TxtMedicalOthers').style.backgroundColor = "transparent";  
                document.getElementById('TxtRLOA').style.backgroundColor = "transparent"; 
                document.getElementById('TxtSchoolYear').style.backgroundColor = "transparent"; 
                document.getElementById('TxtStudentTerm').style.backgroundColor = "transparent";
                document.getElementById('TxtHeight').style.backgroundColor = "transparent";    
                document.getElementById('TxtWeight').style.backgroundColor = "transparent"; 
                document.getElementById('TxtBloodPressure').style.backgroundColor = "transparent"; 
                document.getElementById('TxtTemperature').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPulseRate').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithoutGlassesOD').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithoutGlassesOS').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithGlassesOD').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithGlassesOS').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithContLensOD').style.backgroundColor = "transparent"; 
                document.getElementById('TxtVisionWithContLensOS').style.backgroundColor = "transparent"; 
                document.getElementById('TxtHearingDistanceOption').style.backgroundColor = "transparent";    
                document.getElementById('TxtSpeechOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtEyesOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtEarsOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtNoseOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtHeadOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtAbdomenOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtGenitoUrinaryOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtLymphGlandsOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtSkinOption').style.backgroundColor = "transparent";    
                document.getElementById('TxtExtremitiesOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtDeformitiesOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtCavityAndThroatOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtLungsOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtHeartOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtBreastOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtRadiologicExamsOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtBloodAnalysisOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtUrinalysisOption').style.backgroundColor = "transparent";    
                document.getElementById('TxtFecalysisOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtPregnancyTestOption').style.backgroundColor = "transparent"; 
                document.getElementById('TxtHBSAgOption').style.backgroundColor = "transparent"; 
                document.getElementById('TAHearingDistance').style.backgroundColor = "transparent";    
                document.getElementById('TASpeech').style.backgroundColor = "transparent"; 
                document.getElementById('TAEyes').style.backgroundColor = "transparent"; 
                document.getElementById('TAEars').style.backgroundColor = "transparent"; 
                document.getElementById('TANose').style.backgroundColor = "transparent"; 
                document.getElementById('TAHead').style.backgroundColor = "transparent"; 
                document.getElementById('TAAbdomen').style.backgroundColor = "transparent"; 
                document.getElementById('TAGenitoUrinary').style.backgroundColor = "transparent"; 
                document.getElementById('TALymphGlands').style.backgroundColor = "transparent"; 
                document.getElementById('TASkin').style.backgroundColor = "transparent";    
                document.getElementById('TAExtremities').style.backgroundColor = "transparent"; 
                document.getElementById('TADeformities').style.backgroundColor = "transparent"; 
                document.getElementById('TACavityAndThroat').style.backgroundColor = "transparent"; 
                document.getElementById('TALungs').style.backgroundColor = "transparent"; 
                document.getElementById('TAHeart').style.backgroundColor = "transparent"; 
                document.getElementById('TABreast').style.backgroundColor = "transparent"; 
                document.getElementById('TARadiologicExams').style.backgroundColor = "transparent"; 
                document.getElementById('TABloodAnalysis').style.backgroundColor = "transparent"; 
                document.getElementById('TAUrinalysis').style.backgroundColor = "transparent";    
                document.getElementById('TAFecalysis').style.backgroundColor = "transparent"; 
                document.getElementById('TAPregnancyTest').style.backgroundColor = "transparent"; 
                document.getElementById('TAHBSAg').style.backgroundColor = "transparent"; 
                document.getElementById('TxtOthers').style.backgroundColor = "transparent"; 
                document.getElementById('TxtRecommendation').style.backgroundColor = "transparent"; 
                document.getElementById('TxtRemarks').style.backgroundColor = "transparent"; 
            }

            function enableEditing(type){
                removeAttr();
                document.getElementById('TxtDocumentCode').style.backgroundColor = "white";
                document.getElementById('TxtRevisionNumber').style.backgroundColor = "white"; 
                document.getElementById('TxtEffectivity').style.backgroundColor = "white"; 
                document.getElementById('TxtNoLabel').style.backgroundColor = "white"; 

                document.getElementById('addMore').style.display = 'inline-block';
                document.getElementById('TxtStudentCategory').style.backgroundColor = "white";    
                document.getElementById('TCourse').style.backgroundColor = "white"; 
                document.getElementById('TYear').style.backgroundColor = "white"; 
                document.getElementById('TSection').style.backgroundColor = "white"; 
                document.getElementById('TxtLastname').style.backgroundColor = "white"; 
                document.getElementById('TxtFirstname').style.backgroundColor = "white"; 
                document.getElementById('TxtMiddlename').style.backgroundColor = "white"; 
                document.getElementById('TxtExtension').style.backgroundColor = "white"; 
                document.getElementById('TxtBirthdate').style.backgroundColor = "white";
                document.getElementById('TxtPresAddHouseNo').style.backgroundColor = "white";
                document.getElementById('TxtPresAddStreet').style.backgroundColor = "white";
                document.getElementById('TxtPresAddBrgy').style.backgroundColor = "white";
                document.getElementById('TxtPresAddMunicipal').style.backgroundColor = "white";
                document.getElementById('TxtPresAddProvince').style.backgroundColor = "white";
                document.getElementById('TxtProvAddHouseNo').style.backgroundColor = "white";
                document.getElementById('TxtProvAddStreet').style.backgroundColor = "white";
                document.getElementById('TxtProvAddBrgy').style.backgroundColor = "white";
                document.getElementById('TxtProvAddMunicipal').style.backgroundColor = "white";
                document.getElementById('TxtProvAddProvince').style.backgroundColor = "white";
                document.getElementById('TxtStudentContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber').style.backgroundColor = "white"; 
                document.getElementById('TGPCategory1').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson1').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber1').style.backgroundColor = "white";
                document.getElementById('TGPCategory2').style.backgroundColor = "white"; 
                document.getElementById('TxtContactPerson2').style.backgroundColor = "white"; 
                document.getElementById('TxtPGContactNumber2').style.backgroundColor = "white";

                document.getElementById('TxtDate').style.backgroundColor = "white"; 
                document.getElementById('TxtTime').style.backgroundColor = "white";
                document.getElementById('TxtLMP').style.backgroundColor = "white"; 
                document.getElementById('TxtPregnancy').style.backgroundColor = "white"; 
                document.getElementById('TxtAllergies').style.backgroundColor = "white"; 
                document.getElementById('TxtSurgeries').style.backgroundColor = "white"; 
                document.getElementById('TxtInjuries').style.backgroundColor = "white"; 
                document.getElementById('TxtIllness').style.backgroundColor = "white";
                document.getElementById('TxtMedicalOthers').style.backgroundColor = "white";  
                document.getElementById('TxtRLOA').style.backgroundColor = "white"; 
                document.getElementById('TxtSchoolYear').style.backgroundColor = "white"; 
                document.getElementById('TxtStudentTerm').style.backgroundColor = "white";
                document.getElementById('TxtHeight').style.backgroundColor = "white";    
                document.getElementById('TxtWeight').style.backgroundColor = "white"; 
                document.getElementById('TxtBloodPressure').style.backgroundColor = "white"; 
                document.getElementById('TxtTemperature').style.backgroundColor = "white"; 
                document.getElementById('TxtPulseRate').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithoutGlassesOS').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithGlassesOS').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithContLensOD').style.backgroundColor = "white"; 
                document.getElementById('TxtVisionWithContLensOS').style.backgroundColor = "white"; 
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


            function fetchHistory(idnum, userid, editdate){
                

                var form_data = new FormData();

                let editedDate = editdate;

                editedDate = editedDate.toString().replace('_',' ');
                editedDate = editedDate.toString().replace('/',':');

                //alert(idnum + ' ' +userid +' ' +editedDate);

                form_data.append("idnum", idnum);
                form_data.append("userid", userid);
                form_data.append("editdate", editedDate);

                $.ajax(
                { 
                    url:"../php/Student/FetchHistory.php",
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
                            var ProvAdd = $(this).attr('ProvAdd');
                            var StudentContactNumber = $(this).attr('StudentContactNumber');
                            var GuardianParent = $(this).attr('GuardianParent');
                            var GPCategory = $(this).attr('GPCategory');
                            var ContactPerson = $(this).attr('ContactPerson');
                            var PGContactNumber = $(this).attr('PGContactNumber');
                            var GuardianParent1 = $(this).attr('GuardianParent1');
                            var GPCategory1 = $(this).attr('GPCategory1');
                            var ContactPerson1 = $(this).attr('ContactPerson1');
                            var PGContactNumber1 = $(this).attr('PGContactNumber1');
                            var GuardianParent2 = $(this).attr('GuardianParent2');
                            var GPCategory2 = $(this).attr('GPCategory2');
                            var ContactPerson2 = $(this).attr('ContactPerson2');
                            var PGContactNumber2 = $(this).attr('PGContactNumber2');
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
                            var MedicalOthers = $(this).attr('MedicalOthers');
                            var RLOA = $(this).attr('RLOA');
                            var SchoolYear = $(this).attr('SchoolYear');
                            var StudentTerm = $(this).attr('StudentTerm');
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
                            var VisionWithContLensOD = $(this).attr('VisionWithContLensOD');
                            var VisionWithContLensOS = $(this).attr('VisionWithContLensOS');
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
                            var MSEditor = $(this).attr('MSEditor');

                            TempSex = Sex;
                            TempGuardianParent = GuardianParent;
                            TempGuardianParent1 = GuardianParent1;
                            TempGuardianParent2 = GuardianParent2;

                            //alert('123');

                            if(error == "1"){
                                    disableEditing();
                                    clearPersonalMedical();
                                    modifyVisibleButton('none');
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

                                    changeFunc(StudentCategory);

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

                                    var PresAddArr = Address.split("||");
                                    //$('#TxtAddress').val(Address);
                                    $('#TxtPresAddHouseNo').val(PresAddArr[0]);
                                    $('#TxtPresAddStreet').val(PresAddArr[1]);
                                    $('#TxtPresAddBrgy').val(PresAddArr[2]);
                                    $('#TxtPresAddMunicipal').val(PresAddArr[3]);
                                    $('#TxtPresAddProvince').val(PresAddArr[4]);

                                    var ProvAddArr = ProvAdd.split("||");
                                    //$('#TxtProvAdd').val(ProvAdd);
                                    $('#TxtProvAddHouseNo').val(ProvAddArr[0]);
                                    $('#TxtProvAddStreet').val(ProvAddArr[1]);
                                    $('#TxtProvAddBrgy').val(ProvAddArr[2]);
                                    $('#TxtProvAddMunicipal').val(ProvAddArr[3]);
                                    $('#TxtProvAddProvince').val(ProvAddArr[4]);



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
                                    document.getElementById('TxtMSFullName').innerHTML = '&nbsp&nbsp&nbsp'+(StaffLastname + ", " + StaffFirstname + " " + StaffMiddlename + " " + StaffExtension).toUpperCase();

                                    var editedByDD = document.getElementById("TxtMSEditorDrop");
                                    editedByDD.options.length = 0;
                                    const MSEditorDDArr = MSEditor.split("/");

                                    var value = '';
                                    MSEditorDDArr.forEach((element) => {
                                        let option_elem = document.createElement('option');

                                        // Add index to option_elem
                                        option_elem.value = element;
                                          
                                        // Add element HTML
                                        option_elem.textContent = element;
                                          
                                        // Append option_elem to select_elem
                                        editedByDD.prepend(option_elem);

                                        if(element.match(userid)){
                                            if (element.match(editedDate)) {
                                                value = element;
                                            }
                                        }

                                    });

                                    //alert(value);
                                    $('#TxtMSEditorDrop').val(value);
                                    $('#TxtLMP').val(LMP);
                                    $('#TxtPregnancy').val(Pregnancy);
                                    $('#TxtAllergies').val(Allergies);
                                    $('#TxtSurgeries').val(Surgeries);
                                    $('#TxtInjuries').val(Injuries);
                                    $('#TxtIllness').val(Illness);
                                    $('#TxtMedicalOthers').val(MedicalOthers);
                                    $('#TxtRLOA').val(RLOA);
                                    $('#TxtSchoolYear').val(SchoolYear);
                                    $('#TxtStudentTerm').val(StudentTerm);
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
                                    $('#TxtVisionWithContLensOD').val(VisionWithContLensOD);
                                    $('#TxtVisionWithContLensOS').val(VisionWithContLensOS);

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

                                    if(GPCategory2 != '' || ContactPerson2 != ''){
                                        showAddMore();
                                        if(GuardianParent2 == "guardian"){
                                            $('#RadGuardian2').prop('checked', true);
                                        }else if(GuardianParent2 == "parent"){
                                            $('#RadParent2').prop('checked', true);
                                        }else{
                                            $('#RadNone2').prop('checked', true);
                                        }
                                        $('#TGPCategory2').val(GPCategory2);
                                        $('#TxtContactPerson2').val(ContactPerson2);
                                        $('#TxtPGContactNumber2').val(PGContactNumber2);
                                    }else{
                                        $('#RadNone2').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm3').style.display = 'none';
                                        document.getElementById('addMoreForm4').style.display = 'none';
                                    }
                                    ////123123123

                                    document.getElementById('tab1').click();
                                    document.getElementById('TxtStudentIDNumber').focus();

                                  
                            }
                                    disableEditing();
                                    modifyVisibleButton('save');
                                    styleAllInput();

                                    
                                    var lastOpt = $('#TxtMSEditorDrop option:first').val().trim();
                                    var alertMsg = '';
                                    var alertTitle = '';
                                    if(value == lastOpt){
                                        document.getElementById("TxtStudentIDNumber").removeAttribute('disabled');
                                        document.getElementById("RadNew").removeAttribute('disabled');
                                        document.getElementById("RadOld").removeAttribute('disabled');
                                        document.getElementById('BtnPrint').style.display = 'flex';
                                        document.getElementById('BtnEdit').style.display = 'flex';
                                        document.getElementById('BtnSave').style.display = 'flex';

                                        document.getElementById('BtnPrint1').style.display = 'flex';
                                        document.getElementById('BtnEdit1').style.display = 'flex';
                                        document.getElementById('BtnSave1').style.display = 'flex';
                                        alertMsg = 'Edited by: <br>' +value;
                                        alertTitle = 'Latest Personal/Medical Information';
                                    }else{
                                        document.getElementById("TxtStudentIDNumber").setAttribute('disabled','disabled');
                                        document.getElementById("RadNew").setAttribute('disabled','disabled');
                                        document.getElementById("RadOld").setAttribute('disabled','disabled');
                                        document.getElementById('BtnPrint').style.display = 'none';
                                        document.getElementById('BtnEdit').style.display = 'none';
                                        document.getElementById('BtnSave').style.display = 'none';

                                        document.getElementById('BtnPrint1').style.display = 'none';
                                        document.getElementById('BtnEdit1').style.display = 'none';
                                        document.getElementById('BtnSave1').style.display = 'none';
                                        alertMsg = 'Edited by: <br>' +value;
                                        alertTitle = 'Past Personal/Medical Information';
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

            function passIDPHP(x){
                var form_data = new FormData();
                var Num = x;
                form_data.append("idnumber", Num);
                form_data.append("type", getType);
                $.ajax(
                { 
                    url:"../php/Student/FetchRecords.php",
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
                            var Status = $(this).attr('Status');
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
                            var ProvAdd = $(this).attr('ProvAdd');
                            var StudentContactNumber = $(this).attr('StudentContactNumber');
                            var GuardianParent = $(this).attr('GuardianParent');
                            var GPCategory = $(this).attr('GPCategory');
                            var ContactPerson = $(this).attr('ContactPerson');
                            var PGContactNumber = $(this).attr('PGContactNumber');
                            var GuardianParent1 = $(this).attr('GuardianParent1');
                            var GPCategory1 = $(this).attr('GPCategory1');
                            var ContactPerson1 = $(this).attr('ContactPerson1');
                            var PGContactNumber1 = $(this).attr('PGContactNumber1');
                            var GuardianParent2 = $(this).attr('GuardianParent2');
                            var GPCategory2 = $(this).attr('GPCategory2');
                            var ContactPerson2 = $(this).attr('ContactPerson2');
                            var PGContactNumber2 = $(this).attr('PGContactNumber2');
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
                            var MedicalOthers = $(this).attr('MedicalOthers');
                            var RLOA = $(this).attr('RLOA');
                            var SchoolYear = $(this).attr('SchoolYear');
                            var StudentTerm = $(this).attr('StudentTerm');
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
                            var VisionWithContLensOD = $(this).attr('VisionWithContLensOD');
                            var VisionWithContLensOS = $(this).attr('VisionWithContLensOS');
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
                            var MSEditor = $(this).attr('MSEditor');

                            TempSex = Sex;
                            TempGuardianParent = GuardianParent;
                            TempGuardianParent1 = GuardianParent1;
                            TempGuardianParent2 = GuardianParent2;

                            if(error == "1"){
                                    disableEditing();
                                    clearPersonalMedical();
                                    modifyVisibleButton('none');
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


                                var radOld = document.getElementById("RadOld");
                                var radNew = document.getElementById("RadNew");

                                if(Status == 'old'){
                                    radOld.setAttribute('checked','checked');
                                }else if(Status == 'new'){
                                    radNew.setAttribute('checked','checked');
                                }
                                
                                
                                if(StudentImage.length == 18){
                                    document.getElementById("IDPic").src = "../images/id picture.webp";
                                }else{
                                    document.getElementById("IDPic").src = StudentImage;
                                }
                                    $('#TxtStudentIDNumber').val(StudentIDNumber);

                                    changeFunc(StudentCategory);

                                    
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
                                    var PresAddArr = Address.split("||");
                                    //$('#TxtAddress').val(Address);
                                    $('#TxtPresAddHouseNo').val(PresAddArr[0]);
                                    $('#TxtPresAddStreet').val(PresAddArr[1]);
                                    $('#TxtPresAddBrgy').val(PresAddArr[2]);
                                    $('#TxtPresAddMunicipal').val(PresAddArr[3]);
                                    $('#TxtPresAddProvince').val(PresAddArr[4]);

                                    var ProvAddArr = ProvAdd.split("||");
                                    //$('#TxtProvAdd').val(ProvAdd);
                                    $('#TxtProvAddHouseNo').val(ProvAddArr[0]);
                                    $('#TxtProvAddStreet').val(ProvAddArr[1]);
                                    $('#TxtProvAddBrgy').val(ProvAddArr[2]);
                                    $('#TxtProvAddMunicipal').val(ProvAddArr[3]);
                                    $('#TxtProvAddProvince').val(ProvAddArr[4]);

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
                                    document.getElementById('TxtMSFullName').innerHTML = '&nbsp&nbsp&nbsp'+(StaffLastname + ", " + StaffFirstname + " " + StaffMiddlename + " " + StaffExtension).toUpperCase();

                                    var editedByDD = document.getElementById("TxtMSEditorDrop");
                                    editedByDD.options.length = 0;
                                    const MSEditorDDArr = MSEditor.split("/");

                                    MSEditorDDArr.forEach((element) => {
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

                                    $('#TxtLMP').val(LMP);
                                    $('#TxtPregnancy').val(Pregnancy);
                                    $('#TxtAllergies').val(Allergies);
                                    $('#TxtSurgeries').val(Surgeries);
                                    $('#TxtInjuries').val(Injuries);
                                    $('#TxtIllness').val(Illness);
                                    $('#TxtMedicalOthers').val(MedicalOthers);
                                    $('#TxtRLOA').val(RLOA);
                                    $('#TxtSchoolYear').val(SchoolYear);
                                    $('#TxtStudentTerm').val(StudentTerm);
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
                                    $('#TxtVisionWithContLensOD').val(VisionWithContLensOD);
                                    $('#TxtVisionWithContLensOS').val(VisionWithContLensOS);

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

                                    if(GPCategory2 != '' || ContactPerson2 != ''){
                                        showAddMore();
                                        if(GuardianParent2 == "guardian"){
                                            $('#RadGuardian2').prop('checked', true);
                                        }else if(GuardianParent2 == "parent"){
                                            $('#RadParent2').prop('checked', true);
                                        }else{
                                            $('#RadNone2').prop('checked', true);
                                        }
                                        $('#TGPCategory2').val(GPCategory2);
                                        $('#TxtContactPerson2').val(ContactPerson2);
                                        $('#TxtPGContactNumber2').val(PGContactNumber2);
                                    }else{
                                        $('#RadNone2').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm3').style.display = 'none';
                                        document.getElementById('addMoreForm4').style.display = 'none';
                                    }

                                  
                            }
                                    disableEditing();
                                    modifyVisibleButton('save');
                                    styleAllInput();
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
                    url:"../php/Student/FetchRecords.php",
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
                            var ProvAdd = $(this).attr('ProvAdd');
                            var StudentContactNumber = $(this).attr('StudentContactNumber');
                            var GuardianParent = $(this).attr('GuardianParent');
                            var GPCategory = $(this).attr('GPCategory');
                            var ContactPerson = $(this).attr('ContactPerson');
                            var PGContactNumber = $(this).attr('PGContactNumber');
                            var GuardianParent1 = $(this).attr('GuardianParent1');
                            var GPCategory1 = $(this).attr('GPCategory1');
                            var ContactPerson1 = $(this).attr('ContactPerson1');
                            var PGContactNumber1 = $(this).attr('PGContactNumber1');
                            var GuardianParent2 = $(this).attr('GuardianParent2');
                            var GPCategory2 = $(this).attr('GPCategory2');
                            var ContactPerson2 = $(this).attr('ContactPerson2');
                            var PGContactNumber2 = $(this).attr('PGContactNumber2');
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
                            var MedicalOthers = $(this).attr('MedicalOthers');
                            var RLOA = $(this).attr('RLOA');
                            var SchoolYear = $(this).attr('SchoolYear');
                            var StudentTerm = $(this).attr('StudentTerm');
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
                            var VisionWithContLensOD = $(this).attr('VisionWithContLensOD');
                            var VisionWithContLensOS = $(this).attr('VisionWithContLensOS');
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
                            var MSEditor = $(this).attr('MSEditor');

                            TempSex = Sex;
                            TempGuardianParent = GuardianParent;
                            TempGuardianParent1 = GuardianParent1;
                            TempGuardianParent2 = GuardianParent2;
                           


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

                                    disableEditing();
                                    modifyVisibleButton('none');
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

                                    changeFunc(StudentCategory);

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

                                    var PresAddArr = Address.split("||");
                                    //$('#TxtAddress').val(Address);
                                    $('#TxtPresAddHouseNo').val(PresAddArr[0]);
                                    $('#TxtPresAddStreet').val(PresAddArr[1]);
                                    $('#TxtPresAddBrgy').val(PresAddArr[2]);
                                    $('#TxtPresAddMunicipal').val(PresAddArr[3]);
                                    $('#TxtPresAddProvince').val(PresAddArr[4]);

                                    var ProvAddArr = ProvAdd.split("||");
                                    //$('#TxtProvAdd').val(ProvAdd);
                                    $('#TxtProvAddHouseNo').val(ProvAddArr[0]);
                                    $('#TxtProvAddStreet').val(ProvAddArr[1]);
                                    $('#TxtProvAddBrgy').val(ProvAddArr[2]);
                                    $('#TxtProvAddMunicipal').val(ProvAddArr[3]);
                                    $('#TxtProvAddProvince').val(ProvAddArr[4]);

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
                                    document.getElementById('TxtMSFullName').innerHTML = '&nbsp&nbsp&nbsp'+(StaffLastname + ", " + StaffFirstname + " " + StaffMiddlename + " " + StaffExtension).toUpperCase();

                                    var editedByDD = document.getElementById("TxtMSEditorDrop");
                                    editedByDD.options.length = 0;
                                    const MSEditorDDArr = MSEditor.split("/");

                                    MSEditorDDArr.forEach((element) => {
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

                                    $('#TxtLMP').val(LMP);
                                    $('#TxtPregnancy').val(Pregnancy);
                                    $('#TxtAllergies').val(Allergies);
                                    $('#TxtSurgeries').val(Surgeries);
                                    $('#TxtInjuries').val(Injuries);
                                    $('#TxtIllness').val(Illness);
                                    $('#TxtMedicalOthers').val(MedicalOthers);
                                    $('#TxtRLOA').val(RLOA);
                                    $('#TxtSchoolYear').val(SchoolYear);
                                    $('#TxtStudentTerm').val(StudentTerm);

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
                                    $('#TxtVisionWithContLensOD').val(VisionWithContLensOD);
                                    $('#TxtVisionWithContLensOS').val(VisionWithContLensOS);

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

                                    if(GPCategory2 != '' || ContactPerson2 != ''){
                                        showAddMore();
                                        if(GuardianParent2 == "guardian"){
                                            $('#RadGuardian2').prop('checked', true);
                                        }else if(GuardianParent2 == "parent"){
                                            $('#RadParent2').prop('checked', true);
                                        }else{
                                            $('#RadNone2').prop('checked', true);
                                        }
                                        $('#TGPCategory2').val(GPCategory2);
                                        $('#TxtContactPerson2').val(ContactPerson2);
                                        $('#TxtPGContactNumber2').val(PGContactNumber2);
                                    }else{
                                        $('#RadNone2').prop('checked', true);
                                        document.getElementById('addMore').style.display = 'none';
                                        document.getElementById('addMoreForm1').style.display = 'none';
                                        document.getElementById('addMoreForm3').style.display = 'none';
                                        document.getElementById('addMoreForm4').style.display = 'none';
                                    }

                                    disableEditing();
                                    modifyVisibleButton('save');
                                    styleAllInput();
                            }
                                    
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

            function fetchMissinginfo2(){
                var emptyfields = "";
                var isdate = document.getElementById('TxtDate');
                var istime = document.getElementById('TxtTime');

                if(isdate.value == "" ){
                    emptyfields += "Date ";
                }

                if(istime.value == "" ){
                    emptyfields += "Time ";
                }

                if(emptyfields == ''){
                    return true;
                }else{
                    $.alert(
                        {theme: 'modern',
                        content: emptyfields,
                        title:'Please Fillout missing information', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    return false;
                }
            }

            function fetchMissingInfo(){
                var emptyfields = "";
                var iscategory = document.getElementById('TxtStudentCategory');
                var iscourse = document.getElementById('TCourse');
                var isyear = document.getElementById('TYear');
                var isfirstname = document.getElementById('TxtFirstname');
                var ismiddlename = document.getElementById('TxtMiddlename');
                var islastname = document.getElementById('TxtLastname');
                var isbirthdate = document.getElementById('TxtBirthdate');
                var ismale = document.getElementById('RadMale');
                var isfemale = document.getElementById('RadFemale');

                /*var isaddress = document.getElementById('TxtAddress');
                var isprovaddress = document.getElementById('TxtProvAdd');*/
                

                if(iscategory.value == ""){
                    emptyfields += "Category / ";
                }

                if(iscategory.value == 'college'){
                    if(iscourse.value == "" ){
                    emptyfields += "Degree / ";
                    }

                    if(isyear.value == "" ){
                    emptyfields += "Year / ";
                    }
                }else if(iscategory.value == 'senior highschool'){
                    if(isyear.value == "" ){
                    emptyfields += "Grade / ";
                    }

                    if(iscourse.value == "" ){
                    emptyfields += "Strand / ";
                    }
                }else if(iscategory.value == 'junior highschool' || iscategory.value == 'elementary'){
                    if(isyear.value == "" ){
                    emptyfields += "Grade / ";
                    }

                }else{
                    if(iscourse.value == "" ){
                    emptyfields += "Degree / ";
                    }

                    if(isyear.value == "" ){
                    emptyfields += "Year / ";
                    }
                }
                

                if(isfirstname.value == "" ){
                    emptyfields += "Firstname / ";
                }

                if(ismiddlename.value == "" ){
                    emptyfields += "Middlename / ";
                }

                if(islastname.value == "" ){
                    emptyfields += "Lastname / ";
                }

                if(isbirthdate.value == "" ){
                    emptyfields += "Birthdate / ";
                }

                if(!ismale.checked && !isfemale.checked){
                    emptyfields += "Sex / ";
                }

                /*if(isaddress.value == "" ){
                    emptyfields += "Address /";
                }

                if(isprovaddress.value == "" ){
                    emptyfields += "Provincial Address ";
                }*/

                emptyfields = emptyfields.substring(0, emptyfields.length - 2);
                
                if(emptyfields == ''){
                    return true;
                }else{

                    document.getElementById('tab1').click();

                    $.alert(
                        {theme: 'modern',
                        content: emptyfields,
                        title:'Please fillout missing information', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'

                        }}});

                    

                    return false;
                }


                
            }

            function changeCSS(){
                                
                var iscomplete = fetchMissingInfo();

                if(iscomplete){
                    document.getElementById('tab2').click();

                    document.getElementById('TxtStudentIDNumber1').innerHTML = 'ID Number: ' + document.getElementById('TxtStudentIDNumber').value;
                    document.getElementById('TxtStudentFullName').innerHTML = 'Full Name: ' + (document.getElementById('TxtLastname').value + ", " + document.getElementById('TxtFirstname').value + " " + document.getElementById('TxtMiddlename').value + " " + document.getElementById('TxtExtension').value).toUpperCase(); 
                }else{
                    

                }

            }


            

            function saveRecords(form_data)
            {   
                var TxtMSEditor = '';
                TxtMSEditor = '<?php echo $userID; ?> - <?php echo ucwords($userFName); ?> - <?php echo $userdate; ?>';
                TxtUserEdit = '<?php echo $userID; ?>';
                TxtEditDate = '<?php echo $userdate; ?>';

                var TxtPresAddHouseNo = document.getElementById("TxtPresAddHouseNo").value;
                var TxtPresAddStreet = document.getElementById("TxtPresAddStreet").value;
                var TxtPresAddBrgy = document.getElementById("TxtPresAddBrgy").value;
                var TxtPresAddMunicipal = document.getElementById("TxtPresAddMunicipal").value;
                var TxtPresAddProvince = document.getElementById("TxtPresAddProvince").value;

                var TxtProvAddHouseNo = document.getElementById("TxtProvAddHouseNo").value;
                var TxtProvAddStreet = document.getElementById("TxtProvAddStreet").value;
                var TxtProvAddBrgy = document.getElementById("TxtProvAddBrgy").value;
                var TxtProvAddMunicipal = document.getElementById("TxtProvAddMunicipal").value;
                var TxtProvAddProvince = document.getElementById("TxtProvAddProvince").value;

                var TxtPresAdd = TxtPresAddHouseNo +'||' +TxtPresAddStreet +'||' +TxtPresAddBrgy +'||' +TxtPresAddMunicipal +'||' +TxtPresAddProvince;
                var TxtProvAdd = TxtProvAddHouseNo +'||' +TxtProvAddStreet +'||' +TxtProvAddBrgy +'||' +TxtProvAddMunicipal +'||' +TxtProvAddProvince;

                form_data.append("TxtMSEditor", TxtMSEditor);
                form_data.append("TxtUserEdit", TxtUserEdit);
                form_data.append("TxtEditDate", TxtEditDate);
                form_data.append("TxtAddress", TxtPresAdd);
                form_data.append("TxtProvAdd", TxtProvAdd);

                $.ajax(
                { 
                    url:"../php/Student/SaveUser.php",
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
                              
                                message="Edited existing student information";
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
                                    window.history.go(0);
                                    window.scrollTo(0,0);
                                }, 2000);
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
                var TxtMSEditor = '';
                TxtMSEditor = '<?php echo $userID; ?> - <?php echo ucwords($userFName); ?> - <?php echo $userdate; ?>';
                TxtUserEdit = '<?php echo $userID; ?>';
                TxtEditDate = '<?php echo $userdate; ?>';

                form_data.append("TxtMSEditor", TxtMSEditor);
                form_data.append("TxtUserEdit", TxtUserEdit);
                form_data.append("TxtEditDate", TxtEditDate);

                var TxtPresAddHouseNo = document.getElementById("TxtPresAddHouseNo").value;
                var TxtPresAddStreet = document.getElementById("TxtPresAddStreet").value;
                var TxtPresAddBrgy = document.getElementById("TxtPresAddBrgy").value;
                var TxtPresAddMunicipal = document.getElementById("TxtPresAddMunicipal").value;
                var TxtPresAddProvince = document.getElementById("TxtPresAddProvince").value;

                var TxtProvAddHouseNo = document.getElementById("TxtProvAddHouseNo").value;
                var TxtProvAddStreet = document.getElementById("TxtProvAddStreet").value;
                var TxtProvAddBrgy = document.getElementById("TxtProvAddBrgy").value;
                var TxtProvAddMunicipal = document.getElementById("TxtProvAddMunicipal").value;
                var TxtProvAddProvince = document.getElementById("TxtProvAddProvince").value;

                var TxtPresAdd = TxtPresAddHouseNo +'||' +TxtPresAddStreet +'||' +TxtPresAddBrgy +'||' +TxtPresAddMunicipal +'||' +TxtPresAddProvince;
                var TxtProvAdd = TxtProvAddHouseNo +'||' +TxtProvAddStreet +'||' +TxtProvAddBrgy +'||' +TxtProvAddMunicipal +'||' +TxtProvAddProvince;

                form_data.append("TxtAddress", TxtPresAdd);
                form_data.append("TxtProvAdd", TxtProvAdd);
                $.ajax(
                {
                    url:"../php/Student/addRecords.php",
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
                                id_stud = document.getElementById('TxtStudentIDNumber').value;
                                
                                Result="added new student information";
                                $.alert({
                                    theme: 'modern',
                                    content:'Successfully '+Result,
                                    title:"", 
                                    buttons:{
                                    Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});
                                setTimeout(function(){
                                   window.location.href="indexStudentSummary.php";
                                }, 2000);
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
                    /*document.getElementById("nav2").style.width = "9.33%";
                    document.getElementById("nav3").style.width = "9.33%";
                    document.getElementById("nav4").style.width = "9.33%";
                    document.getElementById("nav5").style.width = "9.33%";
                    document.getElementById("nav7").style.width = "9.33%";
                    document.getElementById("nav8").style.width = "9.33%";*/

                }

                $("#add-personal-information").keypress(preventEnterSubmit);

                $('#TCourse').change(function(){
                    var InpVal = $(this).val();
                    if (InpVal == '-- Add Course to the list --'){
                        $('#DegreeNewModal').modal('show');
                        $('#TCourse').val('');
                    }
                })

                /*$("#BtnCFU").click(function{
                    var date = document.getElementById("TxtDate").value;
                    var time = document.getElementById("TxtTime").value;
                    var studID = document.getElementById("TxtStudentIDNumber2").value;

                    if (studID || date || time){
                        alert('123123');
                    }
                });*/


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
                    var ispage1 = fetchMissingInfo();
                    var ispage2 = fetchMissinginfo2();
                    if(TempBtnValue == "save"){
                        if(ispage1 && ispage2){
                            saveRecords(form_data);
                        }
                        
                    }else{
                        if(ispage1 && ispage2){
                            addRecords(form_data);
                        }
                        
                    }
                });

                $("#degree-form").submit(function(event)
                {    
                    event.preventDefault();
                    var form_data = new FormData(this);            
                    $.ajax(
                    {
                        url:"../php/Student/addDegree.php",
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
                                    
                                    Result="Added new degree";
                                    $.alert({
                                        theme: 'modern',
                                        content:'Successfully '+Result,
                                        title:"", 
                                        buttons:{
                                        Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-green'
                                        }}});

                                    $('#DegreeNewModal').modal('hide');

                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                    
                                }



                                
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

                    if(getType == 'viewRecord'){
                        fetchHistory(studentid,editorID,editDate);
                    }
                    //window.location.href = 'newRecord.php?id_stud='+id_stud+'&staffIDnum=' +editorID + '&editdate='+editDate+'&type=viewRecordHistory';

                    //alert('id_stud='+id_stud+'staffIDnum=' +editorID + '&editdate='+editDate+'&type=viewRecordHistory');
                })

                $('#degree-category').change(function(){
                    var degreeSelected = document.getElementById('degree-category').value;
                    if(degreeSelected == 'senior highschool'){
                        document.getElementById('degree_course_lbl').textContent = 'Strand';
                        document.getElementById('degree_course_lbl_accr').textContent = 'Strand Acronym';
                        
                    }else{
                        document.getElementById('degree_course_lbl').textContent = 'Degree';
                        document.getElementById('degree_course_lbl_accr').textContent = 'Degree Acronym';
                    }
                })
            }); 
        </script>
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?> 
        <div class="container" id="toDownloadPDF">
            <div class="tabs">
                <div class="tabs-head" id="tabsTitle">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Personal Information&nbsp;&bull;</span>
                    <span id="tab2" class="tabs-toggle" onclick="changeCSS()">&bull;&nbsp;Medical Information&nbsp;&bull;</span>
                    <span id="wholetab" class="tabs-toggle">&bull;&nbsp;Student Personal & Medical Record&nbsp;&bull;</span>
                </div>

                <div class="modal fade" id="DegreeNewModal" tabindex="-1" role="dialog" aria-labelledby="DegreeNewModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="DegreeNewModalLabel">Add New Degree</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <form action="#" method="post" id="degree-form" autocomplete="off">
                            <div class="modal-body">
                                        
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label" >Category</label>
                                    <!-- <input type="text" class="form-control" name="degree-category" id="degree-category" required> -->
                                    <select class="form-control" name="degree-category" id="degree-category" required>
                                        <option value="" hidden>Select Category</option>
                                        <option value="senior highschool">Senior Highschool</option>
                                        <option value="college">College</option>
                                        <option value="graduate">Graduate School</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label id="degree_course_lbl" for="message-text" class="col-form-label" >Degree</label>
                                    <input type="text" class="form-control" name="degree-name" id="degree-name" required>
                                </div>
                                <div class="form-group">
                                    <label id="degree_course_lbl_accr" for="message-text" class="col-form-label" >Degree Acronym</label>
                                    <input type="text" class="form-control" name="degree-acr" id="degree-acr" required>
                                </div>
                                        
                            </div>
                                    
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="Submit" class="btn btn-success">Add Degree</button>
                            </div>
                        </form>
                    </div>
                    </div>
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
                                        <input type="text" name="TxtDocumentCode" id="TxtDocumentCode" value="QF-UHS-MC-" readonly>
                                    </td>
                                    <td class="RevisionNumber">
                                        <label for="TxtRevisionNumber"> 
                                            Revision Number:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="number" id="TxtRevisionNumber" name="TxtRevisionNumber" maxlength="4" onkeypress="return isNumber1DecimalKey(this,event)" readonly>
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
                                    <p class="img-instructions">(Upload file not more than 1 MB)</p><br>
                                    <label for="TxtStudentImage">Student Image</label><br>
                                    <input name="TxtStudentImage" onchange="LoadImage(this);" accept="image/jpg, image/jpeg, image/png" type="file" id="TxtStudentImage" disabled>
                                </div>
                            </div>
                            <div class="Three-Info">
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
                                    <select id="TxtStudentCategory" onchange="changeFunc(this.value)" name="TxtStudentCategory" disabled>
                                        <option style="display:none"></option>
                                        <option id="elementary" value="elementary">Elementary</option>
                                        <option id="highschool" value="junior highschool">Junior Highschool</option>
                                        <option id="senior" value="senior highschool">Senior Highschool</option>
                                        <option id="college" value="college">College</option>
                                        <option id="graduate" value="graduate">Graduate</option>
                                    </select>
                                </div>

                            </div>

                            <div class="One-Info">
                                <legend>Academic Information</legend>
                            </div>

                            <div class="Two-Info">
                                <div class="Course" id="Cour">
                                    <label id="CS" for="TxtCourse">Degree</label> <span id="req">*</span>
                                    <input id="TCourse" list="TxtCourse" name="TxtCourse" onkeydown="return alphaOnly(event);" disabled>
                                    <datalist id="TxtCourse">

                                    </datalist>
                                </div>
                                <!-- <div class="btn-new-degree" id="NewDegr">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#DegreeNewModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                                        </svg>
                                    </button>

                                </div> -->
                            </div>
                            <div class="Two-Info">
                                <div class="Year">
                                    <label id="YR" for="TxtYear">Year</label>  <span id="req">*</span>
                                    <input id="TYear" list="TxtYear" name="TxtYear" onkeydown="return allowLetterNumber(event);" disabled>
                                    <datalist id="TxtYear">

                                    </datalist>
                                </div>
                                <div class="Section">
                                    <label for="TxtSection">Section</label> 
                                    <input id="TSection" list="TxtSection" name="TxtSection" onkeydown="return alphaOnly(event);" disabled >
                                    <datalist id="TxtSection">

                                    </datalist>
                                </div>
                            </div>
                            <div class="One-Info">
                                <legend>Personal Information</legend>
                            </div>
                            <div class="Four-Info">
                                    <div class="Lastname">
                                        <label for="TxtLastname">Last Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtLastname" id="TxtLastname" onkeydown="return alphaName(event);" onchange="checkNameLength(this);" readonly>
                                    </div>
                                    <div class="Firstname">
                                        <label for="TxtFirstname">First Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtFirstname" id="TxtFirstname" onkeydown="return alphaName(event);" onchange="checkNameLength(this);" readonly>
                                    </div>
                                    <div class="Middlename">
                                        <label for="TxtMiddlename">Middle Name</label> <span id="req">*</span>
                                        <input type="text" name="TxtMiddlename" id="TxtMiddlename" onkeydown="return alphaName(event);" onchange="checkNameLength(this);" readonly>
                                    </div>
                                    <div class="Extension">
                                        <label for="TxtExtension">Extension Name</label>
                                        <input type="text" name="TxtExtension" id="TxtExtension" onkeydown="return alphaName(event);" readonly>
                                    </div>
                            </div>
                            <div class="Three-Info">
                                    <div class="Birthdate">
                                        <label for="TxtBirthdate">Birthdate</label> <span id="req">*</span>
                                        <input type="date" name="TxtBirthdate" id="TxtBirthdate" onblur="ageCalculator()"  readonly>
                                    </div>
                                    <div class="Age"> 
                                        <label for="TxtAge">Age</label>
                                        <input type="number" name="TxtAge" id="TxtAge" onkeypress="return isNumberKey(this,event)" readonly>
                                    </div>
                                    <div class="Sex"> 
                                        <label for="RadSex">Sex</label><span id="req">*</span><br>
                                        <label class="SecSex">
                                            <input type="radio" class="RadSex" id="RadMale" name="RadSex" value="Male"  disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Male</span>
                                        </label>
                                        &nbsp
                                        <label class="SecSex">
                                            <input type="radio" class="RadSex" id="RadFemale" name="RadSex" value="Female" disabled>
                                            <span class="RadDesign"></span>
                                            <span class="RadText">Female</span>
                                        </label>
                                    </div>
                            </div>
                            <div class="Two-Info">
 
                                    <div class="StudentContactNumber">
                                        <label for="TxtStudentContactNumber">Student Contact Number</label>
                                        <input type="text" name="TxtStudentContactNumber" id="TxtStudentContactNumber" onchange="checkContNumFormat(this)" onkeypress="return isNumberKey(this,event)" minlength="13" maxlength="13" readonly>
                                    </div>
                            </div>

                            <div class="Five-Info">
                                <div class="PresentAddress">
                                    <legend>Present Address</legend>
                                    <div class="AddHouseNo"> 
                                        <label for="TxtPresAddHouseNo">House No.</label>
                                        <input type="text" maxlength="4" name="TxtPresAddHouseNo" id="TxtPresAddHouseNo" readonly>
                                    </div>
                                    <div class="AddStreet"> 
                                        <label for="TxtPresAddStreet">Street</label>
                                        <input type="text" maxlength="15" name="TxtPresAddStreet" id="TxtPresAddStreet" readonly>
                                    </div>
                                    <div class="AddBarangay"> 
                                        <label for="TxtPresAddBrgy">Barangay</label>
                                        <input type="text" maxlength="15" name="TxtPresAddBrgy" id="TxtPresAddBrgy" readonly>
                                    </div>
                                    <div class="AddMunicipality"> 
                                        <label for="TxtPresAddMunicipal">Municipality</label>
                                        <input type="text" maxlength="15" name="TxtPresAddMunicipal" id="TxtPresAddMunicipal" readonly>
                                    </div>
                                    <div class="AddProvince"> 
                                        <label for="TxtPresAddProvince">Province</label>
                                        <input type="text" maxlength="10" name="TxtPresAddProvince" id="TxtPresAddProvince" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="Five-Info">
                                <div class="ProvAddress">
                                    <legend>Provincial Address</legend>
                                    <div class="AddHouseNo"> 
                                        <label for="TxtPresAddHouseNo">House No.</label>
                                        <input type="text" maxlength="4" name="TxtProvAddHouseNo" id="TxtProvAddHouseNo" readonly>
                                    </div>
                                    <div class="AddStreet"> 
                                        <label for="TxtPresAddStreet">Street</label>
                                        <input type="text" maxlength="15" name="TxtProvAddStreet" id="TxtProvAddStreet" readonly>
                                    </div>
                                    <div class="AddBarangay"> 
                                        <label for="TxtPresAddBrgy">Barangay</label>
                                        <input type="text" maxlength="15" name="TxtProvAddBrgy" id="TxtProvAddBrgy" readonly>
                                    </div>
                                    <div class="AddMunicipality"> 
                                        <label for="TxtPresAddMunicipal">Municipality</label>
                                        <input type="text" maxlength="15" name="TxtProvAddMunicipal" id="TxtProvAddMunicipal" readonly>
                                    </div>
                                    <div class="AddProvince"> 
                                        <label for="TxtPresAddProvince">Province</label>
                                        <input type="text" maxlength="10" name="TxtProvAddProvince" id="TxtProvAddProvince" readonly>
                                    </div>
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

                            <!-- add more form 1 -->
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

                            <!-- add more form 1 -->
                            <hr id="addMoreForm1" style="display: none;">
                            <div class="Two-Info" id="addMoreForm3" style="display: none;">
                                <div class="GuardianParent">
                                    <label for="RadGuardianParent2"></label>
                                    <label class="SecGuardianParent2"> <br>
                                        <input type="radio" class="RadGuardianParent2" id="RadGuardian2" name="RadGuardianParent2" value="Guardian" onclick="clickedGuardian2()" disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Guardian</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent2"> 
                                        <input type="radio" class="RadGuardianParent2" id="RadParent2" name="RadGuardianParent2" value="Parent" onclick="clickedParent2()" disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">Parent</span>
                                    </label>
                                    &nbsp
                                    <label class="SecGuardianParent2"> 
                                        <input type="radio" class="RadGuardianParent2" id="RadNone2" name="RadGuardianParent2" value="None" onclick="clickedNone2()" checked disabled>
                                        <span class="RadDesign"></span>
                                        <span class="RadText">None</span>
                                    </label>
                                </div>
                                    <div class="GPCategory">
                                        <label for="TxtGPCategory2">Category</label> 
                                        <input id="TGPCategory2" list="TxtGPCategory2" name="TxtGPCategory2"  onkeydown="return alphaOnly(event);" disabled>
                                        <datalist id="TxtGPCategory2">
                                            <option id="father2" value=""></option>
                                            <option id="mother2" value=""></option>
                                            <option id="sibling2" value=""></option>
                                            <option id="grandparents2" value=""></option>
                                            <option id="ward2" value=""></option>
                                        </datalist>
                                    </div>
                                </nobr>
                            </div>
                            <div class="Two-Info" id="addMoreForm4" style="display: none;">
                                    <div class="ContactPerson"> 
                                        <label for="TxtContactPerson2">Contact Person</label> 
                                        <input type="text" name="TxtContactPerson2" id="TxtContactPerson2" onkeydown="return alphaOnlyCP(event);" placeholder="Last Name, First Name" readonly>
                                    </div>
                                    <div class="PGContactNumber">
                                        <label for="TxtPGContactNumber2">Contact Number of Parent/Guardian</label> 
                                        <input type="text" name="TxtPGContactNumber2" id="TxtPGContactNumber2" onkeypress="return isNumberKey(this,event)" onchange="checkContNumFormat(this)" minlength="13" maxlength="13" readonly>
                                    </div>
                            </div>

                            <span id="addMore" onclick="showAddMore()">+ Add More</span>
                            <div id="exportButton" data-html2canvas-ignore="true">
                                <div class="submit">
                                    <button type="button" id ="BtnPrint" class=form-button onclick="clickedPrint('BtnPrint')"><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg><p> / Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                                </div>
                                <div class="submit hideExportPDF">
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
                                    <input type="Time" name="TxtTime" id="TxtTime" onblur="checkTimeInput(this)" required readonly>
                                </div>
                            
                            </div>
                            
                        
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
                            <div class="MedicalOthers">
                                <label for="TxtMedicalOthers">Others</label>
                                <input type="text" name="TxtMedicalOthers" id="TxtMedicalOthers" readonly>
                            </div>
                        </div>

                        <div class="One-Info">
                            <div class="RLOA mx-3">
                                <label for="TxtRLOA">Reason for leave of Absence (LOA) for ORS</label>
                                <input type="text" name="TxtRLOA" id="TxtRLOA" readonly>
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
                                    <td class="secondColumn">                          
                                            <label for="TxtStudentTerm" id="term">Term</label><br>
                                            <select class="StudentTerm" id="TxtStudentTerm" name="TxtStudentTerm" disabled>
                                                <option style="display:none" value=""></option>
                                                <option id="OptTermFirst" value="First Semester">First Semester</option>
                                                <option id="OptTermMid" value="Midyear">Midyear</option>
                                                <option id="OptTermSecond" value="Second Semester">Second Semester</option>
                                            </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Height">
                                        <label for="TxtHeight">
                                            Height in cm
                                        </label> 
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="TxtHeight" id="TxtHeight" onkeypress="return isNumber1DecimalKey(this,event)" step="any" onchange="calculateBMI()"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Weight">
                                        <label for="TxtWeight">
                                            Weight in kg
                                        </label> 
                                    </td colspan="2">
                                    <td colspan="2">
                                        <input type="number" name="TxtWeight" id="TxtWeight" onkeypress="return isNumber1DecimalKey(this,event)" step="any" onchange="calculateBMI()"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="BMI">
                                        <label for="TxtBMI">
                                            BMI
                                        </label> 
                                    </td>
                                    <td colspan="2">
                                        <input type="text" name="TxtBMI" id="TxtBMI"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="BloodPressure">
                                        <label for="TxtBloodPressure">
                                            Blood Pressure
                                        </label> 
                                    </td>
                                    <td colspan="2">
                                        <input type="text" name="TxtBloodPressure" id="TxtBloodPressure"  readonly>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td class="PulseRate">
                                        <label for="TxtPulseRate">
                                            Pulse Rate
                                        </label> 
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="TxtPulseRate" id="TxtPulseRate" onkeypress="return isNumberKey(this,event)"  readonly>
                                    </td>
                                </tr>

                                 <tr>
                                    <td class="Temperature">
                                        <label for="TxtTemperature">
                                            Temperature in °C
                                        </label> 
                                    </td>
                                    <td colspan="2">
                                        <input type="text" name="TxtTemperature" id="TxtTemperature" onkeypress="return isNumber1DecimalKey(this,event)" step="any"  readonly>
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
                                    <td class="VisionWithContLens">
                                        <label for="TxtVisionWithContLens">
                                            Vision (Snellen's) with Contact Lenses
                                        </label>
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithContLensOD"></label> 
                                        <input type="text" name="TxtVisionWithContLensOD" id="TxtVisionWithContLensOD" placeholder="OD" readonly >
                                    </td>
                                    <td>
                                        <label for="TxtVisionWithContLensOS"></label> 
                                        <input type="text" name="TxtVisionWithContLensOS" id="TxtVisionWithContLensOS" placeholder="OS" readonly >
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
                                            <option id="unremarkableHD" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsHD" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHearingDistance"></label>
                                        <input type="text" name="TAHearingDistance" id="TAHearingDistance" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableSp" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsSp" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TASpeech"></label>
                                        <input type="text" name="TASpeech" id="TASpeech" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableSk" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsSk" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TASkin"></label>
                                        <input type="text" name="TASkin" id="TASkin" cols="0" rows="0"  readonly>
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
                                            <option id="unremarkableHe" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsHe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHead"></label>
                                        <input type="text" name="TAHead" id="TAHead" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableEy" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsEy" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAEyes"></label>
                                        <input type="text" name="TAEyes" id="TAEyes" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableEa" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsEa" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAEars"></label>
                                        <input type="text" name="TAEars" id="TAEars" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableNo" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsNo" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TANose"></label>
                                        <input type="text" name="TANose" id="TANose" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableCT" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsCT" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TACavityAndThroat"></label>
                                        <input type="text" name="TACavityAndThroat" id="TACavityAndThroat" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableLu" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsLu" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TALungs"></label>
                                        <input type="text" name="TALungs" id="TALungs" cols="0" rows="0"  readonly>
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
                                            <option id="unremarkableHea" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsHea" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHeart"></label>
                                        <input type="text" name="TAHeart" id="TAHeart" cols="0" rows="0"  readonly>
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
                                            <option id="unremarkableBr" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsBr" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TABreast"></label>
                                        <input type="text" name="TABreast" id="TABreast" cols="0" rows="0"  readonly>
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
                                            <option id="unremarkableAb" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsAb" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAAbdomen"></label>
                                        <input type="text" name="TAAbdomen" id="TAAbdomen" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableGU" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsGU" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAGenitoUrinary"></label>
                                        <input type="text" name="TAGenitoUrinary" id="TAGenitoUrinary" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableLG" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsLG" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TALymphGlands"></label>
                                        <input type="text" name="TALymphGlands" id="TALymphGlands" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableEx" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsEx" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAExtremities"></label>
                                        <input type="text" name="TAExtremities" id="TAExtremities" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableDe" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsDe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TADeformities"></label>
                                        <input type="text" name="TADeformities" id="TADeformities" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableRE" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsRE" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TARadiologicExams"></label>
                                        <input type="text" name="TARadiologicExams" id="TARadiologicExams" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableBA" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsBA" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TABloodAnalysis"></label>
                                        <input type="text" name="TABloodAnalysis" id="TABloodAnalysis" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableUr" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsUr" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAUrinalysis"></label>
                                        <input type="text" name="TAUrinalysis" id="TAUrinalysis" cols="0" rows="0" readonly>
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
                                            <option id="unremarkableFe" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsFe" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAFecalysis"></label>
                                        <input type="text" name="TAFecalysis" id="TAFecalysis" cols="0" rows="0" readonly>
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
                                            <option id="unremarkablePT" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsPT" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAPregnancyTest"></label>
                                        <input type="text" name="TAPregnancyTest" id="TAPregnancyTest"  cols="0" rows="0" readonly>
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
                                            <option id="unremarkableHB" value="Unremarkable" selected>Unremarkable</option>
                                            <option id="wFindingsHB" value="With Findings">With Findings</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="TAHBSAg"></label>
                                        <input type="text" name="TAHBSAg" id="TAHBSAg" cols="0" rows="0" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Others">
                                        <label for="TxtOthers">
                                            Others
                                        </label>
                                    </td>
                                    <td colspan="2">
                                        <label for="TxtOthers"></label>
                                        <input type="text" name="TxtOthers" id="TxtOthers" cols="0" rows="0" readonly>
                                    </td>
                                </tr>
                        </table>
                        <div class="One-Info" id="pageBreak1">
                            <div class="Remarks" id="RemarksID">
                                <label for="TxtRemarks">Remarks</label> 
                                <input type="text" name="TxtRemarks" id="TxtRemarks" cols="83" rows="10" readonly>
                            </div>
                        </div>
                        <div class="One-Info" id="pageBreak2">
                            <div class="Recommendation" id="RecommendationID">
                                <label for="TxtRecommendation">Recommendation</label> 
                                <input type="text" name="TxtRecommendation" id="TxtRecommendation" cols="76" rows="10" readonly>
                            </div>
                        </div>
                        <div class="One-Info">
                            <div id="MedicalStaffInfo">
                                <legend>Medical Staff</legend>
                                <span id="TxtMSIDNumber1">ID Number:</span><br>
                                <span id="TxtChartedBy">Charted By:</span>
                                <span id="TxtMSFullName"></span><br>
                                <span id="TxtMSEditorTitle">Edited By:</span><br>
                                <select id="TxtMSEditorDrop" name="TxtMSEditorDrop" ></select>
                            </div>
                            <div id="ExaminedBy">
                                    <span id="TxtExaminedBy">Examined By:</span><br>
                            </div>
                        </div>
                        <div id="exportButton1" data-html2canvas-ignore="true">
                            <div class="submit">
                                <button type="button" id ="BtnPrint1" class=form-button onclick="clickedPrint()"><p>Print</p><svg xmlns="http://www.w3.org/2000/svg" class="button__svg" viewBox="0 0 64 64" stroke-width="5" stroke="currentColor" fill="none"><path d="M17.34,39.37H14a3.31,3.31,0,0,1-3.31-3.3V20.77A3.31,3.31,0,0,1,14,17.47H50a3.31,3.31,0,0,1,3.31,3.3v15.3A3.31,3.31,0,0,1,50,39.37H47.18" stroke-linecap="round"/><polyline points="17.34 17.47 17.34 10.59 47.18 10.59 47.18 17.47" stroke-linecap="round"/><rect x="17.34" y="32.02" width="29.84" height="21.39" stroke-linecap="round"/><line x1="21.63" y1="37.93" x2="42.1" y2="37.93" stroke-linecap="round"/><line x1="15.54" y1="32.02" x2="49.15" y2="32.02" stroke-linecap="round"/><line x1="21.76" y1="42.72" x2="42.24" y2="42.72" stroke-linecap="round"/><line x1="22.03" y1="47.76" x2="35.93" y2="47.76" stroke-linecap="round"/><circle cx="46.76" cy="24.04" r="1.75" stroke-linecap="round"/></svg><p> / Export to PDF</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="button__svg" stroke-width="5" stroke="currentColor" fill="none"><path d="M53.5,34.06V53.33a2.11,2.11,0,0,1-2.12,2.09H12.62a2.11,2.11,0,0,1-2.12-2.09V34.06"/><polyline points="42.61 35.79 32 46.39 21.39 35.79"/><line x1="32" y1="46.39" x2="32" y2="7.5"/></svg></button>
                            </div>
                            <div class="submit hideExportPDF">
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
        <div>
            <button type="Submit" class="Upbtn" id="btnUp" name="btnUp" onclick="window.scrollTo(0, 0)"/>
        </div>
        <script src="../js/script-tab.js"></script>
    </body>
</html>

<?php


    $tempo = $_SESSION['accesslevel'];

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
    </script>";


    $id = "";

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "newRecord"){
            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'none';
            document.getElementById('ExaminedBy').style.display = 'none';
            document.getElementById('addMore').style.display = 'none';
            getType = 'newRecord';
            </script>";
        }else if($_GET["type"] == "viewRecord"){

            $id = $_GET["id"];

            $sql = "SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber=$id";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();

            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
            document.getElementById('ExaminedBy').style.display = 'inline-block';
            document.getElementById('addMore').style.display = 'none';
            document.getElementById('BtnClear').style.display = 'none';
            document.getElementById('BtnClear1').style.display = 'none';
            getType = 'viewRecord';
            id_stud = '$id';
            passIDPHP($id);
            </script>";
        }else if($_GET["type"] == "viewArchivedRecord"){

            $id = $_GET["id"];

            $sql = "SELECT * FROM ARCHIVEDSTUDENT WHERE StudentIDNumber=$id";
            $result = $connection->query($sql);
            $Row = $result->fetch_assoc();

            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
            document.getElementById('ExaminedBy').style.display = 'inline-block';
            document.getElementById('addMore').style.display = 'none';
            document.getElementById('BtnClear').style.display = 'none';
            document.getElementById('BtnClear1').style.display = 'none';
            document.getElementById('TxtMSEditorDrop').setAttribute('disabled','disabled');
            getType = 'viewArchivedRecord';
            id_stud = '$id';
            passIDPHP($id);
            </script>";
        }else if($_GET["type"] == "viewRecordHistory"){
            
            $stud_id = $_GET["id_stud"];
            $staffIDnum = $_GET["staffIDnum"];
            $editdate = $_GET["editdate"];

            echo "<script type='text/javascript'>
            document.getElementById('MedicalStaffInfo').style.display = 'inline-block';
            document.getElementById('ExaminedBy').style.display = 'inline-block';
            document.getElementById('addMore').style.display = 'none';
            document.getElementById('BtnClear').style.display = 'none';
            document.getElementById('BtnClear1').style.display = 'none';
            getType = 'viewRecordHistory';
            id_stud = '$stud_id';
            fetchHistory($stud_id, $staffIDnum, $editdate);
            
            </script>";
        }    
    }
?>
