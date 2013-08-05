<!DOCTYPE html>
<html<?php
if ( Facebook_Sample_Application::LOCALE && strlen( Facebook_Sample_Application::LOCALE ) > 2 )
	echo ' lang="' . substr( Facebook_Sample_Application::LOCALE, 0, 2 ) . '"';
?>>
<head id="thehead"<?php
// @link http://www.w3.org/TR/rdfa-syntax/#s_curies RDFa Core 1.1 CURIEs
$prefixes = '';
foreach ( $og['prefixes'] as $prefix => $reference ) {
	$prefixes .= ' ' . $prefix . ': ' . $reference;
}
if ( $prefixes )
	echo ' prefix="' . ltrim( $prefixes ) . '"';
unset( $prefixes );
?>>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo htmlspecialchars( $og['title'] ) . ( $page_url === Facebook_Sample_Application::BASE_URI ? '' : ' | Scrumptious' ); ?></title>
  <link rel="dns-prefetch" href="//code.jquery.com">
  <link rel="dns-prefetch" href="//connect.facebook.net">
<?php
if ( Facebook_Sample_Application::STATIC_BASE_URI && isset( $_SERVER['SERVER_NAME'] ) && $_SERVER['SERVER_NAME'] ) {
	$static_hostname = strtolower( parse_url( Facebook_Sample_Application::STATIC_BASE_URI, PHP_URL_HOST ) );
	if ( strtolower( $_SERVER['SERVER_NAME'] ) !== parse_url( Facebook_Sample_Application::STATIC_BASE_URI, PHP_URL_HOST ) ) { ?>
  <link rel="dns-prefetch" href="//<?php echo htmlspecialchars( $static_hostname ); ?>">
<?php
	unset( $static_hostname );
	}
} ?>
  <meta name="description" content="<?php echo htmlspecialchars( $og['description'] ) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Facebook">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="canonical" href="<?php echo $page_url ?>">

  <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo Facebook_Sample_Application::STATIC_BASE_URI; ?>favicon.ico" sizes="16x16">
  <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo Facebook_Sample_Application::STATIC_BASE_URI; ?>favicon-stacked.ico" sizes="16x16 32x32 48x48 256x256">
  <link rel="icon" type="image/png" href="<?php echo Facebook_Sample_Application::STATIC_BASE_URI; ?>images/icon.png" sizes="16x16">

  <!-- Open Graph protocol http://ogp.me/ -->
  <meta property="og:locale" content="<?php echo Facebook_Sample_Application::LOCALE ? Facebook_Sample_Application::LOCALE : 'en_US'; ?>">
  <meta property="og:type" content="<?php echo htmlspecialchars( $og['type'] ); ?>">
  <meta property="og:url" content="<?php echo $page_url; ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars( $og['title'] ); ?>">
  <meta property="og:site_name" content="Scrumptious">
  <meta property="og:description" content="<?php echo htmlspecialchars( $og['description'] ); ?>">
<?php
if ( isset( $og['image'] ) ) {
	if ( isset( $og['image']['url'] ) ) { ?>
  <meta property="og:image" content="<?php echo htmlspecialchars( $og['image']['url'] ); ?>">
<?php
	}
	if ( isset( $og['image']['type'] ) ) { ?>
  <meta property="og:image:type" content="<?php echo $og['image']['type'] ?>">
<?php
	}
	if ( isset( $og['image']['width'] ) ) { ?>
  <meta property="og:image:width" content="<?php echo $og['image']['width'] ?>">
<?php
	}
	if ( isset( $og['image']['height'] ) ) { ?>
  <meta property="og:image:height" content="<?php echo $og['image']['height'] ?>">
<?php
	}
}
?>
  <meta property="og:image" content="<?php echo Facebook_Sample_Application::STATIC_BASE_URI; ?>images/logo.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
<?php
if ( Facebook_Sample_Application::APP_ID ) { ?>
  <meta property="fb:app_id" content="<?php echo Facebook_Sample_Application::APP_ID; ?>">
<?php
}
if ( function_exists( 'ogp_extras' ) )
	ogp_extras();

include_once( dirname(__FILE__) . '/base-styles.php' );
include_once( dirname(__FILE__) . '/base-scripts.php' );
?>

</head>
<body>
<div class="navbar navbar-inverse">
  <a class="navbar-brand pull-left" href="<?php echo htmlspecialchars( Facebook_Sample_Application::BASE_URI ); ?>">Scrumptious</a>
  <div id="user-identity" class="pull-right"></div>
</div><!--/navbar-->

<!--
Place an fb-root element for DOM access by the Facebook JavaScript SDK
Assists the creation of new DOM elements positioned relative to the top of the page
-->
<div id="fb-root"></div>

<div class="container">