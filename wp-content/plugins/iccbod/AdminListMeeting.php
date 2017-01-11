<?php
/*
  Plugin Name: ICCBOD
  Description: ICC Board of Directors Admin section
  Version: 1.0
  Author: Kapil Yadav
 */

require_once( plugin_dir_path(__FILE__) . 'classes/AdminListTable.php' );
require_once( plugin_dir_path(__FILE__) . 'models/Meeting.php' );
require_once( plugin_dir_path(__FILE__) . 'models/LibraryDocuments.php' );
require_once( plugin_dir_path(__FILE__) . 'models/MeetingCategory.php' );
require_once( plugin_dir_path(__FILE__) . 'valitrono/src/valitron/validator.php' );
require_once( plugin_dir_path(__FILE__) . 'AdminListMeetingCategory.php' );
require_once( plugin_dir_path(__FILE__) . 'AdminListLibraryDocuments.php' );

class AdminListMeeting extends AdminListTable {

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
            'singular' => 'meeting', //singular name of the listed records
            'plural' => 'meetings', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    /**
     * Default columns settings
     * 
     */
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'meet_title':
            case 'meet_image': return ($item[$column_name]) ? '<img src="' . content_url() . "/uploads/" . $item[$column_name] . '" width="50px;">' : 'No Meeting Image';
            case 'meet_deleted': return ($item[$column_name]) ? 'Deleted' : 'Published';
            case 'meet_start_date': return (Date('Y-m-d', strtotime($item[$column_name])));
            case 'meet_end_date': return (Date('Y-m-d', strtotime($item[$column_name])));
            case 'meet_craeted': return '<button class="export_meeting_button" data-meet-id=' . $item['meet_id'] . '>Export ICS</button>';
            case 'meet_location':
            case 'meet_venue':

                return $item[$column_name];
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

        if ($item['meet_deleted']) {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&meeting=%s">Edit</a>', 'meeting-edit', 'edit', $item['meet_id'])
            );
        } else {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&meeting=%s">Edit</a>', 'meeting-edit', 'edit', $item['meet_id']),
                'delete' => sprintf('<a href="?page=%s&action=%s&meeting=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['meet_id']),
            );
        }


        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%3$s',
                /* $1%s */ $item['meet_title'],
                /* $2%s */ $item['meet_id'],
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
                /* $2%s */ $item['meet_id']                //The value of the checkbox should be the record's id
        );
    }

    /**
     * define columns of the list table
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => 'Title',
            'meet_image' => 'Image',
            'meet_start_date' => 'Start Date',
            'meet_end_date' => 'End Date',
            'meet_location' => 'Location',
            'meet_venue' => 'Venue',
            'meet_deleted' => 'Status',
            'meet_craeted' => 'Export ICS'
        );
        return $columns;
    }

    /**
     * Define sortable columns 
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'title' => array('meet_title', false), //true means it's already sorted
            'meet_start_date' => array('meet_start_date', false),
            'meet_end_date' => array('meet_end_date', false),
            'meet_location' => array('meet_location', false)
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
            $meetingObj = new Meeting();
            $response = $meetingObj->delete($_REQUEST);

            if ($response[0] == 'false') {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e($response[1]); ?></p>
                </div>
                <?php
            } else {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e($response[1]); ?></p>
                </div>
                <?php
            }
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
        $query = "SELECT * FROM " . $wpdb->prefix . "meetings";

        //Search
        if (isset($_GET['s'])) {
            $query .= " WHERE meet_title LIKE '%" . $_GET['s'] . "%'";
        }

        //Status Filter
        if (isset($_GET['status-filter']) > 0 && $_GET['status-filter'] != '') {
            $query .= " AND meet_deleted =" . $_GET['status-filter'];
        }
        $this->example_data = $wpdb->get_results($query, ARRAY_A);
        $data = $this->example_data;

        function usort_reorder($a, $b) {
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'meet_title'; //If no sort, default to title
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
//        $move_on_url = '&status-filter=';
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
                    <option value =''>All</option>
                </select>
            </div>
            <!--            <div class="alignleft actions bulkactions"><label class='admin_custom_filter_label'>Category</label></div>
                        <div class="alignleft actions bulkactions">-->
            <?php
//                $meetingCategoryObj = new MeetingCategory();
//                $meetingCategories = $meetingCategoryObj->getMeetingCategories();
            ?>
            <!--</div>-->
            <?php
            submit_button(__('Filter'), 'button', 'filter_action', false, array('id' => 'post-query-submit'));
            ?>
            <?php
        }
    }

    /**
     * Search Box
     */
    public function search_box($text, $input_id) {
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button($text, 'button', false, false, array('id' => 'search-submit')); ?>
        </p>
        <?php
    }

}

/**
 * Register admin menu pages
 */
function meeting_menu_items() {
    add_menu_page('Meetings', 'Meetings', 'activate_plugins', 'meeting_tt_list', 'meeting_render_list_page');
    add_menu_page('Document Library', 'Library', 'activate_plugins', 'library_document_list', 'render_library_document_list');
    add_submenu_page('meeting_tt_list', 'Meeting Categories', 'Meeting Categories', 'activate_plugins', 'meeting-category', 'list_meeting_category_callback');
    add_submenu_page('meeting_tt_list', 'Calendar', 'Calendar', 'activate_plugins', 'meeting-cal', 'meeting_calendar');
    add_submenu_page('meeting_tt_list', 'Settings', 'Settings', 'activate_plugins', 'meeting-settings', 'meeting_settings');


    //Meeting CRUD Hidden pages
    add_submenu_page('', 'Add New Meeting', 'Add New Meeting', 'activate_plugins', 'meeting-add-new', 'meeting_add_callback');
    add_submenu_page('', 'Edit Meeting', 'Edit Meeting', 'activate_plugins', 'meeting-edit', 'meeting_edit_callback');
}

add_action('admin_menu', 'meeting_menu_items');

/**
 * Meeting Add CallBack
 */
function meeting_add_callback() {
    $meetingCategoryObj = new MeetingCategory();
    //Get published meeting categories only
    // meca_deleted = 0
    $meeting_categories = $meetingCategoryObj->getMeetingCategories();
    include 'views/meeting/add.php';
}

/**
 * Edit meeting
 */
function meeting_edit_callback() {

    global $wpdb;
    $query = "SELECT * FROM " . $wpdb->prefix . "meetings WHERE meet_id=" . $_GET['meeting'];
    $meeting = $wpdb->get_row($query);

    //Parse lat long
    $latlong = json_decode($meeting->meet_latlong);

    if (null === $meeting) {
        wp_die('Invalid Meeting ID');
    }

    $meetingCategoryObj = new MeetingCategory();
    //Get published meeting categories only
    // meca_deleted = 0
    $meeting_categories = $meetingCategoryObj->getMeetingCategories();
    $meeting_categoriesArray = [];
    foreach ($meeting_categories as $meetCatObj) {
        $meeting_categoriesArray[$meetCatObj->meca_id] = $meetCatObj->meca_name;
    }

    //Get Meeting categories
    $meetingObj = new Meeting();
    $meetingSelectedCategoriesArray = [];
    $meetingSelectedCategories = $meetingObj->getMeetingCategoriesByMeetingId($_GET['meeting']);
    foreach ($meetingSelectedCategories as $meetingSelectedObj) {
        $meetingSelectedCategoriesArray[] = $meetingSelectedObj->mecm_meca_id;
    }

    include 'views/meeting/edit.php';
}

/**
 * Meeting calendar
 */
function meeting_calendar() {
    include 'views/meeting/calendar.php';
}

/*
 * Meeting Settings
 */

function meeting_settings() {
    include 'views/meeting/settings.php';
}

/*
 * Meetings Admin Listings
 */

function meeting_render_list_page() {

    //Create an instance of our package class...
    $testListTable = new AdminListMeeting();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    ?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <!--<h2>Meetings <a href="?page=meeting_tt_list&action=new" class="page-title-action">Add New</a></h2>-->
        <h2>Meetings <a href="<?php menu_page_url('meeting-add-new', true); ?>" class="page-title-action">Add New</a></h2>

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $testListTable->search_box('search', 'search_id'); ?>
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>

        <form method="post">
            <input type="hidden" name="page" value="my_list_test" />

        </form>

    </div>
    <?php
}
