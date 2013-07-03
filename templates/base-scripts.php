
<!-- Load jQuery from remote CDN. fallback to local copy if fail -->
<script id="jquery-js" src="http://code.jquery.com/jquery-2.0.3<?php echo $debug_file_suffix; ?>.js"></script>
<script>
window.jQuery || document.write('<script src="<?php echo Facebook_Sample_Application::STATIC_BASE_URI; ?>js/jquery.min.js"><\/script>');

// configure jQuery ajax for caching
jQuery.ajaxSetup({
	cache: true // do not append a timestamp to a request URI
});

var FB_DEMO = FB_DEMO || {}; // initialize JS namespace
FB_DEMO.base_uri = <?php echo json_encode( Facebook_Sample_Application::BASE_URI ); ?>; // build URIs
FB_DEMO.base_static_uri = <?php echo json_encode( Facebook_Sample_Application::STATIC_BASE_URI ); ?>; // build static URIs
</script>