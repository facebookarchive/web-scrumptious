<?php include_once( dirname(__FILE__) . '/header.php' ); ?>

<section id="meal" class="meal row" data-meal="<?php echo htmlspecialchars( $meal->id ); ?>" data-mealtitle="<?php echo htmlspecialchars( $meal->title ); ?>" role="main">
  <div class="col-12 col-sm-4 col-lg-4 img-thumbnail img-container"><noscript><img alt="<?php echo htmlspecialchars( $meal->title ); ?>" src="<?php echo htmlspecialchars( Facebook_Sample_Application::STATIC_BASE_URI . 'images/meals/' . $meal->id . '.jpg' ); ?>"></noscript></div>
  <div class="col-12 col-sm-8 col-lg-8">
    <header>
      <h1><?php echo htmlspecialchars( $meal->title ); ?></h1>
      <p class="lead"><?php echo htmlspecialchars( $meal->description ); ?></p>
    </header>

    <p id="ingredients">Ingredients: <?php echo htmlspecialchars( implode( ', ', $meal->ingredients ) ); ?></p>

    <div id="social-actions"></div>
  </div>
</section>

<script>jQuery(document).one( "webp-detect", function(){FB_DEMO.images.add_images()} );</script>
<?php

if ( Facebook_Sample_Application::APP_NS ):
function fb_async_after_init() { ?>
jQuery(document).one("facebook-logged-in",function(){
	jQuery.getScript("http://code.jquery.com/ui/1.10.3/jquery-ui.js").done(function(){
	jQuery.getScript(<?php echo json_encode( Facebook_Sample_Application::STATIC_BASE_URI . 'js/fb-demo-share.js' ); ?>).done(function(){
		jQuery("#thehead").append( jQuery("<link />").attr({rel:"stylesheet",type:"text/css",href:<?php echo json_encode( Facebook_Sample_Application::STATIC_BASE_URI . 'css/jquery-ui-autocomplete.css' ) ?>}) );
		FB_DEMO.share.init();
	});
})});
<?php }
endif;

include_once( dirname(__FILE__) . '/footer.php' );
?>
