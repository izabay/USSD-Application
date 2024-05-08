<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 90px;
            margin-left: 200px;

        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: green;
        }

        .user-info {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 5px 0;
            padding-left: 10px;
        }

        .user-info p:first-child {
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php
// Include necessary files and establish database connection
include 'header.php';
include 'navigation.php';

$select_query = "SELECT * FROM contact_us";
$stmt = $conn->prepare($select_query);
$stmt->execute();
$user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if user information is found
if ($user_info) {
    // Display the user information
    echo "<div class='container'>";
    echo "<h2>User Information</h2>";
    foreach ($user_info as $row) {
        echo "<div class='user-info'>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($row['MemberName']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['ContactEmail']) . "</p>";
        echo "<p><strong>Subject:</strong> " . htmlspecialchars($row['Subject']) . "</p>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($row['Message']) . "</p>";
        echo "<p><strong>Created At:</strong> " . htmlspecialchars($row['DATE']) . "</p>"; // Assuming you want to display the creation timestamp
        echo "</div>"; // Close user-info div
    }
    echo "</div>"; // Close the container
} else {
    // If user information is not found, display an error message
    echo "<div class='container'>";
    echo "<h2>User Information</h2>";
    echo "<p>User information not found.</p>";
    echo "</div>"; // Close the container
}
?>

</body>

</html>
