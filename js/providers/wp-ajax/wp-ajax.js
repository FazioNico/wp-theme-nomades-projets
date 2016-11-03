
export class WpAjaxCallService{

  constructor(){

  }

  ajaxCall(data){
    jQuery.ajax({
  		url : ajaxurl,
  		type : 'post',
  		data : {
  			action : data.action,
  			params : data.params
  		},
  		success : ( response )=> {
  			this.ajaxResponse(response);
  		}
  	});
  }

  ajaxResponse(response){
    alert(response);
  }
}
