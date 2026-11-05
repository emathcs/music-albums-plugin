<?php

// List
function platforms_html() {
    $controller = new PlatformController();
    $controller->list();
}

// Add
function platforms_add_html() {
    $controller = new PlatformController();
    $controller->add();
}

// View
function platforms_view_html() {
    $platform_id = isset( $_GET['platform'] ) ? $_GET['platform'] : 0 ;
    $controller = new PlatformController();
    $controller->view( $platform_id );
}

// Edit
function platforms_edit_html() {
    $platform_id = isset( $_GET['platform'] ) ? $_GET['platform'] : 0 ;
    if ( ! $platform_id ) {
        return;
    }
    $controller = new PlatformController();
    $controller->edit( $platform_id );
}
