jQuery(document).ready(function() { 
   jQuery('.alert.pl_ui .close_btn').live('click', function(e){
       e.preventDefault();
       jQuery(this).closest('.alert.pl_ui').fadeOut('fast');
   });
});

// (function($) {
    // $.pl_function = function(var) {

    // };
// })(jQuery);


// jQuery(document).ready(function() { 
   // $close_button = $('<a href="#" class="close_btn">Close</a>');

   // $('.alert.pl_ui.close').live("create", function(e){
       // $(this).append($close_button);
   // });
// });
