<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once( plugin_dir_path(__FILE__) . '../helpers/IccBodHelper.php' );

/**
 * Description of Dcouments
 *
 * @author kapil
 */
class LibraryDocuments {

    public $wpdbObj;

    public function __construct() {

        global $wpdb;

        $this->wpdbObj = $wpdb;

        //Actions
        add_action('wp_ajax_adddocument', array($this, 'add'));
        add_action('wp_ajax_editdocument', array($this, 'edit'));
        add_action('wp_ajax_deletedocument', array($this, 'delete'));
    }

    /**
     * Add  Document
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function add() {
        echo "<pre>";
        print_r('add document model request');
        echo "</pre>";
        die();
    }

    /**
     * Edit Document
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function edit() {
        echo "<pre>";
        print_r('Edit document model request');
        echo "</pre>";
        die();
    }

    /**
     * Delete Document
     * @return  JSON_OBJCET  Return JSON OBJCET.
     *
     * @since   1.0
     */
    public function delete($medo = null) {

        echo "<pre>";
        print_r('Delete document model request');
        echo "</pre>";
        die();
    }

    /**
     * Get Meeting Document By ID
     */
//    public function getMeetingDocumentById($meeting_document_id = null) {
//        $query = "SELECT * FROM " . $this->wpdbObj->prefix . "meetings_documents WHERE medo_id=" . $meeting_document_id;
//        $result = $this->wpdbObj->get_row($query);
//        if ($result === null) {
//            return false;
//        }
//        return $result;
//    }
}

new LibraryDocuments();
