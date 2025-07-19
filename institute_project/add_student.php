<?php
// Include the database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all form inputs are set
    if (isset($_POST['student_name'], $_POST['email'], $_POST['phone_number'], $_POST['diploma_id'])) {
        
        // Get the submitted form data and sanitize input to prevent SQL injection
        $name = mysqli_real_escape_string($conn, $_POST['student_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $diploma_id = mysqli_real_escape_string($conn, $_POST['diploma_id']);

        // Check if the provided diploma_id exists in the diploma table
        $checkDiplomaQuery = "SELECT * FROM diploma WHERE diploma_id = '$diploma_id'";
        $diplomaResult = mysqli_query($conn, $checkDiplomaQuery);
        
        if (mysqli_num_rows($diplomaResult) > 0) {
            // Insert the data into the students table using a prepared statement
            $query = "INSERT INTO students (student_name, email, phone_number, diploma_id) 
                      VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $phone, $diploma_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "New student added successfully!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: Invalid Diploma ID.";
        }
    } else {
        echo " All fields are required.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>


<nav class="navlocation" id="myNav">
    <ul>
        <li><a href="login_form.php" class="button">Login</a></li>
        <li class="dropdown">
            <a href="diplomas.php" class="dropbtn button">Diplomas</a>
            <div class="dropdown-content">
                <a href="diploma1.php">Diploma 1</a>
                <a href="diploma2.php">Diploma 2</a>
                <a href="diploma3.php">Diploma 3</a>
                <a href="diploma4.php">Diploma 4</a>
                <a href="diploma5.php">Diploma 5</a>
            </div>
        </li>
        <li><a href="about_us.php" class="button">About Us</a></li>
    </ul>
</nav>

    <div class="admin-container">
        <h1>Add New Student</h1>
        <form method="POST" action="add_student.php">
            <label for="student_name">Name:</label>
            <input type="text" name="student_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="phone_number">Phone:</label>
            <input type="text" name="phone_number" required>

            <label for="diploma_id">Diploma ID:</label>
            <input type="text" name="diploma_id" required>

            <button class="btn btn-add">Add Student</button>
        </form>
        <form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
        </form>
    </div>
</body>
</html>
