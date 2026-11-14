<?php

class TypeModel extends SmallModel {
    /**
     * Constructor
     */
    public function __construct() {
        $records = [
            [
                'id'   => 1,
                'name' => 'Album'
            ],
            [
                'id'   => 2,
                'name' => 'Single'
            ],
        ];
        parent::__construct($records);
    }
}
