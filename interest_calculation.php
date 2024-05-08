<?php
// Include the database connection file
include 'header.php';
include 'navigation.php';

if (isset($_SESSION['userid']) or $_SESSION['adminid']){

$loans = [];

try {
    // Prepare and execute the SQL query to fetch loan details
    $select_query = "SELECT * FROM loans";
    $statement = $conn->prepare($select_query);
    $statement->execute();
    
    // Fetch all rows as associative arrays
    $loans = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to fetch data: " . $e->getMessage();
}

// Function to calculate interest for each loan
function calculateInterest($loan) {
    // Assuming interest is calculated monthly
    $months = 12;
    
    // Calculate the interest amount
    $interest_amount = ($loan['loan_amount'] * $loan['interest_rate'] * $months) / 100;
    
    return $interest_amount;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Interest Calculation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
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
        .no-loans {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Loan Interest Calculation</h2>
        <?php if (!empty($loans)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Loan Amount</th>
                        <th>Interest Rate (%)</th>
                        <th>Repayment</th>
                        <th>Interest Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan) : ?>
                        <tr>
                            <td><?php echo $loan['LoanId']; ?></td>
                            <td><?php echo $loan['m_id']; ?></td>
                            <td><?php echo $loan['loan_amount']; ?></td>
                            <td><?php echo $loan['interest_rate']; ?></td>
                            <td><?php echo $loan['repayment']; ?></td>
                            <td><?php echo calculateInterest($loan); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="no-loans">No loans found.</p>
        <?php endif; ?>
    </div>
</body>
<?php include 'footer.php'; ?>
</html>

<?php
}else{

    header('location: ../member/login.php');
}
?>