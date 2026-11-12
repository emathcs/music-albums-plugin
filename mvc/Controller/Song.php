<?php

class SongController extends Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $Model = new SongModel('songs');
        $View  = new SongView();
        // Parent constructor
        parent::__construct($Model, $View);
    }

    /**
     * List
     */
    public function list() {
        // Delete
        if (isset($_POST['delete']) && isset($_POST['model'])) {
            // Song model
            if ('song' === $_POST['model']) {
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
     * Add
     *
     * @param Integer $album_id Album id
     */
    public function add($album_id = 0) {
        // View
        $this->getView()->add(['album_id' => $album_id]);
    }

    /**
     * View
     *
     * @param Integer $id Song id
     */
    public function view($id = 0) {
        // Button Model
        $buttonModel = new ButtonModel('buttons');
        // This process has been added here to avoid to redirect to this page from add page
        if ( isset( $_POST['submit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $name     = sanitize_text_field($_POST['name']);
            $album_id = sanitize_text_field($_POST['album-id']);
            if (!(empty($name) || empty($album_id))) {
                $data = [
                    'name'     => $name,
                    'album_id' => $album_id
                ];
                $id = $this->getModel()->insert($data);
            }
        }
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id       = sanitize_text_field($_POST['id']);
            $name     = sanitize_text_field($_POST['name']);
            if (!(empty($name))) {
                $data = [
                    'name'  => $name
                ];
                $format_data = [
                    '%s'
                ];
                $this->getModel()->updateById(
                    $id,
                    $data,
                    $format_data
                );
            }
        }
        // Delete Button
        if (isset($_POST['delete']) && isset($_POST['model'])) {
            // Song model
            if ('button' === $_POST['model']) {
                $this->setDeleteModel($buttonModel);
            }
            $this->delete();
        }
        // Get the record
        $record = $this->getModel()->selectById($id);
        // Get the related buttons
        $buttons = $buttonModel->selectBySongId($id);
        // Platforms
        $platformModel = new PlatformModel();
        $platforms = $platformModel->getListFormat();
        // View
        $this->getView()->view(['record' => $record, 'buttons' => $buttons, 'platforms' => $platforms]);
    }

    /**
     * Edit
     *
     * @param Integer $id Song id
     */
    public function edit($id = 0) {
        // Get the record
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->edit(['record' => $record]);
    }
}
