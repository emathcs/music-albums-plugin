<?php

require_once 'Controller.php';

class AlbumController extends Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $Model = new AlbumModel('albums');
        $View  = new AlbumView();
        // Alternative
        $ModelChild = new SongModel('songs');
        // Parent constructor
        parent::__construct($Model, $View, $ModelChild);
    }

    /**
     * List function
     */
    public function list() {
        // Delete process
        if (isset($_POST['id'])) {
            $this->delete($_POST['id']);
        }
        // Get the records
        $records = $this->getModel()->select();
        // View
        $this->getView()->list(['records' => $records]);
    }

    /**
     * Add function
     */
    public function add() {
        // View
        $this->getView()->add();
    }

    /**
     * View function
     *
     * @param Integer $id Album id
     */
    public function view($id = 0) {
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id    = sanitize_text_field($_POST['id']);
            $cover = sanitize_text_field($_POST['cover']);
            $name  = sanitize_text_field($_POST['name']);
            if (!(empty($name) || empty($cover))) {
                $data = [
                    'cover' => $cover,
                    'name'  => $name
                ];
                $format_data = [
                    '%s',
                    '%s'
                ];
                $this->getModel()->updateById(
                    $id,
                    $data,
                    $format_data
                );
            }
        }
        // This process has been added here to avoid to redirect to this page from save page
        if ( isset( $_POST['submit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $cover = sanitize_text_field($_POST['cover']);
            $name  = sanitize_text_field($_POST['name']);
            if (!(empty($name) || empty($cover))) {
                $data = [
                    'cover' => $cover,
                    'name'  => $name
                ];
                $id = $this->getModel()->insert($data);
            }
        }
        // Delete a song
        if ( isset( $_POST['submit-delete'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'delete_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $songModel = new SongModel('songs');
            $song_id = sanitize_text_field($_POST['id']);
            $songModel->deleteById($song_id);
        }
        // Get the record
        $record = $this->getModel()->selectById($id);
        // Get the related songs
        $songModel = new SongModel('songs');
        $songs = $songModel->selectByAlbumId($id);
        // Get the related buttons
        $buttonModel = new ButtonModel('buttons');
        $buttons = $buttonModel->selectByAlbumId($id);
        // Temp platforms
        $platforms = [1 => 'Apple Music', 2 => 'Spotify', 3 => 'Amazon Music'];
        // View
        $this->getView()->view(['record' => $record, 'songs' => $songs, 'buttons' => $buttons, 'platforms' => $platforms]);
    }

    /**
     * Edit function
     *
     * @param Integer $id Album id
     */
    public function edit($id = 0) {
        // Get the record
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->edit(['record' => $record]);
    }
}
