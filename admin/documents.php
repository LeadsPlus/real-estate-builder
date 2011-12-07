<?php
/**
 *  Included in function placester_admin_documents_html from admin/init.php
 */
require_once( dirname(__FILE__) . '/../core/util.php' );
require_once( dirname(__FILE__) . '/util.php' );
require_once( dirname(__FILE__) . '/../pdf/includes/fpdf/fpdf.php' );
require_once( dirname(__FILE__) . '/../pdf/includes/fpdi/fpdi.php' );
global $upload_url;
$upload_url = add_query_arg( array(
    'p_action' => 'upload',
));
global $edit_url;
$edit_url = add_query_arg( array(
    'p_action' => 'edit',
));
global $upload_dir;

$upload_dir = get_plugin_dir( 'pdf/uploads' ) ;

if ( ! file_exists( $upload_dir ) )
    mkdir( $upload_dir );

/** ---------------------------
 *  Utility functions
 *  --------------------------- */

/**
 * Splits a PDF file into pages
 *
 * @param (string)$file - The path to the PDF file
 * @param (string)$target_dir - The path to directory where 
 *  the "pages" folder will be created
 *
 * @returns (Array)Page sizes array
 */
function placester_split_pdf_into_pages( $file, $target_dir ) {

    $pdf =& new FPDI();
    // Set the sourcefile
    $page_count = $pdf->setSourceFile(  $file, '/MediaBox');

    mkdir( $target_dir . 'pages/' );
    $page_sizes = array();
    for ( $i = 0; $i < $page_count; $i++ ) { 
        $pdf =& new FPDI();
        $pdf->setSourceFile( $file, '/MediaBox' );
        // Import the page
        $tplIdx = $pdf->importPage( $i + 1 );

        $s = $pdf->getTemplateSize( $tplIdx );
        $pdf->AddPage($s['h'] > $s['w'] ? 'P' : 'L', array($s['w'], $s['h']));

        $pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);
        $pdf->Output( $target_dir . 'pages/' . ($i + 1) . '.pdf', 'F');
        array_push( $page_sizes, $s );
    }

    return $page_sizes;
}

/**
 * Uploads the PDFs, splits them, and creates the document posts
 *
 * @returns bool true success, false otherwise  
 */
function placester_upload_pdfs() {
    global $upload_dir;
    if ( count( $_FILES['documents']['name'][0] ) == 1 && empty ( $_FILES['documents']['name'][0] ) ) {

        $return = new stdClass;
        $return->error_message = "You must select the file you want to upload";
        return $return;
    }

    for ( $i = 0; $i < count($_FILES['documents']['name']); $i++ ) {
        //@TODO validate file type with jQuery
        if ( $_FILES['documents']['type'][$i] == 'application/pdf' ) {
            // Insert document post
            $args = array( 
                'post_status' => 'publish',
                'post_type' => 'placester_document',
                'post_title' => $_FILES['documents']['name'][$i]
            );
            $doc_id = wp_insert_post( $args, true );
            if ( ! $doc_id ) {
                $return = new stdClass;
                $return->error_message = "Post could not be created.";
                return $return;
            } else {
                try {
                    // Set target location and file name
                    $target_dir = str_replace( '//', '/', $upload_dir ) . $doc_id . '/';

                    mkdir( $target_dir );
                    $target_file =  $target_dir . $doc_id . '.pdf';
                    // Upload location
                    $temp_file = $_FILES['documents']['tmp_name'][$i];
                    // Move file to proper location
                    move_uploaded_file( $temp_file, $target_file );

                    $page_sizes = placester_split_pdf_into_pages( $target_file, $upload_dir . $doc_id . '/' );
                    add_post_meta( $doc_id, '_page_sizes', $page_sizes );
                    // Create a the field def skeleton
                    $field_def_skeleton = array();
                    foreach( $page_sizes as $page ) {
                        array_push( $field_def_skeleton, array() );
                    }
                    add_post_meta( $doc_id, '_fields_def', $field_def_skeleton);
                } catch( Exception $e ) {

                    $return = new stdClass;
                    $return->error_message = $e->getMessage();

                    // Cleanup in case of error
                    placester_delete_document( $doc_id, true );
                    return $return;
                }
            }
        }
    }

    return true;
}

/**
 *  Exports a pdf
 */
function placester_export_pdf( $doc_post ) {
    $fields_def_meta = get_post_meta( $doc_post->ID, '_fields_def', true );
    $page_sizes_meta = get_post_meta( $doc_post->ID, '_page_sizes', true );
    $k = 3.75;

    global $upload_dir;
    // Set target location and file name
    $target_dir = str_replace('//','/',$upload_dir) . $doc_post->ID . '/pages/';

    // initiate FPDI
    $pdf =& new FPDI();
    $pdf_temp =& new FPDI();

    foreach ( $page_sizes_meta as $page_index => &$size ) {
        // set the sourcefile
        $pathh = $target_dir . ($page_index + 1) . '.pdf';

        $pdf_temp->setSourceFile( $target_dir . ($page_index + 1) . '.pdf', '/MediaBox' );

        // import the page
        $tplIdx = $pdf_temp->importPage(1);

        $s = $pdf_temp->getTemplateSize( $tplIdx );
        $pdf_temp->AddPage($s['h'] > $s['w'] ? 'P' : 'L', array($s['w'], $s['h']));
        $pdf_temp->useTemplate($tplIdx, 0, 0, 0, 0, true);
        foreach ( $fields_def_meta[$page_index] as $index => $field ) {

            $pdf_temp->SetFont('Arial','', 10);
            $pdf_temp->SetTextColor(255,0,0);
            $x = ( $field->coord->left - 12 ) / $k;
            $y = ( $field->coord->top ) / $k;
// echo "<pre>field\\n";
// print_r($field);
// echo "\\n" . "#end</pre>\\n";
 

// echo "<pre>x\\n";
// print_r($x . "\\n");
// print_r($y);
// echo "\\n" . "#end y</pre>\\n";
            $pdf_temp->SetXY( $x, $y );
            $pdf_temp->Write( 0, $field->values->title[0] );
        }
    }
// echo "<pre>x\\n";
// print_r($pdf_temp);
// echo "\\n" . "#end y</pre>\\n";
    ob_end_clean();
    $pdf_temp->Output( $doc_post->post_title, 'D');

}
/**
 *  Deletes or empties a directory recursively
 *
 *  @param $directory - Path to the dir
 *  @param (bool)$empty - true if dir needs to be emptied instead of deleted
 *
 *  @returns true on success, false otherwise
 */
function placester_delete_dir_recursive( $directory, $empty = false ) { 
    if( substr( $directory, -1 ) == "/" ) { 
        $directory = substr( $directory, 0, -1 ); 
    } 
    if( ! file_exists( $directory ) || ! is_dir( $directory ) ) { 
        return true; 
    } elseif( ! is_readable( $directory ) ) { 
        return array( "{$directory} is not readable." ); 
    } else { 
        $dir_handle = opendir( $directory ); 
        
        while ( $contents = readdir( $dir_handle ) ) { 
            if( $contents != '.' && $contents != '..' ) { 
                $path = $directory . "/" . $contents; 
                
                if( is_dir( $path ) ) { 
                    placester_delete_dir_recursive( $path ); 
                } else { 
                    unlink( $path ); 
                } 
            } 
        } 
        
        closedir( $dir_handle ); 

        if( $empty == false ) { 
            if( ! rmdir( $directory ) ) { 
                return array( "There was a problem deleting the document. See if you have the proper server permissions." ); 
            } 
        } 
        
        return true; 
    } 
} 

/**
 *  Deletes a wordpress document and its associated files and post data
 *
 *  @param (int)$doc_id - The placester_document post ID
 *  @param (bool)$silent - Default false. If true, the function acts as 
 *      a cleanup utility that forces the deletion of the post and of 
 *      any files associated
 *
 *  @returns (mixed) - Error string if fails, 0 if succeeds
 */
//@TODO Alert directory permission, Delete confirm dialog, UI alerts
function placester_delete_document( $doc_id, $silent = false ) {
    $doc = get_post( $doc_id );
    if ( !empty( $doc ) || $silent ) {

        /** Attempt to delete the files on the server  */
        global $upload_dir;
        $dir_deleted = placester_delete_dir_recursive( $upload_dir . $doc_id );

        if ( ! is_array( $dir_deleted ) || $silent ) {
            /** Delete the post, and bypass the trash. */ 
            wp_delete_post( $doc_id, true );
            return 0;
        } else {
            /** If the returned value is an array, return the message within it. */
            return $dir_deleted[0];
        }
    } else {
        return "The document with ID {$doc_id} does not exist.";
    }
}

/**
 *  Updates a wordpress document
 */
function placester_update_document( $doc_id, $data ) {
    $test = get_post_meta( $doc_id, '_fields_def', true );
    $answer = update_post_meta( $doc_id, '_fields_def', $data['fields_def_meta'] );
    $test1 = get_post_meta( $doc_id, '_fields_def', true );
// echo "<pre>data\\n";
// print_r($data);
// echo "\\n" . "#end data</pre>\\n";
// echo "<pre>beforedata\\n";
// print_r($test);
// echo "\\n" . "#end beforedata</pre>\\n";
// echo "<pre>afterdata\\n";
// print_r($test1);
// echo "\\n" . "#end afterdata</pre>\\n";
}


/** ---------------------------
 *  Functions that return html
 *  --------------------------- */

/**
 *  Returns or echoes the documents page overview content
 *
 *  @param (bool)$echo - Default false. If true, content is echoed instead of returned
 */
function placester_get_document_upload_form( $echo = false ) {
    global $upload_url;
    // The upload form
    $result = placester_html( 
        'form',
        array(
            'method' => 'post',
            'id' => 'placester_document_upload',
            'class' => 'clearfix',
            'action' => $upload_url,
            'enctype' => 'multipart/form-data'
        ),
        placester_html(
            'input',
            array(
                'type' => 'file',
                'name' => 'documents[]',
                'class' => 'multi'
            )
        ),
        placester_html(
            'div',
            array( 'class' => 'clear' )
        ),
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'upload_doc',
                'class' => 'button',
                'value' => 'Upload Documents',
            )
        )
    );

    if ( $echo ) 
        echo $result;
    else
        return $result;
}
/**
 *  The Placester documents table
 *
 */
function placester_get_document_table() {
    // Define the columns
    $columns = array( 
        array ( '', 'width: 3%' ),
        'Name',
        array ( 'Uploaded', 'width: 10%' ),
        array ( 'Page Count', 'width: 10%' ),
        array ( 'Fields', 'width: 10%' ),
        array ( 'Filled count', 'width: 10%' ),
    );
    $args = array(
        'post_type' => 'placester_document',
        'numberposts' => -1,
    );
    $docs = get_posts( $args );     
    
    $table_data = array();

    foreach ( $docs as $doc ) {
        $page_count = count( get_post_meta( $doc->ID, '_page_sizes', true ) );
        $fields_def_meta = get_post_meta( $doc->ID, '_fields_def', true );
        $field_count = 0;
        foreach ( $fields_def_meta as $page ) {
            $field_count += count( $page );
        }

        $edit_url = add_query_arg( array(
            'p_action' => 'edit',
            'id' => $doc->ID
        ));
        $delete_url = add_query_arg( array(
            'p_action' => 'delete',
            'id' => $doc->ID
        ));
        $row_actions = array(
            'Edit' => $edit_url,
            'Delete' => $delete_url,
        );
        $doc_info = array(
            '',
            array( 
                placester_html( 
                    'a', 
                    array( 'href' => $edit_url ), 
                    $doc->post_title 
                ), 
                $row_actions 
            ),
            mysql2date( 'M j, Y', $doc->post_date ),
            $page_count,
            $field_count
        );
        array_push( $table_data, $doc_info );
    }
    // Prepend the Columns array to the table data Array
    array_unshift( $table_data, $columns );

    return placester_get_widefat_table( $table_data, array( 'id' => 'placester_documents' ) ); 
}
/**
 *  Outputs the documents page overview content
 *
 *  @param (bool)$echo - Default false. If true, content is returned instead of 
 *      echoed
 */
function placester_get_documents_overview( $echo = false ) {
    // The upload form
    $upload_form = placester_get_document_upload_form(); 

    // The documents table and the upload form on two columns
    $result = placester_get_postbox_containers(
        array( 
            array(
                placester_get_document_table(),
                array( 'style' => 'width: 79.5%' )
            ),
            array(
                placester_get_postbox( $upload_form, 'Upload document' ),
                array( 'style' => 'width: 19.5%' )
            )
        ),
        false // No autowidth
    ); 

    if ( $echo ) 
        echo $result;
    else
        return $result;
}

/**
 *  Outputs the document edit canvas
 *
 *  @param (int)$doc_id - The document post id
 */
function placester_get_document_edit_workspace( $doc, $edit_url ) {
    $page_sizes_meta = get_post_meta( $doc->ID, '_page_sizes', true );
    $fields_val_meta = get_post_meta( $doc->ID, '_fields_value', true );
    $fields_def_meta = get_post_meta( $doc->ID, '_fields_def', true );

    $size = $page_sizes_meta[0];

    $k = 3.85826772;
    foreach ( $page_sizes_meta as &$size ) {
        $ratio = $size['h'] / $size['w'];
        $size['w'] = $size['w'] * $k; 
        $size['h'] = $size['w'] * $ratio; //$size['h']
    }
    $pdf_canvas = placester_html(
        'div',
        array( 
            'id' => 'canvas',
        ),
        placester_html(
            'div',
            array( 
                'id' => 'draw',
                'style' => "height: {$page_sizes_meta[0]['h']}px; width: {$page_sizes_meta[0]['w']}px",
            )
        ),
        placester_html(
            'div',
            array( 
                'id' => 'pdf',
                'style' => "height: {$page_sizes_meta[0]['h']}px; width: {$page_sizes_meta[0]['w']}px",
            ),
            "It appears you don't have Adobe Reader or PDF support in this web browser."
        )
    );
    $upload_form = placester_get_document_upload_form();

    // Generate the field button list to be used in a postbox
    $field_list = placester_html( 
        'div',
        array( 'class' => 'clearfix' ),
        placester_html( 
            'a',
            array(
                'class' => 'button',
                'id' => 'pl_field_text',
            ),
            'Text'
        ),
        placester_html( 
            'a',
            array(
                'class' => 'button',
                'id' => 'pl_field_select',
            ),
            'Select'
        ),
        placester_html( 
            'a',
            array(
                'class' => 'button',
                'id' => 'pl_field_dropdown',
            ),
            'Dropdown'
        ),
        placester_html( 
            'a',
            array(
                'class' => 'button',
                'id' => 'pl_field_checkbox',
            ),
            'Checkbox'
        )
    );
    $page_content = placester_get_postbox_containers(
        //@TODO Calculate column width with javascript
        array( 
            // Main content
            array(
                placester_get_postbox( $pdf_canvas, $doc->post_title ),
            ),
            // Sidebar
            array(
                // Overview
                placester_get_postbox(
                    placester_html(
                        'form',
                        array(
                            'method' => 'post',
                            'class' => 'clearfix',
                            'action' => $edit_url
                        ),
                        placester_html(
                            'input',
                            array(
                                'type' => 'hidden',
                                'name' => 'page_sizes_meta',
                                'id' => 'page_sizes_meta',
                                'value' => json_encode( $page_sizes_meta ),
                            )
                        ),
                        placester_html(
                            'input',
                            array(
                                'type' => 'hidden',
                                'name' => 'fields_def_meta',
                                'id' => 'fields_def_meta',
                                'value' => !empty( $fields_def_meta ) ? json_encode( $fields_def_meta ) : '',
                            )
                        ),
                        placester_html(
                            'input',
                            array(
                                'type' => 'hidden',
                                'name' => 'fields_val_meta',
                                'id' => 'fields_val_meta',
                                'value' => !empty( $fields_val_meta ) ? json_encode( $fields_val_meta ) : '',
                            )
                        ),
                        placester_html(
                            'div',
                            array(
                                'class' => 'doc_nav',
                            ),
                            placester_html(
                                'input',
                                array(
                                    'type' => 'submit',
                                    'name' => 'edit_doc',
                                    'id' => 'edit_doc',
                                    'class' => 'button-primary',
                                    'value' => 'Update'
                                )
                            )
                        )
                    ), 
                    'Overview',
                    array( 
                        'id' => 'pl_doc_overview',
                        'class' => 'clearfix'
                    )
                ) . 
                // Defined Fields postbox
                placester_get_postbox( 
                    '',
                    'Defined fields',
                    array( 
                        'id' => 'pl_defined_fields',
                        'class' => 'clearfix'
                    )
                ) . 
                // Add Fields postbox
                placester_get_postbox( 
                    $field_list,
                    'Add New Field',
                    array( 
                        'id' => 'pl_fields',
                        'class' => 'clearfix'
                    )
                ) . 
                // Filled in by 
                placester_get_postbox( 
                    placester_html(
                        'form',
                        array(
                            'action' => $edit_url,
                            'method' => 'POST'
                        ),
                        placester_html(
                            'p',
                            'This will export with saved document fields'
                        ),
                        placester_html(
                            'input',
                            array(
                                'type' => 'submit',
                                'name' => 'export',
                                'class' => 'button',
                                'value' => 'Export fake',
                            )
                        )
                    ),
                    'Filled in by',
                    array( 
                        'id' => 'pl_defined_fields',
                        'class' => 'clearfix'
                    )
                ),  
                array( 'style' => 'width: 19.5%' )
                ),
            ),
            false, // No autowidth
            array( 'class' => 'placester_ui' )
        );

    return $page_content;
}

/** ---------------------------
 *  Page content
 *  --------------------------- */

if ( isset( $_POST['upload_doc'] ) ) {
    $uploaded = placester_upload_pdfs();
    if ( ! is_object( $uploaded ) ) {
        $success_message = 'Document was uploaded successfully';
    } else {
        $error_message = $uploaded->error_message;
    }
}
echo '<div class="wrap">';
// Echo admin header
placester_admin_header('placester_documents');

if ( isset( $_REQUEST['p_action'] ) && ( $_REQUEST['p_action'] == 'delete' ) ) {
    $error_message = '';
    if ( isset ( $_REQUEST['id'] ) ) {
        $doc_id = $_REQUEST['id'];
        $error_message = placester_delete_document( $doc_id );
    } else {
        $error_message = 'Malformed URL. A document ID must be supplied';
    }

    if ( empty( $error_message ) ) {
        $success_message = 'The document was successfully deleted';
    } 
}

// Print success message if any
if ( !empty( $success_message ) ) {
    placester_success_message( $success_message );
}
// Print errors if any
if ( !empty( $error_message ) ) {
    placester_error_message( $error_message );
}

if ( isset( $_REQUEST['p_action'] ) && ( $_REQUEST['p_action'] == 'edit' ) ) {

    if ( isset( $_REQUEST['id'] ) ) { 
        $doc_id = $_REQUEST['id'];
        $doc = get_post( $doc_id );

        // Export
        if (isset($_POST['export'])) {
            placester_export_pdf( $doc );
        }

        // If document update was requested
        if ( isset( $_POST['edit_doc'] ) ) { 
            $data = array();
            $data['fields_def_meta'] = json_decode(str_replace( "\\", "", $_POST['fields_def_meta'] ));
            placester_update_document( $doc_id, $data );
            placester_success_message( 'The document has been successfully updated' );
        }

        if ( !empty( $doc ) ) {
            echo placester_get_document_edit_workspace( $doc, $edit_url );
        } else {
            echo "The document with ID {$doc_id} does not exist.";
        }
    } else {
        $error_message = 'Malformed URL. A document ID must be supplied';
    }

    // if (isset($_POST['sub'])) {
    //         if (isset($_POST['data'])) {
    //               foreach ($_POST['data'] as $index => $coord) {
    //                     $coord = json_decode(str_replace("\\\\", "", $coord));

    //                     $pdf->SetFont('Arial','', 10);
    //                     $pdf->SetTextColor(255,0,0);
    //                     $x = $coord->x / $k;
    //                     $y = $coord->y / $k;
    //                     $pdf->SetXY( $x, $y );
    //                     $pdf->Write(0, 'FIELD' . $index);
    //                 }
    //             
    //         }
    //         ob_end_clean();
    //         $pdf->Output('output.pdf', 'D');
    //  }

} else {
    placester_get_documents_overview( true );
}

echo '</div>';

