<?php

require_once 'View.php';

class SongView extends View {
    /**
     * List
     */
    public function list($records = []) {
        $total = count($records);
        echo $this->panelOpen('Songs');
        echo '<div class="mb-3">';
        echo '    <a href="?page=songs-add-settings" class="btn btn-sm btn-primary">Add a new song</a>';
        echo '</div>';
        if (count($records)) {
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
                echo '        <td class="col-action"><a href="?page=songs-view-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
                echo '        <td class="col-action"><a href="?page=songs-edit-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                echo '        <td class="col-action">' . $this->deleteForm($records[$i]->id) . '</td>';
                echo '    </tr>';
            }
            echo '    </tbody>';
            echo '</table>';
        }
        echo $this->panelClose();
    }

    /**
     * Add
     */
    public function add() {
        echo $this->panelOpen('Add a new song');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $form->init(['action' => "admin.php?page=songs-view-settings", 'method' => 'post']);
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
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit', 'value' =>'Save', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }

    /**
     * View function
     *
     * @param Array $record Song information.
     */
    public function view($record = null) {
        if ($record) {
            echo $this->panelOpen( 'View song' );
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <td>Name</td>';
            echo '            <td>' . $record->name . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=songs-settings" class="btn btn-sm btn-secondary">Go to Songs</a>';
            echo '    <a href="?page=songs-edit-settings&song=' . $record->id .'" class="btn btn-sm btn-primary">Edit song</a>';
            echo '</div>';
            echo $this->panelClose();
        }
    }

    /**
     * Edit function
     *
     * @param Array $record Song information.
     */
    public function edit($record = null) {
        if (!$record) {
            return;
        }
        echo $this->panelOpen('Edit song');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $form->init(['action' => "admin.php?page=songs-view-settings&song=" . $record->id, 'method' => 'post']);
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
        // Submit
        echo $input->setLabel('');
        echo $input->create(['type' => 'submit', 'name' => 'submit-edit', 'value' =>'Update', 'class' => 'btn btn-sm btn-primary']);
        echo $form->end();
        echo $this->panelClose();
    }
}
