<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["event_title"];
    $description = $_POST["event_description"];
    
    // Handle file upload
    if (isset($_FILES["event_img"]) && $_FILES["event_img"]["error"] == 0) {
        $target_dir = "uploads/"; // Folder to store uploaded images
        $target_file = $target_dir . basename($_FILES["event_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate file type (allow only images)
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES["event_img"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        } else {
            echo "Error uploading the image.";
            exit;
        }
    } else {
        echo "Please select an image to upload.";
        exit;
    }

    // Insert into the database using prepared statements
    $sql = "INSERT INTO events (event_name, description, image_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $target_file);

    if ($stmt->execute()) {
        header("Location: add_event.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>