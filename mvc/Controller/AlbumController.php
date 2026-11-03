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
     * Delete information
     */
    public function delete($id = 0) {
        $where = array(
            'id' => $id
        );
        $where_format = array( '%d' );
        return $this->getDb()->delete( $this->getTable(), $where, $where_format );
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
        $record = $this->getDb()->get_results("SELECT * FROM {$this->getTable()} WHERE id={$id}");
        // View
        $this->View->view($record);
    }

    /**
     * Edit function
     *
     * @param Int $id Album id.
     */
    public function edit($id = 0) {
        $record = $this->getDb()->get_results("SELECT * FROM {$this->getTable()} WHERE id={$id}");
        // View
        $this->View->edit($record);
    }
}
