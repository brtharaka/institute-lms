<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_notes'])) {
    // Get POST data
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $note_title = mysqli_real_escape_string($conn, $_POST['note_title']);
    $note_description = mysqli_real_escape_string($conn, $_POST['note_description']);
    $note_link = isset($_POST['note_link']) ? mysqli_real_escape_string($conn, $_POST['note_link']) : '';

    // Initialize file path to null
    $file_path = null;

    // Directory to store uploaded files
    $target_dir = "uploads/lecture_notes/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // Check if a file was uploaded
    if (!empty($_FILES['lecture_notes']['name'])) {
        $target_file = $target_dir . basename($_FILES["lecture_notes"]["name"]);
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if the uploaded file is a PDF
        if ($file_type != "pdf") {
            echo "Only PDF files are allowed.";
            exit;
        }

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["lecture_notes"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Prepare SQL query to insert or update lecture notes
    $query = "";
    if ($file_path) {
        // If a file was uploaded, use the file path
        $relative_path = "uploads/lecture_notes/" . basename($_FILES["lecture_notes"]["name"]);
        $query = "INSERT INTO lecture_notes (module_id, note_title, note_description, file_path, note_link) 
                  VALUES ('$module_id', '$note_title', '$note_description', '$relative_path', NULL)
                  ON DUPLICATE KEY UPDATE 
                      note_title='$note_title', 
                      note_description='$note_description', 
                      file_path='$relative_path', 
                      note_link=NULL";
    } elseif ($note_link) {
        // If a link was provided, store the link
        $query = "INSERT INTO lecture_notes (module_id, note_title, note_description, file_path, note_link) 
                  VALUES ('$module_id', '$note_title', '$note_description', NULL, '$note_link')
                  ON DUPLICATE KEY UPDATE 
                      note_title='$note_title', 
                      note_description='$note_description', 
                      file_path=NULL, 
                      note_link='$note_link'";
    } else {
        echo "Please provide either a PDF or a link.";
        exit;
    }

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Lecture notes uploaded successfully.";
    } else {
        echo "Database error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
