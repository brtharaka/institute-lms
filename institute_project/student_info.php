<?php
@include 'db_connection.php';
session_start();

if (!isset($_SESSION['student_name'])) {
    header('Location: student_login.php');
    exit();
}

$student_id = $_SESSION['student_id']; // Fetch student_id from session

// Fetch student details
$query = "SELECT student_name, email, phone_number, diploma_id FROM students WHERE student_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Information</title>
    <link rel="stylesheet" type="text/css" href="css/admin_style.css">
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
    <h1>Student Information</h1>

    <table class = "admin-table">
        <tr>
            <td>Student Name:</td>
            <td><?php echo htmlspecialchars($student['student_name']); ?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
        </tr>
        <tr>
            <td>Diploma ID:</td>
            <td><?php echo htmlspecialchars($student['diploma_id']); ?></td>
        </tr>
    </table>

    <!-- Simple message for info change -->
    <div class="info-change">
        <p>If you would like to request a change to your personal information, please send an email to: <strong>admin@admin.com</strong>.</p>
    </div>
</div>
</body>
</html>
