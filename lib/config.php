<?php 

class PL_Config {
	
	public function PL_API_LISTINGS () {	
		global $PL_API_LISTINGS;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_API_LISTINGS, $args, $num_args);
	}

	public function PL_API_CUST_ATTR ($key = false) {
		global $PL_API_CUST_ATTR;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_API_CUST_ATTR, $args, $num_args);
	}

	public function PL_MY_LIST_FORM ($key = false) {	
		global $PL_MY_LIST_FORM;
		$args = func_get_args();
		$num_args = func_num_args();
		return self::config_finder($PL_MY_LIST_FORM, $args, $num_args);
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