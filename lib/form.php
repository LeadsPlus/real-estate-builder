<?php 

class PL_Form {
	
	public static function generate($items, $url = false, $method = 'GET') {
		// pls_dump($_GET);
		$form = '<form name="input" method="' . $method . '" class="complex-search" >';
		foreach ($items as $key => $attributes) {
			$form .= self::item($key, $attributes, $method);
		}	
		$form .= '<section class="clear"></section>';
		$form .= '<button type="submit">Submit</button>';
		$form .= '</form>';
		echo $form;
	}

	public static function item($item, $attributes, $method, $parent = false) {
			
		// if no type, need to traverse 1 level deeper. 
		if( !isset($attributes['type']) && is_array($attributes) ) {
			$items = '';
			foreach ($attributes as $child_item => $attribute) {
				$items .= PL_Form::item($child_item, $attribute, $method, $item);
			}
			return $items;
		}

		if ($item == 'max_beds') {
			// pls_dump($item, $attributes, $method, $parent);
		}
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
					<input id="<?php echo $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" <?php echo !empty($vale) ? 'value="'.$value.'"' : ''; ?> />
				</section>
			<?php
		}
		return trim(ob_get_clean());
	}

	private function prepare_item($item, $attributes, $method, $parent) {

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
		}

		pls_dump($name, $value);

		return array('name' => $name, 'value' => $value, 'text' => $item, 'options' => $options, 'id' => $item, 'type' => $attributes['type'] );
	}

// class end
}