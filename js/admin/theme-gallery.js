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
	        	$( "#install_theme_overlay" ).dialog({
					autoOpen: false,
					draggable: false,
					modal: true,
					width: 700,
				});
				if (data && data.type == 'subscribe') {
					$( "#install_theme_overlay" ).dialog({title: "<h3>Start your 30 day free trial and begin the download</h3>"});
					$( "#install_theme_overlay" ).dialog('open');
				} else {
					window.location.href = "/wp-admin/admin.php?page=placester_theme_gallery&theme_url=" + data.url;
				};
		    }
		});
	});


});
