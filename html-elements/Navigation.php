<?php

class Navigation extends Input {
    /**
     * Generate the HTML navigation
     *
     * @param Array $attrs_link   HTML element attributes
     * @param Array $attrs_submit HTML element attributes
     * @return String HTML navigation
     */
    public function buttons($attrs_link = [], $attrs_submit = []) {
        $strAttributesLink   = $this->strAttributes($attrs_link);
        $strAttributesSubmit = $this->strAttributes($attrs_submit);
        $html  = '<div class="' . $this->getContainerClass() . '">';
        // Go back
        $html .= '    <a href="' . $attrs_link['href'] . '" ' . $strAttributesLink . '>' . $attrs_link['label'] . '</a> ';
        // Submit
        $html .= '    <input ' . $strAttributesSubmit . '>';
        $html .= '</div>';
        return $html;
    }
}
