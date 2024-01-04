<?php
/*
Plugin Name: Custom Wp_List Table
Description: Wp_list table
Version: 1.0
Author: Sarvesh
*/


add_action('admin_menu', 'custom_wp_list_table_menu');

function custom_wp_list_table_menu()
{
    add_menu_page(
        'Custom WP_List Table',
        'Custom WP_List Table',
        'manage_options',
        'custom_wp_list_table',
        'custom_wp_list_table_page'
    );
}

function custom_wp_list_table_page(){
    $action = isset($_GET['action']) ? trim($_GET['action']) : '';

    if ($action === 'delete') {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'crud';
            $wpdb->delete($table_name, array('id' => $id));
            wp_redirect(admin_url('admin.php?page=custom_wp_list_table'));
            exit();
        }
    }
    
    ob_start();
    include_once plugin_dir_path(__FILE__).'views/table-list.php';
    $template = ob_get_contents();
    ob_end_clean();

    echo $template;
}

?>