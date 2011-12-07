<?php

/**
 * Admin interface: Edit listing form
 * Form for editing property
 */

$images_url = plugins_url('/images', dirname(__FILE__));

?>
<script>

jQuery(document).ready(function()
{
    jQuery('a.lightbox').lightBox(
    {
        imageLoading: '<?php echo $images_url ?>/lightbox-ico-loading.gif',
        imageBtnPrev: '<?php echo $images_url ?>/lightbox-btn-prev.gif',
        imageBtnNext: '<?php echo $images_url ?>/lightbox-btn-next.gif',
        imageBtnClose: '<?php echo $images_url ?>/lightbox-btn-close.gif',
        imageBlank: '<?php echo $images_url ?>/lightbox-blank.gif',
    });
});

</script>

<div class="wrap">
  <?php placester_admin_header('placester_properties') ?>

    <div style="position: relative">
        <h3>Edit Listing</h3>
        <a class="button view_listing" href="<?php echo site_url( '/listing/' . $property_id ); ?>" target="_blank">View listing</a>
        <a class="craiglist_template button" href="admin.php?page=placester_properties&craigslist_template=1&id=<?php echo $_GET['id'] ?>">Post to Craigslist</a>
    </div>
      <?php if ($provider = placester_provider_check()): ?>
          <div style="margin: 50px 50px 0 50px; padding: 10px; border: 2px solid #E6DB55; background: lightYellow; margin-bottom: 10px">
              <p style="margin-bottom: 0px"><?php echo 'Your account is being synced with ' . $provider["name"] . ' and so you can\'t create new listings inside this website. However, any property you create in <a href="'.$provider["url"].'">'.$provider["name"].'</a> will appear here automatically.'; ?></p>
          </div>
      <?php else: ?>
      <form method="post" action="admin.php?page=placester_properties&id=<?php echo $property_id ?>">
        <?php
        if (strlen($error_message) > 0)
          placester_error_message($error_message);

        placester_postbox_container_header();
        property_form($p, $v);
?>
        <p class="submit" style="padding: 0; margin: 0 0 20px 0;">
          <input type="submit" name="edit_finish" class="button-primary" 
            value="Modify Listing" />
        </p>
<?php
        uploadify_box($p);
        placester_postbox_container_footer();
        ?>

      </form>
    <?php endif ?>
    
  <a id="lightbox_link" class="lightbox"</a>
</div>
