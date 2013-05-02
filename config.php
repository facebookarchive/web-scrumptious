<?php

/**
 * Configure this file with your own unique server and Facebook application information
 *
 * @link https://developers.facebook.com/apps/ Facebook application information
 */
class Facebook_Sample_Application {
	/**
	 * Turn on extra debugging output such as console output
	 * Loads the debug version of the Facebook JavaScript SDK and non-minified third-party scripts
	 *
	 * @var bool
	 */
	const DEBUG = true;

	/**
	 * Facebook application identifier
	 * Uniquely identifies your web pages to Facebook
	 * Binds JavaScript SDK actions to a specific application context
	 *
	 * @var string
	 */
	const APP_ID = '';

	/**
	 * Facebook application secret
	 * Verifies your application with Facebook, like a password
	 *
	 * @var string
	 */
	const APP_SECRET = '';

	/**
	 * Facebook application namespace
	 * Prefixes your application data properties
	 *
	 * @var string
	 */
	const APP_NS = '';

	/**
	 * Choose a custom locale from a list of Facebook locales
	 *
	 * @link https://www.facebook.com/translations/FacebookLocales.xml Facebook locales XML
	 * @var string
	 */
	const LOCALE = 'en_US';

	/**
	 * Base URI. Used to build absolute URIs
	 * Example: http://example.com/
	 *
	 * @var string
	 */
	const BASE_URI = '';

	/**
	 * Static URI. Used to build absolute URIs of static assets such as CSS, JS, images
	 * Example: http://s.example.com/
	 *
	 * @var string
	 */
	const STATIC_BASE_URI = '';

	/**
	 * Settings used to initialize the Facebook JS SDK
	 *
	 * @link https://developers.facebook.com/docs/reference/javascript/FB.init/ FB.init options
	 */
	public static function js_sdk_init_options() {
		$init_variables = array(
			'channelUrl' => Facebook_Sample_Application::BASE_URI . 'channel.php',
			'status'     => true,
			'cookie'     => true
		);
		if ( self::APP_ID )
			$init_variables['appId'] = Facebook_Sample_Application::APP_ID;
		return $init_variables;
	}
}

?>
