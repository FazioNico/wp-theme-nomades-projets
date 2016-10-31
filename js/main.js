


document.addEventListener("DOMContentLoaded", function(event) {
  console.log("DOM fully loaded and parsed");

  if(document.getElementsByClassName('wp-admin').length>0){
    console.log('-> back')

    // JS for Custom Post type: Projects
    if(document.getElementById('attachementImg')){
      console.log('project edtit ->')
      document.getElementById('attachementImg').addEventListener('click', (event)=>{
        event.preventDefault();
        let imgID = event.target.dataset.id
        console.log(imgID)
        console.log(document.URL+"&deleteImgAtt="+imgID)
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'delete_attachment',
                att_ID: imgID,
                post_type: 'attachment'
            },
            success: function( html ) {
                alert( html );
            }
        });
        //document.location = document.URL+"&deleteImgAtt="+imgID
      })
    }

  }
  else{
    console.log('-> front')

  }
});
