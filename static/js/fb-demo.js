var FB_DEMO = FB_DEMO || {};

FB_DEMO.login = {
	permissions: {
		publish_actions: false
	},
	messages: {
		login: "Login",
		logout: "Logout"
	},
	// Add a login button to the page
	// Call Facebook login functionality when clicked
	display_login_button: function() {
		var user_info = jQuery("#user-identity");
		// no identity section found
		if ( user_info.length === 0 ) {
			return;
		}

		// login should be the only thing inside
		user_info.empty();

		// add a login button, attach a handler
		user_info.append( jQuery("<button />").attr({id:"login-button",type:"button"}).addClass("btn btn-primary").text(FB_DEMO.login.messages.login).click(function(){
			FB.login(function(response){
				if (response.authResponse) {
					jQuery(document).trigger("facebook-logged-in");
				}
			});
		}) );
	},
	// Customize the page dislay for the currently logged-in Facebook account
	display_user_info: function() {
		var user_info = jQuery("#user-identity");
		// no identity section found
		if ( user_info.length === 0 ) {
			return;
		}

		user_info.html("Updating...");

		// Fetch photo and name of current viewer
		FB.api("/me",{fields:"id,name,link,verified,picture"}, function(response){
			// tear it down, build it up again
			user_info.empty();

			FB_DEMO.user.name = response.name;
			user_info.append( jQuery("<img />").attr({alt:response.name,src:response.picture.data.url,width:25,height:25}) );
			user_info.append( jQuery("<span />").addClass("hidden-phone").text(response.name) );
			user_info.append( jQuery("<button />").attr({id:"logout-button",type:"button"}).addClass("btn btn-primary").text(FB_DEMO.login.messages.logout).click(function(){FB_DEMO.login.display_login_button();FB_DEMO.login.logout();}) );
			if ( response.link !== undefined ) {
				FB_DEMO.user.link = response.link;
			}
		});
	},
	permissions_check: function() {
		FB.api( "/me/permissions", function(response) {
			if ( response.data !== undefined && jQuery.isArray( response.data ) && response.data[0].publish_actions === 1 ) {
				FB_DEMO.login.permissions.publish_actions = true;
			}
			jQuery(document).trigger("facebook-permissions-check");
		} );
	},
	logout: function() {
		FB.logout(function(){jQuery(document).trigger("facebook-logged-out")});
	},
	// customize the page based on login status
	status_change: function(response) {
		if (response.authResponse) {
			jQuery(document).trigger("facebook-logged-in");
		} else {
			FB_DEMO.login.display_login_button();
		}
	},
	init: function() {
		FB_DEMO.user = {};
		FB.getLoginStatus( FB_DEMO.login.status_change );
		FB.Event.subscribe( "auth.statusChange", FB_DEMO.login.status_change );
		jQuery(document).one( "facebook-logged-in", function(){
			FB_DEMO.login.display_user_info();
			FB_DEMO.login.permissions_check();
		} );
	}
};

// turn it on
FB_DEMO.init = function() {
	FB_DEMO.login.init();
}