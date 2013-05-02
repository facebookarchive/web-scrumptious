<?php
/**
 * Handle minimum parameters not set
 *
 * @uses header()
 */
function response400( $missing_param = '' ) {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 400 Bad Request', true, 400 );
	if ( $missing_param )
		echo json_encode( array( 'error' => $missing_param . ' required' ) );
	else
		echo json_encode( array( 'error' => 'bad parameter' ) );
}

/**
 * Handle meal not found
 *
 * @uses header()
 */
function response404() {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 404 Not Found', true, 404 );
	echo json_encode( array( 'error' => 'No friends found' ) );
	exit();
}

// return as JSON
header( 'Content-Type: application/json; charset=utf8', true );

if ( ! ( isset( $_GET['q'] ) && $_GET['q'] ) ) {
	response400( 'q' );
	exit();
}

// load app configuration data
require_once( dirname( dirname(__FILE__) ) . '/config.php' );

// load Facebook PHP SDK
require_once( dirname( dirname(__FILE__) ) . '/vendor/facebook/php-sdk/src/facebook.php' );

$facebook = new Facebook( array(
	'appId' => Facebook_Sample_Application::APP_ID,
	'secret' => Facebook_Sample_Application::APP_SECRET
) );

$facebook->setExtendedAccessToken();
if ( $facebook->getUser() ) {
	$friends = null;
	try {
		$friends = $facebook->api( '/me/friends?fields=id,name,link,picture', 'GET' );
	} catch( FacebookApiException $e ) {}
	if ( empty( $friends ) || ! isset( $friends['data'] ) || empty( $friends['data'] ) ) {
		response404();
		exit();
	}
	$search_term = strtolower( trim( $_GET['q'] ) );
	$friends = $friends['data'];
	$matched_friends = array();
	foreach( $friends as $friend ) {
		if ( ! ( isset( $friend['id'] ) && isset( $friend['name'] ) ) )
			continue;

		if ( strpos( strtolower( $friend['name']), $search_term ) !== false ) {
			$matched_friend = array( 'label' => $friend['name'], 'value' => $friend['id'] );
			if ( isset( $friend['picture']['data']['url'] ) )
				$matched_friend['picture'] = $friend['picture']['data']['url'];
			if ( isset( $friend['link'] ) )
				$matched_friend['link'] = $friend['link'];
			$matched_friends[] = $matched_friend;
			unset( $matched_friend );
		}
	}
	if ( empty($matched_friends) ) {
		response404();
		exit();
	} else {
		echo json_encode( $matched_friends );
	}
} else {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 403 Forbidden', true, 403 );
	echo json_encode( array( 'error' => 'no active Facebook session' ) );
	exit();
}
?>