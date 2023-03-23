<?php
    include "../php/centralConnection.php";
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../../index.html');
    } 
    
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
      <select id="dateRange" onchange="if(this.value !== 'custom') { changeLink(); updateChart(); updateMaleFemalePieChart(); updateStudentCategoryPieChart(); }">
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
              <th rowspan="2">Staff Name</th>
              <th colspan="2">Student Info</th>
              <th colspan="2">Consultation</th>
              <th colspan="2">Follow-up</th>
              <th colspan="2">Medical Certificate</th>
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
          <tbody>
            <tr>
              <td>Cadungo, Edrian</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
              <td>123</td>
            </tr>
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
                responsive: true,
                legend: {
                  position: 'top'
                },
                title: {
                  display: true,
                  text: 'Male - Female'
                }
              }
            });
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
                responsive: true,
                legend: {
                  position: 'top'
                },
                title: {
                  display: true,
                  text: 'Elementary - Junior Highschool - Senior Highschool - Undergraduate - Graduate'
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
            updateCustomChart(sDate, eDate);
            updateMaleFemalePieChart();
            updateStudentCategoryPieChart();
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
                      //startDate = $(this).attr('startDate');
                      //endDate = $(this).attr('endDate');
                            
                      const datesResultArr = datesResult.split(",");

                      datesResultArr.forEach((element) => {
                        var elementArr = element.split("-");
                        dates.push(elementArr[0] +" " +elementArr[1] +", " +elementArr[2]);  
                      });

                      //alert(start + "   " + end);

                      const CountPMArr = CountPM.split(",");

                      CountPMArr.forEach((element) => {
                        data1.push(element);  
                      });

                      const CountConsArr = CountCons.split(",");

                      CountConsArr.forEach((element) => {
                        data2.push(element);  
                      });

                      const CountFUArr = CountFU.split(",");

                      CountFUArr.forEach((element) => {
                        data3.push(element);  
                      });

                      const CountMCArr = CountMC.split(",");

                      CountMCArr.forEach((element) => {
                        data4.push(element);  
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

            //alert(sumgrad);
            //Pass data to be used in the pie charts
            //This is only temporary
            localStorage.setItem("PieChartMale", summale);
            localStorage.setItem("PieChartFemale", sumfemale);
            localStorage.setItem("sum1", sumelem);
            localStorage.setItem("sum2", sumhs);
            localStorage.setItem("sum3", sumshs);
            localStorage.setItem("sum4", sumcollege);
            localStorage.setItem("sum5", sumgrad);

          
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

          function fetchCount(startDate, endDate){

                    /*startDate = startDate.toISOString();
                    endDate = endDate.toISOString();*/
                    
                    //alert(startDate +'//' +endDate);

                    var form_data = new FormData();
                    form_data.append("startDate", startDate);
                    form_data.append("endDate", endDate);

                    $.ajax(
                    { 
                        url:"../php/Homepage/FetchCounts.php",
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
                                var datesResult = $(this).attr('dates');
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

                                dates.length = 0;
                                data1.length = 0;
                                data2.length = 0;
                                data3.length = 0;
                                data4.length = 0;
                            
                                const datesResultArr = datesResult.split(",");

                                datesResultArr.forEach((element) => {
                                  var elementArr = element.split("-");
                                  dates.push(elementArr[0] +" " +elementArr[1] +", " +elementArr[2]);  
                                });

                                //alert(dates);

                                const CountPMArr = CountPM.split(",");

                                CountPMArr.forEach((element) => {
                                  data1.push(element);  
                                });

                                const CountConsArr = CountCons.split(",");

                                CountConsArr.forEach((element) => {
                                  data2.push(element);  
                                });

                                const CountFUArr = CountFU.split(",");

                                CountFUArr.forEach((element) => {
                                  data3.push(element);  
                                });

                                const CountMCArr = CountMC.split(",");

                                CountMCArr.forEach((element) => {
                                  data4.push(element);  
                                });


                                /*for (var d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                                  var dateStr = d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                  dates.push(dateStr);
                                  data1.push(Math.floor(Math.random() * 50));
                                  data2.push(Math.floor(Math.random() * 50));
                                  data3.push(Math.floor(Math.random() * 50));
                                  data4.push(Math.floor(Math.random() * 50));
                                }*/

                                     //Display Alert Box
                                    /*$.alert(
                                    {theme: 'modern',
                                    content: 'Date: '+dates +', PM: '+CountPM +', Cons: '+CountCons +', FU: '+CountFU +', MC: '+CountMC +', Male: '+CountMale +', Female: '+CountFemale +', Elem: '+CountElem +', HS: '+CountHS +', SHS: '+CountSHS +', College: '+CountCollege +', Grad: '+CountGrad,
                                    title:'', 
                                    buttons:{
                                        Ok:{
                                        text:'Ok',
                                        btnClass: 'btn-green'
                                    }}}); */

                                //alert(startDate +" " +endDate +" = " +dates);

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

          window.onload = function() {
            changeLink();
            updateChart();
            updateMaleFemalePieChart();
            updateStudentCategoryPieChart();
          };
          
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