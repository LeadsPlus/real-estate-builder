<?php

/**
 * Admin interface: Edit listing form
 * "Edit images" Popup
 */

$property_id = $_REQUEST['id'];

$error_message = '';
$error = false;
$success_message = '';

try
{
    if (isset($_REQUEST['delete']))
    {
        placester_property_image_delete($property_id, $_REQUEST['delete']);
        $success_message = 'Image was deleted';
    }
    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0)
    {
        for ($n = 0; $n < count($_FILES['images']['name']); $n++)
        {
            if (strlen($_FILES['images']['name'][$n]) <= 0)
                continue;

            placester_property_image_add($property_id, 
                $_FILES['images']['name'][$n],
                $_FILES['images']['type'][$n],
                $_FILES['images']['tmp_name'][$n]);
        }
        $success_message = 'Images were successfully added';
    }
}
catch (Exception $e) 
{
    $error_message = $e->getMessage();
    $error = true;
}

$form_url = 'admin.php?page=placester_properties&id=' . $property_id . 
    '&popup=images';
$p = placester_property_get($property_id);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head> 
  <?php
  wp_admin_css( 'css/global' );
  wp_admin_css();
  wp_admin_css( 'css/colors' );
  wp_admin_css( 'css/ie' );
  wp_enqueue_script('utils');

  do_action('admin_print_styles');
  do_action('admin_print_scripts');
  do_action('admin_head');
  ?>
  <script>

  jQuery(document).ready(function()
  {
      jQuery('img').click(function() 
      {
          window.parent.show_image(jQuery(this).attr('src'));
      });
  });
  
  </script>
</head>
<body style="padding:10px 0 10px 10px;height:90%">
  <?php

  if ($error)
      placester_error_message($error_message);
  if (!empty($success_message))
      placester_success_message($success_message);
  ?>

  <h3>Images</h3>
  <?php
  foreach ($p->images as $i)
  {
      ?>
      <div style="float: left; margin-right: 10px">
        <div>
          <img src="<?php echo $i->url ?>" width="100" style="cursor: pointer"/>
        </div>
        <div>
          <a href="<?php echo $form_url ?>&delete=<?php echo urlencode($i->url) ?>">
            Delete
          </a>
        </div>
      </div>
      <?php
  }
  ?>
  <div style="clear:both"></div>

  <form method="post" action="<?php echo $form_url ?>"
    enctype="multipart/form-data">
    <h3>Add Images</h3>
    <input type="file" name="images[]" class="multi" />
    <input type="submit" name="add_images" value="Add Images" class="button-primary" />
  </form>
</body>