<?php
session_start();
// Include the database connection file
include "../connection.php";

// Function to fetch pending loan requests
function getPendingLoanRequests($conn) {
    $select_query = "SELECT * FROM loans WHERE status = 'pending'";
    $statement = $conn->query($select_query);
    
    $loan_requests = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $loan_requests[] = $row;
    }
    return $loan_requests;
}

// Start the session


// Check if the user is logged in and is an admin
if ($_SESSION['adminid']) {
    // Fetch pending loan requests
    $loan_requests = getPendingLoanRequests($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Loans</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Loans</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Member ID</th>
                    <th>Loan Amount</th>
                    <th>Status</th>
                    <th>Interest Rate</th>
                    <th>Repayment</th>
                    <th>Issued Date</th>
                    <th>Payment Deadline Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_requests as $loan) { ?>
                    <tr>
                        <td><?php echo $loan['LoanId']; ?></td>
                        <td><?php echo $loan['m_id']; ?></td>
                        <td><?php echo $loan['loan_amount']; ?></td>
                        <td><?php echo $loan['status']; ?></td>
                        <td><?php echo $loan['interest_rate']; ?></td>
                        <td><?php echo $loan['repayment']; ?></td>
                        <td><?php echo $loan['issued_date']; ?></td>
                        <td><?php echo $loan['payment_deadline_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <a href="../index.php" class="btn btn-primary">Go back to Dashboard</a>
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
