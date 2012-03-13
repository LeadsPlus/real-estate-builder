/*
 * jQuery File Upload Plugin JS Example 6.5
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

jQuery(function ($) {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#add_listing_form').fileupload({
        formData: {action: 'add_temp_image'},
        sequentialUploads: true,
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('#fileupload-holder-message').append('<li class="image_container"><div><img width="100px" height="100px" src="'+file.url+'" ><a id="remove_image">Remove</a><input id="hidden_images" type="hidden" name="images['+$('#hidden_images').length+'][filename]" value="'+file.name+'"></div><li>');
            });
        }
        // url: ajaxurl,
        // uploadTemplateId: false,
        // downloadTemplateId: false,
        // uploadTemplate: function (o) {
        //     var rows = $();
        //     $.each(o.files, function (index, file) {
        //         var row = $('<tr class="template-upload fade">' +
        //             '<td class="preview"><span class="fade"></span></td>' +
        //             '<td class="name"></td>' +
        //             '<td class="size"></td>' +
        //             (file.error ? '<td class="error" colspan="2"></td>' :
        //                     '<td><div class="progress">' +
        //                         '<div class="bar" style="width:0%;"></div></div></td>' +
        //                         '<td class="start"><button>Start</button></td>'
        //             ) + '<td class="cancel"><button>Cancel</button></td></tr>');
        //         row.find('.name').text(file.name);
        //         row.find('.size').text(o.formatFileSize(file.size));
        //         if (file.error) {
        //             row.find('.error').text(
        //                 locale.fileupload.errors[file.error] || file.error
        //             );
        //         }
        //         rows = rows.add(row);
        //     });
        //     return rows;
        // },
        // downloadTemplate: function (o) {
        //     var rows = $();
        //     $.each(o.files, function (index, file) {
        //         var row = $('<tr class="template-download fade">' +
        //             (file.error ? '<td></td><td class="name"></td>' +
        //                 '<td class="size"></td><td class="error" colspan="2"></td>' :
        //                     '<td class="preview"></td>' +
        //                         '<td class="name"><a></a></td>' +
        //                         '<td class="size"></td><td colspan="2"></td>'
        //             ) + '<td class="delete"><button>Delete</button> ' +
        //                 '<input type="checkbox" name="delete" value="1"></td></tr>');
        //         row.find('.size').text(o.formatFileSize(file.size));
        //         if (file.error) {
        //             row.find('.name').text(file.name);
        //             row.find('.error').text(
        //                 locale.fileupload.errors[file.error] || file.error
        //             );
        //         } else {
        //             row.find('.name a').text(file.name);
        //             if (file.thumbnail_url) {
        //                 row.find('.preview').append('<a><img></a>')
        //                     .find('img').prop('src', file.thumbnail_url);
        //                 row.find('a').prop('rel', 'gallery');
        //             }
        //             row.find('a').prop('href', file.url);
        //             row.find('.delete button')
        //                 .attr('data-type', file.delete_type)
        //                 .attr('data-url', file.delete_url);
        //         }
        //         rows = rows.add(row);
        //     });
        //     return rows;
        // }
    });


    // Enable iframe cross-domain access via redirect option:
    // $('#add_listing_form').fileupload(
    //     'option',
    //     'redirect',
    //     window.location.href.replace(
    //         /\/[^\/]*$/,
    //         '/cors/result.html?%s'
    //     )
    // );


    
        // Load existing files:
        // $('#add_listing_form').each(function () {
        //     var that = this;
        //     $.getJSON(this.action, function (result) {
        //         if (result && result.length) {
        //             $(that).fileupload('option', 'done')
        //                 .call(that, null, {result: result});
        //         }
        //     });
        // });
    

});
