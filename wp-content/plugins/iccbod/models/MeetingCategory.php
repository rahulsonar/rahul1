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
class MeetingCategory {

    public $wpdbObj;

    public function __construct() {

        global $wpdb;

        $this->wpdbObj = $wpdb;

        //Actions
        add_action('wp_ajax_addmeetingcategory', array($this, 'add'));
        add_action('wp_ajax_editmeetingcategory', array($this, 'edit'));
        add_action('wp_ajax_deletemeetingcategory', array($this, 'delete'));
    }

    /**
     * Add Meeting Category
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
        if (false === wp_verify_nonce($_REQUEST['_wpnonce'], 'addmeetingcategory')) {
            $r[0] = 'false';
            $r[1] = "Invalid Request Token";
            echo json_encode($r);
            exit();
            die();
        }

        $v = new Valitron\Validator($_REQUEST);

        $v->rule('required', array('_wpnonce', 'meca_name'));
        $v->labels(array(
            'meca_name' => 'Meeting Category Name'
        ));

        if ($v->validate()) {

            //Check If Meeting Category Exist By Name
            if ($this->getMeetingCategoryByName($_REQUEST['meca_name']) === false) {
                $r[0] = 'false';
                $r[1] = 'Error! Meeting Category Already Exist';
                echo json_encode($r);
                exit();
                die();
            }

            //Data
            $meetingCategoryArray = array(
                'meca_name' => $_POST['meca_name'],
                'meca_deleted' => (bool) 0,
                'meca_created_by' => get_current_user_id(),
                'meca_created' => date("Y-m-d H:i:s"),
                'meca_updated' => date("Y-m-d H:i:s")
            );

            //Insert
            $result = $this->wpdbObj->insert(
                    $this->wpdbObj->prefix . 'meetings_category', $meetingCategoryArray, array('%s', '%d', '%d', '%s', '%s')
            );

            //Check Error
            if (false === $result) {
                //Error in insert
                $r[0] = 'false';
                $r[1] = 'Error in creating Meeting Category';
                echo json_encode($r);
                exit();
                die();
            }

            //Success message
            $r[0] = 'true';
            $r[1] = "Meeting Category Created Successfully";
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
     * Edit Meeting Category
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
        if (false === wp_verify_nonce($_REQUEST['_wpnonce'], 'editmeetingcategory')) {
            $r[0] = 'false';
            $r[1] = "Invalid Request Token";
            echo json_encode($r);
            exit();
            die();
        }


        $v = new Valitron\Validator($_REQUEST);
        $v->rule('required', array('_wpnonce', 'meca_name', 'meca_deleted'));
        $v->rule('in', 'meca_deleted', array(0, 1))->message('Invalid Meeting Category Status');
        $v->labels(array(
            'meca_name' => 'Meeting Category Name',
            'meca_deleted' => 'Meeting Category Status'
        ));

        if ($v->validate()) {


            //Check Valid Meeting ID
            if ($this->getMeetingCategoryById($_REQUEST['meca_id']) === false) {
                $r[0] = 'false';
                $r[1] = 'Invalid Meeting Category ID';
                echo json_encode($r);
                exit();
                die();
            }

            //Meeting ID
            $meeting_category_id = $_REQUEST['meca_id'];

            //Data
            $meetingCategoryArray = array(
                'meca_name' => $_POST['meca_name'],
                'meca_deleted' => $_POST['meca_deleted'],
                'meca_updated' => date("Y-m-d H:i:s")
            );

            //Insert
            $result = $this->wpdbObj->update(
                    $this->wpdbObj->prefix . 'meetings_category', $meetingCategoryArray, array('meca_id' => $meeting_category_id), array('%s', '%d', '%s'), array('%d')
            );

            //Check Error
            if (false === $result) {
                //Error in insert
                $r[0] = 'false';
                $r[1] = 'Error in updating Meeting Category';
                echo json_encode($r);
                exit();
                die();
            }

            //Success message
            $r[0] = 'true';
            $r[1] = "Meeting Category Updated Successfully";
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
     * Delete Meeting Category
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function delete($meca = null) {
        if ($meca == null) {
            return false;
        }

        $v = new Valitron\Validator($_REQUEST);

        $v->rule('required', array('meetingcategory'))->message('Error! Missing Meeting Category ID');

        if ($v->validate()) {

            $meeting_category_ids = $_REQUEST['meetingcategory'];
            $user_id = get_current_user_id();

            if (is_array($meeting_category_ids)) {
                $in_str = implode($meeting_category_ids, ",");
            } else {
                $in_str = $meeting_category_ids;
            }

            //Check if all meeting ids exist
            $query = "SELECT count(*) as total FROM " . $this->wpdbObj->prefix . "meetings_category WHERE `meca_id` IN(" . $in_str . ")";
            $result = $this->wpdbObj->get_results($query);


            //Check count is same 
            if (count($meeting_category_ids) != $result[0]->total) {
                $r[0] = 'false';
                $r[1] = 'Error! Invalid Meeting Category ID';
                return $r;
            }


            //Procced to set delete flag
            $query = "UPDATE " . $this->wpdbObj->prefix . "meetings_category SET meca_deleted = 1 WHERE `meca_id` IN(" . $in_str . ")";
            $result = $this->wpdbObj->get_results($query);

            //Error
            if ($result === false) {
                $r[0] = 'false';
                $r[1] = 'Error in deleting meeting categories';
                return $r;
            }

            //Success
            $r[0] = 'true';
            $r[1] = 'Success! Meetings categories deleted successfully';
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
     * Get Meeting Category By ID
     */
    public function getMeetingCategoryById($meeting_category_id = null) {
        $query = "SELECT * FROM " . $this->wpdbObj->prefix . "meetings_category WHERE meca_id=" . $meeting_category_id;
        $result = $this->wpdbObj->get_row($query);
        if ($result === null) {
            return false;
        }
        return $result;
    }

    /**
     * Check if Meeting Category Exist by  Name
     */
    public function getMeetingCategoryByName($meeting_category = null) {
        if (empty($meeting_category)) {
            return false;
        }

        $query = "SELECT count(*) as total FROM " . $this->wpdbObj->prefix . "meetings_category WHERE meca_name='" . $meeting_category . "'";
        $result = $this->wpdbObj->get_row($query);
        if ($result->total != 0) {
            return false;
        }
        return $result;
    }

    /**
     * Get Meeting Category by Status
     * @param: deleted BOOL
     * 
     * @return ARRAY
     */
    public function getMeetingCategories() {
        $query = "SELECT meca_id, meca_name FROM " . $this->wpdbObj->prefix . "meetings_category WHERE meca_deleted=0";
        $result = $this->wpdbObj->get_results($query);
        if ($result === null) {
            return false;
        }
        return $result;
    }

}

new MeetingCategory();
