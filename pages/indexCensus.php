<?php
    include "../php/centralConnection.php";
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../../index.html');
    } 

    $query = mysqli_query($connect, "SELECT * FROM USERACCOUNTS");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>BSU - SHCRMS</title>
  <link rel = "icon" href = "../images/BSU-UHSLogo.webp" type = "image/x-icon">
  
  <?php include '../includes/dependencies0.php'; ?>

  <link rel="stylesheet" type="text/css" href="../css/census-style.css">
  <script type="text/javascript">

// ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var globalAL = "";

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
                act = "Checked User Activities." 
                logAction(act);
            }
        // ---------------------------end functions for System Logs---------------------------------------
            function autoArchive(){
                var reason = 'Auto Archived';
                $.ajax({
                    url:"../php/archive.php?type=autoArchive",
                        method:"GET",
                        data:jQuery.param({ archReason:reason }),
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: "xml",
                        success:function(xml)
                        {   
                            $(xml).find('output').each(function(){
                                var message = $(this).attr('Message');
                                var error = $(this).attr('error');


                                if(error == 1){
                                    $.alert(
                                    {theme: 'modern',
                                    content:'Failed in auto archiving records!',
                                    title:'', 
                                    useBootstrap: false,
                                    buttons:{
                                        Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-red'
                                    }}});
                                }/*else{
                                    $.alert(
                                    {theme: 'modern',
                                    content:message,
                                    title:'', 
                                    useBootstrap: false,
                                    buttons:{
                                        Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}});
                                }*/
                                
                            });
                            

                        },
                        error: function (e)
                            {
                                //Display Alert Box
                                $.alert(
                                {theme: 'modern',
                                content:'Failed to execute due to errors',
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

            /*function loadTableData(items, tbodyID) {
              const table = document.getElementById(tbodyID);
              items.forEach( item => {
                let row = table.insertRow();
                let cell0 = row.insertCell(0);
                cell0.innerHTML = item;
              });
            }*/

            $(document).ready(function() {
                autoArchive();



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

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();
                }
            }); 
  </script>
</head>
<body>
  <?php include '../includes/navbar.php'; ?>   
  
  <div class="container">
    
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Student Records &nbsp&nbsp&nbsp</h5>
            <p class="card-text"></p>
            <a href="#" id="linkPM" class="stretched-link"></a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Consultation Records</h5>
            <p class="card-text"></p>
            <a href="#" id="linkCons" class="stretched-link"></a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Follow-up Consultation</h5>
            <p class="card-text"></p>
            <a href="#" id="linkFU" class="stretched-link"></a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Medical Certificate</h5>
            <p class="card-text"></p>
            <a href="#" id="linkMC" class="stretched-link"></a>
          </div>
        </div>
      </div>
    </div>
    <br>
    
    
    <div id="date-filter">
      <label for="dateRange" id="dateRangelbl" style="font-size: 17.5px;">Select Date Range:</label>
      <select id="dateRange" onchange="if(this.value !== 'custom') { changeLink(); updateChart(); updateMaleFemalePieChart(); updateStudentCategoryPieChart(); updateSummaryTable(); }">
        <option value="today" selected>Today</option>
        <option value="yesterday">Yesterday</option>
        <option value="last7days">Last 7 Days</option>
        <option value="last30days">Last 30 Days</option>
        <option value="thismonth">This Month</option>
        <option value="lastmonth">Last Month</option>
        <option value="custom">Custom</option>
      </select>

      <div id="customDates" class="hidden">
        <label for="startDate">Start Date:</label>
        <input type="date" id="start-date">
        <label for="endDate">End Date:</label>
        <input type="date" id="end-date">
        <button id="customButton">Go</button>
      </div>

      <div >
        <a href="#" class="btn btn-primary" id="linkPrintReport" role="button">Generate My Report</a>
        <?php
        if($_SESSION['accesslevel'] == 'superadmin' || $_SESSION['accesslevel'] == 'admin'){
          echo "<a href='javascript:' class='btn btn-primary' id='linkPrintAllReport' role='button'>Generate All Reports</a>";

        }

        ?>
        
        
      </div>
    </div>

    

    <div class="row">
      <div class="col-md-12">
        <div id="myChart"></div>
      </div>
    </div>
    <br>
    <div class="row col-md-12" id="PieCharts">
      <legend class="text-center">Students Registered</legend>
      <div class="chart-container col-md-4">
        <h4 class="text-center">Gender</h4>
        <canvas id="maleFemalePieChart"></canvas>
      </div>
      <div class="col-md-4"></div>
      <div class="chart-container col-md-4">
        <h4 class="text-center">Educational Level</h4>
        <canvas id="studentCategoryPieChart"></canvas>
      </div>
    </div>

    <div class="row col-md-12" id="PieCharts">
      <legend class="text-center">Summary</legend>
      <table class="table table-striped table-bordered text-center" id="table-summary">
          <thead>
            <tr>
              <th rowspan="2">Category</th>
              <th colspan="2">Student Info</th>
              <th colspan="2">Consultation</th>
              <th colspan="2">Follow-up</th>
              <th colspan="2">Medical Certificate</th>
              <th rowspan="2">Total</th>
            </tr>

            <tr>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
            </tr>
            
          </thead>
          <tbody id="tblSummaryBody">
            
          </tbody>
      </table>
      
    </div>

    <div class="row col-md-12" id="PieCharts">
      <legend class="text-center">Summary per Staff</legend>
      <table class="table table-striped table-bordered text-center" id="table-summary">
          <thead>
            <tr>
              <th rowspan="2">Staff Name</th>
              <th colspan="2">Student Info</th>
              <th colspan="2">Consultation</th>
              <th colspan="2">Follow-up</th>
              <th colspan="2">Medical Certificate</th>
              <th rowspan="2">Total</th>
            </tr>

            <tr>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
              <th>Male</th>
              <th>Female</th>
            </tr>
            
          </thead>
          <tbody id="tblSummaryStaffBody">
            
          </tbody>
      </table>
      
    </div>

  </div>

               <script> 
        //Javascript for the pie chart of male and female 
          function updateMaleFemalePieChart() {
            var PieChartMale = localStorage.getItem("PieChartMale");
            var PieChartFemale = localStorage.getItem("PieChartFemale");



            var ctx = document.getElementById('maleFemalePieChart').getContext('2d');
            var previousChart = Chart.getChart(ctx); // Get the previous chart instance
            if (previousChart) {
                previousChart.destroy(); // Destroy the previous chart instance
            }
           
            var maleFemalePieChart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Count: ',
                  data: [PieChartMale, PieChartFemale],
                  backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)'
                  ],
                  borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                plugins:{
                  responsive: true,
                  legend: {
                    position: 'top'
                  },
                  title: {
                    display: false,
                    text: 'Male - Female'
                  }
                }
                
              }
            });
          }

          function updateSummaryTable(){
            var SdataPMElemMale = localStorage.getItem("SdataPMElemMale");
            var SdataPMElemFemale = localStorage.getItem("SdataPMElemFemale");
            var SdataPMJuniorMale = localStorage.getItem("SdataPMJuniorMale");
            var SdataPMJuniorFemale = localStorage.getItem("SdataPMJuniorFemale");
            var SdataPMSeniorMale = localStorage.getItem("SdataPMSeniorMale");
            var SdataPMSeniorFemale = localStorage.getItem("SdataPMSeniorFemale");
            var SdataPMCollegeMale = localStorage.getItem("SdataPMCollegeMale");
            var SdataPMCollegeFemale = localStorage.getItem("SdataPMCollegeFemale");
            var SdataPMGradMale = localStorage.getItem("SdataPMGradMale");
            var SdataPMGradFemale = localStorage.getItem("SdataPMGradFemale");

            var SdataConsElemMale = localStorage.getItem("SdataConsElemMale");
            var SdataConsElemFemale = localStorage.getItem("SdataConsElemFemale");
            var SdataConsJuniorMale = localStorage.getItem("SdataConsJuniorMale");
            var SdataConsJuniorFemale = localStorage.getItem("SdataConsJuniorFemale");
            var SdataConsSeniorMale = localStorage.getItem("SdataConsSeniorMale");
            var SdataConsSeniorFemale = localStorage.getItem("SdataConsSeniorFemale");
            var SdataConsCollegeMale = localStorage.getItem("SdataConsCollegeMale");
            var SdataConsCollegeFemale = localStorage.getItem("SdataConsCollegeFemale");
            var SdataConsGradMale = localStorage.getItem("SdataConsGradMale");
            var SdataConsGradFemale = localStorage.getItem("SdataConsGradFemale");

            var SdataFUElemMale = localStorage.getItem("SdataFUElemMale");
            var SdataFUElemFemale = localStorage.getItem("SdataFUElemFemale");
            var SdataFUJuniorMale = localStorage.getItem("SdataFUJuniorMale");
            var SdataFUJuniorFemale = localStorage.getItem("SdataFUJuniorFemale");
            var SdataFUSeniorMale = localStorage.getItem("SdataFUSeniorMale");
            var SdataFUSeniorFemale = localStorage.getItem("SdataFUSeniorFemale");
            var SdataFUCollegeMale = localStorage.getItem("SdataFUCollegeMale");
            var SdataFUCollegeFemale = localStorage.getItem("SdataFUCollegeFemale");
            var SdataFUGradMale = localStorage.getItem("SdataFUGradMale");
            var SdataFUGradFemale = localStorage.getItem("SdataFUGradFemale");

            var SdataMCElemMale = localStorage.getItem("SdataMCElemMale");
            var SdataMCElemFemale = localStorage.getItem("SdataMCElemFemale");
            var SdataMCJuniorMale = localStorage.getItem("SdataMCJuniorMale");
            var SdataMCJuniorFemale = localStorage.getItem("SdataMCJuniorFemale");
            var SdataMCSeniorMale = localStorage.getItem("SdataMCSeniorMale");
            var SdataMCSeniorFemale = localStorage.getItem("SdataMCSeniorFemale");
            var SdataMCCollegeMale = localStorage.getItem("SdataMCCollegeMale");
            var SdataMCCollegeFemale = localStorage.getItem("SdataMCCollegeFemale");
            var SdataMCGradMale = localStorage.getItem("SdataMCGradMale");
            var SdataMCGradFemale = localStorage.getItem("SdataMCGradFemale");

            $("#tblSummaryBody tr").remove();

            var PMArr = [SdataPMElemMale,SdataPMElemFemale,SdataPMJuniorMale,SdataPMJuniorFemale,SdataPMSeniorMale,SdataPMSeniorFemale,SdataPMCollegeMale,SdataPMCollegeFemale,SdataPMGradMale,SdataPMGradFemale];
            var PMArrNum = PMArr.map(Number);
            var ConsArr = [SdataConsElemMale,SdataConsElemFemale,SdataConsJuniorMale,SdataConsJuniorFemale,SdataConsSeniorMale,SdataConsSeniorFemale,SdataConsCollegeMale,SdataConsCollegeFemale,SdataConsGradMale,SdataConsGradFemale];
            var ConsArrNum = ConsArr.map(Number);
            var FUArr = [SdataFUElemMale,SdataFUElemFemale,SdataFUJuniorMale,SdataFUJuniorFemale,SdataFUSeniorMale,SdataFUSeniorFemale,SdataFUCollegeMale,SdataFUCollegeFemale,SdataFUGradMale,SdataFUGradFemale];
            var FUArrNum = FUArr.map(Number);
            var MCArr = [SdataMCElemMale,SdataMCElemFemale,SdataMCJuniorMale,SdataMCJuniorFemale,SdataMCSeniorMale,SdataMCSeniorFemale,SdataMCCollegeMale,SdataMCCollegeFemale,SdataMCGradMale,SdataMCGradFemale];
            var MCArrNum = MCArr.map(Number);

            var tblData = [];
            var elemSum = 0;
            var juniorSum = 0;
            var seniorSum = 0;
            var collegeSum = 0;
            var gradSum = 0;
            var malePMSum = 0;
            var femalePMSum = 0;
            var maleConsSum = 0;
            var femaleConsSum = 0;
            var maleFUSum = 0;
            var femaleFUSum = 0;
            var maleMCSum = 0;
            var femaleMCSum = 0;

            for (var i = 0; i < PMArrNum.length; i++) {
              if(i == 0 || i == 1){
                elemSum += PMArrNum[i];
              }else if(i == 2 || i == 3){
                juniorSum += PMArrNum[i];
              }else if(i == 4 || i == 5){
                seniorSum += PMArrNum[i];
              }else if(i == 6 || i == 7){
                collegeSum += PMArrNum[i];
              }else if(i == 8 || i == 9){
                gradSum += PMArrNum[i];
              }

              if(i % 2 != 0){
                femalePMSum += PMArrNum[i];
              }else{
                malePMSum += PMArrNum[i];
              }
              
            }

            for (var i = 0; i < ConsArrNum.length; i++) {
              if(i == 0 || i == 1){
                elemSum += ConsArrNum[i];
              }else if(i == 2 || i == 3){
                juniorSum += ConsArrNum[i];
              }else if(i == 4 || i == 5){
                seniorSum += ConsArrNum[i];
              }else if(i == 6 || i == 7){
                collegeSum += ConsArrNum[i];
              }else if(i == 8 || i == 9){
                gradSum += ConsArrNum[i];
              }

              if(i % 2 != 0){
                femaleConsSum += ConsArrNum[i];
              }else{
                maleConsSum += ConsArrNum[i];
              }
              
            }

            for (var i = 0; i < FUArrNum.length; i++) {
              if(i == 0 || i == 1){
                elemSum += FUArrNum[i];
              }else if(i == 2 || i == 3){
                juniorSum += FUArrNum[i];
              }else if(i == 4 || i == 5){
                seniorSum += FUArrNum[i];
              }else if(i == 6 || i == 7){
                collegeSum += FUArrNum[i];
              }else if(i == 8 || i == 9){
                gradSum += FUArrNum[i];
              }

              if(i % 2 != 0){
                femaleFUSum += FUArrNum[i];
              }else{
                maleFUSum += FUArrNum[i];
              }
              
            }

            for (var i = 0; i < MCArrNum.length; i++) {
              if(i == 0 || i == 1){
                elemSum += MCArrNum[i];
              }else if(i == 2 || i == 3){
                juniorSum += MCArrNum[i];
              }else if(i == 4 || i == 5){
                seniorSum += MCArrNum[i];
              }else if(i == 6 || i == 7){
                collegeSum += MCArrNum[i];
              }else if(i == 8 || i == 9){
                gradSum += MCArrNum[i];
              }

              if(i % 2 != 0){
                femaleMCSum += MCArrNum[i];
              }else{
                maleMCSum += MCArrNum[i];
              }
              
            }


            var grandtotal = malePMSum + femalePMSum + maleConsSum + femaleConsSum + maleFUSum + femaleFUSum + maleMCSum + femaleMCSum;

            tblData = [];
            tblData.push(category[0], SdataPMElemMale, SdataPMElemFemale, SdataConsElemMale, SdataConsElemFemale, SdataFUElemMale, SdataFUElemFemale, SdataMCElemMale, SdataMCElemFemale, elemSum) ;
            loadTableData(tblData, 'tblSummaryBody');
            tblData = [];
            tblData.push(category[1], SdataPMJuniorMale, SdataPMJuniorFemale, SdataConsJuniorMale, SdataConsJuniorFemale, SdataFUJuniorMale, SdataFUJuniorFemale, SdataMCJuniorMale, SdataMCJuniorFemale, juniorSum);
            loadTableData(tblData, 'tblSummaryBody');
            tblData = [];
            tblData.push(category[2], SdataPMSeniorMale, SdataPMSeniorFemale, SdataConsSeniorMale, SdataConsSeniorFemale, SdataFUSeniorMale, SdataFUSeniorFemale, SdataMCSeniorMale, SdataMCSeniorFemale, seniorSum);
            loadTableData(tblData, 'tblSummaryBody');
            tblData = [];
            tblData.push(category[3], SdataPMCollegeMale, SdataPMCollegeFemale, SdataConsCollegeMale, SdataConsCollegeFemale, SdataFUCollegeMale, SdataFUCollegeFemale, SdataMCCollegeMale, SdataMCCollegeFemale, collegeSum);
            loadTableData(tblData, 'tblSummaryBody');
            tblData = [];
            tblData.push(category[4], SdataPMGradMale, SdataPMGradFemale, SdataConsGradMale, SdataConsGradFemale, SdataFUGradMale, SdataFUGradFemale, SdataMCGradMale, SdataMCGradFemale, gradSum);
            loadTableData(tblData, 'tblSummaryBody');
            tblData = [];
            tblData.push('Total', malePMSum, femalePMSum, maleConsSum, femaleConsSum, maleFUSum, femaleFUSum, maleMCSum, femaleMCSum, grandtotal);
            loadTableData(tblData, 'tblSummaryBody');

            console.log(malePMSum);

            
          }

          //Javascript for the pie chart of student category
          function updateStudentCategoryPieChart() {
            var sum1 = localStorage.getItem("sum1");
            var sum2 = localStorage.getItem("sum2");
            var sum3 = localStorage.getItem("sum3");
            var sum4 = localStorage.getItem("sum4");
            var sum5 = localStorage.getItem("sum5");

            //alert(sum5);

            var ctx = document.getElementById('studentCategoryPieChart').getContext('2d');
            var previousChart = Chart.getChart(ctx); // Get the previous chart instance
            if (previousChart) {
                previousChart.destroy(); // Destroy the previous chart instance
            }
           
            var studentCategoryPieChart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: ['Elementary', 'Junior Highschool','Senior Highschool', 'Undergraduate', 'Graduate'],
                datasets: [{
                  label: 'Count: ',
                  data: [sum1, sum2, sum3, sum4, sum5],
                  backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(95, 192, 175, 0.5)',
                    'rgba(195, 12, 124, 0.5)'
                  ],
                  borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(195, 12, 124, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                plugins:{
                  responsive: true,
                  legend: {
                    position: 'top'
                  },
                  title: {
                    display: false,
                    text: 'Elementary - Junior Highschool - Senior Highschool - Undergraduate - Graduate'
                  }
                }
                
              }
            });
          }
          
        </script>

        <script> //Javascript for the echart
          //Code for showing the custom date filter
          var dateRangeSelect = document.getElementById("dateRange");
          var customDatesDiv = document.getElementById("customDates");
          var customButton = document.getElementById("customButton");

          dateRangeSelect.addEventListener("change", function() {
            if (dateRangeSelect.value === "custom") {
              customDatesDiv.classList.remove("hidden");
            } else {
              customDatesDiv.classList.add("hidden");
            }
          });

          customButton.addEventListener("click", function() {
            var sDate = document.getElementById('start-date').value;
            var eDate = document.getElementById('end-date').value;
            //updateCustomChart(sDate, eDate);
            updateChart();
            updateMaleFemalePieChart();
            updateStudentCategoryPieChart();
            updateSummaryTable();
          });

          //End code

          

          

          //Code for the echart
          var myChart = echarts.init(document.getElementById('myChart'));

          var dates = [];
          var data1 = [];
          var data2 = [];
          var data3 = [];
          var data4 = [];

          var datamale = [];
          var datafemale = [];
          var dataelem = [];
          var datahs = [];
          var datashs = [];
          var datacollege = [];
          var datagrad = [];

          var staffs = [];

          //data Personal Medical
          var dataPMElemMale = [];
          var dataPMElemFemale = [];
          var dataPMJuniorMale = [];
          var dataPMJuniorFemale = [];
          var dataPMSeniorMale = [];
          var dataPMSeniorFemale = [];
          var dataPMCollegeMale = [];
          var dataPMCollegeFemale = [];
          var dataPMGradMale = [];
          var dataPMGradFemale = [];

          //data Cons
          var dataConsElemMale = [];
          var dataConsElemFemale = [];
          var dataConsJuniorMale = [];
          var dataConsJuniorFemale = [];
          var dataConsSeniorMale = [];
          var dataConsSeniorFemale = [];
          var dataConsCollegeMale = [];
          var dataConsCollegeFemale = [];
          var dataConsGradMale = [];
          var dataConsGradFemale = [];

          //data Followup
          var dataFUElemMale = [];
          var dataFUElemFemale = [];
          var dataFUJuniorMale = [];
          var dataFUJuniorFemale = [];
          var dataFUSeniorMale = [];
          var dataFUSeniorFemale = [];
          var dataFUCollegeMale = [];
          var dataFUCollegeFemale = [];
          var dataFUGradMale = [];
          var dataFUGradFemale = [];

          //data MC
          var dataMCElemMale = [];
          var dataMCElemFemale = [];
          var dataMCJuniorMale = [];
          var dataMCJuniorFemale = [];
          var dataMCSeniorMale = [];
          var dataMCSeniorFemale = [];
          var dataMCCollegeMale = [];
          var dataMCCollegeFemale = [];
          var dataMCGradMale = [];
          var dataMCGradFemale = [];



          var category = ['Elementary' , 'Junior Highschool' , 'Senior Highschool' , 'College' , 'Graduate'];



          /*var startDate = '';
          var endDate = '';*/

          /*var form_data = new FormData();
          form_data.append("startDate", start.toISOString());
          form_data.append("endDate", end.toISOString());*/

          $.ajax(
              { 
                url:"../php/Homepage/FetchCounts.php",
                method:"POST",
                data:"", 
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
                      var datesResult = $(this).attr('dates');
                      var Staffs = $(this).attr('Staffs');
                      var CountPM = $(this).attr('CountPM');
                      var CountCons = $(this).attr('CountCons');
                      var CountFU = $(this).attr('CountFU');
                      var CountMC = $(this).attr('CountMC');
                      var CountMale = $(this).attr('CountMale');
                      var CountFemale = $(this).attr('CountFemale');
                      var CountElem = $(this).attr('CountElem');
                      var CountHS = $(this).attr('CountHS');
                      var CountSHS = $(this).attr('CountSHS');
                      var CountCollege = $(this).attr('CountCollege');
                      var CountGrad = $(this).attr('CountGrad');
                            
                      var elementArr = [];
                      var total = 0;


                      $("#tblSummaryBody tr").remove();

                      const datesResultArr = datesResult.split(",");

                      datesResultArr.forEach((element) => {
                        elementArr = element.split("-");
                        dates.push(elementArr[0] +" " +elementArr[1] +", " +elementArr[2]);  
                      });

                      const StaffsArr = Staffs.split("-");

                      StaffsArr.forEach((element) => {
                        staffs.push(element);  
                      });

                      const CountPMArr = CountPM.split(",");

                      CountPMArr.forEach((element) => {
                        elementArr = element.split("-");

                        dataPMElemMale.push(elementArr[0]);
                        dataPMElemFemale.push(elementArr[1]);
                        dataPMJuniorMale.push(elementArr[2]);
                        dataPMJuniorFemale.push(elementArr[3]);
                        dataPMSeniorMale.push(elementArr[4]);
                        dataPMSeniorFemale.push(elementArr[5]);
                        dataPMCollegeMale.push(elementArr[6]);
                        dataPMCollegeFemale.push(elementArr[7]);
                        dataPMGradMale.push(elementArr[8]);
                        dataPMGradFemale.push(elementArr[9]);

                        total = 0;
                        elementArr.forEach((dataPM) => {
                          total = total + parseInt(dataPM);
                        });
                        data1.push(total);
                        
                      });

                      const CountConsArr = CountCons.split(",");

                      CountConsArr.forEach((element) => {
                        elementArr = element.split("-");


                        dataConsElemMale.push(elementArr[0]);
                        dataConsElemFemale.push(elementArr[1]);
                        dataConsJuniorMale.push(elementArr[2]);
                        dataConsJuniorFemale.push(elementArr[3]);
                        dataConsSeniorMale.push(elementArr[4]);
                        dataConsSeniorFemale.push(elementArr[5]);
                        dataConsCollegeMale.push(elementArr[6]);
                        dataConsCollegeFemale.push(elementArr[7]);
                        dataConsGradMale.push(elementArr[8]);
                        dataConsGradFemale.push(elementArr[9]);


                        total = 0;
                        elementArr.forEach((dataCons) => {
                          total = total + parseInt(dataCons);
                        });
                        data2.push(total);  
                      });

                      //alert(dataConsJuniorMale)

                      const CountFUArr = CountFU.split(",");

                      CountFUArr.forEach((element) => {
                        elementArr = element.split("-");
                        
                        dataFUElemMale.push(elementArr[0]);
                        dataFUElemFemale.push(elementArr[1]);
                        dataFUJuniorMale.push(elementArr[2]);
                        dataFUJuniorFemale.push(elementArr[3]);
                        dataFUSeniorMale.push(elementArr[4]);
                        dataFUSeniorFemale.push(elementArr[5]);
                        dataFUCollegeMale.push(elementArr[6]);
                        dataFUCollegeFemale.push(elementArr[7]);
                        dataFUGradMale.push(elementArr[8]);
                        dataFUGradFemale.push(elementArr[9]);
                        
                        total = 0;
                        elementArr.forEach((dataFU) => {
                          total = total + parseInt(dataFU);
                        });
                        data3.push(total);  
                      });

                      const CountMCArr = CountMC.split(",");

                      CountMCArr.forEach((element) => {
                        elementArr = element.split("-");

                        dataMCElemMale.push(elementArr[0]);
                        dataMCElemFemale.push(elementArr[1]);
                        dataMCJuniorMale.push(elementArr[2]);
                        dataMCJuniorFemale.push(elementArr[3]);
                        dataMCSeniorMale.push(elementArr[4]);
                        dataMCSeniorFemale.push(elementArr[5]);
                        dataMCCollegeMale.push(elementArr[6]);
                        dataMCCollegeFemale.push(elementArr[7]);
                        dataMCGradMale.push(elementArr[8]);
                        dataMCGradFemale.push(elementArr[9]);
                        
                        total = 0;
                        elementArr.forEach((dataMC) => {
                          total = total + parseInt(dataMC);
                        });
                        data4.push(total);  
                      });

                      const CountMaleArr = CountMale.split(",");

                      CountMaleArr.forEach((element) => {
                        datamale.push(element);  
                      });

                      const CountFemaleArr = CountFemale.split(",");

                      CountFemaleArr.forEach((element) => {
                        datafemale.push(element);  
                      });

                      const CountElemArr = CountElem.split(",");

                      CountElemArr.forEach((element) => {
                        dataelem.push(element);  
                      });

                      const CountHSArr = CountHS.split(",");

                      CountHSArr.forEach((element) => {
                        datahs.push(element);  
                      });

                      const CountSHSArr = CountSHS.split(",");

                      CountSHSArr.forEach((element) => {
                        datashs.push(element);  
                      });

                      const CountCollegeArr = CountCollege.split(",");

                      CountCollegeArr.forEach((element) => {
                        datacollege.push(element);  
                      });

                      const CountGradArr = CountGrad.split(",");

                      CountGradArr.forEach((element) => {
                        datagrad.push(element);  
                      });

                      //alert(datagrad);

                      changeLink();
                      updateChart();
                      updateMaleFemalePieChart(); 
                      updateStudentCategoryPieChart();
                      updateSummaryTable();


                            });
              },  
              error: function (e)
              {
                alert('12300');
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

          

          var option = {
            legend: {
              padding: [20, 0, 0, 0],
              data: ['Student Records', 'Consultation Records', 'Follow-up Consultation', 'Medical Certificate']
            },
            xAxis: {
              type: 'category',
              data: dates
            },
            yAxis: {},
            tooltip: {
              trigger: 'axis'
            },
            series: [
              {
                name: 'Student Records',
                type: 'bar',
                data: data1,
                itemStyle: {
                  color: '#33AB5F'
                }
              },
              {
                name: 'Consultation Records',
                type: 'bar',
                data: data2,
                itemStyle: {
                  color: '#8CDBA9'
                }
              },
              {
                name: 'Follow-up Consultation',
                type: 'bar',
                data: data3,
                itemStyle: {
                  color: '#D5F591'
                }
              },
              {
                name: 'Medical Certificate',
                type: 'bar',
                data: data4,
                itemStyle: {
                  color: '#BFA8BB'
                }
              }
            ]
          };

          option.backgroundColor = '#f2eeeb';
          myChart.setOption(option);

          function changeLink(){

            var startDate = "";
            var endDate = "";

            // get the selected filter value
            var selectedFilter = document.getElementById('dateRange').value;

            // set the start and end date based on the selected filter value
            switch (selectedFilter) {
              case 'today':
                startDate = new Date();
                endDate = new Date();
                break;
              case 'yesterday':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 1);
                endDate = new Date();
                endDate.setDate(endDate.getDate() - 1);
                break;
              case 'last7days':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 6);
                endDate = new Date();
                break;
              case 'last30days':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 29);
                endDate = new Date();
                break;
              case 'thismonth':
                startDate = new Date();
                startDate.setDate(1);
                endDate = new Date();
                break;
              case 'lastmonth':
                startDate = new Date();
                startDate.setMonth(startDate.getMonth() - 1);
                startDate.setDate(1);
                endDate = new Date();
                endDate.setDate(0);
                break;
              case 'custom':
                // get the start and end date from the user input
                startDate = new Date(document.getElementById('start-date').value);
                endDate = new Date(document.getElementById('end-date').value);
                break;
            }

            //alert(startDate +'// ' +endDate);

            var yearstart = startDate.getFullYear();
            var monthstart = startDate.getMonth() + 1;
            var daystart = startDate.getDate();
            if (daystart < 10) {
                daystart = '0' + daystart;
            }

            if (monthstart < 10) {
                monthstart = `0${monthstart}`;
            }
            var startDateStr = yearstart + "-" +monthstart  + "-" + daystart;

            var yearend = endDate.getFullYear();
            var monthend = endDate.getMonth() + 1;
            var dayend = endDate.getDate();
            if (dayend < 10) {
                dayend = '0' + dayend;
            }

            if (monthend < 10) {
                monthend = `0${monthend}`;
            }
            var endDateStr = yearend + "-" +monthend  + "-" + dayend;

            //alert(startDateStr +'// ' +endDateStr);



            document.getElementById('linkPM').href="indexStudent.php?type=checkRange&start=" +startDateStr +"&end=" +endDateStr;
            document.getElementById('linkCons').href="indexCons.php?type=checkRange&start=" +startDateStr +"&end=" +endDateStr;
            document.getElementById('linkFU').href="indexFU.php?type=checkRange&start=" +startDateStr +"&end=" +endDateStr;
            document.getElementById('linkMC').href="indexMC.php?type=checkRange&start=" +startDateStr +"&end=" +endDateStr;

            document.getElementById('linkPrintReport').href="../php/printReport.php?start=" +startDateStr +"&end=" +endDateStr +'&id=' +'<?php echo $_SESSION['userID']; ?>' +'&type=own';

            <?php
                if($_SESSION['accesslevel'] == 'superadmin' || $_SESSION['accesslevel'] == 'admin'){
                  echo "document.getElementById('linkPrintAllReport').href='../php/printReport.php?start=' +startDateStr +'&end=' +endDateStr +'&type=all';";

                }

            ?>
            
            
             
          }

          function updateChart() {

            var startDate = "";
            var endDate = "";

            // get the selected filter value
            var selectedFilter = document.getElementById('dateRange').value;

            // set the start and end date based on the selected filter value
            switch (selectedFilter) {
              case 'today':
                startDate = new Date();
                endDate = new Date();
                break;
              case 'yesterday':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 1);
                endDate = new Date();
                endDate.setDate(endDate.getDate() - 1);
                break;
              case 'last7days':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 6);
                endDate = new Date();
                break;
              case 'last30days':
                startDate = new Date();
                startDate.setDate(startDate.getDate() - 29);
                endDate = new Date();
                break;
              case 'thismonth':
                startDate = new Date();
                startDate.setDate(1);
                endDate = new Date();
                break;
              case 'lastmonth':
                startDate = new Date();
                startDate.setMonth(startDate.getMonth() - 1);
                startDate.setDate(1);
                endDate = new Date();
                endDate.setDate(0);
                break;
              case 'custom':
                // get the start and end date from the user input
                startDate = new Date(document.getElementById('start-date').value);
                endDate = new Date(document.getElementById('end-date').value);
                break;
            }

            var startDateString = `${startDate.toLocaleString('en-US', { month: 'long' })} ${startDate.getDate()}, ${startDate.getFullYear()}`;
            var endDateString = `${endDate.toLocaleString('en-US', { month: 'long' })} ${endDate.getDate()}, ${endDate.getFullYear()}`;

            // filter the data based on the selected date range
            var filteredDates = dates.filter(function(date) {
              var d = new Date(date);
              var startDate = new Date(startDateString);
              var endDate = new Date(endDateString);
              return d >= startDate && d <= endDate;
            });



            var filteredData1 = data1.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData2 = data2.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData3 = data3.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData4 = data4.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            var filtereddatamale = datamale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddatafemale = datafemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddataelem = dataelem.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddatahs = datahs.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddatashs = datashs.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddatacollege = datacollege.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filtereddatagrad = datagrad.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            var fdataPMElemMale = dataPMElemMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMElemFemale = dataPMElemFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMJuniorMale = dataPMJuniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMJuniorFemale = dataPMJuniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMSeniorMale = dataPMSeniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMSeniorFemale = dataPMSeniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMCollegeMale = dataPMCollegeMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMCollegeFemale = dataPMCollegeFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMGradMale = dataPMGradMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataPMGradFemale = dataPMGradFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            var fdataConsElemMale = dataConsElemMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsElemFemale = dataConsElemFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsJuniorMale = dataConsJuniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsJuniorFemale = dataConsJuniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsSeniorMale = dataConsSeniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsSeniorFemale = dataConsSeniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsCollegeMale = dataConsCollegeMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsCollegeFemale = dataConsCollegeFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsGradMale = dataConsGradMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataConsGradFemale = dataConsGradFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            var fdataFUElemMale = dataFUElemMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUElemFemale = dataFUElemFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUJuniorMale = dataFUJuniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUJuniorFemale = dataFUJuniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUSeniorMale = dataFUSeniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUSeniorFemale = dataFUSeniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUCollegeMale = dataFUCollegeMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUCollegeFemale = dataFUCollegeFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUGradMale = dataFUGradMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataFUGradFemale = dataFUGradFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            var fdataMCElemMale = dataMCElemMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCElemFemale = dataMCElemFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCJuniorMale = dataMCJuniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCJuniorFemale = dataMCJuniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCSeniorMale = dataMCSeniorMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCSeniorFemale = dataMCSeniorFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCCollegeMale = dataMCCollegeMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCCollegeFemale = dataMCCollegeFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCGradMale = dataMCGradMale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var fdataMCGradFemale = dataMCGradFemale.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            // Get all the DOM elements with class `card-text`
            var cardTexts = document.querySelectorAll('.card-text');

            var sum1 = 0;
            var sum2 = 0;
            var sum3 = 0;
            var sum4 = 0;

            var summale = 0;
            var sumfemale = 0;
            var sumelem = 0;
            var sumhs = 0;
            var sumshs = 0;
            var sumcollege = 0;
            var sumgrad = 0;

            var SdataPMElemMale = 0;
            var SdataPMElemFemale = 0;
            var SdataPMJuniorMale = 0;
            var SdataPMJuniorFemale = 0;
            var SdataPMSeniorMale = 0;
            var SdataPMSeniorFemale = 0;
            var SdataPMCollegeMale = 0;
            var SdataPMCollegeFemale = 0;
            var SdataPMGradMale = 0;
            var SdataPMGradFemale = 0;

            var SdataConsElemMale = 0;
            var SdataConsElemFemale = 0;
            var SdataConsJuniorMale = 0;
            var SdataConsJuniorFemale = 0;
            var SdataConsSeniorMale = 0;
            var SdataConsSeniorFemale = 0;
            var SdataConsCollegeMale = 0;
            var SdataConsCollegeFemale = 0;
            var SdataConsGradMale = 0;
            var SdataConsGradFemale = 0;

            var SdataFUElemMale = 0;
            var SdataFUElemFemale = 0;
            var SdataFUJuniorMale = 0;
            var SdataFUJuniorFemale = 0;
            var SdataFUSeniorMale = 0;
            var SdataFUSeniorFemale = 0;
            var SdataFUCollegeMale = 0;
            var SdataFUCollegeFemale = 0;
            var SdataFUGradMale = 0;
            var SdataFUGradFemale = 0;

            var SdataMCElemMale = 0;
            var SdataMCElemFemale = 0;
            var SdataMCJuniorMale = 0;
            var SdataMCJuniorFemale = 0;
            var SdataMCSeniorMale = 0;
            var SdataMCSeniorFemale = 0;
            var SdataMCCollegeMale = 0;
            var SdataMCCollegeFemale = 0;
            var SdataMCGradMale = 0;
            var SdataMCGradFemale = 0;

            for (var i = 0; i < filteredData1.length; i++) {
              sum1 += parseInt(filteredData1[i]) ;
            }
            for (var i = 0; i < filteredData2.length; i++) {
              sum2 += parseInt(filteredData2[i]);
            }
            for (var i = 0; i < filteredData3.length; i++) {
              sum3 += parseInt(filteredData3[i]);
            }
            for (var i = 0; i < filteredData4.length; i++) {
              sum4 += parseInt(filteredData4[i]);
            }

            for (var i = 0; i < filtereddatamale.length; i++) {
              summale += parseInt(filtereddatamale[i]);
            }
            for (var i = 0; i < filtereddatafemale.length; i++) {
              sumfemale += parseInt(filtereddatafemale[i]);
            }
            for (var i = 0; i < filtereddataelem.length; i++) {
              sumelem += parseInt(filtereddataelem[i]);
            }
            for (var i = 0; i < filtereddatahs.length; i++) {
              sumhs += parseInt(filtereddatahs[i]);
            }
            for (var i = 0; i < filtereddatashs.length; i++) {
              sumshs += parseInt(filtereddatashs[i]);
            }
            for (var i = 0; i < filtereddatacollege.length; i++) {
              sumcollege += parseInt(filtereddatacollege[i]);
            }
            for (var i = 0; i < filtereddatagrad.length; i++) {
              sumgrad += parseInt(filtereddatagrad[i]);
            }


            for (var i = 0; i < fdataPMElemMale.length; i++) {
              SdataPMElemMale += parseInt(fdataPMElemMale[i]);
            }
            for (var i = 0; i < fdataPMElemFemale.length; i++) {
              SdataPMElemFemale += parseInt(fdataPMElemFemale[i]);
            }
            for (var i = 0; i < fdataPMJuniorMale.length; i++) {
              SdataPMJuniorMale += parseInt(fdataPMJuniorMale[i]);
            }
            for (var i = 0; i < fdataPMJuniorFemale.length; i++) {
              SdataPMJuniorFemale += parseInt(fdataPMJuniorFemale[i]);
            }
            for (var i = 0; i < fdataPMSeniorMale.length; i++) {
              SdataPMSeniorMale += parseInt(fdataPMSeniorMale[i]);
            }
            for (var i = 0; i < fdataPMSeniorFemale.length; i++) {
              SdataPMSeniorFemale += parseInt(fdataPMSeniorFemale[i]);
            }
            for (var i = 0; i < fdataPMCollegeMale.length; i++) {
              SdataPMCollegeMale += parseInt(fdataPMCollegeMale[i]);
            }
            for (var i = 0; i < fdataPMCollegeFemale.length; i++) {
              SdataPMCollegeFemale += parseInt(fdataPMCollegeFemale[i]);
            }
            for (var i = 0; i < fdataPMGradMale.length; i++) {
              SdataPMGradMale += parseInt(fdataPMGradMale[i]);
            }
            for (var i = 0; i < fdataPMGradFemale.length; i++) {
              SdataPMGradFemale += parseInt(fdataPMGradFemale[i]);
            }


            for (var i = 0; i < fdataConsElemMale.length; i++) {
              SdataConsElemMale += parseInt(fdataConsElemMale[i]);
            }
            for (var i = 0; i < fdataConsElemFemale.length; i++) {
              SdataConsElemFemale += parseInt(fdataConsElemFemale[i]);
            }
            for (var i = 0; i < fdataConsJuniorMale.length; i++) {
              SdataConsJuniorMale += parseInt(fdataConsJuniorMale[i]);
            }
            
            for (var i = 0; i < fdataConsJuniorFemale.length; i++) {
              SdataConsJuniorFemale += parseInt(fdataConsJuniorFemale[i]);
            }
            for (var i = 0; i < fdataConsSeniorMale.length; i++) {
              SdataConsSeniorMale += parseInt(fdataConsSeniorMale[i]);
            }
            for (var i = 0; i < fdataConsSeniorFemale.length; i++) {
              SdataConsSeniorFemale += parseInt(fdataConsSeniorFemale[i]);
            }
            for (var i = 0; i < fdataConsCollegeMale.length; i++) {
              SdataConsCollegeMale += parseInt(fdataConsCollegeMale[i]);
            }
            for (var i = 0; i < fdataConsCollegeFemale.length; i++) {
              SdataConsCollegeFemale += parseInt(fdataConsCollegeFemale[i]);
            }
            for (var i = 0; i < fdataConsGradMale.length; i++) {
              SdataConsGradMale += Number(fdataConsGradMale[i]);
            }
            for (var i = 0; i < fdataConsGradFemale.length; i++) {
              SdataConsGradFemale += parseInt(fdataConsGradFemale[i]);
            }
            for (var i = 0; i < fdataFUElemMale.length; i++) {
              SdataFUElemMale += parseInt(fdataFUElemMale[i]);
            }
            for (var i = 0; i < fdataFUElemFemale.length; i++) {
              SdataFUElemFemale += parseInt(fdataFUElemFemale[i]);
            }
            for (var i = 0; i < fdataFUJuniorMale.length; i++) {
              SdataFUJuniorMale += parseInt(fdataFUJuniorMale[i]);
            }
            for (var i = 0; i < fdataFUJuniorFemale.length; i++) {
              SdataFUJuniorFemale += parseInt(fdataFUJuniorFemale[i]);
            }
            for (var i = 0; i < fdataFUSeniorMale.length; i++) {
              SdataFUSeniorMale += parseInt(fdataFUSeniorMale[i]);
            }
            for (var i = 0; i < fdataFUSeniorFemale.length; i++) {
              SdataFUSeniorFemale += parseInt(fdataFUSeniorFemale[i]);
            }
            for (var i = 0; i < fdataFUCollegeMale.length; i++) {
              SdataFUCollegeMale += parseInt(fdataFUCollegeMale[i]);
            }
            for (var i = 0; i < fdataFUCollegeFemale.length; i++) {
              SdataFUCollegeFemale += parseInt(fdataFUCollegeFemale[i]);
            }
            for (var i = 0; i < fdataFUGradMale.length; i++) {
              SdataFUGradMale += parseInt(fdataFUGradMale[i]);
            }
            for (var i = 0; i < fdataFUGradFemale.length; i++) {
              SdataFUGradFemale += parseInt(fdataFUGradFemale[i]);
            }


            for (var i = 0; i < fdataMCElemMale.length; i++) {
              SdataMCElemMale += parseInt(fdataMCElemMale[i]);
            }
            for (var i = 0; i < fdataMCElemFemale.length; i++) {
              SdataMCElemFemale += parseInt(fdataMCElemFemale[i]);
            }
            for (var i = 0; i < fdataMCJuniorMale.length; i++) {
              SdataMCJuniorMale += parseInt(fdataMCJuniorMale[i]);
            }
            for (var i = 0; i < fdataMCJuniorFemale.length; i++) {
              SdataMCJuniorFemale += parseInt(fdataMCJuniorFemale[i]);
            }
            for (var i = 0; i < fdataMCSeniorMale.length; i++) {
              SdataMCSeniorMale += parseInt(fdataMCSeniorMale[i]);
            }
            for (var i = 0; i < fdataMCSeniorFemale.length; i++) {
              SdataMCSeniorFemale += parseInt(fdataMCSeniorFemale[i]);
            }
            for (var i = 0; i < fdataMCCollegeMale.length; i++) {
              SdataMCCollegeMale += parseInt(fdataMCCollegeMale[i]);
            }
            for (var i = 0; i < fdataMCCollegeFemale.length; i++) {
              SdataMCCollegeFemale += parseInt(fdataMCCollegeFemale[i]);
            }
            for (var i = 0; i < fdataMCGradMale.length; i++) {
              SdataMCGradMale += parseInt(fdataMCGradMale[i]);
            }
            for (var i = 0; i < fdataMCGradFemale.length; i++) {
              SdataMCGradFemale += parseInt(fdataMCGradFemale[i]);
            }


            
            //Pass data to be used in the pie charts
            //This is only temporary
            localStorage.setItem("PieChartMale", summale);
            localStorage.setItem("PieChartFemale", sumfemale);
            localStorage.setItem("sum1", sumelem);
            localStorage.setItem("sum2", sumhs);
            localStorage.setItem("sum3", sumshs);
            localStorage.setItem("sum4", sumcollege);
            localStorage.setItem("sum5", sumgrad);

            localStorage.setItem("SdataPMElemMale", SdataPMElemMale);
            localStorage.setItem("SdataPMElemFemale", SdataPMElemFemale);
            localStorage.setItem("SdataPMJuniorMale", SdataPMJuniorMale);
            localStorage.setItem("SdataPMJuniorFemale", SdataPMJuniorFemale);
            localStorage.setItem("SdataPMSeniorMale", SdataPMSeniorMale);
            localStorage.setItem("SdataPMSeniorFemale", SdataPMSeniorFemale);
            localStorage.setItem("SdataPMCollegeMale", SdataPMCollegeMale);
            localStorage.setItem("SdataPMCollegeFemale", SdataPMCollegeFemale);
            localStorage.setItem("SdataPMGradMale", SdataPMGradMale);
            localStorage.setItem("SdataPMGradFemale", SdataPMGradFemale);

            localStorage.setItem("SdataConsElemMale", SdataConsElemMale);
            localStorage.setItem("SdataConsElemFemale", SdataConsElemFemale);
            localStorage.setItem("SdataConsJuniorMale", SdataConsJuniorMale);
            localStorage.setItem("SdataConsJuniorFemale", SdataConsJuniorFemale);
            localStorage.setItem("SdataConsSeniorMale", SdataConsSeniorMale);
            localStorage.setItem("SdataConsSeniorFemale", SdataConsSeniorFemale);
            localStorage.setItem("SdataConsCollegeMale", SdataConsCollegeMale);
            localStorage.setItem("SdataConsCollegeFemale", SdataConsCollegeFemale);
            localStorage.setItem("SdataConsGradMale", SdataConsGradMale);
            localStorage.setItem("SdataConsGradFemale", SdataConsGradFemale);

            //alert(SdataConsJuniorMale)

            localStorage.setItem("SdataFUElemMale", SdataFUElemMale);
            localStorage.setItem("SdataFUElemFemale", SdataFUElemFemale);
            localStorage.setItem("SdataFUJuniorMale", SdataFUJuniorMale);
            localStorage.setItem("SdataFUJuniorFemale", SdataFUJuniorFemale);
            localStorage.setItem("SdataFUSeniorMale", SdataFUSeniorMale);
            localStorage.setItem("SdataFUSeniorFemale", SdataFUSeniorFemale);
            localStorage.setItem("SdataFUCollegeMale", SdataFUCollegeMale);
            localStorage.setItem("SdataFUCollegeFemale", SdataFUCollegeFemale);
            localStorage.setItem("SdataFUGradMale", SdataFUGradMale);
            localStorage.setItem("SdataFUGradFemale", SdataFUGradFemale);

            localStorage.setItem("SdataMCElemMale", SdataMCElemMale);
            localStorage.setItem("SdataMCElemFemale", SdataMCElemFemale);
            localStorage.setItem("SdataMCJuniorMale", SdataMCJuniorMale);
            localStorage.setItem("SdataMCJuniorFemale", SdataMCJuniorFemale);
            localStorage.setItem("SdataMCSeniorMale", SdataMCSeniorMale);
            localStorage.setItem("SdataMCSeniorFemale", SdataMCSeniorFemale);
            localStorage.setItem("SdataMCCollegeMale", SdataMCCollegeMale);
            localStorage.setItem("SdataMCCollegeFemale", SdataMCCollegeFemale);
            localStorage.setItem("SdataMCGradMale", SdataMCGradMale);
            localStorage.setItem("SdataMCGradFemale", SdataMCGradFemale);

          
            // Set the new data for each card
            cardTexts[0].textContent = sum1;
            cardTexts[1].textContent = sum2;
            cardTexts[2].textContent = sum3;
            cardTexts[3].textContent = sum4;

            // update the chart with the filtered data
            myChart.setOption({
              xAxis: {
                data: filteredDates
              },
              series: [
                {
                  name: 'Student Records',
                  type: 'bar',
                  data: filteredData1
                },
                {
                  name: 'Consultation Records',
                  type: 'bar',
                  data: filteredData2
                },
                {
                  name: 'Follow-up Consultation',
                  type: 'bar',
                  data: filteredData3
                },
                {
                  name: 'Medical Certificate',
                  type: 'bar',
                  data: filteredData4
                }
              ]
            });
          }

          function updateCustomChart(sDate, eDate) {
            var startDate = new Date(sDate);
            var endDate = new Date(eDate);
            

            var startDateString = `${startDate.toLocaleString('en-US', { month: 'long' })} ${startDate.getDate()}, ${startDate.getFullYear()}`;
            var endDateString = `${endDate.toLocaleString('en-US', { month: 'long' })} ${endDate.getDate()}, ${endDate.getFullYear()}`;

            // filter the data based on the selected date range
            var filteredDates = dates.filter(function(date) {
              var d = new Date(date);
              var startDate = new Date(startDateString);
              var endDate = new Date(endDateString);
              return d >= startDate && d <= endDate;
            });

            var filteredData1 = data1.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData2 = data2.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData3 = data3.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);
            var filteredData4 = data4.slice(dates.indexOf(filteredDates[0]), dates.indexOf(filteredDates[filteredDates.length - 1]) + 1);

            // Get all the DOM elements with class `card-text`
            var cardTexts = document.querySelectorAll('.card-text');

            var sum1 = 0;
            var sum2 = 0;
            var sum3 = 0;
            var sum4 = 0;

            for (var i = 0; i < filteredData1.length; i++) {
              sum1 += parseInt(filteredData1[i]);
            }
            for (var i = 0; i < filteredData2.length; i++) {
              sum2 += parseInt(filteredData2[i]);
            }
            for (var i = 0; i < filteredData3.length; i++) {
              sum3 += parseInt(filteredData3[i]);
            }
            for (var i = 0; i < filteredData4.length; i++) {
              sum4 += parseInt(filteredData4[i]);
            }
       
            // Set the new data for each card
            cardTexts[0].textContent = sum1;
            cardTexts[1].textContent = sum2;
            cardTexts[2].textContent = sum3;
            cardTexts[3].textContent = sum4;

            // update the chart with the filtered data
            myChart.setOption({
              xAxis: {
                data: filteredDates
              },
              series: [
                {
                  name: 'Student Records',
                  type: 'bar',
                  data: filteredData1
                },
                {
                  name: 'Consultation Records',
                  type: 'bar',
                  data: filteredData2
                },
                {
                  name: 'Follow-up Consultation',
                  type: 'bar',
                  data: filteredData3
                },
                {
                  name: 'Medical Certificate',
                  type: 'bar',
                  data: filteredData4
                }
              ]
            });
          }

          /*window.onload = function() {
            changeLink();
            updateChart();
            updateMaleFemalePieChart();
            updateStudentCategoryPieChart();
            updateSummaryTable();
          };*/
          
          //Code for echart ends here

        </script>
 
        <script src="../js/script-tab.js"></script>
  
</body>
</html>
<?php
$tempo = $_SESSION['accesslevel'];

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
    </script>";
?>