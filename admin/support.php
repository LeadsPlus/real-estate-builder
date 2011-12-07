<?php

/**
 * Admin interface: Support tab
 * Entry point
 */

$view_success = false;
$placester_support_email = 'wordpress-plugin-support@placester.com';



/**
 * Adds attachments to email sent
 */
function placester_phpmailer_init()
{
    global $phpmailer;

    // Attach phpinfo
    ob_start();
    phpinfo();
    $php_info = ob_get_contents();
    ob_end_clean();
    $phpmailer->AddStringAttachment($php_info, 'php_info.html');

    // Attach other files
    if (!empty($_FILES['files'])) 
    {
        $files = (array) $_FILES['files'];
        for ($i = 0, $l = count($files); $i < $l; $i++) 
        {
            if (isset($files['tmp_name'][$i]) && isset($files['name'][$i]) && 
                isset($files['error'][$i]) && $files['error'][$i] == UPLOAD_ERR_OK)
            {
                $phpmailer->AddAttachment($files['tmp_name'][$i], $files['name'][$i]);
            }
        }
    }
}



if (isset($_REQUEST['apply']))
{        
    // Add attachments
    $attachments = array();
   
    // Attach templates
    if (isset($_REQUEST['templates']))
    {
        foreach ($_REQUEST['templates'] as $template) 
        {
            if (!empty($template))
                $attachments[] = $template;
        }
    }
    
    /**
     * Get body contents
     */
    ob_start();
    require('support/email.php');
    $body = ob_get_contents();
    ob_end_clean();
    
    // Send email
    $subject = sprintf('[placester %s] #%s: %s', $_REQUEST['type'], 
        date('YmdHi'), $_REQUEST['subject']);
    
    $headers = array(
        sprintf('From: "%s" <%s>', addslashes($_REQUEST['name']), $_REQUEST['email']), 
        sprintf('Reply-To: "%s" <%s>', addslashes($_REQUEST['name']), $_REQUEST['email']), 
        'Content-Type: text/html; charset=UTF-8'
    );
    
    add_action('phpmailer_init', 'placester_phpmailer_init');

    $result = wp_mail($placester_support_email, 
        $_REQUEST['subject'], $body, implode("\n", $headers), $attachments);
    
    // Remove temporary files
    foreach ($attachments as $attachment) 
    {
        if (strstr($attachment, sys_get_temp_dir()) !== false)
            @unlink($attachment);
    }
    
    // Ok
    $view_success = true;
}

?>
<div class="wrap">
  <?php placester_admin_header('placester_support') ?>

  <h3>Support</h3>
  <?php
  if ($view_success)
      placester_success_message('Your request has been sent.');
  ?>
  <p>
    Request professional services, suggest a feature or submit a bug using the form below:
  </p>

  <form id="placester_form" action="admin.php?page=placester_support" 
    enctype="multipart/form-data" method="post" id="placester_form">
    <?php placester_postbox_container_header(); ?>
    <div id="support_container">
      <?php require("support/intro.php"); ?>
    </div>
    <?php placester_postbox_container_footer(); ?>
  </form>
</div>
