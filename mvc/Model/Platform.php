<?php

class PlatformModel extends SmallModel {
    /**
     * Constructor
     */
    public function __construct() {
        $records = [
            [
                'id'   => 1,
                'name' => 'Apple Music'
            ],
            [
                'id'   => 2,
                'name' => 'Spotify'
            ],
            [
                'id'   => 3,
                'name' => 'Amazon Music'
            ],
        ];
        parent::__construct($records);
    }
}
