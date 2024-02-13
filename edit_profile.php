<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve user information from the database
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User data not found!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="assets/css/edit_profile.css">
</head>
<body>
<h2>Edit Your Profile, <?php echo $user['username']; ?>!</h2>

<!-- Add a form for editing user information -->
<form action="update_profile.php" method="post" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>"><br>

    <label for="password">Password:</label>
    <input type="password" name="password"><br>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>"><br>

    <label for="bio">Bio:</label>
    <textarea name="bio"><?php echo $user['description']; ?></textarea><br>

    <label for="profile_picture">Profile Picture:</label>
    <input type="file" name="profile_picture"><br>

    <label for="url_link">URL Link:</label>
    <input type="text" name="url_link" value="<?php echo $user['url_link']; ?>"><br>



    <input type="submit" value="Save Changes">
</form>

<a href="dashboard.php" class="back-to-dashboard">Go back to Dashboard</a>


</body>
</html>
