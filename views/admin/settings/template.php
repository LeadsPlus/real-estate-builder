<?php extract(PL_Page_Helper::get_types()); ?>

<style type="text/css">
  .snippet_container {
    overflow: hidden;
    text-align: left;
    margin: 30px 0px 0px 0px;
    padding: 0px;
    width: 1250px;
  }
  .shortcode_container {
    float: left;
    width: 50%;
  }
  .shortcode_ref { 
    margin: 0;
    float: left;
    width: 400px;
    padding: 30px;
  }
  .text_snippet {
    margin: 6px 0px 6px 0px;
  }

  .s-note {
    margin: 6px 0px 12px 0px;
    font-style: italic;
    font-size: small;
    font-weight: bold;
  }

  .s-note .s-code {
    background: lightYellow;
    font-style: normal;  
  }

  .s-note div {
    padding-top: 6px;
  }

  .s-note div .enable_ctrl {
    font-style: normal;
    font-weight: normal;
  }
</style>

<?php 
    /*** Load initial data... ***/

    $pl_snippet_list = array();

    foreach ( PL_Shortcodes::$codes as $code ) {
      $pl_snippet_list[$code] = PL_Snippet_Helper::get_shortcode_snippet_list($code);
    }

    $pl_active_snippets = PL_Snippet_Helper::get_active_snippet_map();
    $pl_snippet_types = array('default' => 'Default', 'custom' => 'Custom'); // Order matters, here...

    // Check if the 'Property Details' functionality is enabled...
    $pd_enabled = get_option(PL_Shortcodes::$prop_details_enabled_key);
?>   

<div class="wrap">
  <?php echo PL_Helper_Header::pl_settings_subpages(); ?>

 <?php foreach (PL_Shortcodes::$p_codes as $code => $name): ?>
	<div class="snippet_container">
	  <div class="shortcode_container">
	  	  <h2><?php echo $name ?></h2>
	  	  <input type="hidden" class="shortcode" value="<?php echo $code ?>" />
        <input type="hidden" class="active_snippet" value="<?php echo $pl_active_snippets[$code] ?>" />
        <div class="s-note">
          <?php if ($code != 'prop_details'): ?>
            Use this by placing <span class="s-code">[<?php echo $code ?>]</span> into any Post or Page
          <?php else: ?>
            This will automatically apply to <span>ALL</span> single-property details pages
           <div>
            <span class="enable_ctrl">
              <label for="enabled_check">Enable this function: </label>
              <input type="checkbox" class="enabled_check" value="Enable: " <?php echo ( $pd_enabled == 'true' ? 'checked="checked"' : '' ) ?> />
            </span>
           </div> 
          <?php endif ?>
        </div>
	  	  <section id="shortcode_ref"> 
    			<label for="snippet_list">Available Implementations:</label>	
    			<select class="snippet_list">
            <?php foreach ($pl_snippet_types as $curr_type => $title_type): ?>
              <optgroup label="<?php echo $title_type?>">
    				    <?php foreach ($pl_snippet_list[$code] as $snippet => $type): ?>
                  <?php if ($type != $curr_type) { continue; } ?>
    					    <option id="<?php echo $snippet ?>" value="<?php echo $snippet ?>" class="<?php echo $type ?>" <?php echo $pl_active_snippets[$code] == $snippet ? 'selected' : '' ?>>
                    <?php echo $snippet ?>
                  </option>
    				    <?php endforeach ?>	
              <?php endforeach ?>
  		      </optgroup>
          </select>
  		    <div style="float: right">
      			<input type="button" class="button-secondary edit_snippet" value="<?php echo $pl_snippet_list[$code][$pl_active_snippets[$code]] == 'default' ? 'View' : 'Edit' ?>" />
      			<input type="button" class="button-secondary activate_snippet" value="Activated" disabled="disabled" />
    			</div>
  		  </section>
  	  	
  	  	<div style="display: none" class="area_snippet">
  			  <textarea style="width: 100%" rows="20" tabindex="1" class="text_snippet"></textarea>
  	  		<input type="button" class="button-primary save_snippet" value="Save" tabindex="2">
          <input type="text" style="display: none" class="new_snippet_name" value="" />
  	  	</div>
	  </div>

    <?php echo PL_Router::load_builder_partial('shortcode-ref.php', array('shortcode' => $code), true) ?>
  </div>  
 <?php endforeach ?>	
</div>