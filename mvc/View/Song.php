<?php

class SongView extends View {
    /**
     * Table function
     *
     * @param Array $records Song records
     * @return String $html An HTML table
     */
    public function table($records = []) {
        $html = '';
        $total = count($records);
        if ($total) {
            $html .= '<table class="table table-sm table-striped table-hover table-bordered">';
            $html .= '    <thead>';
            $html .= '        <tr>';
            $html .= '            <th scope="col">Id</th>';
            $html .= '            <th scope="col">Name</th>';
            $html .= '            <th scope="col" colspan="3" class="col-actions">Actions</th>';
            $html .= '        </tr>';
            $html .= '    </thead>';
            $html .= '    <tbody>';
            for ($i = 0; $i < $total; $i++) {
                $html .= '    <tr>';
                $html .= '        <th scope="row">' . $records[$i]->id . '</th>';
                $html .= '        <td>' . $records[$i]->name . '</td>';
                $html .= '        <td class="col-action"><a href="?page=songs-view-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-success">View</a></td>';
                $html .= '        <td class="col-action"><a href="?page=songs-edit-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                $html .= '        <td class="col-action">' . $this->deleteForm($records[$i]->id, 'song') . '</td>';
                $html .= '    </tr>';
            }
            $html .= '    </tbody>';
            $html .= '</table>';
        }
        return $html;
    }

    /**
     * List page
     *
     * @param Array $data Data to render
     */
    public function list($data = []) {
        $records = isset($data['records']) ? $data['records'] : [];

        // Panel
        echo $this->panelOpen('Songs');
        echo '<div class="mb-3">';
        echo '    <a href="?page=songs-add-settings" class="btn btn-sm btn-primary">Add a new song</a>';
        echo '</div>';
        echo $this->table($records);
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
        $album_id = isset($data['album_id']) ? $data['album_id'] : 0;
        // Model for redirection
        $parameters = '';
        if (isset($data['album_id']) && $data['album_id']) {
            $parameters = '&album=' . $data['album_id'];
        }

        // Panel
        echo $this->panelOpen('Add a new song');
        echo $form->init(['action' => 'admin.php?page=songs-view-settings' . $parameters, 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Album id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'album-id',
            'name'     => 'album-id',
            'class'    => 'form-control',
            'value'    => $album_id
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
     * View page
     *
     * @param Array $data Data to render
     */
    public function view($data = []) {
        $record = isset($data['record']) ? $data['record'] : [];

        // Panel
        echo $this->panelOpen( 'View song' );
        if ($record) {
            // Get the Button view
            $buttonsTable = false;
            if (isset($data['buttons']) && count($data['buttons'])) {
                $buttonsTable = true;
                $buttonView = new ButtonView();
            }
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <th>Name</th>';
            echo '            <td>' . $record->name . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=albums-view-settings&album=' . $record->album_id .'" class="btn btn-sm btn-warning">Go to the album</a>';
            echo '    <a href="?page=songs-edit-settings&song=' . $record->id .'" class="btn btn-sm btn-secondary">Edit song</a>';
            echo '    <a href="?page=songs-add-settings&album=' . $record->album_id . '" class="btn btn-sm btn-primary">Add a new song</a>';
            echo '</div>';
            // Button view
            echo '<br><hr><br>';
            echo '<h2>Buttons</h2>';
            echo '<a href="?page=buttons-add-settings&song=' . $record->id . '" class="btn btn-sm btn-primary">Add a new button</a>';
            echo '<br><br>';
            if ($buttonsTable) {
                echo $buttonView->table($data['buttons'], $data['platforms']);
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

        // Panel
        echo $this->panelOpen('Edit song');
        if ($record) {
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
        }
        echo $this->panelClose();
    }
}
