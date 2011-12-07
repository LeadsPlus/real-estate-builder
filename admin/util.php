<?php
/*
 * Minimalist HTML framework. 
 * Created by scribu in scbFramework
 *
 * Examples:
 *
 * placester_html( 'p', 'Hello world!' );												<p>Hello world!</p>
 * placester_html( 'a', array( 'href' => 'http://example.com' ), 'A link' );			<a href="http://example.com">A link</a>
 * placester_html( 'img', array( 'src' => 'http://example.com/f.jpg' ) );				<img src="http://example.com/f.jpg" />
 * placester_html( 'ul', placester_html( 'li', 'a' ), placester_html( 'li', 'b' ) );						<ul><li>a</li><li>b</li></ul>
 */
if ( !function_exists( 'placester_html' ) ):
function placester_html( $tag ) {
	$args = func_get_args();
                   
	$tag = array_shift( $args );
    //@TODO suggest modification
	if ( !empty($args) && is_array( $args[0] ) ) {
		$closing = $tag;
		$attributes = array_shift( $args );
		foreach ( $attributes as $key => $value ) {
			if ( false === $value )
				continue;

			if ( true === $value )
				$value = $key;

			$tag .= ' ' . $key . '="' . esc_attr( $value ) . '"';
		}
	} else {
		list( $closing ) = explode( ' ', $tag, 2 );
	}

	if ( in_array( $closing, array( 'area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta' ) ) ) {
		return "<{$tag} />";
	}

	$content = implode( '', $args );

	return "<{$tag}>{$content}</{$closing}>";
}
endif;

/**
 * Gets a wordpress style alert div of 
 * the desired style, and with the desired 
 * functionality
 * 
 * @param mixed $message 
 * @param string $style 
 * @param string $type 
 * @param mixed $echo 
 * @access public
 * @return void
 */
function placester_get_wp_alert_div( $message, $style = 'notice', $type = '', $extra_attr = array(), $echo = false ) {
    $style_class = '';
    switch( $style ) {
        case 'notice': 
            $style_class = 'updated';
            break;
        case 'error': 
            $style_class = 'error';
            break;
        default: 
            $style_class = '';
    }

    switch( $type ) {
        case 'close': 
            $message .= '<a href="" class="close_btn">Close</a>';
            break;
        case 'expire': 
            break;
    }

    $default_attr = array(
        'class' => 'pl_ui alert ' . $style_class
    );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    $result = placester_html(
        'div',
        $attr,
        $message
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}


/**
 * Merge user defined atrributes into defaults array 
 * by concatenating the values
 *
 * @param array $default_args Array that serves as the defaults.
 * @param array $default_args Value to merge with $defaults
 *
 * @return array Merged user defined values with defaults.
 *
 * e.g. placester_merge_array_args( 
 *          array( 'class' => 'red' ), 
 *          array( 'class' => 'blue', 'id' => 'element' )
 *      );
 *      would be 
 *      array( 'class' => 'red blue', 'id' => 'element' );
 */
function placester_merge_array_args( $default_args, $args ) {
    $default_args_array = array_keys( $default_args );

    // Add extra argsibutes if the case applies 
    foreach ( $args as $key => $args ) {
        if ( in_array( $key, $default_args_array ) ) {
            $default_args[$key] .= ' ' . $args;
        } else {
            $default_args[$key] = $args;
        }
    }
    return $default_args;
}


/** -------------------------------------------------------------
 *  Helper functions that generate Wordpress admin UI elements
 *  -------------------------------------------------------------*/

/**
 * 
 * UI elements
 *
 * Input should be in the following form:
 *  $data = array (
 *      'columns' => array (
 *          0 => array( 'Column 1', 'width: 5%' ),
 *          1 => 'Column 2',
 *          2 => 'Column 3' 
 *      ),
 *      0 => array (
 *          0 => 'val1',
 *          1 => array('val 2', array( 'Edit' => 'http://example.com/edit/' ),
 *          2 => 'val3' 
 *      ),
 *      1 => array (
 *          0 => 'val1',
 *          1 => array('val 2', array( 'Edit' => 'http://example.com/edit/' ),
 *          2 => 'val3' 
 *      ),
 *  );
 */
// TODO extend row actions to include customizable class
function placester_get_widefat_table( $data, $extra_attr = array(), $echo = false ) {

    // Create the th elements for the table header and footer
    $columns = array_shift($data);
    $ths = '';
    foreach ( $columns as $column ) {
        if ( is_array( $column ) ) {
            $ths .= placester_html( 'th', array( 'style' => $column[1] ), $column[0] );
        } else {
            $ths .= placester_html( 'th', $column );
        }
    }

    // Create the tbody rows
    $tbody = '';
    foreach ( $data as $index => $row ) {
        $tr = '';
        $empty_tds = ( count( $columns ) - count( $row ) > 0 ) ? count( $columns ) - count( $row ) : 0; 
        foreach ( $row as $cell ) {
            // If row actions are defined
            if ( is_array( $cell ) ) {
                $row_actions = '';
                $last_key = end(array_keys($cell[1]));
                foreach ( $cell[1] as $label => $url ) {
                    $sepparator = ($label == $last_key) ? ' ' : ' | ';
                    $row_actions .= placester_html( 
                        'span', 
                        placester_html(
                            'a', 
                            array( 'href' => $url, 'class' =>  strtolower( str_replace( ' ', '_', $label ) ) ),
                            $label
                        ), 
                        $sepparator 
                    );
                }
                // Append Row Actions
                $tr .= placester_html( 
                    'td', 
                    $cell[0], 
                    placester_html( 
                        'div', 
                        array( 'class' => 'row-actions' ), 
                        $row_actions 
                    ) 
                );
            } else {
                $tr .= placester_html( 'td', $cell );
            }
        }
        if ( $empty_tds ) {
            for ( $i = 0; $i < $empty_tds; $i++ ) { 
                $tr .= placester_html( 'td', '-' );
            }
        }
        $class = ( $index % 2 == 0 ) ? '' : array( 'class' => 'alternate' );
        $tbody .= placester_html( 'tr', $class, $tr );
    }

    $default_attr = array( 'class' => 'widefat' );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    // Create the table
    $result = placester_html( 
        'table',  
        $attr, 
        placester_html( 'thead', $ths ),
        placester_html( 'tbody', $tbody ),
        placester_html( 'tfoot', $ths )
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

/**
 * placester_get_profile_row 
 * 
 * @param mixed $for 
 * @param mixed $th 
 * @param mixed $td 
 * @param mixed $description 
 * @access public
 * @return void
 */
function placester_get_form_table_row_alt( $for, $th, $td, $description = false ) {
    $td = ( $description ) ? $td . "<br/>" .  placester_html( 'span', array( 'class' => 'description' ), $description ) : $td; 
    return placester_get_form_table_row(
        array( 
            'th' => placester_html( 'label', array( 'for' => $for ), $th ),
            'td' =>  $td
        )
    );
}

/**
 * Generates a Wordpress form table row
 *
 */
// TODO Better docu
// <tr valign="top">
// <th scope="row"><label for="blogname">Site Title</label></th>
// <td><input name="blogname" type="text" id="blogname" value="Real Estate Pro" class="regular-text"></td>
// </tr>
function placester_get_form_table_row( $args, $echo = false ) {
    $defaults = array( 
        'valign' => 'top',
        'th' => 'Default title',
        'td' => 'Default body',
        'extra_attr' => array(),
    );
    $args = wp_parse_args( $args, $defaults );
    extract( $args, EXTR_SKIP );

    $default_attr = array( 'valign' => $valign );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    $result = placester_html(
        'tr',
        $attr,
        placester_html( 'th', array( 'scope' => 'row' ), $th ) . 
        placester_html( 'td', $td ) 
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

/**
 * Generates a Wordpress form table
 *
 * TODO Better documentation, maybe use array structure for rows
 */
function placester_get_form_table( $args, $echo = false ) {
    $defaults = array( 
        'method' => 'POST', // Form method
        'action' => '#', // Form Action
        'pre_table' => '', // What should be added before the table
        'post_table' => '', // What should be added after the table
        'extra_attr' => array(), // Extra form attibutes
        'rows' => '', // The collection of rows
    );
    $args = wp_parse_args( $args, $defaults );
    extract( $args, EXTR_SKIP );

    $default_attr = array( 'method' => $method, 'action' => 'action' );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    $result =
        placester_html(
            'form',
            $attr,
            $pre_table .
            placester_html(
                'table',
                array( 'class' => 'form-table' ),
                placester_html( 'tbody', $rows )
            ) . 
            $post_table
        );    

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

// <tr valign="top">
// <th scope="row"><label for="blogname">Site Title</label></th>
// <td><input name="blogname" type="text" id="blogname" value="Real Estate Pro" class="regular-text"></td>
// </tr>


/**
 *  Creates a wordpress postbox with a desired content
 *
 *  @param (string)$content - The postbox content
 *  @param (string)$title - The postbox title
 *  @param (array)$extra_attr - Extra attributes array
 *  @param (bool)$echo - true echoes the box, false returns it
 *   
 *  e.g.: placester_get_postbox( '<p>Hello World!</p>', 'Hello postbox',  array( 'id' => 'hello' ), true );
 */
function placester_get_postbox( $content, $title, $extra_attr = array(), $echo = false ) {
    $default_attr = array( 'class' => 'postbox' );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    // Create the postbox
    $result = placester_html( 
        'div',
        $attr,
        placester_html( 
            'div',
            array( 
                'class' => 'handlediv',
                'title' => 'Click to toggle' 
            ),
            placester_html( 'br' )
        ),
        placester_html( 
            'h3',
            array( 'class' => 'hndle' ),
            "<span>{$title}</span>"
        ),
        placester_html( 
            'div',
            array( 'class' => 'inside' ),
            $content
        )
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

/**
 *  Creates an unlimited number of wordpress postbox containers
 *  with the desired content
 *
 *  @param (string)$data - The collection of postbox-containers
 *  @param (bool)$auto_width 
 *      - true if all containers should have equal width,
 *      - false if the width is defined
 *  @param (array)$extra_attr - Extra attributes array
 *  @param (bool)$echo - true echoes the box, false returns it
 *
 *  $data = array (
 *      0 => array (
 *          0 => $contents,
 *          1 => $extra_attr
 *      ),
 *      1 => array (
 *          0 => $contents,
 *          1 => $extra_attr
 *      ),
 *  );
 *
 *  Note: If $auto_width is set and the $extra_attr sets an inline width, the 
 *  auto-calculated width will be overwritten by the value defined in the 
 *  $extra_attr array, thus resulting in a columns that don't fit or are too 
 *  narrow.
 */
function placester_get_postbox_containers( $data, $auto_width = true, $extra_attr = array(), $echo = false ) {
    
    $postbox_containers = '';
    // Process the posbox containers
    foreach ( $data as $postbox_container ) {
        $pc_attr = array( 'class' => 'postbox-container' );

        // If auto width required ...
        if ( $auto_width ) {
            // ...set the width and add 'style' to the default attribute array
            $pc_attr['style'] = 'width: ' . ( 100 / count( $data ) - 0.5 ) . '%;';
        }
        // Add extra attributes to each container if the case applies
        // $postbox_container[1] contains an array with extra attr for that pc
        if ( isset( $postbox_container[1] ) && is_array( $postbox_container[1] ) ) {
            $pc_attr = placester_merge_array_args( $pc_attr, $postbox_container[1] );
        }
        $postbox_containers .= placester_html(
            'div',
            $pc_attr,
            $postbox_container[0]
        );
    }

    $default_attr = array( 'id' => 'poststuff' );
    $attr = placester_merge_array_args( $default_attr, $extra_attr );

    // Create the poststuff div with the containers
    $result = placester_html( 
        'div',
        $attr,
        $postbox_containers
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

function placester_get_settings_page_title( $title ) {
    $icon = placester_html( 
        'div',
        array( 
            'id' => 'icon-options-general',
            'class' => 'icon32'
        ),
        placester_html( 'br' )
    ); 

    $title = placester_html(
        'h2',
        array( 
            'id' => 'placester-admin-menu',
        ),
        $title
    ); 

    return $icon . $title;
}


