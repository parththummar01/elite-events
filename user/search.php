<?php
// Database connection
$conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Sanitize the query to prevent SQL injection
$searchQuery = $conn->real_escape_string($query);

// Search for events in the database
$sql = "SELECT * FROM events WHERE event_name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card">
              <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="Event Image">
              <div class="card-body">
                <h5 class="card-title"><?php echo $row['event_name']; ?></h5>
                <p class="card-text"><?php echo $row['description']; ?></p>
                <a href="event.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No results found for your search query.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
