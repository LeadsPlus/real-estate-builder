/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */

jQuery(document).ready(function($) {
	
	// Fade out the save message
	$('.fade').delay(1000).fadeOut(1000);
	
	// Color Picker
    $('.colorSelector').each(function(){
        var $this = this; //cache a copy of the this variable for use inside nested function
        var initialColor = $($this).next('input').attr('value');
        $(this).ColorPicker({
            color: initialColor,
            onShow: function (colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
            onChange: function (hsb, hex, rgb) {
                          $($this).children('div').css('backgroundColor', '#' + hex);
                          $($this).next('input').attr('value','#' + hex);
                      }
        });
    }); //end color picker
	
	// Switches option sections
	$('.group').hide();
	$('.group:first').fadeIn();
	$('.group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).hasClass('last')) {
					$(this).removeClass('hidden');
						return false;
					}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});
	
	$('#of-nav li:first').addClass('current');
	$('#of-nav li a').click(function(evt) {
		$('#of-nav li').removeClass('current');
		$(this).parent().addClass('current');
		var clicked_group = $(this).attr('href');
		$('.group').hide();
		$(clicked_group).fadeIn();
		evt.preventDefault();
	}); 	 		
});	
