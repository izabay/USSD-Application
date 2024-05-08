<?php
include 'header.php';
include 'navigation.php';
// Check if the user is logged in and user ID is set
if (isset($_SESSION['user']) && isset($_SESSION['user']['MemberId'])) {
    $user_id = $_SESSION['user']['MemberId'];

    // Fetch penalty records for the user
    $select_penalty_query = "SELECT * FROM penalties WHERE m_id = :user_id";
    $stmt_penalty = $conn->prepare($select_penalty_query);
    $stmt_penalty->bindParam(':user_id', $user_id);
    $stmt_penalty->execute();

    if ($stmt_penalty) {
        $penalties = $stmt_penalty->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $message = "Failed to retrieve penalty information. Please try again later.";
    }
} else {
    // If user is not logged in or user id is not set, redirect to login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalty Status</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Penalty Status</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Penalty ID</th>
                    <th>Amount</th>
                    <th>Issued Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penalties as $penalty) { ?>
                    <tr>
                        <td><?php echo $penalty['PenaltyId']; ?></td>
                        <td><?php echo $penalty['amount']; ?></td>
                        <td><?php echo $penalty['issued_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php';?>
</html>
