function theChampAuthUserFB(){FB.getLoginStatus(theChampFBCheckLoginStatus)}
function theChampFBCheckLoginStatus(response){if(response&&response.status=='connected'){theChampLoadingIcon_fb();theChampFBLoginUser()}else{FB.login(theChampFBLoginUser,{scope:theChampFacebookScope})}}
function theChampFBLoginUser(){FB.api('/me?fields=id,name,about,link,email,first_name,last_name',function(response){if(!response.id){return}
theChampCallAjax(function(){theChampAjaxUserAuth(response,'facebook')})})};