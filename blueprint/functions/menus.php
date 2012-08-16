<?php
/**
 * The menus functions deal with registering nav menus within WordPress. 
 * Theme * developers may use the default menu(s) provided by the framework 
 * within their own themes, decide not to use them, or register additional menus.
 *
 * @package PlacesterBlueprint
 * @subpackage Functions
 */

/* Register the menus. */
add_action( 'init', 'pls_register_menus', 1 );

/**
 * Registers the the framework's default menus. By default, they are 
 * registered.
 *
 * @since 0.0.1
 * @uses register_nav_menu() Registers a nav menu with WordPress.
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menu
 */
function pls_register_menus() {

	/** Get theme-supported menus. */
	$menus = get_theme_support( 'pls-menus' );

	/** If there is no array of menu IDs, return. */
    if ( ! is_array( $menus[0] ) )
        return;

	/** Register the 'primary' menu. */
    if ( in_array( 'primary', $menus[0] ) )
		register_nav_menu( 'primary', 'Primary Menu');

	/** Register the 'subsidiary' menu. */
    if ( in_array( 'subsidiary', $menus[0] ) )
		register_nav_menu( 'subsidiary', 'Subsidiary Menu' );
}