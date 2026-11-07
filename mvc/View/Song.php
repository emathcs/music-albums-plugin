<?php

require_once 'View.php';

class SongView extends View {
    /**
     * Table
     *
     * @param Array $records Song records.
     * @return String $html An HTML table.
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
                $html .= '        <td class="col-action"><a href="?page=songs-view-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-secondary">View</a></td>';
                $html .= '        <td class="col-action"><a href="?page=songs-edit-settings&song=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
                $html .= '        <td class="col-action">' . $this->deleteForm($records[$i]->id) . '</td>';
                $html .= '    </tr>';
            }
            $html .= '    </tbody>';
            $html .= '</table>';
        }
        return $html;
    }
    /**
     * List
     */
    public function list($records = []) {
        $total = count($records);
        echo $this->panelOpen('Songs');
        echo '<div class="mb-3">';
        echo '    <a href="?page=songs-add-settings" class="btn btn-sm btn-primary">Add a new song</a>';
        echo '</div>';
        echo $this->table($records);
        echo $this->panelClose();
    }

    /**
     * Add function
     *
     * @param Array $data Data to render in the HTML
     */
    public function add($data = []) {
        $form  = new Form();
        $input = new Input('mb-3');
        $input->setLabelClass('form-label');

        $album_id = isset($data['album_id']) ? $data['album_id'] : 0;

        echo $this->panelOpen('Add a new song');
        echo $form->init(['action' => "admin.php?page=songs-view-settings", 'method' => 'post']);
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
            echo '    <a href="?page=albums-view-settings&album=' . $record->album_id .'" class="btn btn-sm btn-success">Go to the album</a>';
            echo '</div>';
            echo $this->panelClose();
        }
    }

    /**
     * Edit function
     *
     * @param Array $data Data to render in the HTML
     */
    public function edit($data = []) {
        if (!count($data)) {
            return;
        }
        $record = $data['record'];
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
