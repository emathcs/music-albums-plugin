<?php

class SmallModel {
    /**
     * Records
     */
    private $records = [];

    /**
     * Constructor
     */
    public function __construct($records = []) {
        $this->records = $records;
    }

    /**
     * Select records
     *
     * SELECT * FROM Model;
     *
     * @return Array Records
     */
    public function select() {
        return $this->records;
    }

    /**
     * Model list
     */
    public function getListFormat() {
        $list = [];
        $platforms = $this->select();
        $total = count($platforms);
        for ($i = 0; $i < $total; $i++) {
            $list[$platforms[$i]['id']] = $platforms[$i]['name'];
        }
        return $list;
    }

    /**
     * Model options
     */
    public function getOptionsFormat() {
        $options = [];
        $platforms = $this->select();
        $total = count($platforms);
        for ($i = 0; $i < $total; $i++) {
            $options[$i] = [
                'value' => $platforms[$i]['id'],
                'label' => $platforms[$i]['name']
            ];
        }
        return $options;
    }
}
