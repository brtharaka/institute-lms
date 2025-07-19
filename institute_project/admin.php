<?php
// Include the database connection file
include 'db_connection.php';

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all timetable entries from the database
$timetable_query = "SELECT * FROM timetable";
$timetable_result = mysqli_query($conn, $timetable_query);

// Check if the query returned any results
if (mysqli_num_rows($timetable_result) === 0) {
    $timetable_message = "No timetable data available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <h1>Admin Dashboard</h1>

        <!-- Section: Manage Students -->
        <h2>Manage Students</h2>
        <table class="admin-table">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Diploma ID</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all students from the database
            $query = "SELECT * FROM students";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['student_id']}</td>
                        <td>{$row['student_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['diploma_id']}</td>
                        <td>
                            <form method='POST' action='edit_student.php' style='display:inline-block;'>
                                <input type='hidden' name='student_id' value='{$row['student_id']}'>
                                <button class='btn btn-edit'>Edit</button>
                            </form>
                            <form method='POST' action='delete_student.php' style='display:inline-block;'>
                                <input type='hidden' name='student_id' value='{$row['student_id']}'>
                                <button class='btn btn-delete'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        <form method="POST" action="add_student.php">
            <button class="btn btn-add">Add New Student</button>
        </form>

        <!-- Section: Manage Lecturers -->
        <h2>Manage Lecturers</h2>
        <table class="admin-table">
            <tr>
                <th>Lecturer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all lecturers from the database, including phone_number and department_id
            $query = "
                SELECT l.lecturer_id, l.lecturer_name, l.email, l.phone_number, m.module_title, d.department_name 
                FROM lecturers l
                LEFT JOIN modules m ON l.module_id = m.module_id
                LEFT JOIN department d ON l.department_id = d.department_id";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['lecturer_id']}</td>
                        <td>{$row['lecturer_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['module_title']}</td>
                        <td>{$row['department_name']}</td>
                        <td>
                            <form method='POST' action='edit_lecturer.php' style='display:inline-block;'>
                                <input type='hidden' name='lecturer_id' value='{$row['lecturer_id']}'>
                                <button class='btn btn-edit'>Edit</button>
                            </form>
                            <form method='POST' action='delete_lecturer.php' style='display:inline-block;'>
                                <input type='hidden' name='lecturer_id' value='{$row['lecturer_id']}'>
                                <button class='btn btn-delete'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        <form method="POST" action="add_lecturer.php">
            <button class="btn btn-add">Add New Lecturer</button>
        </form>

        <!-- Section: Timetable -->
        <h2>Timetable List</h2>
        <table class="admin-table">
            <tr>
                <th>Day</th>
                <th>Time</th>
                <th>Module Title</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
            <?php
            if (isset($timetable_message)) {
                echo "<tr><td colspan='5'>$timetable_message</td></tr>";
            } else {
                while ($row = mysqli_fetch_assoc($timetable_result)) {
                    echo "<tr>
                            <td>{$row['day']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['module_title']}</td>
                            <td>{$row['location']}</td>
                            <td>
                                <form method='POST' action='edit_timetable.php' style='display:inline-block;'>
                                    <input type='hidden' name='timetable_id' value=' {$row["timetable_id"]}'>
                                    <button class='btn btn-edit' type='submit'>Edit</button>
                                </form>


                                <form method='POST' action='delete_timetable.php' style='display:inline-block;'>
                                    <input type='hidden' name='timetable_id' value='{$row['timetable_id']}'>
                                    <button class='btn btn-delete' type='submit'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
            }
            ?>
        </table>
        <form method="GET" action="add_timetable.php">
            <button class="btn btn-add">Add New timetable list</button>
        </form>


        <!-- Section: Manage Users -->
<h2>Manage Users</h2>
<table class="admin-table">
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Password</th> <!-- Hashed password display -->
        <th>Role</th>
        <th>Student ID</th>
        <th>Admin ID</th>
        <th>Lecturer ID</th>
        <th>Actions</th>
    </tr>
    <?php
    // Fetch all users from the database
    $query = "SELECT user_id, username, password, role, student_id, admin_id, lecturer_id FROM users";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['password']}</td> <!-- Hashed password display -->
                <td>{$row['role']}</td>
                <td>{$row['student_id']}</td>
                <td>{$row['admin_id']}</td>
                <td>{$row['lecturer_id']}</td>
                <td>
                    <form method='POST' action='edit_users.php' style='display:inline-block;'>
                        <input type='hidden' name='user_id' value='{$row['user_id']}'>
                        <button class='btn btn-edit'>Edit</button>
                    </form>
                    <form method='POST' action='delete_users.php' style='display:inline-block;'>
                        <input type='hidden' name='user_id' value='{$row['user_id']}'>
                        <button class='btn btn-delete'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
</table>
<form method="POST" action="add_users.php">
    <button class="btn btn-add">Add New User</button>
</form>




    </div>
</body>
</html>
