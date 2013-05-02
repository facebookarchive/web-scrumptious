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
	echo json_encode( array( 'error' => 'No places found' ) );
}

// return as JSON
header( 'Content-Type: application/json; charset=utf8', true );

if ( ! ( isset( $_GET['q'] ) && $_GET['q'] ) ) {
	response400( 'q' );
	exit();
}

$params = array(
	'type' => 'place',
	'distance' => 1000,
	'fields' => 'id,name,location,picture,were_here_count,is_published,link',
	'center' => '37.787943,-122.407548', // Union Square San Francisco
	'q' => trim( $_GET['q'] ),
	'limit' => 5
);
if ( isset( $_GET['center'] ) && $_GET['center'] )
	$params['center'] = trim( $_GET['center'] );

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
	$results = null;
	try {
		$results = $facebook->api( 'search', 'GET', $params );
	} catch( FacebookApiException $e ) {}
	if ( empty( $results ) || ! isset( $results['data'] ) || empty( $results['data'] ) ) {
		response404();
		exit();
	}
	$results = $results['data'];
	$clean_results = array();
	foreach( $results as $result ) {
		if ( ! ( isset( $result['is_published'] ) && $result['is_published'] && isset( $result['id'] ) && $result['id'] && isset( $result['name'] ) && $result['name'] ) )
			continue;
		$clean_result = array( 'label' => trim( $result['name'] ), 'value' => $result['id'], 'link' => $result['link'] );

		// build location components for use in place summary
		if ( isset( $result['location'] ) ) {
			$location = array();

			if ( isset( $result['location']['street'] ) && $result['location']['street'] )
				$location['street'] = trim( $result['location']['street'] );
			if ( isset( $result['location']['city'] ) && $result['location']['city'] && isset( $result['location']['state'] ) && $result['location']['state'] )
				$location['area'] = trim( $result['location']['city'] . ', ' . $result['location']['state'] );
			else if ( isset( $result['location']['state'] ) && $result['location']['state'] && isset( $result['location']['country'] ) && $result['location']['country'] )
				$location['area'] = trim( $result['location']['state'] . ', ' . $result['location']['country'] );
			else if ( isset( $result['location']['country'] ) && $result['location']['country'] )
				$location['area'] = trim( $result['location']['country'] );

			if ( ! empty( $location ) )
				$clean_result['location'] = $location;
			unset( $location );
		}

		if ( isset( $result['were_here_count'] ) && $result['were_here_count'] )
			$clean_result['were_here_count'] = (int) $result['were_here_count'];

		if ( isset( $result['picture']['data']['url'] ) && $result['picture']['data']['url'] )
			$clean_result['picture'] = $result['picture']['data']['url'];

		$clean_results[] = $clean_result;
		unset( $clean_result );
	}
	echo json_encode( $clean_results );
} else {
	if ( ! headers_sent() )
		header( 'HTTP/1.1 403 Forbidden', true, 403 );
	echo json_encode( array( 'error' => 'no active Facebook session' ) );
}
?>