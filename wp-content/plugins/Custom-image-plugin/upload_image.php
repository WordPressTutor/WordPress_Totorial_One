<?php
/*
Plugin Name: Custom Image Plugin
Description: Custom image plugin Upload
Version: 1.0
Author: Sarvesh
*/



$image_url = '';
function custom_image_upload_and_delete()
{
    add_menu_page(
        'Custom Image Plugin',
        'Custom Image Plugin',
        'manage_options',
        'custom-image-plugin',
        'custom_image_plugin_function',
        'dashicons-format-image',
        20
    );
}
add_action('admin_menu', 'custom_image_upload_and_delete');

function custom_image_plugin_function()

{
    global $image_url;
    $image_url = get_option('custom_image_url', '');
    
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <title>Upload Image</title>
    </head>

    <body>
        <div class="container mt-4">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                $result = handle_image_upload();
                if ($result && isset($result['url'])) {
                    $image_url = $result['url'];
                    echo '<script>alert("Image uploaded successfully.");</script>';
                } else {
                    echo '<script>alert("Invalid image.");</script>';
                }
            }
            if (isset($_POST['delete_image'])) {
                custom_handle_image_deletion();
                
            }
            ?>
            <form id="form" method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Choose File:</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                <button type="submit" class="btn btn-primary" id="submit" name="submit">Submit</button>
           
            <h3>Image Preview</h3>
            <?php if ($image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" id="uploaded-image">
            <?php else : ?>
                <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'img.png'); ?>" id="uploaded-image">
                <input type="hidden" name="image_url" value="<?php echo esc_attr($image_url); ?>">
            <?php endif; ?>
             <button type="submit" class="btn btn-danger ml-3" name="delete_image">Delete Image</button>
            </form>


        </div>
    </body>

    </html>
    <script>
        function uploadpic(pic) {
            document.getElementById("image").setAttribute('src', pic);
            document.getElementById("image_url").value = pic;
        }
    </script>
<?php
}

function handle_image_upload()
{
    $uploaded = wp_handle_upload($_FILES['file'], array('test_form' => false));

    if (isset($uploaded['file'])) {
        $image_url = $uploaded['url'];

        update_option('custom_image_url', $image_url);

        return array(
            'url' => $image_url,
        );
    } else {
        return false;
    }
}

function custom_handle_image_deletion() {

    global $image_url;
  
    if (isset($_POST['delete_image'])) {
  
      $image_url = esc_url_raw($_POST['image_url'] ?? '');

      wp_delete_attachment($image_url, true);
  
      delete_option('custom_image_url');
      
      echo '<script>alert("Image deleted successfully")</script>';
    }else{
        echo '<script>alert("Image not deleted")</script>';
    }
    
  }
  
?>