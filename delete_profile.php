<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete user profile from the database
$sql = "DELETE FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}

// Additional cleanup or logout logic can be added here if needed

$conn->close();

// Redirect to the login page or any other desired location after deletion
header("Location: login.php");
exit();
?>

