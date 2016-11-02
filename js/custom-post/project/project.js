
export class ProjectPostType {

  constructor(){
    console.log('hello ProjectPostType!')
    this.loadEventUI();
  }

  loadEventUI(){
    // JS for delete Img attachement of Projects
    if(document.getElementById('attachementImg')){
      document.getElementById('attachementImg').addEventListener('click', this.deleteAttachmentImg)
    }
    // JS for Upload Eleves Project in new folder
    if(document.getElementById('controle-project_type')){
      this.checkProjectType()
      jQuery('input[name=project_type]').on('change', this.checkProjectType)
    }
  }

  checkProjectType(){
    let project_type = jQuery('input[name=project_type]:checked').val();
    jQuery('#controle-project_type').css({
        'border': 'solid 1px #eee',
        'padding': '10px'
    });
    switch (project_type) {
      case 'static':
        console.log(project_type)
        break;
      case 'wp':
        console.log(project_type)
        break;
      case 'atypique':
        console.log(project_type)
        break;
    }
    document.getElementById('controle-project_type').innerHTML = project_type;
  }

  deleteAttachmentImg(event){
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
  }

}
