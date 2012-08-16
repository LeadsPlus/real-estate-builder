<?php
/**
 *  Enqueue the styles only if the PLS_LOAD_STYLES constant is not set to 
 *  false. This allows developers to stop style loading by defining it in 
 *  'functions.php'
 */
if ( !defined( 'PLS_LOAD_STYLES' ) || ( defined( 'PLS_LOAD_STYLES' ) && ( PLS_LOAD_STYLES === true ) ) ) {

    /**
     * Registers and enqueues styles
     *
     * @since 0.0.1
     */
    add_action( 'template_redirect', 'pls_styles' );
    function pls_styles() {

        // wp_enqueue_style( 'pls-default', '/css/style.css' . get_bloginfo( 'stylesheet_url' ) );
				
				// Required by WordPress
				if ( ! isset( $content_width ) ) $content_width = 900;
        
				/**
         *  If the plugin is inactive, or the api key is missing from the 
         *  plugin enqueue a css file that deals with styling the plugin 
         *  notifications. Accompanied by plugin-nags.js.
         */
        if ( pls_has_plugin_error() ) {
            wp_enqueue_style( 'pls-plugin-nags', trailingslashit( PLS_CSS_URL ) . 'styles/plugin-nags.css' );
        }

        // wp_enqueue_style('contact-widget', trailingslashit(PLS_CSS_URL) . 'styles/contact.widget.ajax.css');

        if ( get_theme_support( 'pls-default-normalize' ) ) {
            // wp_enqueue_style( 'normalize', trailingslashit( PLS_CSS_URL ) . 'styles/normalize.css' );
        }

        if ( get_theme_support( 'pls-default-960' ) ) {
            // wp_enqueue_style( 'pls-default-960', trailingslashit( PLS_CSS_URL ) . 'styles/960.css' );
        }
            
        /** Include default layout only if supported. */
        if ( get_theme_support( 'pls-default-css' ) ) {
            // wp_enqueue_style( 'pls-default-css', trailingslashit( PLS_CSS_URL ) . 'styles/all.css' );
        } else {
            if ( get_theme_support( 'pls-default-css-blog' ) ) {
                // wp_enqueue_style( 'pls-default-css-blog', trailingslashit( PLS_CSS_URL ) . 'styles/blog.css' );
            }
            if ( get_theme_support( 'pls-default-css-forms' ) ) {
                // wp_enqueue_style( 'pls-default-css-forms', trailingslashit( PLS_CSS_URL ) . 'styles/forms.css' );
            }
            if ( get_theme_support( 'pls-default-css-header' ) ) {
                // wp_enqueue_style( 'pls-default-css-header', trailingslashit( PLS_CSS_URL ) . 'styles/header.css' );
            }
            if ( get_theme_support( 'pls-default-css-listings-detail' ) ) {
                // wp_enqueue_style( 'pls-default-css-listings-detail', trailingslashit( PLS_CSS_URL ) . 'styles/css-listings-detail.css' );
            }
            if ( get_theme_support( 'pls-default-css-nav' ) ) {
                // wp_enqueue_style( 'pls-default-css-nav', trailingslashit( PLS_CSS_URL ) . 'styles/nav.css' );
            }   
            if ( get_theme_support( 'pls-default-css-widgets' ) ) {
                // wp_enqueue_style( 'pls-default-css-widgets', trailingslashit( PLS_CSS_URL ) . 'styles/widgets.css' );
            }
        }
    }
}

