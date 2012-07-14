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

	$('#google_places_api_button').live('click', function (event) {
		event.preventDefault();
		var request = {};
		request.places_key = $('#google_places_api').val();
		request.action = 'update_google_places'
		$.post(ajaxurl, request, function(data, textStatus, xhr) {
		  	$('#default_googe_places_message').removeClass('red');
			if (data && data.result) {
				$('#default_googe_places_message').addClass('green').html(data.message);
				setTimeout(function () {
					window.location.href = window.location.href;
				}, 700);
			} else {
				$('#default_googe_places_message').addClass('red').html(data.message);
				setTimeout(function () {
					$('#default_googe_places_message').html('');
				}, 700);
			};
		}, 'json');
	});
});