<?php

/**
 * Admin interface: Settings tab
 * Utilities
 */

/**
 * Cuts array with possible values
 *
 * @param array $array
 * @param array $possible_values
 */
function cut_if_fullset(&$array, $possible_values)
{
    foreach ($possible_values as $key => $value)
    {
        if (!in_array($key, $array))
            return;
    }

    $array = array();
}


/**
 * Prints dropdown in html <table> row
 *
 * @param string $label
 * @param string $option_name
 * @param array $possible_values
 * @param string $default_value
 * @param string $description
 */
function row_dropdown($label, $option_name, $possible_values, $default_value='', $description='')
{
    $value = get_option($option_name);
    $value = empty($value) ? $default_value : $value;

    ?>
    <tr valign="top">
      <th><?php echo $label ?></th>
      <td>
        <select class="heading form-input-tip" 
          name="<?php echo $option_name ?>" 
          id="dd_<?php echo $option_name ?>">
          <?php 
            foreach ($possible_values as $key => $name)
            {
                if (!is_array($value))
                    $is_selected = ($key == $value);
                else
                {
                    $is_selected = false;
                    $possible_values_exploded = explode(',', $key);

                    if ($possible_values_exploded == $value) {
                        $is_selected = true;
                    }
                }

                echo '<option value="' . $key . '"' . ($is_selected ? ' selected' : '') . '>' . $name . '</option>';
            }
            ?>  
        </select>
        <?php 
        if (strlen($description) > 0)
            echo '<br /><label for="dd_' . $option_name . '"><span class="description">' . $description . '</span></label>';
        ?>
      </td>
    </tr>
    <?php
}

/**
 * Prints checkbox in html <table> row
 *
 * @param string $label
 * @param string $option_name
 */
function row_checkbox($label, $option_name, $description='')
{
    $v = get_option($option_name);
    $checked = (strlen($v) > 0 ? " checked" : "");

    ?>
    <tr valign="top">
      <th><?php echo $label ?></th>
      <td>
        <input type="checkbox" name="<?php echo $option_name ?>" id="<?php echo 'cb_' . $option_name ?>" value="1" <?php echo $checked ?>/>
        <?php 
        if (strlen($description) > 0)
            echo '<br /><label for="cb_' . $option_name . '"><span class="description">' . $description . '</span></label>';
        ?>
      </td>
    </tr>
    <?php
}



/**
 * Prints list of checkboxes in html <table> row
 *
 * @param string $label
 * @param array $values
 * @param string $option_name
 */
function row_checkboxes($label, $values, $option_name)
{
    $checked_items = get_option($option_name);
    if (!is_array($checked_items))
        $checked_items = array();

    ?>
    <div style='float: left; padding-right: 20px'>
      <h4><?php echo $label ?></h4>
      <table>
        <?php
        foreach ($values as $item_name => $item_label)
        {
            $name = $option_name . '_' . $item_name;
            $checked = 
                (in_array($item_name, $checked_items) 
                     || count($checked_items) <= 0 
                 ? ' checked' : '');

            ?>
            <tr valign="top">
              <th></th>
              <td>
                <input type="checkbox" name="<?php echo $name ?>" value="1" <?php echo $checked ?>/>
                <label for="<?php echo $name ?>"><?php echo $item_label ?></label>
              </td>
            </tr>
            <?php
        }
      ?>
      </table>
    </div>
    <?php
}



/**
 * Prints image upload box in html <table> row
 *
 * @param string $label
 * @param string $option_name
 * @param string $tip
 * @param string $tip2
 */
function row_image($label, $option_name, $description = '')
{
    $img = '';
    $id = get_option($option_name);
    if (strlen($id) > 0)
    {
        $thumbnail = wp_get_attachment_image_src($id, 'thumbnail');
        $img = '<img src="' . $thumbnail[0] . '" />';
    }

    ?>
    <tr valign="top">
      <th scope="row"><label><?php echo $label ?></label></th>
      <td>
        <input type="file" name="file" id="<?php echo $option_name ?>_file" 
          style="float: left" />
        <input type="button" id="<?php echo $option_name ?>_button" 
          class="button file_upload" value="Upload" style="float: left" />
        <input type="hidden" name="<?php echo $option_name ?>" 
          id="<?php echo $option_name ?>" value="<?php echo $id ?>" />

        <?php
        if (strlen($description) > 0)
            echo '<br class="clear" /><span class="description">' . $description . '</span>';
        ?>
      </td>
    </tr>
    <tr valign="top">
      <th></th>
      <td><div id="<?php echo $option_name ?>_thumbnail"><?php echo $img ?></div></td>
    </tr>
    <?php
}



/**
 * Prints textarea in html <table> row
 *
 * @param string $label
 * @param string $option_name
 */
function row_textarea($label, $option_name, $description = '')
{
    ?>
    <tr>
    <th scope="row" style="width: 200px; padding-top: 50px"><label for="<?php echo $option_name ?>"><?php echo $label ?></label></th>
    <td>
      <p align="right">
        <a id="<?php echo $option_name . "_toggleVisual"; ?>" class="button toggleVisual">Visual</a>
        <a id="<?php echo $option_name . "_toggleHTML"; ?>" class="button toggleHTML">HTML</a>
      </p>
      <textarea name="<?php echo $option_name ?>" rows="5" 
        class="heading form-input-tip" 
        style="width:100%"><?php echo htmlspecialchars(get_option($option_name)) ?></textarea>
      <?php
      if (strlen($description) > 0)
          echo '<br /><span class="description">' . $description . '</span>';
      ?>
    </td>
    </tr>
    <?php
}



/**
 * Prints textbox in html <table> row
 *
 * @param string $label
 * @param string $option_name
 */
function row_textbox($label, $option_name, $description = '', $default_value='')
{
    $value = htmlspecialchars(get_option($option_name));
    $value = empty( $value ) ? $default_value : $value;
    ?>
      <tr>
      <th scope="row"><label for="<?php echo $option_name ?>"><?php echo $label ?></label></th>
      <td>
        <input type="text" name="<?php echo $option_name ?>"
          value="<?php echo $value ?>" 
          id="<?php echo $option_name ?>"
          class="heading form-input-tip" 
          style="width:100%" />
        <?php
        if (strlen($description) > 0)
            echo '<br /><span class="description">' . $description . '</span>';
        ?>
      </td>

      </tr>
    <?php
}


/**
 * Prints hidden field in html <table> row
 *
 * @param string $label
 * @param string $option_name
 */
function row_hidden($label, $option_name, $description = '')
{
    ?>

      <th scope="row"><label for="<?php echo $option_name ?>"><?php echo $label ?></label></th>
      <td>
        <input type="hidden" name="<?php echo $option_name ?>"
          value="1" 
          id="<?php echo $option_name ?>"
          class="heading form-input-tip" 
          style="width:100%" />
        <?php
        if (strlen($description) > 0)
            echo '<br /><span class="description">' . $description . '</span>';
        ?>
      </td>

    <?php
}

function placester_refresh_user_data() {
    global $wpdb;
    $placester_options = $wpdb->get_results(
        'SELECT option_name FROM ' . $wpdb->prefix . 'options ' .
        "WHERE ((option_name LIKE  'placester%') AND (option_name !=  'placester_api_key')) " .
        "OR (option_name LIKE '_transient_pl%') " .
        "OR (option_name LIKE '_transient_timeout_pl%');");

    foreach ($placester_options as $option) {
        delete_option( $option->option_name );
    }
}
