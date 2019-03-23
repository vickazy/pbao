(function ($) {
    "use strict"; // Start of use strict

    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"]), #menu-main a[href*="#"]:not([href="#"]), ul.footer-links a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: (target.offset().top - 56)
                }, 1000, "easeInOutExpo");

                $('.navbar-collapse').collapse('hide');

                return false;
            }
        }
    });

    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
        target: '',
        offset: 57
    });

    // Collapse Navbar
    var navbarCollapse = function () {
        if ($("#mainNav").offset().top > 100) {
            $("#mainNav").addClass("navbar-shrink");
        } else {
            $("#mainNav").removeClass("navbar-shrink");
        }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);

    // Scroll reveal calls
    window.sr = ScrollReveal();

    sr.reveal('.sr-icon-1', {
        delay: 200,
        scale: 0
    });
    sr.reveal('.sr-icon-2', {
        delay: 400,
        scale: 0
    });
    sr.reveal('.sr-icon-3', {
        delay: 600,
        scale: 0
    });
    sr.reveal('.sr-icon-4', {
        delay: 800,
        scale: 0
    });
    sr.reveal('.sr-button', {
        delay: 200,
        distance: '15px',
        origin: 'bottom',
        scale: 0.8
    });
    sr.reveal('.sr-contact-1', {
        delay: 200,
        scale: 0
    });
    sr.reveal('.sr-contact-2', {
        delay: 400,
        scale: 0
    });

    $(document).ready(function () {

        var dateToday = new Date();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            endDate: dateToday,
            autoclose: true,
            language: 'id'
            // startDate: '-3d'
        });

        // Registration form submitted
        $('#frmModalReg').validate({
            rules: {
                upass: {
                    minlength: 6
                },
                upass2: {
                    minlength: 6,
                    equalTo: "#upass"
                }
            },
            focusInvalid: true,
            submitHandler: function (form, e) {
                e.preventDefault();
                var result_elm = $(form).find('.frm-result'),
                    buttons = $(form).find('button'),
                    button_submit = $(form).find('button.btn-primary'),
                    button_submit_ori_text = button_submit.html(),
                    loading_html = '<span class="spinner-border spinner-border-sm"></span>';

                result_elm.html('');
                buttons.prop('disabled', true);
                button_submit.html(loading_html);

                $.ajax({
                    url: obj.ajax_url,
                    type: 'POST',
                    data: {
                        'action': 'ureg',
                        'data': $(form).serializeArray()
                    },
                    dataType: 'json',
                    success: function (data) {
                        buttons.prop('disabled', false);
                        button_submit.html(button_submit_ori_text);
                        var result_class = data.is_error ? 'alert-warning' : 'alert-success';
                        result_elm.html('<div class="alert ' + result_class + '">' + data.message + "</div>");
                        if (!data.is_error) {
                            $(form).trigger('reset');
                        }
                    }
                })
            }
        });

        // Modal registration being closed
        $('.modal').on('hidden.bs.modal', function () {
            var form = $(this).find('form');
            if (form) {
                form.find('.frm-result').html('');
                form.validate().resetForm();
                form.trigger('reset');
            }
        })
    })
})(jQuery); // End of use strict
