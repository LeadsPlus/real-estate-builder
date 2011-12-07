<?php

/**
 * Admin interface: Dashboard tab
 * Entry point
 */

?>
<?php placester_admin_header('placester_dashboard') ?>
<div style="width: 970px" class="wrap"> 
  <?php placester_postbox_container_header('width:640px'); ?>
  	<?php placester_postbox('lead-sources', 'Lead Traffic', "<div id='line_chart_wrapper'><div style='height: 275px' id='linechart'></div></div>") ?>
  	<?php placester_postbox('leads', 'Leads', "<div id='leads_container'> </div>") ?>
  <?php placester_postbox_container_footer(); ?>
  
  <?php placester_postbox_container_header('width:320px'); ?>
  	<?php placester_postbox('lead-sources', 'Lead Sources', "<div style='margin-left: 50px; height: 235px' id='piechart'></div>") ?>
  	<?php placester_postbox('recent_news', 'Placester News', '') ?>
  	<?php placester_postbox('about-us', 'About Us', '<strong>Placester builds tools that create value for the real estate industry</strong><br /><p>We focus primarily on providing massive distribution, premium listing services, and deep analytics to help real estate companies advertise and generate business. We care deeply about improving the real estate industry through the use of innovative technology. Learn more about us <a href="http://placester.com/about/">here</a></p>') ?>
  <?php placester_postbox_container_footer(); ?>

  <div class="clear"></div>

</div>
<?php if (get_option('placester_company_id') && get_option('placester_api_key')): ?>
    <script>
        jQuery(document).ready(function($) {
           line_widget("<?php echo get_option('placester_api_id'); ?>", 'linechart', "<?php echo sha1("line_leads" . get_option('placester_api_id') . get_option('placester_api_key')); ?>", "<?php echo get_option('placester_company_id') ?>"); 
           pie_widget("<?php echo get_option('placester_api_id'); ?>", 'piechart', "<?php echo sha1("pie_publishers" . get_option('placester_api_id') . get_option('placester_api_key')); ?>", "<?php echo get_option('placester_company_id') ?>"); 
           leads_widget("<?php echo get_option('placester_api_id'); ?>", 'leads_container', "<?php echo sha1("leads" . get_option('placester_api_id') . get_option('placester_api_key')); ?>", "<?php echo get_option('placester_company_id') ?>"); 
        });
    </script>    
<?php else: ?>
    <script>

    jQuery(document).ready(function($) {

        show_widget_error('linechart', '<?php echo plugins_url(); ?>/placester/images/line-null.png', 'No traffic data yet. Enter an API key in the <a href="admin.php?page=placester_settings">settings</a> tab or an email address in the <a href="admin.php?page=placester_contact">personal</a> tab', 'width:300px;margin-left: -150px');
        show_widget_error('leads_container', '<?php echo plugins_url(); ?>/placester/images/leads-null.png', 'No leads yet. Enter an API key in the <a href="admin.php?page=placester_settings">settings</a> tab or an email address in the <a href="admin.php?page=placester_contact">personal</a> tab', 'width:300px;margin-left: -150px');
        show_widget_error('piechart', '<?php echo plugins_url(); ?>/placester/images/pie-null.png', 'No traffic data yet. Enter an API key in the <a href="admin.php?page=placester_settings">settings</a> tab or an email address in the <a href="admin.php?page=placester_contact">personal</a> tab', 'width:200px;margin:100px 0 0 -100px');

    });

    </script>
<?php endif ?>
