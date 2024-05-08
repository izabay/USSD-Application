<?php
include_once 'header.php';
include_once 'navigation.php';
// Function to calculate financial statistics
function calculateFinancialStatistics($conn) {
    // Calculate total loan amount
    $total_loan_amount_query = "SELECT SUM(loan_amount) AS total_loan_amount FROM loans";
    $total_loan_amount_statement = $conn->query($total_loan_amount_query);
    $total_loan_amount_row = $total_loan_amount_statement->fetch(PDO::FETCH_ASSOC);
    $total_loan_amount = $total_loan_amount_row['total_loan_amount'];

    // Calculate total repayment amount
    $total_repayment_query = "SELECT SUM(repayment) AS total_repayment FROM loans WHERE status = 'approved'";
    $total_repayment_statement = $conn->query($total_repayment_query);
    $total_repayment_row = $total_repayment_statement->fetch(PDO::FETCH_ASSOC);
    $total_repayment = $total_repayment_row['total_repayment'];

    // Calculate total profit (repayment - loan amount)
    $total_profit = $total_repayment - $total_loan_amount;

    // Return the calculated statistics
    return [
        'total_loan_amount' => $total_loan_amount,
        'total_repayment' => $total_repayment,
        'total_profit' => $total_profit
    ];
}

// // Start the session
// session_start();

// Check if the user is logged in and is an admin
if ($_SESSION['userid']) {
    // Calculate financial statistics
    $financial_statistics = calculateFinancialStatistics($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statistics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .container {
            margin-top: 80px;
            margin-left: 200px;
            width: 80%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Financial Statistics</h2>
        <table class="table">
            <tr>
                <td>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Loan Amount</h5>
                            <p class="card-text">$<?php echo number_format($financial_statistics['total_loan_amount'], 2); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Repayment</h5>
                            <p class="card-text">$<?php echo number_format($financial_statistics['total_repayment'], 2); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Profit</h5>
                            <p class="card-text">$<?php echo number_format($financial_statistics['total_profit'], 2); ?></p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>

<?php
} else {
    // Redirect to login page if user is not logged in or not an admin
    echo "You must be logged in as an admin to access this page.";
    // header("location:../members/login.php");
}
?>
