<?php
session_start();
@include 'db_connection.php';

if (isset($_POST['submit'])) {
    // Retrieve and sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare and execute query
    $query = "SELECT * FROM users WHERE username=? AND password=? AND role='lecturer'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Fetch lecturer ID
        $row = mysqli_fetch_assoc($result);
        $_SESSION['lecturer_id'] = $row['lecturer_id']; // Use lecturer_id instead of username
        header('Location: lecturer_page.php');
        exit();
    } else {
        echo "<script>alert('Incorrect username or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Login</title>
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
    
        <form action="" method="post" class="login-form">
            <h2>Lecturer Login</h2>
            <input type="text" name="username" required placeholder="Enter your username">
            <input type="password" name="password" required placeholder="Enter your password">
            <button type="submit" name="submit" class="btn">Login</button>
        </form>
    
</body>
</html>
