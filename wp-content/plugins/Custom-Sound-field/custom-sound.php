<?php
/*
Plugin Name: Custom Sound field
Description: Custom sound field
Version: 1.0
Author: Sarvesh
*/

// Use a unique prefix for function names to avoid conflicts
add_action('admin_menu', 'csf_custom_sound_field_menu');

function csf_custom_sound_field_menu()
{
    add_menu_page(
        'Custom Sound Field',
        'Custom Sound Field',
        'manage_options',
        'csf_custom_sound_field',
        'csf_custom_sound_field_page',
        'dashicons-microphone',
        30
    );

    wp_enqueue_script('sound-script', plugins_url('sound.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('sound-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

function csf_custom_sound_field_page()
{
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = sanitize_text_field($_POST['action']);
            switch ($action) {
                case 'save_sound_data':
                    csf_save_sound_data_ajax();
                    break;
                case 'save_action_data':
                    csf_save_action_data_ajax();
                    break;
                case 'save_pattern_data':
                    csf_save_pattern_data_ajax();
                    break;
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Page Title</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6">
                    <form id="sound-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
                        <div class="form-group">
                            <label for="sound">Sound:</label>
                            <input type="text" class="form-control-file" id="sound-upload" name="sound[]">
                        </div>
                        <button type="submit" id="addsound" class="btn btn-primary">Add Sound</button>
                        <input type="hidden" name="action" value="save_sound_data">
                    </form>

                    <form id="action-form" action="" method="post">
                        <div class="form-group">
                            <label for="action">Action/Display</label>
                            <input type="text" class="form-control-file" id="action" name="display[]">
                        </div>
                        <button type="submit" id="addaction" class="btn btn-primary">Add/Display</button>
                        <input type="hidden" name="action" value="save_action_data">
                    </form>

                    <form id="pattern-form" action="" method="post">
                        <div class="form-group">
                            <label for="pattern">Pattern:</label>
                            <input type="text" class="form-control" id="pattern-input" name="pattern[]">
                        </div>
                        <button type="submit" id="addpattern" class="btn btn-primary">Add Pattern</button>
                        <input type="hidden" name="action" value="save_pattern_data">
                    </form>
                </div>

                <div class="col-md-6">
                    <table id="sound-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sound</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <table id="action-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Display/Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <table id="pattern-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Pattern</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

    </html>

    <?php
}

function csf_save_sound_data_ajax()
{
    if (isset($_POST['sound'])) {
        $sound_data = get_option('custom_sound_field_sound', array());
        $new_sound_item = array('sound' => array_map('sanitize_text_field', $_POST['sound']));
        $sound_data[] = $new_sound_item;
        update_option('custom_sound_field_sound', $sound_data);

        echo "Sound Added Successfully";
    } else {
        echo "Sound not Added";
    }
}

add_action('wp_ajax_csf_save_sound_data', 'csf_save_sound_data_ajax');

function csf_save_action_data_ajax()
{
    if (isset($_POST['display'])) {
        $display_data = get_option('custom_display_field_action', array());
        $new_display_item = array('display' => array_map('sanitize_text_field', $_POST['display']));
        $display_data[] = $new_display_item;
        update_option('custom_display_field_action', $display_data);

        echo "Action/Display Added Successfully";
    } else {
        echo "Action/Display not Added";
    }
}

add_action('wp_ajax_csf_save_action_data', 'csf_save_action_data_ajax');

function csf_save_pattern_data_ajax()
{
    if (isset($_POST['pattern'])) {
        $pattern_data = get_option('custom_pattern_field_pattern', array());
        $new_pattern_item = array('pattern' => array_map('sanitize_text_field', $_POST['pattern']));
        $pattern_data[] = $new_pattern_item;
        update_option('custom_pattern_field_pattern', $pattern_data);

        echo "Pattern Added Successfully";
    } else {
        echo "Pattern not Added";
    }
}

add_action('wp_ajax_csf_save_pattern_data', 'csf_save_pattern_data_ajax');

function csf_get_sound_data_ajax()
{
    $sound_data = get_option('custom_sound_field_sound', array());
    wp_send_json_success($sound_data);
}

add_action('wp_ajax_csf_get_sound_data', 'csf_get_sound_data_ajax');

function csf_get_action_data_ajax()
{
    $action_data = get_option('custom_display_field_action', array());
    wp_send_json_success($action_data);
}

add_action('wp_ajax_csf_get_action_data', 'csf_get_action_data_ajax');

function csf_get_pattern_data_ajax()
{
    $pattern_data = get_option('custom_pattern_field_pattern', array());
    wp_send_json_success($pattern_data);
}

add_action('wp_ajax_csf_get_pattern_data', 'csf_get_pattern_data');

function csf_delete_sound_data_ajax()
{
    if (isset($_POST['index'])) {
        $index = sanitize_text_field($_POST['index']);
        $sound_data = get_option('custom_sound_field_sound', array());
        if (isset($sound_data[$index])) {
            unset($sound_data[$index]);
            update_option('custom_sound_field_sound', array_values($sound_data));
            echo "Item deleted successfully";
        } else {
            echo "Item not found";
        }
    } else {
        echo "Invalid request";
    }

    wp_die();
}

add_action('wp_ajax_csf_delete_sound_data', 'csf_delete_sound_data_ajax');

function csf_delete_action_data_ajax() {
    if (isset($_POST['index'])) {
        $index = sanitize_text_field($_POST['index']);
        $action_data = get_option('custom_display_field_action', array());
        if (isset($action_data[$index])) {
            unset($action_data[$index]);
            update_option('custom_display_field_action', array_values($action_data));
            echo "Item deleted successfully";
        } else {
            echo "Item not found";
        }
    } else {
        echo "Invalid request";
    }

    wp_die();
}

add_action('wp_ajax_csf_delete_action_data', 'csf_delete_action_data_ajax');