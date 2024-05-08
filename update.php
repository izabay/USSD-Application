<?php
include "header.php";
include 'navigation.php';

// Check if the form is submitted for update
if (isset($_POST['update'])) {
    $id = $_GET['updateid'];

    $names = $_POST['names'];
    $identity = $_POST['identity'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query with placeholders
    $update_query = "UPDATE members SET names = :names, national_identity = :identity, telephone = :phone, email = :email, password = :password WHERE MemberId = :id";
    
    // Prepare the statement
    $statement = $conn->prepare($update_query);

    // Bind parameters
    $statement->bindParam(':names', $names);
    $statement->bindParam(':identity', $identity);
    $statement->bindParam(':phone', $phone);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $password);
    $statement->bindParam(':id', $id);

    // Execute the statement
    $execute_query = $statement->execute();

    if ($execute_query) {
        header("location: select-all.php");
        exit();
    } else {
        die("Error:" . $conn->errorInfo()[2]);
    }
}

// Fetch the current user data to prepopulate the form
$id = $_GET['updateid'];
$select_query = "SELECT names, national_identity, telephone, email FROM members WHERE MemberId = :id";
$statement = $conn->prepare($select_query);
$statement->bindParam(':id', $id);
$statement->execute();
$user_data = $statement->fetch(PDO::FETCH_ASSOC);

// Close the statement
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }
        .container{
            margin-top: 90px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="post" onsubmit="return confirm('Are you sure you want to update this user?')">
                <div class="mb-3">
                <label>Names</label>
                <input type="text" class="form-control" name="names" required placeholder="Enter Your Names" value="<?php echo htmlspecialchars($user_data['names']); ?>">
                <label>National Identity</label>
                <input type="text" class="form-control" name="identity" required placeholder="Enter your National Identity" value="<?php echo htmlspecialchars($user_data['national_identity']); ?>">
                <label>Telephone</label>
                <input type="text" class="form-control" name="phone" required placeholder="Enter your Phone number" value="<?php echo htmlspecialchars($user_data['telephone']); ?>">
                <label>Email address</label>
                <input type="email" class="form-control" name="email" required placeholder="Enter your email" value="<?php echo htmlspecialchars($user_data['email']); ?>">
                <label>New Password</label>
                <input type="password" class="form-control" name="password" required placeholder="New Password">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm" required placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn btn-primary" name="update">update Now</button>                
                <a href="select-all.php" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>

</html>
