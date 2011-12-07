<?php 
// Class for handling all user associated actions. 


class PL_User{

	// user attributes. 
	static $email = '';
	static $placester_id = '';
	static $first_name = '';
	static $last_name = '';
	static $phone_number = '';
	static $street_address = '';
	static $city = '';
	static $state = '';
	static $zip = '';
	static $unit = '';
	static $website_url = '';
	static $headshot_url = '';
	static $bio = '';

	// Local save point
	static $local_option_name = 'placester_user';


	// Placester's save endpoint.
	static $placester_api_url = 'http://api.placester.com/v1.0/users.json';

	// json request stucture from Placester
	static $placester_api_request = array(
		'api_key' => '',
        'source' => '',
        'user_id' => '',
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'website' => '',
        'phone' => '',
        'location[address]' => '',
        'location[city]' => '',
        'location[zip]' => '',
        'location[state]' => '',
        'location[unit]' => ''
        );


    //boot up on start.
    public static function init() {
    	
    }
	
	//get user details from db and update object.  
	public static function get() {
		$raw_json = get_option('placester_user');

		if ($raw_jason) {
			//update user object with details... validate them all.
		}

		return false;
	}

	// reload data from Placester, if different auto update. 
	public static function update_from_placester() {
		$raw_json = get_option('placester_user');

		if ($raw_jason) {
			//update user object with details... validate them all.
		}

		return false;
	}

	//create
	public static function create() {
		
	}

	
	//update
	public static function update() {
		
	}

	//delete
	public static function delete() {
		
	}

	//writes changes to local and placester
	private static function save() {
		
	}

	//writes changes locally. 
	private static function update_local () {
		
	}

	private static function update_placester () {

		//validate all data before updating placester. 
		if (self::validate() ) {

			// updates placester_api_request with the current values of the user object's
			// attributes.
	        self::build_request();
	        
	        // sends request to webservice client. 
	        placester_send_request(self::$placester_api_url, self::$placester_api_request, 'PUT');

		} else {
			self::

		}
	}

	private static function build_request() {
			
			// Request stucture from Placester
			self::$placester_api_request = array(
				'api_key' => placester_get_api_key(), //TODO: fix this so webservice just handles it. 
	            'source' => 'wordpress', // constant
	            'user_id' => self::$placester_id,
	            'first_name' => self::$first_name,
	            'last_name' => self::$last_name,
	            'email' => self::$email,
	            'website' => self::$website_url,
	            'phone' => self::$phone_number,
	            'location[address]' => self::$street_address,
	            'location[city]' => self::$city,
	            'location[zip]' => self::$zip,
	            'location[state]' => self::$state,
	            'location[unit]' => self::$unit
	            );
	      
	}

	// validate all data so we know it's safe to use, store, etc... 
	private static function validate() {
		
	}

	//validation functions
	private static function is_phone() {
		
	}

	//
	private static function handle_error() {
		
	}

//end of class	
}





//old functions that still need to be supported.....



 ?>