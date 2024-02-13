
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Learning Path</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/edit_learning.css">
</head>
<body>

<div class="container">

    <div class="header">
        <h2>Edit Learning Path</h2>
    </div>

<?php
session_start();
include('includes/db.php'); // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['path_id'])) {
    $path_id = $_GET['path_id'];
    $user_id = $_SESSION['user_id'];

    // Retrieve the selected learning path
    $sql = "SELECT * FROM learning_paths WHERE path_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $path_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Display the form with pre-filled values
        echo "<form action='process_learning_path.php' method='post'>";
        echo "<input type='hidden' name='path_id' value='" . $row['path_id'] . "'>";
        echo "<label for='title'>Title:</label>";
        echo "<input type='text' name='title' value='" . $row['title'] . "' required>";
        echo "<label for='description'>Description:</label>";
        echo "<textarea name='description' rows='4' required>" . $row['description'] . "</textarea>";
        echo "<label for='topics'>Topics (comma-separated):</label>";
        echo "<input type='text' name='topics' value='" . $row['topics'] . "' required>";
        echo "<label for='difficulty'>Difficulty:</label>";
        echo "<select name='difficulty' required>";
        echo "<option value='easy' " . ($row['difficulty'] === 'easy' ? 'selected' : '') . ">Easy</option>";
        echo "<option value='medium' " . ($row['difficulty'] === 'medium' ? 'selected' : '') . ">Medium</option>";
        echo "<option value='hard' " . ($row['difficulty'] === 'hard' ? 'selected' : '') . ">Hard</option>";
        echo "</select>";
        echo "<label for='resources'>Resources (URLs, separated by commas):</label>";
        echo "<textarea name='resources' rows='4'>" . $row['resources'] . "</textarea>";
        // Include other form fields and pre-fill them
        echo "<input type='submit' value='Update Learning Path'>";
        echo "</form>";
    } else {
        echo "Learning path not found.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>

</body>
</html>
