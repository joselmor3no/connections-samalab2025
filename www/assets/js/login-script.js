/* JS File 
  ===================
    Item Name: Enter - Login and Register HTML5 Template
    Author: Ashish Maraviya
    Version: 1.0
    Copyright 2020 - 2021
===================*/
        
(function($){ 
"use strict";
        // Signup model
        var loginmodal = document.getElementById('login-modal');
        var signupmodal = document.getElementById('signup-modal');
        var forgotmodal = document.getElementById('forgot-modal');
        window.onclick = function(event) {
            if (event.target == loginmodal) {
                $("#login-modal").fadeOut();
            }
            else if (event.target == signupmodal) {
                $("#signup-modal").fadeOut();
            }
            else if (event.target == forgotmodal) {
                $("#forgot-modal").fadeOut();
            }
        }

        // Login model
        $(".login-btn").on('click', function(){
          $("#signup-modal").hide();
          $("#forgot-modal").hide();
          $("#login-modal").show();
        });
        $(".login-btn-cls").on('click', function(){
          $("#login-modal").fadeOut();
        });

        // Signup model
        $(".signup-btn").on('click', function(){
            $("#login-modal").hide();
            $("#signup-modal").show();
        });
        $(".signup-btn-cls").on('click', function(){
          $("#signup-modal").fadeOut();
        });

        // Forgot model
        $(".forgot-btn").on('click', function(){
            $("#login-modal").hide();
            $("#forgot-modal").show();
        });
        $(".forgot-btn-cls").on('click', function(){
          $("#forgot-modal").fadeOut();
        });

        // Password input
        $(".toggle-password").on('click', function() {

          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $($(this).attr("data-toggle"));
          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
})(jQuery);
  