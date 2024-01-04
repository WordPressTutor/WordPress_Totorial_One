<?php
/*
Plugin Name: Add Post
Description: Add a new post to the database
Author: Sarvesh
*/

// Add shortcode to display the form
function custom_post_form_shortcode() {
    ob_start(); ?>

    <form method="post" id="custom-post-form" enctype="multipart/form-data">
        <div>
            <label for="post_title">Post Title:</label>
            <input type="text" id="post_title" name="post_title" required>
        </div>
        <div>
            <label for="post_content">Post Content:</label>
            <textarea id="post_content" name="post_content" rows="5" required></textarea>
        </div>
        <div>
            <label for="post_category">Select Category:</label>
            <?php wp_dropdown_categories(); ?>
        </div>
        <div>
            <label for="featured_image">Upload Featured Image:</label>
            <input type="file" id="featured_image" name="featured_image">
        </div>
        <div>
            <input type="submit" name="submit" value="Submit Post">
        </div>
    </form>

    <div id="response-message"></div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#custom-post-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('action', 'submit_custom_post');

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#response-message').html('<div class="success">' + response.message + '</div>');
                        $('#post_title, #post_content, #featured_image').val('');
                    },
                    error: function(response) {
                        $('#response-message').html('<div class="error">Error occurred. Please try again.</div>');
                    }
                });
            });
        });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('custom_post_form', 'custom_post_form_shortcode');

function submit_custom_post() {
    $post_title = sanitize_text_field($_POST['post_title']);
    $post_content = wp_kses_post($_POST['post_content']);
    $post_category = intval($_POST['post_category']);

    if (isset($_FILES['featured_image'])) {
        $uploaded_file = $_FILES['featured_image'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $file_url = $movefile['url'];
            $file_type = wp_check_filetype(basename($file_url), null);

            $attachment_title = sanitize_file_name(basename($file_url));
            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => $attachment_title,
                'post_content'   => '',
                'post_status'    => 'publish'
            );

            $attach_id = wp_insert_attachment($attachment, $file_url);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_url);
            wp_update_attachment_metadata($attach_id, $attach_data);

            // Get the post ID to set as featured image
            $post_id = wp_insert_post(array(
                'post_title'    => $post_title,
                'post_content'  => $post_content,
                'post_status'   => 'publish',
                'post_type'     => 'post',
                'post_category' => array($post_category)
            ));

            if ($post_id && !is_wp_error($post_id)) {
                wp_set_post_categories($post_id, array($post_category));
        
                if (isset($attach_id)) {
                    set_post_thumbnail($post_id, $attach_id);
                }
            }

            if ($post_id && !is_wp_error($post_id)) {
                set_post_thumbnail($post_id, $attach_id);
                echo json_encode(array('status' => 'success', 'message' => 'Post submitted successfully!'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to submit post. Please try again.'));
            }
        }
    }

    wp_die();
}
add_action('wp_ajax_submit_custom_post', 'submit_custom_post');
add_action('wp_ajax_nopriv_submit_custom_post', 'submit_custom_post');
?>
