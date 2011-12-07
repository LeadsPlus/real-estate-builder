<?php
/*
* Slightly modifed version of the Options Framework by Devin Price
*/

/* Text */
add_filter( 'pl_of_sanitize_text', 'sanitize_text_field' );

/* Textarea */
add_filter( 'pl_of_sanitize_textarea', 'pl_of_sanitize_textarea' );
function pl_of_sanitize_textarea($input) {
	global $allowedtags;
	$output = wp_kses( $input, $allowedtags );
	return $output;
}

/* Select */
add_filter( 'pl_of_sanitize_select', 'pl_of_sanitize_enum', 10, 2);

/* Radio */
add_filter( 'pl_of_sanitize_radio', 'pl_of_sanitize_enum', 10, 2);

/* Check that the key value sent is valid */
function pl_of_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

/* Checkbox */
add_filter( 'pl_of_sanitize_checkbox', 'pl_of_sanitize_checkbox' );
function pl_of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}

/* Multicheck */
add_filter( 'pl_of_sanitize_multicheck', 'pl_of_sanitize_multicheck', 10, 2 );
function pl_of_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1"; 
			}
		}
	}
	return $output;
}

/* Color Picker */
/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */
add_filter( 'pl_of_sanitize_color', 'pl_of_sanitize_hex' );
function pl_of_sanitize_hex( $hex, $default = '' ) {
    $valid_hex =  pl_of_validate_hex( $hex );
	if ( $valid_hex ) {
        $hex = $valid_hex === 3 ? $hex . $hex : $hex;
		return '#' . $hex;
	}
	return $default;
}

/**
 * Checks if a given color string is in the correct hex format
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */
function pl_of_validate_hex( $hex ) {
	$hex = trim( $hex );
	// Strip recognized prefixes.
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
    // If '#' is url encoded
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	// Regex match.
    if ( preg_match( '/^[0-9a-fA-F]{3}$/', $hex ) ) { 
		return 3;
    }
	else if ( preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return 6;
	}
	else {
		return false;
	}
}
