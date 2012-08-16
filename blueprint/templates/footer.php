<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the bottom of the file. It is used mostly as a closing wrapper, which is opened with the 
 * header.php file. It also executes some key functions needed by the theme, child themes, and plugins.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
 ?>

<?php pls_do_atomic( 'before_footer' ); ?>

    <footer class="grid_12">

      <?php pls_do_atomic( 'open_footer' ); ?>

      <div class="wrapper">

        <?php get_template_part( 'menu', 'subsidiary' ); ?>

        <?php pls_do_atomic( 'footer' ); ?>

      </div><!-- .wrapper -->

      <?php if ( is_home() ) { ?>
        <?php PLS_Listing_Helper::get_compliance(array('context' => 'listings', 'agent_name' => false, 'office_name' => false)); ?>
      <?php } ?>

      <?php if ( is_page( 'Listings' ) || is_page( 'listings' ) || is_page( 'Open Houses' ) ) { ?>
        <?php PLS_Listing_Helper::get_compliance(array('context' => 'search', 'agent_name' => false, 'office_name' => false)); ?>
      <?php } ?>

      <?php pls_do_atomic( 'close_footer' ); ?>

    </footer>

    <?php pls_do_atomic( 'after_footer' ); ?>

  </div> <!-- #container -->

<?php pls_do_atomic( 'close_container' ); ?>

<?php wp_footer(); ?>

<?php pls_do_atomic( 'close_body' ); ?>

</body>

</html>
