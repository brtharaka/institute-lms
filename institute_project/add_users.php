<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required form fields are set
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
        $role = $_POST['role'];
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : NULL;
        $admin_id = !empty($_POST['admin_id']) ? $_POST['admin_id'] : NULL;
        $lecturer_id = !empty($_POST['lecturer_id']) ? $_POST['lecturer_id'] : NULL;

        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, student_id, admin_id, lecturer_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $username, $password, $role, $student_id, $admin_id, $lecturer_id);

        if ($stmt->execute()) {
            echo "User added successfully!";
            header("Location: admin.php");  // Redirect back to the admin page after adding
            exit();
        } else {
            echo "Error adding user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Timetable</title>
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
<h2>Add User</h2>
<form method="POST" action="add_users.php">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required> <!-- Password input -->

    <label for="role">Role:</label>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="lecturer">Lecturer</option>
        <option value="student">Student</option>
        <option value="coordinator">Coordinator</option>
    </select>

    <label for="student_id">Student ID:</label>
    <input type="number" name="student_id">

    <label for="admin_id">Admin ID:</label>
    <input type="number" name="admin_id">

    <label for="lecturer_id">Lecturer ID:</label>
    <input type="number" name="lecturer_id">

    <button class="btn btn-edit" name="edit_users">Update User</button>
</form>
             <form method="POST" action="admin.php" style="display:inline-block;">
                   <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
              </form>
        

</div>
</body>
</html>
