<?php
/**
Plugin Name: Real Estate Website Builder
Description: Quickly create a lead generating real estate website for your real property.
Plugin URI: http://placester.com/wordpress/plugin/
Author: Placester, Inc.
Version: 0.3.9
Author URI: http://www.placester.com/developer/wordpress
*/

/*  Copyright (c) 2011 Placester, Inc. <frederick@placester.com>
	All rights reserved.

	Placester Promoter is distributed under the GNU General Public License, Version 2,
	June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
	St, Fifth Floor, Boston, MA 02110, USA

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
	ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
	ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

include_once( 'core/init.php' );
include_once( 'core/util.php' );
include_once( 'core/webservice_client.php' );
include_once( 'core/settings_functions.php' );
include_once( 'core/listings_list_util.php' );
include_once( 'options/init.php' );
include_once( 'admin/init.php' );
include_once( 'admin/widgets.php' );
include_once( 'core/shortcodes.php');
include_once( 'core/leads.php' );
include_once( 'core/membership.php' );
include_once( 'lib/debug.php' );


register_activation_hook( __FILE__, 'placester_activate' );
register_deactivation_hook( __FILE__, 'placester_deactivate' );



/**
 * Registers filter form on a page which will control 
 * property lists / property maps on this page.
 * 
 * @param string $form_dom_id - DOM id of form object containing filter
 * @param bool $echo Wether to echo or return the content.
 */
function placester_register_filter_form( $form_dom_id, $echo = true ) {

    if ( ! $echo ) {
        ob_start(); 
        require_once( 'core/register_filter_form.php' );
        $content = ob_get_contents(); 
        ob_end_clean(); 

        return $content;
    } else {
        require_once( 'core/register_filter_form.php' );
    }
}



/**
 * Prints google maps object containing properties
 * 
 * @param array $parameters - configuration data.
 *
 *        Configuration elements:
 *
 *        - js_on_marker_click =>
 *           js function name called when marker is clicked with prototype:
 *           function(markerData)
 *             markerData - array of all queried property fields
 *        - js_get_marker_class => 
 *           js function name called to get css class for marker with prototype:
 *           function(markerData)
 *             markerData - array of all queried property fields
 */
function placester_listings_map( $parameters = array(), $return = false ) {
    require_once( 'core/listings_map.php' );
    if ( !$return ) {
?>
<div id="placester_listings_map_map"></div>
<?php 
    } else {
        return '<div id="placester_listings_map_map"></div>';
    }
}



/**
 * Prints standalone list of properties
 * 
 * @param array $parameters - Configuration data.
 *        Configuration elements are different based on list mode.
 *        There are different modes defined by 'table_type' parameter.
 *
 *        For table_type = datatable list is displayed using
 *		  <a href="http://datatables.net">datatables.net</a> library. 
 *        Parameters are:
 *			- table_type => 'datatable'
 *			- paginate =>
 *            number of rows for each page
 *			- attributes
 *            array, fields to display, where key is field name
 *				- fieldname =>
 *				- label =>
 *                Name of field, how to display it
 *				- width =>
 *                Width of field
 *			- js_renderer
 *                JS function called to convert field content and return
 *                html representation of field to display
 *
 *        For table_type = html list is displayed as sequence of pure html &lt;div&gt;
 *        elements where each element represent single listing.
 *        Parameters are:
 *				- table_type => 'html'
 *				- js_row_renderer =>
 *            JS function name taking array of property fields data and 
 *            returning html to print.
 *				- pager =>
 *            array. Elements are:
 *					- render_in_dom_element =>
 *              If specified - pager will be rendered to that DOM id
 *            		- rows_per_page =>
 *              Number of properties to print at single page
 *            		- css_current_button =>
 *              CSS style of "current page" button
 *            		- css_not_current_button =>
 *              CSS style of other page-switch buttons
 *            		- first_page =>
 *              array, configuration of "first page" button of pager.
 *              parameters are:
 *						- visible =>
 *                true / false
 *						- label =>
 *                html of button' text
 *            		- previous_page =>
 *              array, configuration of "previous page" button of pager.
 *              same as for "first page"
 *            		- next_page =>
 *              array, configuration of "next page" button of pager.
 *              same as for "first page"
 *            		- last_page' => 
 *              array, configuration of "last page" button of pager.
 *              same as for "first page"
 *            		- numeric_links =>
 *              array, configuration of numeric links buttons of pager.
 *              parameters are:
 *						- visible =>
 *                true / false
 *						- max_count => 
 *                maximum number of page links to show
 *						- more_label
 *                if there are more pages than printed, this html is inserted
 *						- css_outer
 *                CSS class of outer div for numberic links
 *				- attributes =>
 *            array of fields name to extract from data storage.
 *            Dont ask for fields not displayed - that will
 *            unreasonably slow down requests.
 */
function placester_listings_list($parameters, $return = false) {

    /** Extract the crop description settings before moving forward. */
    if ( isset( $parameters['crop_description'] ) ) {
        $crop_description = $parameters['crop_description'];
        unset( $parameters['crop_description'] );
    } else {
        $crop_description = false;
    }

    require_once('core/listings_list_lone.php');

    /** Return or echo. */
    if ( $return )
        return $result; 
    echo $result;
}

/**
 * Prints list of properties which are displayed right now on the map
 * So this list can be used only on pages with map
 *
 * @param array $parameters - configuration data
 *        The same as for "placester_listings_list" function
 */
function placester_listings_list_of_map($parameters) {
    require_once('core/listings_list_of_map.php');
}
