<?php

/****** 
	This class defines the shortcodes that can be used to access blueprint 
	functionality from any page or post.

	It handles both the implementation of each code in addition to hooking 
	them into the proper events and filters.
******/

PL_Shortcodes::init();
class PL_Shortcodes
{
	public static $codes = array('searchform', 'listings', 'prop_details');

	public static $p_codes = array('searchform' => 'Search Form Shortcode', 'listings' => 'Listings Shortcode', 'prop_details' => 'Property Details Template');
	
	// TODO: Construct these lists dynamically by examining the doc hierarchy...
	public static $defaults = array('searchform' 	=> array('twentyten', 'twentyeleven'),
	  				                'prop_details' 	=> array('twentyten', 'twentyeleven'),
			               			'listings' 		=> array('twentyten', 'twentyeleven') );

	public static $subcodes = array('searchform'  =>  array('bedrooms',
												            'min_beds',
												            'max_beds',
												            'bathrooms',
												            'min_baths',
												            'max_baths',
												            'price',
												            'half_baths',
												            'property_type',
												            'listing_types',
												            'zoning_types',
												            'purchase_types',
												            'available_on',
												            'cities',
												            'states',
												            'zips',
												            'neighborhood',
												            'county',
												            'min_price',
												            'max_price',
												            'min_price_rental',
	        												'max_price_rental'),
            						'listing'	  =>  array('price',
            												'sqft',
            												'beds',
            												'baths',
            												'half_baths',
            												'avail_on',
            												'url',
            												'address',
            												'locality',
            												'region',
            												'postal',
            												'neighborhood',
            												'county',
            												'country',
            												'coords',
            												'unit',
            												'full_address',
            												'email',
            												'phone',
            												'desc',
            												'image',
            												'mls_id',
            												'map',
            												'listing_type',
            												'gallery',
            												'amenities',
            												'compliance')
            						);

	// TODO: These are a temporary solution, come up with a better convention...
	public static $form_html = false;
	public static $listing = false;
	public static $prop_details_enabled_key = 'pls_prop_details_enabled';

	public function init() 
	{
		add_shortcode('searchform', array(__CLASS__, 'searchform_shortcode_handler'));
		add_shortcode('listings', array(__CLASS__, 'listings_shortcode_handler'));

		// For any shortcodes that use subcodes, register them to a single handler that bears the shortcode's name
		foreach (self::$subcodes as $code => $subs) {
		  foreach ($subs as $sub) {
			add_shortcode($sub, array(__CLASS__, $code . '_sub_shortcode_handler'));
		  }	
		}

		// Register hooks to generate the forms...
		add_filter('pls_listings_search_form_outer_shortcode', array(__CLASS__, 'searchform_shortcode_context'), 10, 6);
		add_filter('pls_listings_list_ajax_item_html_shortcode', array(__CLASS__, 'listings_shortcode_context'), 10, 3);
		add_filter('property_details_filter', array(__CLASS__, 'prop_details_shortcode_context'), 10, 2);

		// TODO: Construct defaults array properly...

		// Ensure all of shortcodes are set to some snippet...
		foreach (self::$codes as $code) {
			add_option( ('pls_' . $code), self::$defaults[$code][0] );
		}

		// Handle the special case of turning property details functionality on/off...
		add_option( self::$prop_details_enabled_key, 'false' ); 
	}


/*** Shortcode Handlers ***/	
	
	public static function searchform_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		// Ajax setting as an attr?

		// Default form enclosure
		$header = '<form method="post" action="' . esc_url( home_url( '/' ) ) . 'listings" class="pls_search_form_listings">';
		$footer = '</form>';

		return ( $header . PLS_Partials_Listing_Search_Form::init(array('context' => 'shortcode', 'ajax' => true)) . $footer );
	}

	public static function listings_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		// These attributes will hand the look and feel of the listing form container, as 
		// the context func applies to each individual listing.
	  ob_start();
	    PLS_Partials_Get_Listings_Ajax::load(array('context' => 'shortcode'));
	  return ob_get_clean();  
	}


/*** Context Filter Handlers ***/	

	public static function searchform_shortcode_context($form, $form_html, $form_options, $section_title, $form_data)
	{
		$shortcode = 'searchform';
		self::$form_html = $form_html;

		$snippet_body = self::get_active_snippet_body($shortcode);
		return do_shortcode($snippet_body);
	}

	// It's important to note that this is called for every individual listing...
	public static function listings_shortcode_context($item_html, $listing) 
	{
		$shortcode = 'listings';
		self::$listing = $listing;

		// ob_start();
		//   echo pls_dump($listing);
		// return ob_get_clean();

	  	$snippet_body = self::get_active_snippet_body($shortcode);
	  	return do_shortcode($snippet_body);
	}

	public static function prop_details_shortcode_context($html, $listing_data) 
	{
		// Check to see if this functionality is enabled...
		$enabled = get_option( self::$prop_details_enabled_key, 'false' );
		
		if ($enabled == 'true') 
		{
			$shortcode = 'prop_details';
			self::$listing = $listing_data;

		  	$snippet_body = self::get_active_snippet_body($shortcode);
		  	return do_shortcode($snippet_body);
	  	}
	  	else 
	  	{
	  		// Simply pass on what was originally sent the filter...
	  		return $html;
	  	}
	}


/*** Sub-Shortcode Handlers ***/

	public static function searchform_sub_shortcode_handler ($atts, $content, $tag) 
	{
		//pls_dump($tag);
		return self::$form_html[$tag];
	}

	public static function listing_sub_shortcode_handler ($atts, $content, $tag) 
	{
		$val = '';

		if (array_key_exists($tag, self::$listing['cur_data'])) { 
			$val = self::$listing['cur_data'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['location'])) { 
			$val = self::$listing['location'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['contact'])) { 
			$val = self::$listing['contact'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['rets'])) { 
			$val = self::$listing['rets'][$tag];
		}
		else { 
		}

		// This is an example of handling a specific tag in a different way
		// TODO: make this more elegant...
		switch ($tag)
		{
			case 'desc':
			    $max_len = array_key_exists('maxlen', $atts) ? (int)$atts['maxlen'] : 500;
			    $val = substr($val, 0, $max_len);
			    break;
			case 'image':
				$width = array_key_exists('width', $atts) ? (int)$atts['width'] : 180;
				$height = array_key_exists('height', $atts) ? (int)$atts['height'] : 120;
				$val = PLS_Image::load(self::$listing['images'][0]['url'], 
									   array('resize' => array('w' => $width, 'h' => $height), 
									   'fancybox' => true, 
									   'as_html' => true, 
									   'html' => array('alt' => self::$listing['location']['full_address'], 
													   'itemprop' => 'image')));
				break;
			case 'gallery':
				ob_start();
				?>
					<div id="slideshow" class="clearfix theme-default left bottomborder">
						<div class="grid_8 alpha">
							<ul class="property-image-gallery grid_8 alpha">
								<?php foreach (self::$listing['images'] as $image): ?>
									<li><?php echo PLS_Image::load($image['url'], 
										                           array('resize' => array('w' => 100, 'h' => 75), 
																   		 'fancybox' => true, 
																   		 'as_html' => false, 
																   		 'html' => array('itemprop' => 'image'))); ?>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</div>
				<?php
				$val = ob_get_clean();
				break;
			case 'map':
				$val = PLS_Map::lifestyle(self::$listing, array('width' => 590, 'height' => 250, 'zoom' => 16, 'life_style_search' => true,
																'show_lifestyle_controls' => true, 'show_lifestyle_checkboxes' => true, 
																'lat' => self::$listing['location']['coords'][0], 'lng' => self::$listing['location']['coords'][1]));
				break;
			case 'price':
				$val = PLS_Format::number(self::$listing['cur_data']['price'], array('abbreviate' => false, 'add_currency_sign' => true));
				break;
			case 'listing_type':
				$val = PLS_Format::translate_property_type(self::$listing);
				break;
			case 'amenities':
				$amenities = PLS_Format::amenities_but(&self::$listing, array('half_baths', 'beds', 'baths', 'url', 'sqft', 'avail_on', 'price', 'desc'));
				$amen_type = array_key_exists('type', $atts) ? (string)$atts['type'] : 'list';
				ob_start();
				?>
					<div class="amenities-section grid_8 alpha">
	                    <ul>
	                    <?php PLS_Format::translate_amenities(&$amenities[$amen_type]); ?>
	                      <?php foreach ($amenities[$amen_type] as $amenity => $value): ?>
	                        <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
	                      <?php endforeach ?>
	                    </ul>
	                </div>
				<?php 
				$val = ob_get_clean();
				break;
			  case 'compliance':
			  	ob_start();
			  	PLS_Listing_Helper::get_compliance(array('context' => 'listings', 
	  												     'agent_name' => self::$listing['rets']['aname'] , 
	  												     'office_name' => self::$listing['rets']['oname'], 
	  												     'office_phone' => PLS_Format::phone(self::$listing['contact']['phone'])));
			  	$val = ob_get_clean();
			  	break;
			default:
		}
		
		return $val;
	}


/*** Helper Functions ***/

	private static function get_active_snippet_body($shortcode)
	{
		// Get snippet ID currently associated with this shortcode...
		$option_key = ('pls_' . $shortcode);
		$snippet_name = get_option($option_key, self::$defaults[$shortcode][0]);

		// Determine if snippet is custom (in DB) or default (stored in flat-file)
		$snippet_DB_key = ('pls_' . $shortcode . '_' . $snippet_name);
		$type = ( get_option($snippet_DB_key) ? 'custom' : 'default' );

		$snippet_body = PL_Router::load_snippet($shortcode, $snippet_name, $type);
		return $snippet_body;
	}
}

?>