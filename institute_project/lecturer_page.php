<?php
session_start();
@include 'db_connection.php';

// Check if the lecturer is logged in
if (!isset($_SESSION['lecturer_id'])) {
    header('Location: lecturer_login.php');
    exit();
}

$lecturer_id = intval($_SESSION['lecturer_id']); // Ensure it's an integer

// Fetch modules associated with the lecturer
$query = "
    SELECT module_id, module_title 
    FROM modules 
    WHERE module_id IN (
        SELECT module_id 
        FROM lecturers_modules 
        WHERE lecturer_id = ?
    )";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $lecturer_id);
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
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
    <div class = 'update-module-container'>
        <h1>Welcome, Lecturer</h1>
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
                header('Location: update_module.php');
                exit();
            }
        }
        ?>
        </div>
    
</body>
</html>
