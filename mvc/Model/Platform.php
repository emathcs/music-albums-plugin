<?php

class PlatformModel {
    /**
     * List
     */
    private $list = [
        [
            'value' => 1,
            'label' => 'Apple Music'
        ],
        [
            'value' => 2,
            'label' => 'Spotify'
        ],
        [
            'value' => 3,
            'label' => 'Amazon Music'
        ],
    ];

    /**
     * Get list
     *
     * @return Array Platform list
     */
    public function getList() {
        return $this->list;
    }

}
