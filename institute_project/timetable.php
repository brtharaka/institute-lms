<?php
@include 'db_connection.php';
session_start();

if (!isset($_SESSION['student_name'])) {
    header('Location: student_login.php');
    exit();
}

// Fetch timetable data
$query = "SELECT * FROM timetable";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timetable</title>
    <link rel="stylesheet" type="text/css" href="css\admin_style.css">
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
    <h1>Common Timetable</h1>
    <table class = "admin-table">
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Module</th>
            <th>location</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['day']); ?></td>
            <td><?php echo htmlspecialchars($row['time']); ?></td>
            <td><?php echo htmlspecialchars($row['module_title']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
