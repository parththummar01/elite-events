<?php
require("db.php"); // Include the database connection

// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = mysqli_real_escape_string($conn, $_POST["event_title"]);
    $description = mysqli_real_escape_string($conn, $_POST["event_description"]);

    // Handle image upload
    if (isset($_FILES["event_img"]) && $_FILES["event_img"]["error"] === 0) {
        $targetDir = "uploads/"; // Ensure this folder exists
        $targetFile = $targetDir . basename($_FILES["event_img"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate file type
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowedTypes)) {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true); // Create the uploads folder if it doesn't exist
            }
            if (move_uploaded_file($_FILES["event_img"]["tmp_name"], $targetFile)) {
                $imageUrl = $targetFile;

                // Insert into database
                $sql = "INSERT INTO events (event_name, description, image_url) 
                        VALUES ('$title', '$description', '$imageUrl')";

                if (mysqli_query($conn, $sql)) {
                    $message = "Event added successfully!";
                } else {
                    $message = "Error: " . mysqli_error($conn);
                }
            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $message = "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group textarea {
            resize: none;
            height: 100px;
        }
        .form-group button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #ff851f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Event</h2>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form action="add_event.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="event-title">Event Title</label>
            <input type="text" id="event-title" name="event_title" placeholder="Enter event title" required>
        </div>
        <div class="form-group">
            <label for="event-description">Event Description</label>
            <textarea id="event-description" name="event_description" placeholder="Enter event description" required></textarea>
        </div>
        <div class="form-group">
            <label for="event-img">Event Image</label>
            <input type="file" id="event-img" name="event_img" accept="image/*" required>
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Add Event</button>
        </div>
    </form>
</div>

</body>
</html>
