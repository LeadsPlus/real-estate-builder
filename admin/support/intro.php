<?php

/**
 * Admin interface: Support tab
 * Intro form displayed at the start to allow to chose "support request" type
 */

?>
<?php placester_postbox_header('Choose request type'); ?>
<table class="form-table">
  <tr>
    <th valign="top"><label>Request type:</label></th>
    <td>
      <select id="support_request_type" name="request_type">
        <option value="">-- Choose Type --</option>
        <option value="new_feature">Suggest A New Feature</option>
        <option value="bug_report">Submit A Bug Report</option>
      </select>
    </td>
  </tr>
</table>
<?php placester_postbox_footer(); ?>