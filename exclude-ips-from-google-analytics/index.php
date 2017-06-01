<?php
/**
 * Plugin Name: Exclude IPs From Google Analytics
 * Description: Exclude IPs From Google Analytics. You can paste google analytics code in this plugin and it will work.
 * Author: Evgeniy Poznyak
 * Author URI: https://wordpress.poznyaks.com/
 * Version: 1.00
 */

/*  Copyright 2017  Evgeniy Poznyak  (email: ek@35mm@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

register_uninstall_hook( __FILE__, 'evg_analytics_plugin_delete' );


function evg_analytics_plugin_delete() {
	delete_option( 'evg_analytics_plugin_settings_group_name' );
	unregister_setting( 'evg_ext_woo_settings_id', 'evg_analytics_plugin_settings_group_name' );

}


add_action( 'admin_menu', 'ext_analytics_plugin_menu' );


add_action( 'admin_init', 'evg_analytics_plugin_setup_settings' );


// INITIALISE GOOGLE ANALYTICS
$quickCheck = get_option( 'evg_analytics_plugin_settings_group_name' );
if (is_array( $quickCheck )){
	evgPlaceCodeFromGoogleAnalytics($quickCheck);
}
