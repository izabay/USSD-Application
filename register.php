<?php
session_start();

include '../connection.php';

// Function to check if phone number is already registered
function isRegistered($phoneNumber, $conn) {
    try {
        $stmt = $conn->prepare("SELECT PHONE_NUMBER FROM members WHERE PHONE_NUMBER = :phone_number");
        $stmt->bindParam(':phone_number', $phoneNumber, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false; // Return true if phone number exists, false otherwise
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>';
        return false; // Return false in case of error
    }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $names = $_POST['names'];
    $identity = $_POST['identity'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password == $confirm) {
        if (isRegistered($phone, $conn)) {
            echo "Telephone number already exists!";
            exit;
        }

        // Insert new user into members table
        try {
            $stmt = $conn->prepare("INSERT INTO members(names, national_identity, telephone, email, password) VALUES(:names, :identity, :phone, :email, :password)");
            $stmt->bindParam(':names', $names, PDO::PARAM_STR);
            $stmt->bindParam(':identity', $identity, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                // Start the session and store user data
                $_SESSION['user'] = [
                    'names' => $names,
                    'email' => $email
                ];
                header("location: login.php");
                exit;
            } else {
                echo "Failed to insert data!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Password mismatch! Please try to use the same password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }
        .container {
            margin-top: 20px;
            background-color: #f7f7f7; /* Set the background color */
            padding: 20px;
            border-radius: 10px; /* Optional: Add rounded corners */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Optional: Add shadow */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="post">
                <div class="mb-3">
                    <label>Names</label>
                    <input type="text" class="form-control" name="names" required placeholder="Enter Your Names">
                    <label>National Identity</label>
                    <input type="text" class="form-control" name="identity" required maxlength="16" pattern="[0-9]{16}" title="National Identity must be exactly 16 digits" placeholder="Enter your National Identity"> 
                    <label>Telephone</label>
                    <input type="text" class="form-control" name="phone" required minlength="10" maxlength="13" pattern="[0-9]+" title="Telephone must be between 10 and 13 digits" placeholder="Enter your Phone number">
                    <label>Email address</label>
                    <input type="email" class="form-control" name="email" required placeholder="Enter your email">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="password" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and contain at least one letter and one number" placeholder="New Password">

                      <label>Confirm Password</label>
                    <input type="password" class="form-control" name="confirm" required placeholder="Confirm Password">     
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a href="login.php" class="btn btn-primary" >Log in</a>
                <a href="../index.php" class="btn btn-primary" >Back to Dashboard</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
