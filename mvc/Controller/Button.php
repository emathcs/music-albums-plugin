<?php

require_once 'Controller.php';

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
        // Delete process
        if (isset($_POST['id'])) {
            $this->delete($_POST['id']);
        }
        // Platforms
        $platforms = $this->getChildModel()->getPlatformList();
        // Get the records
        $records = $this->getModel()->select();
        // View
        $this->getView()->list(['records' => $records, 'platforms' => $platforms]);
    }

    /**
     * Add function
     */
    public function add() {
        // Platforms
        $platforms = $this->getPlatformOptions();
        // View
        $this->getView()->add(['platforms' => $platforms]);
    }

    /**
     * View function
     *
     * @param Integer $id Button id
     */
    public function view($id = 0) {
        // This process has been added here to avoid to redirect to this page from add page
        if ( isset( $_POST['submit'] ) ) {
            echo 0;
            if ( ! isset( $_POST['custom_form_nonce'] ) || ! wp_verify_nonce( $_POST['custom_form_nonce'], 'save_custom_form_action' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            echo 1;
            $link        = sanitize_text_field($_POST['link']);
            $album_id    = sanitize_text_field($_POST['album-id']);
            $platform_id = sanitize_text_field($_POST['platform-id']);
            if (!(empty($link) || empty($platform_id) || empty($platform_id))) {
                $data = [
                    'link'        => $link,
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
        $platforms = $this->getChildModel()->getPlatformList();
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
        $platforms = $this->getPlatformOptions();
        // Get the record
        $record = $this->getModel()->selectById($id);
        // View
        $this->getView()->edit(['record' => $record, 'platforms' => $platforms]);
    }
}
