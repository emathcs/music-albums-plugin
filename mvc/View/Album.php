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
    public function list($records = []) {
        $total = count($records);
        echo $this->panelOpen('Albums');
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
        for ($i = 0; $i < $total; $i++) {
            echo '    <tr>';
            echo '        <th scope="row">' . $i . '</th>';
            echo '        <td>' . $records[$i]->name . '</td>';
            echo '        <td class="col-action"><a href="?page=albums-view-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
            echo '        <td class="col-action"><a href="?page=albums-edit-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
            echo '        <td class="col-action"><button class="btn btn-sm btn-danger">Delete</button></td>';
            echo '    </tr>';
        }
        echo '    </tbody>';
        echo '</table>';
        echo $this->panelClose();
    }

    /**
     * Add
     */
    public function add() {
        echo $this->panelOpen('Add a new album');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $form->init(['action' => "", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Cover
        $input->setLabel('Cover');
        echo $input->create(['type' => 'text', 'id' => 'cover', 'name' => 'cover', 'class' => 'form-control']);
        // Name
        $input->setLabel('Name');
        echo $input->create(['type' => 'text', 'id' => 'name', 'name' => 'name', 'class' => 'form-control']);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit', 'value' =>'Submit', 'class' => 'btn btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }

    /**
     * View function
     */
    public function view() {
        echo $this->panelOpen( 'View album' );
        echo $this->panelClose();
    }

    /**
     * Edit function
     */
    public function edit() {
        echo $this->panelOpen('Edit album');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $form->init(['action' => "", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Cover
        $input->setLabel('Cover');
        echo $input->create(['type' => 'text', 'id' => 'cover', 'name' => 'cover', 'class' => 'form-control']);
        // Name
        $input->setLabel('Name');
        echo $input->create(['type' => 'text', 'id' => 'name', 'name' => 'name', 'class' => 'form-control']);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit', 'value' =>'Submit', 'class' => 'btn btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }
}
