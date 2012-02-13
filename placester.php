<?php
/**
Plugin Name: Real Estate Website Builder
Description: Quickly create a lead generating real estate website for your real property.
Plugin URI: http://placester.com/
Author: Placester, Inc.
Version: 0.3.10
Author URI: http://www.placester.com/
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

define( 'PL_PARENT_DIR', dirname(__FILE__) );
define( 'PL_PARENT_URL', trailingslashit(plugins_url()) . 'placester/'  );

define( 'PL_VIEWS_DIR', trailingslashit(PL_PARENT_DIR) . 'views/' );
define( 'PL_VIEWS_URL', trailingslashit(PL_PARENT_URL) . 'views/' );

define( 'PL_VIEWS_ADMIN_DIR', trailingslashit(PL_VIEWS_DIR) . 'admin/' );
define( 'PL_VIEWS_ADMIN_URL', trailingslashit(PL_VIEWS_URL) . 'admin/' );

define( 'PL_JS_DIR', trailingslashit(PL_PARENT_DIR) . 'js/' );
define( 'PL_JS_URL', trailingslashit(PL_PARENT_URL) . 'js/' );

define( 'PL_JS_LIB_DIR', trailingslashit(PL_JS_DIR) . 'lib/' );
define( 'PL_JS_LIB_URL', trailingslashit(PL_JS_URL) . 'lib/' );

define( 'PL_CSS_DIR', trailingslashit(PL_PARENT_DIR) . 'css/' );
define( 'PL_CSS_URL', trailingslashit(PL_PARENT_URL) . 'css/' );

define( 'PL_CSS_ADMIN_DIR', trailingslashit(PL_CSS_DIR) . 'admin/' );
define( 'PL_CSS_ADMIN_URL', trailingslashit(PL_CSS_URL) . 'admin/' );


//config
include_once('config/toggle_form_sections.php');
include_once('config/api/custom_attributes.php');
include_once('config/api/listings.php');
include_once('lib/config.php');

//lib
include_once('lib/routes.php');
include_once('lib/http.php');
include_once('lib/debug.php');
include_once('lib/form.php');
include_once('lib/validation.php');

//models
include_once('models/listing.php');
include_once('models/custom_attribute.php');
include_once('models/options.php');

//helpers
include_once('helpers/listing.php');
include_once('helpers/option.php');
include_once('helpers/compatibility.php');
include_once('helpers/css.php');
include_once('helpers/js.php');
// end new

register_activation_hook( __FILE__, 'placester_activate' );
register_deactivation_hook( __FILE__, 'placester_deactivate' );




add_action( 'admin_menu', 'placester_admin_menu' );
function placester_admin_menu() {
    // Add separator
    global $menu;
    $menu['3a'] = array( '', 'read', 'separator1', '', 'wp-menu-separator' );

    // Add Placester Menu
    add_menu_page('Placester','Placester','edit_pages','placester',array('PL_Router','my_listings'), plugins_url('/placester/images/logo_16.png'), '3b' /* position between 3 and 4 */ );

    // Avoid submenu to start with menu function
    global $submenu;
    $submenu['placester'] = array();

    add_submenu_page( 'placester', '','My Listings', 'edit_pages', 'placester_properties', array('PL_Router','my_listings'));
    add_submenu_page( 'placester', '', 'Add Listing', 'edit_pages', 'placester_property_add', array('PL_Router','add_listings') );
    // add_submenu_page( 'placester', '', 'Theme Gallery', 'edit_pages', 'placester_settings', 'placester_admin_settings_html' );    
    // add_submenu_page( 'placester', '', 'Settings', 'edit_pages', 'placester_settings', 'placester_admin_settings_html' );    
}




















































































































