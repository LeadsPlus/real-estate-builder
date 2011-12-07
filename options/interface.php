<?php
/*
* Slightly modifed version of the Options Framework by Devin Price
*
* interface.php: Generates the options fields that are used in the form.
*/

function placester_of_fields() {

	global $allowedtags;
	$placester_of_settings = get_option('placester_theme_options');

	// Gets the unique option id
	if (isset($placester_of_settings['id'])) {
		$option_name = $placester_of_settings['id'];
	}
	else {
		$option_name = 'placester_theme_options';
	};
	$settings = get_option($option_name);

    global $theme_options;
        
    $counter = 0;
	$menu = '';
	$output = '';
	
	foreach ($theme_options as $value) {
		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		
		// Wrap all options
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {
			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );

			$id = 'section-' . $value['id'];

			$class = 'section ';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			$output .= '<h3 class="heading">' . esc_html( $value['name'] ) . '</h3>' . "\n";
            $output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
		 }
		
		// Set default value to $val
		if ( isset( $value['std']) ) {
			$val = $value['std'];
		}
		
		// If the option is already saved, ovveride $val
		if ( ($value['type'] != 'heading') && ($value['type'] != 'info')) {
			if ( isset($settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
			}
		}
		                                
		switch ( $value['type'] ) {
		
		// Basic text input
		case 'text':
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break;
		
		// Textarea
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['options'])){
				$ta_options = $value['options'];
				if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
				} else { $cols = '8'; }
			}
			
			$val = stripslashes( $val );
			
			$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" cols="'. esc_attr( $cols ) . '" rows="8">' . esc_textarea( $val ) . '</textarea>';
		break;
		
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
			
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if( $val != '' ) {
					 if ( $val == $key) { $selected = ' selected="selected"';} 
			     }
				 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			 } 
			 $output .= '</select>';
		break;

		
		// Radio Box
		case "radio":
			$name = $option_name .'['. $value['id'] .']';
			foreach ($value['options'] as $key => $option) {
				$id = $option_name . '-' . $value['id'] .'-'. $key;
				$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label><br />';
			}
		break;
		
		// Image Selectors
		case "images":
			$name = $option_name .'['. $value['id'] .']';
			foreach ( $value['options'] as $key => $option ) {
				$selected = '';
				$checked = '';
				if ( $val != '' ) {
					if ( $val == $key ) {
						$selected = ' of-radio-img-selected';
						$checked = ' checked="checked"';
					}
				}
				$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. $checked .' />';
				$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" />';
			}
		break;
		
		// Checkbox
		case "checkbox":
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .' />';
		break;
		
		// Multicheck
		case "multicheck":
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				$label = $option;
				$option = preg_replace('/\W/', '', strtolower($key));

				$id = $option_name . '-' . $value['id'] . '-'. $option;
				$name = $option_name . '[' . $value['id'] . '][' . $option .']';

			    if ( isset($val[$option]) ) {
					$checked = checked($val[$option], 1, false);
				}

				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
			}
		break;
		
		// Color picker
		case "color":
			$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
			$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break; 
		
		// Info
		case "info":
			$class = 'section';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
			if ( isset($value['name']) ) {
				$output .= '<h3 class="heading">' . esc_html( $value['name'] ) . '</h3>' . "\n";
			}
			if ( $value['desc'] ) {
				$output .= '<p>'. wp_kses( $value['desc'], $allowedtags) . '</p>' . "\n";
			}
			$output .= '<div class="clear"></div></div>' . "\n";
		break;                       
		
		// Heading for Navigation
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}

			$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li><a title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a></li>';
			$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '"><h2>' . esc_html( $value['name'] ) . '</h2>' . "\n";
			break;
		}

		if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
			if ( $value['type'] != "checkbox" ) {
				$output .= '<br/>';
			}
			$explain_value = '';
			if ( isset( $value['desc'] ) ) {
				$explain_value = $value['desc'];
			}
			$output .= '</div><div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}
    $output .= '</div>';
    return array($output,$menu);
}
