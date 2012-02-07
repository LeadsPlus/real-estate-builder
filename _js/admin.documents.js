jQuery(document).ready(function() {
    // Set globals
    var myPDF = new PDFObject({ 
        url: docinfo.pdf_url,
        pdfOpenParams: { page: '1', zoom:'200,200,200', viewrect: '0,0,400,400', pagemode: 'none', scrollbars: '0', toolbar: '0', statusbar: '0', messages: '0', navpanes: '0' }
    }).embed('pdf'); 
    var current_page = 1;
    var field_type;
    var is_unsaved = false;

    // Get the document information
    page_sizes_meta = $.parseJSON( $('#page_sizes_meta').val());
    fields_def_meta = $.parseJSON( $('#fields_def_meta').val());

    updateAllDefinedFieldsPostbox(fields_def_meta, 1);
    fields_val_meta = $.parseJSON( $('#fields_val_meta').val());

    $info_container = $('<div class="clearfix"></div>');
    $page_info = $('<p class="info left">Page: <span class="current_page">' + current_page + '</span> of <span class="total_pages">' + docinfo.page_count + '</span></p>');
    $status_info = $('<p class="info right">Status: <span class="status">Saved</span>');

    $info_container.append($page_info);
    $info_container.append($status_info);

    $("#pl_doc_overview .inside form").before($info_container);

    // The prev / next buttons
    $next_page_button = $('<a href="#" id="pl_doc_next_page" class="button">Next Page</a>');
    $prev_page_button = $('<a href="#" id="pl_doc_prev_page" class="button">Previous Page</a>');
    // If the document has more than one page, 
    // add the next and previous buttons
    if (docinfo.page_count > 1) {
        $('.postbox#pl_doc_overview .inside input[type="submit"]')
            .before($prev_page_button, $next_page_button);
    }


    // The PDF element
    $pdf_object_element = $(myPDF);

    updateToPage(current_page);

    /**
     *  Update button event
     */
    $('#edit_doc').click(function(){
        $(window).unbind('beforeunload');
    });

    /**
     *  Next page button event
     */
    $next_page_button.click(function(e) {
        e.preventDefault(); 
        // Process the document url
        current_page = goToPage('next', $pdf_object_element);
    });

    /**
     *  Previous page button event
     */
    $prev_page_button.click(function(e) {
        e.preventDefault(); 
        // Process the document url
        current_page = goToPage('prev', $pdf_object_element);
    });

    /**
     *  Field remove event
     */
    $('.field .remove').live('click', function(e) {
        e.preventDefault();
        ids = processFieldId($(this).closest('.field').attr('id'));

        deleteField(ids.page, ids.field, fields_def_meta, current_page);
        markAsModified();
        is_unsaved = true;
    });


    /**
     *  Sidebar field hover event
     */
    $('#pl_defined_fields .field .info').live('click', function(e){
        id_attr = $(this).closest('.field').attr('id');
        ids = processFieldId(id_attr);
        if (current_page-1 != ids.page) {
            goToPage(parseInt(ids.page) + 1, $pdf_object_element);
        }
    });

    /**
     *  Field hover event
     */
    $('.field').live('mouseover mouseout', function(e) {
      if ( e.type == 'mouseover' ) {
        field_id = $(this).attr('id');
        $('#canvas, #pl_defined_fields')
            .find('#' + field_id)
            .addClass('hovered');
       $(this).removeClass('hovered');
      } else {
        $('#canvas, #pl_defined_fields')
            .find('.hovered')
            .removeClass('hovered');
      }
    });


    /**
     *  Add New field button event
     */
    $('#pl_fields .button').click( function(e) {
        e.preventDefault();

        $this = $(this);
        field_type = $(this).attr('id').split('pl_field_')[1];
        // Add postbox
        data = {
            action: 'add_field_form',
            // Get the filed type from the button id
            field_type: field_type
        };

        // Request the form postbox
        $.post(ajaxurl, data, function(response) {
            $('.placester_field_form_postbox').remove();
            //@TODO Confirm dialog if anything changed and new button pressed
            $response_postbox = $(response);
            $add_button = $('<a href="#" class="add">Add new</a>');

            $response_postbox
                .find('.placester_replicable')
                .append($add_button);
            $field = $this
                .closest('.postbox')
                .after($response_postbox);
            $response_postbox.fadeIn();


            // Multifield add click
            $add_button.bind('click', function(e) {
                e.preventDefault();
                $replicable = $(this).closest('.placester_replicable');
                $cloned = $replicable.clone();
                $cloned.find('.add')
                $cloned.find('label').remove();
                $cloned.find('.add').remove();
                $cloned
                    .find('input[type=text]')
                    .val('');
                $replicable_elements = $replicable
                    .parent()
                    .find('.placester_replicable');
                $replicable_elements
                    .eq($replicable_elements.length-1)
                    .after($cloned); 
            });


            $field_form = $response_postbox.find('form');
            $field_form.validate({
                //set the rules for the field names
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    // options: {
                    //     required: true,
                    // },
                },
                //set messages to appear inline
                messages: {
                    title: "Please enter a field title.",
                    description: "Please enter a field description.",
                }
            });

            $field_form.find('.cancel').bind('click', function() { 
                $(this).closest('.postbox').fadeOut('fast');
               //@TODO Cleanup on cancel, delete all extra fields
            })

            $add_fields_postbox = $('#pl_fields');
            // When the field form is submitted
            $field_form.submit(function(e){
                if ($field_form.valid()) {
                    $add_fields_postbox.hide();
                    // Process form data
                    var values = new Object();
                    $(this).find('input[type="text"]').each(function(){
                        field_name = $(this).attr('name');
                        is_multi_val = $(this).hasClass('multi');
                        if ( is_multi_val > -1 ) {
                            if ( typeof values[field_name] == "undefined" ) {
                                values[field_name] = new Array();
                            }
                            if ($(this).val() != '') {
                                values[field_name].push($(this).val());
                            }
                        } else {
                            values[field_name] = $(this).val();
                        }
                    });

                    $preview_field = $('<div id="preview_field"><span class="info">' + values.title + '</span></div>');
                    // Create the preview field and show it on the draw board
                    $('#draw')
                        .addClass('active')
                        .hover(function(e) {
                            // Add the preview field
                            $(this).before($preview_field);
                            var parentOffset = $(this).offset(); 
                            $field_coord = getUpdatedCoordinates( e, parentOffset, $(this), $preview_field );
                            // When field is added
                            $(this).bind('click.field_events', function() {
                                $add_fields_postbox.show();
                                // add new field to the submit form 
                                formdata = $('<input type="hidden" name="data[]" />');
                                formdata.val('{"x":' + $field_coord.x + ', "y":' + $field_coord.y + '}');
                                    $new_field = getCanvasFieldHtml($field_coord, values.title );
                                    $new_field.attr('style', $preview_field.attr('style'));
                                    $preview_field.remove();

                                    $(this)
                                .unbind('.field_events')
                                .unbind('mouseenter mouseleave')
                                .removeClass('active')
                                .before($new_field);

                            new_field_object = new Object();
                            new_field_object.type = field_type;
                            new_field_object.coord = new Object;
                            new_field_object.coord.left = $field_coord.x;
                            new_field_object.coord.top = $field_coord.y;
                            new_field_object.values = values;

                            if (typeof fields_def_meta != "object") {
                                fields_def_meta = new Array();
                            }
                            field_id = 0;
                            if (typeof fields_def_meta[current_page-1] != "undefined") {
                                fields_def_meta[current_page-1].push(new_field_object);
                                field_id = fields_def_meta[current_page-1].length - 1;
                            } else {
                                fields_def_meta[current_page-1] = new Array(new_field_object);
                            }
                            $new_field.attr('id', parseInt(current_page-1) + '_' + field_id);

                            updateFieldDefInput(fields_def_meta);
                            updateDefinedFieldsPostbox(fields_def_meta, current_page);

                            markAsModified();
                            is_unsaved = true;
                            //@TODO add new_field_object to val field_def meta
                            //input
                            //- create onsubmit functionality to update 
                                });

                                $(this).bind( 'mousemove.field_events', function(e){
                                    $field_coord = getUpdatedCoordinates( e, parentOffset, $(this), $preview_field );
                                    style_attr = 'left: ' + $field_coord.x + 'px;';
                                    style_attr += 'top: ' + $field_coord.y + 'px;';
                                    $preview_field.attr('style', style_attr);
                                });
                            }, function() {
                                $(this).unbind('.field_events');
                                // $preview_field.remove();
                            });

                            $(this).closest('.postbox').remove();


                            e.preventDefault();
                        }
            }); // End field form submit
            });           
        });
});

/** ----------------  
 *  Helper functions
 *  ---------------- */
jQuery.validator.addMethod('required_group', function(val, el) {
    var $module = $(el).closest('form');
    length = $module.find('.required_group:filled').length;
    // Usefull if at least 2 fields needed
    /* length = length <= 1 ? 0 : length; */ 
    return length;
}, 'Please fill out at least one option.');


getUpdatedCoordinates = function( e, parentOffset, $canvas, $element ) {
    posx = e.pageX - parentOffset.left - $element.outerWidth() / 2;
    posy = e.pageY - parentOffset.top - $element.outerHeight() / 2;
    posy = posy < 0 ? 0 : posy;
    posx = posx < 0 ? 0 : posx;

    if ( posx + $element.outerWidth() >= $canvas.outerWidth()) {
        posx = $canvas.outerWidth() - $element.outerWidth();
    }
    if (posy + $element.outerHeight() >= $canvas.outerHeight()) {
        posy = $canvas.outerHeight() - $element.outerHeight();
    }
    return { x: posx, y: posy };
}

/**
 *  Splits the pdf url  
 */
getSplitPdfUrl = function(url) {
    split = url.split('pages/');
    start_url = split[0] + 'pages/';
    split = split[1].split('.pdf');
    current_page = split[0];
    end_url = split[1];
    next_page = (parseInt(current_page) + 1 > parseInt(docinfo.page_count)) ? 1 : parseInt(current_page) + 1;
    prev_page = (parseInt(current_page) - 1 <= 0) ? parseInt(docinfo.page_count) : parseInt(current_page) - 1;
    return { 
        start: start_url,
            end: end_url,
            current_page: current_page,
            next_page: next_page,
            prev_page: prev_page
    }
}

/**
 *  Changes the document page
 *
 *  @param int page_number The new page number
 *  @param 
 */
goToPage = function(page_number, $pdf_element) {
    split_url = getSplitPdfUrl($pdf_element.attr('data'));
    if (typeof page_number == 'string') {
        switch (page_number) {
            case 'next':
                page_number = split_url.next_page;
                break;
            case 'prev':
                page_number = split_url.prev_page;
                break;
            default: 
                if (typeof page_number !== "number")
                    return;
        }
    }
    $new_pdf_obj_element = $pdf_element.clone();
    // Form the next page url and set it
    new_page_url = split_url.start + page_number + '.pdf' + split_url.end;
    $new_pdf_obj_element.attr('data', new_page_url);
    // Remove old page, add the new one
    $pdf_object_element.remove();
    $('#pdf, #draw')
        .css('width', page_sizes_meta[page_number-1].w)
        .css('height', page_sizes_meta[page_number-1].h);
    $('#pdf')
        .append($new_pdf_obj_element);
    $pdf_object_element = $new_pdf_obj_element;

    updateToPage(page_number);

    return page_number;
}
/**
 *  Changes and updates all that needs to be 
 *  updated when the page is changed
 *
 *  @param page_number - 1 indexed
 */
updateToPage = function(page_number) {

    refreshPageCanvas(page_number);

    $('.current_page').each(function(){
        $(this).html(page_number);
    });

    // Add current class to the correct sidebar page
    $('#pl_defined_fields')
        .find('.page.current')
        .removeClass('current');
    $('#pl_defined_fields')
        .find('.page.' + (parseInt(page_number)-1))
        .addClass('current');
}

/**
 *  Update the field definition input
 */
updateFieldDefInput = function(fields_def_meta) {
    def_meta_string = JSON.stringify(fields_def_meta);
    $('#fields_def_meta').val(def_meta_string);
}

/**
 *  Update canvas fields on a certain page
 *
 *  @param page_number - 1 indexed
 */
refreshPageCanvas = function(page_number){
    // Hide old fields
    $('#canvas .field').remove();
    // Update fields on canvas
    page_fields = fields_def_meta[page_number-1];
    if (typeof page_fields !== "undefined") {
        $.each(page_fields, function(field_id, page_field){
            $old_field = getCanvasFieldHtml(page_field.coord, page_field.values.title, page_number, field_id);
            $('#draw').before($old_field);
        });
    }
}

/**
 *  Updates the "Defined fields" postbox
 */
updateAllDefinedFieldsPostbox = function(fields_def_meta) {
    // Populate the defined fields postbox
    $defined_fields_postbox = $('#pl_defined_fields');
    $.each(fields_def_meta, function(page_index, page){
        updateDefinedFieldsPostbox(fields_def_meta, parseInt(page_index)+1);
    });
}

/**
 *  Updates a certain page from the "Defined fields" postbox
 */
updateDefinedFieldsPostbox = function(fields_def_meta, page_id) {
    // Populate the defined fields postbox
    $defined_fields_postbox = $('#pl_defined_fields');
    page_index = page_id - 1;
    $page_div = $defined_fields_postbox.find('.page.' + page_index);
    // If the page has no fields
    if ((typeof fields_def_meta[page_index] == "undefined") || (fields_def_meta[page_index].length == 0)) {
        $page_div.remove();
        if ($defined_fields_postbox.find('.page').length == 0) {
            $defined_fields_postbox.css('display', 'none');
        }
    } else {
        $defined_fields_postbox.fadeIn();
        // The page field table
        $field_list = $('<table><tbody></tbody></table>');
        $.each(fields_def_meta[page_index], function(field_index, field){
            $list_field = getFieldRowHtml(page_index, field_index, field.values.title, field.type);
            $field_list.append($list_field);
        });
        // If that page already has fields
        if ($page_div.length == 0) {
            // The page title
            $page_title = $('<h4>Page ' + (parseInt(page_index) + 1) + '</h4>');
            // The page container div
            $page = $('<div class="page current ' + page_index + '"></div>');
            $page.append($page_title, $field_list);
            $defined_fields_postbox
                .find('.inside')
                .append($page);
        } else {
            $page_div
                .find('table')
                .remove();
            $page_div
                .append($field_list);
        }
    }
}

/**
 *  Helper function that outputs a table row 
 *  for the "Defined fields" postbox table
 */
getFieldRowHtml = function(page_index, field_index, field_title, field_type) {
   return $('<tr class="field" id="' + page_index + '_' + field_index + '"><td class="info"><span>' + field_title + ' (type: ' + field_type + ')</span></td><td class="remove"><a href="#">Delete</a></td></tr>');
}
/**
 *  Helper function that outputs a table row 
 *  for the "Defined fields" postbox table
 */
getCanvasFieldHtml = function(field_coord, field_title, page_number, field_id, field_type) {
    if (typeof page_number !== "undefined") {
        return $('<div style="left: ' + field_coord.left + 'px;top: ' + field_coord.top + 'px;" id="' + ( parseInt(page_number) - 1 ) + '_' + field_id + '" class="field"><span class="info">' + field_title + '</span><span class="remove"><a href="#">Delete</a></span></div>');
    } else {
        return $('<div style="left: ' + field_coord.left + 'px;top: ' + field_coord.top + 'px;" class="field"><span class="info">' + field_title + '</span><span class="remove"><a href="#">Delete</a></span></div>');
    }
}

/**
 *  Deletes a field
 */
deleteField = function(page_id, field_id, fields_def_meta, current_page) {
    fields_def_meta[page_id].splice(field_id, 1);
    updateFieldDefInput(fields_def_meta);
    updateDefinedFieldsPostbox(fields_def_meta, (parseInt(page_id)+1));

    if ((parseInt(page_id) + 1) == current_page) {
        $('#canvas').find('#' + page_id + '_' + field_id).remove();
        refreshPageCanvas(parseInt(page_id) + 1);
    }

}

/**
 *  Marks the status as Unsaved
 */
markAsModified = function() {
    $('#pl_doc_overview').find('.status')
            .attr('class', 'unsaved')
            .html('Unsaved');

   $(window).bind('beforeunload', function() {
            return 'The document has not been saved. You will lose all changes.';
    }); 
}

processFieldId = function(id_attr) {
    split_ids = id_attr.split('_');

    result_object = new Object();
    result_object.page = split_ids[0];
    result_object.field = split_ids[1];

    return result_object;
}
