<?php 

PL_Listing_Helper::init();

class PL_Listing_Helper {

	public function init() {
		add_action('wp_ajax_datatable_ajax', array(__CLASS__, 'datatable_ajax' ) );
		add_action('wp_ajax_add_listing', array(__CLASS__, 'add_listing_ajax' ) );
		add_action('wp_ajax_update_listing', array(__CLASS__, 'update_listing_ajax' ) );
		add_action('wp_ajax_add_temp_image', array(__CLASS__, 'add_temp_image' ) );
		add_action('wp_ajax_filter_options', array(__CLASS__, 'filter_options' ) );
		add_action('wp_ajax_delete_listing', array(__CLASS__, 'delete_listing_ajax' ) );
	}
	
	public function results($args = array()) {
		if (!is_array($args)) {
			$args = wp_parse_args($args);
		} elseif (empty($args)) {
			$args = $_GET;
		}
		//respect global filters
		$global_filters = PL_Helper_User::get_global_filters();
		$args = wp_parse_args($global_filters, $args);

		//respect block address setting
		if (PL_Option_Helper::get_block_address()) {
			$args['address_mode'] = 'exact';
		} else {
			$args['address_mode'] = 'polygon';
		}

		$listings = PL_Listing::get($args);	
		foreach ($listings['listings'] as $key => $listing) {
			$listings['listings'][$key]['cur_data']['url'] = PL_Page_Helper::get_url($listing['id']);
			$listings['listings'][$key]['location']['full_address'] = $listing['location']['address'] . ' ' . $listing['location']['locality'] . ' ' . $listing['location']['region'];
		}
		return $listings;
	}

	public function many_details($args) {
		extract(wp_parse_args($args, array('property_ids' => array(), 'limit' => '50', 'offset' => '0')));
		$response = array();
		if (empty($property_ids)) {
			return array('listings' => array(), 'total' => 0);
		}
		$use_property_ids = array_slice($property_ids, $offset, $limit);
		foreach ($use_property_ids as $id) {
			$listing = self::details(array('id' => $id) );
			$listing['cur_data']['url'] = PL_Page_Helper::get_url($listing['id']);
			$listing['location']['full_address'] = $listing['location']['address'] . ' ' . $listing['location']['locality'] . ' ' . $listing['location']['region'];
			$response['listings'][] = $listing;
		}
		$response['total'] = count($property_ids);
		return $response;
	}

	public function details($args = array()) {
		
		//respect block address setting
		if (PL_Option_Helper::get_block_address()) {
			$args['address_mode'] = 'exact';
		} else {
			$args['address_mode'] = 'polygon';
		}

		$listing = PL_Listing::details($args);
		//rename cur_data to metadata due to api weirdness;
		$listing['metadata'] = $listing['cur_data'];
		// unset($listing['cur_data']);
		//set compound type using combination of zoning type, purchase type, and listing type
		if (!empty($listing['zoning_types']) && !empty($listing['purchase_types']) ) {
			//residential + commercial + rental + sale combos are handled in zoning / purhcase types
			if ($listing['zoning_types'][0] == 'residential' && $listing['purchase_types'][0] == 'rental') {
				$listing['compound_type'] = 'res_rental';
			}
			if ($listing['zoning_types'][0] == 'residential' && $listing['purchase_types'][0] == 'sale') {
				$listing['compound_type'] = 'res_sale';
			}
			if ($listing['zoning_types'][0] == 'commercial' && $listing['purchase_types'][0] == 'rental') {
				$listing['compound_type'] = 'comm_rental';
			}
			if ($listing['zoning_types'][0] == 'commercial' && $listing['purchase_types'][0] == 'sale') {
				$listing['compound_type'] = 'comm_sale';
			}
		} elseif (!empty($listing['listing_types'])) {
			if ($listing['listing_types'][0] == 'sublet')  {
				$listing['compound_type'] = 'sublet';
			}
			if (!empty($listing['purchase_types']) && $listing['purchase_types'][0] == 'rental' && $listing['listing_types'][0] == 'vacation') {
				$listing['compound_type'] = 'vac_rental';
			}
		}

		return $listing;
	}

	public function custom_attributes($args = array()) {
		$custom_attributes = PL_Custom_Attributes::get(array('attr_class' => '2'));
		return $custom_attributes;
	}

	function datatable_ajax() {
		$response = array();
		//exact addresses should be shown. 
		$_POST['address_mode'] = 'exact';

		// Sorting
		$columns = array('images','location.address', 'location.locality', 'location.region', 'location.postal', 'zoning_types', 'purchase_types', 'listing_types', 'property_type', 'cur_data.beds', 'cur_data.baths', 'cur_data.price', 'cur_data.sqft', 'cur_data.avail_on');
		$_POST['sort_by'] = $columns[$_POST['iSortCol_0']];
		$_POST['sort_type'] = $_POST['sSortDir_0'];
		
		// text searching on address
		$_POST['location']['address'] = @$_POST['sSearch'];
		$_POST['location']['address_match'] = 'like';

		// Pagination
		$_POST['limit'] = $_POST['iDisplayLength'];
		$_POST['offset'] = $_POST['iDisplayStart'];		
		
		// Get listings from model
		$api_response = PL_Listing::get($_POST);
		
		// build response for datatables.js
		$listings = array();
		foreach ($api_response['listings'] as $key => $listing) {
			$images = $listing['images'];
			$listings[$key][] = ((is_array($images) && isset($images[0])) ? '<img width=50 height=50 src="' . $images[0]['url'] . '" />' : 'empty');
			$listings[$key][] = '<a class="address" href="'.ADMIN_MENU_URL.'?page=placester_property_add&id=' . $listing['id'] . '">' . $listing["location"]["address"] . ' ' . $listing["location"]["locality"] . ' ' . $listing["location"]["region"] . '</a><div class="row_actions"><a href="'.admin_url().'?page=placester_property_add&id=' . $listing['id'] . '" >Edit</a><span>|</span><a href=' . PL_Page_Helper::get_url($listing['id']) . '>View</a><span>|</span><a class="red" id="pls_delete_listing" href="#" ref="'.$listing['id'].'">Delete</a></div>';
			$listings[$key][] = $listing["location"]["postal"];
			$listings[$key][] = implode($listing["zoning_types"], ', ') . ' ' . implode($listing["purchase_types"], ', ');
			$listings[$key][] = implode($listing["listing_types"], ', ');
			$listings[$key][] = $listing["property_type"];
			$listings[$key][] = $listing["cur_data"]["beds"];
			$listings[$key][] = $listing["cur_data"]["baths"];
			$listings[$key][] = $listing["cur_data"]["price"];
			$listings[$key][] = $listing["cur_data"]["sqft"];
			$listings[$key][] = $listing["cur_data"]["avail_on"] ? date_format(date_create($listing["cur_data"]["avail_on"]), "jS F, Y g:i A.") : 'n/a';
		}

		// Required for datatables.js to function properly.
		$response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $listings;
		$response['iTotalRecords'] = $api_response['total'];
		$response['iTotalDisplayRecords'] = $api_response['total'];
		echo json_encode($response);

		//wordpress echos out a 0 randomly. die prevents it.
		die();
	}
	
	public function add_listing_ajax() {
		foreach ($_POST as $key => $value) {
			if (is_int(strpos($key, 'property_type')) && $value !== 'false') {
				$_POST['property_type'] = $value;
			}
		}
		$api_response = PL_Listing::create($_POST);
		echo json_encode($api_response);
		if (isset($api_response['id'])) {
			PL_HTTP::clear_cache();
			self::details(array('id' => $api_response['id']));
		}
		die();
	}	

	public function update_listing_ajax() {
		foreach ($_POST as $key => $value) {
			if (is_int(strpos($key, 'property_type')) && $value !== 'false') {
				$_POST['property_type'] = $value;
			}
		}
		$api_response = PL_Listing::update($_POST);
		echo json_encode($api_response);
		if (isset($api_response['id'])) {
			PL_HTTP::clear_cache();
			PL_Pages::delete_by_name($api_response['id']);
			self::details(array('id' => $api_response['id']));
		}
		die();
	}	

	public function add_temp_image() {
		$api_response = array();
		if (isset($_FILES['files'])) {
			foreach ($_FILES as $key => $image) {
				if (isset($image['name']) && is_array($image['name']) && (count($image['name']) == 1))  {
					$image['name'] = implode($image['name']);
				}
				if (isset($image['type']) && is_array($image['type']) && (count($image['type']) == 1))  {
					$image['type'] = implode($image['type']);
				}
				if (isset($image['tmp_name']) && is_array($image['tmp_name']) && (count($image['tmp_name']) == 1))  {
					$image['tmp_name'] = implode($image['tmp_name']);
				}
				if (isset($image['size']) && is_array($image['size']) && (count($image['size']) == 1))  {
					$image['size'] = implode($image['size']);
				}
				$api_response[$key] = PL_Listing::temp_image($_POST, $image['name'], $image['type'], $image['tmp_name']);
				$api_response[$key]['orig_name'] = $image['name'];
			}
			$response = array();
			if (!empty($api_response)) {
				foreach ($api_response as $key => $value) {
					$response[$key]['name']	= $value['filename'];
					$response[$key]['orig_name'] = $value['orig_name'];
					$response[$key]['url'] = $value['url'];
				}
			}
		}		
		header('Vary: Accept');
		header('Content-type: application/json');
		echo json_encode($response);
		die();
	}

	public function delete_listing_ajax () {
		$api_response = PL_Listing::delete($_POST);
		//api returns empty, with successful header. Return actual message so js doesn't explode trying to check empty.
		if (empty($api_response)) { 
			echo json_encode(array('response' => true, 'message' => 'Listing successfully deleted. This page will reload momentarily.'));	
			PL_HTTP::clear_cache();
		} elseif ( isset($api_response['code']) && $api_response['code'] == 1800 ) {
			echo json_encode(array('response' => false, 'message' => 'Cannot find listing. Try <a href="'.admin_url().'?page=placester_settings">emptying your cache</a>.'));
		}
		die();
	}

	public function locations_for_options($return_only) {
		$options = array();
		$response = PL_Listing::locations();
		if ($return_only && isset($response[$return_only])) {
			foreach ($response[$return_only] as $key => $value) {
				$options[$value] = $value;
			}
			ksort($options);
			$options = array_merge(array('false' => 'Any'), $options);
			return $options;	
		} else {
			return array();	
		}
	}

	public function pricing_min_options($type = 'min') {
		$api_response = PL_Listing::get();
		$prices = array();
		foreach ($api_response['listings'] as $key => $listing) {
			$prices[] = $listing['cur_data']['price'];
		}
		sort($prices);
		if (is_array($prices) && !empty($prices)) {
			$range = ($prices[0] - end($prices))/10;
			if ($type == 'max') {
				$range = range($prices[0], end($prices), $range);
				return $range;
			} else {
				$range = range($prices[0], end($prices), $range);
				array_pop($range);
				return $range;
			}
		} else {
			return array();
		}
	}

	public function filter_options () {
		$option_name = 'pl_my_listings_filters';
		$options = get_option($option_name);
		if (isset($_POST['filter']) && isset($_POST['value']) && $options) {
			$options[$_POST['filter']] = $_POST['value'];
			update_option($option_name, $options);
		} elseif (isset($_POST['filter']) && isset($_POST['value']) && !$options) {
			$options = array($_POST['filter'] => $_POST['value']);
			add_option($option_name, $options);
		}
		echo json_encode($options);
		die();
	}

	public function get_listing_attributes() {
		$options = array();
		$attributes = PL_Config::bundler('PL_API_LISTINGS', array('get', 'args'), array('listing_types','property_type.sublet','property_type.res_sale','property_type.res_rental','property_type.vac_rental','property_type.comm_sale','property_type.comm_rental', 'zoning_types', 'purchase_types', array('location' => array('region', 'locality', 'postal', 'neighborhood'))));
		foreach ($attributes as $key => $attribute) {
			if ( isset($attribute['label']) ) {
				$options['basic'][$key] = $attribute['label'];
			} else {
				foreach ($attribute as $k => $v) {
					if (isset( $v['label'])) {
						$options[$key][$k] = $v['label'];
					}
				}
			}
		}
		$option_html = '';
		foreach ($options as $group => $value) {
			ob_start();
			?>
			<optgroup label="<?php echo ucwords($group) ?>">
				<?php foreach ($value as $value => $label): ?>
					<option value="<?php echo $value ?>"><?php echo $label ?></option>
				<?php endforeach ?>
			</optgroup>
			<?php
			$option_html .= ob_get_clean();
		}

		$option_html = '<select id="selected_global_filter">' . $option_html . '</select>';
		echo $option_html;
	}

	public function convert_default_country() {
		$country_array = PL_Helper_User::get_default_country();
		if ($country_array['default_country']) {
			return $country_array['default_country'];
		} else {
			return 'US';
		}
	}

	public function supported_countries () {
		return array("AD" => "Andorra (AD)",
			"AE" => "United Arab Emirates (AE)",
			"AF" => "Afghanistan (AF)",
			"AG" => "Antigua &amp; Barbuda (AG)",
			"AI" => "Anguilla (AI)",
			"AL" => "Albania (AL)",
			"AM" => "Armenia (AM)",
			"AO" => "Angola (AO)",
			"AQ" => "Antarctica (AQ)",
			"AR" => "Argentina (AR)",
			"AS" => "Samoa (American) (AS)",
			"AT" => "Austria (AT)",
			"AU" => "Australia (AU)",
			"AW" => "Aruba (AW)",
			"AX" => "Aaland Islands (AX)",
			"AZ" => "Azerbaijan (AZ)",
			"BA" => "Bosnia &amp; Herzegovina (BA)",
			"BB" => "Barbados (BB)",
			"BD" => "Bangladesh (BD)",
			"BE" => "Belgium (BE)",
			"BF" => "Burkina Faso (BF)",
			"BG" => "Bulgaria (BG)",
			"BH" => "Bahrain (BH)",
			"BI" => "Burundi (BI)",
			"BJ" => "Benin (BJ)",
			"BL" => "St Barthelemy (BL)",
			"BM" => "Bermuda (BM)",
			"BN" => "Brunei (BN)",
			"BO" => "Bolivia (BO)",
			"BQ" => "Bonaire Sint Eustatius &amp; Saba (BQ)",
			"BR" => "Brazil (BR)",
			"BS" => "Bahamas (BS)",
			"BT" => "Bhutan (BT)",
			"BV" => "Bouvet Island (BV)",
			"BW" => "Botswana (BW)",
			"BY" => "Belarus (BY)",
			"BZ" => "Belize (BZ)",
			"CA" => "Canada (CA)",
			"CC" => "Cocos (Keeling) Islands (CC)",
			"CD" => "Congo (Dem. Rep.) (CD)",
			"CF" => "Central African Rep. (CF)",
			"CG" => "Congo (Rep.) (CG)",
			"CH" => "Switzerland (CH)",
			"CI" => "Cote d'Ivoire (CI)",
			"CK" => "Cook Islands (CK)",
			"CL" => "Chile (CL)",
			"CM" => "Cameroon (CM)",
			"CN" => "China (CN)",
			"CO" => "Colombia (CO)",
			"CR" => "Costa Rica (CR)",
			"CU" => "Cuba (CU)",
			"CV" => "Cape Verde (CV)",
			"CW" => "Curacao (CW)",
			"CX" => "Christmas Island (CX)",
			"CY" => "Cyprus (CY)",
			"CZ" => "Czech Republic (CZ)",
			"DE" => "Germany (DE)",
			"DJ" => "Djibouti (DJ)",
			"DK" => "Denmark (DK)",
			"DM" => "Dominica (DM)",
			"DO" => "Dominican Republic (DO)",
			"DZ" => "Algeria (DZ)",
			"EC" => "Ecuador (EC)",
			"EE" => "Estonia (EE)",
			"EG" => "Egypt (EG)",
			"EH" => "Western Sahara (EH)",
			"ER" => "Eritrea (ER)",
			"ES" => "Spain (ES)",
			"ET" => "Ethiopia (ET)",
			"FI" => "Finland (FI)",
			"FJ" => "Fiji (FJ)",
			"FK" => "Falkland Islands (FK)",
			"FM" => "Micronesia (FM)",
			"FO" => "Faroe Islands (FO)",
			"FR" => "France (FR)",
			"GA" => "Gabon (GA)",
			"GB" => "Britain (UK) (GB)",
			"GD" => "Grenada (GD)",
			"GE" => "Georgia (GE)",
			"GF" => "French Guiana (GF)",
			"GG" => "Guernsey (GG)",
			"GH" => "Ghana (GH)",
			"GI" => "Gibraltar (GI)",
			"GL" => "Greenland (GL)",
			"GM" => "Gambia (GM)",
			"GN" => "Guinea (GN)",
			"GP" => "Guadeloupe (GP)",
			"GQ" => "Equatorial Guinea (GQ)",
			"GR" => "Greece (GR)",
			"GS" => "South Georgia &amp; the South Sandwich Islands (GS)",
			"GT" => "Guatemala (GT)",
			"GU" => "Guam (GU)",
			"GW" => "Guinea-Bissau (GW)",
			"GY" => "Guyana (GY)",
			"HK" => "Hong Kong (HK)",
			"HM" => "Heard Island &amp; McDonald Islands (HM)",
			"HN" => "Honduras (HN)",
			"HR" => "Croatia (HR)",
			"HT" => "Haiti (HT)",
			"HU" => "Hungary (HU)",
			"ID" => "Indonesia (ID)",
			"IE" => "Ireland (IE)",
			"IL" => "Israel (IL)",
			"IM" => "Isle of Man (IM)",
			"IN" => "India (IN)",
			"IO" => "British Indian Ocean Territory (IO)",
			"IQ" => "Iraq (IQ)",
			"IR" => "Iran (IR)",
			"IS" => "Iceland (IS)",
			"IT" => "Italy (IT)",
			"JE" => "Jersey (JE)",
			"JM" => "Jamaica (JM)",
			"JO" => "Jordan (JO)",
			"JP" => "Japan (JP)",
			"KE" => "Kenya (KE)",
			"KG" => "Kyrgyzstan (KG)",
			"KH" => "Cambodia (KH)",
			"KI" => "Kiribati (KI)",
			"KM" => "Comoros (KM)",
			"KN" => "St Kitts &amp; Nevis (KN)",
			"KP" => "Korea (North) (KP)",
			"KR" => "Korea (South) (KR)",
			"KW" => "Kuwait (KW)",
			"KY" => "Cayman Islands (KY)",
			"KZ" => "Kazakhstan (KZ)",
			"LA" => "Laos (LA)",
			"LB" => "Lebanon (LB)",
			"LC" => "St Lucia (LC)",
			"LI" => "Liechtenstein (LI)",
			"LK" => "Sri Lanka (LK)",
			"LR" => "Liberia (LR)",
			"LS" => "Lesotho (LS)",
			"LT" => "Lithuania (LT)",
			"LU" => "Luxembourg (LU)",
			"LV" => "Latvia (LV)",
			"LY" => "Libya (LY)",
			"MA" => "Morocco (MA)",
			"MC" => "Monaco (MC)",
			"MD" => "Moldova (MD)",
			"ME" => "Montenegro (ME)",
			"MF" => "St Martin (French part) (MF)",
			"MG" => "Madagascar (MG)",
			"MH" => "Marshall Islands (MH)",
			"MK" => "Macedonia (MK)",
			"ML" => "Mali (ML)",
			"MM" => "Myanmar (Burma) (MM)",
			"MN" => "Mongolia (MN)",
			"MO" => "Macau (MO)",
			"MP" => "Northern Mariana Islands (MP)",
			"MQ" => "Martinique (MQ)",
			"MR" => "Mauritania (MR)",
			"MS" => "Montserrat (MS)",
			"MT" => "Malta (MT)",
			"MU" => "Mauritius (MU)",
			"MV" => "Maldives (MV)",
			"MW" => "Malawi (MW)",
			"MX" => "Mexico (MX)",
			"MY" => "Malaysia (MY)",
			"MZ" => "Mozambique (MZ)",
			"NA" => "Namibia (NA)",
			"NC" => "New Caledonia (NC)",
			"NE" => "Niger (NE)",
			"NF" => "Norfolk Island (NF)",
			"NG" => "Nigeria (NG)",
			"NI" => "Nicaragua (NI)",
			"NL" => "Netherlands (NL)",
			"NO" => "Norway (NO)",
			"NP" => "Nepal (NP)",
			"NR" => "Nauru (NR)",
			"NU" => "Niue (NU)",
			"NZ" => "New Zealand (NZ)",
			"OM" => "Oman (OM)",
			"PA" => "Panama (PA)",
			"PE" => "Peru (PE)",
			"PF" => "French Polynesia (PF)",
			"PG" => "Papua New Guinea (PG)",
			"PH" => "Philippines (PH)",
			"PK" => "Pakistan (PK)",
			"PL" => "Poland (PL)",
			"PM" => "St Pierre &amp; Miquelon (PM)",
			"PN" => "Pitcairn (PN)",
			"PR" => "Puerto Rico (PR)",
			"PS" => "Palestine (PS)",
			"PT" => "Portugal (PT)",
			"PW" => "Palau (PW)",
			"PY" => "Paraguay (PY)",
			"QA" => "Qatar (QA)",
			"RE" => "Reunion (RE)",
			"RO" => "Romania (RO)",
			"RS" => "Serbia (RS)",
			"RU" => "Russia (RU)",
			"RW" => "Rwanda (RW)",
			"SA" => "Saudi Arabia (SA)",
			"SB" => "Solomon Islands (SB)",
			"SC" => "Seychelles (SC)",
			"SD" => "Sudan (SD)",
			"SE" => "Sweden (SE)",
			"SG" => "Singapore (SG)",
			"SH" => "St Helena (SH)",
			"SI" => "Slovenia (SI)",
			"SJ" => "Svalbard &amp; Jan Mayen (SJ)",
			"SK" => "Slovakia (SK)",
			"SL" => "Sierra Leone (SL)",
			"SM" => "San Marino (SM)",
			"SN" => "Senegal (SN)",
			"SO" => "Somalia (SO)",
			"SR" => "Suriname (SR)",
			"SS" => "South Sudan (SS)",
			"ST" => "Sao Tome &amp; Principe (ST)",
			"SV" => "El Salvador (SV)",
			"SX" => "Sint Maarten (SX)",
			"SY" => "Syria (SY)",
			"SZ" => "Swaziland (SZ)",
			"TC" => "Turks &amp; Caicos Is (TC)",
			"TD" => "Chad (TD)",
			"TF" => "French Southern &amp; Antarctic Lands (TF)",
			"TG" => "Togo (TG)",
			"TH" => "Thailand (TH)",
			"TJ" => "Tajikistan (TJ)",
			"TK" => "Tokelau (TK)",
			"TL" => "East Timor (TL)",
			"TM" => "Turkmenistan (TM)",
			"TN" => "Tunisia (TN)",
			"TO" => "Tonga (TO)",
			"TR" => "Turkey (TR)",
			"TT" => "Trinidad &amp; Tobago (TT)",
			"TV" => "Tuvalu (TV)",
			"TW" => "Taiwan (TW)",
			"TZ" => "Tanzania (TZ)",
			"UA" => "Ukraine (UA)",
			"UG" => "Uganda (UG)",
			"UM" => "US minor outlying islands (UM)",
			"US" => "United States (US)",
			"UY" => "Uruguay (UY)",
			"UZ" => "Uzbekistan (UZ)",
			"VA" => "Vatican City (VA)",
			"VC" => "St Vincent (VC)",
			"VE" => "Venezuela (VE)",
			"VG" => "Virgin Islands (UK) (VG)",
			"VI" => "Virgin Islands (US) (VI)",
			"VN" => "Vietnam (VN)",
			"VU" => "Vanuatu (VU)",
			"WF" => "Wallis &amp; Futuna (WF)",
			"WS" => "Samoa (western) (WS)",
			"YE" => "Yemen (YE)",
			"YT" => "Mayotte (YT)",
			"ZA" => "South Africa (ZA)",
			"ZM" => "Zambia (ZM)",
			"ZW" => "Zimbabwe (ZW)");
	}


//end of class
}