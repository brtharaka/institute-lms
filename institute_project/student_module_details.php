<?php
session_start();
@include 'db_connection.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

$student_id = intval($_SESSION['student_id']);
$module_id = isset($_SESSION['module_id']) ? intval($_SESSION['module_id']) : 0;

if ($module_id === 0) {
    echo "<p class='error'>No module selected. Please go back and select a module.</p>";
    exit();
}

// Verify if the module belongs to the student
$verify_query = "SELECT 1 FROM student_modules WHERE student_id = ? AND module_id = ?";
$stmt_verify = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($stmt_verify, 'ii', $student_id, $module_id);
mysqli_stmt_execute($stmt_verify);
$verify_result = mysqli_stmt_get_result($stmt_verify);

if (mysqli_num_rows($verify_result) === 0) {
    echo "<p class='error'>You are not enrolled in this module.</p>";
    exit();
}

// Fetch assignments for the selected module that the student has not yet submitted
$assignments_query = "
    SELECT a.assignment_id, a.assignment_title, a.due_date 
    FROM assignments a
    LEFT JOIN student_assignment sa ON a.assignment_id = sa.assignment_id 
        AND sa.student_id = ?
    WHERE a.module_id = ? AND sa.assignment_id IS NULL";
    
$stmt_assignments = mysqli_prepare($conn, $assignments_query);
mysqli_stmt_bind_param($stmt_assignments, 'ii', $student_id, $module_id);
mysqli_stmt_execute($stmt_assignments);
$assignments_result = mysqli_stmt_get_result($stmt_assignments);

// Fetch module name
$module_name_query = "SELECT module_title FROM modules WHERE module_id = ?";
$stmt_module_name = mysqli_prepare($conn, $module_name_query);
mysqli_stmt_bind_param($stmt_module_name, 'i', $module_id);
mysqli_stmt_execute($stmt_module_name);
$module_name_result = mysqli_stmt_get_result($stmt_module_name);

if ($row = mysqli_fetch_assoc($module_name_result)) {
    $module_name = $row['module_title'];
} else {
    $module_name = "Unknown Module";
}


// Handle assignment submission or update
if (isset($_POST['submit_assignment'])) {
    $assignment_id = intval($_POST['assignment_id']);
    $submitted_date = date("Y-m-d H:i:s");

    if ($_FILES['assignment_file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['assignment_file']['tmp_name'];
        $file_content = file_get_contents($file_tmp);

        // Insert or update the submission in the student_assignment table
        $insert_query = "
            INSERT INTO student_assignment (assignment_id, student_id, module_id, submission, submitted_date)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE submission = VALUES(submission), submitted_date = VALUES(submitted_date)";

        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, 'iiiss', $assignment_id, $student_id, $module_id, $file_content, $submitted_date);
        mysqli_stmt_execute($stmt_insert);

        if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
            echo "<p class='success'>Assignment submitted or updated successfully.</p>";
        } else {
            echo "<p class='error'>Failed to submit or update the assignment.</p>";
        }
    } else {
        echo "<p class='error'>Error uploading file.</p>";
    }
}


// Fetch submitted assignments for the student
$submitted_assignments_query = "
    SELECT sa.assignment_id, a.assignment_title, sa.submitted_date 
    FROM student_assignment sa
    JOIN assignments a ON sa.assignment_id = a.assignment_id
    WHERE sa.student_id = ? AND sa.module_id = ?";
$stmt_submitted = mysqli_prepare($conn, $submitted_assignments_query);
mysqli_stmt_bind_param($stmt_submitted, 'ii', $student_id, $module_id);
mysqli_stmt_execute($stmt_submitted);
$submitted_result = mysqli_stmt_get_result($stmt_submitted);

// Fetch lecture notes for the selected module
$lecture_notes_query = "
    SELECT note_id, note_title, note_description, file_path, note_link 
    FROM lecture_notes 
    WHERE module_id = ?";
    
$stmt_notes = mysqli_prepare($conn, $lecture_notes_query);
mysqli_stmt_bind_param($stmt_notes, 'i', $module_id);
mysqli_stmt_execute($stmt_notes);
$lecture_notes_result = mysqli_stmt_get_result($stmt_notes);



// Close the prepared statement
mysqli_stmt_close($stmt_notes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Details</title>
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
<div class = "admin-container">

<!-- Display the Module Name as a Topic -->
<h1><?php echo htmlspecialchars($module_name); ?> - Module Details</h1>

<h2>Assignments</h2>
<?php
// Query to fetch assignments
$assignments_query = "
    SELECT assignment_id, assignment_title, due_date 
    FROM assignments 
    WHERE module_id = ?"; // Assume $module_id is set in the session or passed to the script

$stmt = mysqli_prepare($conn, $assignments_query);
mysqli_stmt_bind_param($stmt, 'i', $module_id);
mysqli_stmt_execute($stmt);
$assignments_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($assignments_result) > 0) {
    echo "<table class='admin-table'>
            <tr>
                <th>Assignment Title</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($assignments_result)) {
        $assignment_id = $row['assignment_id'];
        $assignment_title = $row['assignment_title'];
        $due_date = $row['due_date'];

        echo "<tr>
                <td>{$assignment_title}</td>
                <td>{$due_date}</td>
                <td>
                    <form action='submit_assignment.php' method='post' enctype='multipart/form-data'>
                        <input type='hidden' name='assignment_id' value='{$assignment_id}'>
                        <input type='file' name='assignment_file' required>
                        <button class='btn btn-add'>Upadate</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p class='info'>You don't have any assignments yet.</p>";
}
?>




<h2>Your Submitted Assignments</h2>
<?php
// Query to fetch submitted assignments
$submitted_query = "
    SELECT sa.assignment_id, a.assignment_title, sa.submitted_date, sa.submission 
    FROM student_assignment sa
    JOIN assignments a ON sa.assignment_id = a.assignment_id
    WHERE sa.student_id = ?";
$stmt = mysqli_prepare($conn, $submitted_query);
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['student_id']); // Assuming student_id is stored in session
mysqli_stmt_execute($stmt);
$submitted_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($submitted_result) > 0) {
    // There are submitted assignments, display them in the table
    echo "<table class='admin-table'>
            <tr>
                <th>Assignment Title</th>
                <th>Submitted Date</th>
                <th>Action</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($submitted_result)) {
        $assignment_id = $row['assignment_id'];
        $assignment_title = $row['assignment_title'];
        $submitted_date = $row['submitted_date'];
        $submission_link = !empty($row['submission']) ? 'uploads/assignment/' . htmlspecialchars($row['submission'], ENT_QUOTES, 'UTF-8') : '';

        echo "<tr>
                <td>{$assignment_title}</td>
                <td>{$submitted_date}</td>
                <td>
                    <!-- Display the existing file if it exists -->
                    " . ($submission_link ? "<a href='{$submission_link}' target='_blank'>View Submitted File</a>" : "No file submitted") . "
                    <form action='resubmit_assignment.php' method='post' enctype='multipart/form-data'>
                        <input type='hidden' name='assignment_id' value='{$assignment_id}'>
                        <input type='file' name='assignment_file' required>
                        <button class='btn btn-add'>Resubmite</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    // No submitted assignments found, display a message
    echo "<p class='info'>You have not submitted any assignments yet.</p>";
}
?>

<!-- Lecture Notes Section -->
<h2>Lecture Notes</h2>
<?php
// Assuming you've already executed a query to get lecture notes for a particular module
// e.g., $lecture_notes_result = mysqli_query($conn, "SELECT * FROM lecture_notes WHERE module_id = $module_id");

if (mysqli_num_rows($lecture_notes_result) > 0) {
    // There are lecture notes available, display them
    echo "<table class='admin-table'>
            <tr>
                <th>Note Title</th>
                <th>Description</th>
                <th>File</th>
                <th>Link</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($lecture_notes_result)) {
        $note_title = $row['note_title'];
        $note_description = $row['note_description'];
        $file_path = $row['file_path']; // Path to the uploaded file (PDF)
        $note_link = $row['note_link']; // External link

        // Display file link if available; otherwise, display external link
        $file_link = !empty($file_path) ? "<a href='{$file_path}' target='_blank'>Download File</a>" : "No file uploaded";

        echo "<tr>
                <td>{$note_title}</td>
                <td>{$note_description}</td>
                <td>{$file_link}</td>
                <td><a href='{$note_link}' target='_blank'>View Note</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    // No lecture notes found, display a message
    echo "<p class='info'>No lecture notes available for this module yet.</p>";
}
?>


</div>

</body>
</html>
