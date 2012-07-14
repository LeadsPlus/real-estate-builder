$(document).ready(function($) {
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
