<?php 
/**
 *  Wrapper function for the slideshow function.
 */
function pls_slideshow( $args = '', $data = false ) {

    return PLS_Slideshow::slideshow( $args, $data );
}

/**
 * Slideshow - A slideshow that integrates with the Placester Plugin
 * 
 */

PLS_Slideshow::init();

class PLS_Slideshow {

    public static $listings_to_delete = array();
    public static $listings_to_save = array();
    public static $option_id = '';

    /**
     * Initializes the slideshow.
     */
    static function init() {

		// For Wordpress 3.3.0
        if (!is_admin()) {
            add_action('init', array(__CLASS__,'enqueue'));
        }
    }

    static function enqueue() {

        $slideshow_support = get_theme_support( 'pls-slideshow' );

        wp_register_script( 'pls-slideshow-orbit', trailingslashit( PLS_EXT_URL ) . 'slideshow/orbit/jquery.orbit.js' , array( 'jquery' ), NULL, false );
        wp_register_style( 'pls-slideshow-orbit', trailingslashit( PLS_EXT_URL ) . 'slideshow/orbit/orbit.css' );

        if ( is_array( $slideshow_support ) ) {
            if ( in_array( 'script', $slideshow_support[0] ) )
                wp_enqueue_script( 'pls-slideshow-orbit' );

            if ( in_array( 'style', $slideshow_support[0] ) ) {
                wp_enqueue_style( 'pls-slideshow-orbit' );
            }
            return;
        }

        wp_enqueue_script( 'pls-slideshow-orbit' );
        wp_enqueue_style( 'pls-slideshow-orbit' );
        
    }

    /**
     * Slideshow
     * 
     * @param string $args 
     * @param mixed $data 
     * @static
     * @access public
     * @return void
     */
    static function slideshow( $args = '' ) {

        /** Define the default argument array. */
        $defaults = array(
            'animation' => 'fade', 									// fade, horizontal-slide, vertical-slide, horizontal-push
            'animationSpeed' => 800, 								// how fast animtions are
            'timer' => true, 											// true or false to have the timer
            'advanceSpeed' => 4000,									// if timer is enabled, time between transitions 
            'pauseOnHover' => true,									// if you hover pauses the slider
            'startClockOnMouseOut' => true,					// if clock should start on MouseOut
            'startClockOnMouseOutAfter' => 500,			// how long after MouseOut should the timer start again
            'directionalNav' => true, 							// manual advancing directional navs
            'captions' => true, 										// do you want captions?
            'captionAnimation' => 'fade', 					// fade, slideOpen, none
            'captionAnimationSpeed' => 800, 				// if so how quickly should they animate in
            'afterSlideChange' => 'function(){}',		// empty function
            'bullets' => 'false',											// true or false to activate the bullet navigation
            'bulletThumbs' => false,								// thumbnails for the bullets
            'bulletThumbLocation' => '',						// location from this file where thumbs will be
            'width' => 620,
            'height' => 300,
            'container_height' => false,
            'context' => '',
            'context_var' => false,
            'featured_option_id' => false,
            'allow_user_slides' => false,
            'user_slides_header_id' => false,
            'listings' => 'limit=5&sort_by=price',
            'data' => false,
        );
        $args = wp_parse_args( $args, $defaults );
        $cache = new PLS_Cache('slide');
        if ($result = $cache->get($args)) {
          return $result;
        }
        extract( $args, EXTR_SKIP );

        // pls_dump(pls_get_option($user_slides_header_id));

        if ( !$data || ! is_array($data) ) {
            /** Display a placeholder if the plugin is not active or there is no API key. */
            if ( pls_has_plugin_error() && current_user_can( 'administrator' ) ) {
                global $PLS_API_DEFAULT_LISTING;
                $api_response = $PLS_API_DEFAULT_LISTING;
            } else {
                /** Data assumed to take this form. */
                $data = array('images' => array(),'links' => array(),'captions' => array());

                //if the dev allows user input, get a list of the stytles
                if ($allow_user_slides && $user_slides_header_id) {
                    $slides = pls_get_option($user_slides_header_id);
                    self::$option_id  = $slides();
                    foreach ($slides as $index => $slide) {
                      if (!empty($slide['image']) || !empty($slide['html'])) {
                        // pls_dump($slide);
                        switch ($slide['type']) {
                            case 'listing':
                                unset($slide['html'], $slide['image'], $slide['type'], $slide['link']);
                                $property_id = key($slide);
                                $api_response = PLS_Plugin_API::get_listings_details_list(array('property_ids' => array($property_id)));
                                if (!empty($api_response['listings']) && $api_response['listings'][0]['id'] === false ) {
                                    self::$listings_to_delete[] = $property_id;
                                }
                                if ($api_response['total'] == '1') {
                                    $listing = $api_response['listings'][0];
                                    $listing_url = PLS_Plugin_API::get_property_url( $listing['id'] );
                                    /** Overwrite the placester url with the local url. */
                                    $data['links'][] = $listing_url;
                                    $data['images'][] = ! empty( $listing['images'] ) ?  $listing['images'][0]['url'] : PLS_IMG_URL . "/null/listing-1200x720.jpg";
                                    $data['listing'][] = $listing;
                                    /** Get the listing caption. */
                                    ob_start();
                                    ?>
                                     <div id="caption-<?php echo $index ?>" class="orbit-caption">
                                        <p class="caption-title"><a href="<?php echo $listing['cur_data']['url'] ?>"><?php echo $listing['location']['address'] ?></a></p>
                                        <p class="caption-subtitle"><?php printf( ' <span class="price">%1$s beds</span>, <span class="baths">%2$s baths</span>', $listing['cur_data']['beds'], $listing['cur_data']['baths']); ?></p>
                                        <a class="button details" href="<?php echo $listing['cur_data']['url'] ?>"><span><?php 'See Details' ?></span></a>
                                    </div>
                                    <?php 
                                    $data['captions'][] = trim( ob_get_clean() );
                                } else {

                                }
                                break;
                            case 'default':
                            case 'custom':
                                $data['images'][] = $slide['image'];
                                $data['links'][] = $slide['link'];
                                ob_start();
                                    ?>
                                     <div id="caption-<?php echo $index ?>" class="orbit-caption">
                                        <?php echo $slide['html']; ?>
                                    </div>
                                    <?php 
                                $data['captions'][] = trim( ob_get_clean() );
                                break;
                        } // end switch
                      } // end conditional
                    }
                } else {
                    if ($featured_option_id) {
                        self::$option_id = $featured_option_id;
                        $api_response = PLS_Listing_Helper::get_featured($featured_option_id);
                    } 

                    if (empty($api_response['listings'])) {
                        $api_response = PLS_Plugin_API::get_property_list($listings);    
                    }

                    $listings = $api_response['listings'];
                    foreach ($listings as $index => $listing) {

                        if ($listing['id'] === false ) {
                            self::$listings_to_delete[] = $listing['id'];
                        } else {
                            self::$listings_to_save[] = $listing['id'];
                        }

                        $listing_url = PLS_Plugin_API::get_property_url( $listing['id'] );
                        
                        /** Overwrite the placester url with the local url. */
                        $data['links'][] = $listing_url;
                        $data['images'][] = ! empty( $listing['images'] ) ?  $listing['images'][0]['url'] : PLS_IMG_URL . "/null/listing-1200x720.jpg";
                        $data['listing'][] = $listing;

                        /** Get the listing caption. */
                        ob_start();
                        ?>
                         <div id="caption-<?php echo $index ?>" class="orbit-caption">
                            <p class="caption-title"><a href="<?php echo $listing['cur_data']['url'] ?>"><?php echo $listing['location']['address'] ?></a></p>
                            <p class="caption-subtitle"><?php printf( ' <span class="price">%1$s beds</span>, <span class="baths">%2$s baths</span>', $listing['cur_data']['beds'], $listing['cur_data']['baths']); ?></p>
                            <a class="button details" href="<?php echo $listing['cur_data']['url'] ?>"><span><?php 'See Details' ?></span></a>
                        </div>
                        <?php 
                        $data['captions'][] = trim( ob_get_clean() );
                    }
                }
            }
        }

        /** Filter the data array. */
        $data = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_data', $context ), '_', 'pre', false ), $data, $context, $context_var );

        $html = array(
            'slides' => '',
            'captions' => '',
        );
        /** Create the slideshow */
        foreach( $data['images'] as $index => $slide_src ) {
            $extra_attr = array();
            $extra_attr['title'] = '';

            /** Save the caption and the title attribute for the img. */
            if ( isset( $data['captions'][$index] ) ) {
                $html['captions'] .= $data['captions'][$index];
                $extra_attr['title'] = "#caption-{$index}";
            }

            /** Create the img element. */
            $slide = pls_h_img($slide_src, false, $extra_attr);

            /** Wrap it in an achor if the anchor exists. */
            if ( isset( $data['links'][$index] ) )
                $slide = pls_h_a( $data['links'][$index], $slide, array('data-caption' => "#caption-{$index}") );

            $html['slides'] .= $slide;

}
        /** Combine the html. */
        $html = pls_h_div(
            $html['slides'],
            array( 'id' => 'slider', 'class' => 'orbitSlider' ) 
        ) . $html['captions'];
        
        /** Filter the html array. */
        $html = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_html', $context ), '_', 'pre', false ), $html, $data, $context, $context_var, $args );

        if (!$container_height) {
          $container_height = $height;
        }

        /** The javascript needed for orbit. */
        ob_start();
		?>
		<style type="text/css">
			.orbit-wrapper {
				width:<?php echo $width; ?>px !important;
				height:<?php echo $container_height; ?>px !important;
				overflow: hidden;
			}
			#slider, #slider img {
				width:<?php echo $width; ?>px !important;
				height:<?php echo $height; ?>px !important;
				background: #000 url( <?php echo PLS_EXT_URL; ?> '/orbit-slider/orbit/loading.gif') no-repeat center center; 
				overflow: hidden;
			}
		</style>
		<?php 
		        /** Geth the css. */
		        $css = ob_get_clean();

		        /** The javascript needed for orbit. */
		        ob_start();
		?>
		<script type="text/javascript">
		jQuery(window).load(function($) {
		    jQuery('#slider').orbit({
						animation: '<?php echo $animation ?>', 																	// fade, horizontal-slide, vertical-slide, horizontal-push
						animationSpeed: <?php echo $animationSpeed ?>, 													// how fast animtions are
						timer: <?php echo $timer ?>, 																						// true or false to have the timer
						advanceSpeed: <?php echo $advanceSpeed ?>,															// if timer is enabled, time between transitions 
						pauseOnHover: <?php echo $pauseOnHover ?>,															// if you hover pauses the slider
						startClockOnMouseOut: <?php echo $startClockOnMouseOut ?>,							// if clock should start on MouseOut
						startClockOnMouseOutAfter: <?php echo $startClockOnMouseOutAfter ?>,		// how long after MouseOut should the timer start again
						directionalNav: <?php echo $directionalNav ?>, 													// manual advancing directional navs
						captions: <?php echo $captions ?>, 																			// do you want captions?
						captionAnimation: '<?php echo $captionAnimation ?>',										// fade, slideOpen, none
						captionAnimationSpeed: <?php echo $captionAnimationSpeed ?>, 						// if so how quickly should they animate in
						bullets: <?php echo $bullets ? 'true' : 'false' ?>,			 // true or false to activate the bullet navigation
						// bulletThumbs: false,		 // thumbnails for the bullets
						// bulletThumbLocation: '',		 // location from this file where thumbs will be
						width: 620,
						height: 300,
						context: '',
						context_var: false,
						listings: '<?php echo $listings ?>',
						data: '<?php echo $data ?>',
		
		    });
		});
		</script>
		<?php 
        /** Geth the js. */
        $js = ob_get_clean();
		$js = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_js', $context ), '_', 'pre', false ), $js, $html, $data, $context, $context_var );
        $full_slideshow = apply_filters( pls_get_merged_strings( array( 'pls_slideshow', $context ), '_', 'pre', false ), $css . $html . $js, $html, $js, $data, $context, $context_var, $args );
        $cache->save($full_slideshow);
        self::delete_listings();
        return $full_slideshow;
    }

    static function prepare_single_listing ($listing = false) {


        $slide_array = array();

        if ($listing && isset($listing['images'])) {

          // for listings with no images
          if (empty($listing['images'])) {
            $empty_slide_array['images'][0] = PLS_IMG_URL . "/null/listing-1200x720.jpg";
            // Default single image slide array successfully created
            return $empty_slide_array;
          }

            foreach ($listing['images'] as $image) {
            $slide_array['images'][] = $image['url'];
            // $slide_array['links'][] = 'google.com';
            $slide_array['captions'][] = '';
            // $slide_array['listing'][] = $data;
            }

            // Slide array successfully created
            return $slide_array;
        } elseif ($listing && isset($listing->images)) {
						foreach ($listing->images as $image) {
		          $slide_array['images'][] = $image->url;
		          // $slide_array['links'][] = 'google.com';
		          $slide_array['captions'][] = '';
		          // $slide_array['listing'][] = $data;
	          }

	          // Slide array successfully created
	          return $slide_array;
				}	

        return false;
    }

    function delete_listings () {
        // pls_dump(self::$listings_to_delete, self::$listings_to_save, self::$option_id);
        $config = get_option( 'optionsframework' );
        $options = get_option( $config['id'] );
        if ($options[self::$option_id]) {
            foreach ($options[self::$option_id] as $id => $address) {
                if ( !in_array($id, self::$listings_to_save) ) {
                    unset($options[self::$option_id][$id]);
                }
            }
            update_option( $config['id'], $options );
        }
    }

//end of class
}
