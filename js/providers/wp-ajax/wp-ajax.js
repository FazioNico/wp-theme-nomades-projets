
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
    // console.log(response);
    let ajaxResult = JSON.parse(response)
    console.log(ajaxResult);
    return ajaxResult;
  }
}
