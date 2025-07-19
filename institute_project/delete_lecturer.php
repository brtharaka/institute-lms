<?php
// Include the database connection file
include 'db_connection.php';

if (isset($_POST['lecturer_id'])) {
    $lecturer_id = $_POST['lecturer_id'];

    // Prepare the delete statement
    $query = "DELETE FROM lecturers WHERE lecturer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $lecturer_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Lecturer deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>
