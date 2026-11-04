<?php

require_once plugin_dir_path( __FILE__ ) . '../Model/Album.php';

class AlbumController {
    /**
     * Database
     */
    private $db;

    /**
     * Model
     */
    private $Model;

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
        $this->Model = new AlbumModel('albums');
        $this->View  = new Album();
        return 0;
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
     * Save information
     */
    public function save($data = []) {
        $id = 0;
        // Data Sanitization
        $cover = sanitize_text_field($data['cover']);
        $name  = sanitize_text_field($data['name']);
        // Validate required fields are not empty after sanitization
        if ( ! ( empty( $name ) || empty( $cover ) ) ) {
            // Database insertion
            $inserted = $this->getDb()->insert(
                $this->getTable(),
                array(
                    'cover' => $cover,
                    'name'  => $name,
                )
            );
            $id = $inserted ? $this->getDb()->insert_id : 0;
        }
        return $id;
    }

    /**
     * Update information
     */
    public function update($id = 0, $data = []) {
        // Data Sanitization
        $cover = sanitize_text_field($data['cover']);
        $name  = sanitize_text_field($data['name']);
        // Validate required fields are not empty after sanitization
        if ( ! ( empty( $name ) || empty( $cover ) ) ) {
            $data = [
                'cover' => $cover,
                'name'  => $name
            ];
            $format_data = [
                '%s',
                '%s'
            ];
            // Database insertion
            $id = $this->Model->updateById(
                $id,
                $data,
                $format_data
            );
        }
        return $id;
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
     * @param Int $id Album id.
     */
    public function view($id = 0) {
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id    = sanitize_text_field($_POST['id']);
            $cover = sanitize_text_field($_POST['cover']);
            $name  = sanitize_text_field($_POST['name']);
            if (!(empty($name) || empty($cover))) {
                $data = [
                    'cover' => $cover,
                    'name'  => $name
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
        // This process has been added here to avoid to redirect to this page from save page
        if ( isset( $_POST['submit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $cover = sanitize_text_field($_POST['cover']);
            $name  = sanitize_text_field($_POST['name']);
            if (!(empty($name) || empty($cover))) {
                $data = [
                    'cover' => $cover,
                    'name'  => $name
                ];
                $id = $this->Model->insert($data);
            }
        }
        $record = $this->Model->selectById($id);
        // View
        $this->View->view($record);
    }

    /**
     * Edit function
     *
     * @param Int $id Album id.
     */
    public function edit($id = 0) {
        $record = $this->Model->selectById($id);
        // View
        $this->View->edit($record);
    }
}
