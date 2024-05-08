<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <style>
        /* Sidebar/Navigation Bar */
        aside {
            background-color: #4CAF50; /* Green background color */
            color: white;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 54px; /* Adjust according to header height */
        }

        aside nav ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        aside nav ul li {
            padding: 10px 0;
            position: relative; /* Required for absolute positioning */
        }

        aside nav ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            text-align: center;
        }

        aside nav ul li a:hover {
            background-color: #555;
        }

        /* Submenu styling */
        .submenu {
            display: none;
            position: absolute;
            top: 0;
            left: 100%; /* Position the submenu to the right of the parent */
            background-color: #4CAF50; /* Background color of submenu */
            min-width: 200px;
            padding: 10px;
            z-index: 1; /* Ensure the submenu is on top */
        }

        aside nav ul li:hover .submenu {
            display: block; /* Show submenu on hover */
        }

        /* Submenu item styling */
        .submenu li {
            padding: 10px 0;
        }

        .submenu li a {
            color: white;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .submenu li a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<aside>
    <nav>
        <ul>
            <!-- Users Subcategories -->
            <li>
                <a href="#">Users</a>
                <ul class="submenu">
                    <li><a href="../members/register.php">Create User Account</a></li>
                    <li><a href="../members/select-all.php">Display All Users</a></li>
                    <li><a href="../members/select-all.php">User Operations</a></li>
                </ul>
            </li>
            <!-- Loans Subcategories -->
            <li>
                <a href="#">Loans</a>
                <ul class="submenu">
                <li><a href="../loans/requesting_loan.php">Request Loans</a></li>
                    <li><a href="../requested_loan.php">Requested Loans</a></li>
                    <li><a href="../pending_loan.php">Pending Loans</a></li>
                    <li><a href="../approved_loan.php">Approved Loans</a></li>
                    <li><a href="../rejected_loan.php">rejected Loans</a></li>
                    <li><a href="../loans/pay_loan.php">pay for Loans</a></li>
                </ul>
            </li>
            <!-- Penalties Subcategories -->
            <li>
                <a href="#">Penalties</a>
                <ul class="submenu">
                    <li><a href="../penalities/pay_penality.php">Pay Penalty</a></li>
                    <li><a href="../penalities/penalty_status.php">Penalty Status</a></li>
                    <li><a href="../penalities/penalty_management.php">Penalty Management</a></li>
                </ul>
            </li>
            <!-- Savings Subcategories -->
            <li>
                <a href="#">Savings</a>
                <ul class="submenu">
                    <li><a href="../saving/saving.php">Deposit Savings</a></li>
                    <li><a href="../saving/withdrawal_savings.php">Withdrawal Savings</a></li>
                    <li><a href="../saving/transaction_statistics.php">Savings Transactions</a></li>
                    <li><a href="../saving/check_balance.php">Check you balance</a></li>
                    <li><a href="../saving/interest_calculation.php">Interest Calculation</a></li>
                </ul>
            </li>
            <!-- Interest Rate -->
            <li><a href="../saving/interest_calculation.php">Interest Rate</a></li>
            <!-- Statistics Subcategories -->
            <li>
                <a href="#">Statistics</a>
                <ul class="submenu">
                    <li><a href="../saving/financial_statistics.php">Financial Statistics</a></li>
                    <li><a href="../members/user_activity_statistics.php">User Activity Statistics</a></li>
                    <li><a href="../members/perfomance_metrics.php">Performance Metrics</a></li>
                    <li><a href="customer_analytics.php">Customer Analytics</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>

</body>
</html>
