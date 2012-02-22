$(document).ready(function($) {

	$('#existing_placester').bind('click', function() {
		$( "#existing_placester_dialog" ).dialog( "open" );
		return false;
	});

	$( "#existing_placester_dialog" ).dialog({
		autoOpen: false,
		draggable: false,
		modal: true,
		title: false,
		width: 700,
		title: "Use Existing Placester Account",
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

	function check_api_key (api_key) {
		$('#api_key_success').hide();
		$('#api_key_validation').hide();
		if (api_key.length == 40) {
			var data = {action : "check_placester_api_key",api_key: api_key};
			$('#api_key_success').html('Checking....').show();
			$.ajax({
				url: ajaxurl, //wordpress thing
				type: "POST",
				data: data,
				dataType: "json",
				success: function (response) {
					$('#api_key_success').hide();
					if (response && response['message']) {
						if (response['result']) {
							$('#api_key_success').html(response['message']).show();			
							setTimeout(function () {
								window.location.href=window.location.href;
							}, 1000);
						} else {
							$('#api_key_validation').html(response['message']).show();			
						};
					};		
				}
			});
		} else {
			$('#api_key_validation').html('Invalid Placester API Entered. Not 40 Characters long.').show();
		}
	}
});