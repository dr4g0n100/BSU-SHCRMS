let tabs = document.querySelectorAll('.tabs-toggle'), 
    contents = document.querySelectorAll('.tabs-content');

tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
        contents.forEach((content) => {
            content.classList.remove('is-active');
        });
        tabs.forEach((tab) => {
            tab.classList.remove('is-active');
        });

        contents[index].classList.add('is-active');
        tabs[index].classList.add('is-active');
    })
})

$('body').tooltip({
    selector: '[data-toggle="tooltip"]'
}).click(function () {
    $('[data-toggle="tooltip"]').tooltip("hide");
});

$(document).ready(function() {
  autoArchive();
});



//Allow a textbox to allow only numbers and one decimal point
  function isNumberKey(txt,evt){
    var charCode = (evt.which) ? evt.which : evt.keyChode;
    if(charCode == 46){
      if(txt.value.indexOf('.') === -1){
        return true;
      }else{
        return false;
      }
    }else{
      if(charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    }
    return true;
  }

  function loadTableData(items, tbodyID) {
    const table = document.getElementById(tbodyID);
    let row = table.insertRow();
    items.forEach( item => {
      let cell0 = row.insertCell(-1);
      cell0.innerHTML = item;
    });
  }

  function preventNumOnly(inputElement) {
    var inputValue = inputElement.value;

    // Use a regular expression to check if the input contains only numbers
    const containsOnlyNumbers = /^\d+$/.test(inputValue);

    // If the input contains only numbers, clear the input field
    if (containsOnlyNumbers) {
      $.alert(
        {theme: 'modern',
            content: 'Numbers only input are not allowed',
            title:'', 
            buttons:{
            Ok:{
                text:'Ok',
                btnClass: 'btn-red'
        }}});
      inputElement.value = '';
    }
}

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

