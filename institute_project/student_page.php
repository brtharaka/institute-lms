<?php
session_start();
@include 'db_connection.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

$student_id = intval($_SESSION['student_id']); // Ensure it's an integer

// Fetch student name from the students table
$query = "SELECT student_name FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the student record exists
if ($result && mysqli_num_rows($result) > 0) {
    $student_data = mysqli_fetch_assoc($result);
    $student_name = $student_data['student_name']; // Fetch student's name
} else {
    die('Student not found.');
}

// Fetch modules associated with the student from the student_module table
$query = "
    SELECT m.module_id, m.module_title 
    FROM modules m
    JOIN student_modules sm ON m.module_id = sm.module_id
    WHERE sm.student_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
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

<div class="update-module-container">
<h1>Welcome, <?php echo htmlspecialchars($student_name); ?></h1>
    <div class ="dashboard">
        <button onclick="location.href='student_info.php'">View Student Information</button>
        <button onclick="location.href='timetable.php'">View Timetable</button>
    </div>
    <h2>Select Module:</h2>
    <form action="" method="post">
        <select name="module_id" required>
            <option value="" disabled selected>Select a Module</option>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['module_id']}'>{$row['module_title']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="select_module" class="btn">Select Module</button>
    </form>

    <?php
    if (isset($_POST['select_module'])) {
        $selected_module_id = intval($_POST['module_id']);
        
        // Validate input
        if ($selected_module_id <= 0) {
            echo "<p class='error'>Invalid module selected.</p>";
        } else {
            $_SESSION['module_id'] = $selected_module_id;
            header('Location: student_module_details.php'); // Redirect to module details page
            exit();
        }
    }
    ?>
</div>

</body>
</html>
