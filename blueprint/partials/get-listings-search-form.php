<?php

class PLS_Partials_Listing_Search_Form {

	/**
     * Returns a form that can be used to search for listings.
     * 
     * The defaults are as follows:
     *     'ajax' - Default is false. Wether the resulting form should use ajax 
     *          or not. If ajax is set to true, then for the form to work, the 
     *          results container should be defined on the page. 
     *          {@link PLS_Partials::get_listings_list_ajax()} should be used.
     *     'results_page_id' - Default is the id of the page with the name 
     *          'lisings'. The id of the page that will contain the 
     *          results. In play only if 'ajax' is set to false.
     *     'context' - An execution context for the function. Used when the 
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters 
     *          when function is executed.
     * Defines the following hooks.
     *      pls_listings_search_form_bedrooms_array[_context] - Filters the 
     *          array with the data used to generate the select.
     *      pls_listings_search_form_bathrooms_array[_context]
     *      pls_listings_search_form_available_on_array[_context]
     *      pls_listings_search_form_cities_array[_context]
     *      pls_listings_search_form_min_price_array[_context]
     *      pls_listings_search_form_max_price_array[_context]
     *      
     *      pls_listings_search_form_bedrooms_attributes[_context] - Filters 
     *          the attribute array for the select. If extra attributes need to 
     *          be added to the select element, they should be provided in 
     *          a array( $attribute_key => $attribute_value ) form.
     *      pls_listings_search_form_bathrooms_attributes[_context]
     *      pls_listings_search_form_available_on_attributes[_context]
     *      pls_listings_search_form_cities_attributes[_context]
     *      pls_listings_search_form_min_price_attributes[_context]
     *      pls_listings_search_form_max_price_attributes[_context]
     *      
     *      pls_listings_search_form_bedrooms_html[_context] - Filters the html 
     *          for this option. Can be used to add extra containers.
     *      pls_listings_search_form_bathrooms_html[_context]
     *      pls_listings_search_form_available_on_html[_context]
     *      pls_listings_search_form_cities_html[_context]
     *      pls_listings_search_form_min_price_html[_context]
     *      pls_listings_search_form_max_price_html[_context]
     *      
     *      pls_listings_search_form_submit[_context] - Filters the form submit 
     *          button.
     *
     *      pls_listings_search_form_inner[_context] - Filters the form inner html.
     *      pls_listings_search_form_outer[_context] - Filters the form html.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html for the listings search form.
     * @since 0.0.1
     */
	function init ($args = '') {

        /** Define the default argument array. */
        $defaults = array(
            'ajax' => false,
            'class' => 'pls_search_form_listings',
            'results_page_id' => PLS_Pages::get_page_by_name('listings', 'page-template-listings.php', true),
            'context' => '',
            'theme_option_id' => '',
            'context_var' => null,
            'bedrooms' => 1,
            'min_beds' => 1,
            'max_beds' => 1,
            'bathrooms' => 1,
            'min_baths' => 1,
            'max_baths' => 1,
            'price' => 1,
            'half_baths' => 1,
            'property_type' => 1,
            'listing_types'=> 1,
            'zoning_types' => 1,
            'purchase_types' => 1,
            'available_on' => 1,
            'cities' => 1,
            'states' => 1,
            'zips' => 1,
            'neighborhood' => 1,
            'county' => 1,
            'min_price' => 1,
            'max_price' => 1,
            'min_price_rental' => 1,
            'max_price_rental' => 1,
            'neighborhood_polygons' => 0,
            'neighborhood_polygons_type' => false,
            'include_submit' => true
        );

        $args = wp_parse_args( $args, $defaults );

        $cache_id = $args;
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
          $cache_id['$_POST'] = $_POST;
        }

        $cache = new PLS_Cache('Search Form');
        if ($result = $cache->get($cache_id)) {
          return $result;
        }

        $form_options = array();

        // Set Form Defaults for params onload, set in Theme Options
        $form_options['location']['locality'] = pls_get_option('form_default_options_locality');
        $form_options['location']['region'] = pls_get_option('form_default_options_region');
        $form_options['location']['postal'] = pls_get_option('form_default_options_postal');
        $form_options['location']['neighborhood'] = pls_get_option('form_default_options_neighborhood');
        $form_options['location']['county'] = pls_get_option('form_default_options_county');
        $form_options['property_type'] = pls_get_option('form_default_options_property_type');
        
        $_POST = wp_parse_args($_POST, $form_options);
               
        //respect user settings, unless they are all empty. 
        $user_search_params = pls_get_option($args['theme_option_id']);
        
        if (isset($user_search_params['hide_all']) && $user_search_params['hide_all'] == 1) {
          return '';
        }

        $args = wp_parse_args( $args, $user_search_params);    
        
        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        global $wp_rewrite;
        $search_page_id = $results_page_id;

        if ( $wp_rewrite->using_permalinks() ) {
            $form_data->action = get_permalink( $search_page_id );
            $form_data->hidden_field = '';             
        } else {
            $form_data->action = "index.php";
            $form_data->hidden_field = pls_h( 'input', array( 'type' => 'hidden', 'name' => 'page_id', 'value' => $search_page_id ) );
        }

        /**
         * Elements options arrays. Used to generate the HTML.
         */

        /** Prepend the default empty valued element. */
        $user_beds_start = pls_get_option('pls-option-bed-min');
        $user_beds_end = pls_get_option('pls-option-bed-max');
        if (is_numeric($user_beds_start) && is_numeric($user_beds_end) ) {
          $beds_range = range( $user_beds_start, $user_beds_end );
            $form_options['bedrooms'] = array( 'pls_empty_value' => 'Any' ) + array_combine( $beds_range, $beds_range );
        } else {
            $form_options['bedrooms'] = array( 'pls_empty_value' => 'Any' ) + range( 0, 16 );    
        }

        /** Prepend the default empty valued element. */
        $user_baths_start = pls_get_option('pls-option-bath-min');
        $user_baths_end = pls_get_option('pls-option-bath-max');
        if (is_numeric($user_baths_start) && is_numeric($user_baths_end) ) {
          $baths_range =  range( $user_baths_start, $user_baths_end );
            $form_options['bathrooms'] = array( 'pls_empty_value' => 'Any' ) + array_combine( $baths_range, $baths_range );
        } else {
            $form_options['bathrooms'] = array( 'pls_empty_value' => 'Any' ) + range( 0, 10 );
        }

        /** Prepend the default empty valued element. */
        $user_half_baths_start = pls_get_option('pls-option-half-bath-min');
        $user_half_baths_end = pls_get_option('pls-option-half-bath-max');
        if (is_numeric($user_half_baths_start) && is_numeric($user_half_baths_end) ) {
          $half_bath_range = range( $user_half_baths_start, $user_half_baths_end );
            $form_options['half_baths'] = array( 'pls_empty_value' => 'Any' ) + array_combine( $half_bath_range, $half_bath_range );
        } else {
            $form_options['half_baths'] = array( 'pls_empty_value' => 'Any' ) + range( 0, 10 );
        }
        

        /** Generate an array with the next 12 months. */
        $current_month = (int) date('m');
        for ( $i = $current_month; $i < $current_month + 12; $i++ ) {
            $form_options['available_on'][date( 'd-m-Y', mktime( 0, 0, 0, $i, 1 ) )] = date( 'F Y', mktime( 0, 0, 0, $i, 1 ) );
        }

        /** Get the property type options. */
        $form_options['property_type'] = array( 'pls_empty_value' => 'Any' ) + PLS_Plugin_API::get_type_values( 'property' );

        /** Get the listing type options. */
        $form_options['listing_types'] = array( 'pls_empty_value' => 'All' ) + PLS_Plugin_API::get_type_values( 'listing' );

        /** Get the zoning type options. */
        $form_options['zoning_types'] = array( 'pls_empty_value' => 'All') + PLS_Plugin_API::get_type_values( 'zoning' );
				// removed "All" - it's not giving all listings. jquery needs to change to not include "[]"s
        // $form_options['zoning_types'] = PLS_Plugin_API::get_type_values( 'zoning' ); // for Multiple, not for single, see below

        /** Get the purchase type options. */
        $form_options['purchase_types'] = array( 'pls_empty_value' => 'All' ) + PLS_Plugin_API::get_type_values( 'purchase' );

				// removed "All" - it's not giving all listings. jquery needs to change to not include "[]"s
				// $form_options['purchase_types'] = PLS_Plugin_API::get_type_values( 'purchase' );
				
        /** Prepend the default empty valued element. */
        $form_options['available_on'] = array( 'pls_empty_value' => 'Anytime' ) + $form_options['available_on'];

        /** Prepend the default empty valued element. */
        
        $locations = PLS_Plugin_API::get_location_list();
        $neighborhood_polygons_options = PLS_Plugin_API::get_location_list_polygons($neighborhood_polygons_type);

        if (empty($locations['locality'])) {
            $form_options['cities'] = array('pls_empty_value' => 'Any');
        } else {
            unset($locations['locality']['false']);
            sort($locations['locality']);
            $form_options['cities'] = array('pls_empty_value' => 'Any') + $locations['locality'];
        }

        if (empty($locations['region'])) {
            $form_options['states'] = array('pls_empty_value' => 'Any');
        } else {
            unset($locations['region']['false']);
            sort($locations['region']);
            $form_options['states'] = array('pls_empty_value' => 'Any') + $locations['region'];
            
        }

        if (empty($locations['postal'])) {
            $form_options['zips'] = array('pls_empty_value' => 'Any'); 
        } else {
            unset($locations['postal']['false']);
            sort($locations['postal']);
            $form_options['zips'] = array('pls_empty_value' => 'Any') + $locations['postal'];
        }

        if (empty($locations['neighborhood'])) {
            $form_options['neighborhood'] = array('pls_empty_value' => 'Any'); 
        } else {
            unset($locations['neighborhood']['false']);
            sort($locations['neighborhood']);
            $form_options['neighborhood'] = array('pls_empty_value' => 'Any') + $locations['neighborhood'];
        }
        
        if (empty($locations['county'])) {
            $form_options['county'] = array('pls_empty_value' => 'Any'); 
        } else {
            unset($locations['county']['false']);
            sort($locations['county']);
            $form_options['county'] = array('pls_empty_value' => 'Any') + $locations['county'];
        }

        if (empty($neighborhood_polygons_options)) {
            $form_options['neighborhood_polygons'] = array('pls_empty_value' => 'Any');  
        } else {
            unset($neighborhood_polygons_options['false']);
            sort($neighborhood_polygons_options);
            $form_options['neighborhood_polygons'] = array('pls_empty_value' => 'Any') + $neighborhood_polygons_options;
        }

        

        
    // Price for Sales
        /** Define the minimum price options array. */
        $form_options['min_price'] = array(
              'pls_empty_value' => __( 'Any', pls_get_textdomain() ),
              '0' => '$0',
              '$400' => '$400',
              '500' => '$5,000',
              '2000' => '$2,000',
              '3000' => '$3,000',
              '4000' => '$4,000',
              '5000' => '$5,000',
              '50000' => '$50,000',
              '100000' => '$100,000',
              '200000' => '$200,000',
              '350000' => '$350,000',
              '400000' => '$400,000',
              '450000' => '$450,000',
              '500000' => '$500,000',
              '600000' => '$600,000',
              '700000' => '$700,000',
              '800000' => '$800,000',
              '900000' => '$900,000',
              '1000000' => '$1,000,000',
        );

          $user_price_start = pls_get_option('pls-option-price-min');
          $user_price_end = pls_get_option('pls-option-price-max');
          $user_price_inc = pls_get_option('pls-option-price-inc');

          if (is_numeric($user_price_start) && is_numeric($user_price_end) && is_numeric($user_price_inc)) {
              $range = range($user_price_start, $user_price_end, $user_price_inc);    
              $form_options['min_price'] = array();
              foreach ($range as $price_value) {
                  $form_options['min_price'][$price_value] = PLS_Format::number($price_value, array('abbreviate' => false));
              }
          }   

          /** Set the maximum price options array. */
          $form_options['max_price'] = $form_options['min_price'];


      // Price for Rentals 
          /** Define the minimum price options array. */
          $form_options['min_price_rental'] = array(
                'pls_empty_value' => __( 'Any', pls_get_textdomain() ),
                '200' => '$200',
                '400' => '$400',
                '600' => '$600',
                '800' => '$800',
                '1000' => '$1,000',
                '1100' => '$1,100',
                '1200' => '$1,200',
                '1300' => '$1,300',
                '1400' => '$1,400',
                '1500' => '$1,500',
                '1600' => '$1,600',
                '1700' => '$1,700',
                '1800' => '$1,800',
                '1900' => '$1,900',
                '2000' => '$2,000',
                '2100' => '$2,100',
                '2200' => '$2,200',
                '2300' => '$2,300',
                '2400' => '$2,400',
                '2500' => '$2,500',
                '2600' => '$2,600',
                '2700' => '$2,700',
                '2800' => '$2,800',
                '2900' => '$2,900',
                '3000' => '$3,000',
                '3500' => '$3,500',
                '4000' => '$4,000',
                '4500' => '$4,500',
                '5000' => '$5,000',
                '6000' => '$6,000',
                '7000' => '$7,000',
                '8000' => '$8,000',
          );

            $user_price_start_rental = '';
            $user_price_end_rental = '';
            $user_price_inc_rental = '';

            if (is_numeric($user_price_start_rental) && is_numeric($user_price_end_rental) && is_numeric($user_price_inc_rental)) {
                $range = range($user_price_start_rental, $user_price_end_rental, $user_price_inc_rental);    
                $form_options['min_price_rental'] = array();
                foreach ($range as $price_value) {
                    $form_options['min_price_rental'][$price_value] = PLS_Format::number($price_value, array('abbreviate' => false));
                }
            }   

            /** Set the maximum price options array. */
            $form_options['max_price_rental'] = $form_options['min_price_rental'];

      $form_options['min_beds'] = array(
            'pls_empty_value' => __( 'Any', pls_get_textdomain() ),
            '0' => '0',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
      );

      $form_options['min_baths'] = array(
            'pls_empty_value' => __( 'Any', pls_get_textdomain() ),
            '0' => '0',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10'
      );

        $user_bed_start = pls_get_option('pls-option-bed-min');
        $user_bed_end = pls_get_option('pls-option-bed-max');
        $user_bed_inc = pls_get_option('pls-option-bed-inc');

        if (is_numeric($user_bed_start) && is_numeric($user_bed_end) && is_numeric($user_bed_inc)) {
            $range = range($user_bed_start, $user_bed_end, $user_bed_inc);
            $form_options['min_price'] = array();
            foreach ($range as $bed_value) {
                $form_options['min_beds'][$bed_value] = $bed_value;
            }
        }

        /** Set the max beds/baths array. */
        $form_options['max_beds'] = $form_options['min_beds'];
        $form_options['max_baths'] = $form_options['min_baths'];

        /** Define an array for extra attributes. */
        $form_opt_attr = array();

        /** Filter form fields. */
        foreach( $form_options as $option_name => &$opt_array ) {

            /** Filter each of the fields options arrays. */
            $opt_array = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_array", $context ), '_', 'pre', false ), $opt_array, $context_var );

            /** Form options array. */
            $form_opt_attr[$option_name] = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_attributes", $context ), '_', 'pre', false ), array(), $context_var );

            /** Make sure it is an array. */
            if ( ! is_array( $form_opt_attr[$option_name] ) ) {
                $form_opt_attr[$option_name] = array();    
            }          

            /** Append the data-placeholder attribute. */
            if ( isset( $opt_array['pls_empty_value'] ) ) {
                $form_opt_attr[$option_name] = $form_opt_attr[$option_name] + array( 'data-placeholder' => $opt_array['pls_empty_value'] );
            }
        }

        if (!isset($_POST['metadata'])) {
          $_POST['metadata'] = array();
        }

        /**
         * Elements HTML.
         */
        /** Add the bedrooms select element. */
        if ($bedrooms == 1) {
            $form_html['bedrooms'] = pls_h( 
                'select',
                array( 'name' => 'metadata[beds]') + $form_opt_attr['bedrooms'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['bedrooms'], wp_kses_post(@$_POST['metadata']['beds'])  )
                );
        }
        
        
        /** Add the bedrooms select element. */
        if ($min_beds == 1) {
            $form_html['min_beds'] = pls_h( 
                'select',
                array( 'name' => 'metadata[min_beds]') + $form_opt_attr['min_beds'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['min_beds'], @$_POST['metadata']['min_beds']  )
                );
        }
        
        /** Add the bedrooms select element. */
        if ($max_beds == 1) {
            $form_html['max_beds'] = pls_h( 
                'select',
                array( 'name' => 'metadata[max_beds]') + $form_opt_attr['max_beds'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['max_beds'], @$_POST['metadata']['max_beds']  )
                );
        }

        /** Add the bathroms select element. */
        if ($bathrooms == 1) {
            $form_html['bathrooms'] = pls_h( 
                'select',
                array( 'name' => 'metadata[baths]' ) + $form_opt_attr['bathrooms'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['bathrooms'], wp_kses_post(@$_POST['metadata']['baths'] ) )
            );            
        }

        /** Add the min baths select element. */
        if ($min_baths == 1) {
            $form_html['min_baths'] = pls_h( 
                'select',
                array( 'name' => 'metadata[min_baths]') + $form_opt_attr['min_baths'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['min_baths'], @$_POST['metadata']['min_baths']  )
                );
        }

        /** Add the max baths select element. */
        if ($max_baths == 1) {
            $form_html['max_baths'] = pls_h( 
                'select',
                array( 'name' => 'metadata[max_baths]') + $form_opt_attr['max_baths'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['max_baths'], @$_POST['metadata']['max_baths']  )
                );
        }

        /** Add the bathroms select element. */
        if ($half_baths == 1) {
            $form_html['half_baths'] = pls_h( 
                'select',
                array( 'name' => 'metadata[half_baths]' ) + $form_opt_attr['half_baths'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['half_baths'], wp_kses_post( @$_POST['metadata']['half_baths'] ) )
            );
        }
        

        /** Add the property type select element. */
        if ($property_type == 1) {
            $form_html['property_type'] = pls_h(
                'select',
                array( 'name' => 'property_type' ) + $form_opt_attr['property_type'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['property_type'], wp_kses_post( @$_POST['property_type'] ) )
            );
        }

        /** Add the listing type select element. */
        if ($listing_types == 1) {
            $form_html['listing_types'] = pls_h(
                'select',
                array( 'name' => 'listing_types') + $form_opt_attr['listing_types'],
                pls_h_options( $form_options['listing_types'], wp_kses_post( @$_POST['listing_types'] ) )
            );
        }
        
        /** Add the zoning type select element. */
        if ($zoning_types == 1) {
            $form_html['zoning_types'] = pls_h(
                'select',
                array( 'name' => 'zoning_types[]'  ) + $form_opt_attr['zoning_types'],
                pls_h_options( $form_options['zoning_types'], wp_kses_post( @$_POST['zoning_types'] ) )
            );
        }

        /** Add the purchase type select element. */
        if ($purchase_types == 1) {
          $default_purchase_types = @pls_get_option('default_form_options_purchase_types');
          // Set Default
          if ( empty($_POST['purchase_types']) ) {
            $purchase_types_select = array($default_purchase_types);
          } else {
            $purchase_types_select = wp_kses_post(@$_POST['purchase_types']);
          }
          
            $form_html['purchase_types'] = pls_h(
                'select',
                array( 'name' => 'purchase_types[]' ) + $form_opt_attr['purchase_types'],
                pls_h_options( $form_options['purchase_types'], $purchase_types_select )
            );
        }
        
        /** Add the availability select element. */
        if ($available_on == 1) {
            $form_html['available_on'] = pls_h(
                'select',
                array( 'name' => 'metadata[avail_on]' ) + $form_opt_attr['available_on'],
                pls_h_options( $form_options['available_on'], wp_kses_post( @$_POST['metadata']['avail_on'] ) )
            );
        }
                                   
        /** Add the cities select element. */
        if ($cities == 1) {
            $form_html['cities'] = pls_h(
                'select',
                array( 'name' => 'location[locality]' ) + $form_opt_attr['cities'],
                pls_h_options( $form_options['cities'], wp_kses_post( @$_POST['location']['locality'] ), true )
            );
        }
        
        /** Add the cities select element. */
        if ($states == 1) {
                $form_html['states'] = pls_h(
                'select',
                array( 'name' => 'location[region]' ) + $form_opt_attr['states'],
                pls_h_options( $form_options['states'], wp_kses_post(@$_POST['location']['region'] ), true )
            );
        }

        /** Add the cities select element. */
        if ($zips == 1) {
            $form_html['zips'] = pls_h(
                'select',
                array( 'name' => 'location[postal]' ) + $form_opt_attr['zips'],
                pls_h_options( $form_options['zips'], wp_kses_post(@$_POST['location']['postal'] ), true )
            );
        }

        /** Add the neighborhood select element. */
        if ($neighborhood == 1) {
            $form_html['neighborhood'] = pls_h(
                'select',
                array( 'name' => 'location[neighborhood]' ) + $form_opt_attr['neighborhood'],
                pls_h_options( $form_options['neighborhood'], wp_kses_post(@$_POST['location']['neighborhood'] ), true )
            );
        }

        /** Add the county select element. */
        if ($county == 1) {
            $form_html['county'] = pls_h(
                'select',
                array( 'name' => 'location[county]' ) + $form_opt_attr['county'],
                pls_h_options( $form_options['county'], wp_kses_post(@$_POST['location']['county'] ), true )
            );
        }

        /** Add the county select element. */
        if ($neighborhood_polygons == 1) {
            $form_html['neighborhood_polygons'] = pls_h(
                'select',
                array( 'name' => 'neighborhood_polygons' ) + $form_opt_attr['neighborhood_polygons'],
                pls_h_options( $form_options['neighborhood_polygons'], wp_kses_post(@$_POST['neighborhood_polygons'] ), true )
            );
        }

        /** Add the minimum price select element. */
        if ($min_price == 1) {
            $form_html['min_price'] = pls_h(
                'select',
                array( 'name' => 'metadata[min_price]' ) + $form_opt_attr['min_price'],
                pls_h_options( $form_options['min_price'], wp_kses_post(@$_POST['metadata']['min_price'] ) )
            );
        }
        
        /** Add the maximum price select element. */
        if ($max_price == 1) {
            $form_html['max_price'] = pls_h(
                'select',
                array( 'name' => 'metadata[max_price]' ) + $form_opt_attr['max_price'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['max_price'], wp_kses_post(@$_POST['metadata']['max_price'] ) )
            );
        }

        /** Add the minimum price select element. */
        if ($min_price_rental == 1) {
            $form_html['min_price_rental'] = pls_h(
                'select',
                array( 'name' => 'metadata[min_price]' ) + $form_opt_attr['min_price'],
                pls_h_options( $form_options['min_price_rental'], @$_POST['metadata']['min_price'] )
            );
        }
        
        /** Add the maximum price select element. */
        if ($max_price_rental == 1) {
            $form_html['max_price_rental'] = pls_h(
                'select',
                array( 'name' => 'metadata[max_price]' ) + $form_opt_attr['max_price'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['max_price_rental'], @$_POST['metadata']['max_price'] )
            );
        }

        $section_title = array(
            'bedrooms' => 'Beds',
            'min_beds' => 'Min Beds',
            'max_beds' => 'Max Beds',
            'bathrooms' => 'Baths',
            'min_baths' => 'Min Baths',
            'max_baths' => 'Max Baths',
            'half_baths' => 'Half Baths',
            'property_type' => 'Property Type',
            'zoning_types' => 'Zoning Type',
            'listing_types' => 'Listing Type',
            'purchase_types' => 'Purchase Type',
            'available_on' => 'Available',
            'cities' => 'Near',
            'states' => 'State',
            'zips' => 'Zip Code',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'neighborhood' => 'Neighborhood',
            'county' => 'County',
            'min_beds' => 'Min Beds',
            'max_beds' => 'Max Beds',
            'min_price_rental' => 'Min Price Rental',
            'max_price_rental' => 'Max Price Rental',
            'neighborhood_polygons' => 'Neighborhood Polygon',
        );

        // In case user somehow disables all filters.
        if (empty($form_html)) {
            return '';
        }

        /** Apply filters on all the form elements html. */
        foreach( $form_html as $option_name => &$opt_html ) {

            $opt_html = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_html", $context ), '_', 'pre', false )
, $opt_html, $form_options[$option_name], $section_title[$option_name], $context_var );
        }

        /** Combine the form elements. */
        $form = '';
        
        foreach ( $form_html as $label => $select ) {
            $form .= pls_h(
                'section',
                array( 'class' => $label . ' pls_search_form' ),
                pls_h_label( $section_title[$label], $label ) .
                $select
            );
        }

        /** Add the filtered submit button. */
        if ($include_submit) {
            $form_html['submit'] = apply_filters( 
                pls_get_merged_strings( array( "pls_listings_search_submit", $context ), '_', 'pre', false ), 
                pls_h( 'input', array('class' => 'pls_search_button', 'type' => 'submit', 'value' => 'Search' ) ),  
                $context_var
            );
            /** Append the form submit. */
            $form .= $form_html['submit'];
        }
        

        

        /** Wrap the combined form content in the form element and filter it. */
        $form_id = pls_get_merged_strings( array( 'pls-listings-search-form', $context ), '-', 'pre', false );

        $form = pls_h(
            'form',
            array( 'action' => $form_data->action, 'method' => 'post', 'id' => $form_id, 'class' => $class ),
            $form_data->hidden_field . apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_inner", $context ), '_', 'pre', false ), $form, $form_html, $form_options, $section_title, $context_var )
        );

        /** Filter the form. */
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_outer", $context ), '_', 'pre', false ), $form, $form_html, $form_options, $section_title, $form_data, $form_id, $context_var );
            
        $cache->save($return);
        return $return;

	}
}