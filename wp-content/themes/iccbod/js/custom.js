/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function ($) {
    jQuery(document).ready(function () {

        //Dropdown Menu
        $(".dropdown").hover(
                function () {
                    $('.dropdown-menu', this).stop(true, true).fadeIn("fast");
                    $(this).toggleClass('open');
                    $('b', this).toggleClass("caret caret-up");
                },
                function () {
                    $('.dropdown-menu', this).stop(true, true).fadeOut("fast");
                    $(this).toggleClass('open');
                    $('b', this).toggleClass("caret caret-up");
                });


        //Footer Code
        $(window).resize(function () {
            if ($(window).width() < 767) {
                $('.span2 h2').click(function () {
                    $('.acc-footer').slideToggle();
                });
            } else {
                $('.acc-footer').show();
            }
        });

        //Frontend accordian 
        var Accordion = function (el, multiple) {
            this.el = el || {};
            this.multiple = multiple || false;

            // Variables privadas
            var links = this.el.find('.link');
            // Evento
            links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
        }

        Accordion.prototype.dropdown = function (e) {
            var $el = e.data.el;
            $this = $(this),
            $next = $this.next();
            $next.slideToggle();
            $this.parent().toggleClass('open');
            if (!e.data.multiple) {
                $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
            }
            ;
        }
        var accordion = new Accordion($('#accordion'), false);


        //Login Form Code
        $( "#loginform" ).submit(function( event ) {
            event.preventDefault();
            var is_valid_form = true;
            if ( $( "#username" ).val() == "" ) {
                $( "#error_username" ).text( "Username should not be empty." ).show();
                is_valid_form = false;
            } else {
                $( "#error_username" ).text( "" );
            }
            if ( $( "#user_password" ).val() == "" ) {
                $( "#error_userpassword" ).text( "Password should not be empty." ).show();
                is_valid_form = false;
            } else {
                $( "#error_userpassword" ).text( "" );
            }
            if(is_valid_form == true) {
                var url = "/userlogin"; // the script where you handle the form input.
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#loginform").serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        alert(data); // show response from the php script.
                    }
                });
            }
        });

    });
}(jQuery));