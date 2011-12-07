<?php

/**
 * Admin interface: Get Themes tab
 * Intro form
 */

$type = (isset($_REQUEST['type']) ? $_REQUEST['type'] : '');
$term = (isset($_REQUEST['term']) ? $_REQUEST['term'] : '');

?>
<p class="install-help"><?php _e('Search for themes by keyword, author, or tag.') ?></p>

<form id="search-themes" method="post" action="admin.php?page=placester_themes">
  <select name="type" id="typeselector">
    <option value="term" <?php selected('term', $type) ?>><?php _e('Term'); ?></option>
    <option value="author" <?php selected('author', $type) ?>><?php _e('Author'); ?></option>
    <option value="tag" <?php selected('tag', $type) ?>><?php _ex('Tag', 'Theme Installer'); ?></option>
  </select>
  <input type="text" name="s" size="30" value="<?php echo esc_attr($term) ?>" />
  <input type="submit" name="search" value="<?php esc_attr_e('Search'); ?>" 
    class="button" />
</form>

<h4><?php _e('Feature Filter') ?></h4>
<form method="post" action="admin.php?page=placester_themes">
  <p class="install-help"><?php _e('Find a theme based on specific features') ?></p>
  <div class="feature-filter">
    <?php
    $feature_list = install_themes_feature_list();

    $trans = array('Colors' => __('Colors'), 'black' => __('Black'), 'blue' => __('Blue'), 'brown' => __('Brown'),
        'green' => __('Green'), 'orange' => __('Orange'), 'pink' => __('Pink'), 'purple' => __('Purple'), 'red' => __('Red'),
        'silver' => __('Silver'), 'tan' => __('Tan'), 'white' => __('White'), 'yellow' => __('Yellow'), 'dark' => __('Dark'),
        'light' => __('Light'), 'Columns' => __('Columns'), 'one-column' => __('One Column'), 'two-columns' => __('Two Columns'),
        'three-columns' => __('Three Columns'), 'four-columns' => __('Four Columns'), 'left-sidebar' => __('Left Sidebar'),
        'right-sidebar' => __('Right Sidebar'), 'Width' => __('Width'), 'fixed-width' => __('Fixed Width'), 'flexible-width' => __('Flexible Width'),
        'Features' => __('Features'), 'custom-colors' => __('Custom Colors'), 'custom-header' => __('Custom Header'), 'theme-options' => __('Theme Options'),
        'threaded-comments' => __('Threaded Comments'), 'sticky-post' => __('Sticky Post'), 'microformats' => __('Microformats'),
        'Subject' => __('Subject'), 'holiday' => __('Holiday'), 'photoblogging' => __('Photoblogging'), 'seasonal' => __('Seasonal'),
    );

    foreach ((array)$feature_list as $feature_name => $features)
    {
        if (isset($trans[$feature_name]))
            $feature_name = $trans[$feature_name];
        $feature_name = esc_html($feature_name);

        echo '<div class="feature-name">' . $feature_name . '</div>';
        echo '<ol style="float:left;width:725px;" class="feature-group">';

        foreach ($features as $feature)
        {
            $feature_name = $feature;
            if (isset($trans[$feature]))
                $feature_name = $trans[$feature];
            $feature_name = esc_html($feature_name);
            $feature = esc_attr($feature);
            ?>
            <li>
              <input type="checkbox" name="features[<?php echo $feature; ?>]" 
                id="feature-id-<?php echo $feature; ?>" 
                value="<?php echo $feature; ?>" />
              <label for="feature-id-<?php echo $feature; ?>"><?php echo $feature_name; ?></label>
            </li>
            <?php
        }
        
        echo '</ol>';
        echo '<br class="clear" />';
    }
    ?>
  </div>
  <br class="clear" />
  <p>
    <input type="submit" name="search" 
      value="<?php esc_attr_e('Find Themes'); ?>" class="button" />
  </p>
</form>
