<?php
// Set expectations

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
	'title' => 'Terms of Service',
	'description' => 'Scrumptious is a sample application intended to be used for demonstrative purposes only.'
);

$page_url = Facebook_Sample_Application::BASE_URI . 'terms.php';

include_once( dirname(__FILE__) . '/templates/terms.php' );
?>