<?php

class ButtonModel extends Model {
    /**
     * Select by Album id function
     *
     * @param Integer $id Album id.
     * @return Array Buttons Object Array.
     */
    public function selectByAlbumId($id = 0) {
        return $this->getDb()->get_results("SELECT * FROM {$this->getTable()} WHERE album_id={$id}");
    }

    /**
     * Select by Song id function
     *
     * @param Integer $id Song id.
     * @return Array Buttons Object Array.
     */
    public function selectBySongId($id = 0) {
        return $this->getDb()->get_results("SELECT * FROM {$this->getTable()} WHERE song_id={$id}");
    }
}
