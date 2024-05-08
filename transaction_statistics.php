<?php
include 'header.php';
include 'navigation.php';

// Fetch Saving Statistics
$select_query = "SELECT SUM(amount) AS total_amount, MONTH(SavingDate) AS month FROM saving GROUP BY MONTH(SavingDate)";
$statement = $conn->query($select_query);
$saving_statistics = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch Saving Transactions
$select_transactions_query = "SELECT saving.*, members.names FROM saving JOIN members ON saving.m_id = members.MemberId";
$statement_transactions = $conn->query($select_transactions_query);
$saving_transactions = $statement_transactions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saving Statistics and Transactions</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 90px;
            margin-left: 240px;
            width: 60%;
        }

        .transaction {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .transaction h4 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .transaction p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    

    <div class="container">
        <h2>Saving Statistics</h2>
        <canvas id="savingChart" width="80" height="40"></canvas>
        <h2>All Saving Transactions</h2>
        <?php
        if ($saving_transactions) {
            foreach ($saving_transactions as $row) {
                echo '<div class="transaction">';
                echo '<h4>' . $row['names'] . '</h4>';
                echo '<p><strong>Transaction ID:</strong> ' . $row['SavingId'] . '</p>';
                echo '<p><strong>Amount:</strong> ' . $row['amount'] . '</p>';
                echo '<p><strong>Saving Date:</strong> ' . $row['SavingDate'] . '</p>';
                echo '<p><strong>Shares:</strong> ' . $row['shares'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No saving transactions found.</p>';
        }
        ?>
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('savingChart').getContext('2d');

        // Create arrays for labels and data
        var months = <?php echo json_encode(array_column($saving_statistics, 'month')); ?>;
        var totalAmounts = <?php echo json_encode(array_column($saving_statistics, 'total_amount')); ?>;

        // Create a new chart instance
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Amount Saved',
                    data: totalAmounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
<?php include 'footer.php'; ?>
</html>
