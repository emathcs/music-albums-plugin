<?php

class PlatformModel {
    /**
     * Records
     */
    private $records = [
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

    /**
     * Select function
     *
     * SELECT * FROM Platforms;
     *
     * @return Array Platforms
     */
    public function select() {
        return $this->records;
    }
}
