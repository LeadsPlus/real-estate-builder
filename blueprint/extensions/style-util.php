<?php 

PLS_Style::init();

class PLS_Style {
	

    static $styles = array();

    /**
     *  grabs the option list and makes it available
     */
    static function init()
    {

        // hooks create_css into head. create_css generates all
        // the needed css for these options.
        add_filter('wp_head', array(__CLASS__, 'create_css') );		
        
        // bunddles all the options to the class so they can 
        // have styles generated for them. 
        self::get_options();

    }

    static function get_options()
    {
        // Cache options
        if((WP_DEBUG !== true)) {
            $cache = new PLS_Cache('Theme PLS Options');
            $cache_args = array();
            if ($options = $cache->get($cache_args)) {
                PLS_Debug::add_msg('[[Theme options cache hit!]] Returning cached options');
                self::$styles = array_merge(self::$styles, $options);
                return;
            }
        }

        require(PLS_Route::locate_blueprint_option('init.php'));
        require_if_theme_supports("pls-user-options", PLS_Route::locate_blueprint_option('user.php'));
        require_if_theme_supports("pls-search-options", PLS_Route::locate_blueprint_option('search.php'));    
        require_if_theme_supports("pls-color-options", PLS_Route::locate_blueprint_option('colors.php'));    
        require_if_theme_supports("pls-color-options", PLS_Route::locate_blueprint_option('slideshow.php'));    
        require_if_theme_supports("pls-typography-options", PLS_Route::locate_blueprint_option('typography.php'));
        require_if_theme_supports("pls-header-options", PLS_Route::locate_blueprint_option('header.php'));
        require_if_theme_supports("pls-navigation-options", PLS_Route::locate_blueprint_option('navigation.php'));   
        require_if_theme_supports("pls-listing-options", PLS_Route::locate_blueprint_option('listings.php'));
        require_if_theme_supports("pls-post-options", PLS_Route::locate_blueprint_option('post.php'));
        require_if_theme_supports("pls-widget-options", PLS_Route::locate_blueprint_option('widget.php'));
        require_if_theme_supports("pls-footer-options", PLS_Route::locate_blueprint_option('footer.php'));
        require_if_theme_supports("pls-css-options", PLS_Route::locate_blueprint_option('css.php'));

        // Cache options
        if((WP_DEBUG !== true)) {
            $cache->save(self::$styles);
        }

    }

    public static function add ($options = false)
    {
        if ($options) {
            self::$styles[] =$options;
        }
    }

	public static function create_css () {

			PLS_Debug::add_msg('Styles being created');
				// groups all the styles by selector so they can 
				// be combine in a string, which is echo'd out. 
				$sorted_selector_array = self::sort_by_selector(self::$styles);

				if ( empty($sorted_selector_array) ) {
					return false;
				}

			$styles = '';

			foreach ( $sorted_selector_array as $selector => $options) {

				$styles .= apply_filters($selector, $selector) . ' {' . "\n";

				foreach ($options as $index => $option) {

					$defaults = array(
						"name" => "",
						"desc" => "",
						"id" => "",
						"std" => "",
						"selector" => "body",
						"style" => "",
						"type" => "",
						"important" => true,
						"default" => ""
					);

					/** Merge the arguments with the defaults. */
					$option = wp_parse_args( $option, $defaults );

					if (!empty($option['style']) || self::is_special_case($option['type'])) {

						//if we have a style, then let's try to generate a stlye.
						$styles .= self::handle_style($option['style'], $option['id'], $option['default'], $option['type'], $option['important']);

					} elseif (!empty($id)) {
						//try to use the id as the style... saves time for power devs.
						$styles .= self::handle_style($option['id'], $option['id'], $option['default'], $option['type'], $option['important']);

					} else {
					}
				}
				$styles .= '}' . "\n";
			}

			PLS_Debug::add_msg('<pre>' . $styles . '</pre>');

			$styles = '<style type="text/css">' . $styles . '</style>';

			echo $styles;

	}

	//for quick styling
	private static function handle_style ($style, $id, $default, $type, $important) 
	{

        if ($value = pls_get_option($id, $default)) {
            
            $css_style = '';
            
            // check for special cases
            // sometimes the options framework saves certain options
            // in unique ways which can't be directly translated into styles
            if (self::is_special_case($type)) {
                
                //handles edge cases, returns a property formatted string
                
                return self::handle_special_case($value, $id, $default, $type, $important);
            } else {
                $css_style = self::make_style($style, $value, $important);
                return $css_style;                    
            }
                        
        } else {
            return '';
        }
	}

    private static function handle_special_case($value, $id, $default, $type, $important)
    {
        switch ($type) {
            case 'typography':
                return self::handle_typography($value, $id, $default, $type, $important);
                break;
            case 'background':
                return self::handle_background($value, $id, $default, $type, $important);
                break;
			case 'border':
					return self::handle_border($value, $id, $default, $type, $important);
					break;
        }
    }

    private static function handle_background($value, $id, $default, $type, $important) {

        if (is_array($value)) {
            
            $css_style = '';
            
            foreach ($value as $key => $value) {
                switch ($key) {
                    case 'color':
						$css_style .= self::make_style('background', $value, $important);
                        break;

                    case 'image':
                        $css_style .= self::make_style('background-image', $value, $important);
                        break;
                    
                    case 'repeat':
                        $css_style .= self::make_style('background-repeat', $value, $important);
                        break;
                    
                    case 'position':
                        $css_style .= self::make_style('background-position', $value, $important);
                        break;

                    case 'attachment':
                        $css_style .= self::make_style('background-attachment', $value, $important);
                        break;    
                }    
            }
            return $css_style;
        }
    }

    private static function handle_typography ($value, $id, $default, $type, $important)
    {
        
        if (is_array($value)) {
            
            $css_style = '';
            
            foreach ($value as $key => $value) {
                switch ($key) {
                        case 'size':
                            if ($value != "9px") {
                                $css_style .= self::make_style('font-size', $value, $important);
                            }
                            break;

                        case 'face':
                            $css_style .= self::make_style('font-family', $value, $important);
                            break;
                        
                        case 'style':
                            $css_style .= self::make_style('font-weight', $value, $important);
                            break;
                        
                        case 'color':
                            $css_style .= self::make_style('color', $value, $important);
                            break;
                    }    
            }
            // return the new styles.
            return $css_style;

        } else {
            //something strange happened, typography should always return an array.
            return '';
        }
    }

		private static function handle_border ($value, $id, $default, $type, $important) {

			if (is_array($value)) {

				$css_style = "border: ";

				foreach ($value as $key => $value) {
					if ($key == "size") {
						$value = $value . 'px';
						$css_style .= $value . ' ';
					}
					if($key == "style") {
						$css_style .= $value . ' ';
					}
					if($key == "color") {
						$css_style .= $value . ' !important;';
					}
				}
				return $css_style . "\n";

			} else {
				return '';
			}
    }


    //given a syle, and a value, it returns a propertly formated styles
    private static function make_style($style, $value, $important = false)
    {

        if (empty($value) || $value == 'default') {
            return '';
        } else {

					switch ($style) {

						case 'radius':
							$item = 'border-radius:'. $value . "px " . ($important ? ' !important;' : '') . "\n";
							$item .= '-moz-border-radius:' . $value . "px " . ($important ? ' !important;' : '') . "\n";
							$item .= '-webkit-border-radius:' . $value . "px" . ($important ? ' !important;' : '') . "\n";
							return $item;
							break;
                
						case 'background-image':
							return 'background-image: url(\'' . $value . "') " . ($important ? ' !important;' : '') . "\n";
							break;

						default:
							return $style . ': ' . $value . ($important ? ' !important;' : '') . "\n";
							break;
						}
					}

    }

    // Takes an array with options that have various seelctors
    // and merges all the otpions with the same selector under
    // a new array attribute so it can be easily used to generate
    // css
    private static function sort_by_selector ($options)
    {
        $selector_array = array();

        foreach ($options as $item => $option) {
            //if we don't have a selector, try to generate one
            if ($option['type'] == 'heading' || $option['type'] == 'info') {
                continue;
            }

            if ((is_array($option) && !isset($option['selector'])) || empty($option['selector'])) {

                // user can set selector in front of id
                $selector_id_array = explode('.', $option['id']);
                
                if ( isset($selector_id_array[1])) {
                    $option['selector'] = $selector_id_array[0];    
                } else {
                    $option['selector'] = 'body';    
                }
            } 

            // yank out all the styles that apply to specific selectors into
            // an array that is 'selector'[0] => style, [1] => style
            if (array_key_exists($option['selector'], $selector_array)) {
                $selector_array[$option['selector']][] = $option;
            } else {
                $selector_array[$option['selector']] = array();
                $selector_array[$option['selector']][] = $option;
            }
        }

        return $selector_array;
    }

    private static function is_special_case($option_type)
    {
        $special_id_cases = array('typography', 'background', 'border');
        if ( in_array($option_type, $special_id_cases) ) {
            return true;
        } 

        return false;

    }
}

// needed for the options framework. 
// TODO: integrate this into the style class
function optionsframework_options() {
    return PLS_Style::$styles;
}
