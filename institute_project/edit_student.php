<?php
// Include the database connection file
include 'db_connection.php';

// Check if the student_id is provided
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student data
    $query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $query);
    $student = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    // Get the updated data
    $name = $_POST['student_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $diploma_id = $_POST['diploma_id'];

    // Update the student data
    $update_query = "UPDATE students 
                     SET student_name='$name', email='$email', phone_number='$phone', diploma_id='$diploma_id' 
                     WHERE student_id = '$student_id'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "Student details updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
        <h1>Edit Student</h1>
        <form method="POST" action="edit_student.php">
            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">

            <label for="student_name">Name:</label>
            <input type="text" name="student_name" value="<?php echo $student['student_name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $student['email']; ?>" required>

            <label for="phone_number">Phone:</label>
            <input type="text" name="phone_number" value="<?php echo $student['phone_number']; ?>" required>

            <label for="diploma_id">Diploma ID:</label>
            <input type="text" name="diploma_id" value="<?php echo $student['diploma_id']; ?>" required>

            <button class="btn btn-edit" name="update_student">Update Student</button>
        </form>
        <form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
        </form>
    </div>
</body>
</html>
