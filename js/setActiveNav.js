function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
  }

  var currentLocation = window.location.pathname;
  var locationArr = currentLocation.split("/");
  var curlocation = locationArr[4];

  var getType = getUrlVars()['type'];
  if(getType){
    getType = getType.replace(/#.*$/, '');
  }
  
  if(curlocation == "indexHomepage.php"){
      document.getElementById('navHome').classList.add('active');
  }else if(curlocation == "newStudent.php" || curlocation == "studentSummary.php" || curlocation == "indexStudentSummary.php"){
      document.getElementById('navCensus').classList.add('active');
  }else if(curlocation == "indexcensus.php"){
      document.getElementById('navCensus').classList.add('active');
  }else if(curlocation == "indexStaff.php"){
    if(getType == 'checkArchivedStaff'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navUser').classList.add('active');
    }   
  }else if(curlocation == "indexStudent.php"){
    if(getType == 'checkArchivedStudent'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navCensus').classList.add('active');
    } 
  }else if(curlocation == "indexCons.php"){
    if(getType == 'checkArchivedConsultation'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navRecord').classList.add('active');
    }
  }else if(curlocation == "indexFU.php"){
    if(getType == 'checkArchivedFollowUp'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navRecord').classList.add('active');
    }
  }else if(curlocation == "indexMC.php"){
    if(getType == 'checkArchivedMC'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navRecord').classList.add('active');
    }   
  }else if(curlocation == "logs.php"){
    if(getType == 'checkArchivedLogs'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navMaintenance').classList.add('active');
    }
  }else if(curlocation == "degreeList.php"){
    document.getElementById('navMaintenance').classList.add('active');  
  }else if(curlocation == "vaccineList.php"){
    document.getElementById('navMaintenance').classList.add('active');  
  }else if(curlocation == "help.php"){
    document.getElementById('navHelp').classList.add('active');  
  }else if(curlocation == "newConsultation.php" || curlocation == "newFollowUp.php" || curlocation == "newMC.php"){
    if(getType == 'viewArchivedRecord' || getType == 'viewArchivedCons' || getType == 'viewArchivedFollowUp' || getType == 'viewArchivedMC'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navRecord').classList.add('active');
    }
    
  }else if(curlocation == "newStaff.php"){
    if(getType == 'viewArchivedRecord'){
        document.getElementById('navArchive').classList.add('active');
    }else{
        document.getElementById('navUser').classList.add('active');
    }
    
  }