<?php
// Start the session
// session_start();

// Include the database connection file
include 'header.php';
include 'navigation.php';

// Check if the user is logged in
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

    try {
        // Retrieve the user's total saved money from the database
        $select_query = "SELECT * FROM balances WHERE m_id = :user_id";
        $statement = $conn->prepare($select_query);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $total_saved_money = $row['balance'] ?? 0;

    } catch (PDOException $e) {
        echo "Failed to fetch data: " . $e->getMessage();
    }
} else {
    // Redirect to the login page if the user is not logged in
    // header("Location: ../members/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Balance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 90px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-left: 220px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        h3 {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello, <?php echo $_SESSION['usernames']; ?> here is your balance!</h2>
        <h3>Your New Balance is: <?php echo $total_saved_money; ?></h3>
    </div>
</body>
<?php include 'footer.php'; ?>

</html>
