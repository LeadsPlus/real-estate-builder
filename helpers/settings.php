<?php 

class PL_Settings_Helper {

	function display_global_filters ($filters) {
		$html = '';
		if (!empty($filters)) {
			foreach ($filters as $key => $filter) {
				if (is_array($filter)) {
					$name = $key . '[' . key($filter) . ']';
 					$label = ucwords($key) . '-' . ucwords(key($filter));
					$value = current($filter);
					ob_start();
					?>
						<span id="active_filter_item">
							<a href="#"  id="remove_filter"></a>
							<span class="global_dark_label"><?php echo $label ?></span> : <?php echo $value ?>
							<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>">	
						</span>
					<?php 
					$html .= ob_get_clean();
				} else {
					ob_start();
					?>
						<span id="active_filter_item">
							<a href="#"  id="remove_filter"></a>
							<span class="global_dark_label"><?php echo ucwords(str_replace('_', ' ', $key)) . '-' . $subkey ?></span> : <?php echo ucwords(str_replace('_', ' ', $filter)) ?>
							<input type="hidden" name="<?php echo $key ?>" value="<?php echo $filter ?>">	
						</span>
					<?php 
					$html .= ob_get_clean();
				}
			}
		}
		echo $html;
	}

//end of class
}