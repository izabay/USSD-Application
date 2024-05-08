<?php
include 'header.php';
include 'navigation.php';

// Check if the user is logged in and user ID is set
if (isset($_SESSION['userid']) or isset($_SESSION['adminid'])) {
    $user_id = $_SESSION['userid'];
    $admin_id=$_SESSION['adminid'];

    // Check if the user has any penalties
    $select_penalty_query = "SELECT * FROM penalties WHERE m_id = :user_id";
    $stmt_penalty = $conn->prepare($select_penalty_query);
    $stmt_penalty->bindParam(':user_id', $user_id);
    $stmt_penalty->execute();

    if ($stmt_penalty) {
        if ($stmt_penalty->rowCount() > 0) {
            
            $penalty_row = $stmt_penalty->fetch(PDO::FETCH_ASSOC);
            $penalty_id = $penalty_row['PenaltyId'];
            $penalty_amount = $penalty_row['amount'];

            $select_savings_query = "SELECT amount FROM saving WHERE m_id = :user_id";
            $stmt_savings = $conn->prepare($select_savings_query);
            $stmt_savings->bindParam(':user_id', $user_id);
            $stmt_savings->execute();

            if ($stmt_savings) {
                $savings_row = $stmt_savings->fetchAll(PDO::FETCH_ASSOC);
                $savings_amount = $savings_row['amount'];
               
                if ($savings_amount >= $penalty_amount) {                    
                    if (isset($_POST['pay_penalty'])) {                        
                        $new_savings_amount = $savings_amount - $penalty_amount;

                        $conn->beginTransaction();

                        try {
                            // Update savings table with new savings amount
                            $update_savings_query = "UPDATE saving SET amount = :new_savings_amount WHERE m_id = :user_id";
                            $stmt_update_savings = $conn->prepare($update_savings_query);
                            $stmt_update_savings->bindParam(':new_savings_amount', $new_savings_amount);
                            $stmt_update_savings->bindParam(':user_id', $user_id);
                            $stmt_update_savings->execute();

                            // Remove penalty entry from penalties table
                            $delete_penalty_query = "DELETE FROM penalities WHERE id = :penalty_id";
                            $stmt_delete_penalty = $conn->prepare($delete_penalty_query);
                            $stmt_delete_penalty->bindParam(':penalty_id', $penalty_id);
                            $stmt_delete_penalty->execute();

                            // Commit the transaction
                            $conn->commit();

                            $message = "Penalty paid successfully. Your new savings balance is $new_savings_amount.";
                        } catch (PDOException $e) {
                            // Rollback the transaction on error
                            $conn->rollback();
                            $message = "Failed to process penalty payment. Please try again later.";
                        }
                    }
                } else {
                    $message = "You do not have sufficient savings to pay the penalty.";
                }
            } else {
                $message = "Failed to retrieve savings amount. Please try again later.";
            }
        } else {
            $message = "You do not have any pending penalties to pay.";
        }
    } else {
        $message = "Failed to retrieve penalty information. Please try again later.";
    }
} else {
    // If user is not logged in or user id is not set, redirect to login page
    header("Location: ../members/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Penalty</title>
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
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pay Penalty</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
        <form method="post">
            <button type="submit" class="btn btn-primary" name="pay_penalty">Pay Penalty</button>
        </form>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
