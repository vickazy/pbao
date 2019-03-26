(function ($) {
    $('#frmLogin').submit(function (e) {
        e.preventDefault();
        var form = $(this),
            form_data = form.serializeArray(),
            btn = form.find('button[type=submit]'),
            btn_text = btn.html();

        btn.prop('disabled', true).html("Loading...");

        $.ajax({
            url: obj.ajax_url,
            type: 'POST',
            data: {
                'action': 'ulogin',
                'data': form_data
            },
            dataType: 'json',
            success: function (data) {
                if (data.is_error) {
                    btn.prop('disabled', false).html(btn_text);
                    swal('Maaf', data.message, 'error');
                } else {
                    location.href = data.callback;
                }
            }
        });
    })
})(jQuery);