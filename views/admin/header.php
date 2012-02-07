<style type="text/css">
  .wrapper .pls_search_form {
    float: left;
    padding: 10px
  }
</style>

<?php function placester_admin_header( $current_page, $title_postfix = '' ) {
   
    /**
     *      Check to see if the agency is verified.
     */
    // placester_verified_check()

    global $i_am_a_placester_theme;

    $placester_admin_options = get_option('placester_admin_options');

    if ( !$i_am_a_placester_theme && !isset( $placester_admin_options['placester-theme-update'] ) && current_user_can( 'switch_themes' ) ) {
        placester_warning_message('<strong>You are currently running the Placester plugin, but not with a Placester theme</strong>. You\'ll likely have a better experience with a compatible theme.  <a target="_blank" href="https://placester.com/themes/">Find a compatible theme here.</a>', '', true, 'placester-theme-update');
    }

    if ( !$i_am_a_placester_theme && !isset( $placester_admin_options['placester-theme-problem'] ) && current_user_can( 'switch_themes' ) ) {
        placester_warning_message('<strong>Having issues with a Placester theme?</strong> please checkout our <a target="_blank" href="https://placester.com/themes/">theme gallery</a> for the latest updates. If you are having a problem it\'s likely been addressed there.', '', true, 'placester-theme-problem');
    }

    global $wp_rewrite;

    if ( !$wp_rewrite->using_permalinks() && !isset( $placester_admin_options['placester-theme-links'])) {
        placester_warning_message(
            'For best performance <input type="button" class="button " value="Enable Fancy Permalinks" onclick="document.location.href = \'/wp-admin/options-permalink.php\';">' .
            'following the directions appropriate for your ' .
            '<a href="http://codex.wordpress.org/Using_Permalinks#Choosing_your_permalink_structure">' .
            'WordPress ' . get_bloginfo( 'version' ) .
            '</a>', null, true, 'placester-theme-links');
    }

    echo "<div class='clear'></div>";  

    ?>
    <div id="icon-options-general" class="icon32 placester_icon"><br /></div>
    <h2 id="placester-admin-menu">
      <?php
      $current_title = '';
      $v = '';

      global $submenu;
      foreach ( $submenu['placester'] as $i ) {
          $title = $i[0];
          $slug = $i[2];
          $style = '';
          if ( $slug == $current_page ) {
              $style = 'nav-tab-active';
              $current_title = $title;
          }

          $v .= "<a href='admin.php?page=$slug' style='font-size: 15px' class='nav-tab $style'>$title</a>";
      }

      echo $current_title;
      echo $title_postfix;
      echo '&nbsp;&nbsp;&nbsp;';
      echo $v;
      ?>
    </h2>
    <?php
} ?>

<?php placester_admin_header('test')	  ?>
<div class="wrapper">	
		
  

