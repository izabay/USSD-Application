<?php
// Include the database connection file
include "../connection.php";

// Function to fetch rejected loan requests
function getRejectedLoanRequests($conn) {
    $select_query = "SELECT * FROM loans WHERE status = 'rejected'";
    $statement = $conn->query($select_query);
    
    $loan_requests = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $loan_requests[] = $row;
    }
    return $loan_requests;
}

// Start the session
session_start();

// Check if the user is logged in and is an admin
if ($_SESSION['user']) {
    // Fetch rejected loan requests
    $rejected_loan_requests = getRejectedLoanRequests($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejected Loans</title>
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
        .rejected {
            background-color: #ffcdd2; /* Light red background for rejected loans */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rejected Loans</h2>
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
                <?php foreach ($rejected_loan_requests as $loan) { ?>
                    <tr class="rejected">
                        <td><?php echo $loan['id']; ?></td>
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
    <h1 style="text-align: center;" ><a href="../index.php" class="btn btn-primary" >Go back to Dashboard</a></h1>
    
</body>
</html>

<?php
} else {
    // Redirect to login page if user is not logged in or not an admin
    echo "You must be logged in as an admin to access this page.";
    // header("location:../members/login.php");
}
?>
