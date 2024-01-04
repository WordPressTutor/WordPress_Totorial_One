jQuery(document).ready(function($) {
    $('select[name="status"]').on('change', function() {
        var userId = $(this).closest('form').find('input[name="user_id"]').val();
        var status = $(this).val();

        $.ajax({
            url: customUserStatusAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'update_user_status_ajax',
                user_id: userId,
                status: status,
            },
            success: function(updatedStatus) {
                $('select[name="status"]').val(updatedStatus);
            },
            error: function(error) {
               
            }
        });
    });
});
