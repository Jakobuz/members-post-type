<?php
/*
 Plugin Name: Members post type
 Version: 0.1
 Plugin URI: https://github.com/herewithme/members-post-type
 Description: Manage members on WordPress as post type. Implement, post type, authentification, role, clone from WP.
 Author: Amaury Balmer
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Network: false
 Text Domain: mpt

 ----

 Copyright 2013 Amaury Balmer (amaury@beapi.fr)

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
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Plugin constants
define('MPT_VERSION', '0.0.1');
define('MPT_OPTION', 'members-post-type');
define('MPT_CPT_NAME', 'member');

// Plugin URL and PATH
define('MPT_URL', plugins_url('', __FILE__));
define('MPT_DIR', dirname(__FILE__));

// Function for easy load files
function _mpt_load_files($dir, $files, $prefix = '') {
	foreach ($files as $file) {
		if ( is_file($dir . $prefix . $file . ".php") ) {
			require_once $dir . $prefix . $file . ".php";
		}
	}	
}

// Plugin functions
_mpt_load_files(MPT_DIR . '/functions/', array('api', 'template'));

// Plugin client classes
_mpt_load_files(MPT_DIR . '/classes/', array('main', 'plugin', 'post-type', 'role', 'shortcode', 'taxonomy', 'user', 'widget'), 'class-');

// Plugin admin classes
if (is_admin()) {
	_mpt_load_files(MPT_DIR . '/classes/admin/', array('main', 'post-type', 'taxonomy'), 'class-');
}

// Plugin activate/desactive hooks
register_activation_hook(__FILE__, array('MPT_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('MPT_Plugin', 'deactivates'));

add_action('plugins_loaded', 'init_mpt_plugin');
function init_mpt_plugin() {
	// Load translations
	load_plugin_textdomain('mpt', false, basename(rtrim(MPT_DIR, '/')) . '/languages');

	// Client
	new MPT_Main();
	new MPT_Post_Type();

	// Admin
	if (is_admin()) {
		// Class admin
		new MPT_Admin_Main();
		new MPT_Admin_Post_Type();
		new MPT_Admin_Taxonomy();
	}

	// Widget
	// add_action('widgets_init', create_function('', 'return register_widget("MPT_Widget");'));
}