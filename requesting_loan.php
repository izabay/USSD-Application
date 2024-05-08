<?php
// Include the database connection file
include "header.php";
include 'navigation.php';

// Function to check if a user has pending or approved loans
function hasPendingOrApprovedLoans($conn, $m_id) {
    $select_query = "SELECT COUNT(*) AS count FROM loans WHERE m_id = ? AND (status = 'pending' OR status = 'approved')";
    $stmt = $conn->prepare($select_query);
    $stmt->bindParam(1, $m_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'] > 0;
}

function requestLoan($conn, $m_id, $loan_amount, $interest_rate) {
    // Calculate payment deadline date (30 days from the issued date)
    $issued_date = date("Y-m-d H:i:s"); // Include date and time
    $payment_deadline_date = date('Y-m-d H:i:s', strtotime($issued_date . ' + 30 days')); // Include date and time

    // Calculate repayment (loan_amount * interest_rate)
    $repayment = $loan_amount * ($interest_rate / 100);

    try {
        // Prepare and execute the loan request query using prepared statements
        $insert_query = "INSERT INTO loans (m_id, loan_amount, status, repayment, interest_rate, issued_date, payment_deadline_date) VALUES (?, ?, 'pending', ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(1, $m_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $loan_amount, PDO::PARAM_INT);
        $stmt->bindParam(3, $repayment, PDO::PARAM_INT);
        $stmt->bindParam(4, $interest_rate, PDO::PARAM_INT);
        $stmt->bindParam(5, $issued_date, PDO::PARAM_STR);
        $stmt->bindParam(6, $payment_deadline_date, PDO::PARAM_STR);
        $execute_query = $stmt->execute();

        if ($execute_query) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Process loan request
if (isset($_POST['submit_loan_request'])) {

    if (isset($_SESSION['user'])) {
        echo $_SESSION['user']['MemberId'];
        echo $_SESSION['user']['names'];   
        $loan_amount = $_POST['loan_amount'];
        $interest_rate = 5; // 5% interest rate
        

        // Check if the user has pending or approved loans
        if (hasPendingOrApprovedLoans($conn, $m_id)) {
            $message = "You already have a pending or approved loan. Please wait for admin approval or pay off your existing loan before requesting a new one.";
        } else {
            // Request the loan
            if (requestLoan($conn, $m_id, $loan_amount, $interest_rate)) {
                // Loan request successful
                $message = "Loan request submitted successfully. Please wait for admin approval.";
            } else {
                // Loan request failed
                $message = "Failed to submit loan request. Please try again later.";
            }
        }
    } else {
        // User is not logged in
        $message = "You must be logged in to request a loan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Request Form</title>
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
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Loan Request Form</h2>
        <?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
        <form method="post">
            <div class="mb-3">
                <label for="loan_amount" class="form-label">Loan Amount</label>
                <input type="number" class="form-control" id="loan_amount" name="loan_amount" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary" name="submit_loan_request">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="goBack()">Cancel</button>
            </div>
        </form>
    </div>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript to go back -->
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
