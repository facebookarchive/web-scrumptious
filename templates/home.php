<?php

include_once( dirname(__FILE__) . '/header.php' ); ?>

<div class="row-fluid">
  <div id="meal-listings" class="span12">
<!--[if lt IE 10]><p>The Scrumptious sample web application prefers Internet Explorer 10 or newer. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->
    <div class="row-fluid"><?php
$item_count = 0;
foreach ( $meals as $meal_id => $meal ) {

	if ( $item_count && $item_count % 3 === 0 )
		echo '</div><div class="row-fluid">';
?><section id="meal-listing-<?php echo $meal_id; ?>" class="meal span4" data-meal="<?php echo $meal_id; ?>" aria-label="<?php echo htmlspecialchars( $meal->title ); ?>">
    <div class="caption">
      <h2><a href="meals/meal.php?id=<?php echo $meal_id; ?>"><?php echo htmlspecialchars( $meal->title ); ?></a></h2>
      <p><a href="meals/meal.php?id=<?php echo $meal_id; ?>"><?php echo htmlspecialchars( $meal->description ); ?></a></p>
    </div>
  </section><?php
	  $item_count++;
}
?>    </div><!--/row-fluid-->
  </div><!--/span-->
</div><!--/row-fluid-->

<script type="text/javascript">
jQuery(document).one( "webp-detect", function(){FB_DEMO.images.add_background_images()} );
</script>

<?php
include_once( dirname(__FILE__) . '/footer.php' );
?>
