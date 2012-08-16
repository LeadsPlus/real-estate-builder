<?php
/**
 * Finds out if an item (script or style) has been registered.
 *
 * @param string $handle The name under which the item is registered.
 * @param string $type Type of the item verified (script of style).
 * @return bool TRUE if the handler is enqueued, FALSE otherwise.
 */
function pls_is_registered( $handler, $type ) {

    $supported_types = array( 'style', 'script' );

    if ( !in_array( $type, $supported_types) )
        return;
                
    return isset( $GLOBALS['wp_' . $type . 's']->registered[$handler] );
}

/**
 * Verifies if a value is positive integer.
 * 
 * @param mixed $var What needs to be tested.
 * @return mixed FALSE if the value is not a positive integer, or the integer 
 * otherwise
 */
function pls_is_positive_int( $var ) {

    return filter_var( $var, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
}

/**
 * Merges a collection of non-empty strings and adds a sepparator between them 
 * at the desired position.
 * 
 * @param array $strings The array containing the string.
 * @param string $sepparator Optional. Default ''. What should sepparate the strings.
 * @param string $position Optional. Default 'pre'. Where should the sepparator be added. 
 *  Accepted values are 'pre' for prefix and 'post' for suffix.
 * @param bool $force Optional. Default true. Wether the sepparator should be added at the 
 *  beginning (when $position = 'pre'), or at the end (when $position = 'post') of the 
 *  merged strings.
 * @return string The merged string.
 */
function pls_get_merged_strings( $strings, $sepparator = '', $position = 'pre', $force = true )  {

    if ( ! is_array( $strings ) )
        return (string) $strings;

    $return = '';

    $count = count( $strings );

    foreach ( $strings as $key => $string ) {

        if ( !empty( $string ) ) {

            /** Add prefix if not the first element.  */
            $return .= ( $key || $force ) && ( $position == 'pre' ) ? $sepparator : '';

            /** Add the string.  */
            $return .= (string) $string;

            /** Add suffix if not the last string.  */
            $return .= ( $key + 1 < $count || $force ) && ( $position == 'post' ) ? $sepparator : '';
        }
    }

    return $return;
}

/**
 * Returns a variable only if it isn't empty
 * 
 * @param mixed $var The variable tested
 * @param mixed $otherwise Optional. Defaults to empty string. What to output 
 * if the tested variable is empty.
 * @return mixed $var or $otherwise
 * @since 0.0.1
 */
function pls_get_if_not_empty( $var, $otherwise = '' ) {

    return ! empty( $var ) ? $var : $otherwise;
}

/**
 * Crops a text while preserving the html.
 *
 * @param string $s The input string. Must be one character or longer.
 * @param integer $start Start of the crop.
 * @param integer $length Length of the crop.
 * @param mixed	$strict If this is defined, then the last word will be complete. If this is set to 2 then the last sentence will be completed.
 * @param string $suffix A string to suffix the value, only if it has been chopped.
 *
 * @return string The cropped string.
 *
 * @link http://perplexed.co.uk/290_html_substr_crop_html_text.htm
 */
function pls_html_substr( $s, $start, $length = NULL, $strict = false, $suffix = NULL ) {

    if ( is_null( $length ) )
        $length = strlen( $s ); 

    /** Function body that crops the text. */
    $f = 'static $startlen = 0; 
        if ( $startlen >= ' . $length . ' ) return "><"; 
        $html_str = html_entity_decode( $a[1] );
        $subsrt = max( 0, ( ' . $start . ' - $startlen ) );
        $sublen = ' . ( empty( $strict ) ? '( ' . $length . ' - $startlen )' : 'max( @strpos( $html_str, "' . ( $strict == 2 ? '.' : ' ' ) . '", (' . $length . ' - $startlen + $subsrt - 1 ) ), ' . $length . ' - $startlen )' ) . ';
        $new_str = substr( $html_str, $subsrt, $sublen ); 
        $startlen += $new_str_len = strlen( $new_str );
        $suffix = ' . ( !empty( $suffix ) ? '( $new_str_len === $sublen ? "' . $suffix . '" : "" )' : ' "" ' ) . ';
        return ">" . htmlentities( $new_str, ENT_QUOTES, "UTF-8" ) . "$suffix<";';

    return preg_replace( 
        array( "#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), 
        "", 
        trim( 
            rtrim( 
                ltrim( 
                    preg_replace_callback( 
                        "#>([^<]+)<#", 
                        create_function( '$a', $f ), 
                        ">$s<" 
                    ), 
                    ">" ), 
                "<" ) 
            )
        );
}

// Takes an object or array of unknown depth and makes a list
function pls_quick_list ($unknown_object, $recursive = false, $skip_html_keys = true, $included_html = '') {
	$html_list = '';

	foreach ($unknown_object as $key => $value) {
		if ( !is_array($value) && !is_object($value) ) {
			if ($skip_html_keys && $key !== 'html') {
				$html_list .= '<li><strong>' . $key . '</strong>: ' . $value . '</li>';
			}
		} elseif ($recursive) {
			$html_list .= pls_quick_list($value, $recursive, $skip_html_keys, $html_list);
		}
	}

	return $html_list;
}