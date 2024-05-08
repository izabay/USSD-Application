<?php
session_start();
include '../connection.php';

$error = ''; // Initialize error variable

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare the query to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM admins WHERE (email = :email OR username = :email) AND role = 'admin' AND password = :password");
        
        // Bind parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch user data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if there's a match
        if ($row) {
            // Store user data in session
            $_SESSION['admin'] = [
                'id' => $row['user_id'],
                'username' => $row['username'],
                'email' => $row['email']
            ];

            // Redirect to profile page
            header('location: ../loans/requested_loan.php');
            exit();
        } else {
            // Login failed, set error message
            $error = "Incorrect email or password. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }

        .container {
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="post">
                <div class="mb-3">
                    <label>Email address</label>
                    <input type="email" class="form-control" name="email" required placeholder="Enter user email">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" required placeholder="Enter Password">
                </div>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
                <a href="register.php" class="btn btn-link">Create Account</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
