
function checkFBStatus(){

		var fbuserid, fbtoken;
		var appid = jQuery("#appIdFb").val();
		var loggedin = false;
		FB.init({
				appId: appid, 
				status: true, 
				cookie: true, 
				xfbml: true,
				oauth      : true,
		});
		
		FB.getLoginStatus(handleUserStateChange);
		FB.Event.subscribe('auth.authResponseChange', handleUserStateChange);
		
		function handleUserStateChange(response) {
			connected = !!response.authResponse;

			if (response.status === 'connected') {
			  getAPIFB();
			} else if (response.status === 'not_authorized') {
			  loginFB();
			} else {
			  loginFB();
			}
		}

};
	

function getAPIFB() {
	FB.api('/me/permissions', function (response) {
		if((response["data"][0].create_event == 1) && (response["data"][0].user_events == 1) && (response["data"][0].publish_stream == 1) && (response["data"][0].photo_upload == 1) && (response["data"][0].rsvp_event == 1) && (response["data"][0].publish_actions == 1)) {
			FB.getLoginStatus(function(responseUser) {
				var uid = responseUser.authResponse.userID;
   			 	var accessToken = responseUser.authResponse.accessToken;
				assignEventFB(uid,accessToken);
			});
		} else {
			loginFB();
		}
	});
}
function loginFB() {
	FB.Event.subscribe('auth.login', function(response){
	});
	FB.login(function(response) {
		if (response.session) {
			var session = FB.getSession();
			fbtoken = session.access_token;
			fbuserid = session.uid;
			assignEventFB(fbuserid,fbtoken);
		}
	}, {scope:'create_event,publish_stream,photo_upload,user_events,publish_actions,rsvp_event'});
}

function logoutFB() {
	FB.logout(function(response) {
		// user is now logged out
	});
}

function assignEventFB(appTokenUidFb,appTokenFb){
	jQuery("#appTokenFb").val(appTokenFb);
	jQuery("#appTokenUidFb").val(appTokenUidFb);
	appAuthorId = jQuery("#idAuthorFB").val();
	if (appAuthorId=="")
		jQuery("#idAuthorFB").val(appTokenUidFb);
	if ((appAuthorId!="") && (appAuthorId!="appTokenUidFb")){
		jQuery("updatefb").hide();
	}
}