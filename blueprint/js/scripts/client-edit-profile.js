jQuery(document).ready(function($) {

	$('#edit_profile_button').live('click', function(event) {
		event.preventDefault();

		$( "#edit_profile" ).dialog();		
		$("#edit_profile").dialog({		
			autoOpen: false,
			draggable: false,
			modal: true,
			width: 700,
			title: "<h2>Update Contact Info</h2>",
			buttons: {
					"Close": function() {
						$( this ).dialog( "close" );
					},
					"Update Contact Info": {
						id: "update_contact_info",
						text: "Update Contact Info",
						click: function() {
							 update_contact_info();
						},
					}
			}
		});
	});

	function update_contact_info () {
		data = {};
		$.each($('#edit_profile_form').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
		data['action'] = 'pls_update_client_profile';

		console.log(data);

		$.post(info.ajaxurl, data, function(data, textStatus, xhr) {
		  if (data && data.id) {
		  	$('#edit_profile_message').html('You successfully updated your profile.');
		  	setTimeout(function () {
		  		window.location.href=window.location.href;
		  		console.log('asdfads');
		  	}, 700);
		  	
		  };
		}, 'json');


		
		// console.log('update_contact_info');
	}

});