jQuery(document).ready(function() {
    
    jQuery('#update').click(function(e) {
        e.preventDefault();
    });

    jQuery('#update').on('click', function() {
        var updateData = {
            username: jQuery('#username').val(),
            name: jQuery('#name').val(),
            email: jQuery('#email').val(),
            phone: jQuery('#phone').val(),
            add: jQuery('#add').val(),
        };
        console.log(updateData);
        jQuery.ajax({
            type: "post",
            url: custom_update_script_params.ajax_url,
            data: {
                action: 'my_update_action',
                updateData: updateData,
            },
            dataType: "json",
            success: function(response) {
                alert(response.message);
                jQuery('#username').val('');
                jQuery('#name').val('');
                jQuery('#email').val('');
                jQuery('#phone').val('');
                jQuery('#add').val('');
            },
            error: function(error) {
                console.error(error);
            }
        });
    })   
});

function previewImage() {
    var input = document.getElementById('profile');
    var preview = document.getElementById('img-preview');

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
