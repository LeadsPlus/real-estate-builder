<?php
/*
 * Minimalist HTML framework. 
 * Created by scribu in scbFramework
 *
 * Examples:
 *
 * pls_h( 'p', 'Hello world!' ); 
 * <p>Hello world!</p>
 *
 * pls_h( 'a', array( 'href' => 'http://example.com' ), 'A link' );
 * <a href="http://example.com">A link</a>
 *
 * pls_h( 'img', array( 'src' => 'http://example.com/f.jpg' ) );
 * <img src="http://example.com/f.jpg" />
 *
 * pls_h( 'ul', pls_h( 'li', 'a' ), pls_h( 'li', 'b' ) );
 * <ul><li>a</li><li>b</li></ul>
 *
 * @since 0.0.1
 */
function pls_h( $tag ) {

    $args = func_get_args();

    $tag = array_shift( $args );
    if ( !empty($args) && is_array( $args[0] ) ) {
        $closing = $tag;
        $attributes = array_shift( $args );
        foreach ( $attributes as $key => $value ) {
            if ( false === $value )
                continue;

            if ( true === $value )
                $value = $key;

            if(function_exists('filter_var')) {
                // For performance. If filter_var is available (PHP >= 5.2),
                // use that & iconv to deal with any special characters.
                $safe_value = iconv("UTF-8", "UTF-8//IGNORE", $value);
                $safe_value = filter_var($safe_value, FILTER_SANITIZE_SPECIAL_CHARS);
                $safe_value = apply_filters( 'attribute_escape', $safe_value, $value );
                $tag .= ' ' . $key . '="' . $safe_value . '"';
            }
            else {
                // Fallback to Scribu's original, slower approach, calling WP's esc_attr()
                $tag .= ' ' . $key . '="' . esc_attr( $value ) . '"';
            }
        }
    } else {
        list( $closing ) = explode( ' ', $tag, 2 );
    }

    static $SELF_CLOSING_TAGS = null;
    if($SELF_CLOSING_TAGS === null) {
        $SELF_CLOSING_TAGS = array_flip(array('area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta'));
    }

    if ( isset($SELF_CLOSING_TAGS[$closing]) ) {
        return "<{$tag} />";
    }

    $content = implode( '', $args );

    return "<{$tag}>{$content}</{$closing}>";
}

/**
 * Creates an anchor html tag with given parameters.
 * 
 * @param string $url The anchor href value.
 * @param string $title The anchor content.
 * @param array $extra_attr Optional. Array containing extra attributes. 
 *  Attributes must be provided in the array( "attr_name" => "attr_value" ) form.
 * @return string The anchor element.
 * @since 0.0.1
 */
function pls_h_a( $url, $title = false, $extra_attr = array(), $noesc = false ) {

    $title = empty( $title ) ? $url : $title;

    /** Call the escaping or non-escaping html function to generate the link. */
    return pls_h( 'a', array( 'href' => $url ) + $extra_attr, $title );
}

/**
 * Creates an img html tag with given parameters.
 * 
 * @param string $src The img src attribute value.
 * @param string $title Option. Defaults to src. The alt attribute vaue.
 * @param array $extra_attr Optional. Array containing extra attributes. 
 *  Attributes must be provided in the array( "attr_name" => "attr_value" ) form.
 * @return string The img element.
 * @since 0.0.1
 */
function pls_h_img( $src, $alt = false, $extra_attr = array() ) {

    $alt = empty( $alt ) ? $src : $alt;

    return pls_h( 'img', array( 'src' => $src, 'alt' => $alt ) + $extra_attr + array( 'title' => $alt ) );
}

/**
 * TODO
 * 
 * @param mixed $content 
 * @param array $extra_attr 
 * @access public
 * @return void
 */
function pls_h_p( $content, $extra_attr = array() ) {

    return pls_h( 'p', $extra_attr, $content );
}

function pls_h_span( $content, $extra_attr = array() ) {

    return pls_h( 'span', $extra_attr, $content );
}

/**
 * TODO
 * 
 * @param mixed $content 
 * @param array $extra_attr 
 * @access public
 * @return void
 */
function pls_h_div( $content, $extra_attr = array() ) {

    return pls_h( 'div', $extra_attr, $content );
}

/**
 * TODO
 * 
 * @param mixed $for 
 * @param array $extra_attr 
 * @access public
 * @return void
 */
function pls_h_label( $content, $for = '', $extra_attr = array() ) {

    return pls_h( 'label', array( 'for' => $for ) + $extra_attr, $content );
}

function pls_h_li( $content, $extra_attr = array() ) {

    return pls_h( 'li', $extra_attr, $content );
}

/**
 * TODO
 * 
 * @param mixed $for 
 * @param array $extra_attr 
 * @access public
 * @return void
 */
function pls_h_checkbox( $checked, $extra_attr = array() ) {

    $attributes = array(
        'class' => 'checkbox',
        'type' => 'checkbox',
    ) + $extra_attr;

    if ( $checked )
        $attributes['checked'] = true;

    return pls_h( 'input', $attributes );
}

/**
 * TODO
 * 
 * @param mixed $option_array 
 * @param mixed $selected_value 
 * @param mixed $clone_value 
 * @param array $extra_attr 
 * @access public
 * @return void
 */
function pls_h_options( $option_array, $selected_value = false, $clone_value = false, $extra_attr = array() ) {

    $return = '';

    foreach ( $option_array as $key => $value ) {

        if ( $key === 'pls_empty_value' ) 
            $option_value = "";
        elseif ( ! $clone_value )
            $option_value = $key;
        else 
            $option_value = $value;

        $option_label = $value;

        $attr = array();
        //allow the form library to accept arrays for multiselect items
        if ( $selected_value && is_array($selected_value) ) {
            foreach ($selected_value as $single_value) {
                if ($single_value == $option_value) {
                    $attr['selected'] = true;
                }
            }
        } elseif ( ( $selected_value ) && ( (string) $selected_value == (string) $option_value ) ) {
            $attr['selected'] = true;
        }

        $attr['value'] = $option_value;

        if ( isset( $extra_attr[$key] ) )
            $attr = $attr + $extra_attr[$key];

        if ( isset( $extra_attr['all'] ) )
            $attr = $attr + $extra_attr['all'];

        $return .= pls_h( 'option', $attr, $option_label );
    }
    return $return;
}
