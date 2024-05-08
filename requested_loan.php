<?php
include_once 'header.php';
include_once 'navigation.php';

// Function to update user balance
function updateUserBalance($conn, $m_id, $loan_amount) {
    try {
        // Fetch current balance
        $select_query = "SELECT balance FROM balances WHERE m_id = :m_id";
        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(':m_id', $m_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calculate new balance
        $new_balance = $result['balance'] + $loan_amount;

        // Update balance in the database
        $update_query = "UPDATE balances SET balance = :new_balance WHERE m_id = :m_id";
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':new_balance', $new_balance);
        $stmt->bindParam(':m_id', $m_id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to fetch pending loan requests
function getPendingLoanRequests($conn) {
    $select_query = "SELECT * FROM loans WHERE status = 'pending'";
    $stmt = $conn->query($select_query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Process loan approval/rejection
if (isset($_POST['approve_loan']) || isset($_POST['reject_loan'])) {
    try {
        $loan_id = $_POST['loan_id'];
        $status = isset($_POST['approve_loan']) ? 'approved' : 'rejected';

        // Update loan status in the database
        $update_query = "UPDATE loans SET status = :status";
        
        // If loan is rejected, update additional fields
        if ($status == 'rejected') {
            $update_query .= ", interest_rate = 0, repayment = 0, payment_deadline_date = NULL";
        }

        $update_query .= " WHERE LoanId = :loan_id";

        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':loan_id', $loan_id);
        $stmt->execute();

        // If loan is approved, update user balance
        if ($status == 'approved') {
            $loan_details_query = "SELECT m_id, loan_amount FROM loans WHERE LoanId = :loan_id";
            $stmt = $conn->prepare($loan_details_query);
            $stmt->bindParam(':loan_id', $loan_id);
            $stmt->execute();
            $loan_details = $stmt->fetch(PDO::FETCH_ASSOC);

            // Update user balance
            updateUserBalance($conn, $loan_details['m_id'], $loan_details['loan_amount']);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Check if the user is logged in and is an admin
if ($_SESSION['adminid'] or $_SESSION['$userid'] ) {
    // Fetch pending loan requests
    $loan_requests = getPendingLoanRequests($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Approve Loans</title>
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
    <!-- Navbar -->
    <?php include "navigation.php"; ?>

    <!-- Page content -->
    <div class="container mt-4">
        <h2>Approve Loans</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Member ID</th>
                    <th>Loan Amount</th>
                    <th>Issued Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_requests as $loan) { ?>
                    <tr>
                        <td><?php echo $loan['LoanId']; ?></td>
                        <td><?php echo $loan['m_id']; ?></td>
                        <td>$<?php echo number_format($loan['loan_amount'], 2); ?></td>
                        <td><?php echo date('M d, Y', strtotime($loan['issued_date'])); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="loan_id" value="<?php echo $loan['LoanId']; ?>">
                                <button type="submit" class="btn btn-success" name="approve_loan">Approve</button>
                                <button type="submit" class="btn btn-danger" name="reject_loan">Reject</button>
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
<?php include 'footer.php'; ?>
</html>

<?php
} else {
    // Redirect to login page if user is not logged in or not an admin
    echo "You must be logged in as an admin to access this page.";
    // header("location:../members/login.php");
}
?>
