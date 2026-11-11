<?php

class ButtonController extends Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $Model = new ButtonModel('buttons');
        $View  = new ButtonView();
        // ModelChild
        $ChildModel = new PlatformModel();
        // Parent constructor
        parent::__construct($Model, $View, $ChildModel);
    }

    /**
     * List function
     */
    public function list() {
        // Delete
        if (isset($_POST['delete']) && isset($_POST['model'])) {
            // Button model
            if ('button' === $_POST['model']) {
                $this->setDeleteModel($this->getModel());
            }
            $this->delete();
        }
        // Platforms
        $platforms = $this->getChildModel()->getListFormat();
        // Get the records
        $records = $this->getModel()->select();
        // View
        $this->getView()->list(['records' => $records, 'platforms' => $platforms]);
    }

    /**
     * Add function
     */
    public function add() {
        // Album id
        $album_id = isset($_GET['album']) ? $_GET['album'] : 0;
        // Song id
        $song_id = isset($_GET['song']) ? $_GET['song'] : 0;
        // Platforms
        $platforms = $this->getChildModel()->getOptionsFormat();
        // View
        $this->getView()->add(['platforms' => $platforms, 'album_id' => $album_id, 'song_id' => $song_id]);
    }

    /**
     * View function
     *
     * @param Integer $id Button id
     */
    public function view($id = 0) {
        // This process has been added here to avoid to redirect to this page from add page
        if ( isset( $_POST['submit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $link        = sanitize_text_field($_POST['link']);
            $song_id     = sanitize_text_field($_POST['song-id']);
            $album_id    = sanitize_text_field($_POST['album-id']);
            $platform_id = sanitize_text_field($_POST['platform-id']);
            // Update the values if they are 0
            $song_id  = $song_id ? $song_id : null;
            $album_id = $album_id ? $album_id : null;
            if (!(empty($link) || empty($platform_id))) {
                $data = [
                    'link'        => $link,
                    'song_id'     => $song_id,
                    'album_id'    => $album_id,
                    'platform_id' => $platform_id
                ];
                $id = $this->getModel()->insert($data);
            }
        }
        // This process has been added here to avoid to redirect to this page from edit page
        if ( isset( $_POST['submit-edit'] ) ) {
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $id          = sanitize_text_field($_POST['id']);
            $link        = sanitize_text_field($_POST['link']);
            $platform_id = sanitize_text_field($_POST['platform-id']);
            if (!(empty($platform_id) || empty($link))) {
                $data = [
                    'link'        => $link,
                    'platform_id' => $platform_id
                ];
                $format_data = [
                    '%s',
                    '%d'
                ];
                $this->getModel()->updateById(
                    $id,
                    $data,
                    $format_data
                );
            }
        }
        // Platforms
        $platforms = $this->getChildModel()->getListFormat();
        // Get the record
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->view(['record' => $record, 'platforms' => $platforms]);
    }

    /**
     * Edit function
     *
     * @param Integer $id Button id
     */
    public function edit($id = 0) {
        // Platforms
        $platforms = $this->getChildModel()->getOptionsFormat();
        // Get the record
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->edit(['record' => $record, 'platforms' => $platforms]);
    }
}
