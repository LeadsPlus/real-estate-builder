<?php 

global $PL_API_LISTINGS;
$PL_API_LISTINGS = array(
	'get' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'GET'
		),
		'args' => array(
			'listing_ids',
			'listing_types' => array(
				'group' => 'advanced',
				'type' => 'multiselect',
				'options' => array(
					'sublet' => 'Sublet',
					'res_sale' => 'Residential Sale',
					'vac_rental' => 'Vacation Rental',
					'res_rental' => 'Residential Rental',
					'comm_rental' => 'Commercial Rental',
					'comm_sale' => 'Commercial Sale',
				)
			),
			// 'property_type.sublet' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		'penthouse' => 'Penthouse',
			//         'apartment' => 'Apartment',
			//         'condo' => 'Condo',
			//         'townhouse' => 'Townhouse',
			//         'duplex' => 'Duplex',
			//         'fam_home' => 'Single Family Home'
			// 	)
			// ),
			// 'property_type.res_sale' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		'penthouse' => 'Penthouse',
			//         'apartment' => 'Apartment',
			//         'condo' => 'Condo',
			//         'duplex' => 'Duplex',
			//         'fam_home' => 'Single Family Home',
			//         'multi_fam' => 'Multi Family Home',
			//         'coop' => 'Cooperative',
			//         'tic' => 'Tenants In Common',
			//         'manuf' =>  'Manufactured Home',
			//         'vacant' =>  'Vacant'
			// 	)
			// ),
			// 'property_type.vac_rental' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		'penthouse' => 'Penthouse',
			//         'apartment' => 'Apartment',
			//         'condo' => 'Condo',
			//         'townhouse' => 'Townhouse',
			//         'duplex' => 'Duplex',
			//         'fam_home' => 'Single Family Home',
			//         'multi_fam' => 'Multi Family Home',
			//         'coop' => 'Cooperative',
			//         'tic' => 'Tenants In Common',
			//         'manuf' =>  'Manufactured Home',
			//         'vacant' =>  'Vacant'	
			// 	)
			// ),
			// 'property_type.res_rental' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		'penthouse' => 'Penthouse',
			//         'apartment' => 'Apartment',
			//         'condo' => 'Condo',
			//         'townhouse' => 'Townhouse',
			//         'duplex' => 'Duplex',
			//         'fam_home' => 'Single Family Home',
			// 	)
			// ),
			// 'property_type.comm_rental' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		// Office           
			//         'off_loft' => 'off_loft',
			//         'off_gen' => 'off_gen',
			//         'off_inst_gov' => 'off_inst_gov',
			//         'off_med' => 'off_med',
			//         'off_rd' => 'off_rd',
			//         // Industrial
			//         'ind_flex' => 'ind_flex',
			//         'ind_manuf' => 'ind_manuf',
			//         'ind_off_shw' => 'ind_off_shw',
			//         'ind_term_trans' => 'ind_term_trans',
			//         'ind_dist_warh' => 'ind_dist_warh',
			//         'ind_warh' => 'ind_warh',
			//         'ind_ref_str' => 'ind_ref_str',
			//         // Retail
			//         'ret_outlet' => 'ret_outlet',
			//         'ret_comm' => 'ret_comm',
			//         'ret_strip' => 'ret_strip',
			//         'ret_nghbr' => 'ret_nghbr',
			//         'ret_reg' => 'ret_reg',
			//         'ret_sup_reg' => 'ret_sup_reg',
			//         'ret_special' => 'ret_special',
			//         'ret_theme' => 'ret_theme',
			//         'ret_anchor' => 'ret_anchor',
			//         'ret_resta' => 'ret_resta',
			//         'ret_pad' => 'ret_pad',
			//         'ret_free_stnd' => 'ret_free_stnd',
			//         'ret_strt_ret' => 'ret_strt_ret',
			//         'ret_veh_rel' => 'ret_veh_rel',
			//         'ret_other' => 'ret_other',
			//         // Land
			//         'lan_indust' => 'lan_indust',
			//         'lan_office' => 'lan_office',
			//         'lan_resid' => 'lan_resid',
			//         'lan_ret' => 'lan_ret',
			//         'lan_ret_pad' => 'lan_ret_pad',
			//         'lan_comm' => 'lan_comm'
			// 	)
			// ),
			// 'property_type.comm_sale' => array(
			// 	'type' => 'multiselect',
			// 	'options' => array(
			// 		// Office           
			//         'off_loft' => 'off_loft',
			//         'off_gen' => 'off_gen',
			//         'off_inst_gov' => 'off_inst_gov',
			//         'off_med' => 'off_med',
			//         'off_rd' => 'off_rd',
			//         // Industrial
			//         'ind_flex' => 'ind_flex',
			//         'ind_manuf' => 'ind_manuf',
			//         'ind_off_shw' => 'ind_off_shw',
			//         'ind_term_trans' => 'ind_term_trans',
			//         'ind_dist_warh' => 'ind_dist_warh',
			//         'ind_warh' => 'ind_warh',
			//         'ind_ref_str' => 'ind_ref_str',
			//         // Retail
			//         'ret_outlet' => 'ret_outlet',
			//         'ret_comm' => 'ret_comm',
			//         'ret_strip' => 'ret_strip',
			//         'ret_nghbr' => 'ret_nghbr',
			//         'ret_reg' => 'ret_reg',
			//         'ret_sup_reg' => 'ret_sup_reg',
			//         'ret_special' => 'ret_special',
			//         'ret_theme' => 'ret_theme',
			//         'ret_anchor' => 'ret_anchor',
			//         'ret_resta' => 'ret_resta',
			//         'ret_pad' => 'ret_pad',
			//         'ret_free_stnd' => 'ret_free_stnd',
			//         'ret_strt_ret' => 'ret_strt_ret',
			//         'ret_veh_rel' => 'ret_veh_rel',
			//         'ret_other' => 'ret_other',
			//         // Land
			//         'lan_indust' => 'lan_indust',
			//         'lan_office' => 'lan_office',
			//         'lan_resid' => 'lan_resid',
			//         'lan_ret' => 'lan_ret',
			//         'lan_ret_pad' => 'lan_ret_pad',
			//         'lan_comm' => 'lan_comm'
			// 	)
			// ),
			'zoning_types' => array(
				'type' => 'multiselect',
				'group' => 'advanced',
				'options' => array(
					'residential' => 'Residential',
					'commercial' => 'Commercial'
				)
			),
			'purchase_types' => array(
				'type' => 'multiselect',
				'group' => 'advanced',
				'options' => array(
					'sale' => 'Sale',
					'rental' => 'Rental'
				)
			),
			// binds to building id
			'building_id',// => array('type' => 'text'),
			'location' => array(
				'postal' => array(
					'type' => 'multiselect',
					'group' => 'basic',
					'bound' => array(
						'class' => 'PL_Listing',
						'method' => 'locations_for_options',
						'params' => 'postal'
					)
				),
				'region'  => array(
					'type' => 'multiselect',
					'group' => 'basic',
					'bound' => array(
						'class' => 'PL_Listing',
						'method' => 'locations_for_options',
						'params' => 'region'
					)
				),
				'locality'  => array(
					'type' => 'multiselect',
					'group' => 'basic',
					'bound' => array(
						'class' => 'PL_Listing',
						'method' => 'locations_for_options',
						'params' => 'locality'
					)
				)
			),
			// binds to keys / values of all attributes (cur + uncur)
			'metadata' => array(
				'beds' => array('type' => 'text','group' => 'basic'),
				'max_beds' => array('type' => 'text', 'group' => 'advanced'),
				'min_beds' => array('type' => 'text', 'group' => 'advanced'),
                'baths' => array('type' => 'text', 'group' => 'basic'),
                'max_baths' => array('type' => 'text', 'group' => 'advanced'),
                'min_baths' => array('type' => 'text', 'group' => 'advanced'),
                'half_baths' => array('type' => 'text', 'group' => 'basic'),
                'max_half_baths' => array('type' => 'text', 'group' => 'advanced'),
                'min_half_baths' => array('type' => 'text', 'group' => 'advanced'),
                'price' => array('type' => 'text', 'group' => 'basic'),
                'max_price' => array('type' => 'text', 'group' => 'advanced'),
                'min_price' => array('type' => 'text', 'group' => 'advanced'),
                'sqft' => array('type' => 'text', 'group' => 'basic'),
                'max_sqft' => array('type' => 'text', 'group' => 'advanced'),
                'min_sqft' => array('type' => 'text', 'group' => 'advanced'),
                'avail_on' => array('type' => 'date', 'group' => 'advanced'),
                'max_avail_on' => array('type' => 'date', 'group' => 'basic'),
                'min_avail_on' => array('type' => 'date', 'group' => 'basic'),
                'desc' => array('type' => 'checkbox', 'group' => 'advanced'),
                'lt_sz' => array('type' => 'text', 'group' => 'advanced'),
                'ngb_shop' => array('type' => 'checkbox', 'group' => 'advanced'),
                'ngb_hgwy' => array('type' => 'checkbox', 'group' => 'advanced'),
                'grnt_tops' => array('type' => 'checkbox', 'group' => 'advanced'),
                'ngb_med' => array('type' => 'checkbox', 'group' => 'advanced'),
                'ngb_trails' => array('type' => 'checkbox', 'group' => 'advanced'),
                'cent_ht' => array('type' => 'checkbox', 'group' => 'advanced'),
                'pk_spce' => array('type' => 'checkbox', 'group' => 'advanced'),
                'air_cond' => array('type' => 'checkbox', 'group' => 'advanced'),
                'lse_trms',// => array('type' => 'select','options' => array('per_mnt' => 'Per Month')),
                'ngb_trans' => array('type' => 'checkbox', 'group' => 'advanced'),
                'off_den' => array('type' => 'checkbox', 'group' => 'advanced'),
                'frnshed' => array('type' => 'checkbox', 'group' => 'advanced'),
                'refrig' => array('type' => 'checkbox', 'group' => 'advanced'),
                'deposit' => array('type' => 'checkbox', 'group' => 'advanced'),
                'ngb_pubsch' => array('type' => 'checkbox', 'group' => 'advanced'),
				'key',
				'value'
			),
			'box' => array(
				'min_latitude',// => array('type' => 'text'),
				'min_longitude',// => array('type' => 'text'),
				'max_latitude',// => array('type' => 'text'),
				'max_longitude'// => array('type' => 'text')
			),
			'include_disabled' => array('type' => 'checkbox', 'group' => 'basic'),
			'address_mode',// => array('type' => 'select','options' => array('exact' => 'Exact','polygon' => 'Polygon')),
			'limit', // => array('type' => 'text'),
			'offset', // => array('type' => 'text'),
			// Field to sort by, can be any field returned from the API, for uncurated fields use _uncur_data.<key>_ for curated use _cur_data.<key>_
			'sort_by',
			'sort_type'// => array('type' => 'select','options' => array('asc' => 'Ascending','desc' =>'Decending'))
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
	'create' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'POST'
		),
		'args' => array(
			'listing_types' => array(
				'label' => 'Listing Type',
				'group' => 'Listing Type',
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
			),
			'images' => array(
			)
		),
		'returns' => array(
		)
	),
	'update' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'GET'
		),
		'args' => array(
		),
		'returns' => array(
		)
	),
	'delete' => array(
		'request' => array(
			'url' => 'https://api.placester.com/v2.0/listings',
			'type' => 'GET'
		),
		'args' => array(
		),
		'returns' => array(
		)
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