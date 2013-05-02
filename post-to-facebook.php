<?php
header( 'Content-Type: application/json; charset=utf8', true );
if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
	if ( ! headers_sent() ) {
		header( 'HTTP/1.1 405 Method Not Allowed', true, 405 );
		header( 'Allow: POST' );
	}
	echo json_encode( array( 'error' => 'POST only' ) );
	exit();
}

require_once( dirname(__FILE__) . '/meals/meal-options.php' );

if ( ! isset( $_POST['meal'] ) || ! isset( $meal_options[ $_POST['meal'] ] ) ) {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 400 Bad Request', true, 400 );
	echo json_encode( array( 'error' => 'no valid meal specified or no app namespace set' ) );
}

// load app configuration data
require_once( dirname(__FILE__) . '/config.php' );

$params = array(
	'meal' => Facebook_Sample_Application::BASE_URI . 'meals/meal.php?' . http_build_query( array( 'id' => $_POST['meal'] ) ),
	'fb:explicitly_shared' => true
);

// custom message
if ( isset( $_POST['message'] ) && $_POST['message'] ) {
	$message = trim( $_POST['message'] );
	if ( $message )
		$params['message'] = $message;
	unset( $message );
}

// action tags
if ( isset( $_POST['tags'] ) && $_POST['tags'] ) {
	$tags = explode( ',', trim( $_POST['tags'] ) );
	$valid_tags = array();
	foreach( $tags as $tag ) {
		if ( ctype_digit( $tag ) )
			$valid_tags[] = $tag;
	}
	unset( $tags );
	if ( ! empty( $valid_tags ) )
		$params['tags'] = implode( ',', $valid_tags );
	unset( $valid_tags );
}

// place
if ( isset( $_POST['place'] ) && ctype_digit( trim( $_POST['place'] ) ) )
	$params['place'] = trim( $_POST['place'] );

// load Facebook PHP SDK
require_once( dirname(__FILE__) . '/vendor/facebook/php-sdk/src/facebook.php' );

$facebook = new Facebook( array(
	'appId' => Facebook_Sample_Application::APP_ID,
	'secret' => Facebook_Sample_Application::APP_SECRET
) );

$facebook->setExtendedAccessToken();
if ( $facebook->getUser() && Facebook_Sample_Application::APP_NS ) {
	try {
		$result = $facebook->api( 'me/' . Facebook_Sample_Application::APP_NS . ':eat', 'POST', $params );
	} catch( FacebookApiException $e ) {
		header( 'HTTP/1.1 500 Internal Server Error', true, 500 );
		echo json_encode( array( 'error' => $e->getCode() . ' ' . $e->getMessage() ) );
		exit();
	}
	if ( isset( $result ) )
		echo json_encode( $result );
	else
		echo '{}';
} else {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 403 Forbidden', true, 403 );
	echo json_encode( array( 'error' => 'must be logged in to Facebook' ) );
	exit();
}
?>