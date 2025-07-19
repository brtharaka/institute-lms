<?php
session_start();

if(isset($_POST['submit'])){
    // Ensure a role is selected
    if(isset($_POST['role'])){
        $role = $_POST['role'];

        // Redirect based on the selected role
        switch($role){
            case 'admin':
                header('Location: admin_login.php');
                exit();
            case 'lecturer':
                header('Location: lecturer_login.php');
                exit();
            case 'student':
                header('Location: student_login.php');
                exit();
            default:
                $error_message = "Invalid role selected.";
        }
    } else {
        $error_message = "Please select a role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Selection</title>
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

    
    
    
     <h1>Welcome to IT Institute</h1>
        <h2>Select Your Role</h2>
        <?php
        if(isset($error_message)){
            echo "<p style='color:red;'>".htmlspecialchars($error_message)."</p>";
        }
        ?>
        <form method="post" action="">
            <label for="role">Choose your role:</label>
            <select name="role" id="role" required>
                <option value="">-- Select Role --</option>
                <option value="admin">Admin</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select>
            <br><br>
            <button type="submit" name="submit" class="btn">Continue</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 IT Institute. All rights reserved.</p>
    </footer>
    
</body>
</html>
