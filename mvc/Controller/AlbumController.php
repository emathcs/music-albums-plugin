<?php

class AlbumController {
    /**
     * Database
     */
    private $db;

    /**
     * View
     */
    private $View;

    /**
     * Constructor
     */
    public function __construct($wpdb = null) {
        if ($wpdb) {
            $this->db = $wpdb;
        }
        $this->View = new Album();
        return 0;
    }

    /**
     * Table
     */
    public function list($title = '') {
        $table_name = $this->db->prefix . 'albums';
        $records    = $this->db->get_results("SELECT * FROM {$table_name}");
        $this->View->list();
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
        $this->View->add();
    }

    /**
     * View function
     */
    public function view() {
        // View
        $this->View->view();
    }

    /**
     * Edit function
     */
    public function edit() {
        // View
        $this->View->edit();
    }
}
