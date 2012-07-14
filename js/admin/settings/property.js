$(document).ready(function($) {
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
});