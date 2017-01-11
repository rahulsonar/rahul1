<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once( plugin_dir_path(__FILE__) . '../helpers/IccBodHelper.php' );

/**
 * Description of Meeting
 *
 * @author kapil
 */
class Meeting {

    public $wpdbObj;
    private $googlePlacesApiSecret;

    public function __construct() {

        global $wpdb;

        $this->wpdbObj = $wpdb;
        $this->googlePlacesApiSecret = 'AIzaSyCuIDAXSax5dl6muRvpO_teoO9_Y_nUbuo';

        //Actions
        add_action('wp_ajax_addmeeting', array($this, 'add'));
        add_action('wp_ajax_editmeeting', array($this, 'edit'));
        add_action('wp_ajax_deletemeeting', array($this, 'delete'));
        add_action('wp_ajax_exportmeetingics', array($this, 'export'));

        add_action('admin_enqueue_scripts', array($this, 'loadadminassets'));
    }

    //Load admin js
    public function loadadminassets() {

        wp_enqueue_script('gritterjs', plugins_url() . '/iccbod/js/jquery.gritter.min.js', array('jquery'));
        wp_enqueue_script('bvalidatorjs', plugins_url() . '/iccbod/js/jquery.bvalidator.js', array('jquery'));
        wp_enqueue_style('grittercss', plugins_url() . '/iccbod/css/jquery.gritter.css');
        wp_enqueue_style('bvalidatorcss', plugins_url() . '/iccbod/css/bvalidator.css');

        //qtip only
        wp_enqueue_script('qtipjs', plugins_url() . '/iccbod/js/jquery.qtip.min.js', array('jquery'));
        wp_enqueue_style('qtipcss', plugins_url() . '/iccbod/css/jquery.qtip.min.css');

        //fullcal only
        wp_enqueue_script('momentjs', plugins_url() . '/iccbod/js/moment.min.js', array('jquery'));
        wp_enqueue_script('fullcaljs', plugins_url() . '/iccbod/js/fullcalendar.min.js', array('jquery'));
        wp_enqueue_style('fullcalprintcss', plugins_url() . '/iccbod/css/fullcalendar.print.min.css', array(), false, 'print');
        wp_enqueue_style('fullcalcss', plugins_url() . '/iccbod/css/fullcalendar.min.css');

        //Select2
        wp_enqueue_script('chosenjs', plugins_url() . '/iccbod/js/chosen.jquery.min.js', array('jquery'));
        wp_enqueue_style('chosencss', plugins_url() . '/iccbod/css/chosen.min.css');

        //jQuery UI
        wp_enqueue_script('jqueryuijs', plugins_url() . '/iccbod/js/jquery-ui.js', array('jquery'));
        wp_enqueue_style('jqueryuicss', plugins_url() . '/iccbod/css/jquery-ui.css');

        //Timepicker
        wp_enqueue_script('jqueryuitimepcikerjs', plugins_url() . '/iccbod/js/jquery-ui-timepicker-addon.js', array('jquery'));
        wp_enqueue_style('jqueryuitimepickercss', plugins_url() . '/iccbod/css/jquery-ui-timepicker-addon.css');

        //custom css and js
        wp_enqueue_style('mycustom', plugins_url() . '/iccbod/css/custom.css');

        //Google map places api
        wp_enqueue_script('googleplaces', 'http://maps.googleapis.com/maps/api/js?key=' . $this->googlePlacesApiSecret . '&libraries=places', array('jquery'));

        //Calender ICS Export script
        wp_enqueue_script('icsexportdep', plugins_url() . '/iccbod/js/ics.deps.min.js', array('jquery'));

        //Custom JS place at last
        wp_register_script('customadminjs', plugins_url() . '/iccbod/js/custom.js');
        $constants_array = array(
            'bod_plugin_path' => plugins_url() . '/iccbod/',
            '_wpnonce' => wp_create_nonce('exportmeetingics')
        );
        wp_localize_script('customadminjs', 'wp_constants', $constants_array);
        wp_enqueue_script('customadminjs', array('jquery'));
//        wp_enqueue_script('customadminjs', plugins_url() . '/iccbod/js/custom.js', array('jquery'));
    }

    /**
     * Add Meeting
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function add() {

        //Check nonce exist
        if (false === array_key_exists('_wpnonce', $_REQUEST)) {
            $r[0] = 'false';
            $r[1] = "Invalid Request";
            echo json_encode($r);
            exit();
            die();
        }

        //Check and verify nonce
        if (false === wp_verify_nonce($_REQUEST['_wpnonce'], 'addmeeting')) {
            $r[0] = 'false';
            $r[1] = "Invalid Request Token";
            echo json_encode($r);
            exit();
            die();
        }

        $v = new Valitron\Validator($_REQUEST);

        $v->rule('required', array('_wpnonce', 'meet_title', 'meet_description', 'meet_start_date', 'meet_start_time', 'meet_end_date', 'meet_end_time', 'meet_location', 'meet_lat', 'meet_long', 'meet_venue', 'meet_category'));
        $v->rule('lengthBetween', 'meet_description', 0, 800)->message('Bio should not exceed 800 characters.');
        $v->rule('dateFormat', array('meet_start_date', 'meet_end_date'), 'Y-m-d');
        $v->labels(array(
            'meet_title' => 'Meeting Title',
            'meet_description' => 'Meeting Description',
            'meet_start_date' => 'Meeting Start Date',
            'meet_end_date' => 'Meeting End Date',
            'meet_location' => 'Meeting Location',
            'meet_category' => 'Meeting Category',
            'meet_lat' => 'Location Lattitude',
            'meet_long' => 'Location Longitude',
            'meet_venue' => 'Meeting Venue'
        ));

        if ($v->validate()) {

            //Check if categories submitted as array
            if (false === is_array($_REQUEST['meet_category'])) {
                $r[0] = 'false';
                $r[1] = 'Error! Invalid Meeting Categories';
                echo json_encode($r);
                exit();
                die();
            }

            //Validate submitted meeting categories are valid
            $meetingCategoryObj = new MeetingCategory();
            $validCategoriesArray = [];
            $validCategories = $meetingCategoryObj->getMeetingCategories();
            foreach ($validCategories as $validCategory) {
                $validCategoriesArray[] = $validCategory->meca_id;
            }
            foreach ($_REQUEST['meet_category'] as $category) {
                if (false === in_array($category, $validCategoriesArray)) {
                    $r[0] = 'false';
                    $r[1] = 'Error! Invalid Meeting Categories';
                    echo json_encode($r);
                    exit();
                    die();
                }
            }


            //Check file exist
            if (isset($_FILES['meet_image']) && $_FILES['meet_image']['size'] != 0) {

                // Proceed with file upload and checks
                $file = $_FILES['meet_image'];

                $filearrays = array();
                $upload_dir = wp_upload_dir();
                $upload_dir = $upload_dir['path'] . "/";
                $max_size = 5242880; // max file size: 10 MB
                $allow_override = FALSE; // allow uploading files overriding existing ones
                $valid_exts = array(// allowed extensions
                    'jpg',
                    'jpeg',
                    'png'
                );
                $valid_types = array(
                    'image/jpeg',
                    'image/jpeg',
                    'image/png'
                );
                $count = 0;

                $fileuploaded = 0;

                if (isset($file['name']) && !empty($file['name'])) {

                    // get extension
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                    // make sure extension and type are not empty
                    if (!(strlen($extension) && strlen($file['type']))) {
                        $r[0] = 'false';
                        $r[1] = 'File extension Not Found ' . $file['name'];
                        echo json_encode($r);
                        exit();
                        die();
                    } else {

                        // make sure extension and type are allowed
                        if (!(in_array($file['type'], $valid_types) && in_array($extension, $valid_exts))) {
                            $msg = "Extension '$extension' or file type '$file[type]' is not permitted ";

                            $r[0] = 'false';
                            $r[1] = $msg . $file['name'];
                            echo json_encode($r);
                            exit();
                            die();
                        } else {

                            // make sure file is not empty
                            if (!$file['size']) {
                                $r[0] = 'false';
                                $r[1] = 'Empty File Uploaded.' . $file['name'];
                                echo json_encode($r);
                                exit();
                                die();
                            } else {

                                // make sure file is not too large
                                if ($file['size'] > $max_size) {
                                    $msg = 'Uploaded File Is too large' . '(' . ceil($file['size'] / 1024) . 'kB > ' . floor($max_size / 1024) . 'kB) ';
                                    $r[0] = 'false';
                                    $r[1] = $msg . $file['name'];
                                    echo json_encode($r);
                                    exit();
                                    die();
                                } else {

                                    // no other errors
                                    if ($file['error'] > 0) {
                                        $msg = 'Unknown File Upload Error' . "(Code: $file[error]) ";

                                        $r[0] = 'false';
                                        $r[1] = $msg . $file['name'];
                                        echo json_encode($r);
                                        exit();
                                        die();
                                    }


                                    //create random name for file for upload
                                    $randomy = IccBodHelper::randString(10);
                                    $newname = $randomy . "." . $extension;
                                    $target = $upload_dir . $newname;
                                    $filearrays['file'] = $file;
                                    $filearrays['randname'] = $newname;
                                    $filearrays['target'] = $target;

                                    // make sure files don't override
                                    if (!$allow_override && file_exists($target)) {
                                        $randomy = IccBodHelper::randString(10);
                                        $newname = $randomy . "." . $extension;
                                        $target = $upload_dir . $newname;
                                        $filearrays['target'] = $target;
                                    } else {

                                        //Set upload file flag is set to true
                                        $fileuploaded = 1;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //invalid file name
                    $r[0] = 'false';
                    $r[1] = 'Empty File Uploaded.' . $file['name'];
                    echo json_encode($r);
                    exit();
                    die();
                }

                if ($fileuploaded) {

                    if (!move_uploaded_file($filearrays['file']['tmp_name'], $filearrays['target'])) { // attempt uploading
                        $r[0] = 'false';
                        $r[1] = 'Uploading File Failed' . $filearrays['file']['name'];
                        echo json_encode($r);
                        exit();
                        die();
                    }
                }
            }

            //File check 
            $filename = empty($newname) ? '' : $newname;

            //Get All Inputs
            $latlong = json_encode(array(
                'lat' => isset($_POST['meet_lat']) ? $_POST['meet_lat'] : '',
                'long' => isset($_POST['meet_long']) ? $_POST['meet_long'] : ''
            ));

            $meetingArray = array(
                'meet_title' => $_POST['meet_title'],
                'meet_description' => $_POST['meet_description'],
                'meet_image' => $filename,
                'meet_start_date' => $_POST['meet_start_date'],
                'meet_start_time' => $_POST['meet_start_time'],
                'meet_end_date' => $_POST['meet_end_date'],
                'meet_end_time' => $_POST['meet_end_time'],
                'meet_location' => $_POST['meet_location'],
                'meet_latlong' => $latlong,
                'meet_venue' => $_POST['meet_venue'],
                'meet_created_by' => get_current_user_id(),
                'meet_deleted' => (bool) 0,
                'meet_created' => date("Y-m-d H:i:s"),
                'meet_updated' => date("Y-m-d H:i:s")
            );

            //Insert
            $result = $this->wpdbObj->insert(
                    $this->wpdbObj->prefix . 'meetings', $meetingArray, array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s')
            );

            //Check Error
            if (false === $result) {
                //Error in insert
                $r[0] = 'false';
                $r[1] = 'Error in creating Meeting';
                echo json_encode($r);
                exit();
                die();
            }


            //Meeting Category Mapping
            $this->updateMeetingCategoriesMapping($this->wpdbObj->insert_id, $_REQUEST['meet_category']);

            //Success message
            $r[0] = 'true';
            $r[1] = "Meeting Created Successfully";
            echo json_encode($r);
            exit();
            die();
        } else {
            $error_array = array();
            foreach ($v->errors() as $error => $reason) {
                $error_array[] = array($reason[0], $error);
            }

            $r[0] = 'false';
            $r[1] = $error_array;
            echo json_encode($r);
            exit();
            die();
        }
        die();
    }

    /**
     * Edit Meeting
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function edit() {

        //Check nonce exist
        if (false === array_key_exists('_wpnonce', $_REQUEST)) {
            $r[0] = 'false';
            $r[1] = "Invalid Request";
            echo json_encode($r);
            exit();
            die();
        }


        //Check and verify nonce
        if (false === wp_verify_nonce($_REQUEST['_wpnonce'], 'editmeeting')) {
            $r[0] = 'false';
            $r[1] = "Invalid Request Token";
            echo json_encode($r);
            exit();
            die();
        }

        $v = new Valitron\Validator($_REQUEST);


        $v->rule('required', array('_wpnonce', 'meet_id', 'meet_title', 'meet_description', 'meet_start_date', 'meet_start_time', 'meet_end_date', 'meet_end_time', 'meet_location', 'meet_lat', 'meet_long', 'meet_venue', 'meet_category', 'meet_deleted'));
        $v->rule('lengthBetween', 'meet_description', 0, 800)->message('Bio should not exceed 800 characters.');
        $v->rule('dateFormat', array('meet_start_date', 'meet_end_date'), 'Y-m-d');
        $v->rule('integer', 'meet_id');
        $v->rule('in', 'meet_deleted', array(0, 1))->message('Invalid Meeting Status');
        $v->labels(array(
            'meet_id' => 'Meeting ID',
            'meet_title' => 'Meeting Title',
            'meet_description' => 'Meeting Description',
            'meet_start_date' => 'Meeting Start Date',
            'meet_end_date' => 'Meeting End Date',
            'meet_location' => 'Meeting Location',
            'meet_category' => 'Meeting Category',
            'meet_venue' => 'Meeting Venue'
        ));

        if ($v->validate()) {


            //Check Valid Meeting ID
            if ($this->getMeetingById($_REQUEST['meet_id']) === false) {
                $r[0] = 'false';
                $r[1] = 'Invalid Meeting ID';
                echo json_encode($r);
                exit();
                die();
            }


            //Validate submitted meeting categories are valid
            $meetingCategoryObj = new MeetingCategory();
            $validCategoriesArray = [];
            $validCategories = $meetingCategoryObj->getMeetingCategories();
            foreach ($validCategories as $validCategory) {
                $validCategoriesArray[] = $validCategory->meca_id;
            }
            foreach ($_REQUEST['meet_category'] as $category) {
                if (false === in_array($category, $validCategoriesArray)) {
                    $r[0] = 'false';
                    $r[1] = 'Error! Invalid Meeting Categories';
                    echo json_encode($r);
                    exit();
                    die();
                }
            }

            //Meeting ID
            $meeting_id = $_REQUEST['meet_id'];

            //Check file exist
            if (isset($_FILES['meet_image']) && $_FILES['meet_image']['size'] != 0) {

                // Proceed with file upload and checks
                $file = $_FILES['meet_image'];

                $filearrays = array();
                $upload_dir = wp_upload_dir();
                $upload_dir = $upload_dir['path'] . "/";
                $max_size = 5242880; // max file size: 10 MB
                $allow_override = FALSE; // allow uploading files overriding existing ones
                $valid_exts = array(// allowed extensions
                    'jpg',
                    'jpeg',
                    'png'
                );
                $valid_types = array(
                    'image/jpeg',
                    'image/jpeg',
                    'image/png'
                );
                $count = 0;

                $fileuploaded = 0;

                if (isset($file['name']) && !empty($file['name'])) {

                    // get extension
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                    // make sure extension and type are not empty
                    if (!(strlen($extension) && strlen($file['type']))) {
                        $r[0] = 'false';
                        $r[1] = 'File extension Not Found ' . $file['name'];
                        echo json_encode($r);
                        exit();
                        die();
                    } else {

                        // make sure extension and type are allowed
                        if (!(in_array($file['type'], $valid_types) && in_array($extension, $valid_exts))) {
                            $msg = "Extension '$extension' or file type '$file[type]' is not permitted ";

                            $r[0] = 'false';
                            $r[1] = $msg . $file['name'];
                            echo json_encode($r);
                            exit();
                            die();
                        } else {

                            // make sure file is not empty
                            if (!$file['size']) {
                                $r[0] = 'false';
                                $r[1] = 'Empty File Uploaded.' . $file['name'];
                                echo json_encode($r);
                                exit();
                                die();
                            } else {

                                // make sure file is not too large
                                if ($file['size'] > $max_size) {
                                    $msg = 'Uploaded File Is too large' . '(' . ceil($file['size'] / 1024) . 'kB > ' . floor($max_size / 1024) . 'kB) ';
                                    $r[0] = 'false';
                                    $r[1] = $msg . $file['name'];
                                    echo json_encode($r);
                                    exit();
                                    die();
                                } else {

                                    // no other errors
                                    if ($file['error'] > 0) {
                                        $msg = 'Unknown File Upload Error' . "(Code: $file[error]) ";

                                        $r[0] = 'false';
                                        $r[1] = $msg . $file['name'];
                                        echo json_encode($r);
                                        exit();
                                        die();
                                    }


                                    //create random name for file for upload
                                    $randomy = IccBodHelper::randString(10);
                                    $newname = $randomy . "." . $extension;
                                    $target = $upload_dir . $newname;
                                    $filearrays['file'] = $file;
                                    $filearrays['randname'] = $newname;
                                    $filearrays['target'] = $target;

                                    // make sure files don't override
                                    if (!$allow_override && file_exists($target)) {
                                        $randomy = IccBodHelper::randString(10);
                                        $newname = $randomy . "." . $extension;
                                        $target = $upload_dir . $newname;
                                        $filearrays['target'] = $target;
                                    } else {

                                        //Set upload file flag is set to true
                                        $fileuploaded = 1;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //invalid file name
                    $r[0] = 'false';
                    $r[1] = 'Empty File Uploaded.' . $file['name'];
                    echo json_encode($r);
                    exit();
                    die();
                }

                if ($fileuploaded) {

                    if (!move_uploaded_file($filearrays['file']['tmp_name'], $filearrays['target'])) { // attempt uploading
                        $r[0] = 'false';
                        $r[1] = 'Uploading File Failed' . $filearrays['file']['name'];
                        echo json_encode($r);
                        exit();
                        die();
                    }


                    //Get All Inputs
                    $latlong = json_encode(array(
                        'lat' => isset($_POST['meet_lat']) ? $_POST['meet_lat'] : '',
                        'long' => isset($_POST['meet_long']) ? $_POST['meet_long'] : ''
                    ));

                    $meetingArray = array(
                        'meet_title' => $_POST['meet_title'],
                        'meet_description' => $_POST['meet_description'],
                        'meet_image' => $newname,
                        'meet_start_date' => $_POST['meet_start_date'],
                        'meet_start_time' => $_POST['meet_start_time'],
                        'meet_end_date' => $_POST['meet_end_date'],
                        'meet_end_time' => $_POST['meet_end_time'],
                        'meet_location' => $_POST['meet_location'],
                        'meet_latlong' => $latlong,
                        'meet_venue' => $_POST['meet_venue'],
                        'meet_created_by' => get_current_user_id(),
                        'meet_deleted' => (bool) 0,
                        'meet_updated' => date("Y-m-d H:i:s")
                    );

                    //Insert
                    $result = $this->wpdbObj->update(
                            $this->wpdbObj->prefix . 'meetings', $meetingArray, array('meet_id' => $meeting_id), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s'), array('%d')
                    );

                    //Check Error
                    if (false === $result) {
                        //Error in insert
                        $r[0] = 'false';
                        $r[1] = 'Error in updating Meeting';
                        echo json_encode($r);
                        exit();
                        die();
                    }

                    //Meeting Category Mapping
                    $this->updateMeetingCategoriesMapping($meeting_id, $_REQUEST['meet_category']);

                    //Success message
                    $r[0] = 'true';
                    $r[1] = "Meeting Updated Successfully";
                    echo json_encode($r);
                    exit();
                    die();
                }
            }
            // Image upload part end
            //If not image uploaded
            $latlong = json_encode(array(
                'lat' => isset($_POST['meet_lat']) ? $_POST['meet_lat'] : '',
                'long' => isset($_POST['meet_long']) ? $_POST['meet_long'] : ''
            ));

            $meetingArray = array(
                'meet_title' => $_POST['meet_title'],
                'meet_description' => $_POST['meet_description'],
                'meet_start_date' => $_POST['meet_start_date'],
                'meet_start_time' => $_POST['meet_start_time'],
                'meet_end_date' => $_POST['meet_end_date'],
                'meet_end_time' => $_POST['meet_end_time'],
                'meet_location' => $_POST['meet_location'],
                'meet_latlong' => $latlong,
                'meet_created_by' => get_current_user_id(),
                'meet_deleted' => (bool) 0,
                'meet_created' => date("Y-m-d H:i:s"),
                'meet_updated' => date("Y-m-d H:i:s")
            );

            //Insert
            $result = $this->wpdbObj->update(
                    $this->wpdbObj->prefix . 'meetings', $meetingArray, array('meet_id' => $meeting_id), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s'), array('%d')
            );

            //Check Error
            if (false === $result) {
                //Error in insert
                $r[0] = 'false';
                $r[1] = 'Error in creating Meeting';
                echo json_encode($r);
                exit();
                die();
            }

            //Meeting Category Mapping
            $this->updateMeetingCategoriesMapping($meeting_id, $_REQUEST['meet_category']);

            //Success message
            $r[0] = 'true';
            $r[1] = "Meeting Updated Successfully";
            echo json_encode($r);
            exit();
            die();
        } else {
            $error_array = array();
            foreach ($v->errors() as $error => $reason) {
                $error_array[] = array($reason[0], $error);
            }

            $r[0] = 'false';
            $r[1] = $error_array;
            echo json_encode($r);
            exit();
            die();
        }
        die();
    }

    /**
     * Delete Meeting
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function delete($meeting = null) {


        if ($meeting == null) {
            return false;
        }

        $v = new Valitron\Validator($_REQUEST);

        $v->rule('required', array('meeting'));
        $v->labels(array(
            'meeting' => 'Meeting ID Missing'
        ));

        if ($v->validate()) {

            $meeting_ids = $_REQUEST['meeting'];
            $user_id = get_current_user_id();

            if (is_array($meeting_ids)) {
                $in_str = implode($meeting_ids, ",");
            } else {
                $in_str = $meeting_ids;
            }

            //Check if all meeting ids exist
            $query = "SELECT count(*) as total FROM " . $this->wpdbObj->prefix . "meetings WHERE `meet_id` IN(" . $in_str . ")";
            $result = $this->wpdbObj->get_results($query);


            //Check count is same 
            if (count($meeting_ids) != $result[0]->total) {
                $r[0] = 'false';
                $r[1] = 'Error! Invalid Meeting ID';
                return $r;
            }


            //Procced to set delete flag
            $query = "UPDATE " . $this->wpdbObj->prefix . "meetings SET meet_deleted = 1 WHERE `meet_id` IN(" . $in_str . ")";
            $result = $this->wpdbObj->get_results($query);

            //Error
            if ($result === false) {
                $r[0] = 'false';
                $r[1] = 'Error in deleting meetings';
                return $r;
            }

            //Success
            $r[0] = 'true';
            $r[1] = 'Success! Meetings deleted successfully';
            return $r;
        } else {
            $error_array = array();
            foreach ($v->errors() as $error => $reason) {
                $error_array[] = array($reason[0], $error);
            }

            //Show last error
            $error = array_pop($error_array);
            $r[0] = 'false';
            $r[1] = $error[0];
            return $r;
        }
    }

    /**
     * Export Meeting By ID For Calendar
     */
    public function export() {
        //Check nonce exist
        if (false === array_key_exists('_wpnonce', $_REQUEST)) {
            $r[0] = 'false';
            $r[1] = "Invalid Request";
            echo json_encode($r);
            exit();
            die();
        }


        //Check and verify nonce
        if (false === wp_verify_nonce($_REQUEST['_wpnonce'], 'exportmeetingics')) {
            $r[0] = 'false';
            $r[1] = "Invalid Request Token";
            echo json_encode($r);
            exit();
            die();
        }

        $v = new Valitron\Validator($_REQUEST);

        $v->rule('required', array('_wpnonce', 'meet_id'));
        $v->rule('integer', array('meet_id'))->message('Invalid Meeting ID');
        $v->rule('min', 'meet_id', 1)->message('Invalid Meeting ID');
        $v->labels(array(
            'meet_id' => 'Meeting ID',
            '_wpnonce' => 'Request Token'
        ));

        if ($v->validate()) {

            //Check Valid Meeting ID
            if ($this->getMeetingById($_REQUEST['meet_id']) === false) {
                $r[0] = 'false';
                $r[1] = 'Invalid Meeting ID';
                echo json_encode($r);
                exit();
                die();
            }

            $meetingObject = $this->getMeetingById($_REQUEST['meet_id']);

            //prepare formatted meeting object for export ics
            $meetingFormattedObject = new stdClass();
            $meetingFormattedObject->subject = $meetingObject->meet_title;
            $meetingFormattedObject->description = $meetingObject->meet_description;
            $meetingFormattedObject->location = $meetingObject->meet_location;
            $meetingFormattedObject->begin = Date('m/d/Y', strtotime($meetingObject->meet_start_date));

            //Fixing patch, google calendar import shows end date one day less than supplied, Fixing by adding one day in actual end_daate
            $meetingFormattedObject->end = Date('m/d/Y', strtotime('+1 day', strtotime($meetingObject->meet_end_date)));

            $r[0] = 'true';
            $r[1] = $meetingFormattedObject;
            echo json_encode($r);
            exit();
            die();
        } else {
            $error_array = array();
            foreach ($v->errors() as $error => $reason) {
                $error_array[] = array($reason[0], $error);
            }

            //Show last error
            $error = array_pop($error_array);
            $r[0] = 'false';
            $r[1] = $error[0];
            echo json_encode($r);
            exit();
            die();
        }
        die();
    }

    /**
     * Get Meeting By ID
     */
    public function getMeetingById($meeting_id = null) {
        if (empty($meeting_id)) {
            return false;
        }
        $query = "SELECT * FROM " . $this->wpdbObj->prefix . "meetings WHERE meet_id=" . $meeting_id;
        $result = $this->wpdbObj->get_row($query);
        if ($result === null) {
            return false;
        }
        return $result;
    }

    /**
     * Add/Update Meeting Categories
     */
    public function updateMeetingCategoriesMapping($meeting_id, $meeting_categories = null) {

        $query = "UPDATE " . $this->wpdbObj->prefix . "meetings_category_mapping SET `mecm_deleted`=1 WHERE `mecm_meet_id`=" . $meeting_id;
        if (false === $this->wpdbObj->query($query)) {
            $r[0] = 'false';
            $r[1] = 'Error in Meeting Category Mapping';
            echo json_encode($r);
            exit();
            die();
        }

        if (!empty($meeting_categories)) {
            //Check if all submitted categories are valid
            foreach ($meeting_categories as $category) {

                $result = $this->wpdbObj->insert(
                        $this->wpdbObj->prefix . "meetings_category_mapping", array(
                    'mecm_meet_id' => $meeting_id,
                    'mecm_meca_id' => $category,
                    'mecm_deleted' => (bool) 0,
                    'mecm_created' => date("Y-m-d H:i:s")
                        ), array(
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                        )
                );

                if (false === $result) {
                    $r[0] = 'false';
                    $r[1] = 'Error in Meeting Category Mapping';
                    echo json_encode($r);
                    exit();
                    die();
                }
            }
        }
    }

    /**
     * Get Meeting Categories By Meeting ID
     */
    public function getMeetingCategoriesByMeetingId($meeting_id = null) {
        if (empty($meeting_id)) {
            return false;
        }

        $query = "SELECT map.mecm_meca_id FROM " . $this->wpdbObj->prefix . "meetings_category_mapping as map INNER JOIN " . $this->wpdbObj->prefix . "meetings_category as cat ON ( map.mecm_meca_id = cat.meca_id ) WHERE cat.meca_deleted=0 AND map.mecm_meet_id=" . $meeting_id . " AND map.mecm_deleted=0";
        $result = $this->wpdbObj->get_results($query);
        if ($result === null) {
            return false;
        }
        return $result;
    }

}

//add_action('admin_init', new Meeting());
new Meeting();
