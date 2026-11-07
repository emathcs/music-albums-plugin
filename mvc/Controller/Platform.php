<?php

require_once 'Controller.php';

class PlatformController extends Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $Model = new PlatformModel('platforms');
        $View  = new PlatformView();
        // Parent constructor
        parent::__construct($Model, $View);
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
            $this->getModel()->deleteById($id);
        }
        $records = $this->getModel()->select();
        // View
        $this->getView()->list($records);
    }

    /**
     * Add
     */
    public function add() {
        // View
        $this->getView()->add();
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
                $id = $this->getModel()->insert($data);
            }
        }
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id       = sanitize_text_field($_POST['id']);
            $name     = sanitize_text_field($_POST['name']);
            $link     = sanitize_text_field($_POST['link']);
            if (!(empty($name) || empty($link))) {
                $data = [
                    'name' => $name,
                    'link' => $link
                ];
                $format_data = [
                    '%s',
                    '%s'
                ];
                $this->getModel()->updateById(
                    $id,
                    $data,
                    $format_data
                );
            }
        }

        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->view($record);
    }

    /**
     * Edit function
     *
     * @param Int $id Platform id.
     */
    public function edit($id = 0) {
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->edit($record);
    }
}
