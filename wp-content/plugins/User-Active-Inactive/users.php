<?php
/**
 * Plugin Name: Custom User Status
 * Description: Add a custom dropdown in user table to set user status without AJAX.
 * Version: 1.0
 * Author: Your Name
 */

 // Enqueue JavaScript file for AJAX functionality
function enqueue_custom_user_status_script() {
    wp_enqueue_script('custom-user-status-script', plugin_dir_url(__FILE__) . 'userstatus.js', array('jquery'), '1.0.0', true);
    wp_localize_script('custom-user-status-script', 'customUserStatusAjax', array('ajaxUrl' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'enqueue_custom_user_status_script');

// Add custom column in user table
function custom_user_table_column($columns) {
    $columns['user_status'] = 'Status';
    return $columns;
}
add_filter('manage_users_columns', 'custom_user_table_column');

// Populate custom column with dropdown
function custom_user_table_column_data($value, $column_name, $user_id) {
    if ('user_status' == $column_name) {
        $user_status = get_userdata($user_id)->user_status;
        $options = [
            '0' => 'Active',
            '1' => 'Inactive'
        ];
        $select = '<form method="post" action="' . admin_url('admin-post.php') . '">';
        $select .= '<input type="hidden" name="action" value="update_user_status">';
        $select .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
        $select .= '<select name="status">';
        foreach ($options as $key => $option) {
            $selected = selected($key, $user_status, false);
            $select .= "<option value='$key' $selected>$option</option>";
        }
        $select .= '</select>';
        //$select .= '<input type="submit" value="Update">';
        $select .= '</form>';
        return $select;
    }
    return $value;
}
add_filter('manage_users_custom_column', 'custom_user_table_column_data', 10, 3);


function handle_user_status_update_ajax() {
    if (isset($_POST['user_id']) && isset($_POST['status'])) {
        $user_id = intval($_POST['user_id']);
        $status = intval($_POST['status']);
        global $wpdb;
        $wpdb->update(
            $wpdb->users,
            array('user_status' => $status),
            array('ID' => $user_id)
        );
        echo 'success'; 
    }
    wp_die(); 
}
add_action('wp_ajax_update_user_status_ajax', 'handle_user_status_update_ajax');
add_action('wp_ajax_nopriv_update_user_status_ajax', 'handle_user_status_update_ajax'); 

?>
