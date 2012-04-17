jQuery(document).ready(function($) {

	$('#pls_search_form').live('submit', function(event) {
		event.preventDefault();
		$('#rets_form_message').removeClass('red');
		$('#message.error').remove();

		$('#rets_form_message').html('Checking Account Status...');

		$.post(ajaxurl, {action: 'subscriptions'}, function(data, textStatus, xhr) {
		  console.log(data);
		  if (data && data.plan && data.plan == 'pro') {
		  	check_mls_credentials();
		  } else if (data && data.eligible_for_trial) {
		  	console.log('prompt free trial');
		  	prompt_free_trial('Start your 60 day free trial to complete the MLS integration', check_mls_credentials, display_cancel_message);
		  } else {
		  	console.log('not eligible');
		  };
		},'json');		
	});

	function check_mls_credentials () {
		$('#rets_form_message').html('Checking RETS information...');
		
		var form_values = {action: 'create_integration'};
		$.each($('#pls_search_form').serializeArray(), function(i, field) {
    		form_values[field.name] = field.value;
        });

        console.log(form_values);

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
	}

	function display_cancel_message () {
		$('#rets_form_message').html('');
		$('#pls_search_form').prepend('<div id="message" class="error"><h3>Sorry, this feature requires a premium subscription</h3><p>However, you can test the MLS integration feature for free by creating a website <a href="https://placester.com" target="_blank">placester.com</a></p></div>');
	}

});