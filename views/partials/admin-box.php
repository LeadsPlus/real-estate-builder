<div id="<?php echo isset($id) ? $id : ''; ?>" class="meta-box-sortables ui-sortable" style="<?php echo isset($style) ? $style : '' ?>" >
	<div id="div" class="postbox ">
		<div class="handlediv" title="Click to toggle"><br></div>
		<h3 class="hndle">
			<span><?php echo $title ?></span>
		</h3>
		<div class="inside">
			<?php echo $content; ?>
		</div>
		<div class="clear"></div>
	</div>	
</div>