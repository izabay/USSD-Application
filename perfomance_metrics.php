<?php
include 'header.php';
include 'navigation.php';

function calculateLoanApprovalPerformance($conn) {

    $avg_approval_time_query = "SELECT AVG(TIMESTAMPDIFF(HOUR, issued_date, NOW())) AS avg_approval_time FROM loans WHERE status = 'approved'";
    $avg_approval_time_statement = $conn->query($avg_approval_time_query);
    $avg_approval_time_row = $avg_approval_time_statement->fetch(PDO::FETCH_ASSOC);
    $avg_approval_time = $avg_approval_time_row['avg_approval_time'];


    return [
        'avg_approval_time' => $avg_approval_time
        ];
    }

    if ($_SESSION['userid'] or $_SESSION['adminid'])  {
         $loan_approval_performance = calculateLoanApprovalPerformance($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Metrics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .container {
            margin-top: 100px;
            margin-left: 300;
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Performance Metrics</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Loan Approval Performance</h5>
                <p class="card-text">Average Approval Time: <?php echo $loan_approval_performance['avg_approval_time']; ?> hours</p>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>

<?php
} else {    
   
    header("location:../members/login.php");
}
?>
