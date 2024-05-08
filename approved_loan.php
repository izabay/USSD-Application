<?php
    
    include "../connection.php";

    function getApprovedLoanRequests($conn) {
        $select_query = "SELECT * FROM loans WHERE status = 'approved'";
        $statement = $conn->query($select_query);
        
        $loan_requests = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $loan_requests[] = $row;
        }
        return $loan_requests;
        session_start();
    }
    if ($_SESSION['userid'] or $_SESSION['adminid']) {

        
        try {        
            $conn->beginTransaction();

            $approved_loan_requests = getApprovedLoanRequests($conn);

            foreach ($approved_loan_requests as $loan) {        

                $select_balance_query = "SELECT balance FROM balances WHERE m_id = :mid";
                $stmt = $conn->prepare($select_balance_query);
                $stmt->bindParam(':mid', $loan['m_id']);
                $stmt->execute();
                $existing_balance = $stmt->fetchColumn();
                $updated_balance = $existing_balance + $loan['loan_amount'];

                $update_balance_query = "UPDATE balances SET balance = ? WHERE m_id = ?";
                $stmt = $conn->prepare($update_balance_query);
                $stmt->execute([$updated_balance, $loan['m_id']]);
            }

            $conn->commit();

            ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Approved Loans</title>
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
                .approved {
                    background-color: #c8e6c9; /* Light green background for approved loans */
                }
                .rejected {
                    background-color: #ffcdd2; /* Light red background for rejected loans */
                }
                /* Custom heading style */
                h2 {
                    margin-bottom: 20px;
                    padding: 10px;
                    background-color: #007bff; /* Blue background color */
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Approved Loans</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr >
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
                        <?php foreach ($approved_loan_requests as $loan) { ?>
                            <tr class="<?php echo $loan['status'] == 'approved' ? 'approved' : 'rejected'; ?>">
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
            <h1 style="text-align: center;" ><a href="../index.php" class="btn btn-primary" >Go back to Dashboard</a></h1>
        </body>
        </html>
        <?php
    } catch (PDOException $e) {
        // Rollback the transaction and display error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect to login page if user is not logged in or not an admin
    echo "You must be logged in as an admin to access this page.";
    header("location:../members/login.php");
}
?>
