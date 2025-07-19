<?php
// Include the database connection file
include 'db_connection.php';

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $module_title = mysqli_real_escape_string($conn, $_POST['module_title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Insert data into the timetable table
    $insert_query = "INSERT INTO timetable (day, time, module_title, location) VALUES ('$day', '$time', '$module_title', '$location')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Redirect to admin page after successful insertion
        header("Location: admin.php");
        exit();
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
    <h1>Add New Timetable Entry</h1>
    <form method="POST">
        <label>Day:</label>
        <input type="text" name="day" required>
        <label>Time:</label>
        <input type="time" name="time" required>
        <label>Module Title:</label>
        <input type="text" name="module_title" required>
        <label>Location:</label>
        <input type="text" name="location" required>
        <button type="submit" class="btn btn-add">Add Timetable Entry</button>
    </form>
    <form method="POST" action="admin.php" style="display:inline-block;">
         <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
    </form>

</div>
</body>
</html>
