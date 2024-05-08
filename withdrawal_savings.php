<?php

include "header.php";
include 'navigation.php';

// Check if the user is logged in
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
    
    try {
     
        function hasActiveLoan($conn, $user_id) {
            $select_query = "SELECT * FROM loans WHERE m_id = :user_id AND status = 'approved'";
            $statement = $conn->prepare($select_query);
            $statement->bindParam(':user_id', $user_id);
            $statement->execute();
            return $statement->rowCount() > 0;
        }
    
        if (hasActiveLoan($conn, $user_id)) {
            $message = "You must pay off your existing loan before making a withdrawal.";
        } else {
 
            $select_query = "SELECT * FROM balances WHERE m_id = :user_id";
            $statement = $conn->prepare($select_query);
            $statement->bindParam(':user_id', $user_id);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $balance_amount = $row['balance'];

                if ($balance_amount >= 100) {

                    if (isset($_POST['submit_withdrawal'])) {
                        $withdrawal_amount = $_POST['withdrawal_amount'];
                        if ($withdrawal_amount <=$balance_amount) {

                            $new_savings_amount = $balance_amount - $withdrawal_amount;
                            $update_query = "UPDATE balances SET balance = :new_amount WHERE m_id = :user_id";
                            $update_statement = $conn->prepare($update_query);
                            $update_statement->bindParam(':new_amount', $new_savings_amount);
                            $update_statement->bindParam(':user_id', $user_id);

                            $my_balance =$update_statement->execute();
                            if ($my_balance) {

                                $transaction_type = 'withdrawal';
                                $insert_transaction_query = "INSERT INTO transactions (m_id, amount, transaction_type) VALUES (:m_id, :amount, :transaction_type)";
                                $transaction_statement = $conn->prepare($insert_transaction_query);
                                $transaction_statement->bindParam(':m_id', $user_id);
                                $transaction_statement->bindParam(':amount', $withdrawal_amount);
                                $transaction_statement->bindParam(':transaction_type', $transaction_type);
                                $transaction_statement->execute();
                                
                                // Update total savings amount
                                // $update_total_savings_query = "UPDATE balances SET balance = (SELECT SUM(amount) FROM saving WHERE m_id = :user_id) WHERE m_id = :user_id";
                                // $update_total_savings_statement = $conn->prepare($update_total_savings_query);
                                // $update_total_savings_statement->bindParam(':user_id', $user_id);
                                // $update_total_savings_statement->execute();
                                
                                $message = "Withdrawal successful. Your new savings balance is $my_balance";
                            } else {
                                $message = "Failed to process withdrawal. Please try again later.";
                            }
                        } else {
                            $message = "Insufficient savings balance.";
                        }
                    }
                } else {
                    $message = "You do not have any savings to withdraw.";
                }
            } else {
                $message = "Error fetching balance information.";
            }
        }
    } catch (PDOException $e) {
        // Handle database errors
        $message = "Database Error: " . $e->getMessage();
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
    <title>Withdraw Savings</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            background-color: #fff;
        }
        .btn-container {
            display: flex;
            gap: 10px;
        }
        /* Your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Withdraw Savings</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
        <form method="post">
            <div class="mb-3">
                <label for="withdrawal_amount" class="form-label">Withdrawal Amount</label>
                <input type="number" class="form-control" id="withdrawal_amount" name="withdrawal_amount" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_withdrawal">Withdraw</button>
        </form>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
