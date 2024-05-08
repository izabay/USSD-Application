<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Include connection file
include "../connection.php";

// Fetch user data
$userData = $_SESSION['user'];
$names = $userData['names'];
$email = $userData['email'];

// Example operations: Update user details, Delete user account, etc.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Operations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
   <?php include 'navigation.php'; ?>

    <div class="container">
        <h1>User Operations</h1>
        <p>Welcome, <?php echo $names; ?> (<?php echo $email; ?>)</p>
        <div>
            <!-- Example operations -->
            <h2>Example Operations:</h2>
            <ul>
                <li><a href="update_user.php">Update User Details</a></li>
                <li><a href="delete_account.php">Delete Account</a></li>
                <!-- Add more operations as needed -->
            </ul>
        </div>
        <a href="logout.php">Logout</a> <!-- Logout link -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
