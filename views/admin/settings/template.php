<?php extract(PL_Page_Helper::get_types()); ?>    

<script type="text/javascript">
  // Throw this into a .js file...
  jQuery( function ( $ ) {
  	$('.edit_snippet').live('click', function() {
		  var container = $(this).parents('.shortcode_container');

      // If edit box is already visible, don't overwrite any possible changes/re-load unnecessarily...
      if (container.find('.area_snippet').css('display') != 'none')
      { alert('skipping edit...'); return; }

      data = {
      	  action: 'get_snippet_body',
          shortcode: container.find('.shortcode').val(),
          snippet: container.find('.snippet_list option:selected').val(),
          type: container.find('.snippet_list option:selected').attr('class')
      };

      // console.log(data);
      // return;

      $.post(ajaxurl, data, function(response) {
          if ( response.snippet_body ) {
            // console.log(response.snippet_body);
          	container.find('.area_snippet textarea').val(response.snippet_body);
          }
      },'json');

      // Conditional set the "save" button text...
      var is_default = container.find('.snippet_list option:selected').hasClass('default');
      var save_text = ( is_default ? 'Create a Custom Version to Edit' : 'Save' );
      container.find('.area_snippet .save_snippet').val(save_text);
      
      if (is_default) {
        container.find('.area_snippet textarea').attr('disabled', 'disabled');
        container.find('.area_snippet .new_snippet_name').css('display', 'inline');
      } else {
        container.find('.area_snippet textarea').removeAttr('disabled');
      }

		  // Show text area...
		  container.find('.area_snippet').css('display', 'inline');
  	});

  	$('.activate_snippet').live('click', function() {
		  var container = $(this).parents('.shortcode_container');

      // If edit box is visible and you are viewing a custom snippet, confirm the save-and-set...
      if ( container.find('.snippet_list option:selected').hasClass('custom') && container.find('.area_snippet').css('display') != 'none' ) {
        alert('Please click \'Save\' before activating it...'); 
        return; 
      }

      data = {
      	action: 'activate_snippet',
        shortcode: container.find('.shortcode').val(),
        snippet: container.find('.snippet_list option:selected').val()
      };

      console.log(data);
        
  		$.post(ajaxurl, data, function(response) {
      	console.log(response);
        if ( response.activated_snippet_id ) {
        	alert(data.shortcode + ' points to: ' + response.activated_snippet_id);
        }
      },'json');
	  });

  	$('.save_snippet').live('click', function() {
  		var container = $(this).parents('.shortcode_container');

      // Determine whether creating a new custom snippet, or persisting changes to an existing one...
      var create_new = ( $(this).val() != 'Save' );
      var snippet_name;
      var snippet_body;

      if (create_new) {
        alert('creating a new custom snippet...');

        // Make sure name has been provided, and that it is unique with the given shortcode..
        var name = $(this).siblings('.new_snippet_name').val();

        if (!name) { // empty case
          alert('Please enter a name for your new custom snippet...'); 
          return;
        }

        var snippet_opts = container.find('.snippet_list option');
        // Check for existing snippet with a name matching the one entered...
        for (var i = 0; i < snippet_opts.length; ++i) {
          if (name == snippet_opts[i].value) {
            alert('The name you entered already represents a snippet for this shortcode. \n\n Please enter a unique name...');
            return;
          }
        }

        alert('The new name checks out...');
        snippet_name = name;

      } else {
        alert('saving an existing custom snippet...');
        snippet_name = container.find('.snippet_list option:selected').val();

        // TODO: Sanitize snippet code...
        // sani_func = function (code) { return code; } 
    
        // sani_func(container.find('.area_snippet textarea').text());
      }

      // Whether it's a new snippet, or an existing custom one, we need to pull what's in the textarea...
      snippet_body = container.find('.area_snippet textarea').val(); 

      // Temp Fix...
      if (!snippet_body) { snippet_body = 'Empty Template' }

      // AJAX call to persist new snippet...
      data = {
        action: 'save_custom_snippet',
        shortcode: container.find('.shortcode').val(),
        snippet: snippet_name,
        snippet_body: snippet_body
      };

      // console.log(data);
      // return;
        
      $.post(ajaxurl, data, function(response) {
        if ( response && response.unique_id ) {
          console.log(data.snippet + ' snippet ' + (create_new ? 'created' : 'saved') + ' with ID: ' + response.unique_id
                + '\n' + data.shortcode + ' ID list: ' + response.id_array);
          
          if (create_new) {
            // add to options
            // set newly added option as "custom"
            var new_option = '<option id="0" value="0" class="custom">0</option>'.replace(/0/g, data.snippet);
            container.find('.snippet_list').append(new_option);
            container.find('.snippet_list').val(data.snippet);
            container.find('.edit_snippet').val('Edit');
          }
        }
      },'json');
    	
      // Clear & reset certain elements...
      $(this).siblings('.new_snippet_name').val('');
      $(this).siblings('.new_snippet_name').css('display', 'none');
      container.find('.area_snippet').css('display', 'none');

      // Call edit handler...?
	  });

    // Chaining these events to capture previously selected value...
    $('.snippet_list').live('focus', function() {
      // As globals (lame, I know)
      prev_opt_val = $(this).find('option:selected').val();
      prev_opt_class = $(this).find('option:selected').attr('class');
    });

    $('.snippet_list').live('change', function() {
      var container = $(this).parents('.shortcode_container');
      var shortcode = container.find('.shortcode').val();

      // Alert to save if a customizable template is in edit mode...
      if (container.find('.area_snippet').css('display') != 'none' && prev_opt_class && prev_opt_class !== 'default')
      {
        var choice = confirm('Are you sure you want to navigate away before saving any changes?');
        if (!choice) { 
          $(this).val(prev_opt_val);
          return; 
        }
      }

      // Check for type of the newly selected snippet...
      var is_default = $(this).find('option:selected').hasClass('default');
      container.find('.edit_snippet').val( is_default ? 'View' : 'Edit' );

      container.find('.area_snippet textarea').val('');
      container.find('.area_snippet .new_snippet_name').css('display', 'none');
      container.find('.area_snippet').css('display', 'none');
    });
  });
</script>

<?php 
    /*** Load initial data... ***/

    $pl_snippet_list = array();

    foreach ( PL_Shortcodes::$codes as $code ) {
      $pl_snippet_list[$code] = PL_Snippet_Helper::get_shortcode_snippet_list($code);
    }

    $pl_active_snippets = PL_Snippet_Helper::get_active_snippet_map();
    $pl_snippet_types = array('default' => 'Default', 'custom' => 'Custom'); // Order matters, here...

    //echo '<pre>' . var_dump($pl_snippet_list['searchform']) . '</pre><br/>';
    //echo '<pre>' . var_dump($pl_active_snippets) . '</pre><br/>';
?>   

<div class="wrap">
  <?php echo PL_Helper_Header::pl_settings_subpages(); ?>
  <div class="" style="width: 50%">

	<?php foreach (PL_Shortcodes::$p_codes as $code => $name): ?>
	  <!-- <form name="snippet" id="snippet" action="" method="post"> -->
	  <div style="margin-bottom: 18px" class="shortcode_container">
	  	  <h2><?php echo $name ?> Snippets</h2>
	  	  <input type="hidden" class="shortcode" value="<?php echo $code ?>" />
        
	  	  <section id="shortcode_ref"> 
    			<label for="shortcode_list">Available Implementations:</label>	
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
      			<input type="button" class="button-secondary activate_snippet" value="Activate" />
    			</div>
  		  </section>
  	  	
  	  	<div style="display: none" class="area_snippet">
  			  <textarea style="width: 100%" rows="20" tabindex="1" class="text_snippet"></textarea>
  	  		<input type="button" class="button-primary save_snippet" value="Save" tabindex="2">
          <input type="text" style="display: none" class="new_snippet_name" value="" />
  	  	</div>
	  </div>
	  <!-- </form> -->
	<?php endforeach ?>		
	
  </div>
</div>