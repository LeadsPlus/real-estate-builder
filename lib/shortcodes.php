<?php

/****** 
	I need to figure out how to hook this into the WP execution framework properly
	so the rest of the plugin & WP can actually utilize these... 
******/

PL_Shortcodes::init();
class PL_Shortcodes
{
	public static $codes = array('searchform', 'listings', 'prop_details');

	public static $p_codes = array('searchform' => 'Search Form', 'prop_details' => 'Property Details', 'listings' => 'Listings');
	
	public static $defaults = array('searchform' 	=> array('ventura', 'columbus', 'highland'),
	  				                'prop_details' 	=> array('Red', 'Yellow', 'Orange'),
			               			'listings' 		=> array('Purple', 'Pink', 'Gray') );

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
            						'listings'	  =>  array('price',
            												'sqft',
            												'beds',
            												'baths',
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
            												'phone'	),
            						'prop_details'	  =>  array('price',
            												'sqft',
            												'beds',
            												'baths',
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
            												'phone'	)
            						);

	// TODO: These are a temporary solution, come up with a better convention...
	public static $form_html = false;
	public static $listing = false;

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
	}


/*** Shortcode Handlers ***/	
	
	public static function searchform_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		// Ajax setting as an attr?

		return PLS_Partials_Listing_Search_Form::init(array('context' => 'shortcode', 'ajax' => true));
	}

	public static function listings_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		// These attributes will hand the look and feel of the listing form container, as 
		// the context func applies to each individual listing.
	
		return PLS_Partials_Get_Listings_Ajax::load(array('context' => 'shortcode'));
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
		$shortcode = 'prop_details';
		self::$listing = $listing_data;

	  	$snippet_body = self::get_active_snippet_body($shortcode);
	  	return do_shortcode($snippet_body);
	}


/*** Sub-Shortcode Handlers ***/

	public static function searchform_sub_shortcode_handler ($atts, $content, $tag) 
	{
		//pls_dump($tag);
		return self::$form_html[$tag];
	}

	public static function listings_sub_shortcode_handler ($atts, $content, $tag) 
	{
		$val = '';

		if (array_key_exists($tag, self::$listing['cur_data'])) { 
			$val = self::$listing['cur_data'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['location'])) { 
			$val = self::$listing['location'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['contact'])) { 
			$val = self::$listing['contact'][$tag]; 
		}
		else {
			$val = 'Could not handle...';
		}

		return $val;
	}

	public static function prop_details_sub_shortcode_handler ($atts, $content, $tag)
	{
		$val = '';

		if (array_key_exists($tag, self::$listing['cur_data'])) { 
			$val = self::$listing['cur_data'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['location'])) { 
			$val = self::$listing['location'][$tag]; 
		}else if (array_key_exists($tag, self::$listing['contact'])) { 
			$val = self::$listing['contact'][$tag]; 
		}
		else {
			$val = 'Could not handle...';
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