<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'bootstrap.php';

class TestModel extends PHPUnit_Framework_TestCase {
    
    /**
     * @var object
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();
        $this->object = new modelClass();
    }
    
    
   public function testData() {
       $data=  $this->object->getData();
       $this->assertEquals(1,count($data));
   }
}