<?php include 'header.php'; ?>
<?php include 'navigation.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <style>
    
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 90px;
        }

        h1 {
            color: #333;
            margin-top: 20px;
        }

        h2 {
            color: #4CAF50;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .service {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .service h2 {
            margin-top: 0;
            color: #333;
        }

        .service p {
            margin-bottom: 10px;
            color: #555;
        }

        .service ul {
            padding-left: 20px;
            color: #666;
        }


    </style>
</head>
<body>

    <!-- Main Content -->
    <div class="container">
        <h1>Our Services</h1>

        <!-- Savings System -->
        <div class="service">
            <h2>Savings System</h2>
            <p>We offer a comprehensive savings system designed to help you achieve your financial goals.</p>
            <ul>
                <li>Flexible Savings Plans: Choose from a variety of savings plans tailored to your needs.</li>
                <li>Competitive Interest Rates: Earn competitive interest rates on your savings.</li>
                <li>Secure Platform: Your savings are safe and secure with us.</li>
            </ul>
        </div>

        <!-- Membership Opportunities -->
        <div class="service">
            <h2>Membership Opportunities</h2>
            <p>Join our community and enjoy exclusive benefits as a member:</p>
            <ul>
                <li>Access to Premium Services: Unlock access to premium services and features.</li>
                <li>Member Discounts: Enjoy special discounts and offers available only to members.</li>
                <li>Community Engagement: Connect with like-minded individuals and participate in community events.</li>
            </ul>
        </div>

        <p>Contact us today to learn more about our services and how you can get started!</p>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
   

</body>
</html>
