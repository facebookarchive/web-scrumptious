<?php
/*
 * Ask the browser and proxies to cache the file for 365 days.
 * Prevents reloading the channel file with each page request
 */
if ( ! headers_sent() ) {
	$cache_expire = 60*60*24*365;
	header( 'Pragma: public' );
	header( 'Cache-Control: max-age=' . $cache_expire );
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_expire ) . ' GMT' );
}
// load information about your Facebook app and runtime config
if ( ! class_exists('Facebook_Sample_Application') )
	require_once( dirname(__FILE__) . '/config.php' );
?><script src="//connect.facebook.net/<?php echo Facebook_Sample_Application::LOCALE ? Facebook_Sample_Application::LOCALE : 'en_US' ?>/all.js"></script>