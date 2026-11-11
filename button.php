<?php

// Add
function buttons_add_html() {
    $controller = new ButtonController();
    $controller->add();
}

// View
function buttons_view_html() {
    $button_id = isset( $_GET['button'] ) ? $_GET['button'] : 0 ;
    $controller = new ButtonController();
    $controller->view( $button_id );
}

// Edit
function buttons_edit_html() {
    $button_id = isset( $_GET['button'] ) ? $_GET['button'] : 0 ;
    if ( ! $button_id ) {
        return;
    }
    $controller = new ButtonController();
    $controller->edit( $button_id );
}
