<?php
session_start(); // Start the session

include '../connection.php'; // Include the PDO database connection file

// Initialize the $user variable
$user = null;

// Check if the user is logged in
if (isset($_SESSION['user']['MemberId'])) {
    $m_id = $_SESSION['user']['MemberId'];
    // Fetch user's name from the database using PDO
    $stmt = $conn->prepare("SELECT names FROM members WHERE MemberId = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user']['MemberId'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if the admin is logged in
if (isset($_SESSION['admin'])) {
    // Fetch admin's username from the database using PDO
    $stmt = $conn->prepare("SELECT AdminNames FROM admin WHERE adminId = :admin_id");
    $stmt->bindParam(':admin_id', $_SESSION['admin']['adminId'], PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <style>
        /* Header */
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Ensure header is on top */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        header .avatar {
            margin-right: auto;
        }
        header .avatar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        header .user-info {
            margin-right: 10px;
            color: white;
            font-size: 14px;
        }
        header ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        header ul li {
            margin-right: 40px;
        }
        header ul li a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="avatar">
        <!-- Add code to display user's avatar -->
        <!-- Replace 'profile.jpg' with the path to the user's avatar -->
        <img src="profile.jpg" alt="Avatar">
        <div class="user-info">

        <?php 
    // Check if $_SESSION['user'] is set before accessing its keys
    if (isset($_SESSION['user'])) {
        echo $_SESSION['user']['names'];
    } 
    ?>
        
        </div>
    </div>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../contact_us.php">Contact Us</a></li>
            <li><a href="../about_us.php">About Us</a></li>
            <li><a href="../services.php">Services</a></li>
            <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) : ?>
                <li><a href="./members/logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Your website content goes here -->

</body>
</html>
