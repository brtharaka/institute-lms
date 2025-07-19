<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_assignment'])) {
    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    $student_id = mysqli_real_escape_string($conn, $_SESSION['student_id']); // Assuming student_id is stored in session

    $target_dir = "uploads/assignment/";
    $target_file = $target_dir . basename($_FILES["assignment_file"]["name"]);

    // Check if the file was uploaded without errors
    if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
        // Insert or update the assignment submission in the database
        $query = "
            INSERT INTO student_assignment (student_id, assignment_id, submission, submitted_date) 
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
                submission = VALUES(submission), 
                submitted_date = NOW()";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iis', $student_id, $assignment_id, $target_file);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Assignment submitted/updated successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

mysqli_close($conn);
?>
