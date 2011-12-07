<?php

/**
 * Admin interface: Update tab
 */

$plugin_name = 'placester/placester.php';

//
// Check for updates
//
if (array_key_exists('check_updates', $_POST))
{
    set_site_transient('update_plugins', new stdClass);
    wp_update_plugins();    
}

//
// Load current state
//
$last_check = 'never';
$new_version = 'n/a';

$update_info = get_site_transient('update_plugins');
if (is_object($update_info))
{
    $last_check = strftime('%B %d, %Y %H:%M:%S', $update_info->last_checked);
    if (isset($update_info->response[$plugin_name]))
        $new_version = $update_info->response[$plugin_name]['new_version'];
}

// Find current version
$plugins = get_plugins();
if (isset($plugins[$plugin_name]))
    $current_version = $plugins[$plugin_name]['Version'];
else
    $current_version = 'n/a';

?>
<div class="wrap">
  <?php placester_admin_header('placester_update') ?>

  <form method="post" action="admin.php?page=placester_update">
    <table class="form-table">
      <tr>
        <th>Current version:</th>
        <td><?php echo $current_version ?></td>
      </tr>
      <tr>
        <th>Version available by update:</th>
        <td><?php echo $new_version ?></td>
      </tr>
      <tr>
        <th>Last check:</th>
        <td><?php echo $last_check ?></td>
      </tr>
    </table>

    <p class="submit">
      <input type="submit" name="check_updates" class="button-primary" 
        value="Check for updates now" />
    </p>
  </form>
</div>