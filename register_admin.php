<?php
session_start();
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare and execute the SQL statement to insert user data
        $stmt = $conn->prepare("INSERT INTO admin_manager (username, password, telephone, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $telephone, $email, $role]);

        // Check if the user was successfully inserted
        if ($stmt->rowCount() > 0) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            
            // Redirect to a success page or dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Handle insertion failure
            $_SESSION['error_message'] = "Registration failed. Please try again.";
            header("Location: registration.php");
            exit();
        }
    } catch (PDOException $e) {
        // Handle database error
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: registration.php");
        exit();
    }
} else {
    // Redirect to the registration form if accessed directly without form submission
    header("Location: registration.php");
    exit();
}
?>
