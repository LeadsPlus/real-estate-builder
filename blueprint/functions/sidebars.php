<?php
/**
 * Sets up the default framework sidebars if the theme supports them. Theme 
 * developers may choose to use or not these sidebars, create new sidebars, 
 * or unregister individual sidebars. A theme must register support for 
 * 'pls-sidebars' to use them.
 *
 * @package PlacesterBlueprint
 * @subpackage Functions
 */

/** Register widget areas. */
add_action( 'widgets_init', 'pls_register_sidebars' );

/**
 * Register the default framework sidebars. Theme developers may optionally 
 * choose to support these sidebars within their themes or add more custom 
 * sidebars to the mix.
 *
 * @since 0.0.1
 * @uses register_sidebar() Registers a sidebar with WordPress.
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function pls_register_sidebars() {

	/** Get theme-supported sidebars. */
    $sidebar_support = get_theme_support( 'pls-sidebars' );

	/** If there is no array of sidebars IDs, return. */
    if ( ! is_array( $sidebar_support[0] ) )
        return;

	/** Set up the primary sidebar arguments. */
	$sidebars[] = array(
		'id' => 'primary',
		'name' =>  'Main Sidebar',
		'description' => 'The main (primary) widget area, most often used as a sidebar.',
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);

/** Set up the primary sidebar arguments. */
	$sidebars[] = array(
		'id' => 'listings-search',
		'name' => 'Listings Search Sidebar',
		'description' => 'The main (primary) widget area, most often used as a sidebar.',
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);

//    pls_dump($sidebar_support);

    // loop through and create sidebars
    foreach ($sidebars as $sidebar) {
        if (in_array($sidebar['id'], $sidebar_support[0])) {
            register_sidebar( $sidebar );
        }
    }

}
