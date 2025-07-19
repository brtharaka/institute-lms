<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    $delete_query = "DELETE FROM users WHERE user_id = $user_id";

    if (mysqli_query($conn, $delete_query)) {
        echo "User deleted successfully!";
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>

