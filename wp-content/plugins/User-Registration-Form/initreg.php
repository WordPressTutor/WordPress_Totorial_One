<?php

/*
Plugin Name: User Registration Form
Description: This plugin is used to perform User Registration Form
version: 1.0
Author: Sarvesh
*/

include plugin_dir_path( __FILE__ ) . 'user_registration.php';
include plugin_dir_path( __FILE__ ) . 'get_userdata.php';

function user_resgitration_shortcode(){
    ob_start();
    user_registration_form();
    get_user_data();
    return ob_get_clean();
}

add_shortcode('user_resgitration_shortcode','user_resgitration_shortcode');
?>