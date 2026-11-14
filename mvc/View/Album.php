<?php

class AlbumView extends View {
    /**
     * List page
     *
     * @param Array $data Data to render
     */
    public function list($data = []) {
        $records = isset($data['records']) ? $data['records'] : [];
        $total = count($records);

        // Panel
        echo $this->panelOpen('Albums');
        echo '<div class="mb-3">';
        echo '    <a href="?page=albums-add-settings" class="btn btn-primary">Add a new album</a>';
        echo '</div>';
        if ($total) {
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
                echo '        <td class="col-action"><a href="?page=albums-view-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-success">View</a></td>';
                echo '        <td class="col-action"><a href="?page=albums-edit-settings&album=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                echo '        <td class="col-action">' . $this->deleteForm($records[$i]->id, 'album') . '</td>';
                echo '    </tr>';
            }
            echo '    </tbody>';
            echo '</table>';
        }
        echo $this->panelClose();
    }

    /**
     * Add page
     *
     * @param Array $data Data to render
     */
    public function add($data = []) {
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');
        $select = new Select('mb-3');
        $select->setLabelClass('form-label');
        $navigation = new Navigation('mb-3');

        // Panel
        echo $this->panelOpen('Add a new album');
        echo $form->init(['action' => "admin.php?page=albums-view-settings", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Cover image
        echo '<div id="album-image-gallery-item-preview" class="mb-3 col-12 col-sm-4 album-upload-btn album-image-gallery-item-preview album-admin-hide">';
        echo '    <img src="" id="album-cover-img">';
        echo '</div>';
        echo '<div id="album-image-gallery-item-upload" class="mb-3 col-12 col-sm-4 album-upload-btn album-image-gallery-item-preview">';
        echo '    <img src="' . $data['default_src'] . '" id="album-admin">';
        echo '</div>';
        echo '<div class="mb-3">';
        // Select
        echo '    <button type="button" id="upload-btn" class="album-upload-btn btn btn-secondary">Select an image</button> ';
        // Remove
        echo '    <button type="button" id="remove-btn" class="album-remove-btn btn btn-warning album-admin-hide">Remove image</button>';
        echo '</div>';
        // Image ID
        $input->setLabel('');
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'image-id',
            'name'     => 'image_id',
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
        // Type
        $select->setLabel('Type');
        echo $select->create(
            [
                'type'     => 'text',
                'id'       => 'type-id',
                'name'     => 'type_id',
                'class'    => 'form-control',
                'required' => '',
                'value'    => ''
            ],
            $data['types']
        );
        // Post ID
        $input->setLabel('Post ID');
        echo $input->create([
            'type'     => 'text',
            'id'       => 'post-id',
            'name'     => 'post_id',
            'class'    => 'form-control',
            'required' => ''
        ]);
        // Navigation buttons
        echo $input->setLabel('');
        echo $navigation->buttons(
            ['href' => '?page=albums-settings', 'label' => 'Go to the albums', 'class' => 'btn btn-warning'],
            ['type' => 'submit', 'name' => 'submit', 'value' =>'Save information', 'class' => 'btn btn-primary']
        );
        echo $form->end();
        echo $this->panelClose();
    }

    /**
     * View page
     *
     * @param Array $data Data to render
     */
    public function view($data = []) {
        $record = isset($data['record']) ? $data['record'] : [];

        // Panel
        echo $this->panelOpen( 'View album' );
        if ($record) {
            // Get the Button view
            $buttonsTable = false;
            if (isset($data['buttons']) && count($data['buttons'])) {
                $buttonsTable = true;
                $buttonView = new ButtonView();
            }
            // Get the Song view
            $songsTable = false;
            if (isset($data['songs']) && count($data['songs'])) {
                $songsTable = true;
                $songView = new SongView();
            }

            // Cover image
            echo '<div class="mb-3 col-12 col-sm-4">';
            echo '    <img src="' . $data['src'] . '">';
            echo '</div>';
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <th>Id</th>';
            echo '            <td>' . $record->id . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <th>Name</th>';
            echo '            <td>' . $record->name . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <th>Type</th>';
            echo '            <td>' . $data['types'][$record->type_id] . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <th>Image ID</th>';
            echo '            <td>' . $record->image_id . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <th>Post ID</th>';
            echo '            <td>' . $record->post_id . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=albums-settings" class="btn btn-warning">Go to the Albums</a>';
            echo '    <a href="?page=albums-edit-settings&album=' . $record->id .'" class="btn btn-secondary">Edit album</a>';
            echo '    <a href="?page=albums-add-settings" class="btn btn-primary">Add a new album</a>';
            echo '</div>';
            // Button view
            echo '<br><hr><br>';
            echo '<h2>Buttons</h2>';
            echo '<a href="?page=buttons-add-settings&album=' . $record->id . '" class="btn btn-primary">Add a new button</a>';
            echo '<br>';
            if ($buttonsTable) {
                echo '<br>';
                echo $buttonView->table($data['buttons'], $data['platforms']);
            }
            // Song view
            echo '<br><hr><br>';
            echo '<h2>Songs</h2>';
            echo '<a href="?page=songs-add-settings&album=' . $record->id . '" class="btn btn-primary">Add a new song</a>';
            echo '<br><br>';
            if ($songsTable) {
                echo $songView->table($data['songs']);
            }
        }
        echo $this->panelClose();
    }

    /**
     * Edit page
     *
     * @param Array $data Data to render
     */
    public function edit($data = []) {
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');
        $record = isset($data['record']) ? $data['record'] : [];
        $navigation = new Navigation('mb-3');

        // Panel
        echo $this->panelOpen('Edit album');
        if ($record) {
            echo $form->init(['action' => "admin.php?page=albums-view-settings&album=" . $record->id, 'method' => 'post']);
            // Security field: Nonce to prevent CSRF attacks
            wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
            // Cover image
            echo '<div id="album-image-gallery-item-preview" class="mb-3 col-12 col-sm-4 album-upload-btn album-image-gallery-item-preview">';
            echo '    <img src="' . $data['src'] . '" id="album-cover-img">';
            echo '</div>';
            echo '<div id="album-image-gallery-item-upload" class="mb-3 col-12 col-sm-4 album-upload-btn album-image-gallery-item-preview album-admin-hide">';
            echo '    <img src="' . $data['default_src'] . '" id="album-admin">';
            echo '</div>';
            echo '<div class="mb-3">';
            // Select
            echo '    <button type="button" id="upload-btn" class="album-upload-btn btn btn-secondary album-admin-hide">Select an image</button> ';
            // Remove
            echo '    <button type="button" id="remove-btn" class="album-remove-btn btn btn-warning">Remove image</button>';
            echo '</div>';
            // Id
            echo $input->create([
                'type'     => 'hidden',
                'id'       => 'id',
                'name'     => 'id',
                'class'    => 'form-control',
                'value'    => $record->id
            ]);
            // Cover
            $input->setLabel('');
            echo $input->create([
                'type'     => 'hidden',
                'id'       => 'image-id',
                'name'     => 'image_id',
                'class'    => 'form-control',
                'required' => '',
                'value'    => $record->image_id
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
            // Post ID
            $input->setLabel('Post ID');
            echo $input->create([
                'type'     => 'text',
                'id'       => 'post-id',
                'name'     => 'post_id',
                'class'    => 'form-control',
                'required' => '',
                'value'    => $record->post_id
            ]);
            // Navigation buttons
            echo $input->setLabel('');
            echo $navigation->buttons(
                ['href' => '?page=albums-view-settings&album=' . $record->id, 'label' => 'Go to the album', 'class' => 'btn btn-warning'],
                ['type' => 'submit', 'name' => 'submit', 'value' =>'Update information', 'class' => 'btn btn-primary']
            );
            echo $form->end();
        }
        echo $this->panelClose();
    }
}
