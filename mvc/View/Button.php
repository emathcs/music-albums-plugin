<?php

require_once 'View.php';

class ButtonView extends View {
    /**
     * Table function
     *
     * @param Array $records Button records
     * @return String $html An HTML table
     */
    public function table($records = [], $platforms = []) {
        $html = '';
        $total = count($records);
        if ($total) {
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <thead>';
            echo '        <tr>';
            echo '            <th scope="col">Id</th>';
            echo '            <th scope="col">Platform</th>';
            echo '            <th scope="col">Link</th>';
            echo '            <th scope="col" colspan="3" class="col-actions">Actions</th>';
            echo '        </tr>';
            echo '    </thead>';
            echo '    <tbody>';
            for ($i = 0; $i < $total; $i++) {
                echo '    <tr>';
                echo '        <th scope="row">' . $records[$i]->id . '</th>';
                echo '        <td>' . $platforms[$records[$i]->platform_id] . '</td>';
                echo '        <td>' . $records[$i]->link . '</td>';
                echo '        <td class="col-action"><a href="?page=buttons-view-settings&button=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
                echo '        <td class="col-action"><a href="?page=buttons-edit-settings&button=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                echo '        <td class="col-action">' . $this->deleteForm($records[$i]->id, 'button') . '</td>';
                echo '    </tr>';
            }
            echo '    </tbody>';
            echo '</table>';
        }
        return $html;
    }

    /**
     * List render function
     *
     * @param Array $data Data to render
     */
    public function list($data = []) {
        $records = isset($data['records']) ? $data['records'] : [];
        $total = count($records);
        // Panel
        echo $this->panelOpen('Buttons');
        echo '<div class="mb-3">';
        echo '    <a href="?page=buttons-add-settings" class="btn btn-sm btn-primary">Add a new button</a>';
        echo '</div>';
        echo $this->table($records, $data['platforms']);
        echo $this->panelClose();
    }

    /**
     * Add render function
     *
     * @param Array $data Data to render
     */
    public function add($data = []) {
        echo $this->panelOpen('Add a new button');
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');
        $select = new Select('mb-3');
        $select->setLabelClass('form-label');
        // Panel
        echo $form->init(['action' => "admin.php?page=buttons-view-settings", 'method' => 'post']);
        // Security field: Nonce to prevent CSRF attacks
        wp_nonce_field( 'save_custom_form_action', 'custom_form_nonce' );
        // Album id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'album-id',
            'name'     => 'album-id',
            'class'    => 'form-control',
            'value'    => $data['album_id']
        ]);
        // Song id
        echo $input->create([
            'type'     => 'hidden',
            'id'       => 'song-id',
            'name'     => 'song-id',
            'class'    => 'form-control',
            'value'    => $data['song_id']
        ]);
        // Platform
        $select->setLabel('Platform');
        echo $select->create(
            [
                'type'     => 'text',
                'id'       => 'platform-id',
                'name'     => 'platform-id',
                'class'    => 'form-control',
                'required' => '',
                'value'    => ''
            ],
            $data['platforms']
        );
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
        echo $this->panelOpen( 'View button' );
        if ($record) {
            echo '<table class="table table-sm table-striped table-hover table-bordered">';
            echo '    <tbody>';
            echo '        <tr>';
            echo '            <th>Platform</th>';
            echo '            <td>' . $data['platforms'][$record->platform_id] . '</td>';
            echo '        </tr>';
            echo '        <tr>';
            echo '            <th>Link</th>';
            echo '            <td>' . $record->link . '</td>';
            echo '        </tr>';
            echo '    </tbody>';
            echo '</table>';
            echo '<div class="mb-3">';
            echo '    <a href="?page=buttons-settings" class="btn btn-sm btn-secondary">Go to Buttons</a>';
            echo '    <a href="?page=buttons-edit-settings&button=' . $record->id .'" class="btn btn-sm btn-primary">Edit button</a>';
            if ($record->album_id) {
                echo '    <a href="?page=albums-view-settings&album=' . $record->album_id .'" class="btn btn-sm btn-success">Go to the album</a>';
            }
            if ($record->song_id) {
                echo '    <a href="?page=songs-view-settings&song=' . $record->song_id .'" class="btn btn-sm btn-success">Go to the song</a>';
            }
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
        $select = new Select('mb-3');
        $select->setLabelClass('form-label');
        $record = isset($data['record']) ? $data['record'] : [];
        // Panel
        echo $this->panelOpen('Edit button');
        echo $form->init(['action' => "admin.php?page=buttons-view-settings&button=" . $record->id, 'method' => 'post']);
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
        // Platform
        $select->setLabel('Platform');
        echo $select->create(
            [
                'type'     => 'text',
                'id'       => 'platform-id',
                'name'     => 'platform-id',
                'class'    => 'form-control',
                'required' => '',
                'value'    => $record->platform_id
            ],
            $data['platforms']
        );
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
