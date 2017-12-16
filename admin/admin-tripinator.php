<?php

/**
 * Creates an Admin Page for the Tripinator Plugin
 */


class Admin_Tripinator {

	public function tripinator_init() {
		add_action( 'admin_menu', array( $this, 'tripinator_add_options_page' ) );
        add_action ('wp_loaded', array( $this, 'tripinator_submit_form' ) );
	}

	public function tripinator_add_options_page() {

		add_menu_page(
            'Tripinator Admin',
            'Tripinator',
            'edit_plugins',
            'tripinator-admin-page',
            array( $this, 'tripinator_admin_page_render' ),
            'dashicons-location-alt'
        );


        add_submenu_page(
            'tripinator-admin-page',
            'Create',
            'Create',
            'edit_plugins',
            'create-tripinator',
            array( $this, 'tripinator_create_new' )
        );

	}

	public function tripinator_submit_form() {
        if(isset($_POST['submit'])){
            $trip = $_POST['trip'];
            $days = $_POST['days'];
            $type = $_POST['type'];
            $canoe_experience = $_POST['canoe_experience'];
            $kayak_experience = $_POST['kayak_experience'];
            $adversity = $_POST['adversity'];

            global $wpdb;
            $tripinatorDB = $wpdb->prefix . "tripinator";
            $inserted = $wpdb->insert(
                $tripinatorDB,
                array(
                    'trip' => $trip,
                    'days' => $days,
                    'type' => $type,
                    'canoe_experience' => $canoe_experience,
                    'kayak_experience' => $kayak_experience,
                    'adversity' => $adversity
                )
            );

            if( ! $inserted ) {
                echo 'the row could not be inserted';
            } else {
                /*redirect*/
                $redirect = admin_url('admin.php?page=tripinator-admin-page');
                wp_redirect($redirect);
                exit();
            }
        }
    }


    public function tripinator_create_new() {

        ?>
        <div class="wrap">
            <h1 id="add-new-user">Add New Trip</h1>

            <p>Create a brand new trip and add them to this site.</p>
            <form method="post" name="createtrip" id="createtrip" class="validate" novalidate="novalidate">
                <table class="form-table">
                    <tbody>
                        <tr class="form-field form-required">
                            <th scope="row"><label>Trip</label></th>
                            <td><input name="trip" type="text" id="trip" value="" aria-required="true" ></td>
                        </tr>
                        <tr class="form-field form-required">
                            <th scope="row"><label>Days and Hours <span class="description">(required)</span></label></th>
                            <td><input name="days" type="text" id="days" value=""></td>
                        </tr>
                        <tr class="form-field">
                                <th scope="row"><label>Type </label></th>
                            <td><input name="type" type="text" id="type" value=""></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Canoe Experience </label></th>
                            <td><input name="canoe_experience" type="text" id="canoe_experience" value=""></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Kayak Experience </label></th>
                            <td><input name="kayak_experience" type="text" id="kayak_experience" value=""></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Adversity </label></th>
                            <td><input name="adversity" type="text" id="adversity" value=""></td>
                        </tr>
                    </tbody>
                </table>


                <p class="submit">
                    <input type="submit" name="submit" id="createtripsub" class="button button-primary" value="Add New Trip">
                </p>
            </form>
        </div>
        <?php
    }


	public function tripinator_admin_page_render() {

        require_once ('tripinatorTable.php');
        $table = new tripinatorTable();
        /*$table->prepare_items();
        $table->display();*/
        ?>
        <div class="wrap">
            <h2>Tripinator Admin Page</h2>
            <a href="admin.php?page=create-tripinator" class="page-title-action">Add New</a>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <?php
                                $table->prepare_items();
                                $table->search_box('Search', 'search');
                                $table->display();
                                 ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <?php
	}

	

}