<?php
// Privacy policy URL required by Facebook Platform Policy
// Tells users what user data you are going to use and how you will use, display, share, or transfer that data

// load information about your Facebook app and runtime config
if ( ! class_exists('Facebook_Sample_Application') )
	require_once( dirname(__FILE__) . '/config.php' );

$debug_file_suffix = '.min';
if ( Facebook_Sample_Application::DEBUG )
	$debug_file_suffix = '';

$og = array(
	'prefixes' => array(
		'og' => 'http://ogp.me/ns#',
		'fb' => 'http://ogp.me/ns/fb#'
	),
	'type' => 'website',
	'title' => 'Privacy Policy',
	'description' => 'Scrumptious does not store any personal data.'
);

$page_url = Facebook_Sample_Application::BASE_URI . 'privacy.php';

include_once( dirname(__FILE__) . '/templates/privacy.php' );
?>