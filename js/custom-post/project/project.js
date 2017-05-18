/**
 * @Author: Nicolas Fazio <webmaster-fazio>
 * @Date:   02-11-2016
 * @Email:  contact@nicolasfazio.ch
 * @Last modified by:   webmaster-fazio
 * @Last modified time: 18-05-2017
 */

import  { StaticProject } from '../../components/static-project/static-project';
import  { StaticSQLProject } from '../../components/static-sql-project/static-sql-project';
import  { AtypicProject } from '../../components/atypic-project/atypic-project';

export class ProjectPostType {

  constructor(){
    console.log('hello ProjectPostType!')
    jQuery('#field_url_projet').css({'display': 'none'})
    this.loadEventUI();
  }

  loadEventUI(){
    // JS for delete Img attachement of Projects
    if(document.getElementById('attachementImg')){
      document.getElementById('attachementImg').addEventListener('click', ()=>this.deleteAttachmentImg(event))
    }
    // JS for Upload Eleves Project in new folder
    if(document.getElementById('controle-project_type')){
      this.checkProjectType()
      jQuery('input[name=project_type]').on('change', ()=>this.checkProjectType())
    }
  }

  checkProjectType(){
    let project_type = jQuery('input[name=project_type]:checked').val();
    jQuery('#controle-project_type').css({
        'border-top': 'solid 1px #eee',
        'padding': '10px'
    });
    switch (project_type) {
      case 'static':
        new StaticProject();
        break;
      case 'wp':
        new StaticSQLProject();
        //document.getElementById('controle-project_type').innerHTML = project_type;
        break;
      case 'atypique':
        new AtypicProject();
        // document.getElementById('controle-project_type').innerHTML = project_type;
        break;
    }
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
