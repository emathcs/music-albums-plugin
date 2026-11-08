<?php

class Select extends Input {
    /**
     * Generate the HTML input tag function.
     *
     * @param type array Input attributes.
     * @return type string Input container, label and input.
     */
    public function create($attrs = [], $options = []) {
        $total = count($options);
        $strAttributes = $this->strAttributes($attrs);
        $html  = '<div class="' . $this->getContainerClass() . '">';
        if (strlen($this->getLabel())) {
            $html .= '    <label for="' . $this->getId() . '" class="' . $this->getLabelClass() . '">' . $this->getLabel() . '</label>';
        }
        $html .= '    <select ' . $strAttributes . '>';
        for ($i = 0; $i < $total; $i++) {
            $html .= '        <option value="' . $options[$i]['value'] . '" ' . ($options[$i]['value'] == $attrs['value'] ? 'selected' : '') . '>' . $options[$i]['label'] . '</option>';
        }
        $html .= '    </select>';
        $html .= '</div>';
        return $html;
    }
}
