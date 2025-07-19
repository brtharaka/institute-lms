<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    // Fetch existing user details to populate the form
    $query = "SELECT username, role, student_id, admin_id, lecturer_id FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Timetable Entry</title>
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
<h2>Edit User</h2>

<form method="POST" action="update_users.php">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

    <label for="password">New Password (leave blank if not changing):</label>
    <input type="password" name="password"> <!-- Leave blank if password shouldn't change -->

    <label for="role">Role:</label>
    <select name="role" required>
        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        <option value="lecturer" <?php if ($user['role'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
        <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
        <option value="coordinator" <?php if ($user['role'] == 'coordinator') echo 'selected'; ?>>Coordinator</option>
    </select>

    <label for="student_id">Student ID:</label>
    <input type="number" name="student_id" value="<?php echo $user['student_id']; ?>">

    <label for="admin_id">Admin ID:</label>
    <input type="number" name="admin_id" value="<?php echo $user['admin_id']; ?>">

    <label for="lecturer_id">Lecturer ID:</label>
    <input type="number" name="lecturer_id" value="<?php echo $user['lecturer_id']; ?>">

    <button class="btn btn-edit" name="edit_users">Update user</button>
</form>
<form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
    </form>

</div>
</body>
</html>
