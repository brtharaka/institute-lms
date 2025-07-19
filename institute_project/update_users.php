<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : NULL;
    $admin_id = !empty($_POST['admin_id']) ? $_POST['admin_id'] : NULL;
    $lecturer_id = !empty($_POST['lecturer_id']) ? $_POST['lecturer_id'] : NULL;

    // If password is provided, hash it and include it in the update
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET username = '$username', password = '$password', role = '$role', 
                         student_id = '$student_id', admin_id = '$admin_id', lecturer_id = '$lecturer_id' 
                         WHERE user_id = $user_id";
    } else {
        // If password is not provided, don't update the password field
        $update_query = "UPDATE users SET username = '$username', role = '$role', 
                         student_id = '$student_id', admin_id = '$admin_id', lecturer_id = '$lecturer_id' 
                         WHERE user_id = $user_id";
    }

    if (mysqli_query($conn, $update_query)) {
        echo "User updated successfully!";
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>
