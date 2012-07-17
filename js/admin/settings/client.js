$(document).ready(function($) {

	$('#save').click(function(event) {
		event.preventDefault();
		var form = $(this).parents('form');
		var form_values = {};
		 $.each($(form).serializeArray(), function(i, field) {
    		form_values[field.name] = field.value;
        });
		form_values['send_client_message_text'] = $('#send_client_message_text').val();
		form_values['action'] = 'set_client_settings'
		$.post(ajaxurl, form_values, function(data, textStatus, xhr) {
			if (data && data.result) {
				alert(data.message);
			};
		},'json');
	});
});