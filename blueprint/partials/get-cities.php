<?php 

class PLS_Partials_Get_Cities {

	
	
    /**
     * Returns the list of cities available wrapped in certain html element.
     * 
     * The defaults are as follows:
     *     'numberposts' - Default is 5. Total number of posts to retrieve.
     *     'offset' - Default is 0. See {@link WP_Query::query()} for more.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html with the list of cities.
     * @since 0.0.1
     */
	function init ($args = '') {
		
		  /** Define the default argument array. */
        $defaults = array(
            'wrapper_element' => 'option',
            'extra_attr' => array(),
        );

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

        /** Return nothing when no plugin and user is not admin. */
        if ( pls_has_plugin_error() )
            return NULL;

        /** Get the location list from the plugin. */
        $locations = PLS_Plugin_API::get_location_list();

        /** Sort the cities */
        sort( $locations->city );

        $return = '';
        if ( $wrapper_element == 'option' ) 
            $return = pls_h_options( $locations->city, false, true );
        else 
            foreach( $location->city as $city ) 
                $return .= pls_h(
                    $wrapper_element,
                    $extra_attr,
                    $city
                );
            

        return $return; 
	}
}