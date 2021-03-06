<?php

/**
 * Creates an Admin Page for the Tripinator Plugin
 */


class Admin_Tripinator {

	public function tripinator_init() {
		add_action( 'admin_menu', array( $this, 'tripinator_add_options_page' ) );
        add_action ('wp_loaded', array( $this, 'tripinator_submit_form' ) );
        add_shortcode( 'tripinator', array( $this, 'tripinator_shortcode' ) );
        add_action( 'wp', array( $this, 'search_page_init' ) );
        add_action( 'wp', array( $this, 'more_info_page_init' ) );
	}

    public function search_page_init() {
        if(is_page('search-result')){
            $dir = plugin_dir_path( __FILE__ );
            include($dir."searchResult.php");
            die();
        }
    }

    public function more_info_page_init() {
        if(is_page('more-info')){
            $dir = plugin_dir_path( __FILE__ );
            include($dir."moreInfo.php");
            die();
        }
    }


	public function tripinator_shortcode() {
        ob_start();
	    ?>
        <style>
            select {
                border-radius: 3px;
                height: 35px;
                width: 100%;
                border-color: #fefefe!important;
                background-color: transparent!important;
                color: white;
                -webkit-appearance: none;
            }
            select:focus {
                border-radius: 3px;
                height: 35px;
                width: 100%;
                border-color: #fefefe!important;
                background-color: transparent!important;
                color: white;
                -webkit-appearance: none;
            }
            option{
                color: black;
            }

        </style>
        <form action="search-result" method="post" class="tripinator">
            <div>
                <label>Number of days</label>
                <select name="days" class="days">
                    <option value="">- Select -</option>
                    <option value=".5">Half Day</option>
                    <option value="1">1 Day</option>
                    <option value="2">2 Days</option>
                    <option value="3">3 Days</option>
                    <option value="4">4+ Days</option>
                </select>
            </div>
            <div>
                <label>Have you paddle camped before?</label>
                <select name="canoe" class="days">
                    <option value="">- Select -</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div>
                <label>How do you handle adversity, like a breezy day?</label>
                <select name="adversity" class="days">
                    <option value="">- Select -</option>
                    <option value="It">It is what it is</option>
                    <option value="flower">I'm considered a delicate flower</option>
                </select>

            </div>


            <button type="submit" name="submit_tripinator_form" value="submit">GET STARTED</button>
        </form>
        <?php return ob_get_clean();
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
            'form-tripinator',
            array( $this, 'tripinator_form' )
        );

	}

	public function tripinator_submit_form() {
        if(isset($_POST['submit'])){

            $id = $_POST['id'];
            $trip = $_POST['trip'];
            $days = $_POST['days'];
            $type = $_POST['type'];
            $canoe_experience = $_POST['canoe_experience'];
            $kayak_experience = $_POST['kayak_experience'];
            $adversity = $_POST['adversity'];
            $description = $_POST['description'];
            $img_url = $_POST['img_url'];
            $external_url = $_POST['external_url'];

            $items = array(
                'trip' => $trip,
                'days' => $days,
                'type' => $type,
                'canoe_experience' => $canoe_experience,
                'kayak_experience' => $kayak_experience,
                'adversity' => $adversity,
                'description' => $description,
                'img_url' => $img_url,
                'external_url' => $external_url
            );

            global $wpdb;
            $tripinatorDB = $wpdb->prefix . "tripinator";
            $result = $wpdb->update(
                $tripinatorDB,
                $items,
                array( 'id' => $id )
            );

            if( ! $result ) {
                echo 'the row could not be updated';
            } else {
                $redirect = admin_url('admin.php?page=tripinator-admin-page');
                wp_redirect($redirect);
                exit();
            }
        }
    }

    public function tripinator_get_by_id($id) {

        global $wpdb;
        $tripinatorDB = $wpdb->prefix . "tripinator";

        $item = $wpdb->get_row("SELECT * FROM $tripinatorDB WHERE id = $id", ARRAY_A);

        return $item;
    }

    public function tripinator_form() {

        global $wpdb;
        $tripinatorDB = $wpdb->prefix . "tripinator";

	    if( isset($_REQUEST['action']) ) {

            $id = $_REQUEST['id'];

            $form_title = 'Update Trip';
            $button_value = 'Update Trip';
            $data = $this->tripinator_get_by_id($id);

        } else {
            $form_title = 'Add New Trip';
            $button_value = 'Add New Trip';
            $default_values = array(
                'trip' => '',
                'days' => '',
                'type' => '',
                'canoe_experience' => '',
                'kayak_experience' => '',
                'adversity' => '',
                'description' => '',
                'img_url' => '',
                'external_url' => ''
            );
            /*initialize values*/

            $wpdb->insert($tripinatorDB, $default_values);

            $validate = $wpdb->get_row("SELECT * FROM $tripinatorDB ORDER BY id DESC LIMIT 1", ARRAY_A );
            if($validate['trip'] == "") {
                $id = $validate['id'];
                $data = $this->tripinator_get_by_id($id);
            } else {
                $id = $wpdb->insert_id;
                $data = $this->tripinator_get_by_id($id);
            }
        };

        ?>
        <div class="wrap">
            <h1 id="add-new-user"><?php echo $form_title ?></h1>

            <p>Fill up the forms</p>
            <form method="post" name="createtrip" id="createtrip" class="validate" novalidate="novalidate">
                <input name="id" type="hidden" value="<?php echo $data['id']; ?>" aria-required="true" >
                <table class="form-table">
                    <tbody>
                        <tr class="form-field form-required">
                            <th scope="row"><label>Trip</label></th>
                            <td><input name="trip" type="text" id="trip" value="<?php echo $data['trip']; ?>" aria-required="true" ></td>
                        </tr>
                        <tr class="form-field form-required">
                            <th scope="row"><label>Days and Hours <span class="description">(required)</span></label></th>
                            <td><input name="days" type="text" id="days" value="<?php echo $data['days']; ?>"></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Type </label></th>
                            <td><input name="type" type="text" id="type" value="<?php echo $data['type']; ?>"></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Description </label></th>
                            <td><textarea name="description" id="" cols="30" rows="10"><?php echo preg_replace('/\\\\/', '',$data['description']); ?></textarea></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Image URL </label></th>
                            <td>
                                <input name="img_url" type="text" id="type" value="<?php echo $data['img_url']; ?>">
                                <span><i>*Upload an image to your media library and copy the image url here*</i></span>
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>External URL </label></th>
                            <td>
                                <input name="external_url" type="text" id="type" value="<?php echo $data['external_url']; ?>">
                                <span><i>*Add the external URL for more info*</i></span>
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Canoe Experience </label></th>
                            <td><input name="canoe_experience" type="text" id="canoe_experience" value="<?php echo $data['canoe_experience']; ?>"></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Kayak Experience </label></th>
                            <td><input name="kayak_experience" type="text" id="kayak_experience" value="<?php echo $data['kayak_experience']; ?>"></td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row"><label>Adversity </label></th>
                            <td><input name="adversity" type="text" id="adversity" value="<?php echo $data['adversity']; ?>"></td>
                        </tr>
                    </tbody>
                </table>


                <p class="submit">
                    <input type="submit" name="submit" id="createtripsub" class="button button-primary" value="<?php echo $button_value; ?>">
                </p>
            </form>
        </div>
        <?php
    }



	public function tripinator_admin_page_render() {

        require_once ('tripinatorTable.php');
        $table = new tripinatorTable();
        if ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' ) {
            global $wpdb;
            $tripinatorDB = $wpdb->prefix . "tripinator";
            $id = $_REQUEST['id'];
            $wpdb->delete("$tripinatorDB", ['id' => $id], ['%d']);
        }

        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Tripinator Admin Page</h1>
            <a href="admin.php?page=form-tripinator" class="page-title-action">Add New</a>
            <span class="subtitle">
                <?php if(isset($_REQUEST['s'])){
                    echo 'Search result for "'.$_REQUEST['s'].'"';
                } else {
                    echo '';
                } ?>
            </span>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                                <?php
                                    $table->prepare_items();
                                    $table->search_box('Search', 'search_id');
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