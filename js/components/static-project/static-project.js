
export class StaticProject{

  constructor(){
    console.log('Hello static project!')
    this.start();
  }

  start(){
    let $input = jQuery('input[name=url_projet]')
    console.log('->', $input.val())
    document.getElementById('controle-project_type').innerHTML = 'static';

  }
}
