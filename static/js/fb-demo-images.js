var FB_DEMO = FB_DEMO || {};
FB_DEMO.images = {
	pixel_ratio: 1,
	supports_webp: false,
	set_high_dpi: function() {
		if ( "devicePixelRatio" in window && window.devicePixelRatio != 1 ) {
			FB_DEMO.images.pixel_ratio = window.devicePixelRatio;
		}
	},
	set_web_p: function() {
		var webp = new Image();
		webp.src = "data:image/webp;base64,UklGRiwAAABXRUJQVlA4ICAAAAAUAgCdASoBAAEAL/3+/3+CAB/AAAFzrNsAAP5QAAAAAA==";
		webp.onload = webp.onerror = function(){
			if ( webp.height == 1 ) {
				FB_DEMO.images.supports_webp = true;
			}
			jQuery(document).trigger("webp-detect");
		};
	},
	feature_detect: function() {
		FB_DEMO.images.set_web_p();
		FB_DEMO.images.set_high_dpi();
	},
	get_all_background_image_containers: function() {
		return jQuery(".meal");
	},
	get_all_image_containers: function() {
		return jQuery(".meal .img-container");
	},
	get_image_uri: function( meal_id, container_width ) {
		if ( ( typeof meal_id !== "string" || meal_id === "" ) || ( typeof container_width !== "number" || container_width < 1 ) ) {
			return;
		}

		if ( FB_DEMO.images.supports_webp ) {
			var suffix = ".webp";
		} else {
			var suffix = ".jpg";
		}
		if ( ( container_width * FB_DEMO.images.pixel_ratio ) > 400 ) {
			suffix = "@2x" + suffix;
		}
		return FB_DEMO.base_static_uri + "images/meals/" + meal_id + suffix;
	},
	add_background_images: function() {
		var containers = FB_DEMO.images.get_all_background_image_containers();
		if ( containers.length === 0 ) {
			return;
		}
		containers.each( function( index, container ) {
			container = jQuery(container);
			var image_uri = FB_DEMO.images.get_image_uri( container.data("meal"), container.width() );
			if (!image_uri) {
				return;
			}
			container.css( "background-image", "url("+image_uri+")" );
			image_uri=null;
		} );
	},
	add_images: function() {
		var containers = FB_DEMO.images.get_all_image_containers();
		if ( containers.length === 0 ) {
			return;
		}
		containers.each( function( index, container ) {
			container = jQuery(container);
			var meal_container = container.closest(".meal");
			if ( meal_container.length === 0 ) {
				return;
			}

			var image_uri = FB_DEMO.images.get_image_uri( meal_container.data("meal"), container.width() );
			if ( !image_uri ) {
				return;
			}
			container.empty();
			container.append( jQuery("<img />").addClass("img-responsive").attr({
				alt: meal_container.data("mealtitle"),
				src: image_uri
			}) );
		} );
	}
};
jQuery( function(){
	FB_DEMO.images.feature_detect();
});