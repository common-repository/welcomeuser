<?php
/*
Plugin Name: Welcome User!
Plugin URI: http://wordpress.org/extend/plugins/welcomeuser
Description: Add commonly seen user login links and welcome messages to the meta / utility section of your blog.
Version: 0.1
Author: Chris Carvache
Author URI: http://chriscarvache.com
License:  GPL2

Copyright 2012 Chris Carvache (email : chriscarvache@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Define all initial parameters
 */
define('NLWS_WU_VERSION', 01);
define('NLWS_WU_URL', rtrim(plugin_dir_url(__FILE__)));
define('NLWS_WU_DIR', rtrim(plugin_dir_path(__FILE__)));

/**
 * Load all necessary libraries
 */
require ('wu-functions.php');
require ('wu-menus.php');
require ('wu-views.php');

/**
 * Add actions
 */
add_action( 'admin_init', 'nlws_wu_register_settings' );
add_action( 'admin_menu', 'nlws_wu_plugin_menu' );
add_action( 'admin_enqueue_scripts', 'nlws_wu_add_scripts' );
add_shortcode( 'nlws_wu', 'nlws_wu' );
register_activation_hook( __FILE__, 'nlws_wu_activate' );