<?php 

/*
Based heavily on Wes Edling's chaching/scaling script, modified to work properly in our context.
Modifications include:
	 - fixing the way urls are handled to remove get vars in the image name
	 - rewrote to use GD for image manipulation rather then ImageMagic

TODO: 
	- break this out into reusable functions so the logic is more obvious
	- performance testing / optimization.

Here's Wes' requested attribution for the modified "resize" function:

function by Wes Edling .. http://joedesigns.com
feel free to use this in any project, i just ask for a credit in the source code.
a link back to my site would be nice too.

** Wes' resizing was removed because WP Theme Submission didn't allow file_get_contents();

*/

// Include the GD image manipulation library. 
include(trailingslashit ( PLS_EXT_DIR ) . 'image-util/image-resize-writer.php');

PLS_Image::init();
class PLS_Image {

	static function init() {

		if (!is_admin()) {
			add_action('init', array(__CLASS__,'enqueue'));
		}
	}

    static function enqueue() {

        $image_util_support = get_theme_support( 'pls-image-util' );

		if ( !wp_script_is('pls-image-util-fancybox' , 'registered') ) {
	        wp_register_script( 'pls-image-util-fancybox', trailingslashit( PLS_EXT_URL ) . 'image-util/fancybox/jquery.fancybox-1.3.4.pack.js' , array( 'jquery' ), NULL, true );
		}

		if ( !wp_script_is('pls-image-util-fancybox-default-settings' , 'registered') ) {
        	wp_register_script( 'pls-image-util-fancybox-default-settings', trailingslashit( PLS_EXT_URL ) . 'image-util/fancybox/default-settings.js' , array( 'jquery' ), NULL, true );
		}
		
		if ( !wp_style_is('pls-image-util-fancybox-style' , 'registered') ) {
        	wp_register_style( 'pls-image-util-fancybox-style', trailingslashit( PLS_EXT_URL ) . 'image-util/fancybox/jquery.fancybox-1.3.4.css' );
		}

        if ( is_array( $image_util_support ) ) {
            if ( in_array( 'fancybox', $image_util_support[0] ) ) {
              	if ( !wp_script_is('pls-image-util-fancybox' , 'queue') ) {
	  				wp_enqueue_script( 'pls-image-util-fancybox' );
              	}

				if ( !wp_script_is('pls-image-util-fancybox-default-settings' , 'queue') ) {
	                wp_enqueue_script( 'pls-image-util-fancybox-default-settings' );
				}

				if ( !wp_style_is('pls-image-util-fancybox-style' , 'queue') ) {
	                wp_enqueue_style( 'pls-image-util-fancybox-style' );
				}
            }
            return;
        }
    }

	static function load ( $old_image = '', $args = null )
	{
		$new_image = false;

		if (isset($args['fancybox']) && $args['fancybox']) {
			unset($args['fancybox']);
		}

		// pls_dump($args);

        $args = self::process_defaults($args);

		// use standard default image
		if ( $old_image === '' || empty($old_image)) {
			$old_image = PLS_IMG_URL . "/null/listing-1200x720.jpg";
		}

		if ( $args['fancybox'] || $args['as_html']) {
			if ($new_image) {
				$new_image = self::as_html($old_image, $new_image, $args);
			} else {
				$new_image = self::as_html($old_image, null, $args);
			}
		}

		// return the new image if we've managed to create one
		if ($new_image) {
			return $new_image;
		} else {
			return $old_image;
		}

	}
	
	static private function as_html ($old_image, $new_image = false, $args )
	{

		extract( $args, EXTR_SKIP );
		// echo 'here in html';
		// pls_dump($html);
		if ($fancybox && !$as_html) {
			// echo 'fancybox';
			ob_start();
			// our basic fancybox html
			?>
				<a ref="#" rel="<?php echo @$html['rel']; ?>" class="<?php echo @$fancybox['trigger_class'] . ' ' .  @$html['classes']; ?>" href="<?php echo @$old_image; ?>" >
					<img alt="<?php echo @$html['alt']; ?>" title="<?php echo @$html['title'] ? $html['title'] : ''; ?>" class="<?php echo @$html['img_classes']; ?>" style="width: <?php echo @$resize['w']; ?>px; height: <?php echo @$resize['h']; ?>px; overflow: hidden;" src="<?php echo $new_image ? $new_image : $old_image; ?>" />
				</a>
			<?php
			
			return trim( ob_get_clean() );
			
			
		} else {
			ob_start();
			?>
			<img class="<?php echo @$html['img_classes']; ?>" style="width: <?php echo @$resize['w']; ?>px; height: <?php echo @$resize['h']; ?>px; overflow: hidden;" src="<?php echo $new_image ? $new_image : $old_image; ?>" alt="<?php echo @$html['alt']; ?>" title="<?php echo $html['title'] ?>" itemprop="image" />
			<?php
		
			return trim(ob_get_clean());
		}
	}
	

	private static function process_defaults ($args) {

		/** Define the default argument array. */
		$defaults = array(
			'resize' => array(
				'w' => false,
				'h' => false
				),
			'html' => array(
				'ref' => '',
				'rel' => 'gallery',
				'a_classes' => '',
				'img_classes' => '',
				'alt' => '',
				'title' => ''
				),
			'as_html' => false,
			'as_url' => true,
			'fancybox' => array(
			'trigger_class' => 'pls_use_fancy',
			'classes' => false
			),
		);

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );
        $args['resize'] = wp_parse_args( $args['resize'], $defaults['resize']);
        $args['html'] = wp_parse_args( $args['html'], $defaults['html']);

        return $args;
				
	}
}// end class 

?>