<?php

/**
 * Admin interface: Support tab
 * Script called by AJAX when question type is chosen
 */

$action = $_REQUEST['ajax_action'];

echo '<input type="hidden" name="type" value="' . htmlspecialchars($action) . '" />';

if ($action == 'intro')
    require('support/intro.php');
if ($action == 'bug_report')
    require('support/bug_report.php');
if ($action == 'new_feature')
    require('support/new_feature.php');
