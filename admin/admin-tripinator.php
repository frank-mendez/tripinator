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

        require_once ('tripinatorTable.php');
        $table = new tripinatorTable();
        /*$table->prepare_items();
        $table->display();*/
        ?>
        <div class="wrap">
            <h2>Tripinator Admin Page</h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <?php
                                $table->prepare_items();
                                $table->display(); ?>
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