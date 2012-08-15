<div id="<?php echo $shortcode ?>_ref" class="shortcode_ref">	
  <h3><u>Usage</u></h3>
	<?php if ($shortcode == 'searchform'): ?>
	  <p>
		You can insert your "activated" Search Form snippet by using the [searchform] shortcode in a page or a post. 
		This control is intended to be used alongside the [listings] shortcode (defined below) to display the search 
		form's results.
	  </p>
	  <p>
		You can use the following shortcodes inside of your Search Form snippet definition to define form elements 
		that can filter on the field they are named after:
	  </p>
  	  <?php foreach (PL_Shortcodes::$subcodes[$shortcode] as $subcode): ?>
  	    [<?php echo $subcode ?>], &nbsp;
      <?php endforeach ?>

	<?php elseif ($shortcode == 'listings'): ?>
	  <p>
		You can insert your "activated" Listings snippet by using the [searchform] shortcode in a page or a post.
		The listings view is intended to be used alongside the [searchform] shortcode defined above as a container
		for the results of the search, with the snippet representing how an <i>individual</i> listing that matches
		the search criteria will be displayed.
	  </p>
	  <p>
		<b>NOTE:</b> The snippet that will be used by [listings] is the one that you last clicked "Activate" while 
		viewing or editing.
	  </p>
	  <p>
		You can use the following shortcodes inside of your Listings Form snippet definition to define what and where 
		certain information is displayed in the listings search :
	  </p>
	  <?php foreach (PL_Shortcodes::$subcodes['listing'] as $subcode): ?>
  	    [<?php echo $subcode ?>], &nbsp;
      <?php endforeach ?>

	<?php elseif ($shortcode == 'prop_details'): ?>
	  <p>
		Unlike the other examples here, this snippet is not actually used via a shortcode--instead, what you define
		in your snippet overwrites the format for any property details page, including those accesssed from search and 
		listing elements you have <i>not</i> defined.
	  </p>
	  <p>
		You can use the following shortcodes inside of your Property Details snippet definition include form elements that can 
		filter on field they are named after:
	  </p>
	  <?php foreach (PL_Shortcodes::$subcodes['listing'] as $subcode): ?>
  	    [<?php echo $subcode ?>], &nbsp;
      <?php endforeach ?>
	<?php else: ?>
	    Doc not found...
	<?php endif ?>

</div>