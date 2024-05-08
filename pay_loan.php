<?php
// // Include the database connection file
include "header.php";
include 'navigation.php';
// session_start();
// include '../connection.php';

// Function to retrieve pending or approved loans for a specific user
function getPendingOrApprovedLoansByUserId($conn, $m_id) {
    $select_query = "SELECT * FROM loans WHERE m_id = ? AND (status = 'pending' OR status = 'approved')";
    $stmt = $conn->prepare($select_query);
    $stmt->execute([$m_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to calculate the total amount to pay including interest
function calculateTotalAmountToPay($loans, $payment_amount) {
    $total_amount = $payment_amount;
    foreach ($loans as $loan) {
        $total_amount += $loan['loan_amount'] * ($loan['interest_rate'] / 100);
    }
    return $total_amount;
}

// Process loan payment
if (isset($_POST['submit_payment'])) {
    // Retrieve user ID from session
    $m_id = $_SESSION['user']['id'];

    // Retrieve pending or unpaid loans for the specified user ID
    $loans = getPendingOrApprovedLoansByUserId($conn, $m_id);

    // Check if the user has any loan to pay
    if (empty($loans)) {
        $message = "You don't have any loan to pay.";
    } else {
        // Calculate the total amount to pay including interest
        $payment_amount = $_POST['payment_amount'];
        $total_amount_to_pay = calculateTotalAmountToPay($loans, $payment_amount);

        // Deduct payment amount directly from the user's balance
        $update_balance_query = "UPDATE balances SET balance = balance - :payment_amount WHERE m_id = :m_id";
        $stmt_balance = $conn->prepare($update_balance_query);
        $stmt_balance->bindParam(':payment_amount', $payment_amount);
        $stmt_balance->bindParam(':m_id', $m_id);
        $stmt_balance->execute();

        // Update loan status to 'paid' in the database
        foreach ($loans as $loan) {
            $update_loan_query = "UPDATE loans SET status = 'paid' WHERE LoanId = :loan_id";
            $stmt_loan = $conn->prepare($update_loan_query);
            $stmt_loan->bindParam(':loan_id', $loan['LoanId'], PDO::PARAM_INT);
            $stmt_loan->execute();
        }

        // Display a message confirming the payment
        $message = "Payment of $" . $payment_amount . " successfully processed. Total amount paid including interest: $" . $total_amount_to_pay;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Payment Form</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS Styles -->
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
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        /* Your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Loan Payment Form</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
        <form method="post">
            <div class="form-group">
                <label for="payment_amount">Payment Amount:</label>
                <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_payment">Submit Payment</button>
            <!-- Add button to check user's loans -->
            <a href="view_loan.php" class="btn btn-secondary">View My Loans</a>
        </form>
        <?php if (isset($loans) && !empty($loans)) { ?>
            <h4>Your Loans:</h4>
            <ul>
                <?php foreach ($loans as $loan) { ?>
                    <li>Loan ID: <?php echo $loan['LoanId']; ?> - Amount: <?php echo $loan['loan_amount']; ?> - Interest Rate: <?php echo $loan['interest_rate']; ?>%</li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
