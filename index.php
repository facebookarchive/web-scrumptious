<?php

/**
 * SELECT * FROM meals
 */
function get_all_meals() {
	// SELECT id FROM meals
	require_once( dirname(__FILE__) . '/meals/meal-options.php' );
	$data_directory = dirname(__FILE__) . '/data/';
	$meals = array();
	foreach ( $meal_options as $meal_option => $exists ) {
		$meals[ $meal_option ] = json_decode( file_get_contents( $data_directory . $meal_option . '.json' ) );
	}
	return $meals;
}

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
	'title' => 'Scrumptious sample web application',
	'description' => 'Scrumptious sample web application. Choose a meal, friends, and place before posting a story to your Facebook Timeline.'
);

$page_url = Facebook_Sample_Application::BASE_URI;
$meals = get_all_meals();
include_once( dirname(__FILE__) . '/templates/home.php' );
?>
