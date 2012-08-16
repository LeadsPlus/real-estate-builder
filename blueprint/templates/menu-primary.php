<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */


   	pls_do_atomic( 'before_menu_primary' ); ?>
	<nav class="main-nav grid_12 alpha" role="navigation">

        <?php pls_do_atomic( 'open_menu_primary' ); ?>

        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '', 'menu_class' => 'primary', 'link_after' => '<span></span>' ) ); ?>

        <?php pls_do_atomic( 'close_menu_primary' ); ?>

	</nav><!-- #menu-primary .menu-container -->
    <?php pls_do_atomic( 'after_menu_primary' ); ?>


