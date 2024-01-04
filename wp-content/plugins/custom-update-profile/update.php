<?php
/*
Plugin Name: Custom Update User Profile 
Description: Update user profile with custom fields
Version: 1.0
Author: Sarvesh
*/

function my_update_script()
{
    wp_enqueue_script('my_update_script', plugins_url('update.js', __FILE__), array('jquery'), '1.0', true);

    wp_localize_script('my_update_script', 'custom_update_script_params', array(
        'ajax_url'   => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'my_update_script');

add_action('wp_ajax_my_update_action', 'my_update_action');
add_action('wp_ajax_nopriv_my_update_action', 'my_update_action');

function my_update_action()
{
    if (!is_user_logged_in()) {
        $response = array('message' => 'You must be logged in firstly');
    } else {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        if (isset($_POST['update'])) {
            // ... (existing code)

            if (isset($_FILES['profile'])) {
                $file = $_FILES['profile'];

             
                error_log('File Info: ' . print_r($file, true));

                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('profile', $user_id);
                error_log('Attachment ID: ' . $attachment_id);

                if (is_wp_error($attachment_id)) {
                    $response = array('message' => 'Error uploading file: ' . $attachment_id->get_error_message());
                } else {
                    update_user_meta($user_id, 'profile', $attachment_id);
                }
            }
        }
        $response = array('message' => 'Update successful');
    }
    wp_send_json($response);
}


function update_profile_shortcode()
{
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $username = $current_user->user_login;
    $name = $current_user->user_nicename;
    $email = $current_user->user_email;

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Profile</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <style>
            #img-preview {
                max-width: 200px;
                max-height: 200px;
            }
    </style>
    <body>
        <div class="container mt-4">
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                    <label for="profile">Profile Picture:</label>
                    <input type="file" class="form-control" id="profile" name="profile" onchange="previewImage()">
                    <img id="img-preview" src="" alt="Profile Picture Preview">
                </div>
                <div class="form-group">
                    <label for="username">User Name:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo esc_attr(get_user_meta($user_id, 'phone', true)); ?>">
                </div>
                <div class="form-group">
                    <label for="add">Address:</label>
                    <input type="text" class="form-control" id="add" name="add" value="<?php echo esc_attr(get_user_meta($user_id, 'add', true)); ?>">
                </div>
                <button type="submit" class="btn btn-primary" id="update" name="update">Update</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
add_shortcode('update_profile', 'update_profile_shortcode');
?>