<?php
/**
 * test case for meeting model
 */
class iccbod_Test_MeetingCategory extends PHPUnit_Framework_TestCase {
 

    /**
     * @var object
     */
    public $meetingObj;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();
        $this->meetingObj = new MeetingCategory();
        global $wpdb;
        $this->wpdbObj = $wpdb;
    }
        
    /*test case to fetch meeting categories which is not soft delete*/
    function test_count_of_get_meeting_categories(){
        $result = $this->meetingObj->getMeetingCategories();
        $this->assertCount(3,$result);
    }

    /*test case to check value of return array for get meetig categories is same or not*/
    function test_value_of_get_meeting_categories(){
        $result = $this->meetingObj->getMeetingCategories();
        $test_array = array(0 => (object) array( 'meca_id' => '1','meca_name' => 'QA Meeting category'), 1 => (object) array( 'meca_id' => '2','meca_name' => 'Category California'), 2 => (object) array( 'meca_id' => '3','meca_name' => 'Q3'));
        print_r($result);die;
        $this->assertEquals($test_array,$result);
    }

    /*test case to check type of get metting category result*/
    // function test_instatnce_of_get_category_by_id(){
    //     $result = $this->meetingObj->getMeetingCategoryById(1);
    //     $this->assertInstanceOf('stdClass', $result);
    // }

    // /*test case to check value of return array for get category by id is same or not*/
    // function test_value_of_get_category_by_id(){
    //     $result = $this->meetingObj->getMeetingCategoryById(1);
    //     $test_array = new stdClass;
    //     $test_array->meca_id = 1;
    //     $test_array->meca_name = 'QA Meeting category';
    //     $test_array->meca_deleted = 0;
    //     $test_array->meca_created_by = 1;
    //     $test_array->meca_created = '2016-12-31 02:41:08';
    //     $test_array->meca_updated = '2016-12-31 02:41:08';
    //     $this->assertEquals($result,$test_array);
    // }
    // /*test case to check count for fetching meeting category*/
    // function test_category_is_exists_or_not(){
    //     $result  = $this->meetingObj->getMeetingCategoryByName('QA Meeting category');
    //     $this->assertFalse($result);
    // }

    // /*test case to add new meeting category*/
    // function test_add_new_meeting_category(){
    //     $meetingCategoryArray = array(
    //         'meca_name' => "anand",
    //         'meca_deleted' => 0,
    //         'meca_created_by' => 1,
    //         'meca_created' => date("Y-m-d H:i:s"),
    //         'meca_updated' => date("Y-m-d H:i:s")
    //     );
    //     $result  = $this->meetingObj->add($meetingCategoryArray);
    //     $this->assertSame(1,$result);
    // }

    /*test case to edit existing meeting category*/
    // function test_edit_existing_meeting_category(){
    //     $result  = $this->meetingObj->getMeetingCategoryByName('Edit meeting cats');
    //     $this->assertSame(1,count($result));
    // }

    // /*test case to check meeting category exist before delete process*/
    // function test_existing_meeting_category(){
    //     $result  = $this->meetingObj->getMeetingCategoryByName('Edit meeting cats');
    //     $this->assertSame(1,count($result));
    // }

    // /*test case to delete existing meeting category*/
    // function test_delete_existing_meeting_category(){
    //     $result  = $this->meetingObj->getMeetingCategoryByName('Edit meeting cats');
    //     $this->assertSame(1,count($result));
    // }
}