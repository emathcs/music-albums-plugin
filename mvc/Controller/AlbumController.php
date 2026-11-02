<?php

class AlbumController {
    /**
     * View
     */
    private $view;

    /**
     * Constructor
     */
    public function __construct() {
        $this->view = new Album();
        return 0;
    }

    /**
     * Table
     */
    public function list($title = '') {
        $this->view->list($title);
    }

    /**
     * Add
     */
    public function add() {
        // Check if our specific form was submitted
        if ( isset( $_POST['submit'] ) ) {
            // 1. Security Check: Verify Nonce
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }

            // 2. Data Sanitization: Secure against XSS and broken input
            $cover = sanitize_text_field( $_POST['cover'] );
            $name  = sanitize_text_field( $_POST['name'] );

            // Validate required fields are not empty after sanitization
            if ( ! ( empty( $name ) || empty( $cover ) ) ) {
                echo $cover;
                echo '<br>';
                echo $name;
            }
        }

        // View
        $this->view->add();
    }
}
