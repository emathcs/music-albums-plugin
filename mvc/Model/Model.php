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
     * Contructor
     */
    public function __construct($table = '') {
        global $wpdb;
        $this->setDb($wpdb);
        if (strlen($table)) {
            $this->setTable($this->getDb()->prefix . $table);
        }
    }

    /**
     * Select
     */
    public function select() {
        return $this->getDb()->get_results("SELECT * FROM {$this->getTable()}");
    }

    /**
     *
     */
    public function selectById($id = 0) {
        $record = $this->getDb()->get_results("SELECT * FROM {$this->getTable()} WHERE id={$id}");
        if ($record) {
            return $record[0];
        }
        return [];
    }

    /**
     *
     */
    public function insert($data = []) {
        $inserted = $this->getDb()->insert(
            $this->getTable(),
            $data
        );
        return $inserted ? $this->getDb()->insert_id : 0;
    }

    /**
     *
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
     * Delete by id function
     *
     * @param Integer $id Element id to delete.
     * @return Integer The status of the operation.
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
