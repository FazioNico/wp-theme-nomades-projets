import  { WpAjaxCallService } from '../../providers/wp-ajax/wp-ajax'

export class StaticProject{

  constructor(wpAjax = WpAjaxCallService){

    console.log('Hello static project!')
    this.wpAjax = new WpAjaxCallService();
    this.selector = document.getElementById('controle-project_type');
    this.$input = jQuery('input[name=url_projet]')
    this.localFolder = 'projects-eleves'
    this.start();
  }

  start(){
    this.loadUI();
    this.loadEventUI();
  }

  /* UI Methode */
  loadUI(){
    if(!this.$input.val()){
      this.selector.innerHTML = this.defaultSeleletonUI();
    }
    else {
      console.log('->', this.$input.val())
      this.selector.innerHTML = this.SeleletonUI();
    }
  }

  loadEventUI(){
    if(document.getElementById('copyDistantFolder')){
          document.getElementById('copyDistantFolder').addEventListener('click', this.copyDistantFolder.bind(this))
    }
  }

  /* Event Methode */

  copyDistantFolder(event){
    event.preventDefault();
    // URL for testing: http://ateliers.nomades.ch/~fazio/exercice05ajax/
    let distantFolderInput = document.getElementById('distantURL').value;
    let pwd = document.getElementById('serverPWD').value;
    if(
      distantFolderInput.length > 1
      && distantFolderInput.indexOf("~")>0
      && distantFolderInput.indexOf("/")>0
      && pwd.length >=3
    ){
      let pathName = {
        'userPath': distantFolderInput.split('~')[1].split('/')[0],
        'projectPath': distantFolderInput.split('~')[1].split('/').reverse()[1]
      }
      let localFolder = Object.keys(pathName).map(key => pathName[key])
      let action = 'copy_distant_folder';
      this.localFolder = this.localFolder + '/' + localFolder.join('/')+'/';
      // add folder value to input[name=url_projet]
      this.$input.val(this.localFolder)

      // Ajax call to WP Action API ->
      //console.info({'action-> ': action, 'params-> ': this.localFolder })
      //this.ajaxCall({'action': action, 'params': this.localFolder });
      let params = {'folder': this.localFolder, 'password': pwd };
      this.wpAjax.ajaxCall({'action': action, 'params': params});
    }
    else{
      console.warn("Les critères ne parsing de l'URL distante ne sont pas respectés (~ /)");
    }

  }

  ajaxCall(data){
    jQuery.ajax({
  		url : ajaxurl,
  		type : 'post',
  		data : {
  			action : data.action,
  			params : data.params
  		},
  		success : function( response ) {
  			alert(response)
  		}
  	});
  }
  /* Class View Methode */
  defaultSeleletonUI(){
    return `
      <p>
        Le projet de l'élève n'a pas encore copié.<br/>
        Copier le dossier distant du projet de l'élève dans le répértoir des projets copier:
      </p>
      <input style="width: 100%;" type="text" name="distantURL" id="distantURL" value="" placeholder="http://ateliers.nomades.ch/~eleve/dossier-de-projet/"><br/>
      <input style="width:60%;" type="text" name="serverPWD" id="serverPWD" value="nicfaz" placeholder="mot de pass du profil de l'élève"><br/>
      <button id="copyDistantFolder">Copier le projet de l'élève</button>
    `;
  }

  SeleletonUI(){
    return `
      <p>
        Dossier du projet de l'élève: <a href="../../${this.localFolder}" target="_blank">Projet de l'élève</a> <br/>
      </p>
      <button id="deleteLocalFolder">supprimer le dossier de l'élève</button>
    `;
  }
}












//
