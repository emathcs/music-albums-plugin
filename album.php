<?php

// List
function albums_render_settings_page() {
    $controller = new AlbumController();
    $controller->list();
}

// Add
function albums_add_html() {
    $controller = new AlbumController();
    $controller->add();
}

// View
function albums_view_html() {
    $album_id = isset( $_GET['album'] ) ? $_GET['album'] : 0 ;
    $controller = new AlbumController();
    $controller->view( $album_id );
}

// Edit
function albums_edit_html() {
    $album_id = isset( $_GET['album'] ) ? $_GET['album'] : 0 ;
    $controller = new AlbumController();
    $controller->edit( $album_id );
}
