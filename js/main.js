


document.addEventListener("DOMContentLoaded", function(event) {
  console.log("DOM fully loaded and parsed");

  if(document.getElementsByClassName('wp-admin').length>0){
    console.log('-> back')

    // JS for Custom Post type: Projects
    if(document.getElementById('attachementImg')){
      console.log('project edtit ->')
      document.getElementById('attachementImg').addEventListener('click', function(event){
        console.log(event)
      })
    }

  }
  else{
    console.log('-> front')

  }
});
