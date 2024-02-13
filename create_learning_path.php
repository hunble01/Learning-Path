<?php
session_start();
include('includes/db.php'); // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $topics = $_POST['topics'];
    $difficulty = $_POST['difficulty'];
    $resources = $_POST['resources'];

    // Validate and sanitize user inputs (implement as needed)

    // Example: Insert or update learning path in the database
    $user_id = $_SESSION['user_id']; // Assuming you have user authentication
    $path_id = $_POST['path_id']; // Assuming you have a hidden field for path_id

    // Update or insert logic based on whether $path_id is set
    // Update or insert logic based on whether $path_id is set
    // Update or insert logic based on whether $path_id is set
    // Update or insert logic based on whether $path_id is set
    if ($path_id) {
        // Update existing learning path
        $sql = "UPDATE learning_paths SET title = ?, description = ?, topics = ?, difficulty = ?, resources = ? WHERE path_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Log the SQL error
            error_log("SQL Error: " . $conn->error);
            die("Error in SQL query: " . $conn->error);
        }
        $stmt->bind_param("sssssi", $title, $description, $topics, $difficulty, $resources, $path_id);
    } else {
        // Insert new learning path
        $sql = "INSERT INTO learning_paths (user_id, title, description, topics, difficulty, resources) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Log the SQL error
            error_log("SQL Error: " . $conn->error);
            die("Error in SQL query: " . $conn->error);
        }
        $stmt->bind_param("isssss", $user_id, $title, $description, $topics, $difficulty, $resources);
    }

}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Learning Path</title>
    <link rel="stylesheet" href="assets/css/create_learning_path.css">
</head>
<body>

<h2>Create Learning Path</h2>

<form action="process_learning_path.php" method="post">
    <!-- Hidden field for path_id -->
    <input type="hidden" name="path_id" value="<?php echo isset($path_id) ? $path_id : ''; ?>">


    <!-- Title -->
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>

    <!-- Description -->
    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="4" required></textarea>

    <!-- Topics -->
    <label for="topics">Topics (comma-separated):</label>
    <input type="text" name="topics" id="topics" required>

    <!-- Difficulty -->
    <label for="difficulty">Difficulty:</label>
    <select name="difficulty" id="difficulty" required>
        <option value="easy">Easy</option>
        <option value="medium">Medium</option>
        <option value="hard">Hard</option>
    </select>

    <!-- Resources -->
    <label for="resources">Resources (URLs, separated by commas):</label>
    <textarea name="resources" id="resources" rows="4"></textarea>

    <!-- Submit Button -->
    <input type="submit" value="Create Learning Path">
</form>

</body>
</html>

