/**
 * @Author: Nicolas Fazio <webmaster-fazio>
 * @Date:   18-05-2017
 * @Email:  contact@nicolasfazio.ch
 * @Last modified by:   webmaster-fazio
 * @Last modified time: 18-05-2017
 */

import  { WpAjaxCallService } from '../../providers/wp-ajax/wp-ajax'

export class AtypicProject {
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
    if(document.getElementById('copyURL')){
      document.getElementById('copyURL').addEventListener('click', ()=>this.copyURL(event))
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

  deleteLocalFolder(event){
    event.preventDefault();
    this.$input.val(null);
    this.loadUI();
    document.getElementById('update').innerHTML = `
      <p>Ne pas oublier d'enregister les modifications</p>
      <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Mettre à jour">
    `;
  }

  copyURL(event){
    event.preventDefault();
    let url = document.getElementById('distantURL').value;
    this.$input.val(url)
    this.selector.innerHTML = `
      <p><b>Success!</b></p>
      <hr/>
      <p>Ne pas oublier d'enregister les modifications</p>
      <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Mettre à jour">
    `;

  }
  /* Class View Methode */
  defaultSeleletonUI(){
    return `
      <p>
        Les projets atypiques ne peuvent actuelement pas être copier automatiquement. <br/>
        Il faut copier le dossier de du projet de l'élève dans le répértoire des projects copiers en passant par un utilitaire FTP. Puis indiquer l'url d'accès au projet copier dans le champ ci-dessous.
      </p>
      <p>
        <b>Project de l'élève:</b>
      </p>
      URL du projet copié: <input style="width: 100%;" type="text" name="distantURL" id="distantURL" value="" placeholder="http://www.nomades-projets.ch/projects-eleves/NOM_DE_L_ELEVE/DOSSIER_DU_PROJET/"><br/>
      <br/>
      <button id="copyURL" class="button button-primary button-large" >Enregister l'URL du projet de l'élève</button>
      <div id="update"></div>
    `;
  }

  SeleletonUI(){
    return `
      <p>
        Dossier du projet de l'élève: <a href="${this.localFolder}" target="_blank">Projet de l'élève</a> <br/>
      </p>
      <button id="deleteLocalFolder" class="button button-primary button-large" >supprimer le dossier de l'élève</button>
    `;
  }

}
