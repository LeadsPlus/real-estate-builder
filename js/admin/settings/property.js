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

	var property_datatable = $('#list_of_pages_list').dataTable( {
        "bFilter": false,
        "bProcessing": true,
        // "bServerSide": true,
        "sServerMethod": "POST",
        "sAjaxSource": ajaxurl, 
        "iDisplayLength" : 5,
        "aoColumns" : [
            { sWidth: '50px' },    //name
            { sWidth: '100px' },    //type
            { sWidth: '150px' },     //neighborhood
            { sWidth: '50px' },    //edit
            { sWidth: '200px' },    //remove
            { sWidth: '200px' },    //remove
            { sWidth: '60px' }    //remove
        ], 
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "action", "value" : "get_pages"} );
        }
    });


});