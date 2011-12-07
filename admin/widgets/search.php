<?php

/**
 * Create Search Widget
 */
class Placester_Search_Widget extends WP_Widget {
    
    function Placester_Search_Widget() {
        $widget_ops = array('classname' => 'placester_search_widget', 'description' => __( 'Search Placester Listings') );
        parent::WP_Widget(false, 'Placester Search Widget', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
 
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        $selected = "selected='selected'";
 
        echo $before_widget;
 
        if ( $title ) echo $before_title . $title . $after_title;
 
        echo '<form action="' . get_bloginfo("url") . '" method="get" id="searchform" class="search_form">';
 
        
        echo '<div>Bedrooms';
        echo '<select name="bedrooms">';
        echo '<option value="" ></option>';
        for ($i=1; $i < 10; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if(isset($_GET['bedrooms']) && ($i == $_GET['bedrooms'])) echo "selected='selected'"; ?>> <?php echo "$i"; ?></option>
        <?php
        }
        echo '</select>';
        echo '</div>';
 
        echo '<div>Bathrooms';
        echo '<select name="bathrooms">';
        echo '<option value="" ></option>';
        for ($i=1; $i < 10; $i++) { ?>
            <option value="<?php echo $i ?>"<?php if(isset($_GET['bathrooms']) && ($i == $_GET['bathrooms'])) echo "selected='selected'"; ?>> <?php echo "$i"; ?></option>
        <?php
        }
        echo '</select>';
        echo '</div>';
        
 
        $price = array('',200,500,1000,1500,2000,3000,4000,5000);
        echo '<div>Minimum Price';
        echo '<select name="min_price">';
        foreach ($price as $value) { ?>
            <option value="<?php echo $value ?>"<?php if(isset($_GET['min_price']) && ($value == $_GET['min_price'])) echo "selected='selected'"; ?>>&#36;<?php echo "$value"; ?></option>
        <?php
        }
        echo '</select>';
        echo '</div>';
 
 
        $price = array(5000,4000,3000,2000,1500,1000,500,200);
        echo '<div>Maximum Price';
        echo '<select name="max_price">';
        foreach ($price as $value) { ?>
            <option value="<?php echo $value ?>"<?php if(isset($_GET['max_price']) && ($value == $_GET['max_price'])) echo "selected='selected'"; ?>>&#36;<?php echo "$value"; ?></option>
        <?php
        }
        echo '</select>';
        echo '</div>';
 
        $locations = array('city', 'state', 'zip');
        foreach($locations as $location) {
        echo '<div>' . ucfirst($location);
        echo '<select name="location['. $location . ']">'; 
        ?>
        <option value="" <?php if(!isset($_GET['location'][$location])) { echo "selected='selected'" ;} ?>></option>
        <?php
        placester_display_location($location);
        echo '</select>';
        echo '</div>';
    }
           
 echo '<input type="submit" value="Search" /></form>' . $after_widget;    
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
        echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . '</label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';
    }

}