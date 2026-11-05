<?php

class View {
    /**
     * Open panel
     */
    public function panelOpen($title = '') {
        $render  = '<div class="albums-wrap">';
        $render .= '    <div class="container albums-panel">';
        $render .= '        <div class="row">';
        $render .= '            <div class="col-xs">';
        $render .= '                <h1>' . esc_html( $title ) . '</h1>';
        $render .= '            </div>';
        $render .= '            <div class="col-xs">';
        return $render;
    }

    /**
     * Close panel
     */
    public function panelClose() {
        $render  = '            </div>';
        $render .= '        </div>';
        $render .= '    </div>';
        $render .= '</div>';
        return $render;
    }

    /**
     * Delete form
     */
    public function deleteForm($id = 0) {
        $form  = new Form();
        $input = new Input();

        $html  = $form->init(['action' => "", 'method' => 'post']);
        $html .= wp_nonce_field( 'delete_custom_form_action', 'custom_form_nonce', true, false );
        $html .= $input->setLabel('');
        $html .= $input->create([
            'type'  => 'hidden',
            'id'    => 'id-' . $id,
            'name'  => 'id',
            'class' => 'form-control',
            'value' => $id
        ]);
        $html .= $input->setLabel('');
        $html .= $input->create(['type' => 'submit', 'name' => 'submit-delete', 'value' =>'Delete', 'class' => 'btn btn-sm btn-danger']);
        $html .= $form->end();
        return $html;
    }
}
