<?php
session_start();
include '../connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        nav {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        nav ul {
            list-style-type: none;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Navigation bar -->
    <nav>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="../members/select-all.php">Manage Users</a></li>
            <li><a href="../saving/select_all_money.php">Manage Savings</a></li>
            <li><a href="../loans/applying_loan.php">Manage Loans</a></li>
            <li><a href="../penalities/penallty_status.php">Manage Penalties</a></li>
            <li><a href="../saving/interest_calculation.php">Manage Interest Rates</a></li>
            <li><a href="../members/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="container">
        <h1>Welcome to the Admin Panel</h1>
        <!-- Add various sections for managing users, savings, loans, penalties, interest rates, etc. -->
        <section>
            <h2>Manage Users</h2>
            <!-- Add user management functionality here (e.g., tables, forms) -->
        </section>
        <section>
            <h2>Manage Savings</h2>
            <!-- Add savings management functionality here (e.g., tables, forms) -->
        </section>
        <section>
            <h2>Manage Loans</h2>
            <!-- Add loan management functionality here (e.g., tables, forms) -->
        </section>
        <section>
            <h2>Manage Penalties</h2>
            <!-- Add penalty management functionality here (e.g., tables, forms) -->
        </section>
        <section>
            <h2>Manage Interest Rates</h2>
            <!-- Add interest rate management functionality here (e.g., tables, forms) -->
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Your Company. All rights reserved.</p>
    </footer>

</body>
</html>
