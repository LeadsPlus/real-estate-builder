$(document).ready(function($) {
	update_global_form_status();
	$('#selected_global_filter').bind('change', function () {
		update_global_form_status();
	});
	function update_global_form_status() {
		var active_filter = $('#selected_global_filter').val();
		//property_type filters have their . switched out to - because 
		//of jquerys issues finding "."
		active_filter = active_filter.replace(".","-");
		// console.log(active_filter);
		$('#gloal_filter_form').find('.currently_active_filter').removeClass('currently_active_filter').hide();
		$('#gloal_filter_form').find('section.' + active_filter).show().addClass('currently_active_filter');
	}

	$('#add-single-filter').bind('click', function () {
		$('#global_filter_message').html('');
		$('#global_filter_message').removeClass('red');
		$('#global_filter_message').removeClass('green');
		var key = $('.currently_active_filter select, .currently_active_filter input').attr('name');
		var value = $('.currently_active_filter select, .currently_active_filter input').val();
		var current_form_values = {};
		
		$.each($('#active_filters').serializeArray(), function(i, field) {
			if (current_form_values[field.name] && current_form_values[field.name] instanceof Array) {
				current_form_values[field.name].push(field.value);
			} else {
				current_form_values[field.name] = [field.value];	
			};
        });
        if (current_form_values[key] == value ) {
        	$('#global_filter_message').html('That filter is already active. Select another one.');
        	$('#global_filter_message').addClass('red');
        } else {
	        if (value != 'false') {
				$('form#active_filters').append('<span id="active_filter_item"><a href="#"  id="remove_filter"></a><span class="global_dark_label">'+key.replace('_', ' ')+'</span>: '+value.replace('_', ' ')+'<input type="hidden" name="'+key+'" value="'+value+'"></span>');	
				
				console.log(current_form_values[key]);
				if (current_form_values[key] && current_form_values[key] instanceof Array) {
					current_form_values[key].push(value);
				} else {
					current_form_values[key] = [value];
				}

				current_form_values['action'] = 'user_save_global_filters';
				console.log(current_form_values);
				$.post(ajaxurl, current_form_values, function(data, textStatus, xhr) {
					console.log(data);
					if (data && data.result) {
						$('#global_filter_message').removeClass('red');
						$('#global_filter_message').html(data.message);
						$('#global_filter_message').addClass('green');
						are_global_filters_active();
					} else {
						$('#global_filter_message').removeClass('green');
						$('#global_filter_message').html(data.message);
						$('#global_filter_message').addClass('red');					
					};
				}, 'json');
				
			} else {
				console.log($('#global_filter_message'));
				$('#global_filter_message').html('Select a value for your filter.').addClass('red');
				$('#global_filter_message').addClass('red');
			};	
        };
		setTimeout(function () {
			$('#global_filter_message').html('');
		}, 1500)
	});

 	$('#remove_filter').live('click', function (event) {
 		event.preventDefault();
 		$(this).closest('#active_filters span#active_filter_item').remove();
 		var current_form_values = {};
		$.each($('#active_filters').serializeArray(), function(i, field) {
			if (current_form_values[field.name] && current_form_values[field.name] instanceof Array) {
				current_form_values[field.name].push(field.value);
			} else {
				current_form_values[field.name] = [field.value];	
			};
        });
		current_form_values['action'] = 'user_save_global_filters';
		$.post(ajaxurl, current_form_values, function(data, textStatus, xhr) {
			console.log(data);
			if (data && data.result) {
				$('#global_filter_message').removeClass('red');
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('green');
				are_global_filters_active();
			} else {
				$('#global_filter_message').removeClass('green');
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('red');					
			};
			setTimeout(function () {
				$('#global_filter_message').html('');
			}, 1500)
		}, 'json');
 	});


 	$('#remove_global_filters').live('click', function () {
 		$('#global_filter_message_remove').removeClass('red');
 		$('#global_filter_message_remove').addClass('green');
		$('#global_filter_message_remove').html('Working....');
 		$.post(ajaxurl, {action: 'user_remove_all_global_filters'}, function(data, textStatus, xhr) {
 			// console.log(data);
 			if (data && data.result) {
				$('#global_filter_message_remove').html(data.message);
				$('#global_filter_message_remove').addClass('green');
				setTimeout(function () {
					window.location.href = window.location.href;
				}, 750);
 			} else {
 				$('#global_filter_message_remove').removeClass('green');
				$('#global_filter_message_remove').html(data.message);
				$('#global_filter_message_remove').addClass('red');	
 			};
 		}, 'json');
 		setTimeout(function () {
			$('#global_filter_message_remove').html('');
		}, 1500);
 	});

 	function are_global_filters_active () {
 		if ($('#active_filters #active_filter_item').length > 0) {
 			$('#global_filter_wrapper').addClass('filters_active');
 		} else {
 			$('#global_filter_wrapper').removeClass('filters_active');
 			$('#global_filter_active').html('');
 			$('.global_filters.tagchecklist p.label').remove();
 		};
 	}
});