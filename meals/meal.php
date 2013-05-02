<?php
/**
 * Handle minimum parameters not set
 *
 * @uses header()
 */
function response400( $missing_param = '' ) {
	if ( headers_sent() )
		return;
	header( 'HTTP/1.1 400 Bad Request', true, 400 );
	header( 'Content-Type: text/plain', true );
	if ( $missing_param )
		echo $missing_param . ' required';
	exit();
}

/**
 * Handle meal not found
 *
 * @uses header()
 */
function response404() {
	if ( headers_sent() )
		return;
	header( 'HTTP/1.1 404 Not Found', true, 404 );
	header( 'Content-Type: text/plain', true );
	echo 'Meal not found';
	exit();
}

if ( ! isset( $_GET['id'] ) )
	response400();

$meal_id = trim( $_GET['id'] );
require_once( dirname(__FILE__) . '/meal-options.php' );

if ( ! isset( $meal_options[$meal_id] ) )
	response404();

// load information about your Facebook app and runtime config
if ( ! class_exists('Facebook_Sample_Application') )
	require_once( dirname( dirname(__FILE__) ) . '/config.php' );

$page_url = Facebook_Sample_Application::BASE_URI . 'meals/meal.php?id=' . $meal_id;

$meal = json_decode( file_get_contents( dirname( dirname(__FILE__) ) . '/data/' . $meal_id . '.json' ) );

$debug_file_suffix = '.min';
if ( Facebook_Sample_Application::DEBUG )
	$debug_file_suffix = '';

$og = array(
	'prefixes' => array(
		'og' => 'http://ogp.me/ns#',
		'fb' => 'http://ogp.me/ns/fb#'
	),
	'type' => 'website',
	'title' => $meal->title,
	'description' => $meal->description,
	'image' => array(
		'url' => Facebook_Sample_Application::STATIC_BASE_URI . 'images/meals/' . $meal->id . '-full.jpg',
		'type' => 'image/jpeg'
	)
);
if ( Facebook_Sample_Application::APP_NS ):
	$og['prefixes'][Facebook_Sample_Application::APP_NS] = 'http://ogp.me/ns/fb/' . Facebook_Sample_Application::APP_NS . '#';
	$og['type'] = Facebook_Sample_Application::APP_NS . ':meal';

/**
 * Add custom properties for the meal object in our app namespace
 */
function ogp_extras() {
	global $meal;

	foreach( $meal->ingredients as $ingredient ) { ?>
  <meta property="<?php echo Facebook_Sample_Application::APP_NS; ?>:ingredient" content="<?php echo htmlspecialchars( $ingredient ) ?>">
<?php
	}
}

endif;

include_once( dirname( dirname(__FILE__) ) . '/templates/meal.php' );
?>