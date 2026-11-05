<?php

// List
function songs_html() {
    $controller = new SongController();
    $controller->list();
}

// Add
function songs_add_html() {
    $controller = new SongController();
    $controller->add();
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
