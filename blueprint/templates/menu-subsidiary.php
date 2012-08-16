<?php
/**
 * Subsidiary Menu Template
 *
 * Displays the Subsidiary Menu if it has active menu items.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */

if ( has_nav_menu( 'subsidiary' ) ) : ?>

    <?php pls_do_atomic( 'before_menu_subsidiary' ); ?>

	<section class="footer-nav" role="navigation">

        <?php pls_do_atomic( 'open_menu_subsidiary' ); ?>

        <?php wp_nav_menu( array( 'container'=> '','theme_location' => 'subsidiary',  'menu_class' => '', 'link_after' => '<span></span>' ) ); ?>

        <?php pls_do_atomic( 'close_menu_subsidiary' ); ?>

	</section><!-- #menu-subsidiary .menu-container -->

    <?php pls_do_atomic( 'after_menu_subsidiary' ); ?>

<?php endif; ?>

