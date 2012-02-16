<?php 

class PL_Config {
	
	public function PL_API_LISTINGS () {	
		global $PL_API_LISTINGS;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_API_LISTINGS, $args, $num_args);
	}

	public function PL_API_CUST_ATTR () {
		global $PL_API_CUST_ATTR;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_API_CUST_ATTR, $args, $num_args);
	}

	public function PL_MY_LIST_FORM () {	
		global $PL_MY_LIST_FORM;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_MY_LIST_FORM, $args, $num_args);
	}

	public function bundler ($config_function, $keys, $bundle) {	
		$config_items = array();
		foreach ($bundle as $key) {
			if (is_array($key)) {
				foreach ($key as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $item) {
							$config_items[$k][$item] = call_user_func_array(array(__CLASS__, $config_function), array_merge($keys,(array)$k,(array)$item));			
						}			
					} else {
						$config_items[$k][$v] = call_user_func_array(array(__CLASS__, $config_function), array_merge($keys,(array)$k,(array)$v));			
					}
				}
			} else {
				$config_items[$key] = call_user_func_array(array(__CLASS__, $config_function), array_merge($keys,(array)$key));		
			}
		}
		return $config_items;
	}

	private function config_finder($config, $args, $num_args) {
		switch ($num_args) {
			case '0':
				return $config;
				break;
			case '1':
				if (isset($config[$args[0]])) {
					return $config[$args[0]];
				}
				break;
			case '2':
				if (isset($config[$args[0]][$args[1]])) {
					return $config[$args[0]][$args[1]];
				}
				break;
			case '3':
				if (isset($config[$args[0]][$args[1]][$args[2]])) {
					return $config[$args[0]][$args[1]][$args[2]];
				}
				break;
			case '4':
				if (isset($config[$args[0]][$args[1]][$args[2]][$args[3]])) {
					return $config[$args[0]][$args[1]][$args[2]][$args[3]];
				}
				break;
		}
	}
// end of class
}