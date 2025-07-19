<?php
// Include the database connection file
include 'db_connection.php';

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Delete the student record
    $delete_query = "DELETE FROM students WHERE student_id = '$student_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "Student deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
