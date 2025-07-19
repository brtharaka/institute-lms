<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_assignments'])) {
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $assignment_title = mysqli_real_escape_string($conn, $_POST['assignment_title']);
    $assignment_description = mysqli_real_escape_string($conn, $_POST['assignment_description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);

    // Insert or update the assignment in the database
    $query = "INSERT INTO assignments (module_id, assignment_title, assignment_description, due_date) VALUES ('$module_id', '$assignment_title', '$assignment_description', '$due_date') ON DUPLICATE KEY UPDATE assignment_title='$assignment_title', assignment_description='$assignment_description', due_date='$due_date'";

    if (mysqli_query($conn, $query)) {
        echo "Assignment updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>


