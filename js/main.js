


document.addEventListener("DOMContentLoaded", function(event) {
  console.log("DOM fully loaded and parsed");

  if(jQuery('.wp-admin').length>0){
    console.log('-> back')

    /*
    * JS for Custom Post type: Projects
    */
    if(jQuery('.post-type-projet').length>0){
      loadProjectPostTypeJS();
    }
  }
  else{
    console.log('-> front')

  }
});

function loadProjectPostTypeJS(){
    console.log('JS for Custom Post type: Projects ->')
    // JS for delete Img attachement of Projects
    if(document.getElementById('attachementImg')){
      document.getElementById('attachementImg').addEventListener('click', (event)=>{
        event.preventDefault();

        let r = confirm("Supprimer l'image?!\n");
        if (r == true) {
          let img = event.target
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
                  //alert( html );
                  jQuery(img).remove();
              }
          });
        }

        //document.location = document.URL+"&deleteImgAtt="+imgID
      })
    }
    // JS for Upload Eleves Project in new folder
    if(document.getElementById('controle-project_type')){
      checkProjectType()
      jQuery('input[name=project_type]').on('change', checkProjectType)

    }
}

function checkProjectType(){
  let project_type = jQuery('input[name=project_type]:checked').val();
  jQuery('#controle-project_type').css({
      'border': 'solid 1px #eee',
      'padding': '10px'
  });
  jQuery('#controle-project_type').html(project_type);
}
