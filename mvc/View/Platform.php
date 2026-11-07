<?php

require_once 'View.php';

class PlatformView extends View {
    /**
     * List render function
     *
     * @param Array $data Data to render
     */
    public function list($data = []) {
        $records = isset($data['records']) ? $data['records'] : [];
        $total = count($records);
        // Panel
        echo $this->panelOpen('Platforms');
        echo '<div class="mb-3">';
        echo '    <a href="?page=platforms-add-settings" class="btn btn-sm btn-primary">Add a new platform</a>';
        echo '</div>';
        if ($total) {
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <thead>';
            echo '        <tr>';
            echo '            <th scope="col">Id</th>';
            echo '            <th scope="col">Name</th>';
            echo '            <th scope="col">Link</th>';
            echo '            <th scope="col" colspan="3" class="col-actions">Actions</th>';
            echo '        </tr>';
            echo '    </thead>';
            echo '    <tbody>';
            for ($i = 0; $i < $total; $i++) {
                echo '    <tr>';
                echo '        <th scope="row">' . $records[$i]->id . '</th>';
                echo '        <td>' . $records[$i]->name . '</td>';
                echo '        <td>' . $records[$i]->link . '</td>';
                echo '        <td class="col-action"><a href="?page=platforms-view-settings&platform=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
                echo '        <td class="col-action"><a href="?page=platforms-edit-settings&platform=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                echo '        <td class="col-action">' . $this->deleteForm($records[$i]->id) . '</td>';
                echo '    </tr>';
            }
            echo '    </tbody>';
            echo '</table>';
        }
        echo $this->panelClose();
    }

    /**
     * Add render function
     */
    public function add() {
        echo $this->panelOpen('Add a new platform');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');
        // Panel
        echo $form->init(['action' => "admin.php?page=platforms-view-settings", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Album id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'album-id',
            'name'     => 'album-id',
            'class'    => 'form-control',
            'value'    => 1
        ]);
        // Name
        $input->setLabel('Name');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'name',
            'name'     => 'name',
            'class'    => 'form-control',
            'required' => ''
        ]);
        // Link
        $input->setLabel('Link');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'link',
            'name'     => 'link',
            'class'    => 'form-control',
            'required' => ''
        ]);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit', 'value' =>'Save', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }

    /**
     * View render function
     *
     * @param Array $data Data to render
     */
    public function view($data = []) {
        $record = isset($data['record']) ? $data['record'] : [];
        // Panel
        echo $this->panelOpen( 'View platform' );
        if ($record) {
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <td>Name</td>';
            echo '            <td>' . $record->name . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <td>Link</td>';
            echo '            <td>' . $record->link . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=platforms-settings" class="btn btn-sm btn-secondary">Go to Platforms</a>';
            echo '    <a href="?page=platforms-edit-settings&platform=' . $record->id .'" class="btn btn-sm btn-primary">Edit platform</a>';
            echo '</div>';
        }
        echo $this->panelClose();
    }

    /**
     * Edit render function
     *
     * @param Array $data Data to render
     */
    public function edit($data = []) {
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');
        $record = isset($data['record']) ? $data['record'] : [];
        // Panel
        echo $this->panelOpen('Edit platform');
        echo $form->init(['action' => "admin.php?page=platforms-view-settings&platform=" . $record->id, 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'id',
            'name'     => 'id',
            'class'    => 'form-control',
            'value'    => $record->id
        ]);
        // Album id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'album-id',
            'name'     => 'album-id',
            'class'    => 'form-control',
            'value'    => $record->album_id
        ]);
        // Name
        $input->setLabel('Name');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'name',
            'name'     => 'name',
            'class'    => 'form-control',
            'required' => '',
            'value'    => $record->name
        ]);
        // Link
        $input->setLabel('Link');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'link',
            'name'     => 'link',
            'class'    => 'form-control',
            'required' => '',
            'value'    => $record->link
        ]);
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit-edit', 'value' =>'Update', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }
}
