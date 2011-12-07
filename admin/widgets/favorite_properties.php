<?php 
class Placester_Property_Favorites_Widget extends WP_Widget {

  function Placester_Property_Favorites_Widget() {
      $widget_ops = array(
          'classname' => 'Placester_Property_Favorites_Widget', 
          'description' => __( 'Adds a list of favorite properties when a lead is logged in.') 
      );
    $this->WP_Widget( 'Placester_Properties_Favorites_Widget', 'Placester Property Favorites', $widget_ops );
  }

  /**
   * Form that will be displayed on in Wordpress admin backend
   * 
   * @param mixed $instance 
   */
  function form( $instance ) {

      $defaults = array( 'title' => 'Favorites' ); 

      // Defaults
      $instance = wp_parse_args( (array)$instance, $defaults );

      $title = htmlspecialchars( $instance['title'] );

      extract( $instance, EXTR_SKIP );

      // Output the title
      echo placester_html( 
          'p', 
          placester_html( 
              'label',
              array( 'for' => $this->get_field_name( 'title' ) ),
              'Title'
          ) . 
          placester_html(
              'input',
              array(
                  'class' => 'widefat',
                  'type' => 'text',
                  'id' => $this->get_field_name( 'title' ),
                  'name' => $this->get_field_name( 'title' ),
                  'value' => $title,
              )
          )
      );

      echo placester_html( 
          'p',
          array( 'style' => 'font-size: 0.9em' ),
          'This widget will show only when an user with a lead account is logged in.'
      );
  }
  
  /**
   * Called when the widget is updated
   * 
   */
  function update( $new_instance, $old_instance ){
    $instance = $old_instance;
    $instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
    return $instance;
  }
  
  /**
   * Displays the widget frontend content
   * 
   * @param mixed $args 
   * @param mixed $instance 
   * @access public
   * @return void
   */
  function widget( $args, $instance ) {
      $widget = '';
      $current_user = wp_get_current_user();

      // Display content only if the current user is a lead
      if ( current_user_can( 'placester_lead' ) ) {

          extract( $instance );
          $lead_info = placester_get_lead_information( $current_user );

          if ( isset( $lead_info->properties ) ) {

              // Create the list of favoritte properties 
              $property_items = '';
              foreach( $lead_info->properties as $favorite ) {
                  $property_items .= placester_html(
                      'li',
                      array( 'class' => 'clearfix' ),
                      placester_html(
                          'a',
                          array(
                              'href' => site_url( placester_post_slug() . "/{$favorite->id}" )
                          ),
                          $favorite->full_address
                      ) .
                      placester_html(
                          'a',
                          array(
                              'href' => "#{$favorite->id}",
                              'class' => "pl_remove",
                          ),
                          'Remove'
                      )
                  );
              }

              // The favorites list
              $widget_content = placester_html(
                  'ul',
                  $property_items
              );

              // Create the widget html
              $widget = placester_html( 
                  'section',
                  array(
                      'class' => 'side-ctnr pl_favorite_properties',
                  ),
                  placester_html( 
                      'h3', 
                      $title .
                      // TODO Add ability of setting path in the widget form
                      placester_html(
                          'img',
                          array(
                              'src' => admin_url( 'images/wpspin_light.gif' ),
                              'class' => "pl_spinner",
                              'alt' => "ajax-spinner",
                              'style' => 'display:none;'
                          ) 
                      )
                  ) . 
                  placester_html( 
                      'section',
                      array( 'class' => 'common-side-cont clearfix' ),
                      $widget_content
                  ) .
                  placester_html( 'div', array( 'class' => 'sepparator' ) )
              );
          } // #end if ( isset( $lead_info->properties ) )
      }
      echo $widget;
  }
} // End Class


/**
 * Enqueues the needed style and script files in the theme
 * 
 * @return void
 */
add_action('template_redirect', 'placester_property_favorites_widget_init');
function placester_property_favorites_widget_init() {

    wp_register_style( 'favorite_properties.widget', 
        get_plugin_url( 'css' ) . 'favorite_properties.widget.css' );
    wp_register_script( 'favorite_properties.widget', 
        get_plugin_url( 'js' ) . 'favorite_properties.widget.js' );

    if ( current_user_can( 'placester_lead' ) ) {
        wp_enqueue_style( 'favorite_properties.widget' );
        wp_enqueue_script( 'favorite_properties.widget' );
    }
}
