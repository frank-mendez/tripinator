<?php

/*Plugin Name: Tripinator
Plugin URI: https://github.com/frank-mendez/tripinator
Description: A WordPress plugin that allows you to import data from a spreadsheet to an Admin Page and will filter a Trip you desire.
Version: 1.0.0
Author: Heaviside Group
Author URI: https://www.heavisidegroup.com/
License: GPLv2 or later.*/

// If this file is called directly, abort. Security
if ( ! defined( 'WPINC' ) ) {
     die;
}

global $wpdb;
/* Globals */
$tripinatorDB = $wpdb->prefix . "tripinator";
require_once( 'tripinator-setup.php' );
require_once( 'admin/admin-tripinator.php');

/* Activate and Deactivate Plugin */
register_activation_hook( __FILE__, 'tripinator_install' );
register_deactivation_hook( __FILE__, 'tripinator_uninstall' );

add_action( 'plugins_loaded', 'tripinator_admin' );

function tripinator_admin() {
	$admin_page = new Admin_Tripinator();
	$admin_page->tripinator_init();
}