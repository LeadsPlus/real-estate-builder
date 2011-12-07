<?php

/**
 * Admin interface: Contact tab
 * Entry point
 */
include('contact_parts.php');

$api_key = get_option('placester_api_key');

?>
<div class="wrap">
  <?php placester_admin_header('placester_contact') ?>

  <form method="post" action="admin.php?page=placester_contact" id="placester_form" enctype="multipart/form-data">
    <?php
    placester_postbox_container_header();

    if (strlen($api_key) <= 0) {
        // Used as a flag for save buttons
        define('CONTACT_SIGNUP_FORM', true);
        include('contact_signup.php');                
    } else {
        define('CONTACT_SIGNUP_FORM', false);
        include('contact_edit.php');        
    }

    placester_postbox_container_footer();
    ?>
  </form>
</div>
