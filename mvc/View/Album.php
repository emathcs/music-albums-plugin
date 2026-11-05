<?php

require_once 'View.php';

class AlbumView extends View {
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
            'type'     => 'text',
            'id'       => 'cover',
            'name'     => 'cover',
            'class'    => 'form-control',
            'required' => ''
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
     * @param Array $record Album information.
     */
    public function view($record = null) {
        if ($record) {
            $songsTable = false;
            if (isset($record['songs']) && count($record['songs'])) {
                $songsTable = true;
                $songView = new SongView();
            }
            $album = $record['album'];
            echo $this->panelOpen( 'View album' );
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <td>Cover</td>';
            echo '            <td>' . $album->cover . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <td>Name</td>';
            echo '            <td>' . $album->name . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=albums-settings" class="btn btn-sm btn-secondary">Go to Albums</a>';
            echo '    <a href="?page=albums-edit-settings&album=' . $album->id .'" class="btn btn-sm btn-primary">Edit album</a>';
            echo '</div>';
            echo '<br><hr><br>';
            if ($songsTable) {
                echo '<h2>Songs</h2>';
                echo $songView->table($record['songs']);
            }
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
            'type'     => 'hidden',
            'id'       => 'id',
            'name'     => 'id',
            'class'    => 'form-control',
            'value'    => $record->id
        ]);
        // Cover
        $input->setLabel('Cover');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'cover',
            'name'     => 'cover',
            'class'    => 'form-control',
            'required' => '',
            'value'    => $record->cover
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
