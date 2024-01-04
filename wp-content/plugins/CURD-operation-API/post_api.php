<?php
/*
Plugin Name: crate Post Type Using Post API
Descriptions: This plugin is used to perform CRUD operation using API
version: 1.0
Author: Sarvesh
*/


function custom_post_type_end_point(){

    register_rest_route('custom/v1','/create_post/',array(
        'methods' => 'POST',
        'callback' => 'create_post_callback',
        'permission_callback' => '__return_true',
    ));
    

}

add_action('rest_api_init','custom_post_type_end_point');


function create_post_callback($request){
    $data = $request->get_params();
    $title = sanitize_text_field($data['title']);
    $content = sanitize_text_field($data['content']);
    $author = sanitize_text_field($data['author']);


    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $content,
        'post_author' => $author,
        'post_status' => 'publish',
        'post_type'    => 'custompost',

    ));



    if(!is_wp_error($post_id)){
        return new WP_REST_Response(array('message' => 'Post created successfully'),200);
    }else{
        return new WP_REST_Response(array('error' => 'Error creating post'),500);
    }
}
?>