<?php

require_once 'Model.php';

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
}
