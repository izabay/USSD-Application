<?php
include_once 'header.php';
include_once 'navigation.php';

// Check if the form is submitted
if (isset($_POST['apply_loan'])) {
    $user_id = 1; // Assuming the user ID is hardcoded for demonstration
    
    // Retrieve user's savings
    $query_savings = "SELECT * FROM saving WHERE m_id = :user_id";
    $statement_savings = $conn->prepare($query_savings);
    $statement_savings->bindParam(':user_id', $user_id);
    $statement_savings->execute();
    
    if ($statement_savings->rowCount() == 1) {
        $row_savings = $statement_savings->fetch(PDO::FETCH_ASSOC);
        $savings = $row_savings['amount'];

        $loan_amount = $_POST['loan_amount'];
        
        if ($loan_amount > $savings) {
            $error = "Requested loan amount exceeds your savings.";
        } else {
            $status = "Pending"; 
            $repayment = $_POST['repayment'];
            $interest_rate = $_POST['interest_rate'];
            $issued_date = date('Y-m-d H:i:s'); 
            
            // Insert loan application
            $query_insert_loan = "INSERT INTO loan_applications (m_id, loan_amount, status, repayment, interest_rate, issued_date) 
                                  VALUES (:user_id, :loan_amount, :status, :repayment, :interest_rate, :issued_date)";
            $statement_insert_loan = $conn->prepare($query_insert_loan);
            $statement_insert_loan->bindParam(':user_id', $user_id);
            $statement_insert_loan->bindParam(':loan_amount', $loan_amount);
            $statement_insert_loan->bindParam(':status', $status);
            $statement_insert_loan->bindParam(':repayment', $repayment);
            $statement_insert_loan->bindParam(':interest_rate', $interest_rate);
            $statement_insert_loan->bindParam(':issued_date', $issued_date);
            if ($statement_insert_loan->execute()) {
                $success = "Loan application submitted successfully.";
            } else {
                $error = "Failed to submit loan application.";
            }
        }
    } else {
        $error = "Failed to retrieve user's savings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }

        .container {
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="post">
                <div class="mb-3">
                    <label>Loan Amount</label>
                    <input type="number" class="form-control" name="loan_amount" required placeholder="Enter loan amount">
                </div>
                <div class="mb-3">
                    <label>Repayment</label>
                    <input type="number" class="form-control" name="repayment" required placeholder="Enter repayment">
                </div>
                <div class="mb-3">
                    <label>Interest Rate</label>
                    <input type="number" class="form-control" name="interest_rate" required placeholder="Enter interest rate">
                </div>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php elseif (isset($success)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" name="apply_loan">Apply Loan</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>
