<?php

class Controller {
    /**
     * Model
     */
    private $Model;

    /**
     * Child model
     */
    private $ChildModel;

    /**
     * Delete model
     */
    private $DeleteModel;

    /**
     * View
     */
    private $View;

    /**
     * Constructor
     *
     * @param Object $Model Object to manage the database table operations.
     * @param Object $View Object to render HTML.
     * @param Object $ChildModel Object to manage the related database table operations.
     */
    public function __construct($Model = null, $View = null, $ChildModel = null) {
        // Model
        $this->setModel($Model);
        $this->setChildModel($ChildModel);
        // View
        $this->setView($View);
    }

    /**
     * Set Model
     *
     * @param Object $Model Object to manage the database table operations.
     */
    public function setModel($Model = null) {
        $this->Model = $Model;
    }

    /**
     * Get Model
     *
     * @return Object Object to manage the database table operations.
     */
    public function getModel() {
        return $this->Model;
    }

    /**
     * Set Child Model
     *
     * @param Object $ChildModel Object to manage the related database table operations.
     */
    public function setChildModel($ChildModel = null) {
        $this->ChildModel = $ChildModel;
    }

    /**
     * Get Child Model
     *
     * @$return Object Object to manage the related database table operations.
     */
    public function getChildModel() {
        return $this->ChildModel;
    }

    /**
     * Set Delete Model
     *
     * @param Object $Model Object to manage the database table operations.
     */
    public function setDeleteModel($Model = null) {
        $this->DeleteModel = $Model;
    }

    /**
     * Get Delete Model
     *
     * @return Object Object to manage the database table operations.
     */
    public function getDeleteModel() {
        return $this->DeleteModel;
    }

    /**
     * Set View
     *
     * @param Object $View Object to render HTML.
     */
    public function setView($View = null) {
        $this->View = $View;
    }

    /**
     * Get View
     *
     * @param Object Object to render HTML.
     */
    public function getView() {
        return $this->View;
    }

    /**
     * Delete function
     *
     * @return Boolean Status of the operation.
     */
    public function delete() {
        if (isset( $_POST['delete'])) {
            if (!isset( $_POST['custom_form_nonce']) || !wp_verify_nonce($_POST['custom_form_nonce'], 'delete_custom_form_action')) {
                wp_die('Security check failed. Please try again.');
            }
            $id = sanitize_text_field($_POST['id']);
            return $this->getDeleteModel()->deleteById($id);
        }
    }
}
