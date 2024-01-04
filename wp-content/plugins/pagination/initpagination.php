<?php
/*
Plugin Name: Pagination
Descriptions: This plugin is used to perform Pagination
version: 1.0
Author: Sarvesh
*/

include plugin_dir_path(__FILE__) . 'pagination.php';

function pagination_shortcode(){
    ob_start();
    listing_post();
    return ob_get_clean();
}

add_shortcode('pagination','pagination_shortcode');


