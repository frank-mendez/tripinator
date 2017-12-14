<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 14/12/2017
 * Time: 11:13 PM
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class tripinatorTable extends WP_List_Table{
    public function __construct() {

        parent::__construct( [
            'singular' => __( 'Trip', 'sp' ),
            'plural' => __( 'Trips', 'sp' ),
            'ajax' => false
        ] );

    }

    public function prepare_items() {

        $columns = [
            'cb' => '<input type="checkbox" />',
            'trip' => __('Trip', 'ux') ,
            'days' => __('Days', 'ux') ,
            'type' => __('Type', 'ux') ,
            'canoe_experience' => __('Canoe Experience', 'ux') ,
            'kayak_experience' => __('Kayak Experience', 'ux') ,
            'adversity' => __('Adversity', 'ux')
        ];
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );

        /* Bulk Process*/
        //$this->process_bulk_action();
        $per_page = $this->get_items_per_page( 'records_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $data = self::get_records($per_page, $current_page);
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page
        ]);
        $this->items = $data;
    }

    public static function get_records( $per_page = 10, $page_number = 1 ){
        global $wpdb;
        $tripinatorDB = $wpdb->prefix . "tripinator";
        $sql = "SELECT * FROM {$tripinatorDB}";
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }
        $sql.= " LIMIT $per_page";
        $sql.= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        /*$result = array([
           'id' => 1,
           'trip' => 'Sauk City to Wyalusing',
            'days' => '4',
            'type' => 'paddle',
            'canoe_experience' => 'yes',
            'kayak_experience' => 'yes',
            'adversity' => 'It is what…'
        ]);*/
        return $result;
    }

    public function get_columns(){
        $columns = [
            'cb' => '<input type="checkbox" />',
            'trip' => __('Trip', 'ux') ,
            'days' => __('Days', 'ux') ,
            'type' => __('Type', 'ux') ,
            'canoe_experience' => __('Canoe Experience', 'ux') ,
            'kayak_experience' => __('Kayak Experience', 'ux') ,
            'adversity' => __('Adversity', 'ux')
        ];
        return $columns;
    }

    public function get_hidden_columns(){
        // Setup Hidden columns and return them
        return array();
    }

    /**
     * Columns to make sortable.
     * * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id' => array('id',true) ,
            'trip' => array('trip',false) ,
            'days' => array('days',false) ,
        );
        return $sortable_columns;
    }

    /**
     * Render the bulk edit checkbox
     * * @param array $item
     * * @return string
     */
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
    }

    /**
     * Render the bulk edit checkbox
     * * @param array $item
     * * @return string
     */
    /*function column_first_column_name($item)
    {
        return sprintf('<a href="%s" class="btn btn-primary"/>Trip</a>', $item['trip']);
    }*/
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id':
            case 'trip':
            case 'days':
            case 'type':
            case 'canoe_experience':
            case 'kayak_experience':
            case 'adversity':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }


    /**
     * Returns an associative array containing the bulk action
     * * @return array */
    public function get_bulk_actions(){
        $actions = ['bulk-delete' => 'Delete'];
        return $actions;
    }
    public function process_bulk_action(){
        // Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'bx_delete_records')) {
                die('Go get a life script kiddies');
            }
            else {
                self::delete_records(absint($_GET['record']));
                $redirect = admin_url('admin.php?page=codingbin_records');
                wp_redirect($redirect);
                exit;
            }
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            // loop over the array of record IDs and delete them
            foreach($delete_ids as $id) {
                self::delete_records($id);
            }

            $redirect = admin_url('admin.php?page=codingbin_records');
            wp_redirect($redirect);
            exit;
            exit;
        }
    }

    /**
     * Delete a record record.
     * * @param int $id customer ID
     */
    public static function delete_records($id)
    {
        global $wpdb;
        $tripinatorDB = $wpdb->prefix . "tripinator";
        $wpdb->delete("$tripinatorDB", ['id' => $id], ['%d']);
    }

    /**
     * Returns the count of records in the database.
     * * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;
        $tripinatorDB = $wpdb->prefix . "tripinator";
        $sql = "SELECT COUNT(*) FROM {$tripinatorDB}";
        return $wpdb->get_var($sql);
    }
}