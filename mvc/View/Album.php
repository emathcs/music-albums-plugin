<?php

require_once 'View.php';

class AlbumView extends View {
    /**
     * List render function
     *
     * @param Array $data Data to render
     */
    public function list($data = []) {
        $records = isset($data['records']) ? $data['records'] : [];
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
     * Add render function
     */
    public function add() {
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $this->panelOpen('Add a new album');
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
     * View render function
     *
     * @param Array $data Data to render
     */
    public function view($data = []) {
        $record = isset($data['record']) ? $data['record'] : [];
        if ($record) {
            $songsTable = false;
            if (isset($data['songs']) && count($data['songs'])) {
                $songsTable = true;
                $songView = new SongView();
            }
            $album = $data['record'];
            echo $this->panelOpen( 'View album' );
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <td><b>Cover</b></td>';
            echo '            <td>' . $album->cover . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <td><b>Name</b></td>';
            echo '            <td>' . $album->name . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=albums-settings" class="btn btn-sm btn-secondary">Go to Albums</a>';
            echo '    <a href="?page=albums-edit-settings&album=' . $album->id .'" class="btn btn-sm btn-primary">Edit album</a>';
            echo '    <a href="?page=songs-add-settings&album=' . $album->id . '" class="btn btn-sm btn-success">Add a new song</a>';
            echo '</div>';
            if ($songsTable) {
                echo '<br><hr><br>';
                echo '<h2>Songs</h2>';
                echo $songView->table($data['songs']);
            }
            echo $this->panelClose();
        }
    }

    /**
     * Edit render function
     *
     * @param Array $data Data to render
     */
    public function edit($data = []) {
        $record = isset($data['record']) ? $data['record'] : [];
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        echo $this->panelOpen('Edit album');
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
