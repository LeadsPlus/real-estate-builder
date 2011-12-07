<?php namespace Shortcodes;
/**
 *  Something about Shorcodes
 */

/**
 * Shows a search results table. All attributes are optional.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listings_search]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listings_search max_price=1000]'); 
 * </code>
 * to your desired template.
 * 
 * @param int $max_price The maximum price of the searched properties
 * @param int $min_price The minimum price of the searched properties 
 * @param int $rows_per_page Number of rows per page in the table
 * @param string $sort_by What should be sorted by. Valid options are 
 * 'bathrooms', 'price'
 * @param bool $show_sort Wether sort controls should be shown
 * @param string $available_on The month and year of its availability e.g. 
 * "June 2012"
 * @param int $bathrooms The number of bathrooms
 * @param int $bedrooms The number of bedroom
 * @param int $city The city in which to restrict the search
 * @param int $state The State in which to restrict the search
 * @param int $zip The zip code with which to restrict the search
 *
 *
 * You can use this shortcode by adding:
 * <code>
 * [price]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[price]'); 
 * </code>
 * to your desired template.
 */
function listings_search( $max_price = 5000, $min_price = 200, $rows_per_page = 5, $sort_by = 'bathrooms', $show_sort = true, $available_on, $bathrooms, $bedrooms, $city, $state, $zip ) {
// Dust
}

/**
 * Displays the map of the listing. Can only be used on the listing single 
 * page.
 * 
 * @param int $max_price The maximum price of the searched properties
 * @param int $min_price The minimum price of the searched properties 
 * @param int $rows_per_page Number of rows per page in the table
 * @param string $sort_by What should be sorted by. Valid options are 
 * 'bathrooms', 'price'
 * @param bool $show_sort Wether sort controls should be shown
 * @param string $available_on The month and year of its availability e.g. 
 * "June 2012"
 * @param int $bathrooms The number of bathrooms
 * @param int $bedrooms The number of bedroom
 * @param int $city The city in which to restrict the search
 *
 */
function listings_map( $max_price = 5000, $min_price = 200, $rows_per_page = 5, $sort_by = 'bathrooms', $show_sort = true, $available_on, $bathrooms, $bedrooms, $city ) {
}

/**
 *  Displays the number of bedrooms. Can only be used on the listing single 
 *  page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [bedrooms]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[bedrooms]'); 
 * </code>
 * to your desired template.
 */
function bedrooms() {}

/**
 *  Displays the number of bathrooms. Can only be used on the listing single 
 *  page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [bathrooms]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[bathrooms]'); 
 * </code>
 * to your desired template.
 */
function bathrooms() {}

/**
 *  Displays the number of bathrooms. Can only be used on the listing single 
 *  page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [price]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[price]'); 
 * </code>
 * to your desired template.
 */
function price() {}

/**
 *  Displays when will the listing be available. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [price]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[price]'); 
 * </code>
 * to your desired template.
 */
function available_on() {}

/**
 *  Displays the listing address. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listings_address]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listings_address]'); 
 * </code>
 * to your desired template.
 */
function listing_address() {}

/**
 *  Displays the listing city. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_city]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_city]'); 
 * </code>
 * to your desired template.
 */
function listing_city() {}

/**
 *  Displays the listing state. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_state]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_state]'); 
 * </code>
 * to your desired template.
 */
function listing_state() {}

/**
 *  Displays the listing unit. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_unit]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_unit]'); 
 * </code>
 * to your desired template.
 */
function listing_unit() {}

/**
 *  Displays the listing zip code. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_zip]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_zip]'); 
 * </code>
 * to your desired template.
 */
function listing_zip() {}

/**
 *  Displays the listing neighborhood. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_neighborhood]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_neighborhood]'); 
 * </code>
 * to your desired template.
 */
function listing_neighborhood() {}

/**
 *  Displays a map of the listing. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_map]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_map]'); 
 * </code>
 * to your desired template.
 */
function listing_map() {}

/**
 *  Displays the listing description. Can only be used on 
 *  the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_description]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_description]'); 
 * </code>
 * to your desired template.
 */
function listing_description() {}

/**
 *  Displays a paragraph with all the listing images.
 *  Can only be used on the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_images]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_images]'); 
 * </code>
 * to your desired template.
 */
function listing_images() {}

/**
 *  Displays the first image of a listing. 
 *  Can only be used on the listing single page.
 *
 * You can use this shortcode by adding:
 * <code>
 * [listing_image]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[listing_image]'); 
 * </code>
 * to your desired template.
 */
function listing_image() {}

/**
 *  Displays the company logo. 
 *
 * You can use this shortcode by adding:
 * <code>
 * [logo]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[logo]'); 
 * </code>
 * to your desired template.
 */
function logo() {}

/**
 *  Displays the agent first name as it registered with Placester.
 *
 * You can use this shortcode by adding:
 * <code>
 * [first_name]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[first_name]'); 
 * </code>
 * to your desired template.
 */
function first_name() {}

/**
 *  Displays the agent last name as it registered with Placester.
 *
 * You can use this shortcode by adding:
 * <code>
 * [last_name]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[last_name]'); 
 * </code>
 * to your desired template.
 */
function last_name() {}

/**
 *  Displays the agent phone number.
 *
 * You can use this shortcode by adding:
 * <code>
 * [phone]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[phone]'); 
 * </code>
 * to your desired template.
 */
function phone() {}

/**
 * Displays the agent email address.
 *
 * You can use this shortcode by adding:
 * <code>
 * [email]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[email]'); 
 * </code>
 * to your desired template.
 */
function email() {}

/**
 * Displays the Company address.
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_address]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_address]'); 
 * </code>
 * to your desired template.
 */
function user_address() {}

/**
 * Displays the Company unit.
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_unit]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_unit]'); 
 * </code>
 * to your desired template.
 */
function user_unit() {}

/**
 * Displays the Company city.
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_city]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_city]'); 
 * </code>
 * to your desired template.
 */
function user_city() {}

/**
 * Displays the Company State.
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_state]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_state]'); 
 * </code>
 * to your desired template.
 */
function user_state() {}

/**
 * Displays the Company Zip Code.
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_zip]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_zip]'); 
 * </code>
 * to your desired template.
 */
function user_zip() {}

/**
 * Displays the company description
 *
 * You can use this shortcode by adding:
 * <code>
 * [user_description]
 * </code>
 * to a post contents, or by adding:
 * <code>
 * do_shortcode('[user_description]'); 
 * </code>
 * to your desired template.
 */
function user_description() {}
