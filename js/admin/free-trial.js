jQuery(document).ready(function($) {
	$( "#premium_wizard" ).dialog({
		autoOpen: false,
		draggable: false,
		modal: true,
		width: 700,
	});
});

function prompt_free_trial (title, success_callback, cancel_callback) {
	$( "#premium_wizard" ).dialog({title : '<h3>' + title + '</h3>'} );
	$( "#premium_wizard" ).dialog({close : cancel_callback} );
	$( "#premium_wizard" ).dialog( "open" );

	$('#premium_wizard_start').live('click', function(event) {
		event.preventDefault();
		var content_div = $(this).parents('.ui-dialog-content');
		$(content_div).html('<div class="ajax_message">Confirming Name and Phone have been entered....</div>');
		$.post(ajaxurl, {action: 'whoami'}, function(data, textStatus, xhr) {
			console.log(data);
			if (data) {
				var collect_info_form = '';
				var needed_fields = '';
				if (data.user.first_name == '') {
					needed_fields += '<div><label for="first_name">First Name</label><input id="first_name" type="text" name="user[first_name]" value=""></div>';
				};
				if (data.user.last_name == '') {
					needed_fields += '<div><label for="last_name">Last Name</label><input id="last_name" type="text" name="user[last_name]" value=""></div>';
				};
				if (data.user.phone == '') {
					needed_fields += '<div><label for="user[phone]">Phone Name</label><input id="phone" type="text" name="user[phone]" value=""></div>';
				};
				if (needed_fields != '') {
					collect_info_form = '<div><h4>Almost done!</h4><p>We need to collect the follow information for MLS compliance.</p>' + needed_fields + '</div>';
					$(content_div).html(collect_info_form, cancel_callback);	
				} else {
					$(content_div).html('<div class="ajax_message">Name and Phone confirmed. Starting 60 day free trial...</div>');
					start_free_trial(success_callback);	
				};
			} else {
				$(content_div).html('<div><p>There was an error. That sucks because we\'re excited to work together. Give us a ring at (800) 728-8391 or shoot us an email at <a mailto="support@placester.com">support@placester.com</a> and we\'ll get you set up.</p></div>');
			};
		}, 'json');
	});
}

function start_free_trial (success_callback, cancel_callback) {
	$.post(ajaxurl, {action: "start_subscription_trial"}, function(data, textStatus, xhr) {
		console.log(data)
		if (data && data.plan && data.plan.pro == 'pro') {
			$( "#premium_wizard" ).dialog( "close" );
			success_callback;
		} else {
			$( "#premium_wizard" ).dialog( "close" );
			cancel_callback;
		};
	});
	

}