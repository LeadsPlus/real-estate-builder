<?php extract(PL_Page_Helper::get_types()); ?>

<?php 

   	$pl_shortcodes = array('searchform' => 'Search Form', 'prop_details' => 'Property Details', 'excerpt' => 'Excerpt');

   	$pl_shortcode_impls = array('searchform' => array('ventura', 'columbus', 'highland'),
						        'prop_details' => array('Red', 'Yellow', 'Orange'),
						   		'excerpt' => array('Purple', 'Pink', 'Gray'),
						  	   );
?>

<script type="text/javascript">
  // Throw this into a .js file...
  jQuery( function ( $ ) {
  	$('.edit_snippet').live('click', function() {
  		var container = $(this).parents('.shortcode_container');

        data = {
        	action: 'get_snippet_body',
            shortcode: container.find('.shortcode').val(),
            snippet: container.find('.snippet_list option:selected').val()//'nothing yet...'//$(this).parents('.shortcode_container').find()
        };

        $.post(ajaxurl, data, function(response) {
        	console.log(response);
            if ( response.snippet_body ) {
            	container.find('.area_snippet textarea').text(response.snippet_body);
            }
        },'json');

  		// Show text area...
  		container.find('.area_snippet').css('display', 'inline');
  	  });

  	$('.activate_snippet').live('click', function() {
  		var container = $(this).parents('.shortcode_container');

        data = {
        	action: 'set_shortcode_snippet',
            shortcode: container.find('.shortcode').val(),
            snippet: container.find('.snippet_list option:selected').val()//'nothing yet...'//$(this).parents('.shortcode_container').find()
        };

        console.log(data);
        
  		$.post(ajaxurl, data, function(response) {
        	console.log(response);
            if ( response.stored_snippet ) {
            	alert(data.shortcode + ' set to: ' + response.stored_snippet);
            }
        },'json');
	  });

  	$('.save_snippet').live('click', function() {
  		// Make AJAX call to alter implementation...
    	$(this).parents('.shortcode_container').find('.area_snippet').css('display', 'none');
	  });
  });
</script>

<div class="wrap">
  <?php echo PL_Helper_Header::pl_settings_subpages(); ?>
  <div class="" style="width: 50%">

	<?php foreach ($pl_shortcodes as $code => $name): ?>
	  <!-- <form name="snippet" id="snippet" action="" method="post"> -->
	  <div style="margin-bottom: 18px" class="shortcode_container">
	  	  <h2><?php echo $name ?> Snippets</h2>
	  	  <input type="hidden" class="shortcode" value="<?php echo $code ?>" />
	  	  <section id="shortcode_ref"> 
			<label for="shortcode_list">Available Implementations:</label>	
			<select class="snippet_list">
				<!-- <option id="default" value="default" selected="selected">By Name...</option> -->
				<?php foreach ($pl_shortcode_impls[$code] as $snippet): ?>
					<option id="<?php echo $snippet ?>" value="<?php echo $snippet ?>"><?php echo $snippet ?></option>
				<?php endforeach ?>	
		    </select>
		    <div style="float: right">
    			<input type="button" class="button-secondary edit_snippet" value="Edit" />
    			<input type="button" class="button-secondary activate_snippet" value="Activate" />
  			</div>
		  </section>
	  	
	  	  <div style="display: none" class="area_snippet">
			<textarea style="width: 100%" rows="20" tabindex="1"></textarea>
	  		<input type="button" class="button-primary save_snippet" value="Save" tabindex="2">
	  	  </div>
	  </div>
	  <!-- </form> -->
	<?php endforeach ?>		
	
  </div>
</div>


<?php 
/********
Questions:

- I am assuming a pre-defined set of templates...how are we versioning these files amongst the various sites?  
This is where I assume the DB comes into play?

- I'm still interpreting the template rendering hierarchy..so the use case for these templates being constructed
is in some other theme?  Can you refresh my memory on at what point (and how) the shortcodes are resolved? I am
assuming that if [shortcode] is placed in a php file alongside mark-up WP will work its magic...

- On the topic of shortcodes, I need to make sure that my PL_Shortcodes class is hooked in such that  

********/

?>