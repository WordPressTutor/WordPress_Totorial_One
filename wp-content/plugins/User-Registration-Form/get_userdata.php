<?php


function get_user_data()
{
    $users = get_users();

    $user_data = array();

    foreach ($users as $user) {
        $address = get_user_meta($user->ID, 'address', true);

        $user_data[] = array(
            'username' => $user->user_login,
            'email'    => $user->user_email,
            'address'  => $address,
            'role'     => $user->roles[0]
        );
    }
?>
    <br><br>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Address</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($user_data as $data) { ?>
            <tr>
                <td><?php echo esc_html($data['username']); ?></td>
                <td><?php echo esc_html($data['email']); ?></td>
                <td><?php echo esc_html($data['address']); ?></td>
                <td><?php echo esc_html($data['role']); ?></td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=update_user_data&user_id=' . $user->ID); ?>">Edit</a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php
}


add_action('wp_get_user_data', 'get_user_data');

?>