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

//Allow a textbox to allow only numbers and one decimal point
  function isNumberKey(txt,evt){
    var charCode = (evt.which) ? evt.which : evt.keyChode;
    if(charCode > 31 && (charCode < 48 || charCode > 57)){
      return false;
    }
    return true;
  }

  //Allow a textbox to allow only numbers and one decimal point
  function isNumber1DecimalKey(txt,evt){
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