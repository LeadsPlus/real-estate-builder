$(document).ready(function($) {

	//property selectbox
	set_property_type();
	
	$('select#compound_type').bind('change', function () {
		set_property_type();
	});

	function set_property_type() {
		$('#property_type-sublet').hide().find('input, select').prop('disabled', true);
		$('#property_type-res_sale').hide().find('input, select').prop('disabled', true);
		$('#property_type-vac_rental').hide().find('input, select').prop('disabled', true);
		$('#property_type-res_rental').hide().find('input, select').prop('disabled', true);
		$('#property_type-comm_rental').hide().find('input, select').prop('disabled', true);
		$('#property_type-comm_sale').hide().find('input, select').prop('disabled', true);
		$('#property_type-' + $('select#compound_type').val() ).show().find('input, select').prop('disabled', false);

		$('div#res_sale_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('div#res_rental_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('div#vac_rental_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('div#sublet_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('div#comm_rental_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('div#comm_sale_details_admin_ui').hide().find('input, select').prop('disabled', true);
		$('#' + $('select#compound_type').val() + '_details_admin_ui' ).show().find('input, select').prop('disabled', false);
	}

	// Initialize the jQuery File Upload widget:
    $('#add_listing_form').fileupload({
        formData: {action: 'add_temp_image'},
        sequentialUploads: true,
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('#fileupload-holder-message').append('<li class="image_container"><div><img width="100px" height="100px" src="'+file.url+'" ><a id="remove_image">Remove</a><input id="hidden_images" type="hidden" name="images['+$('#hidden_images').length+'][filename]" value="'+file.name+'"></div><li>');
            });
        }
    });

	$('#remove_image').live('click', function (event) {
		event.preventDefault();
		$(this).closest('.image_container').remove();
	});	

	// duplicates the custom attribute form.
	$('button#custom_data').live('click', function (event) {
		event.preventDefault();
		var html = $(this).closest('section').clone();
		$(this).html('Remove').attr('id', 'custom_data_remove');
		$(this).closest(".form_group").append(html);
	});	

	$('button#custom_data_remove').live('click', function (event) {
		event.preventDefault();
		$(this).closest('section').remove();
	});	

	$("input#metadata-avail_on_picker").datepicker({
        showOtherMonths: true,
        numberOfMonths: 2,
        selectOtherMonths: true
	});
	    
	//create listing
	$('#add_listing_publish').live('click', function(event) {
        event.preventDefault();
       	//hide all previous validation issues
       	$('.red').remove();
       	//set default values required for the form to work. 
        var form_values = {}
        form_values['request_url'] = $(this).attr('url');
        form_values['action'] = 'add_listing';
        //get each of the form values, set key/values in array based off name attribute
        $.each($('#add_listing_form:visible').serializeArray(), function(i, field) {
        	console.log(field.name + ' / ' + field.value + ' / ');
    		form_values[field.name] = field.value;
        });
        console.log('asdf');
        //set context of the form.
       var form = $('#add_listing_form');
       //ajax request for creating the listing. 
        $.ajax({
			url: ajaxurl, //wordpress thing
			type: "POST",
			data: form_values,
			dataType: "json",
			success: function (response) {
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