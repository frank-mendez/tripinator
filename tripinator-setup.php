<?php

$table_name = false;
$table_meta_name = false;
$charset_collate = false;

function tripinator_install() {
	/*We need to create a database table name wp_tripinator*/


	/*For table name prefix*/
	global $wpdb;
	/*DB NAME*/
	$tripinatorDB = $wpdb->prefix . "tripinator";

	/* SQL Query*/
	$sql = "CREATE TABLE {$tripinatorDB} (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	trip varchar(64) NOT NULL,
	days varchar(64),
	type varchar(16),
	canoe_experience varchar(64),
	kayak_experience varchar(64),
	adversity varchar(64),
	UNIQUE KEY id (id)
	) $charset_collate;";

	/* Rather than executing an SQL query directly, we'll use the dbDelta function in wp-admin/includes/upgrade.php (we'll have to load this file, as it is not loaded by default). The dbDelta function examines the current table structure, compares it to the desired table structure, and either adds or modifies the table as necessary, so it can be very handy for updates (see wp-admin/upgrade-schema.php for more examples of how to use dbDelta).
	Source: https://codex.wordpress.org/Creating_Tables_with_Plugins */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

function tripinator_uninstall() {
	global $wpdb, $tripinatorDB;
	$wpdb->query("DROP TABLE {$tripinatorDB}");
}