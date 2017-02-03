/**
* @Author: Nicolas Fazio <webmaster-fazio>
* @Date:   03-11-2016
* @Email:  contact@nicolasfazio.ch
* @Last modified by:   webmaster-fazio
* @Last modified time: 03-02-2017
*/

export class WpAjaxCallService{

  constructor(){
    this.selector;
  }

  ajaxCall(data){
    return Promise.resolve(
      jQuery.ajax({
    		url : ajaxurl,
    		type : 'post',
    		data : {
    			action : data.action,
    			params : data.params
    		},
    		success : ( response )=> {
    			return this.ajaxResponse(response);
    		},
        error:(err)=>{
          return this.ajaxResponse(err);
        }
    	})
    );
  }

  ajaxResponse(response){
    console.log('Ajax Response debug-> ', response);
    //let ajaxResult = JSON.parse(response)
    //console.log(ajaxResult);
    return response;
  }
}
