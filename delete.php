<?php
include "header.php";

// Check if delete button is clicked
if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Fetch user data for the selected ID
    $select_query = "SELECT * FROM members WHERE MemberId = :id";
    $statement = $conn->prepare($select_query);
    $statement->bindParam(':id', $id);
    $statement->execute();
    $user_data = $statement->fetch(PDO::FETCH_ASSOC);

    // Close the statement
    $statement->closeCursor();
}

// Check if confirm delete button is clicked
if (isset($_POST['confirm_delete'])) {
    $id = $_POST['delete_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM members WHERE MemberId = :id";
    $statement = $conn->prepare($delete_query);
    $statement->bindParam(':id', $id);
    $result = $statement->execute();

    // Redirect to select-all.php after deletion
    if ($result) {
        header("location: select-all.php");
        exit();
    } else {
        die("Error:" . $conn->errorInfo()[2]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }
        .container{
            margin-top: 90px;
        }
        p{
            color: green;
            font-weight: bolder;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Delete User</h2>
            <form method="post">
                <div class="mb-3">
            
                    <p>Names:  <?php echo htmlspecialchars($user_data['names']); ?></p>
                   
                    <p>National Identity: <?php echo htmlspecialchars($user_data['national_identity']); ?></p>
                   
                    <p>Telephone: <?php echo htmlspecialchars($user_data['telephone']); ?></p>
                   
                    <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                </div>
                <input type="hidden" name="delete_id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-danger" name="confirm_delete" onclick="return confirm('Are you sure you want to delete this user?')">Confirm Delete</button>
                <a href="select-all.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
