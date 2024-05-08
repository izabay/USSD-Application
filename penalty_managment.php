<?php
// Start the session
include 'header.php';
include 'navigation.php';
// Check if the user is logged in and is an admin
if ( $_SESSION['userid']) {
    $userid=$_SESSION['userid'];
    // $adminid=$_SESSION['adminid'];
    // Fetch penalty records
    $select_penalty_query = "SELECT * FROM penalties";
    $stmt_penalty = $conn->query($select_penalty_query);

    // Check if the form is submitted to add a new penalty
    if (isset($_POST['submit_penalty'])) {
        $m_id = $userid;
        $amount = $_POST['amount'];

        // Insert the new penalty into the database
        $insert_penalty_query = "INSERT INTO penalties (m_id, amount, issued_date) VALUES (:m_id, :amount, NOW())";
        $stmt_insert_penalty = $conn->prepare($insert_penalty_query);
        $stmt_insert_penalty->bindParam(':m_id', $m_id);
        $stmt_insert_penalty->bindParam(':amount', $amount);
        $execute_insert_query = $stmt_insert_penalty->execute();

        if ($execute_insert_query) {
            $message = "Penalty added successfully.";
        } else {
            $message = "Failed to add penalty. Please try again.";
        }
    }

    // Check if the form is submitted to delete a penalty
    if (isset($_POST['delete_penalty'])) {
        $penalty_id = $userid;

        // Delete the penalty from the database
        $delete_penalty_query = "DELETE FROM penalties WHERE m_id = :penalty_id";
        $stmt_delete_penalty = $conn->prepare($delete_penalty_query);
        $stmt_delete_penalty->bindParam(':penalty_id', $penalty_id);
        $execute_delete_query = $stmt_delete_penalty->execute();

        if ($execute_delete_query) {
            $message = "Penalty deleted successfully.";
        } else {
            $message = "Failed to delete penalty. Please try again.";
        }
    }
} else {
    // If user is not logged in or not an admin, redirect to login page
    header("Location: ../members/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalty Management</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 200px;
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
        <h2>Penalty Management</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>

        <!-- Add Penalty Form -->
        <form method="post" class="mb-4">           
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_penalty">Add Penalty</button>
        </form>

        <!-- Display Existing Penalties -->
        <h3>Existing Penalties</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Penalty ID</th>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Issued Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt_penalty->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['PenaltyId']; ?></td>
                        <td><?php echo $row['m_id']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['issued_date']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="penalty_id" value="<?php echo $row['PenaltyId']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete_penalty">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
