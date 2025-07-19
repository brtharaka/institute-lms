<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_announcements'])) {
    $module_id = mysqli_real_escape_string($conn, $_SESSION['module_id']);
    $announcement_details = mysqli_real_escape_string($conn, $_POST['announcement_details']);

    // Insert or update the announcement in the database
    $query = "INSERT INTO announcements (module_id, announcement_details) VALUES ('$module_id', '$announcement_details') ON DUPLICATE KEY UPDATE announcement_details='$announcement_details'";

    if (mysqli_query($conn, $query)) {
        echo "Announcement updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>

