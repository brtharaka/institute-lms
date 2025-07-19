<?php
// Include the database connection file
include 'db_connection.php';

if (isset($_POST['lecturer_id'])) {
    $lecturer_id = $_POST['lecturer_id'];

    // Fetch lecturer details from the database
    $query = "SELECT * FROM lecturers WHERE lecturer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $lecturer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lecturer = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Handle the form submission for editing lecturer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lecturer_name'], $_POST['email'], $_POST['phone_number'], $_POST['department_id'], $_POST['module_id'])) {
    $name = mysqli_real_escape_string($conn, $_POST['lecturer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    
    // Update the lecturer's information
    $query = "UPDATE lecturers SET lecturer_name = ?, email = ?, phone_number = ?, department_id = ?, module_id = ? WHERE lecturer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssiii', $name, $email, $phone, $department_id, $module_id, $lecturer_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Lecturer updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lecturer</title>
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
        <h1>Edit Lecturer</h1>
        <form method="POST" action="edit_lecturer.php">
            <input type="hidden" name="lecturer_id" value="<?php echo $lecturer['lecturer_id']; ?>">
            
            <label for="lecturer_name">Name:</label>
            <input type="text" name="lecturer_name" value="<?php echo $lecturer['lecturer_name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $lecturer['email']; ?>" required>

            <label for="phone_number">Phone:</label>
            <input type="text" name="phone_number" value="<?php echo $lecturer['phone_number']; ?>" required>

            <label for="department_id">Department ID:</label>
            <input type="text" name="department_id" value="<?php echo $lecturer['department_id']; ?>" required>

            <label for="module_id">Module ID:</label>
            <input type="text" name="module_id" value="<?php echo $lecturer['module_id']; ?>" required>

            <button class="btn btn-edit" name="update_lecturer">Update lecturer</button>
        </form>
        <form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
        </form>
    </div>
</body>
</html>
