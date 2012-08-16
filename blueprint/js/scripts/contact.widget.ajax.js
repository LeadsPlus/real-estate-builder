/**
 * Placester's errorTooltip plugin
 */
(function($){

    $.fn.errorTooltip = function(tooltip_name, config_or_action) {
        
        var config = {
            text: 'An error has occurred',
            type: 'error',
            css_class: 'error_tooltip'
        };
        
        var selector = '.' + tooltip_name,
            target;

        if(config_or_action === "remove") {
            jQuery(selector).remove();
        }
        else if(config_or_action === "fade") {
            jQuery(selector).fadeOut('slow', function() {
                jQuery(this).remove();
            });
        }
        else if(config_or_action instanceof Object) {
            jQuery.extend(config, config_or_action);
            if(jQuery(selector).size() === 0) {
                jQuery('body').append('<div class="placester_tooltip ' +  tooltip_name + ' ' + config_or_action.css_class + '">' + config_or_action.text + '<div class="tooltip_arrow_border"></div><div class="tooltip_arrow"></div></div>');
                target = jQuery(selector);
                target.css({display: 'block', position: 'absolute'});
                target.css({
                    top: this.offset().top - 15,
                    left: this.offset().left,
                    opacity: 0.6
                }).animate({top: this.offset().top - (target.height() + 20), opacity: 1}, 500);
            }
        }

        return this;
    };

})(jQuery);

jQuery(document).ready(function($) {

   var widget = jQuery('.side-ctnr.placester_contact');

    var valid_email = function (email) {
        // Email regex courtesy http://fightingforalostcause.net/misc/2006/compare-email-regex.php
        return (email !== "" && email.match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i));
    };

    // Validate the name field
    jQuery("input[name=name]", widget).bind('blur.pl', function() {
        if(jQuery(this).val() !== "") {
            jQuery(this).errorTooltip('name_error', 'remove');
        }
    });

    // Validate the email field
    jQuery("input[name=email]", widget).bind('blur.pl', function() {
        if(valid_email(jQuery(this).val())) {
            jQuery(this).errorTooltip('email_error', 'remove');
        }
    });

	jQuery('.side-ctnr.placester_contact form').submit(function(e) {

        var error_found = false;

        $this = jQuery(this);
        e.preventDefault();

        // widget.find('.placester_loading').show();

		var str = jQuery(this).serialize();
        
        var clear_form = function(form) {
            form.find('input[type="text"], input[type="email"], textarea').val('');
        };

        // Clear out existing tooltips
        jQuery("input[name=name]", this).errorTooltip('name_error', 'remove');
        jQuery("input[name=email]", this).errorTooltip('email_error', 'remove');
        $this.find('input[type=submit]').errorTooltip('submit_success', 'remove');
        $this.find('input[type=submit]').errorTooltip('submit_error', 'remove');

        // Validate the name field
        if(jQuery('input[name=name]', this).val() === "") {
            jQuery("input[name=name]", this).errorTooltip('name_error', {text: "Please enter your name"});
            error_found = true;
        }

        // Validate the email address field
        var email_value = jQuery("input[name=email]", this).val();
        if(!valid_email(email_value)) {
            jQuery("input[name=email]", this).errorTooltip('email_error', {text: "Please enter a valid email address"});
            error_found = true;
        }

        if(!error_found) {
            jQuery(this).find('input[type=submit').errorTooltip('submit_error', 'remove');

            jQuery.ajax({
                type: 'POST',
                url: info.ajaxurl,
                data: 'action=placester_contact&' + str,
                success: function(msg) {
                    if(msg === 'sent') {
                        // widget.find('.placester_loading').fadeOut('fast');
                        $this.find('input[type=submit]').errorTooltip('submit_success', {text: "Thank you for the email. We\'ll get back to you shortly."});
                        setTimeout(function() {
                            $this.errorTooltip('submit_success', 'fade');
                        }, 10000);
                        clear_form($this);
                    }
                    else {
                        $this.find('input[type=submit]').errorTooltip('submit_error', {text: "An error occurred. Please try again."});
                        setTimeout(function() {
                            $this.errorTooltip('submit_error', 'fade');
                        }, 10000);
                        clear_form($this);

                    //     widget.find('.placester_loading').hide();
                    //     widget.find('.msg')
                    //     .html(msg)
                    //     .removeClass('success')
                    //     .addClass('error')
                    //     .fadeIn('slow');
                    }
                }
            });
        }
	});
});
