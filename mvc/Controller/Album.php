<?php

require_once 'Controller.php';

class AlbumController extends Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $Model = new AlbumModel('albums');
        $View  = new AlbumView();
        // Parent constructor
        parent::__construct($Model, $View);
    }

    /**
     * List function
     */
    public function list() {
        // Delete
        if (isset($_POST['delete']) && isset($_POST['model'])) {
            // Album model
            if ('album' === $_POST['model']) {
                $this->setDeleteModel($this->getModel());
            }
            $this->delete();
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
        // Default album image
        $default_src = $src_default = esc_url(ALBUM_URL . 'assets/images/default-cover.png');
        // View
        $this->getView()->add(['default_src' => $default_src]);
    }

    /**
     * View function
     *
     * @param Integer $id Album id
     */
    public function view($id = 0) {
        // Children model
        $songModel = new SongModel('songs');
        $buttonModel = new ButtonModel('buttons');
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
        // Delete Button or Song
        if (isset($_POST['delete']) && isset($_POST['model'])) {
            // Song model
            if ('song' === $_POST['model']) {
                $this->setDeleteModel($songModel);
            }
            // Button model
            if ('button' === $_POST['model']) {
                $this->setDeleteModel($buttonModel);
            }
            $this->delete();
        }
        // Get the record
        $record = $this->getModel()->selectById($id);
        // Get the related songs
        $songs = $songModel->selectByAlbumId($id);
        // Get the related buttons
        $buttons = $buttonModel->selectByAlbumId($id);
        // Platforms
        $platformModel = new PlatformModel();
        $platforms = $platformModel->getListFormat();
        // Image section
        $src = wp_get_attachment_image_url($record->cover, 'medium');
        // View
        $this->getView()->view(['record' => $record, 'songs' => $songs, 'buttons' => $buttons, 'platforms' => $platforms, 'src' => $src]);
    }

    /**
     * Edit function
     *
     * @param Integer $id Album id
     */
    public function edit($id = 0) {
        // Get the record
        $record = $this->getModel()->selectById($id);
        // Image section
        $src = wp_get_attachment_image_url($record->cover, 'medium');
        $default_src = esc_url(ALBUM_URL . 'assets/images/default-cover.png');
        // View
        $this->getView()->edit(['record' => $record, 'src' => $src, 'default_src' => $default_src]);
    }
}
