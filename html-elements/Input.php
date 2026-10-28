<?php

require_once 'Element.php';

class Input extends Element {
    /**
     * Input label.
     */
    private $label;

    /**
     * Input label class.
     */
    private $labelClass;

    /**
     * Input container class.
     */
    private $containerClass;

    /**
     * Set the input label function.
     *
     * @param type $label Input label.
     * @return type void.
     */
    public function setLabel($label = '') {
        $this->label = $label;
    }

    /**
     * Get the input label function.
     *
     * @return type string Input label.
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Set the input label class function.
     *
     * @param type $labelClass Input label class.
     * @return type string Input label class.
     */
    public function setLabelClass($labelClass = '') {
        $this->labelClass = $labelClass;
    }

    /**
     * Get the input label class function.
     *
     * @return type string Input label class.
     */
    public function getLabelClass() {
        return $this->labelClass;
    }

    /**
     * Set the input container class function.
     *
     * @param type $containerClass Input container class.
     * @return type void.
     */
    public function setContainerClass($containerClass = '') {
        $this->containerClass = $containerClass;
    }

    /**
     * Get the input container class function.
     *
     * @return type string Input container class.
     */
    public function getContainerClass() {
        return $this->containerClass;
    }

    /**
     * Constructor
     *
     * @param type $containerClass Input container class.
     * @return type void.
     */
    public function __construct($containerClass = '') {
        $this->setAttribute('name');
        $this->setAttribute('placeholder');
        $this->setAttribute('required');
        $this->setAttribute('type');
        $this->setAttribute('value');
        $this->setContainerClass($containerClass);
    }

    /**
     * Generate the HTML input tag function.
     *
     * @param type array Input attributes.
     * @return type string Input container, label and input.
     */
    public function create($attrs = []) {
        $strAttributes = $this->strAttributes($attrs);
        $html  = '<div class="' . $this->getContainerClass() . '">';
        if (strlen($this->getLabel())) {
            $html .= '    <label for="' . $this->getId() . '" class="' . $this->getLabelClass() . '">' . $this->getLabel() . '</label>';
        }
        $html .= '    <input ' . $strAttributes . '>';
        $html .= '</div>';
        return $html;
    }
}
