<?php 

class PL_Form {
	
	public static function generate($items, $url = false, $method = 'GET', $id = 'pls_search_form', $title = false) {
		$form = '<form name="input" method="' . $method . '" class="complex-search" id="' . $id . '">';
		$form_group = array();
		foreach ($items as $key => $attributes) {
			if ( isset($attributes['type']) && isset($attributes['group']) ) {
			 	$form_group[$attributes['group']][] = self::item($key, $attributes, $method);
			 } elseif ( !isset($attributes['type']) && is_array($attributes) ) {
				foreach ($attributes as $child_item => $attribute) {
					if ( isset($attribute['group']) ) {
						$form_group[$attribute['group']][] = self::item($child_item, $attribute, $method, $key);	
					}
				}
			}
		}	
		foreach ($form_group as $group => $elements) {
			$form .= "<section class='form_group' id='".$group."'>";
			$form .= $title ? "<h3>" . ucwords($group) . "</h3>" : '';
			$form .= implode($elements, '');
			$form .= "</section>";
		}
		$form .= '<section class="clear"></section>';
		$form .= '<button id="' . $id . '_submit_button" type="submit">Submit</button>';
		$form .= '</form>';
		echo $form;
	}

	public static function item($item, $attributes, $method, $parent = false) {
		extract(self::prepare_item($item, $attributes, $method, $parent), EXTR_SKIP);
		ob_start();
		if ($type == 'checkbox') {
			?>
				<section id="<?php echo $id ?>" class="pls_search_form">
					<input id="<?php echo $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" value="true" <?php echo $value ? 'checked' : '' ?>/>
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
				</section>
			<?php	
		} elseif ($type == 'textarea') {
			?>
				<section id="<?php echo $id ?>" class="pls_search_form">
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
					<textarea id="<?php echo $id ?>" rows="2" cols="20"><?php echo $value ?></textarea>
				</section>
			<?php
		} elseif ($type == 'select') {
			?>
				<?php //pls_dump($value); ?>
				<section id="<?php echo $id ?>" class="pls_search_form" >
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
					<select name="<?php echo $name ?>" id="<?php echo $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
						<?php foreach ($options as $key => $text): ?>
							<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ($key == $value ? 'selected' : '' ) ?>><?php echo $text ?></option>
						<?php endforeach ?>
					</select>
				</section>
			<?php	
		} elseif ($type == 'multiselect') {
			?>	<?php //pls_dump($value) ?>
				<section id="<?php echo $id ?>" class="pls_search_form" >
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
					<select name="<?php echo $name ?>[]" id="<?php echo $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
						<?php foreach ($options as $key => $text): ?>
							<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ((is_array($value) && in_array($key, $value) ) ? 'selected' : '' ) ?>><?php echo $text ?></option>
						<?php endforeach ?>
					</select>
				</section>
			<?php	
		} elseif( $type == 'text' ) {
			?>
				<section id="<?php echo $id ?>" class="pls_search_form">
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
					<input id="<?php echo $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" <?php echo !empty($value) ? 'value="'.$value.'"' : ''; ?> />
				</section>
			<?php
		} elseif ( $type == 'date') {
			?>
				<section id="<?php echo $id ?>" class="pls_search_form">
					<label for="<?php echo $id ?>"><?php echo $text ?></label>	
					<input id="<?php echo $id ?>" class="trigger_datepicker" type="<?php echo $type ?>" name="<?php echo $name ?>" <?php echo !empty($value) ? 'value="'.$value.'"' : ''; ?> />
				</section>
			<?php
		}
		return trim(ob_get_clean());
	}

	private function prepare_item($item, $attributes, $method, $parent) {

		$text = $item;
		if (isset($attributes['label'])) {
			$text = $attributes['label'];
		}

		// properly set the name if an array
		$name = $item;
		if($parent) {
			$name = $parent . '[' . $item . ']';
		}

		// get options, if there are any.
		if (isset($attributes['bound']) && is_array(($attributes['bound']))) {
			$options = call_user_func(array($attributes['bound']['class'], $attributes['bound']['method']), (isset($attributes['bound']['params']) ? $attributes['bound']['params'] : null));
		} elseif (isset($attributes['options'])) {
			$options = $attributes['options'];
		} else {
			$options = array();
		}


		// get values
		if ($method == 'GET') {
			if ($parent) {
				$value = isset($_GET[$parent][$item]) ? $_GET[$parent][$item] : null;
			} else {
				$value = isset($_GET[$item]) ? $_GET[$item] : null;	
			}
		} else {
			if ($parent) {
				$value = isset($_POST[$parent][$item]) ? $_POST[$parent][$item] : null;
			} else {
				$value = isset($_POST[$item]) ? $_POST[$item] : null;	
			}
		}

		return array('name' => $name, 'value' => $value, 'text' => $text, 'options' => $options, 'id' => $item, 'type' => $attributes['type'] );
	}

// class end
}