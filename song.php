<?php

// Add
function songs_add_html() {
    // Get the album id
    $album_id = isset( $_GET['album'] ) ? $_GET['album'] : 0 ;
    $controller = new SongController();
    $controller->add($album_id);
}

// View
function songs_view_html() {
    $song_id = isset( $_GET['song'] ) ? $_GET['song'] : 0 ;
    $controller = new SongController();
    $controller->view( $song_id );
}

// Edit
function songs_edit_html() {
    $song_id = isset( $_GET['song'] ) ? $_GET['song'] : 0 ;
    if ( ! $song_id ) {
        return;
    }
    $controller = new SongController();
    $controller->edit( $song_id );
}
