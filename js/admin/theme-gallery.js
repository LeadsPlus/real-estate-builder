$(document).ready(function($) {
	var that = {};
	$('#install_theme').live('click', function (event) {
		event.preventDefault();
		var link = 'https://placester.com/wordpress/themes/download/' + $(this).attr('href');
		that['download_link'] = link;
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
					prompt_free_trial('Start your 60 day free trial and begin the download', premium_theme_success, premium_theme_cancel);
				} else {
					window.location.href = "/wp-admin/admin.php?page=placester_theme_gallery&theme_url=" + encodeURIComponent(data.url);
				};
		    }
		});
	});
	function premium_theme_success () {
		var link = that.download_link;
		$.ajax({
		    url: link,
		    type: 'GET',
		    dataType: 'jsonp',
		    success: function(data) {
	        	$( "#install_theme_overlay" ).dialog({
					autoOpen: false,
					draggable: false,
					modal: true,
					width: 700,
				});
				if (data && data.type == 'subscribe') {
					prompt_free_trial('Start your 60 day free trial and begin the download', premium_theme_success, premium_theme_cancel);
				} else {
					window.location.href = "/wp-admin/admin.php?page=placester_theme_gallery&theme_url=" + encodeURIComponent(data.url);
				};
		    }
		});
	}

	function premium_theme_cancel () {
		$('#theme-gallery-error-message').html('<div id="message" class="error"><h3>Sorry, this feature requires a premium subscription</h3><p>However, you can test the MLS integration feature for free by creating a website <a href="https://placester.com" target="_blank">placester.com</a></p></div>');
		setTimeout(function () {
			$('#theme-gallery-error-message #message').fadeOut('slow');
		}, 1000)
	}



});
