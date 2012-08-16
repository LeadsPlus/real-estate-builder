<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will 
 * display a generic message.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 * @link http://codex.wordpress.org/Creating_an_Error_404_Page
 */
?>
<section class="left-content">

	<section id="post-0" <?php post_class() ?>>

      <h2 class="error-404-title entry-title">Not Found</h2>

      <section class="entry-content">

 
        <p>You navigated to a page that doesn't exist.</p>

        <code><?php echo home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) ?></code>

        <p>Please use the navigation bar to find what you were looking for. Otherwise, feel free to email to the site owner, <a href="mailto:<?php echo @pls_get_option('pls-company-email') ?>"><?php echo @pls_get_option('pls-company-email') ?></a>, with any questions.</p>

      </section><!-- .entry-content -->

  </section>

</section>
