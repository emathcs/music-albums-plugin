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

    /**
     * Platform list function
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
     * Platform options function
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
