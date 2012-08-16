<?php
/**
 * A more powerfull recent posts widget.
 *
 * @package PlacesterBlueprint
 * @subpackage Classes
 */

/**
 * Recent Posts Widget Class
 *
 * @since 0.0.1
 */
class PLS_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Textdomain for the widget.
	 * @since 0.0.1
	 */
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.0.1
	 */
	function __construct() {

		/* Set the widget textdomain. */
		$this->textdomain = pls_get_textdomain();

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'pls-recent-posts',
            'description' => 'A more powerful widget that displays the recent blog posts.'
		);

		/* Create the widget. */
        parent::__construct( "pls-recent-posts", 'Placester: Recent Blog Posts', $widget_options );

        /** Delete the widget cache if a post is modified, deleted, or a the theme is switched. */
		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	/**
	 * Outputs and filters the widget.
     * 
	 * @since 0.0.1
	 */
	function widget( $args, $instance ) {

        /** Get the the cached posts list. */
		$cache = wp_cache_get( 'pls_widget_recent_posts', 'widget' );

        /** If cache hasn't been set, initialize it. */
		if ( ! is_array( $cache ) )
			$cache = array();

        /** If cache has been set for this widget, echo it, and return. */
		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

        /** Start output buffering. */
        ob_start();


        list($args, $instance) = self::process_defaults($args, $instance);
        /** Extract the arguments into separate variables. */
		extract( $args, EXTR_SKIP );

        /** If conversion to non-negative integer results in 0, set the number of posts to 5. */
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 5;

        /** Get the posts. */
        $query = new WP_Query( array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish'
        ) );

        /** If there are posts... */
        if ( $query->have_posts() ) {

            /* If a title was input by the user, store it. */
            $widget_title = '';
            if ( !empty( $instance['title'] ) )
                $widget_title = $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

            /* Output the theme's $before_widget wrapper. */
            echo $before_widget;
            
            /** Will hold the combined posts html. */
            $widget_body = '';

            function custom_excerpt($length){
              global $post;
              $content = strip_tags($post->post_content);
              preg_match('/^\s*+(?:\S++\s*+){1,'.$length.'}/', $content, $matches);
              return $matches[0];
            }
            
            /** The loop. */
            while ( $query->have_posts() ) {

                /** Setup post data. */
                $query->the_post();

                /** This array will hold the html elements for each post and will be passed to the filters. */
                $post_html = $instance;
                unset( $post_html['title'] );

                $post_html['post_title'] = empty( $instance['post_title'] ) ? ''
                                           : pls_h_a( get_permalink(), esc_attr( get_the_title() ? get_the_title() : get_the_ID() ), array('class' => 'title') );

                $post_html['author'] = empty( $instance['author'] ) ? ''
                                       : sprintf( ' ' . 'by <span class="author">%1$s</span>', get_the_author() );

                $post_html['date'] = empty( $instance['date'] ) ? ''
                                     : sprintf( ' ' . 'on <time datetime="%1$s">%2$s</time>', get_the_date( 'Y-m-d' ), get_the_date() );
 
                $post_html['excerpt'] = empty( $instance['excerpt'] ) ? ''
                                        : pls_h_div( ( has_excerpt() ? get_the_excerpt() : '' ), array( 'class' => 'excerpt' ) );

                $post_html['read_more'] = empty( $instance['read_more'] ) ? ''
                                          : pls_h_a( get_permalink(), 'Read more', array( 'class' => 'read-more' ) );

                /** Combine the post information. */
                $post_item = pls_get_if_not_empty( $post_html['post_title'] ) . 
                    ( ! empty( $post_html['author'] ) || ! empty( $post_html['date'] ) ? 
                    pls_h_p( sprintf( 'Posted%1$s%2$s.', pls_get_if_not_empty( $post_html['author'] ), pls_get_if_not_empty( $post_html['date'] ) ), array( 'class' => 'meta p3' ) ) : 
                    '' ) .
                    pls_get_if_not_empty( $post_html['excerpt'] ) . 
                    pls_get_if_not_empty( $post_html['read_more'] ); 

                global $post;

                /** Wrap the post in an article element and filter its contents. */
                $post_item = pls_h('article', array('class' => 'recent-post-single', 'itemscope' => '', 'itemtype' => "http://schema.org/BlogPosting"), apply_filters( 'pls_widget_recent_posts_post_inner', $post_item, $post_html, $instance, $widget_id ));
                 
                /** Append the filtered post to the post list. */
                $widget_body .= apply_filters( 'pls_widget_recent_posts_post_outer', $post_item, $post_html, $instance, $widget_id );

            } /** while $query->have_posts() */ 

            /** Wrap the widget body in a section element. */
            $widget_body = pls_h(
                'section',
                array( 'class' => 'widget-inner', 'itemscope' => '', 'itemtype' => 'http://schema.org/Blog' ),
                /** Apply a filter on the combined list of posts. */
                apply_filters( 'pls_widget_recent_posts_inner', $widget_body, $instance, $widget_id )
            );

            /** Output and apply a filter on the whole widget. */
            echo apply_filters( 'pls_widget_recent_posts', $widget_title . $widget_body, $widget_title, $before_title, $after_title, $widget_body, $instance, $widget_id );

            /* Output the theme's $after_widget wrapper. */
            echo $after_widget;

            /** Reset the global $the_post as this query will have stomped on it. */	
            wp_reset_postdata();

        } /** if $query->have_posts() */ 

        /** Cache the widget contents */
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'pls_widget_recent_posts', $cache, 'widget' );
	}

    /**
     * Deletes the widget cache
     * 
     * @since 0.0.1
     */
	function flush_widget_cache() {
		wp_cache_delete( 'pls_widget_recent_posts', 'widget' );
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
     *
	 * @since 0.0.1
	 */
	function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
        $instance['post_title'] = ( isset( $new_instance['post_title'] ) ? 1 : 0 );
        $instance['author'] = ( isset( $new_instance['author'] ) ? 1 : 0 );
        $instance['date'] = ( isset( $new_instance['date'] ) ? 1 : 0 );
        $instance['excerpt'] = ( isset( $new_instance['excerpt'] ) ? 1 : 0 );
        $instance['read_more'] = ( isset( $new_instance['read_more'] ) ? 1 : 0 );
        $instance['number'] = absint( $new_instance['number'] );

        /** Delete the widget cache. */ 
        $this->flush_widget_cache();
  

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
     *
	 * @since 0.0.1
	 */
	function form( $instance ) {

		/** Set up the default form values. */
		$defaults = array(
			'title' => 'Latest Blog Posts',
            'post_title' => true,
            'author' => true,
            'date' => true,
            'excerpt' => true,
            'read_more' => true,
            'number' => 5,
		);

		/** Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

        /** Print the backend widget form. */
        echo pls_h_div(
            /** Print the Title input */
            pls_h_p( 
                pls_h_label( 
                    'Title' . ':' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'class' => 'widefat',
                            'id' => $this->get_field_id( 'title' ),
                            'name' => $this->get_field_name( 'title' ),
                            'value' => esc_attr( $instance['title'] )
                        ) 
                    ), 
                    $this->get_field_id( 'title' ) 
                ) 
            ) . 
            /** Print the Post Title checkbox */
            pls_h_p( 
                pls_h_label( 
                    pls_h_checkbox( 
                        checked( $instance['post_title'], true, false ), 
                        array(
                            'id' => $this->get_field_id( 'post_title' ),
                            'name' => $this->get_field_name( 'post_title' ),
                        ) 
                    ) . 
                    ' ' . 'Post Title', 
                    $this->get_field_id( 'post_title' ) 
                ) 
            ) . 
            /** Print the Author checkbox */
            pls_h_p( 
                pls_h_label( 
                    pls_h_checkbox( 
                        checked( $instance['author'], true, false ), 
                        array(
                            'id' => $this->get_field_id( 'author' ),
                            'name' => $this->get_field_name( 'author' ),
                        ) 
                    ) . 
                    ' ' . 'Author', 
                    $this->get_field_id( 'author' ) 
                ) 
            ) . 
            /** Print the Post Date checkbox */
            pls_h_p( 
                pls_h_label( 
                    pls_h_checkbox( 
                        checked( $instance['date'], true, false ), 
                        array(
                            'id' => $this->get_field_id( 'date' ),
                            'name' => $this->get_field_name( 'date' ),
                        ) 
                    ) . 
                    ' ' . 'Post Date', 
                    $this->get_field_id( 'date' ) 
                ) 
            ) . 
            /** Print the Excerpt checkbox */
            pls_h_p( 
                pls_h_label( 
                    pls_h_checkbox( 
                        checked( $instance['excerpt'], true, false ), 
                        array(
                            'id' => $this->get_field_id( 'excerpt' ),
                            'name' => $this->get_field_name( 'excerpt' ),
                        ) 
                    ) . 
                    ' ' . 'Excerpt', 
                    $this->get_field_id( 'excerpt' ) 
                ) 
            ) .
            /** Print the Read More checkbox */
            pls_h_p( 
                pls_h_label( 
                    pls_h_checkbox( 
                        checked( $instance['read_more'], true, false ), 
                        array(
                            'id' => $this->get_field_id( 'read_more' ),
                            'name' => $this->get_field_name( 'read_more' ),
                        ) 
                    ) . 
                    ' ' . 'Read more link', 
                    $this->get_field_id( 'read_more' ) 
                ) 
            ) . 
            /** Print the Number text input */
            pls_h_p( 
                pls_h_label( 
                    'Number of posts' . ': ' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'size' => 4,
                            'id' => $this->get_field_id( 'number' ),
                            'name' => $this->get_field_name( 'number' ),
                            'value' => esc_attr( $instance['number'] )
                        ) 
                    ),
                    $this->get_field_id( 'number' ) 
                ) 
            )  
            /** Print the Extra HTML textarea */
            // pls_h_p( 
                // pls_h_label( 
                    // 'Extra HTML' . ":" .
                    // pls_h( 
                        // 'textarea',
                        // array(
                            // 'class' => 'widefat',
                            // 'type' => 'text',
                            // 'rows' => 7,
                            // 'cols' => 20,
                            // 'id' => $this->get_field_id( 'extra' ),
                            // 'name' => $this->get_field_name( 'extra' ),
                            // 'value' => esc_textarea( $instance['extra'] ),
                        // ) 
                    // ), 
                    // $this->get_field_id( 'extra' ) 
                // ) 
            // ) 
        ); 
	}


    function process_defaults ($args, $instance) {

        /** Define the default argument array. */
        $arg_defaults = array(
            'title' => 'Latest Blog Posts',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<section id="pls-recent-posts-3" class="widget pls-recent-posts widget-pls-recent-posts" itemscope itemtype="http://schema.org/Blog">',
            'after_widget' => '</section>',
            'widget_id' => ''
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $arg_defaults );


        /** Define the default argument array. */
        $instance_defaults = array(
            'photo' => true,
            'name' => true,
            'description' => true,
            'email' => true,
            'phone' => true,
            'width' => 100,
            'height' => 75,
            'widget_id' => ''
        );

        /** Merge the arguments with the defaults. */
        $instance = wp_parse_args( $instance, $instance_defaults );


        return array($args, $instance);
    }
} // end of class
