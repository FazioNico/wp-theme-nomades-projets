
export class StaticProject{

  constructor(){
    console.log('Hello static project!')
    this.selector = document.getElementById('controle-project_type');
    this.localFolder = 'projects-eleves'
    this.start();
  }

  start(){
    let $input = jQuery('input[name=url_projet]')
    if(!$input.val()){
      this.selector.innerHTML = this.defaultSeleletonUI();
    }
    else {
      console.log('->', $input.val())
      this.localFolder = this.localFolder + '/userFolder'
      this.selector.innerHTML = this.SeleletonUI();
    }

  }


  /* Class View Methode */
  defaultSeleletonUI(){
    return `
      <p>
        Le projet de l'élève n'a pas encore copié.<br/>
        Copier le dossier distant du projet de l'élève dans le répértoir des projets copier:
      </p>
      <input style="width: 100%;" type="text" name="distantURL" id="distantURL" value="" placeholder="http://ateliers.nomades.ch/~eleve/dossier-de-projet/"><br/>
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
