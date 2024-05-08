<?php
// // Include the database connection file
include "header.php";
include 'navigation.php';
// session_start();
// include '../connection.php';

// Function to fetch user's loans
function getUserLoans($conn, $m_id) {
    $select_query = "SELECT * FROM loans WHERE status='approved' and  m_id = ?";
    $stmt = $conn->prepare($select_query);
    $stmt->bindParam(1, $m_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if the user is logged in
if (($_SESSION['user']) && ($_SESSION['user']['id'])) {
    $m_id = $_SESSION['user']['id']; // Get user ID from session

    // Fetch user's loans
    $loans = getUserLoans($conn, $m_id);

    // Display user's loans
    if (!empty($loans)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Loans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
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
            justify-content: center;
            margin-top: 20px;
        }
        /* Your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($loans) && !empty($loans)) { ?>
            <h2>Your Loans</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Amount</th>
                        <th>Interest Rate</th>
                        <th>Repayment</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan) {
                        // Calculate repayment
                        $repayment = $loan['loan_amount'] * ($loan['interest_rate'] / 100);
                        // Calculate total
                        $total = $loan['loan_amount'] + $repayment;
                    ?>
                    <tr>
                        <td><?php echo $loan['LoanId']; ?></td>
                        <td><?php echo $loan['loan_amount']; ?></td>
                        <td><?php echo $loan['interest_rate']; ?>%</td>
                        <td><?php echo $repayment; ?></td>
                        <td><?php echo $total; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="btn-container">
                <a href="pay_loan.php" class="btn btn-primary">Go back to pay your loan</a>
            </div>
        <?php } else {
            echo "<p>No loans found.</p>";
        } ?>
    </div>
</body>
</html>


<?php
    } else {
        echo "<div class='container'><p>No loans found.</p></div>";
    }
} else {
    // User is not logged in
    echo "<div class='container'><p>You must be logged in to view your loans.</p></div>";
}
?>
