<?php
/**
 *  Define wrapper functions for the PLS_Partials methods.
 *  This is so the theme developers won't get confused by the class syntax.
 *  See {@link PLS_Partials} for more details about each method.
 */
function pls_get_listings( $args = '' ) {
    return PLS_Partials::get_listings( $args );
}
function pls_get_cities( $args ) {
    return PLS_Partials::get_cities( $args );
}
function pls_get_listings_search_form( $args ) {
    return PLS_Partials::get_listings_search_form( $args );
}
function pls_get_listings_list_ajax( $args = '' ) { 
    return PLS_Partials::get_listings_list_ajax( $args );
}

/**
 * This class contains methods that retrurn plguin data wrapped in html. Each 
 * method implements filters that allow the theme developer to modify the 
 * returned data contextually.
 *
 * @package PlacesterBlueprint
 * @since 0.0.1
 */
 PLS_Partials::init();
class PLS_Partials {

    // links in all the hooks and includes. 
    function init () {
        // all the includes
        include_once( trailingslashit(PLS_PAR_DIR) . 'custom-property-details.php');
        include_once( trailingslashit(PLS_PAR_DIR) . 'get-cities.php');
        include_once( trailingslashit(PLS_PAR_DIR) . 'get-listings-ajax.php');
        include_once( trailingslashit(PLS_PAR_DIR) . 'get-listings-search-form.php');
        include_once( trailingslashit(PLS_PAR_DIR) . 'get-listings.php');

        // all the hooks, all partial files can be found in blueprint/partials/{file}.php
        add_filter('the_content', array( __CLASS__ ,'custom_property_details_html_filter'), 11);
    }

    // wrapper for calling get listings directly. 
    static function get_listings( $args = array() ) {
        return PLS_Partial_Get_Listings::init($args);
    }

    // wrapper for get cities api request
    static function get_cities( $args ) {
        if(WP_DEBUG !== true) {
            $cache = new PLS_Cache('Listings Search Form');
            if($form = $cache->get($args)) {
                return $form;
            }
        }
        $form = PLS_Partials_Listing_Search_Form::init($args);
        if(WP_DEBUG !== true) {
            $cache->save($form);
        }
        return $form;
      
    }
    
    // wrapper for listings search for content
    static function get_listings_search_form( $args ) {
        return PLS_Partials_Listing_Search_Form::init($args);
    }

    
    // wrapper for listings list ajax content
    static function get_listings_list_ajax( $args = '' ) {
        
        return PLS_Partials_Get_Listings_Ajax::load($args);       
    }

    // wrapper for property details page content
    static function custom_property_details_html_filter ($content) {
//        pls_dump($content);
        echo PLS_Partials_Property_Details::init($content);
    } 
}
