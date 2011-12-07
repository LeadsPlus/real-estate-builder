<?php
include_once('util.php');

/** ---------------------------
 *  Functions that return html
 *  --------------------------- */

/**
 *  Return the Text field form
 */
function placester_get_text_field_form() {
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'class' => 'clearfix',
            'id' => 'placester_edit_text_field',
            'action' => '#'
        ),
        placester_html(
            'label',
            array( 'for' => 'title' ),
            'Title'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'title',
            )
        ),
        placester_html(
            'label',
            array( 'for' => 'description' ),
            'Description'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'description',
                'id' => 'description',
            )
        ),
        // Buttons
        placester_html(
            'a',
            array( 'class' => 'button cancel' ),
            'Cancel'
        ),
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'place_field',
                'class' => 'button',
                'value' => 'Save and Place',
            )
        )
    );

    return $result;
}

/**
 *  Return the Select field form
 */
function placester_get_select_field_form() {
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'class' => 'clearfix',
            'id' => 'placester_edit_select_field',
            'action' => '#'
        ),
        placester_html(
            'label',
            array( 'for' => 'title' ),
            'Title'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'title',
            )
        ),
        placester_html(
            'label',
            array( 'for' => 'description' ),
            'Description'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'description',
                'id' => 'description',
            )
        ),
        // Replicable option
        placester_html( 
            'div',
            array( 'class' => 'placester_replicable' ),
            placester_html(
                'label',
                array( 'for' => 'options' ),
                'Options'
            ),
            placester_html(
                'input',
                array(
                    'type' => 'text',
                    'name' => 'options',
                    'class' => 'required_group multi',
                )
            )
        ),
        placester_html(
            'span',
            array( 'class' => 'ui_description' ),
            'Add a * to the end of the option you want to be selected by default.'
        ),
        // Buttons
        placester_html(
            'a',
            array( 'class' => 'button cancel' ),
            'Cancel'
        ),
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'place_field',
                'class' => 'button',
                'value' => 'Save and Place',
            )
        ) 
    );

    return $result;
}
/**
 *  Return the Dropdown field form
 */
function placester_get_dropdown_field_form() {
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'class' => 'clearfix',
            'id' => 'placester_edit_dropdown_field',
            'action' => '#'
        ),
        placester_html(
            'label',
            array( 'for' => 'title' ),
            'Title'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'title',
            )
        ),
        placester_html(
            'label',
            array( 'for' => 'description' ),
            'Description'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'description',
                'id' => 'description',
            )
        ),
        // Replicable option
        placester_html( 
            'div',
            array( 'class' => 'placester_replicable' ),
            placester_html(
                'label',
                array( 'for' => 'options' ),
                'Options'
            ),
            placester_html(
                'input',
                array(
                    'type' => 'text',
                    'name' => 'options',
                    'class' => 'required_group multi',
                )
            )
        ),
        placester_html(
            'span',
            array( 'class' => 'ui_description' ),
            'Add a * to the end of the option you want to be selected by default.'
        ),
        // Buttons
        placester_html(
            'a',
            array( 'class' => 'button cancel' ),
            'Cancel'
        ),
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'place_field',
                'class' => 'button',
                'value' => 'Save and Place',
            )
        ) 
    );

    return $result;
}

/**
 *  Return the Checkbox field form
 */
function placester_get_checkbox_field_form() {
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'id' => 'placester_edit_checkbox_field',
            'class' => 'clearfix',
            'action' => '#'
        ),
        placester_html(
            'label',
            array( 'for' => 'title' ),
            'Title'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'title',
            )
        ),
        placester_html(
            'label',
            array( 'for' => 'description' ),
            'Description'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'description',
                'id' => 'description',
            )
        ),
        // Replicable option
        placester_html( 
            'div',
            array( 'class' => 'placester_replicable' ),
            placester_html(
                'label',
                array( 'for' => 'options' ),
                'Options'
            ),
            placester_html(
                'input',
                array(
                    'type' => 'text',
                    'name' => 'options',
                    'class' => 'required_group multi',
                )
            )
        ),
        placester_html(
            'span',
            array( 'class' => 'ui_description' ),
            'Add a * to the end of each option you want to be checked by default.'
        ),
        // Buttons
        placester_html(
            'a',
            array( 'class' => 'button cancel' ),
            'Cancel'
        ),
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'place_field',
                'class' => 'button',
                'value' => 'Save and Place',
            )
        )
    );

    return $result;
}

/** ---------------------------
 *  Ajax functions
 *  --------------------------- */

/**
 *  Callback function for when a field button 
 *  in the "Add new field" posbox is clicked.
 *
 *  JavaScript in "js/admin.documents.js"
 *
 */
add_action( 'wp_ajax_add_field_form', 'placester_ajax_add_field_form' );
function placester_ajax_add_field_form() {
    $field_type = trim( $_POST['field_type'] );

    // Add the correct 
    $postbox = placester_get_postbox( 
        call_user_func( "placester_get_{$field_type}_field_form" ),
        sprintf( '%s field', ucfirst( $field_type ) ), 
        array(
            'style' => 'display:none',
            'class' => 'placester_field_form_postbox'
        )
    ); 
    echo $postbox;
    die; 
}
