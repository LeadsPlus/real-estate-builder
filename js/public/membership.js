jQuery(document).ready(function($) {

    $('#pl_lead_register_form').submit(function(e) {
        e.preventDefault();

        $this = $(this);
        nonce = $(this).find('#register_nonce_field').val();
        username = $(this).find('#user_email').val();
        email = $(this).find('#user_email').val();
        password = $(this).find('#user_password').val();
        confirm = $(this).find('#user_confirm').val();
        name = $(this).find('#user_fname').val();
        phone = $(this).find('#user_phone').val();

        data = {
            action: 'pl_register_lead',
            username: username,
            email: email,
            nonce: nonce,
            password: password,
            confirm: confirm,
            name: name,
            phone: phone,
        };

        $.post(info.ajaxurl, data, function(response) {
            if (response) {             
                $('#form_message_box').html(response);
                $('#form_message_box').fadeIn('fast');
            } else {
                $('#form_message_box').html('You have been successfully signed up. This page will refresh momentarily.');
                $('#form_message_box').fadeIn('fast');
                setTimeout(function () {
                    window.location.href = window.location.href;
                }, 700);
                return true;
            }
        });

    });

    $('#pl_login_form').bind('submit',function(e) {
        $this = $(this);
        username = $(this).find('#user_login').val();
        password = $(this).find('#user_pass').val();
    
        return login_user(username, password);
    });
    
    $(".pl_register_lead_link").fancybox({
        'hideOnContentClick': false,
        'scrolling' : true
    });

    $(".pl_login_link").fancybox({
        'hideOnContentClick': false,
        'scrolling' : true
    });

    $(document).ajaxStop(function() { 
      favorites_link_signup();
    });

    favorites_link_signup();

    function favorites_link_signup () {
      $("#pl_register_lead_favorites_link").fancybox({
          'hideOnContentClick': false,
          'scrolling' : true
      });
    }
    
    function login_user (username, password) {


        data = {
            action: 'pl_login',
            username: username,
            password: password,
        };

        var success = false;
        $.ajax({
            url: info.ajaxurl, 
            data: data, 
            async: false,
            type: "POST",
            success: function(response) {
                // If request successfull empty the form
                if ( $(response).hasClass('success') ) {
                    success = true;
                } else {
                    $(".pl_login_alert").fadeOut('fast');
                    $this.before(response);
                }
            }
        });

        if ( ! success ) 
            return false;
        else 
            return true;

    }
        
});
