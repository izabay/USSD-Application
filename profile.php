<?php
session_start();
include '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Check if the form is submitted for image upload
if (isset($_POST['upload'])) {
    // Handle image upload here
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Specify the directory where you want to store uploaded images
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            // Update user's image path in the database
            $imagePath = $uploadFile;
            $userId = $user['id'];
            $updateQuery = "UPDATE members SET image = :imagePath WHERE id = :userId";
            $statement = $conn->prepare($updateQuery);
            $statement->bindParam(':imagePath', $imagePath);
            $statement->bindParam(':userId', $userId);
            $statement->execute();

            // Redirect back to the profile page
            header('Location: profile.php');
            exit();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Error uploading image.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo $user['names']; ?></h1>
        <!-- Profile Image -->
        <div class="mb-3">
            <!-- Display user's uploaded image or default image -->
            <?php if (!empty($user['image'])) : ?>
                <img src="<?php echo $user['image']; ?>" alt="Profile Image" class="img-fluid">
            <?php else : ?>
                <img src="path/to/default/image.jpg" alt="Profile Image" class="img-fluid">
            <?php endif; ?>
        </div>
        <!-- Image Upload Form -->
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">Upload Profile Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary" name="upload">Upload</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
