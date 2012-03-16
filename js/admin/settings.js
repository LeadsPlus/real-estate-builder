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


	$('#save_global_filters').live('click', function(event) {
		event.preventDefault();
		data = {
			action: 'user_save_global_filters'
		};
		$.each($('#pls_search_form:visible').serializeArray(), function(i, field) {
			data[field.name] = field.value;
        });
		$.post(ajaxurl, data, function(data, textStatus, xhr) {
		  	console.log(data);
			if (data.result) {
				$('#global_filter_message').removeClass();
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('green');
				setTimeout(function () {
					window.location.href = window.location.href;
				}, 700);
			} else {
				$('#global_filter_message').removeClass();
				$('#global_filter_message').html(data.message);
				$('#global_filter_message').addClass('red');
			};
		}, 'json');
	});
});