<?php

class PlatformController {
    /**
     * Model
     */
    private $Model;

    /**
     * View
     */
    private $View;

    /**
     * Constructor
     */
    public function __construct() {
        $this->Model = new PlatformModel('platforms');
        $this->View  = new PlatformView();
    }

    /**
     * Table
     */
    public function list() {
        // This process has been added here to avoid to redirect to this page from view page
        if ( isset( $_POST['submit-delete'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'delete_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id = sanitize_text_field($_POST['id']);
            $this->Model->deleteById($id);
        }
        $records = $this->Model->select();
        // View
        $this->View->list($records);
    }

    /**
     * Add
     */
    public function add() {
        // View
        $this->View->add();
    }

    /**
     * View function
     *
     * @param Int $id Platform id.
     */
    public function view($id = 0) {
        // This process has been added here to avoid to redirect to this page from add page
        if ( isset( $_POST['submit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $name     = sanitize_text_field($_POST['name']);
            $link     = sanitize_text_field($_POST['link']);
            $album_id = sanitize_text_field($_POST['album-id']);
            if (!(empty($name) || empty($album_id) || empty($album_id))) {
                $data = [
                    'name'     => $name,
                    'link'     => $link,
                    'album_id' => $album_id
                ];
                $id = $this->Model->insert($data);
            }
        }
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id       = sanitize_text_field($_POST['id']);
            $link     = sanitize_text_field($_POST['link']);
            $name     = sanitize_text_field($_POST['name']);
            if (!(empty($name) || empty($album_id))) {
                $data = [
                    'name' => $name
                    'link' => $link
                ];
                $format_data = [
                    '%s',
                    '%s'
                ];
                $this->Model->updateById(
                    $id,
                    $data,
                    $format_data
                );
            }
        }

        $record = $this->Model->selectById($id);
        // View
        $this->View->view($record);
    }

    /**
     * Edit function
     *
     * @param Int $id Platform id.
     */
    public function edit($id = 0) {
        $record = $this->Model->selectById($id);
        // View
        $this->View->edit($record);
    }
}
