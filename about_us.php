<?php include 'header.php'; ?>
<?php include 'navigation.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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

        .feature {
            background-color: #f4f4f4;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .feature h3 {
            color: #4CAF50;
        }

        .feature p {
            color: #666;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>About Us</h1>
        <p>Welcome to our website! We are dedicated to providing high-quality products/services and exceptional customer experience. Here's what you need to know about us:</p>
        
        <h2>Our Mission</h2>
        <p>Our mission is to [insert your mission statement here]. We strive to [describe your goals and objectives].</p>
        
        <h2>Our Story</h2>
        <p>We started our journey in [insert year] with the vision of [describe your initial motivation or inspiration]. Since then, we have been [briefly describe your growth, achievements, and milestones].</p>
        
        <h2>Why Choose Us?</h2>
        <p>Here are some reasons why you should choose us:</p>
        <div class="feature">
            <h3>Quality Products/Services</h3>
            <p>We are committed to delivering the highest quality products/services to our customers.</p>
        </div>
        <div class="feature">
            <h3>Customer Satisfaction</h3>
            <p>Customer satisfaction is our top priority. We value your feedback and continuously strive to improve our offerings.</p>
        </div>
        <div class="feature">
            <h3>Expert Team</h3>
            <p>Our team consists of experienced professionals who are passionate about what they do. They are dedicated to providing you with the best solutions.</p>
        </div>
        
        <h2>Contact Us</h2>
        <p>If you have any questions or inquiries, please feel free to contact us at [insert contact information]. We look forward to hearing from you!</p>
    </div>
    <?php include 'footer.php';?>
</body>
</html>

<?php
// Include the database connection file
include 'connection.php';

try {
    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Perform any database operations here if needed

} catch(PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
}
?>
