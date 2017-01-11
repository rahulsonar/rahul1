<?php

class AdminListLibraryDocuments extends AdminListTable {

    var $example_data = array();
    var $wpdb;

    /**
     * Class Constructor
     */
    public function __construct() {
        global $status, $page, $pagenow, $wpdb;
        $this->wpdb = $wpdb;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'librarydocuments', //singular name of the listed records
            'plural' => 'librarydocument', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    /**
     * Default columns settings
     * 
     */
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'docu_name':
            case 'docu_file':
            case 'docu_created_by':
            case 'docu_created':
            case 'docu_updated':
                return ($item[$column_name]);
            case 'docu_approved': return ($item[$column_name]) ? 'Approved' : 'Unapproved';
            case 'docu_deleted': return ($item[$column_name]) ? 'Deleted' : 'Published';
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Build row actions
     *  
     */
    function column_title($item) {

        //Build row actions

        if ($item['docu_deleted']) {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&librarydocument=%s">Edit</a>', 'meeting-category-edit', 'edit', $item['docu_id'])
            );
        } else {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&librarydocument=%s">Edit</a>', 'meeting-category-edit', 'edit', $item['docu_id']),
                'delete' => sprintf('<a href="?page=%s&action=%s&librarydocument=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['docu_id']),
            );
        }


        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%3$s',
                /* $1%s */ $item['docu_name'],
                /* $2%s */ $item['docu_id'],
                /* $3%s */ $this->row_actions($actions)
        );
    }

    /**
     * Bulk check-box column callback
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /* $1%s */ $this->_args['singular'], //Let's simply repurpose the table's singular label ("movie")
                /* $2%s */ $item['docu_id']         //The value of the checkbox should be the record's id
        );
    }

    /**
     * define columns of the list table
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => 'Title',
            'docu_file' => 'File',
            'docu_deleted' => 'Document Status',
            'docu_approved' => 'Bulletin Status',
            'docu_created_by' => 'Author',
            'docu_created' => 'Created',
            'docu_updated' => 'Updated'
        );
        return $columns;
    }

    /**
     * Define sortable columns 
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'title' => array('docu_name', false), //true means it's already sorted
            'docu_approved' => array('docu_approved', false),
            'docu_deleted' => array('docu_deleted', false),
            'docu_created_by' => array('docu_created_by', false),
            'docu_created' => array('docu_created', false),
            'docu_updated' => array('docu_updated', false)
        );
        return $sortable_columns;
    }

    /**
     * Define bulk actions
     */
    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * Process bulk actions
     */
    function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {

            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";
            die();
        }
    }

    /**     * ***********************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()t
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     * ************************************************************************ */
    function prepare_items() {

        global $wpdb; //This is used only if making any database queries
        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        global $wpdb;
        $query = "SELECT * FROM " . $wpdb->prefix . "documents";
//        if (isset($_GET['status-filter']) > 0 && $_GET['status-filter'] != '') {
//            $query .= " WHERE meca_deleted =" . $_GET['status-filter'];
//        }
        $this->example_data = $wpdb->get_results($query, ARRAY_A);
        $data = $this->example_data;

        function usort_reorder($a, $b) {
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'docu_name'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
        }

        usort($data, 'usort_reorder');
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }

    /**
     * Adding custom filter for filtering results
     */
    public function extra_tablenav($which) {
        global $wpdb;
        $move_on_url = '&status-filter=';
        if ($which == "top") {
            ?>
            <div class="alignleft actions bulkactions"><label class='admin_custom_filter_label'>Status</label></div>
            <div class="alignleft actions bulkactions">
                <?php
                $cats = array(
                    array(
                        'id' => 1,
                        'title' => 'Deleted'
                    ),
                    array(
                        'id' => 0,
                        'title' => 'Published'
                    )
                );
                if ($cats) {
                    ?>
                    <select name="status-filter" class="ewc-filter-cat">
                        <?php
                        foreach ($cats as $cat) {
                            $selected = '';
                            if ($_GET['status-filter'] == $cat['id']) {
                                $selected = ' selected = "selected"';
                            }
                            ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>><?php echo $cat['title']; ?></option>
                            <?php
                        }
                        ?>
                        <option value='' selected="selected">All</option>
                    </select>

                    <?php
                    submit_button(__('Filter'), 'button', 'filter_action', false, array('id' => 'post-query-submit'));
                }
                ?>  
            </div>
            <?php
        }
    }

}

/**
 * Register admin menu pages
 */
function document_category_menu_items() {
    //Meeting Category CRUD Hidden pages
    add_submenu_page('', 'Add New Document', 'Add New Meeting Document', 'activate_plugins', 'library-document-add-new', 'library_document_add_callback');
    add_submenu_page('', 'Edit Document', 'Edit Meeting Document', 'activate_plugins', 'library-document-edit', 'library_document_edit_callback');
}

add_action('admin_menu', 'document_category_menu_items');

/**
 * Meeting Category Add CallBack
 */
function library_document_add_callback() {
    echo "<pre>";
    print_r('Add Library document');
    echo "</pre>";
    die();

//    include 'views/meeting_category/add.php';
}

/**
 * Meeting category Edit callback
 */
function library_document_edit_callback() {
    echo "<pre>";
    print_r('Edit document');
    echo "</pre>";
    die();

//    global $wpdb;
//    $query = "SELECT * FROM " . $wpdb->prefix . "meetings_category WHERE meca_id=" . $_GET['meeting_category'];
//    $meetingcategory = $wpdb->get_row($query);
//
//    if (null === $meetingcategory) {
//        wp_die('Invalid Meeting Category ID');
//    }
//    include 'views/meeting_category/edit.php';
}

/*
 * Meetings Category Admin Listings
 */

function render_library_document_list() {

    //Create an instance of our package class...
    $testListTable = new AdminListLibraryDocuments();

    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    ?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <!--<h2>Meetings <a href="?page=meeting_tt_list&action=new" class="page-title-action">Add New</a></h2>-->
        <h2>Documents Library<a href="<?php menu_page_url('library-document-add-new', true); ?>" class="page-title-action">Add New</a></h2>

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>

    </div>
    <?php
}
