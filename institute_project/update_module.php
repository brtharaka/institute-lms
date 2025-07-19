<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if lecturer_id and module_id are set in the session
if (!isset($_SESSION['lecturer_id']) || !isset($_SESSION['module_id'])) {
    header('Location: lecturer_page.php');
    exit();
}

include 'db_connection.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the module title using prepared statements to prevent SQL injection
$module_id = $_SESSION['module_id'];
$query = "SELECT module_title FROM modules WHERE module_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $module_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

$module = $result->fetch_assoc();
if (!$module) {
    die("Module not found.");
}

$module_title = htmlspecialchars($module['module_title'], ENT_QUOTES, 'UTF-8');

// Fetch uploaded assignments by students for the current module
$assignment_query = "
    SELECT 
        student_id, 
        submitted_date, 
        submission 
    FROM 
        student_assignment 
    WHERE 
        module_id = ?";

$stmt_assignments = $conn->prepare($assignment_query);
$stmt_assignments->bind_param('i', $module_id);
$stmt_assignments->execute();
$assignments_result = $stmt_assignments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Module</title>
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
        <h1>Update Module: <?php echo htmlspecialchars($module_title, ENT_QUOTES, 'UTF-8'); ?></h1>

        <div class="task2">
            <h2>Update Assignments</h2>
            <form action="update_assignments.php" method="post">
                <input type="hidden" name="module_id" value="<?php echo htmlspecialchars($module_id, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="form-group">
                    <label for="assignment_title">Assignment Title:</label>
                    <input type="text" id="assignment_title" name="assignment_title" placeholder="Enter Assignment Title" required>
                </div>
                
                <div class="form-group">
                    <label for="assignment_description">Assignment Description:</label>
                    <textarea id="assignment_description" name="assignment_description" placeholder="Enter assignment description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" required>
                </div>
                
                <button type="submit" name="update_assignments" class="btn">Update Assignments</button>
            </form>
        </div>
        <div class="task2">
    <h2>Uploaded Assignments for <?php echo $module_title; ?></h2>

    <?php
    if ($assignments_result->num_rows > 0) {
        echo "<table class='admin-table'>
                <tr>
                    <th>Student ID</th>
                    <th>Submitted Date</th>
                    <th>File</th>
                </tr>";

        while ($row = $assignments_result->fetch_assoc()) {
            $student_id = htmlspecialchars($row['student_id'], ENT_QUOTES, 'UTF-8');
            $submitted_date = htmlspecialchars($row['submitted_date'], ENT_QUOTES, 'UTF-8');
            $file_path = htmlspecialchars($row['submission'], ENT_QUOTES, 'UTF-8');  // Already has the full path

            // Check if the file exists before displaying the link
            if (file_exists($file_path) && !empty($file_path)) {
                $file_link = "<a href='{$file_path}' target='_blank'>View Assignment</a>";
            } else {
                $file_link = "No file available";
            }

            echo "<tr>
                    <td>{$student_id}</td>
                    <td>{$submitted_date}</td>
                    <td>{$file_link}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p class='info'>No assignments have been uploaded for this module yet.</p>";
    }

    $stmt_assignments->close();
    $stmt->close();
    $conn->close();
    ?>
</div>



<div class="task2">
    <h2>Update Lecture Notes</h2>
    <form action="update_lecture_notes.php" method="post" enctype="multipart/form-data">
        <!-- Module ID (hidden) -->
        <input type="hidden" name="module_id" value="<?php echo htmlspecialchars($module_id, ENT_QUOTES, 'UTF-8'); ?>">

        <!-- Note Title -->
        <div class="form-group">
            <label for="note_title">Note Title:</label>
            <input type="text" id="note_title" name="note_title" required>
        </div>

        <!-- Note Description -->
        <div class="form-group">
            <label for="note_description">Note Description:</label>
            <textarea id="note_description" name="note_description" required></textarea>
        </div>

        <!-- Upload Lecture Notes -->
        <div class="form-group">
            <label for="lecture_notes">Upload Lecture Notes (PDF):</label>
            <input type="file" id="lecture_notes" name="lecture_notes" accept=".pdf">
        </div>

        <!-- OR External Link -->
        <div class="form-group">
            <label for="note_link">Or Provide a Link:</label>
            <input type="url" id="note_link" name="note_link">
        </div>

        <!-- Submit Button -->
        <button type="submit" name="update_notes" class="btn">Upload Lecture Notes</button>
    </form>
</div>

<script>
    // Enforce either file upload or external link but not both
    document.getElementById('lecture_notes').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('note_link').disabled = true;
        } else {
            document.getElementById('note_link').disabled = false;
        }
    });

    document.getElementById('note_link').addEventListener('input', function() {
        if (this.value) {
            document.getElementById('lecture_notes').disabled = true;
        } else {
            document.getElementById('lecture_notes').disabled = false;
        }
    });
</script>


        <div class="task2">
            <h2>Update Announcements</h2>
            <form action="update_announcements.php" method="post">
                <input type="hidden" name="module_id" value="<?php echo htmlspecialchars($module_id, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="form-group">
                    <label for="announcement_details">Announcement Details:</label>
                    <textarea id="announcement_details" name="announcement_details" placeholder="Enter announcement details" required></textarea>
                </div>
                
                <button type="submit" name="update_announcements" class="btn">Update Announcements</button>
            </form>
        </div>

        <div class="task2">
            <h2>Update Exam Results</h2>
            <form action="update_exam_results.php" method="post">
                <input type="hidden" name="module_id" value="<?php echo htmlspecialchars($module_id, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" placeholder="Enter student ID" required>
                </div>
                
                <div class="form-group">
                    <label for="grade">Grade:</label>
                    <input type="text" id="grade" name="grade" placeholder="Enter grade" required>
                </div>
                
                <button type="submit" name="update_exam_results" class="btn">Update Exam Results</button>
            </form>
        </div>
    </div>
</body>
</html>
