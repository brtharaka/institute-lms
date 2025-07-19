<?php
// Include the database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all form inputs are set
    if (isset($_POST['lecturer_name'], $_POST['email'], $_POST['phone_number'], $_POST['department_id'], $_POST['module_id'])) {
        
        // Sanitize input to prevent SQL injection
        $name = mysqli_real_escape_string($conn, $_POST['lecturer_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
        $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);

        // Check if the provided department_id and module_id exist
        $checkDeptQuery = "SELECT * FROM department WHERE department_id = '$department_id'";
        $checkModuleQuery = "SELECT * FROM modules WHERE module_id = '$module_id'";
        $deptResult = mysqli_query($conn, $checkDeptQuery);
        $moduleResult = mysqli_query($conn, $checkModuleQuery);

        if (mysqli_num_rows($deptResult) > 0 && mysqli_num_rows($moduleResult) > 0) {
            // Insert the data into the lecturers table using a prepared statement
            $query = "INSERT INTO lecturers (lecturer_name, email, phone_number, department_id, module_id) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssii', $name, $email, $phone, $department_id, $module_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "New lecturer added successfully!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: Invalid Department ID or Module ID.";
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
    <title>Add Lecturer</title>
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
        <h1>Add New Lecturer</h1>
        <form method="POST" action="add_lecturer.php">
            <label for="lecturer_name">Name:</label>
            <input type="text" name="lecturer_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="phone_number">Phone:</label>
            <input type="text" name="phone_number" required>

            <label for="department_id">Department ID:</label>
            <input type="text" name="department_id" required>

            <label for="module_id">Module ID:</label>
            <input type="text" name="module_id" required>

            <button class="btn btn-add">Add Lecturer</button>
        </form>
        <form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
        </form>
    </div>
</body>
</html>
