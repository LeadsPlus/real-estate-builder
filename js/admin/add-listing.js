$(document).ready(function($) {

	//property selectbox
	$('select#compound_type').bind('change', function () {
		jQuery('#property_type-sublet').hide()
		jQuery('#property_type-res_sale').hide()
		jQuery('#property_type-vac_rental').hide()
		jQuery('#property_type-res_rental').hide()
		jQuery('#property_type-comm_rental').hide()
		jQuery('#property_type-comm_sale').hide()
		jQuery('#property_type-' + jQuery(this).val() ).show();
	});

	// duplicates the custom attribute form.
	$('button#custom_data').live('click', function (event) {
		event.preventDefault();
		var html = jQuery(this).closest('section').clone();
		jQuery(this).html('Remove').attr('id', 'custom_data_remove');
		jQuery(this).closest(".form_group").append(html);
	});	

	$('button#custom_data_remove').live('click', function (event) {
		event.preventDefault();
		jQuery(this).closest('section').remove();
	});	

	$('#fileupload').fileupload({
		dataType: 'json',
		url: ajaxurl,
		formData: {action : "add_temp_image"},
		done: function (e, data) {
		    $.each(data.result, function (index, file) {
		        $('<p/>').text(file.name).appendTo(document.body);
		    });
		}
	});
	    
	//create listing
	$('#add_listing_publish').live('click', function(event) {
        event.preventDefault();
       	
       	$('.red').remove();

        var form_values = {}
        form_values['request_url'] = jQuery(this).attr('url');
        form_values['action'] = 'add_listing';
        $.each($('#add_listing_form').serializeArray(), function(i, field) {
    		form_values[field.name] = field.value;
        });
       var form = $('#add_listing_form');
        $.ajax({
			url: ajaxurl, //wordpress thing
			type: "POST",
			data: form_values,
			dataType: "json",
			success: function (response) {
				console.log(response);
				if (response && response['validations']) {
					var item_messages = [];
					for(var key in response['validations']) {
						var item = response['validations'][key];
						if (typeof item == 'object') {
							for( var k in item) {
								if (typeof item[k] == 'string') {
									var message = '<p class="red">' + response['human_names'][key] + ' ' + item[k] + '</p>';
								} else {
									var message = '<p class="red">' + response['human_names'][k] + ' ' + item[k].join(',') + '</p>';
								}
								$("#" + key + '-' + k).prepend(message);
								item_messages.push(message);
							}
						} else {
							var message = '<p class="red">'+item[key].join(',') + '</p>';
							$("#" + key).prepend(message);
							item_messages.push(message);
						}
					} 
					$(form).prepend('<h3 class="red">'+ response['message'] + '</h3>' + item_messages.join(' '));
				} else if (response && response['id']) {
					console.log("YOU DID IT! HERES THE LINK: https://placester.com/listings/"+ response['id']);
				}
			}
		});
    });
});