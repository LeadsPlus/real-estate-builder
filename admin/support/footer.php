<?php

/**
 * Admin interface: Support tab
 * Common Footer used by different types of "support requests"
 */

?>
<?php placester_postbox_header('Notes'); ?>
<ul>
  <li>All submitted data will not be saved and is used solely for the purposes your support request. You will not be added to a mailing list, solicited without your permission, nor will your site be administered after this support case is closed.</li>
  <li>Instead of providing your primary administrative or <acronym title="Secure Shell">SSH</acronym> / <acronym title="File Transfer Protocol">FTP</acronym> accounts, create a new administrator account that can be disabled when the support case is closed.</li>
  <li>Please add the domain placester.com to your <a href="http://en.wikipedia.org/wiki/Whitelist" target="_blank">email whitelist</a> as soon as possible.</li>
</ul>
<?php placester_postbox_footer(); ?>

<?php placester_postbox_container_footer(); ?>

<p>
  <input type="submit" name="apply" class="button-primary" value="Submit request" />
  <input id="support_cancel" type="button" value="Cancel" class="button-primary" />
</p>
