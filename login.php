<?php
include('includes/db.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the entered password against the stored hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: dashboard.php");
            exit(); // Ensure that the script stops execution after redirecting
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }

    $stmt->close(); // Close the prepared statement
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Path Creator</title>
    <link rel="stylesheet" type="text/css" href="assets/css/login1.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            Username: <input type="text" name="username" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>

        <!-- Add a signup button/link -->
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</div>
</body>
</html>
