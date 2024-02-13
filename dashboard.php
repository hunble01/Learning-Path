<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve user information from the database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    // Handle the case where there is an error in the query
    echo "Error: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle the case where user data is not found (this should not happen if sessions are managed correctly)
    echo "User data not found!";
    exit();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">

</head>
<body>
<nav class="top-nav">
    <div class="nav-links">
        <a href="edit_profile.php" class="nav-button edit-profile">Edit Page</a>
        <a href="logout.php" class="nav-button logout">Logout</a>
    </div>
</nav>

<div class="dashboard-header">
    <div class="welcome-header">
        <h2>Welcome to the Dashboard, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    </div>
    <div class="learning-paths-header">
        <h2>Your Learning Paths</h2>
    </div>
</div>


<!-- Search Form -->
<form action="dashboard.php" method="get">
    <label for="search">Search by Title:</label>
    <input type="text" id="search" name="search" required>
    <button type="submit">Search</button>
</form>

<form action="create_learning_path.php" method="get">
    <button type="submit">Create Path</button>
</form>

<?php
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Retrieve user's learning paths based on search
$sql = "SELECT * FROM learning_paths WHERE user_id = ? AND title LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search_query%";
$stmt->bind_param("is", $user_id, $search_param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='learning-path'>";

        echo "<div class='title'>";
        echo "<p>Title: <a href='edit_learning_path.php?path_id=" . $row['path_id'] . "'>{$row['title']} </a></p>";
        // Add a space for separation
        echo "</div>";

        echo "<div class='description'>";
        echo "<p>Description: {$row['description']}</p>";
        echo "</div>";

        echo "<div class='topics'>";
        echo "<p>Topics: {$row['topics']}</p>";
        echo "</div>";

        echo "<div class='difficulty'>";
        echo "<p>Difficulty: {$row['difficulty']}</p>";
        echo "</div>";

        echo "<div class='url'>";
        echo "<p>URL: <a href='" . $row['resources'] . "' target='_blank'>" . $row['resources'] . "</a></p>";
        echo "</div>";

        echo "</div>";
        echo "<hr>"; // Add a horizontal line for better separation
    }

} else {
    echo "";
}

$stmt->close();
$conn->close();
?>


<!-- Add a button to redirect to tutor.php -->
<form action="tutor.php" method="get">
    <button type="submit">Go to Tutor Page</button>
</form>

<form action="learning_path.php" method="get">
    <button type="submit">All Learning Paths</button>
</form>

<nav class="top-nav">
    <!-- Existing nav-buttons here... -->

    <!-- New button-link for the Word document -->
    <a href="readme.docx" class="nav-button button-link right-aligned" download="readme.docx">
        Download Readme
    </a>
</nav>


</body>
</html>
