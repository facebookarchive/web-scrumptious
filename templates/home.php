<?php

include_once( dirname(__FILE__) . '/header.php' ); ?>

<div class="row" id="meal-listings">
<!--[if lt IE 10]><p>The Scrumptious sample web application prefers Internet Explorer 10 or newer. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p><![endif]-->
    <?php
foreach ( $meals as $meal_id => $meal ) {
	//echo '</div><div class="row-fluid">';
?><div class="col-12 col-sm-6 col-lg-6"><section id="meal-listing-<?php echo $meal_id; ?>" class="meal" data-meal="<?php echo $meal_id; ?>" aria-label="<?php echo htmlspecialchars( $meal->title ); ?>">
    <div class="caption">
      <h2><a href="meals/meal.php?id=<?php echo $meal_id; ?>"><?php echo htmlspecialchars( $meal->title ); ?></a></h2>
      <p><a href="meals/meal.php?id=<?php echo $meal_id; ?>"><?php echo htmlspecialchars( $meal->description ); ?></a></p>
    </div>
  </section></div><?php
}
?>
</div><!-- /meal-listings -->

<script type="text/javascript">
jQuery(document).one( "webp-detect", function(){FB_DEMO.images.add_background_images()} );
</script>

<?php
include_once( dirname(__FILE__) . '/footer.php' );
?>
