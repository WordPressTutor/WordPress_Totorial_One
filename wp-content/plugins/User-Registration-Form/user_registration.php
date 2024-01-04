<?php

function user_registration_form()
{

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $user = get_userdata($user_id);
    $is_editing = $user_id && $user;

    if (isset($_POST['submit'])) {
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_text_field($_POST['address']);
        $password = sanitize_text_field($_POST['password']);

        $result = wp_insert_user(array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
        ));

        update_user_meta($result, 'phone', $phone);
        update_user_meta($result, 'address', $address);

        if (isset($_POST['update'])) {
            $username = sanitize_text_field($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $address = sanitize_text_field($_POST['address']);

            $updated_user = wp_update_user(array(
                'ID'         => $user_id,
                'user_login' => $username,
                'user_email' => $email
            ));

            if (!is_wp_error($updated_user)) {
                update_user_meta($user_id, 'phone', $phone);
                update_user_meta($user_id, 'address', $address);
                echo "<script>alert('User details updated successfully')</script>";
            } else {
                echo "<script>alert('Error updating user')</script>";
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <title>User Registration Form</title>
    </head>

    <body>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>User Registration</h4>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="addres">Address:</label>
                                    <input type="text" class="form-control" id="address" name="address" value="" req>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <?php if ($is_editing) : ?>
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <input type="submit" name="update" class="btn btn-success" value="Update">
                                <?php else : ?>
                                    <input type="submit" name="submit" class="btn btn-primary" value="Register">
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php
}
add_action('wp_get_user_data', 'get_user_data');
add_action('wp_user_registration_form', 'user_registration_form');

?>