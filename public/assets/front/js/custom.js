/*---------------------------------------------

Author:         IziPresta
Author Email:   contact@izipresta.com
description: Our custom pambe JS

----------------------------------------------*/
 
(function ($) {
    "use strict";

    $(document).on('submit', '#login-form', function(event) {
    
        event.preventDefault(); 

        var form = $(this);
        var url = form.attr('action');
        var email = $('#email').val();
        var password = $('#password').val();
        var data = JSON.stringify({_username: email, _password: password, _remember_me: false});

        $.ajax({
            type: "POST",
            url: url,
            data: data, 
            contentType: "application/json",
            dataType: "json",
            success: function(data)
            {
                console.log(data); 
            }
        });
    });

})(jQuery);