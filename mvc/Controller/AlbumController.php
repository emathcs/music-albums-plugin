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
     * Table
     */
    private $table;

    /**
     * Set database
     *
     * @param Object $db Database interface.
     */
    public function setDb($db = null) {
        $this->db = $db;
    }

    /**
     * Get database
     *
     * @return Object Database interface.
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Set table
     *
     * @param string $table Table name.
     */
    public function setTable($table = '') {
        $this->table = $table;
    }

    /**
     * Get table
     *
     * @return string Table name
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Is a valid database
     *
     * @return Object Database interface.
     */
    public function isValidDb() {
        return $this->db != null;
    }

    /**
     * Constructor
     */
    public function __construct($wpdb = null) {
        $this->setDb($wpdb);
        if ($this->isValidDb()) {
            $this->setTable($this->getDb()->prefix . 'albums');
        }
        $this->View = new Album();
        return 0;
    }

    /**
     * Table
     */
    public function list() {
        $records = $this->getDb()->get_results("SELECT * FROM {$this->getTable()}");
        // View
        $this->View->list($records);
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
