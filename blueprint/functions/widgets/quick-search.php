<?php 

class PLS_Quick_Search_Widget extends WP_Widget {
    
    function __construct() {
    
        /* Set the widget textdomain. */
        $this->textdomain = pls_get_textdomain();
            
        $widget_options = array( 'classname' => 'pls-quick-search','description' => 'Displays search filters for bedrooms, bathrooms, city, state, zip, minimum price, and maximum price');

        /* Create the widget. */
        parent::__construct( "pls-quick-search", 'Placester: Listings Quick Search', $widget_options );        
    }

    function widget($args, $instance) {

        list($args, $instance) = self::process_defaults($args, $instance);

        extract( $args, EXTR_SKIP );
 
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

        $search_form_filter_string = '';

        $search_form_filter_string .= 'context=' . apply_filters('pls_widget_quick_search_context', 'quick_search_widget');

        $search_form_filter_string .= apply_filters('pls_widget_quick_search_filter_string', '&ajax=0');

        $search_form_filter_string .= '&class=pls_quick_search';

           echo $before_widget;
            echo "<h3>" . $title . "</h3>";
            echo PLS_Partials::get_listings_search_form($search_form_filter_string);
        echo "<section class='clear'></section>";
        echo "</section>";
        echo $after_widget;
    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));

        return $instance;
    }

    function form($instance){
        //Defaults
        $instance = wp_parse_args( (array) $instance, array('title'=>'') );

        $title = htmlspecialchars($instance['title']);

        // Output the options
        echo '<p><label for="' . $this->get_field_name('title') . '"> Title: </label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';
    }

    function process_defaults ($args, $instance) {

        /** Define the default argument array. */
        $arg_defaults = array(
            'title' => 'Quick Search',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<section id="pls-quick-search-3" class="widget pls-quick-search widget-pls-quick-search">',
            'after_widget' => '</section>',
            'widget_id' => ''
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $arg_defaults );


        /** Define the default argument array. */
        $instance_defaults = array(
            'widget_id' => ''
        );

        /** Merge the arguments with the defaults. */
        $instance = wp_parse_args( $instance, $instance_defaults );


        return array($args, $instance);
    }

} //end of class