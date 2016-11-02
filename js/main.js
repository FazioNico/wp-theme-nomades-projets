
import  { ProjectPostType } from './custom-post/project/project';

class App{

  constructor(){
    document.addEventListener("DOMContentLoaded", this.start)
  }

  start(event){
    if(document.getElementsByClassName('wp-admin').length>0){
      console.log('-> back')
      ///JS for Custom Post type: Projects
      if(document.getElementsByClassName('post-type-projet').length>0){
        let ProjectPost = new ProjectPostType();
      }
    }
    else{
      console.log('-> front')
    }
  }
}

const WP_APP = new App();
