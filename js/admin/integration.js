jQuery(document).ready(function($) {

	$('#pls_search_form').live('submit', function(event) {
		event.preventDefault();
		$('#rets_form_message').removeClass('red');
		$('#rets_form_message').html('Checking RETS information...');
		$('#message.error').remove();

		var form_values = {action: 'create_integration'};
		$.each($(this).serializeArray(), function(i, field) {
    		form_values[field.name] = field.value;
        });

		$.post(ajaxurl, form_values, function(data, textStatus, xhr) {
		  	console.log(data);
		  	 var form = $('#pls_search_form');
			if (data && data.result) {
				$('#rets_form_message').html(data.message);
				setTimeout(function () {
					window.location.href = window.location.href;
				}, 700);
			} else {
				var item_messages = [];
				for(var key in data['validations']) {
					var item = data['validations'][key];
					if (typeof item == 'object') {
						for( var k in item) {
							if (typeof item[k] == 'string') {
								var message = '<li class="red">' + data['human_names'][key] + ' ' + item[k] + '</li>';
							} else {
								var message = '<li class="red">' + data['human_names'][k] + ' ' + item[k].join(',') + '</li>';
							}
							$("#" + key + '-' + k).prepend(message);
							item_messages.push(message);
						}
					} else {
						var message = '<li class="red">'+item[key].join(',') + '</li>';
						$("#" + key).prepend(message);
						item_messages.push(message);
					}
				} 
				$(form).prepend('<div id="message" class="error"><h3>'+ data['message'] + '</h3><ul>' + item_messages.join(' ') + '</ul></div>');
				$('#rets_form_message').html('');
			};

		}, 'json');
		
	});
});