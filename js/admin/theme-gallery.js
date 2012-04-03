$(document).ready(function($) {
	$('#install_theme').live('click', function (event) {
		event.preventDefault();
		var link = 'https://placester.com/wordpress/themes/download/' + $(this).attr('href');
		$.ajax({
		    url: link,
		    type: 'GET',
		    dataType: 'jsonp',
		    success: function(data) {
	        	console.log(data);
				if (data && data.type == 'register') {
					$( "#install_theme_overlay" ).dialog({title: "<h2>Register</h2>"});
					$( "#install_theme_overlay" ).dialog('open');
					
					$( "#install_theme_overlay" ).dialog({
						autoOpen: false,
						draggable: false,
						modal: true,
						width: 700,
						buttons: {
							"Close": function() {
								$( this ).dialog( "close" );
							},
							"Switch API Keys": {
							
							}
						}
					});

					// $.post(ajaxurl, {action: 'install_theme', theme_url: data.url }, function(data, textStatus, xhr) {
					//   console.log(data);
					//   $('#theme_install_message').html(data);
					// });



					
				} else {
					window.location.href = "/wp-admin/admin.php?page=placester_theme_gallery&theme_url=" + data.url;
				};
		    }
		});
	});


});
