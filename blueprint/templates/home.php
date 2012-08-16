<?php
/**
 * Home Template
 *
 * This is the home template.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
<div id="slideshow" class="clearfix theme-default left bottomborder grid_8 alpha">
    <?php 
			echo PLS_Slideshow::slideshow( array( 
						'animation' => 'fade', 									// fade, horizontal-slide, vertical-slide, horizontal-push
						'animationSpeed' => 800, 								// how fast animtions are
						'timer' => true,												// true or false to have the timer
						'pauseOnHover' => true,									// if you hover pauses the slider
						'advanceSpeed' => 4000,									// if timer is enabled, time between transitions 
						'startClockOnMouseOut' => true,					// if clock should start on MouseOut
						'startClockOnMouseOutAfter' => 1000,		// how long after MouseOut should the timer start again
						'directionalNav' => true, 							// manual advancing directional navs
						'captions' => true, 										// do you want captions?
						'captionAnimation' => 'fade', 					// fade, slideOpen, none
						'captionAnimationSpeed' => 800, 				// if so how quickly should they animate in
						'afterSlideChange' => 'function(){}',		// empty function
						'width' => 620, 
						'height' => 300,
						'bullets' => true,
						'context' => 'home',
						'featured_option_id' => 'slideshow-featured-listings', 
						'listings' => array('limit' => 5, 'sort_by' => 'price')
					)
			); 
		?>
</div>

<div id="listing" class="grid_8 alpha">

    <?php echo pls_get_listings( "limit=5&featured_option_id=custom-featured-listings&context=home" ) ?>

    <?php //echo PLS_Map::lifestyle_polygon(array('cur_data' => array(),'location' => array('coords' => array(41.815594,-71.413879))), array('width' => 590, 'height' => 300, 'zoom' => 16,'map_js_var' => 'test4', 'canvas_id' => 'another4', 'life_style_search' => 'true','show_lifestyle_controls' => true, 'show_lifestyle_checkboxes' => true, 'lat' => '41.815594', 'lng' => '-71.413879')); ?>
    <?php //echo pls_get_listings( "limit=5&context=home" ) ?>
    <?php //echo pls_get_listings( "limit=5&featured_option_id=custom-featured-listings&context=home" ) ?>
    <?php //PLS_Listing_Helper::get_compliance(array('context' => 'listings', 'agent_name' => false, 'office_name' => false)); ?>
    <?php //echo PLS_Map::polygon(null, array('width' => 590, 'height' => 250, 'zoom' => 16,'map_js_var' => 'test2', 'canvas_id' => 'another2', 'polygon_search' => 'city')); ?>
</div>
