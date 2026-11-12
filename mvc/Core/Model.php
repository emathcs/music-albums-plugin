<?php

class Model {
    /**
     *
     */
    private $db;

    /**
     * Table
     */
    private $table;

    /**
     * Set database
     *
     * @param Object $db Database interface
     */
    public function setDb($db = null) {
        $this->db = $db;
    }

    /**
     * Get database
     *
     * @return Object Database interface
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Set table
     *
     * @param String $table Table name
     */
    public function setTable($table = '') {
        $this->table = $table;
    }

    /**
     * Get table
     *
     * @return String Table name
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Contructor
     *
     * @param String $table Table name
     */
    public function __construct($table = '') {
        global $wpdb;
        $this->setDb($wpdb);
        if (strlen($table)) {
            $this->setTable($this->getDb()->prefix . $table);
        }
    }

    /**
     * Select records
     *
     * @return Array Object record array
     */
    public function select() {
        return $this->getDb()->get_results(
            $this->getDb()->prepare("SELECT * FROM {$this->getTable()}")
        );
    }

    /**
     * Select records by Id
     *
     * @param Integer $id Object id
     * @return Object Object record
     */
    public function selectById($id = 0) {
        $record = $this->getDb()->get_row(
            $this->getDb()->prepare("SELECT * FROM {$this->getTable()} WHERE id=%d", $id)
        );
        return $record;
    }

    /**
     * Insert record
     *
     * @param Array $data   Record to insert
     * @param Array $format Format of the data to insert
     * @return Integer Record id or 0
     */
    public function insert($data = [], $format = []) {
        $inserted = $this->getDb()->insert(
            $this->getTable(),
            $data,
            $format
        );
        return $inserted ? $this->getDb()->insert_id : 0;
    }

    /**
     * Update record by id
     *
     * @param Integer $id          Record id
     * @param Array   $data        Record
     * @param Array   $format_data Format of the data to update
     * @return Integer Id or 0
     */
    public function updateById($id = 0, $data = [], $format_data = []) {
        $where = [
            'id' => $id
        ];
        $format_where = [
            '%d'
        ];
        // Database insertion
        $updated = $this->getDb()->update(
            $this->getTable(),
            $data,
            $where,
            $format_data,
            $format_where
        );
        return $updated ? $id : 0;
    }

    /**
     * Delete record by id
     *
     * @param Integer $id Record id to delete
     * @return Integer The status of the operation
     */
    public function deleteById($id = 0) {
        $where = array(
            'id' => $id
        );
        $where_format = [
            '%d'
        ] ;
        return $this->getDb()->delete( $this->getTable(), $where, $where_format );
    }
}
