<?php

class AlbumController {
    /**
     * Constructor
     */
    public function __construct() {
        return 0;
    }

    /**
     * Table
     */
    public function table() {
        $i = 1;
        echo '<a href="?page=albums-add-settings" class="btn btn-sm btn-primary">Add a new album</a>';
        echo '<table class="table table-sm table-striped table-hover table-bordered">';
        echo '    <thead>';
        echo '        <tr>';
        echo '            <th scope="col">No.</th>';
        echo '            <th scope="col">Name</th>';
        echo '            <th scope="col" colspan="3" class="col-actions">Actions</th>';
        echo '        </tr>';
        echo '    </thead>';
        echo '    <tbody>';
        echo '        <tr>';
        echo '            <th scope="row">' . $i . '</th>';
        echo '            <td>Album name</td>';
        echo '            <td class="col-action"><a href="?page=albums-view-settings&album=' . $i . '" class="btn btn-sm btn-secondary">View</a></td>';
        echo '            <td class="col-action"><a href="?page=albums-edit-settings&album=' . $i . '" class="btn btn-sm btn-warning">Edit</a></td>';
        echo '            <td class="col-action"><button class="btn btn-sm btn-danger">Delete</button></td>';
        echo '        </tr>';
        echo '    </tbody>';
        echo '</table>';
    }

    /**
     * Add
     */
    public function add() {
        // Check if our specific form was submitted
        if ( isset( $_POST['submit'] ) ) {
            // 1. Security Check: Verify Nonce
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }

            // 2. Data Sanitization: Secure against XSS and broken input
            $cover = sanitize_text_field( $_POST['cover'] );
            $name  = sanitize_text_field( $_POST['name'] );

            // Validate required fields are not empty after sanitization
            if ( ! ( empty( $name ) || empty( $cover ) ) ) {
                echo $cover;
                echo '<br>';
                echo $name;
            }
        }
    }
}
