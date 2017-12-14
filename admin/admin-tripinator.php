<?php

/**
 * Creates an Admin Page for the Tripinator Plugin
 */

class Admin_Tripinator {

	public function tripinator_init() {
		add_action( 'admin_menu', array( $this, 'tripinator_add_options_page' ) );
	}

	public function tripinator_add_options_page() {
		add_options_page(
            'Tripinator Admin',
            'Tripinator',
            'manage_options',
            'tripinator-admin-page',
            array( $this, 'tripinator_admin_page_render' )
        );
	}

	public function tripinator_admin_page_render() {
		echo 'Welcome to Tripinator Admin Page! Good luck, have fun.';
	}

}