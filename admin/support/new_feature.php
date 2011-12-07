<?php

/**
 * Admin interface: Support tab
 * "Request feature" form returned by AJAX
 */

$url = get_home_url();
$name = '';
$email = '';
$twitter = '';
$subject = '';
$description = '';
$phone = '';
$forum_url = '';

?>

<?php placester_postbox_header('Required information'); ?>
<table class="form-table">
  <tr>
    <th valign="top">Request type:</th>
    <td>Suggest A New Feature</td>
  </tr>
  <tr>
    <th><label for="support_url">Blog <acronym title="Uniform Resource Locator">URL</acronym>:</label></th>
    <td><input id="support_url" type="text" name="url" value="<?php echo htmlspecialchars($url); ?>" size="80" /></td>
  </tr>
  <tr>
    <th><label for="support_name">Name:</label></th>
    <td><input id="support_name" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" size="80" /></td>
  </tr>
  <tr>
    <th><label for="support_email">E-Mail:</label></th>
    <td><input id="support_email" type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" size="80" /></td>
  </tr>
  <tr>
    <th><label for="support_twitter">Twitter ID:</label></th>
    <td><input id="support_twitter" type="text" name="twitter" value="<?php echo htmlspecialchars($twitter); ?>" size="80" /></td>
  </tr>
  <tr>
    <th><label for="support_subject">Subject:</label></th>
    <td><input id="support_subject" type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>" size="80" /></td>
  </tr>
  <tr>
    <th valign="top"><label for="support_description">Issue description:</label></th>
    <td><textarea id="support_description" name="description" cols="70" rows="8"><?php echo htmlspecialchars($description); ?></textarea></td>
  </tr>
</table>
<?php placester_postbox_footer(); ?>

<?php placester_postbox_header('Additional information'); ?>
<table class="form-table">
  <tr>
    <th><label for="support_phone">Phone:</label></th>
    <td><input id="support_phone" type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" size="80" /></td>
  </tr>
  <tr>
    <th><label for="support_forum_url">Forum Topic URL:</label></th>
    <td><input id="support_forum_url" type="text" name="forum_url" value="<?php echo htmlspecialchars($forum_url); ?>" size="80" /></td>
  </tr>
</table>
<?php placester_postbox_footer(); ?>

<?php 
require('footer.php'); 
?>
