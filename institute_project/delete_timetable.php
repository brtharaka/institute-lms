<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $timetable_id = $_POST['timetable_id'];

    // Delete the timetable entry from the database
    $query = "DELETE FROM timetable WHERE timetable_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $timetable_id);
    if ($stmt->execute()) {
        header("Location: admin.php"); // Redirect back to the admin dashboard
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
