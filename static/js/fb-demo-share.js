var FB_DEMO = FB_DEMO || {};
FB_DEMO.share = {
	messages: {
		action_text: "Post to Timeline",
		close: "Close",
		custom_message: "Message",
		custom_message_placeholder: "Write something about the %s",
		eat_action: "I ate this!",
		error: "Error",
		thousands_separator: ",",
		success: "Posted to Facebook!",
		view_on_facebook: "View %s story on Facebook"
	},
	// format an integer such as 1234 to a string with a thousands separator: 1,234
	format_number: function( num ) {
		return num.toString().replace( /(\d)(?=(\d{3})+(?!\d))/g, "$1" + FB_DEMO.share.messages.thousands_separator );
	},
	build_with_at_text: function() {
		var message_data = jQuery("#composer-message-data");
		if ( message_data.length === 0 ) {
			return;
		}
		var component = jQuery( "<span>" );
		if ( FB_DEMO.share.friends.tagged !== undefined && !jQuery.isEmptyObject( FB_DEMO.share.friends.tagged ) ) {
			var friends = jQuery( "<span>" ).addClass("friends"). text( " " + FB_DEMO.share.friends.autocomplete.messages.intro_text + " " );
			jQuery.each( FB_DEMO.share.friends.tagged, function( id, values ) {
				friends.append( jQuery( "<a>" ).addClass("friend").attr({href:values.link,target:"_blank"}).text(values.name).data("fbid",id) );
			} );
			if ( !friends.is(":empty") ) {
				component.append( friends );
			}
			friends=null;
		}
		if ( FB_DEMO.share.place.tagged !== undefined ) {
			component.append( jQuery( "<span>" ).addClass( "place" ).text( " " + FB_DEMO.share.place.autocomplete.messages.intro_text + " " ).append( jQuery( "<a>" ).attr({href:FB_DEMO.share.place.tagged.link,target:"_blank"}).text(FB_DEMO.share.place.tagged.name) ) );
		}

		// clear any previously stored elements and their handlers
		message_data.empty();
		if ( component.is(":empty") ) {
			// hide the extra component
			message_data.hide();
		} else {
			message_data.show();
			message_data.append( component.html() );
		}
	},
	// search for a place matching text near a fixed point
	place: {
		messages: {
			add_location: "Add location",
			icon: "Facebook Location icon",
			placeholder: "Where are you?"
		},
		init: function() {
		},
		build_form_fields: function() {
			return jQuery( "<div>" ).attr("id","composer-place-group").append( jQuery( "<input>" ).addClass("form-control").attr({type:"search",role:"combobox",id:"composer-place-field",autocomplete:"off",placeholder:FB_DEMO.share.place.messages.placeholder,"aria-label":FB_DEMO.share.place.messages.placeholder}) ).hide();
		},
		build_toggle: function() {
			var button = jQuery( "<button>" ).attr({
				"id": "toggle-place",
				"type": "button",
				"title": FB_DEMO.share.place.messages.add_location,
				"aria-controls": "composer-place-group"
			}).addClass("btn");
			button.append( jQuery( "<img>" ).attr({
				"alt": FB_DEMO.share.place.messages.icon,
				"src": FB_DEMO.base_static_uri + "images/location.png",
				"width": 32,
				"height": 32
			}) );
			button.one( "click", FB_DEMO.share.place.autocomplete.init );
			button.click( FB_DEMO.share.place.handle_click );
			return button;
		},
		handle_click: function() {
			var button = jQuery("#toggle-place");
			jQuery("#composer-place-group").toggle();
			if ( button.hasClass("active") ) {
				button.removeClass("active");
			} else {
				button.addClass("active");
				var friend_button = jQuery("#toggle-friends");
				// one active button display at a time
				if ( friend_button.hasClass("active") ) {
					friend_button.click();
				}
				FB_DEMO.share.place.clear();
				FB_DEMO.share.place.autocomplete.search_field.focus();
			}
		},
		clear: function() {
			delete FB_DEMO.share.place.tagged;
			jQuery("#composer-place-field").val("");
			FB_DEMO.share.build_with_at_text();
		},
		autocomplete: {
			search_endpoint: FB_DEMO.base_uri + "search/places.php",
			search_params: {}, // persist parameters between searches
			messages: {
				were_here: "%s were here",
				intro_text: "at"
			},
			init: function() {
				FB_DEMO.share.place.autocomplete.search_field = jQuery("#composer-place-field");
				if ( FB_DEMO.share.place.autocomplete.search_field.length === 0 ) {
					return;
				}
				FB_DEMO.share.place.autocomplete.search_field.attr("placeholder",FB_DEMO.share.place.autocomplete.messages.placeholder);
				FB_DEMO.share.place.autocomplete.search_field.autocomplete({
					appendTo: "#composer-place-group",
					autoFocus: true,
					minLength: 3,
					focus: function( event, ui ) {
						// add consistency between mouse and keyboard events
						if ( event.keyCode !== undefined ) {
							var menu = $(this).data("ui-autocomplete").menu.element, focused = menu.find("li:has(a.ui-state-focus)");
							menu.find(".ui-state-focus").removeClass("ui-state-focus");
							focused.addClass("ui-state-focus");
							menu=focused=null;
						}
						return false;
					},
					select: function( event, ui ) {
						FB_DEMO.share.place.tagged = {id:ui.item.value, name:ui.item.label, link:ui.item.link};
						FB_DEMO.share.build_with_at_text();
						FB_DEMO.share.place.autocomplete.search_field.autocomplete("close");
						jQuery("#toggle-place").click();
						return false;
					},
					source: function( request, response ) {
						if ( request.term === undefined || request.term.length < 3 ) {
							return;
						}
						jQuery.getJSON( FB_DEMO.share.place.autocomplete.search_endpoint + "?" + jQuery.param( jQuery.extend( {}, FB_DEMO.share.place.autocomplete.search_params, {q:request.term} ) ) ).done(function(results){response(results)}).fail(function(){response([])});
					}
				});
				FB_DEMO.share.place.autocomplete.search_field.data( "ui-autocomplete" )._renderItem = FB_DEMO.share.place.autocomplete.renderItem;
				FB_DEMO.share.place.autocomplete.search_field.attr( "aria-haspopup", "true" );
				if ("geolocation" in navigator) {
					navigator.geolocation.getCurrentPosition( function(position) {
						FB_DEMO.share.place.autocomplete.update_coordinates( position.coords.latitude, position.coords.longitude );
					});
				}
			},
			// override jQuery UI autocomplete default item listing
			renderItem: function( ul, place ) {
				var li = jQuery( "<li>" ).addClass("place").attr({"role":"option","aria-label":place.label}).mouseenter(function(){jQuery(this).addClass("ui-state-focus")}).mouseleave(function(){jQuery(this).removeClass("ui-state-focus")});
				if ( place.picture !== undefined ) {
					li.append( jQuery( "<img>" ).attr({src:place.picture,alt:place.label,width:25,height:25}) );
				}

				var text_pieces = [place.label];
				if ( place.location !== undefined ) {
					if ( place.location.street !== undefined ) {
						text_pieces.push( place.location.street );
					}
					if ( place.location.area !== undefined ) {
						text_pieces.push( place.location.area );
					}
				}
				li.append( jQuery( "<a>" ).addClass( "text" ).text( text_pieces.join( " • " ) ) );

				if ( place.were_here_count !== undefined ) {
					li.append( jQuery( "<div>" ).addClass( "subtext" ).text( FB_DEMO.share.place.autocomplete.messages.were_here.replace( /%s/i , FB_DEMO.share.format_number( place.were_here_count ) ) ) );
				}

				return li.appendTo( ul );
			},
			update_coordinates: function(latitude,longitude) {
				if ( latitude===undefined || longitude===undefined ) {
					return;
				}
				FB_DEMO.share.place.autocomplete.search_params.center = latitude + "," + longitude;
			}
		}
	},
	friends: {
		messages: {
			icon: "Facebook silhouette icon",
			tag_friends: "Tag friends",
			placeholder: "Who are you with?"
		},
		init: function() {
			FB_DEMO.share.friends.tagged = {};
		},
		build_form_fields: function() {
			return jQuery( "<div>" ).addClass("form-inline").attr("id", "composer-friends-group").append( jQuery("<ul>").addClass("list-unstyled list-inline").attr("id","composer-friends-group-fields").append( jQuery("<li>").append(
				jQuery( "<input>" ).addClass("form-control").attr({type:"search",role:"combobox",id:"composer-friends-field",autocomplete:"off",placeholder:FB_DEMO.share.friends.messages.placeholder,"aria-label":FB_DEMO.share.friends.messages.placeholder})
			) ) ).hide();
		},
		build_toggle: function() {
			var button = jQuery( "<button>" ).attr({
				"id": "toggle-friends",
				"type": "button",
				"title": FB_DEMO.share.friends.messages.tag_friends,
				"aria-controls": "composer-friends-group"
			}).addClass("btn");
			button.append( jQuery( "<img>" ).attr({
				"alt": FB_DEMO.share.friends.messages.icon,
				"src": FB_DEMO.base_static_uri + "images/friend.png",
				"width": 32,
				"height": 32
			}) );
			button.one( "click", FB_DEMO.share.friends.autocomplete.init );
			button.click( FB_DEMO.share.friends.handle_click );
			return button;
		},
		handle_click: function() {
			var button = jQuery("#toggle-friends");
			jQuery("#composer-friends-group").toggle();
			if ( button.hasClass("active") ) {
				button.removeClass("active");
			} else {
				button.addClass("active");
				var place_button = jQuery("#toggle-place");
				// one active button display at a time
				if ( place_button.hasClass("active") ) {
					place_button.click();
				}
				FB_DEMO.share.friends.autocomplete.search_field.focus();
			}
		},
		// search for friends matching text
		autocomplete: {
			messages: {
				intro_text: "with",
				remove: "Remove %s from meal"
			},
			search_endpoint: FB_DEMO.base_uri + "search/friends.php",
			init: function() {
				FB_DEMO.share.friends.autocomplete.search_field = jQuery("#composer-friends-field");
				if ( FB_DEMO.share.friends.autocomplete.search_field.length === 0 ) {
					return;
				}
				FB_DEMO.share.friends.autocomplete.search_field.autocomplete({
					appendTo: "#composer-friends-group",
					autoFocus: true,
					minLength: 2,
					focus: function( event, ui ) {
						// add consistency between mouse and keyboard events
						if ( event.keyCode !== undefined ) {
							var menu = $(this).data("ui-autocomplete").menu.element, focused = menu.find("li:has(a.ui-state-focus)");
							menu.find(".ui-state-focus").removeClass("ui-state-focus");
							focused.addClass("ui-state-focus");
							menu=focused=null;
						}
						return false;
					},
					select: function( event, ui ) {
						FB_DEMO.share.friends.tagged[ui.item.value] = {name:ui.item.label, link:ui.item.link};
						FB_DEMO.share.build_with_at_text();
						jQuery("#composer-friends-group-fields").prepend( jQuery( "<li>" ).data( "fbid", ui.item.value ).addClass( "friend" ).append( jQuery( "<a>" ).attr({href:ui.item.link,target:"_blank"}).text(ui.item.label) ).append( jQuery( "<button>" ).addClass("btn btn-link").attr({type:"button",title:FB_DEMO.share.friends.autocomplete.messages.remove.replace( /%s/i , ui.item.label)}).text("×").click(FB_DEMO.share.friends.autocomplete.remove_friend) ) );
						FB_DEMO.share.friends.autocomplete.search_field.autocomplete("close");
						FB_DEMO.share.friends.autocomplete.search_field.val("");
						FB_DEMO.share.friends.autocomplete.search_field.focus();
						//jQuery("#toggle-friends").click();
						return false;
					},
					source: function( request, response ) {
						if ( request.term === undefined || request.term.length < 2 ) {
							return;
						}
						jQuery.getJSON( FB_DEMO.share.friends.autocomplete.search_endpoint + "?" + jQuery.param({q:request.term}) ).done(function(results){response( results )}).fail(function(){response([])});
					}
				});
				FB_DEMO.share.friends.autocomplete.search_field.data( "ui-autocomplete" )._renderItem = FB_DEMO.share.friends.autocomplete.renderItem;
				FB_DEMO.share.friends.autocomplete.search_field.attr( "aria-haspopup", "true" );
			},
			remove_friend: function() {
				var friend = jQuery(this).closest(".friend");
				if ( friend.length === 0 ) {
					return;
				}
				delete FB_DEMO.share.friends.tagged[friend.data("fbid")];
				friend.remove();
				FB_DEMO.share.build_with_at_text();
				FB_DEMO.share.friends.autocomplete.search_field.focus();
			},
			// override jQuery UI autocomplete default item listing
			renderItem: function( ul, friend ) {
				return jQuery( "<li>" ).addClass( "friend" ).attr( {"role": "option", "aria-label": friend.label} ).mouseenter(function(){jQuery(this).addClass("ui-state-focus")}).mouseleave(function(){jQuery(this).removeClass("ui-state-focus")}).append(
					jQuery( "<img>" ).attr( {src: (friend.picture === undefined) ? "https:\/\/graph.facebook.com\/" + friend.value + "\/picture" : friend.picture, alt: friend.label, width:25, height:25} ) ).append(
					jQuery( "<a>" ).addClass( "text" ).text( friend.label )
				).appendTo( ul );
			}
		}
	},
	story_uri: function(story_id) {
		if ( FB_DEMO.user.link !== undefined ) {
			return FB_DEMO.user.link + "/activity/" + story_id;
		} else {
			return "https://www.facebook.com/" + story_id;
		}
	},
	form_handler: function(e) {
		if ( e.preventDefault ) {
			e.preventDefault();
		}
		var params = {
			meal: jQuery.trim( jQuery("#composer-meal").val() ),
			message: jQuery.trim( jQuery("#composer-message").val() )
		};
		if ( params.meal.length === 0 ) {
			return;
		}
		if ( params.message.length === 0 ) {
			delete params.message;
		}
		if ( FB_DEMO.share.place.tagged !== undefined && FB_DEMO.share.place.tagged.id !== undefined ) {
			params.place = FB_DEMO.share.place.tagged.id;
		}
		if ( FB_DEMO.share.friends.tagged !== undefined && !jQuery.isEmptyObject( FB_DEMO.share.friends.tagged ) ) {
			params.tags = Object.keys( FB_DEMO.share.friends.tagged ).join(",");
		}
		jQuery.ajax({
			type: "POST",
			url: FB_DEMO.base_uri + "post-to-facebook.php",
			data: params,
			cache: false,
			dataType: "json"
		}).done(function(data){
			if ( !jQuery.isPlainObject(data) || data.id === undefined ) {
				return;
			}

			var meal = jQuery("#meal");
			if ( meal.length === 0 ) {
				return;
			}
			meal.before( jQuery("<div>").addClass("alert alert-success").append( jQuery("<strong>").text( FB_DEMO.share.messages.success + " ") ).append( jQuery("<a>").addClass("alert-link").attr({"_target":"blank",href:FB_DEMO.share.story_uri(data.id) }).text( FB_DEMO.share.messages.view_on_facebook.replace( /%s/i, meal.data("mealtitle").toLowerCase() ) ) ) );
		}).fail(function(jqXHR){
			if ( !jQuery.isPlainObject(jqXHR.responseJSON) || jqXHR.responseJSON.error === undefined ) {
				return;
			}

			jQuery("#meal").before( jQuery("<div>").addClass("alert alert-danger").text(jqXHR.responseJSON.error).prepend( jQuery("<strong>").text( FB_DEMO.share.messages.error + ": " ) ) );
		}).always(function(){
			jQuery("#composer-modal").modal("hide");
		});
		return false;
	},
	add_like_button_social_plugin: function() {
		var social_actions = jQuery("#social-actions");
		if ( social_actions.length === 0 ) {
			return;
		}
		social_actions.append( jQuery("<div>").addClass("fb-like").attr({"data-layout":"button_count","data-send":"false","data-width":"90","data-show-faces":"false"}) );
		FB.XFBML.parse( social_actions[0] );
	},
	add_share_button: function() {
		jQuery("#social-actions").append( jQuery( "<button>" ).addClass("btn btn-default").attr({id:"share-button",type:"button"}).text(FB_DEMO.share.messages.eat_action).click( function() {
			if ( ! FB_DEMO.share.maybe_add_share_composer() ) {
				// prompt for publish_actions permission
				FB.login( function(response){
					if (response.authResponse) {
						// display share composer if permission granted
						jQuery(document).one("facebook-permissions-check",function(){
							FB_DEMO.share.maybe_add_share_composer();
						});
						// test granted permissions
						FB_DEMO.login.permissions_check();
					}
				}, {scope:"publish_actions"} );
			}
		} ) );
	},
	maybe_add_share_composer: function() {
		if ( FB_DEMO.login.permissions.publish_actions ) {
			FB_DEMO.share.add_share_composer();
			return true;
		} else {
			return false;
		}
	},
	add_share_composer: function() {
    	var meal = jQuery("#meal");
    	if ( meal.length === 0 ) {
	    	return;
    	}
    	var modal = jQuery("#composer-modal");
    	if ( modal.length !== 0 ) {
	    	return;
    	}
    	var meal_id = meal.data("meal"), meal_title = meal.data("mealtitle");
    	if ( !meal_id ) {
	    	return;
    	}

    	jQuery.getScript(FB_DEMO.base_static_uri+"js/bootstrap-modal.min.js").done(function(){
	    	var modal = jQuery( "<div>" ).addClass("modal-content"), form = jQuery( "<form>" ).attr("id","composer").submit(FB_DEMO.share.form_handler);
	    	form.append( jQuery( "<input>" ).attr({"id":"composer-meal","type":"hidden"}).val(meal_id) );

	    	var modal_body = jQuery("<div>").addClass("modal-body");
	    	modal_body.append( jQuery( "<div>" ).attr( "id", "composer-message-group" ).addClass("form-group").append(
				jQuery( "<label>" ).attr("for","composer-message").text(FB_DEMO.share.messages.custom_message)
			).append(
				jQuery( "<div>" ).addClass("controls").append( jQuery("<input>").addClass("form-control input-large").attr( {id:"composer-message",type:"text",maxlength:1000,autocomplete:"off",placeholder:FB_DEMO.share.messages.custom_message_placeholder.replace( /%s/i, meal_title.toLowerCase() )} ) )
			).append( jQuery( "<span>" ).attr("id","composer-message-data").hide() ) );
			modal_body.append( jQuery( "<div>" ).attr("id","autocomplete-fields").append(
				FB_DEMO.share.friends.build_form_fields()
			).append(
				FB_DEMO.share.place.build_form_fields()
			) );
			modal_body.append( jQuery( "<div>" ).addClass("btn-group").attr("id","composer-buttons").append(
				FB_DEMO.share.place.build_toggle()
			).append(
				FB_DEMO.share.friends.build_toggle()
			) );
			modal.append( jQuery("<div>").addClass("modal-header").append( jQuery("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-hidden":"true"}).text("×") ).append( jQuery("<h3>").attr("id","modal-title").addClass("modal-title").text( FB_DEMO.share.messages.action_text ) ) );
			modal.append( modal_body );
			modal_body=null;
			modal.append( jQuery("<div>").addClass("modal-footer").append(jQuery("<button>").addClass("btn").attr({"data-dismiss":"modal","aria-hidden":"true"}).text(FB_DEMO.share.messages.close)).append( jQuery("<button>").addClass("btn btn-primary").attr("type","submit").text(FB_DEMO.share.messages.action_text) ) );
			form.append(modal);
			meal.after( jQuery( "<div>" ).addClass("modal").attr({id:"composer-modal",role:"dialog","aria-labelledby":"modal-title"}).append( jQuery("<div>").addClass("modal-dialog").append(form) ) );
			jQuery("#composer-modal").modal();
			FB_DEMO.share.place.init();
			FB_DEMO.share.friends.init();
			jQuery("#share-button").click(function(){jQuery("#composer-modal").modal()});
		});
	},
	logout_handler: function() {
		jQuery("#composer-modal").remove();
		jQuery("#share-button").remove();
	},
	init: function() {
		jQuery(document).one("facebook-permissions-check",FB_DEMO.share.add_share_button());
		jQuery(document).on("facebook-logged-out",FB_DEMO.share.logout_handler);
	}
};