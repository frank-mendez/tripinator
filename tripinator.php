<?php

/*Plugin Name: Tripinator
Plugin URI: https://github.com/frank-mendez/tripinator
Description: A super simplified Phonebook
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

require_once("tripinator-setup.php");

/* Activate and Deactivate Plugin */
register_activation_hook( __FILE__, 'tripinator_install' );
register_deactivation_hook( __FILE__, 'tripinator_uninstall' );