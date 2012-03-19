function parse_validation (response) {
	$ = jQuery; //we're in no conflict land. 
	if (response && response['validations']) {
		var item_messages = [];
		for(var key in response['validations']) {
			var item = response['validations'][key];
			if (typeof item == 'object') {
				for( var k in item) {
					if (typeof item[k] == 'string') {
						var message = '<span class="red">' + response['human_names'][key] + ' ' + item[k] + '</span>';
					} else {
						var message = '<span class="red">' + response['human_names'][k] + ' ' + item[k].join(',') + '</span>';
					}
					item_messages.push(message);
				}
			} else {
				var message = '<span class="red">'+item[key].join(',') + '</span>';
				item_messages.push(message);
			}
		}
		return item_messages;	
	}
}



function check_api_key (api_key) {
	$ = jQuery; //we're in no conflict land. 
	$('#api_key_success').hide();
	$('#api_key_validation').hide();
	if (api_key.length == 40) {
		var data = {action : "set_placester_api_key",api_key: api_key};
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

function new_sign_up() {
	$ = jQuery; //we're in no conflict land. 
	var email = $('input#email').val();
	$('#api_key_success').html('Checking...').show();
	$('#api_key_validation').html('');
	$.post(ajaxurl, {action: 'create_account', email: email}, function(data, textStatus, xhr) {
		if (data) {	
			console.log(data);
			if (data['validations']) {
				mixpanel.track("SignUp: Validation issue on signup");			
				var message = parse_validation(data);
				$('#api_key_success').html('');
				$('#api_key_validation').html(message.join(', ')).show();
			} else if(data['api_key']) {
				$('#api_key_success').html('Success! Setting up plugin.');
				mixpanel.track("SignUp: Successful Signup");			
				$.post(ajaxurl, {action: 'set_placester_api_key', api_key: data['api_key']}, function(response, textStatus, xhr) {
					if (response['result']) {
						$('#api_key_success').html(response['message']).show();
						mixpanel.track("SignUp: API key installed");			
						setTimeout(function () {
							window.location.href=window.location.href;
						}, 1000);
					};
				},'json');
			};
		};
	},'json');
}

