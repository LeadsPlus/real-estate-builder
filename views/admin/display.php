<div class="wrap">
	<div class="header-wrapper">
		<h2>Listing Excerpt</h2>	
	</div>
	<div class="clear"></div>
	<div>
		<select name="" id="" class="template">
			<?php echo PL_Display_Helper::get_templates_as_options(array('type' => 'exerpts')); ?>	
		</select>
		<a href="#" class="button">Edit Template</a>
		<a href="#" class="button">Delete Template</a>
		<a href="#" class="button">New Template</a>
	</div>
	<div class="editor">
		<?php wp_editor('','excerpt-editor',array('quicktags' => array('buttons' => 'em,strong,link',),'text_area_name'=>'extra_content','quicktags' => true,'tinymce' => true)); ?>
	</div>
	<div class="header-wrapper">
		<h2>Listing Content</h2>	
	</div>
	<div class="clear"></div>
	<div>
		<select name="" id="" class="template">
			<?php echo PL_Display_Helper::get_templates_as_options(array('type' => 'content')); ?>	
		</select>
		<a href="#" class="button">Edit Template</a>
		<a href="#" class="button">Delete Template</a>
		<a href="#" class="button">New Template</a>
	</div>
	<div class="editor">
		<?php wp_editor('','content-editor',array('quicktags' => array('buttons' => 'em,strong,link',),'text_area_name'=>'extra_content','quicktags' => true,'tinymce' => true)); ?>
	</div>
</div>