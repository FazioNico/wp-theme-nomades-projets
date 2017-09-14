/**
 * @Author: Nicolas Fazio <webmaster-fazio>
 * @Date:   28-09-2016
 * @Email:  contact@nicolasfazio.ch
 * @Last modified by:   webmaster-fazio
 * @Last modified time: 31-07-2017
 */

import  { ProjectPostType } from './custom-post/project/project';

class App{

  constructor(){
    this.contentPage;
    document.addEventListener("DOMContentLoaded", ()=> this.start())
  }

  start(){
    if(document.getElementsByClassName('wp-admin').length>0){
      console.log('-> back')
      ///JS for Custom Post type: Projects
      if(document.getElementsByClassName('post-type-projet').length>0){
        let ProjectPost = new ProjectPostType();
      }
    }
    else{
      console.log('-> front')
      let isAuth = localStorage.getItem('auth-nomades')
      //console.log(JSON.parse(isAuth));
      if(!isAuth){
        this.loadLoginPage()
        return;
      }
      else {
        document.querySelector('.site-content > .conatainer').classList.remove('hide')
        document.querySelector('.header-search-form > div > div').classList.remove('hide')
      }
      if(JSON.parse(isAuth) <= Date.now()){
        this.loadLoginPage()
        return;
      }

      if(document.querySelector('.entry-title.hide') && document.querySelector('.entry-title.hide').innerHTML === 'Contact'){
        console.log('contact page');
        document.querySelector('.entry-content').innerHTML = `
        <div class="wpcf7">
        <form action="#" method="post" class=" wpcf7-form">
          <p>
            <label class="FORM_Label">Prénom</label>
            <span class="right wpcf7-form-control-wrap your-firstname">
              <input type="text" name="your-firstname" value="" class="" size="40">
            </span>
          </p>
          <p>
            <label class="FORM_Label2">Nom</label>
            <span class="right wpcf7-form-control-wrap your-name">
              <input type="text" name="your-name" value="" class="wpcf7-text wpcf7-validates-as-required" size="40">
            </span>
          </p>
          <p>
            <label class="FORM_Label">Société/Organisation</label>
            <span class="right wpcf7-form-control-wrap your-society">
              <input type="text" name="your-society" value="" class="wpcf7-text" size="40">
            </span>
          </p>
          <p>
            <label class="FORM_Label2">e-mail</label>
            <span class="right wpcf7-form-control-wrap your-email">
              <input type="text" name="your-email" value="" class="wpcf7-text wpcf7-validates-as-email wpcf7-validates-as-required" size="40">
            </span>
          </p>
          <p>
            <label class="FORM_Label">Message</label>
            <span class="right wpcf7-form-control-wrap your-message">
              <textarea name="your-message" cols="35" rows="3"></textarea>
            </span>
          </p>
          <div class="FORM_BtnSubmit">
            <label class="FORM_LabelSubmit">Envoyer</label>
            <input type="submit" value="#" class="wpcf7-submit">
            <img class="ajax-loader" style="visibility: hidden;" alt="Envoi en cours ..." src="http://www.nomades-projets.ch/wp-content/plugins/contact-form-7/images/ajax-loader.gif">
          </div>
          <div class="wpcf7-response-output wpcf7-display-none"></div>
        </form>
        </div>
        `;
      }
    }
  }

  loadLoginPage(){
      this.contentPage = document.querySelector('.site-content > .conatainer').innerHTML
      document.querySelector('.site-content > .conatainer').innerHTML = `
        <div class="row">
          <div class="col col-md-4 col-md-offset-4">
            <div class="card">
              <div class="row">

                <div class="col col-md-12">
                  <div class="input-field"  style="margin:40px 0px 5px;">
                    <label class="active" for="user">Identifiant</label>
                    <input style="float:right;max-width:160px;" value="" id="user" type="text" class="validate">
                    <br/>
                  </div>
                </div>

                <div class="col col-md-12">
                  <div class="input-field ">
                    <label class="active" for="pwd">Mot de passe</label>
                    <input style="float:right;max-width:160px;" value="" id="pwd" type="password" class="validate">
                  </div>
                </div>

                <div class="col col-md-12">
                  <div class="input-field">
                    <br/>
                    <button id="loginUser" style="float:right;margin-right: 45px;" class="btn">Connexion</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      `;
      document.querySelector('.site-content > .conatainer').classList.remove('hide')
      // document.querySelector('.header-search-form > div > div').classList.remove('hide')

      document.querySelector('#loginUser').addEventListener('click', e => {
        console.log(document.querySelector('#user').value, document.querySelector('#pwd').value);
        let user = document.querySelector('#user').value;
        let pwd = document.querySelector('#pwd').value;
        if(user === 'oce' && pwd === 'projets'){
          localStorage.setItem('auth-nomades',Date.now() + (1 * 24*3600*1000))
          document.querySelector('.site-content > .conatainer').innerHTML = this.contentPage
          document.querySelector('.site-content > .conatainer').classList.remove('hide')
          // document.querySelector('.header-search-form > div > div').classList.remove('hide')
        }
        else {
          if(document.querySelector('.errorLogin')){
            document.querySelector('.errorLogin').innerHTML = `
            <p class="errorLogin text-center aligncenter">
              Mot de passe ou identifiant incorrect.
            </p>
            `;
            return;
          }
          document.querySelector('.site-content > .conatainer').insertAdjacentHTML('beforeend', `
            <p class="errorLogin text-center aligncenter">
              Mot de passe ou identifiant incorrect.
            </p>
          `);
        }
      })

  }
}

const WP_APP = new App();
