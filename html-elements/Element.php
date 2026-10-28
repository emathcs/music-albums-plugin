<?php

class Element {
    /**
     * Input id.
     */
    private $id;

    /**
     * Set the input id function.
     *
     * @param type $id Input id.
     * @return type void.
     */
    public function setId($id = '') {
        $this->id = $id;
    }

    /**
     * Get the input id function.
     *
     * @return type string Input id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Element attributes.
     */
    private $attributes = [
        'id',
        'class'
    ];

    /**
     * Set an Element attribute function.
     *
     * @param type $attr Element attribute.
     * @return type void.
     */
    public function setAttribute($attr = '') {
        if (strlen($attr)) {
            $this->attributes[] = $attr;
        }
    }

    /**
     * Get the attributes array function.
     *
     * @return type array Attributes array.
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Get the total of attributes function.
     *
     * @return type integer Total of attributes.
     */
    public function getTotalAttributes() {
        return count($this->attributes);
    }

    /**
     * Create the attributes string function.
     *
     * @param type $attrs Attributes array.
     * @return type string Parameters in string format.
     */
    public function strAttributes($attrs = []) {
        if (isset($attrs['id'])) {
            $this->setId($attrs['id']);
        }
        $strAttributes = '';
        $attributes = $this->getAttributes();
        $total      = $this->getTotalAttributes();
        for ($i = 0; $i < $total; $i++) {
            if (isset($attrs[$attributes[$i]])) {
                $strAttributes .= $attributes[$i] . '="' . $attrs[$attributes[$i]] . '"';
            }
        }
        return $strAttributes;
    }
}
