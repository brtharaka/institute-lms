<?php
// Include the database connection file
include 'db_connection.php';

// Initialize timetable_id variable
$timetable_id = null;

// Check if timetable_id is passed via POST
if (isset($_POST['timetable_id']) && is_numeric($_POST['timetable_id'])) {
    $timetable_id = intval($_POST['timetable_id']);

    // Fetch the existing timetable data
    $query = "SELECT * FROM timetable WHERE timetable_id = $timetable_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $timetable = mysqli_fetch_assoc($result);
    } else {
        die("Timetable entry not found");
    }
} else {
    die("Invalid timetable ID");
}

// Check if the form has been submitted for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_timetable'])) {
    // Get the updated data from the form
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $module_title = mysqli_real_escape_string($conn, $_POST['module_title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Update the timetable in the database
    $update_query = "UPDATE timetable 
                     SET day = '$day', time = '$time', module_title = '$module_title', location = '$location' 
                     WHERE timetable_id = $timetable_id";
    
    if (mysqli_query($conn, $update_query)) {
        // Redirect back to admin page after update
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating timetable: " . mysqli_error($conn);
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
    <h2>Edit Timetable</h2>
    <form method="POST" action="edit_timetable.php">
        <!-- Pass the timetable_id again in the form so it can be used in the update -->
        <input type="hidden" name="timetable_id" value="<?php echo htmlspecialchars($timetable_id); ?>">

        <label>Day:</label>
        <input type="text" name="day" value="<?php echo htmlspecialchars($timetable['day']); ?>" required>

        <label>Time:</label>
        <input type="time" name="time" value="<?php echo htmlspecialchars($timetable['time']); ?>" required>

        <label>Module Title:</label>
        <input type="text" name="module_title" value="<?php echo htmlspecialchars($timetable['module_title']); ?>" required>

        <label>Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($timetable['location']); ?>" required>

        <!-- Submit button for updating the timetable -->
        <button class="btn btn-edit" name="edit_timetable">Update Timetable</button>
        
             <form method="POST" action="admin.php" style="display:inline-block;">
                   <button class="btn btn-back" type="submit">Back to Admin Dashboard</button>
              </form>
        

</div>
</body>
</html>
