/**
* @Author: Nicolas Fazio <webmaster-fazio>
* @Date:   02-11-2016
* @Email:  contact@nicolasfazio.ch
* @Last modified by:   webmaster-fazio
* @Last modified time: 01-02-2017
*/

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
      this.localFolder = this.$input.val()
      this.selector.innerHTML = this.SeleletonUI();
    }
  }

  loadEventUI(){
    if(document.getElementById('copyDistantFolder')){
      document.getElementById('copyDistantFolder').addEventListener('click', ()=>this.copyDistantFolder(event))
    }
    if(document.getElementById('deleteLocalFolder')){
      document.getElementById('deleteLocalFolder').addEventListener('click', ()=>this.deleteLocalFolder(event))
    }
    if(document.getElementById('tryAgain')){
      document.getElementById('tryAgain').addEventListener('click', (event)=>{
        event.preventDefault();
        this.selector.innerHTML = this.defaultSeleletonUI();
        this.loadEventUI()
      })
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
      this.selector.innerHTML = this.LoadingSeleletonUI();
      // Ajax call to WP Action API ->
      //console.info({'action-> ': action, 'params-> ': this.localFolder })
      //this.ajaxCall({'action': action, 'params': this.localFolder });
      let params = {'folder': this.localFolder, 'password': pwd };
      let ajaxResult = this.wpAjax.ajaxCall({'action': action, 'params': params});
      ajaxResult.then((data)=>{
        this.displayAjaxResult(JSON.parse(data));
      })
    }
    else{
      console.warn("Les critères ne parsing de l'URL distante ne sont pas respectés (~ /)");
    }

  }

  displayAjaxResult(ajaxResult){
    console.log('static displayAjaxResult-> ',ajaxResult)
    let resultSelector = document.getElementById('loadingUI')
    if(resultSelector){
      if(ajaxResult.state == 1){
        resultSelector.innerHTML = `
          <p><b>${ajaxResult.extract}</b></p>
          <hr/>
          <p>Ne pas oublier d'enregister les modifications</p>
          <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Mettre à jour">
        `;
      }
      else if (ajaxResult.state == 2){
        //this.$input.val('');
        resultSelector.innerHTML = `
          <p><b>${ajaxResult.extract}</b></p>
          <hr/>
          <span id="deleteLocalFolder" class="button button-primary button-large">supprimer dossiers temporaires ou existants</span>
          <span id="tryAgain" class="button button-primary button-large">Réessayer</span>
        `;
      }
      else {
        //this.$input.val('');
        resultSelector.innerHTML = `
          <p><b>${ajaxResult.extract}</b></p>
          <hr/>
          <span id="deleteLocalFolder" class="button button-primary button-large">supprimer dossiers temporaires ou existants</span>
          <span id="tryAgain" class="button button-primary button-large">Réessayer</span>
        `;
      }

    }
    else {

    }
    this.loadEventUI()
  }

  deleteLocalFolder(event){
    event.preventDefault();
    // console.log(`event-> ${event}`)
    // console.log(`folder-> ${this.localFolder}`)
    let params = {'folder': this.localFolder };
    this.wpAjax.ajaxCall({'action': 'delete_local_folder', 'params': params});
    this.localFolder = 'projects-eleves';
    this.$input.val(null);
    this.loadUI();
    document.getElementById('update').innerHTML = `
      <p>Ne pas oublier d'enregister les modifications</p>
      <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Mettre à jour">
    `;
  }

  /* Class View Methode */
  defaultSeleletonUI(){
    return `
      <p>
        Le projet de l'élève n'a pas encore copié.<br/>
        Copier le dossier distant du projet de l'élève dans le répértoir des projets copier:
      </p>
      <p>
        <b>Fichiers</b>
      </p>
      <input style="width: 100%;" type="text" name="distantURL" id="distantURL" value="" placeholder="http://ateliers.nomades.ch/~eleve/dossier-de-projet/"><br/>
      <input style="width:60%;" type="text" name="serverPWD" id="serverPWD" value="nicfaz" placeholder="mot de pass du profil de l'élève"><br/>
      <br/>
      <p>
        <b>Sauvegarder</b>
      </p>
      <button id="copyDistantFolder" class="button button-primary button-large" >Copier le projet de l'élève</button>
      <div id="update"></div>
    `;
  }

  SeleletonUI(){
    return `
      <p>
        Dossier du projet de l'élève: <a href="../../${this.localFolder}" target="_blank">Projet de l'élève</a> <br/>
      </p>
      <button id="deleteLocalFolder" class="button button-primary button-large" >supprimer le dossier de l'élève</button>
    `;
  }

  LoadingSeleletonUI(){
    return `
      <div id="loadingUI">
        <p>
          loading...
        </p>
        <img src="images/spinner-2x.gif">
      </div>
    `;
  }
}












//
