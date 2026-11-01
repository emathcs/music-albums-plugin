<?php

class Album {
    /**
     * Open panel
     */
    public function panelOpen($title = '') {
        $render  = '<div class="albums-wrap">';
        $render .= '    <div class="container albums-panel">';
        $render .= '        <div class="row">';
        $render .= '            <div class="col-xs">';
        $render .= '                <h1>' . esc_html( $title ) . '</h1>';
        $render .= '            </div>';
        $render .= '            <div class="col-xs">';
        return $render;
    }

    /**
     * Close panel
     */
    public function panelClose() {
        $render  = '            </div>';
        $render .= '        </div>';
        $render .= '    </div>';
        $render .= '</div>';
        return $render;
    }

    /**
     * List
     */
    public function list() {
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
}
