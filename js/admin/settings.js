 $(document).ready(function($) {

	$('#existing_placester').bind('click', function() {
		$( "#existing_placester_dialog" ).dialog( "open" );
		return false;
	});

	$('#new_email').bind('click', function () {
		$.post(ajaxurl, {action: 'new_api_key_view'}, function(data, textStatus, xhr) {
  			//optional stuff to do after success
  			$("#existing_placester_dialog" ).dialog("open");
  			$('#existing_placester_dialog').html(data);
  			$('#existing_placester_dialog').dialog('option', 'title', '	<h2>Welcome to the RE Website Builder Set Up Wizard</h2>');
			$('#existing_placester_dialog').dialog('option', 'buttons', {
				1:{
					text: "Cancel", 
					click: function (){
						$(this).dialog("close")
					}
				},
				2:{
					text:"Confirm Email",
					click: function () {
						new_sign_up();
					}
				}
			});
			return false;
		});
		
	});

	$( "#existing_placester_dialog" ).dialog({
		autoOpen: false,
		draggable: false,
		modal: true,
		title: false,
		width: 700,
		title: "<h2>Use an existing Placester account</h2>",
		buttons: {
				"Close": function() {
					$( this ).dialog( "close" );
				},
				"Switch API Keys": {
					id: "switch_placester_api_key",
					text: "Switch API Keys",
					click: function() {
						 check_api_key($("#existing_placester_modal_api_key").val());
					},
				}
			}
	});


	$('#existing_placester_modal_api_key').live('change', function () {
		var val = $(this).val();
		if (val.length != 40) {
			$('#api_key_validation').html('Invalid Placester API Entered. Not 40 Characters long.').show();
		} else {
			$('#api_key_validation').hide();
		};
	});

	$('#clear_cache').live('click', function(event) {
		$('#cache_message').html('Clearing Cache...');
		$.post(ajaxurl, {action: 'user_empty_cache'}, function(data, textStatus, xhr) {
			if (data && data['result']) {
				$('#cache_message').html(data['message'])
			};
			setTimeout(function () {
				$('#cache_message').html('');
				$('#num_cached_items').html('0');
			}, 1500);
		},'json');
		
	});

	$('#delete_pages').live('click', function () {
		$('#regenerate_message').html('Deleting...');
		$.post(ajaxurl, {action:'ajax_delete_all'}, function(data, textStatus, xhr) {
			if (data && data['result']) {
				$('#regenerate_message').html(data['message'])
			};
			setTimeout(function () {
				$('#regenerate_message').html('');
				$('#num_placester_pages').html('0');
			}, 1500);
		},'json');
		
	});


	update_global_form_status();
	$('#selected_global_filter').bind('change', function () {
		update_global_form_status();
	});
	function update_global_form_status() {
		var active_filter = $('#selected_global_filter').val();
		//property_type filters have their . switched out to - because 
		//of jquerys issues finding "."
		active_filter = active_filter.replace(".","-");
		console.log(active_filter);
		$('#gloal_filter_form').find('.currently_active_filter').removeClass('currently_active_filter').hide();
		$('#gloal_filter_form').find('section.' + active_filter).show().addClass('currently_active_filter');
	}

	$('#add-single-filter').bind('click', function () {
		$('#global_filter_message').html('');
		$('#global_filter_message').removeClass('red');
		$('#global_filter_message').removeClass('green');
		var key = $('#selected_global_filter').val();
		var value = $('.currently_active_filter select, .currently_active_filter input').val();
		var current_form_values = {};
		$.each($('#active_filters').serializeArray(), function(i, field) {
			current_form_values[field.name] = field.value;
        });
        if (current_form_values[key] ) {
        	$('#global_filter_message').html('That filter is already active. Select another one.');
        	$('#global_filter_message').addClass('red');
        } else {
	        if (value != 'false') {
				$('form#active_filters').append('<section><p>'+key.replace('_', ' ')+': '+value.replace('_', ' ')+'<span id="remove_filter">(Remove)</span></p><input type="hidden" name="'+key+'" value="'+value+'"></section>');	
				current_form_values[key] = value;
				current_form_values['action'] = 'user_save_global_filters';
				$.post(ajaxurl, current_form_values, function(data, textStatus, xhr) {
					console.log(data);
					if (data && data.result) {
						$('#global_filter_message').removeClass();
						$('#global_filter_message').html(data.message);
						$('#global_filter_message').addClass('green');
					} else {
						$('#global_filter_message').removeClass();
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
	});

 	$('#remove_filter').live('click', function () {
 		console.log($(this).closest('#active_filters section'));
 		$(this).closest('#active_filters section').remove();
 		var current_form_values = {};
		$.each($('#active_filters').serializeArray(), function(i, field) {
			current_form_values[field.name] = field.value;
        });
		current_form_values['action'] = 'user_save_global_filters';
		$.post(ajaxurl, current_form_values, function(data, textStatus, xhr) {
			console.log(data);
			if (data && data.result) {
				$('#global_filter_message').removeClass('red');
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('green');
			} else {
				$('#global_filter_message').removeClass('green');
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('red');					
			};
		}, 'json');
 	});


 	$('#remove_global_filters').live('click', function () {
 		$.post(ajaxurl, {action: 'user_remove_all_global_filters'}, function(data, textStatus, xhr) {
 			console.log(data);
 			if (data && data.result) {
				$('#global_filter_message_remove').removeClass('red');
				$('#global_filter_message_remove').html(data.message);
				$('#global_filter_message_remove').addClass('green');
 			} else {
				$('#global_filter_message_remove').removeClass('green');
				$('#global_filter_message_remove').html(data.message);
				$('#global_filter_message_remove').addClass('red');	
 			};
 		});
 		
 	});

	// $('#save_global_filters').live('click', function(event) {
	// 	event.preventDefault();
	// 	data = {
	// 		action: 'user_save_global_filters'
	// 	};
	// 	$.each($('#pls_search_form:visible').serializeArray(), function(i, field) {
	// 		data[field.name] = field.value;
 //        });
	// 	$.post(ajaxurl, data, function(data, textStatus, xhr) {
	// 	  	console.log(data);
	// 		if (data.result) {
	// 			$('#global_filter_message').removeClass();
	// 			$('#global_filter_message').html(data.message);
	// 			$('#global_filter_message').addClass('green');
	// 			setTimeout(function () {
	// 				window.location.href = window.location.href;
	// 			}, 700);
	// 		} else {
	// 			$('#global_filter_message').removeClass();
	// 			$('#global_filter_message').html(data.message);
	// 			$('#global_filter_message').addClass('red');
	// 		};
	// 	}, 'json');
	// });

	$('#error_logging_click').live('click', function() {
		var request = {
			report_errors: $(this).is(':checked'),
			action: 'ajax_log_errors'
		}
		$.post(ajaxurl, request, function(data, textStatus, xhr) {
		  if (data && data.result) {
			$('#error_logging_message').html(data.message);
			$('#error_logging_message').removeClass('red');
			$('#error_logging_message').addClass('green');
		  } else {
		  	$('#error_logging_message').html(data.message);
		  	$('#error_logging_message').removeClass('green');
		  	$('#error_logging_message').addClass('red');
		  };
		}, 'json');
	});


	$('#block_address').live('click', function() {
		var request = {
			use_block_address: $(this).is(':checked'),
			action: 'ajax_block_address'
		}
		$.post(ajaxurl, request, function(data, textStatus, xhr) {
		  if (data && data.result) {
			$('#block_address_messages').html(data.message);
			$('#block_address_messages').removeClass();
			$('#block_address_messages').addClass('green');
		  } else {
		  	$('#block_address_messages').html(data.message);
		  	$('#block_address_messages').removeClass();
		  	$('#block_address_messages').addClass('red');
		  };
		}, 'json');
	});


	$('#set_default_country').live('click', function(event) {
	  	event.preventDefault();
	  	var country = $('#set_default_country_select').val();
	  	$.post(ajaxurl, {action: 'ajax_default_address', country: country}, function(data) {
	  		$('#default_country_message').removeClass('red');
	  	  	if (data && data.result) {
	  	  		$('#default_country_message').addClass('green').html(data.message);
	  	  	} else if (!data.result) {
	  	  		$('#default_country_message').addClass('red').html(data.message);
			}
	  	}, 'json');
	});
});