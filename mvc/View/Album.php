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
     *
     */
    public function deleteForm($id = 0) {
        $form  = new Form();
        $input = new Input();

        $html  = $form->init(['action' => "", 'method' => 'post']);
        $html .= wp_nonce_field( 'delete_custom_form_action', 'custom_form_nonce', true, false );
        $html .= $input->setLabel('');
        $html .= $input->create([
            'type'  => 'hidden',
            'id'    => 'id-' . $id,
            'name'  => 'id',
            'class' => 'form-control',
            'value' => $id
        ]);
        $html .= $input->setLabel('');
        $html .= $input->create(['type' => 'submit', 'name' => 'submit-delete', 'value' =>'Delete', 'class' => 'btn btn-sm btn-danger']);
        $html .= $form->end();
        return $html;
    }

    /**
     * List
     */
    public function list($records = []) {
        $total = count($records);
        echo $this->panelOpen('Albums');
        echo '<div class="mb-3">';
        echo '    <a href="?page=albums-add-settings" class="btn btn-sm btn-primary">Add a new album</a>';
        echo '</div>';
        echo '<table class="table table-sm table-striped table-hover table-bordered">';
        echo '    <thead>';
        echo '        <tr>';
        echo '            <th scope="col">Id</th>';
        echo '            <th scope="col">Name</th>';
        echo '            <th scope="col" colspan="3" class="col-actions">Actions</th>';
        echo '        </tr>';
        echo '    </thead>';
        echo '    <tbody>';
        for ($i = 0; $i < $total; $i++) {
            echo '    <tr>';
            echo '        <th scope="row">' . $records[$i]->id . '</th>';
            echo '        <td>' . $records[$i]->name . '</td>';
            echo '        <td class="col-action"><a href="?page=albums-view-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
            echo '        <td class="col-action"><a href="?page=albums-edit-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
            echo '        <td class="col-action">' . $this->deleteForm($records[$i]->id) . '</td>';
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

        echo $form->init(['action' => "admin.php?page=albums-view-settings", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Cover
        $input->setLabel('Cover');
        echo $input->create([
            'type'  => 'text',
            'id'    => 'cover',
            'name'  => 'cover',
            'class' => 'form-control'
        ]);
        // Name
        $input->setLabel('Name');
        echo $input->create([
            'type'  => 'text',
            'id'    => 'name',
            'name'  => 'name',
            'class' => 'form-control'
        ]);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit', 'value' =>'Save', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }

    /**
     * View function
     *
     * @param Array $record Album information.
     */
    public function view($record = null) {
        if ($record) {
            echo $this->panelOpen( 'View album' );
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <td>Cover</td>';
            echo '            <td>' . $record->cover . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <td>Name</td>';
            echo '            <td>' . $record->name . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=albums-settings" class="btn btn-sm btn-secondary">Go to Albums</a>';
            echo '</div>';
            echo $this->panelClose();
        }
    }

    /**
     * Edit function
     *
     * @param Array $record Album information.
     */
    public function edit($record = null) {
        if (!$record) {
            return;
        }
        echo $this->panelOpen('Edit album');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $form->init(['action' => "admin.php?page=albums-view-settings&album=" . $record->id, 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Id
        echo $input->create([
            'type'  => 'hidden',
            'id'    => 'id',
            'name'  => 'id',
            'class' => 'form-control',
            'value' => $record->id
        ]);
        // Cover
        $input->setLabel('Cover');
        echo $input->create([
            'type'  => 'text',
            'id'    => 'cover',
            'name'  => 'cover',
            'class' => 'form-control',
            'value' => $record->cover
        ]);
        // Name
        $input->setLabel('Name');
        echo $input->create([
            'type'  => 'text',
            'id'    => 'name',
            'name'  => 'name',
            'class' => 'form-control',
            'value' => $record->name
        ]);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit-edit', 'value' =>'Update', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }
}
