<?php
include 'header.php';
include 'navigation.php';
include "../connection.php";


function calculateUserActivityStatistics($conn) {
  
    $total_users_query = "SELECT COUNT(*) AS total_users FROM members";
    $total_users_statement = $conn->query($total_users_query);
    $total_users_row = $total_users_statement->fetch(PDO::FETCH_ASSOC);
    $total_users = $total_users_row['total_users'];

    $total_loans_query = "SELECT COUNT(*) AS total_loans FROM loans";
    $total_loans_statement = $conn->query($total_loans_query);
    $total_loans_row = $total_loans_statement->fetch(PDO::FETCH_ASSOC);
    $total_loans = $total_loans_row['total_loans'];

    $average_loans_per_user = $total_loans / $total_users;

    return [
        'total_users' => $total_users,
        'total_loans' => $total_loans,
        'average_loans_per_user' => $average_loans_per_user
    ];
}

if (isset($_SESSION['userid']) or $_SESSION['adminid']) {

    $user_activity_statistics = calculateUserActivityStatistics($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Statistics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
   
        .container {
            margin-top: 50px;
            margin-left: 200px;
            width: 80%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Activity Statistics</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $user_activity_statistics['total_users']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Loans Requested</h5>
                        <p class="card-text"><?php echo $user_activity_statistics['total_loans']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Average Loans Per User</h5>
                        <p class="card-text"><?php echo number_format($user_activity_statistics['average_loans_per_user'], 2); ?></p>
                    </div>
                </div>
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
    
    echo "You must be logged in as an admin to access this page.";
   
}
?>
