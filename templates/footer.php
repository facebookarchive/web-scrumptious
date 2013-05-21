<hr>

<footer>
  <p>&copy; <a href="https://developers.facebook.com/" title="Facebook Developers">Facebook</a> 2013 | <a href="<?php echo htmlspecialchars( Facebook_Sample_Application::BASE_URI . 'terms.php' ); ?>" title="Terms of Service">Terms</a> | <a href="<?php echo htmlspecialchars( Facebook_Sample_Application::BASE_URI . 'privacy.php' ); ?>" title="Privacy Policy">Privacy</a></p>
</footer>

</div><!--/.fluid-container-->

<script src="<?php echo htmlspecialchars( Facebook_Sample_Application::STATIC_BASE_URI . 'js/fb-demo-images.js' ); ?>"></script>
<script>
// Facebook JavaScript SDK will execute this function script download and execution
window.fbAsyncInit = function() {
	if ( window.FB === undefined ) {
		return;
	}

	// Initialize the Facebook JavaScript SDK
	FB.init(<?php echo json_encode(Facebook_Sample_Application::js_sdk_init_options()); ?>);
<?php
if ( function_exists( 'fb_async_after_init' ) ) {
	fb_async_after_init();
} ?>
	jQuery.getScript( <?php echo json_encode( Facebook_Sample_Application::STATIC_BASE_URI . 'js/fb-demo.js' ); ?> ).done(function(){FB_DEMO.init()} );
<?php
if ( function_exists( 'fb_async_init_extras' ) ) {
	fb_async_init_extras();
} ?>
}

jQuery(function() {
	var fb_js_sdk_id = "facebook-jssdk";
	if ( jQuery("#"+fb_js_sdk_id).length !== 0 ) {
		return;
	}
	var script = jQuery( "<script>" ).attr({id:fb_js_sdk_id, src:<?php echo json_encode( '//connect.facebook.net/' . ( Facebook_Sample_Application::LOCALE ? Facebook_Sample_Application::LOCALE : 'en_US' ) . '/all' . ( Facebook_Sample_Application::DEBUG ? '/debug' : '' ) . '.js' ); ?>});
	document.getElementById("thehead").appendChild(script[0]);
});
</script>
</body>
</html>