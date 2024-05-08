<?php 
include 'header.php'; 
include 'navigation.php'; 

// Initialize variables to store form data
$name = $email = $subject = $message = '';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data and sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    try {
        // Prepare SQL statement with placeholders to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO contact_us (MemberName, ContactEmail, Subject, Message) VALUES (:name, :email, :subject, :message)");
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        // Execute the statement
        $stmt->execute();
     


        // Redirect to index page after successful submission
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        // Log error
        error_log('Error inserting data into database: ' . $e->getMessage(), 0);
        // Display error message to the user
        echo "<script>alert('An error occurred while processing your request. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 400px; /* Reduce max-width for smaller container */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            color: #555;
            margin-bottom: 20px;
            text-align: center;
        }

        .contact-form {
            text-align: center;
        }

        .contact-form form {
            max-width: 300px; /* Reduce max-width for smaller form */
            margin: 0 auto;
        }

        .contact-form label {
            display: block;
            margin-bottom: 5px; /* Reduce margin for labels */
            color: #333;
        }

        .contact-form input[type="text"],
        .contact-form input[type="email"],
        .contact-form textarea {
            width: calc(100% - 20px); /* Adjust width of input fields */
            padding: 8px;
            margin-bottom: 15px; /* Reduce margin between input fields */
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .contact-form textarea {
            resize: vertical;
        }

        .contact-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .contact-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <p>Have a question or need assistance? Please feel free to reach out to us using the contact form below:</p>
        
        <div class="contact-form">
            <form method="post">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name" value="<?php echo $name; ?>">

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email" value="<?php echo $email; ?>">

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required placeholder="Enter subject" value="<?php echo $subject; ?>">

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required placeholder="Enter your message"><?php echo $message; ?></textarea>

                <button type="submit" name="submit">Send Message</button>
            </form>
        </div>
    </div>  
   
</body>
</html>
