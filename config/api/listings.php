<?php 

global $PL_API_LISTINGS;
$PL_API_LISTINGS = array(
	'get' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'GET'
		),
		'args' => array(
			'listing_ids'  => array(),
			'listing_types' => array(
				'label' => 'Listing Types',
				'group' => 'listing types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					'sublet' => 'Sublet',
					'res_sale' => 'Residential Sale',
					'vac_rental' => 'Vacation Rental',
					'res_rental' => 'Residential Rental',
					'comm_rental' => 'Commercial Rental',
					'comm_sale' => 'Commercial Sale',
				)
			),
			'property_type.sublet' => array(
				'label' => 'Sublet Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home'
				)
			),
			'property_type.res_sale' => array(
				'label' => 'Residential Sale Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
			        'multi_fam' => 'Multi Family Home',
			        'coop' => 'Cooperative',
			        'tic' => 'Tenants In Common',
			        'manuf' =>  'Manufactured Home',
			        'vacant' =>  'Vacant'
				)
			),
			'property_type.vac_rental' => array(
				'label' => 'Vacation Rental Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
			        'multi_fam' => 'Multi Family Home',
			        'coop' => 'Cooperative',
			        'tic' => 'Tenants In Common',
			        'manuf' =>  'Manufactured Home',
			        'vacant' =>  'Vacant'	
				)
			),
			'property_type.res_rental' => array(
				'label' => 'Residental Rental Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
				)
			),
			'property_type.comm_rental' => array(
				'label' => 'Commercial Rental Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					// Office           
			        'off_loft' => 'off_loft',
			        'off_gen' => 'off_gen',
			        'off_inst_gov' => 'off_inst_gov',
			        'off_med' => 'off_med',
			        'off_rd' => 'off_rd',
			        // Industrial
			        'ind_flex' => 'ind_flex',
			        'ind_manuf' => 'ind_manuf',
			        'ind_off_shw' => 'ind_off_shw',
			        'ind_term_trans' => 'ind_term_trans',
			        'ind_dist_warh' => 'ind_dist_warh',
			        'ind_warh' => 'ind_warh',
			        'ind_ref_str' => 'ind_ref_str',
			        // Retail
			        'ret_outlet' => 'ret_outlet',
			        'ret_comm' => 'ret_comm',
			        'ret_strip' => 'ret_strip',
			        'ret_nghbr' => 'ret_nghbr',
			        'ret_reg' => 'ret_reg',
			        'ret_sup_reg' => 'ret_sup_reg',
			        'ret_special' => 'ret_special',
			        'ret_theme' => 'ret_theme',
			        'ret_anchor' => 'ret_anchor',
			        'ret_resta' => 'ret_resta',
			        'ret_pad' => 'ret_pad',
			        'ret_free_stnd' => 'ret_free_stnd',
			        'ret_strt_ret' => 'ret_strt_ret',
			        'ret_veh_rel' => 'ret_veh_rel',
			        'ret_other' => 'ret_other',
			        // Land
			        'lan_indust' => 'lan_indust',
			        'lan_office' => 'lan_office',
			        'lan_resid' => 'lan_resid',
			        'lan_ret' => 'lan_ret',
			        'lan_ret_pad' => 'lan_ret_pad',
			        'lan_comm' => 'lan_comm'
				)
			),
			'property_type.comm_sale' => array(
				'label' => 'Commercial Sale Property Types',
				'type' => 'select',
				'options' => array(
					'false' => 'Any',
					// Office           
			        'off_loft' => 'off_loft',
			        'off_gen' => 'off_gen',
			        'off_inst_gov' => 'off_inst_gov',
			        'off_med' => 'off_med',
			        'off_rd' => 'off_rd',
			        // Industrial
			        'ind_flex' => 'ind_flex',
			        'ind_manuf' => 'ind_manuf',
			        'ind_off_shw' => 'ind_off_shw',
			        'ind_term_trans' => 'ind_term_trans',
			        'ind_dist_warh' => 'ind_dist_warh',
			        'ind_warh' => 'ind_warh',
			        'ind_ref_str' => 'ind_ref_str',
			        // Retail
			        'ret_outlet' => 'ret_outlet',
			        'ret_comm' => 'ret_comm',
			        'ret_strip' => 'ret_strip',
			        'ret_nghbr' => 'ret_nghbr',
			        'ret_reg' => 'ret_reg',
			        'ret_sup_reg' => 'ret_sup_reg',
			        'ret_special' => 'ret_special',
			        'ret_theme' => 'ret_theme',
			        'ret_anchor' => 'ret_anchor',
			        'ret_resta' => 'ret_resta',
			        'ret_pad' => 'ret_pad',
			        'ret_free_stnd' => 'ret_free_stnd',
			        'ret_strt_ret' => 'ret_strt_ret',
			        'ret_veh_rel' => 'ret_veh_rel',
			        'ret_other' => 'ret_other',
			        // Land
			        'lan_indust' => 'lan_indust',
			        'lan_office' => 'lan_office',
			        'lan_resid' => 'lan_resid',
			        'lan_ret' => 'lan_ret',
			        'lan_ret_pad' => 'lan_ret_pad',
			        'lan_comm' => 'lan_comm'
				)
			),
			'zoning_types' => array(
				'type' => 'select',
				'label' => 'Zoning',
				'group' => 'listing types',
				'options' => array(
					'false' => 'Any',
					'residential' => 'Residential',
					'commercial' => 'Commercial'
				)
			),
			'purchase_types' => array(
				'type' => 'select',
				'label' => 'Purchase',
				'group' => 'listing types',
				'options' => array(
					'false' => 'Any',
					'sale' => 'Sale',
					'rental' => 'Rental'
				)
			),
			// binds to building id
			'building_id' => array(),// => array('type' => 'text'),
			'location' => array(
				'postal' => array(
					'label' => 'Zip',
					'type' => 'select',
					'group' => 'location',
					'bound' => array(
						'class' => 'PL_Listing_Helper',
						'method' => 'locations_for_options',
						'params' => 'postal'
					)
				),
				'region'  => array(
					'label' => 'State',
					'type' => 'select',
					'group' => 'location',
					'bound' => array(
						'class' => 'PL_Listing_Helper',
						'method' => 'locations_for_options',
						'params' => 'region'
					)
				),
				'locality'  => array(
					'label' => 'City',
					'type' => 'select',
					'group' => 'location',
					'bound' => array(
						'class' => 'PL_Listing_Helper',
						'method' => 'locations_for_options',
						'params' => 'locality'
					)
				)
			),
			// binds to keys / values of all attributes (cur + uncur)
			'metadata' => array(
				'beds' => array(
					'label' => 'Beds',
					'type' => 'select',
					'group' => 'basic',
					'options' => array(
						'false' => 'Any',
						'0' => 'Studio',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
					)
				),
				'max_beds' => array('type' => 'text', 'group' => 'advanced'),
				'min_beds' => array('type' => 'text', 'group' => 'advanced'),
                'baths' => array(
                	'label' => 'Baths',
	                'type' => 'select',
	                'group' => 'basic',
	                'options' => array(
						'false' => 'Any',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
					)
	            ),
                'max_baths' => array('type' => 'text', 'group' => 'advanced'),
                'min_baths' => array('type' => 'text', 'group' => 'advanced'),
                'half_baths' => array(
                	'label' => 'Half Baths',
	                'type' => 'select',
	                'group' => 'basic',
	                'options' => array(
						'false' => 'Any',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
					)
	            ),
                'max_half_baths' => array(),// => array('type' => 'text', 'group' => 'advanced'),
                'min_half_baths' => array(),// => array('type' => 'text', 'group' => 'advanced'),
                'price'  => array(),// => array('type' => 'text', 'group' => 'basic'),
                'max_price' => array(
                	'label' => 'Max Price',
	                'type' => 'select',
	                'group' => 'basic',
					'bound' => array(
						'class' => 'PL_Listing_Helper',
						'method' => 'pricing_min_options',
						'params' => 'max'
					)
					
	            ),
                'min_price' => array(
                	'label' => 'Min Price',
	                'type' => 'select',
	                'group' => 'basic',
					'bound' => array(
						'class' => 'PL_Listing_Helper',
						'method' => 'pricing_min_options',
						'params' => 'min'
					)
					
	            ),
                'sqft' => array(),// => array('type' => 'text', 'group' => 'basic'),
                'max_sqft' => array('type' => 'text', 'group' => 'advanced'),
                'min_sqft' => array('type' => 'text', 'group' => 'advanced'),
                'avail_on' => array(),// => array('type' => 'date', 'group' => 'advanced'),
                'max_avail_on' => array('type' => 'date', 'group' => 'basic', 'label' => 'Latest Available Date'),
                'min_avail_on' => array('type' => 'date', 'group' => 'basic', 'label' => 'Earliest Available Date'),
                'desc' => array('type' => 'checkbox', 'group' => 'advanced'),
                'lt_sz' => array('type' => 'text', 'group' => 'advanced'),
                'ngb_shop' => array('type' => 'checkbox', 'group' => 'amenities'),
                'ngb_hgwy' => array('type' => 'checkbox', 'group' => 'amenities'),
                'grnt_tops' => array('type' => 'checkbox', 'group' => 'amenities'),
                'ngb_med' => array('type' => 'checkbox', 'group' => 'amenities'),
                'ngb_trails' => array('type' => 'checkbox', 'group' => 'amenities'),
                'cent_ht' => array('type' => 'checkbox', 'group' => 'amenities'),
                'pk_spce' => array('type' => 'checkbox', 'group' => 'amenities'),
                'air_cond' => array('type' => 'checkbox', 'group' => 'amenities'),
                'lse_trms' => array(),// => array('type' => 'select','options' => array('per_mnt' => 'Per Month')),
                'ngb_trans' => array('type' => 'checkbox', 'group' => 'amenities'),
                'off_den' => array('type' => 'checkbox', 'group' => 'amenities'),
                'frnshed' => array('type' => 'checkbox', 'group' => 'amenities'),
                'refrig' => array('type' => 'checkbox', 'group' => 'amenities'),
                'deposit' => array('type' => 'checkbox', 'group' => 'amenities'),
                'ngb_pubsch' => array('type' => 'checkbox', 'group' => 'amenities'),
			),
			'custom' => array(
				'type' => 'bundle',
				'group' => '',
				'id' => 'custom',
				'bound' => array(
					'class' => 'PL_Listing_Helper',
					'method' => 'custom_attributes',
				)
			),
			'box' => array(
				'min_latitude' => array(),// => array('type' => 'text'),
				'min_longitude' => array(),// => array('type' => 'text'),
				'max_latitude' => array(),// => array('type' => 'text'),
				'max_longitude' => array()// => array('type' => 'text')
			),
			'include_disabled' => array('type' => 'checkbox', 'group' => 'basic','label' => 'Include Inactive Listings'),
			'address_mode' => array(),// => array('type' => 'select','options' => array('exact' => 'Exact','polygon' => 'Polygon')),
			'limit' => array(), // => array('type' => 'text'),
			'offset' => array(), // => array('type' => 'text'),
			// Field to sort by, can be any field returned from the API, for uncurated fields use _uncur_data.<key>_ for curated use _cur_data.<key>_
			'sort_by' => array(),
			'sort_type' => array()// => array('type' => 'select','options' => array('asc' => 'Ascending','desc' =>'Decending'))
		),
		'returns' => array(
			'property_type' => false,
			'zoning_types' => false,
			'purchase_types' => false,
			'listing_types' => false,
			'building_id' => false,
			'cur_data' => array(
				'half_baths' => false,
                'price' => false,
                'sqft' => false,
                'baths' => false,
                'avail_on' => false,
                'beds' => false,
                'url' => false,
                'desc' => false,
                'lt_sz' => false,
                'ngb_shop' => false,
                'ngb_hgwy' => false,
                'grnt_tops' => false,
                'ngb_med' => false,
                'ngb_trails' => false,
                'cent_ht' => false,
                'pk_spce' => false,
                'air_cond' => false,
                'price_unit' => false,
                'lt_sz_unit' => false,
                'lse_trms' => false,
                'ngb_trans' => false,
                'off_den' => false,
                'frnshed' => false,
                'refrig' => false,
                'deposit' => false,
                'ngb_pubsch' => false
			),
			'uncur_data' => false,
			'location' => array(
				'address' => false,
				'locality' => false,
				'region' => false,
				'postal' => false,
				'neighborhood' => false,
				'country' => false,
				'coords' => array(
					'latitude' => false,
					'longitude' => false
				)
			),
			'contact' => array(
				'email' => false,
				'phone' => false
			),
			'images' => false,
			'tracker_url' => false
		)
	),
	'details' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2.0/listings/',
			'type' => 'GET'
		),
		'args' => array(),
		'returns' => array(
			'property_type' => false,
			'zoning_types' => false,
			'purchase_types' => false,
			'listing_types' => false,
			'building_id' => false,
			'cur_data' => array(
				'half_baths' => false,
                'price' => false,
                'sqft' => false,
                'baths' => false,
                'avail_on' => false,
                'beds' => false,
                'url' => false,
                'desc' => false,
                'lt_sz' => false,
                'ngb_shop' => false,
                'ngb_hgwy' => false,
                'grnt_tops' => false,
                'ngb_med' => false,
                'ngb_trails' => false,
                'cent_ht' => false,
                'pk_spce' => false,
                'air_cond' => false,
                'price_unit' => false,
                'lt_sz_unit' => false,
                'lse_trms' => false,
                'ngb_trans' => false,
                'off_den' => false,
                'frnshed' => false,
                'refrig' => false,
                'deposit' => false,
                'ngb_pubsch' => false
			),
			'uncur_data' => false,
			'location' => array(
				'address' => false,
				'locality' => false,
				'region' => false,
				'postal' => false,
				'neighborhood' => false,
				'country' => false,
				'coords' => array(
					'latitude' => false,
					'longitude' => false
				)
			),
			'contact' => array(
				'email' => false,
				'phone' => false
			),
			'images' => false,
			'tracker_url' => false
		)
	),
	'create' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'POST'
		),
		'args' => array(
			'compound_type' => array(
				'label' => 'Listing Type',
				'group' => 'Basic Details',
				'type' => 'select',
				'options' => array(
					'sublet' => 'Sublet',
					'res_sale' => 'Residential Sale',
					'vac_rental' => 'Vacation Rental',
					'res_rental' => 'Residential Rental',
					'comm_rental' => 'Commercial Rental',
					'comm_sale' => 'Commercial Sale',
				)
			),
			'property_type-sublet' => array(
				'type' => 'select',
				'label' => 'Sublet Property Type',
				'group' => 'Basic Details',
				'options' => array(
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home'
				)
			),
			'property_type-res_sale' => array(
				'type' => 'select',
				'label' => 'Residential Sale Property Type',
				'group' => 'Basic Details',
				'options' => array(
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
			        'multi_fam' => 'Multi Family Home',
			        'coop' => 'Cooperative',
			        'tic' => 'Tenants In Common',
			        'manuf' =>  'Manufactured Home',
			        'vacant' =>  'Vacant'
				)
			),
			'property_type-vac_rental' => array(
				'type' => 'select',
				'label' => 'Vacation Rental Property Type',
				'group' => 'Basic Details',
				'options' => array(
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
			        'multi_fam' => 'Multi Family Home',
			        'coop' => 'Cooperative',
			        'tic' => 'Tenants In Common',
			        'manuf' =>  'Manufactured Home',
			        'vacant' =>  'Vacant'	
				)
			),
			'property_type-res_rental' => array(
				'type' => 'select',
				'label' => 'Residential Rental Property Type',
				'group' => 'Basic Details',
				'options' => array(
					'penthouse' => 'Penthouse',
			        'apartment' => 'Apartment',
			        'condo' => 'Condo',
			        'townhouse' => 'Townhouse',
			        'duplex' => 'Duplex',
			        'fam_home' => 'Single Family Home',
				)
			),
			'property_type-comm_rental' => array(
				'type' => 'select',
				'label' => 'Commercial Rental Property Type',
				'group' => 'Basic Details',
				'options' => array(
					// Office           
			        'off_loft' => 'off_loft',
			        'off_gen' => 'off_gen',
			        'off_inst_gov' => 'off_inst_gov',
			        'off_med' => 'off_med',
			        'off_rd' => 'off_rd',
			        // Industrial
			        'ind_flex' => 'ind_flex',
			        'ind_manuf' => 'ind_manuf',
			        'ind_off_shw' => 'ind_off_shw',
			        'ind_term_trans' => 'ind_term_trans',
			        'ind_dist_warh' => 'ind_dist_warh',
			        'ind_warh' => 'ind_warh',
			        'ind_ref_str' => 'ind_ref_str',
			        // Retail
			        'ret_outlet' => 'ret_outlet',
			        'ret_comm' => 'ret_comm',
			        'ret_strip' => 'ret_strip',
			        'ret_nghbr' => 'ret_nghbr',
			        'ret_reg' => 'ret_reg',
			        'ret_sup_reg' => 'ret_sup_reg',
			        'ret_special' => 'ret_special',
			        'ret_theme' => 'ret_theme',
			        'ret_anchor' => 'ret_anchor',
			        'ret_resta' => 'ret_resta',
			        'ret_pad' => 'ret_pad',
			        'ret_free_stnd' => 'ret_free_stnd',
			        'ret_strt_ret' => 'ret_strt_ret',
			        'ret_veh_rel' => 'ret_veh_rel',
			        'ret_other' => 'ret_other',
			        // Land
			        'lan_indust' => 'lan_indust',
			        'lan_office' => 'lan_office',
			        'lan_resid' => 'lan_resid',
			        'lan_ret' => 'lan_ret',
			        'lan_ret_pad' => 'lan_ret_pad',
			        'lan_comm' => 'lan_comm'
				)
			),
			'property_type-comm_sale' => array(
				'type' => 'select',
				'label' => 'Commercial Sale Property Type',
				'group' => 'Basic Details',
				'options' => array(
					// Office           
			        'off_loft' => 'off_loft',
			        'off_gen' => 'off_gen',
			        'off_inst_gov' => 'off_inst_gov',
			        'off_med' => 'off_med',
			        'off_rd' => 'off_rd',
			        // Industrial
			        'ind_flex' => 'ind_flex',
			        'ind_manuf' => 'ind_manuf',
			        'ind_off_shw' => 'ind_off_shw',
			        'ind_term_trans' => 'ind_term_trans',
			        'ind_dist_warh' => 'ind_dist_warh',
			        'ind_warh' => 'ind_warh',
			        'ind_ref_str' => 'ind_ref_str',
			        // Retail
			        'ret_outlet' => 'ret_outlet',
			        'ret_comm' => 'ret_comm',
			        'ret_strip' => 'ret_strip',
			        'ret_nghbr' => 'ret_nghbr',
			        'ret_reg' => 'ret_reg',
			        'ret_sup_reg' => 'ret_sup_reg',
			        'ret_special' => 'ret_special',
			        'ret_theme' => 'ret_theme',
			        'ret_anchor' => 'ret_anchor',
			        'ret_resta' => 'ret_resta',
			        'ret_pad' => 'ret_pad',
			        'ret_free_stnd' => 'ret_free_stnd',
			        'ret_strt_ret' => 'ret_strt_ret',
			        'ret_veh_rel' => 'ret_veh_rel',
			        'ret_other' => 'ret_other',
			        // Land
			        'lan_indust' => 'lan_indust',
			        'lan_office' => 'lan_office',
			        'lan_resid' => 'lan_resid',
			        'lan_ret' => 'lan_ret',
			        'lan_ret_pad' => 'lan_ret_pad',
			        'lan_comm' => 'lan_comm'
				)
			),
			'location' => array(
				'address' => array('type' => 'text','group' => 'location', 'label' => 'Address'), 
				'locality'  => array('type' => 'text','group' => 'location', 'label' => 'City'),
				'region'  => array('type' => 'text','group' => 'location', 'label' => 'State'),
				'postal' => array('type' => 'text','group' => 'location', 'label' => 'Zip Code'),
				'unit'  => array('type' => 'text','group' => 'location', 'label' => 'Unit'),
				'neighborhood'  => array('type' => 'text','group' => 'location', 'label' => 'Neighborhood'),
				'country'  => array('type' => 'text','group' => 'location', 'label' => 'Country')
			),
			// // binds to keys / values of all attributes (cur + uncur)
			'metadata' => array(
				'beds' => array('type' => 'text','group' => 'basic details', 'label' => 'Bedrooms'),
                'baths' => array('type' => 'text', 'group' => 'basic details', 'label' => 'Bathrooms'),
                'half_baths' => array('type' => 'text', 'group' => 'basic details', 'label' => 'Half Bathrooms'),
                'price' => array('type' => 'text', 'group' => 'basic details', 'label' => 'Price'),
                'sqft' => array('type' => 'text', 'group' => 'basic details', 'label' => 'Square Feet'),
                'avail_on' => array('type' => 'date', 'group' => 'basic details', 'label' => 'Available On'),
                'desc' => array('type' => 'textarea', 'group' => 'description', 'label' => 'Description'),
                'lse_trms' => array('type' => 'select', 'group' => 'Transaction Details','label' => 'Lease Terms', 'options' => array('per_mnt' => 'Per Month')),
                'lt_sz' => array('type' => 'text', 'group' => 'Lot Details', 'label' => 'Lot Size'),
                'ngb_pubsch' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'ngb_trans' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'ngb_med' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'ngb_trails' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'ngb_shop' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'ngb_hgwy' => array('type' => 'checkbox', 'group' => 'Neighborhood Amenities'),
                'grnt_tops' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'cent_ht' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'pk_spce' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'air_cond' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'off_den' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'frnshed' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'refrig' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
                'deposit' => array('type' => 'checkbox', 'group' => 'Listing Amenities'),
			),
			'uncur_data' => array(
				'type' => 'bundle',
				'group' => '',
				'bound' => array(
					'class' => 'PL_Listing_Helper',
					'method' => 'custom_attributes',
				)
			),
			'custom_data' => array(
				'type' => 'custom_data',
				'group' => 'Custom Amenities'
			),
			'images' => array(
				'type' => 'image',
				'group' => 'Upload Images',
				'label' => 'Select Files'
			)
		),
		'returns' => array(
		)
	),
	'temp_image' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2.0/listings/media/temp/image',
			'type' => 'POST'
		),
		'args' => array(
			'file'
		),
		'returns' => array()
	),
	'update' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'PUT'
		),
		'args' => array(),
		'returns' => array()
	),
	'delete' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'DELETE'
		),
		'args' => array(),
		'returns' => array()
	),
	'get.locations' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2.0/listings/locations/',
			'type' => 'GET'
		),
		'args' => array(
			'include_disabled' => array(
				'type' => 'checkbox'
			)
		),
		'returns' => array(
			'postal' => array(),
			'region'  => array(),
			'locality' => array()
		)
	)
);