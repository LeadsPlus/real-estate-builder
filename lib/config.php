<?php 

class PL_Config {
	
	public function PL_API_LISTINGS ($key = false) {	
		global $PL_API_LISTINGS;
		if ($key && isset($PL_API_LISTINGS[$key])) {
			return $PL_API_LISTINGS[$key];
		}
		return $PL_API_LISTINGS;
	}

	public function PL_API_CUST_ATTR ($key = false) {
		global $PL_API_CUST_ATTR;
		if ($key && isset($PL_API_CUST_ATTR[$key])) {
			return $PL_API_CUST_ATTR[$key];
		}
		return $PL_API_CUST_ATTR;
	}

	public function PL_MY_LIST_FORM ($key = false) {	
		global $PL_MY_LIST_FORM;
		if ($key && isset($PL_MY_LIST_FORM[$key])) {
			return $PL_MY_LIST_FORM[$key];
		}
		return $PL_MY_LIST_FORM;
	}
// end of class
}