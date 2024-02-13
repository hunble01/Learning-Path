<?php
include('includes/db.php');

// Retrieve learning paths with associated usernames from the database
$sql = "SELECT lp.*, u.username,
               COUNT(IF(pv.vote_type = 'like', 1, NULL)) AS like_count,
               COUNT(IF(pv.vote_type = 'dislike', 1, NULL)) AS dislike_count
        FROM learning_paths lp
        LEFT JOIN path_votes pv ON lp.path_id = pv.path_id
        LEFT JOIN users u ON lp.user_id = u.user_id
        GROUP BY lp.path_id";

$result = $conn->query($sql);

if (!$result) {
    // Handle the case where there is an error in the query
    echo "Error: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Paths</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/learning_path.css">
</head>
<body>
<div class="overlay"></div> <!-- This is the overlay div -->

<h2>Learning Paths</h2>

<!-- Search Form -->
<form action="learning_path.php" method="get">
    <label for="search">Search by Title:</label>
    <input type="text" id="search" name="search" required>
    <button type="submit">Search</button>
</form>

<?php
session_start();
include('includes/db.php'); // Adjust the path as needed


// Step 2: Display Learning Paths
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>Title: " . $row['title'] . "</p>";
        echo "<p>Description: " . $row['description'] . "</p>";
        echo "<p>Topics: " . $row['topics'] . "</p>";
        echo "<p>Difficulty: " . $row['difficulty'] . "</p>";
        echo "<p>Created by: " . $row['username'] . "</p>"; // Add the username
        echo "<p>URL: <a href='" . $row['resources'] . "' target='_blank'>" . $row['resources'] . "</a></p>";
        echo "<p>Likes: " . $row['like_count'] . "</p>";
        echo "<p>Dislikes: " . $row['dislike_count'] . "</p>";



        // Step 3: Handle Upvote/Downvote
        echo "<form action='process_vote.php' method='post'>";
        echo "<input type='hidden' name='path_id' value='" . $row['path_id'] . "'>";
        echo "<button type='submit' name='vote' value='like'>Like</button>";
        echo "<button type='submit' name='vote' value='dislike'>Dislike</button>";
        echo "</form>";

        echo "<hr>"; // Add a horizontal line for better separation
    }
} else {
    echo "No learning paths found.";
}


$conn->close();
?>

<a href="dashboard.php" class="back-to-dashboard" id="back-to-dashboard">Go back to Dashboard</a>

</body>
</html>
