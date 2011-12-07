<?php

/**
 * Admin interface: Add listing tab
 * "Add listing" form
 */

?>
<div class="wrap">
  <?php placester_admin_header('placester_property_add') ?>

  <h3>Add Listing</h3>
    <?php if ($provider = placester_provider_check()): ?>
        <div style="margin: 50px 50px 0 50px; padding: 10px; border: 2px solid #E6DB55; background: lightYellow; margin-bottom: 10px">
            <p style="margin-bottom: 0px"><?php echo 'Your account is being synced with ' . $provider["name"] . ' and so you can\'t create new listings inside this website. However, any property you create in <a href="'.$provider["url"].'">'.$provider["name"].'</a> will appear here automatically.'; ?></p>
        </div>
    <?php else: ?>
        <form method="post" id="property_add_form" action="admin.php?page=placester_property_add"
          enctype="multipart/form-data">  
          <?php
          if (strlen($error_message) > 0)
            placester_error_message($error_message);

          placester_postbox_container_header();
          property_form($p, $v);
          box_images();
          placester_postbox_container_footer();
          ?>

          <p class="submit">
            <input type="submit" name="add_finish" class="button-primary" 
              value="Add Property" />
          </p>
        </form>
    <?php endif ?>
</div>
