<?php

require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class My_Custom_List extends WP_List_Table
{

    // Prepare items
    public function prepare_items()
    {

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : '';
        $order = isset($_GET['order']) ? trim($_GET['order']) : '';
        $search_term = isset($_POST['s']) ? trim($_POST['s']) : '';

        $datas =  $this->wp_list_table_data($orderby, $order, $search_term);

        $per_page = 3;
        $current_page = $this->get_pagenum();
        $total_items = count($datas);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ));
    
        $this->items = array_slice($datas, (($current_page - 1) * $per_page), $per_page);

        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    public function wp_list_table_data($orderby = '', $order = '', $search_term = '')
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'crud';

        if (!empty($search_term)) {
            $query = "SELECT * FROM $table_name WHERE fname LIKE '%$search_term%'";
        } else {
            if ($orderby === 'fname' && $order === 'asc') {
                $query = "SELECT * FROM $table_name ORDER BY fname ASC";
            } elseif ($orderby === 'id' && $order === 'desc') {
                $query = "SELECT * FROM $table_name ORDER BY id DESC";
            } else {
                $query = "SELECT * FROM $table_name ORDER BY id ASC";
            }
        }
        $results = $wpdb->get_results($query, ARRAY_A);

        return $results;
    }


    public function get_hidden_columns()
    {

        return array('');
    }

    public function get_sortable_columns()
    {

        return array(
            'fname' => array('fname', true),
            //'email' => array('email', false),
        );
    }

    // Get columns
    public function get_columns()
    {
        $columns = array(
            'id'         => 'ID',
            'fname'      => 'First Name',
            'lname'      => 'Last Name',
            'company'    => 'Company',
            'address'    => 'Address',
            'email'      => 'Email',
            'phone'      => 'Phone',
            'additional' => 'Additional Information',
        );

        return $columns;
    }

    // Column default
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'fname':
            case 'lname':
            case 'company':
            case 'address':
            case 'email':
            case 'phone':
            case 'additional':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function column_fname($item){
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&fname=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['fname']),
            'delete' => sprintf('<a href="?page=%s&action=%s&fname=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['fname']),
        );

        // $actions = array(
        //     'edit' => "<a href='?page=".$_GET['page']."action=custom_wp_list_table'></a>"
        // );

        return sprintf('%s %s', $item['fname'], $this->row_actions($actions));
    }
}

function show_list_table()
{
    $mylisttable = new My_Custom_List();
    // Calling prepare items from class
    $mylisttable->prepare_items();
    echo "<h3>This is List</h3>";
    echo "<form method='post' name='search_data' action='" . $_SERVER['PHP_SELF'] . "?page=custom_wp_list_table'>";
    $mylisttable->search_box('search', 'search_id');
    $mylisttable->display();
}

show_list_table();
