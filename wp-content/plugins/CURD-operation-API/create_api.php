<?php
/*
Plugin Name: CRUD operation using API
Descriptions: This plugin is used to perform CRUD operation using API
version: 1.0
Author: Sarvesh
*/

add_action('wp_enqueue_scripts', 'script_files');

function custom_user_api_endpoints() {
    // Create user
    register_rest_route('custom/v1', '/create_user/', array(
        'methods' => 'POST',
        'callback' => 'create_user_callback',
        'permission_callback' => '__return_true',
    ));

    // Update user
    register_rest_route('custom/v1', '/update_user/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'update_user_callback',
        'permission_callback' => '__return_true',
    ));

    // Delete user
    register_rest_route('custom/v1', '/delete_user/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'delete_user_callback',
        'permission_callback' => '__return_true',
    ));
    // Get user
    register_rest_route('custom/v1', '/get_user/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_user_callback',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'custom_user_api_endpoints');

function create_user_callback($data) {
    
    $username = sanitize_text_field($data['username']);
    $email = sanitize_email($data['email']);
    $password = sanitize_text_field($data['password']);

    // Create a new user
    $user_id = wp_create_user($username, $password,$email);

    if (!is_wp_error($user_id)) {
        return new WP_REST_Response(array('message' => 'User created successfully'), 200);
    } else {
        return new WP_REST_Response(array('error' => 'Error creating user'), 500);
    }
}

function update_user_callback($request) {
    $data = $request->get_params();
    $user_id = absint($request->get_param('id'));

    $user_data = array(
        'ID' => $user_id,
        'user_email' => sanitize_email($data['email']),
        'user_pass' => sanitize_text_field($data['user_pass']),
        'user_nicename' => sanitize_text_field($data['user_nicename']),
    );

    $updated = wp_update_user($user_data);

    if (!is_wp_error($updated)) {
        return new WP_REST_Response(array('message' => 'User updated successfully'), 200);
    } else {
        return new WP_REST_Response(array('error' => 'User not found or could not be updated'), 500);
    }
}
function delete_user_callback($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $delete_id = $data['id'];
    $delete_user = $wpdb->delete($table_name, array('ID' => $delete_id), array('%d'));

    if (is_wp_error($delete_user)) {
        return new WP_Error('registration_error', $delete_user->get_error_message(), array('status' => 400));
    }

    return array('status' => 'success', 'user_id' => $delete_user);
}


function get_user_callback($request) {
    $user_id = absint($request->get_param('id'));

    $user = get_user_by('ID', $user_id);

    if ($user) {
        return new WP_REST_Response(array('user' => $user->to_array()), 200);
    } else {
        return new WP_REST_Response(array('error' => 'User not found'), 500);
    }
}
