(function ($) {
    "use strict";

    $('.bkel').click(function (e) {
        e.preventDefault();
        var angkatan_id = $(this).data('id');
        $(this).prop('disabled', true).html('Loading...');
        $.ajax({
            url: obj.ajax_url,
            type: 'POST',
            data: {
                'action': 'create_groups',
                'angkatan_id': angkatan_id
            },
            dataType: 'json',
            success: function (data) {
                if (!data.is_error) {
                    location.reload();
                }
            }
        })
    })
})(jQuery);