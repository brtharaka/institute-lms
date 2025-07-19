<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_exam_results'])) {
    $module_id = mysqli_real_escape_string($conn, $_SESSION['module_id']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);

    // Insert or update the exam result in the database
    $query = "INSERT INTO exam_results (module_id, student_id, grade) VALUES ('$module_id', '$student_id', '$grade') ON DUPLICATE KEY UPDATE grade='$grade'";

    if (mysqli_query($conn, $query)) {
        echo "Exam result updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
