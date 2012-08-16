<?php

/**
 * Generates the options fields that are used in the form.
 */

function optionsframework_fields() {
	global $allowedtags;
	$optionsframework_settings = get_option('optionsframework');
	
	// Get the theme name so we can display it up top
	$themename = get_theme_data(get_stylesheet_directory() . '/style.css');
	$themename = $themename['Name'];

	// Gets the unique option id
	if (isset($optionsframework_settings['id'])) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	};

	$settings = get_option($option_name);
	// pls_dump($option_name);
	// pls_dump($settings);
    $options = optionsframework_options();
        
    $counter = 0;
	$menu = '';
	$output = '';
	
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		
		// Wrap all options
		


		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {

			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['id']) );

			$id = 'section-' . $value['id'];

			$class = 'section ';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
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
		
		// If there is a description save it for labels
		$explain_value = '';
		if ( isset( $value['desc'] ) ) {
			$explain_value = $value['desc'];
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
				$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
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
			$output .= '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
		break;
		
		// Multicheck
		case "multicheck":
			$output .= '<ul class="multicheck-list">';
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				$label = $option;
				$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

				$id = $option_name . '-' . $value['id'] . '-'. $option;
				$name = $option_name . '[' . $value['id'] . '][' . $option .']';

			    if ( isset($val[$option]) ) {
					$checked = checked($val[$option], 1, false);
				}
				$output .= '<li>';
				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
				$output .= '</li>';
			}
			$output .= '</ul>';

		break;
		
		// Color picker
		case "color":
			$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
			$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break; 
		
		// Uploader
		case "upload":
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
		break;
		
		// Typography
		case 'typography':	
		
			$typography_stored = $val;

			// Check if null
       if ( !isset( $typography_stored['size'] ) ) {
         $typography_stored['size'] = '';
       }
       if ( !isset( $typography_stored['face'] ) ) {
         $typography_stored['face'] = '';
       }
       if ( !isset( $typography_stored['style'] ) ) {
         $typography_stored['style'] = '';
       }
       if ( !isset( $typography_stored['color'] ) ) {
         $typography_stored['color'] = '';
       }

			// Font Size
			$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
			for ($i = 9; $i < 71; $i++) { 
				$size = $i . 'px';
				
				// Check if null
				if(!isset($typography_stored['size'])) {
					$typography_stored['size'] = '';
				}
				
				$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
			}
			$output .= '</select>';
		
			// Font Face
			$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '][face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
			
			$faces = of_recognized_font_faces();

			// Check if null
			if(!isset($typography_stored['face'])) {
				$typography_stored['face'] = '';
			}

			foreach ( $faces as $key => $face ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
			}			
			
			$output .= '</select>';	

			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'][style]" id="'. $value['id'].'_style">';

			/* Font Style */

			// Check if null
			if(!isset($typography_stored['style'])) {
				$typography_stored['style'] = '';
			}

			$styles = of_recognized_font_styles();
			foreach ( $styles as $key => $style ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
			}
			$output .= '</select>';

			// Font Color
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';

		break;
		
		// Background
		case 'background':
			// pls_dump($val);
			$background = $val;

			// Check if null
			if ( !isset( $background['repeat'] ) ) {
			  $background['repeat'] = '';
			}
			if ( !isset( $background['position'] ) ) {
			  $background['position'] = '';
			}
			if ( !isset( $background['attachment'] ) ) {
			  $background['attachment'] = '';
			}
			if (!isset($background['image'])) {
				$background['image'] = '';
			}
			if (!isset($background['color'])) {
				$background['color'] = '';
			}

			// Background Color
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';

			// Background Image - New AJAX Uploader using Media Library
			$output .= optionsframework_medialibrary_uploader( $value['id'], $background['image'], null, '',0,'image');
			$class = 'of-background-properties';
			if ( '' == $background['image'] ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';

			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
			$repeats = of_recognized_background_repeat();


			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Position
			$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
			$positions = of_recognized_background_position();
			
			foreach ($positions as $key=>$position) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
			$attachments = of_recognized_background_attachment();

			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';

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
				$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			}
			if ( $value['desc'] ) {
				$output .= apply_filters('of_sanitize_info', $value['desc'] ) . "\n";
			}
			$output .= '<div class="clear"></div></div>' . "\n";

		break;


		// Border
		case 'border':
		
			$border_stored = $val;

			// Check if null
			if ( !isset( $border_stored['size'] ) ) {
				$border_stored['size'] = '';
			}
			if ( !isset( $border_stored['style'] ) ) {
				$border_stored['style'] = '';
			}
			if ( !isset( $border_stored['color'] ) ) {
				$border_stored['color'] = '';
			}

			// Border Size
			$output .= '<select class="of-border of-border-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
			for ($i = 1; $i < 11; $i++) { 
				$size = $i . 'px';
				$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $border_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
			}
			$output .= '</select>';

			// Border Style
			$styles = of_recognized_border_styles();
			$output .= '<select class="of-border of-border-style" name="'.$option_name.'['.$value['id'].'][style]" id="'. $value['id'].'_style">';
			foreach ( $styles as $key => $style ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $border_stored['style'], $key, false ) . '>'. $style .'</option>';
			}
			$output .= '</select>';

			// Border Color
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $border_stored['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-border of-border-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $border_stored['color'] ) . '" />';

		break;

		case "slideshow":
		if (!isset($val['num_slides'])) {
			$val['num_slides'] = 6;
		}
		ob_start();
			for ($i=0; $i < $val['num_slides']; $i++) { 
				?>
					<?php if (!isset($val[$i])): ?>
						<?php $val[$i] = array(); ?>
					<?php endif ?>
					<?php $val[$i] = wp_parse_args($val[$i], array('image' => '', 'link' => '', 'html' => '', 'type' => '', '')) ?>
					<div class="featured_slideshow_options">
						<div class="featured_slide">
							<div class="featured_slide_header">
								<h2>Slide <?php echo $i+1 ?></h2>
								<select class="slideshow_type" name="<?php echo esc_attr( $option_name . '[' . $value['id'] . '][' . $i . '][type]') ?>" id="" >
									<option value="custom" <?php echo $val[$i]['type'] == 'custom' ? 'selected=selected' : '' ?> >Custom Image</option>
									<option value="listing" <?php echo $val[$i]['type'] == 'listing' ? 'selected=selected' : '' ?> >Listing</option>
<!-- 									<option value="post">Post</option>
									<option value="area">Area Page</option> -->
								</select>
							</div>
							<div class="type_controls" id="custom_type">
								<p>Select an image for the background of the slide and enter in text or html to be displayed. </p>
								<div id="image_wrapper_<?php echo $i ?>" class="item_row">
									<label for="">Background Image</label>
									<?php echo optionsframework_medialibrary_uploader( $value['id'], $val[$i]['image'], null, '',0, "image", $i ); ?>		
								</div>
								<div class="item_row">
									<label for="">Click to Link</label>
									<input type="text" value="<?php echo $val[$i]['link'] ?>" name="<?php echo esc_attr( $option_name . '[' . $value['id'] . '][' . $i . '][link]') ?>">
								</div>
								<div class="item_row">
									<label for="">HTML/Text Displayed</label>
									<textarea name="<?php echo esc_attr( $option_name . '[' . $value['id'] . '][' . $i . '][html]') ?>" id="" cols="30" rows="10"><?php echo $val[$i]['html'] ?></textarea>	
								</div>
							</div>
							<div class="type_controls" id="listing_type">
								<?php echo pls_generate_featured_listings_ui($value, $val[$i], $option_name, $i, $for_slideshow = true) ?>		
							</div>
							<!-- <div class="type_controls" id="post_type">
								<select name="" id="">
									<option>How the web was won</option>
								</select>
							</div>
							<div class="type_controls" id="area_type">
								
							</div> -->
						</div>						
					</div>
				<?php
			}
			$output .= trim( ob_get_clean() );
		break;

		// Featured Listing Selection
		case "featured_listing":
			$output .= pls_generate_featured_listings_ui($value,$val, $option_name);
		break;



		// Featured Listing Selection
		case "featured_neighborhoods":

			ob_start();
			?>

			<div class="featured-listing-search" id="featured-listing-search-<?php echo $value['id']; ?>">

				<div class="fls-address">

          <label>Neighborhoods</label>
          <?php $categories = get_categories('type=property&taxonomy=neighborhood'); ?>
          <select name="<?php echo $value['id']; ?>" class="fls-address-select" id="fls-select-neighborhood">
            <?php for ($i = 1; $i <= 200; $i++) {
              if (!isset($categories[$i])) {
                break;
              } else {
              echo '<option value=' . $categories[$i]->slug . '>';
                echo $categories[$i]->name;
              echo '</option>';
              }
            }
            ?>
          </select>
          

					<input type="submit" name="<?php echo $value['id']; ?>" value="Add Neighborhood" class="fls-add-listing button" id="add-listing-<?php echo $value['id']; ?>">	
					<input type="hidden" value="<?php echo esc_attr( $option_name . '[' . $value['id'] . ']' ) ?>" id="option-name">	

				</div>

				<h4 class="heading">Featured Neighborhoods</h4>
				<div class="fls-option">
					<div class="controls">
						<ul name="<?php echo $value['id']; ?>" id="fls-added-listings">
							<?php if (isset($settings[$value['id']])): ?>
								<?php foreach ($settings[$value['id']] as $key => $text): ?>
									<li style='float:left; list-style-type: none;'><div id='pls-featured-text' style='width: 200px; float: left;'><?php echo $text ?></div><a style='float:left;' href='#' id='pls-option-remove-listing'>Remove</a><input type='hidden' name='<?php echo esc_attr( $option_name . '[' . $value['id'] . '][' . $key . ']=' ) ?>' value='<?php echo $text ?>' /></li>
								<?php endforeach ?>
							<?php endif ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			</div>


			<?php
			$output .= trim( ob_get_clean() );
		break;



		// Heading for Navigation
		case "heading":
			if ($counter >= 2) {
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li class="side-bar-nav-item"><a id="'.  esc_attr( $jquery_click_hook ) . '-tab" class="nav-tab" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a></li>';
			$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '">';
			$output .= '<h3 id="optionsframework-submit-top" >' . esc_html( $value['name'] ) . '<input type="submit" class="top-button button-primary" name="update" value="'. 'Save Options'  . '" /></h3>' . "\n";
			break;
		}

		if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
			if ( $value['type'] != "checkbox" ) {
				$output .= '<br/>';
			}
			$output .= '</div>';
			if ( $value['type'] != "checkbox" ) {
				$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
			}
			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}
	$for_slideshow = isset($for_slideshow) ? $for_slideshow : false;
	$output .= PLS_Featured_Listing_Option::load( array( 'value' => $value, 'val' => $val, 'option_name' => $option_name, 'iterator' => isset($iterator) ? $iterator : false, 'for_slideshow' => $for_slideshow) );
    $output .= '</div>';

    return array($output,$menu);
}

function pls_generate_featured_listings_ui ($value, $val, $option_name, $iterator = false, $for_slideshow = false) {
	return PLS_Featured_Listing_Option::init( array( 'value' => $value, 'val' => $val, 'option_name' => $option_name, 'iterator' => $iterator, 'for_slideshow' => $for_slideshow) );
	//return PLS_Featured_Listing_Option::load( array( 'value' => $value, 'val' => $val, 'option_name' => $option_name, 'iterator' => $iterator, 'for_slideshow' => $for_slideshow) );
}

