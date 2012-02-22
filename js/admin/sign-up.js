$(document).ready(function($) {

	$( "#dialog" ).dialog({
		autoOpen: true,
		draggable: false,
		modal: true,
		title: false,
		width: 700,
		buttons: {
				"Use Existing Placester API Key": function() {
					
				},
				"Close Setup Wizard": function() {
					$( this ).dialog( "close" );
				},
				"Next: Select MLS Integration": function() {
					$( this ).dialog( "close" );
				}
			}
	});

	$('.wrapper').click(function() {
		$( "#dialog" ).dialog( "open" );
		return false;
	});

});