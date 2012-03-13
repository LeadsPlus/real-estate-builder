$(document).ready(function($) {

	//property selectbox
	set_property_type();

	$('select#compound_type').bind('change', function () {
		set_property_type();
	});

	function set_property_type() {
		$('#property_type-sublet').hide()
		$('#property_type-res_sale').hide()
		$('#property_type-vac_rental').hide()
		$('#property_type-res_rental').hide()
		$('#property_type-comm_rental').hide()
		$('#property_type-comm_sale').hide()
		$('#property_type-' + $('select#compound_type').val() ).show();
	}

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
        $.each($('#add_listing_form').serializeArray(), function(i, field) {
    		form_values[field.name] = field.value;
        });
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