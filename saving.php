<?php

// Start the session
include 'header.php';
include 'navigation.php';
include '../connection.php';

    if (isset($_SESSION['userid'])) {
        $m_id = $_SESSION['userid'];
        if(isset($_SESSION['usernames'])){
            $membername=$_SESSION['usernames'];
        }

        $stmt = $conn->prepare("SELECT * FROM members WHERE MemberId = :user_id");
        $stmt->bindParam(':user_id', $m_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($_POST['update'])) {  
            $amount = $_POST['amount'];
            $transaction_type = 'deposit';
            $share = 2000;
            $shares = $amount / $share;

            try {
                $conn->beginTransaction();

                $check_balance_query = "SELECT * FROM balances WHERE m_id = :m_id";
                $stmt_check = $conn->prepare($check_balance_query);
                $stmt_check->bindParam(':m_id', $m_id);
                $stmt_check->execute();
                $balance_exists = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if (!$balance_exists) {
                    
                        $insert_balance_query = "INSERT INTO balances (m_id, MemberName, balance) VALUES (:m_id, :member_name, :amount)";
                        $stmt_insert_balance = $conn->prepare($insert_balance_query);
                        $stmt_insert_balance->bindParam(':m_id', $m_id);
                        
                        $stmt_insert_balance->bindParam(':member_name',  $membername); 
                        $stmt_insert_balance->bindParam(':amount', $amount);
                        $stmt_insert_balance->execute();

                        $insert_query_saving = "INSERT INTO saving (m_id, amount, shares, transaction_type) VALUES (:m_id, :amount, :shares, :transaction_type)";
                        $stmt1 = $conn->prepare($insert_query_saving);
                        $stmt1->bindParam(':m_id', $m_id);
                        $stmt1->bindParam(':amount', $amount);
                        $stmt1->bindParam(':shares', $shares);
                        $stmt1->bindParam(':transaction_type', $transaction_type);
                        $stmt1->execute();

                        $conn->commit();

                        echo '<div class="alert alert-success accordion-close">Deposit Successful</div>';

                } else {
                    // Update balance
                    $update_query = "UPDATE balances SET balance = balance + :amount WHERE m_id = :m_id";
                    $stmt2 = $conn->prepare($update_query);
                    $stmt2->bindParam(':amount', $amount);
                    $stmt2->bindParam(':m_id', $m_id);
                    $stmt2->execute();
                                // Insert into saving table
                    $insert_query_saving = "INSERT INTO saving (m_id, amount, shares, transaction_type) VALUES (:m_id, :amount, :shares, :transaction_type)";
                    $stmt1 = $conn->prepare($insert_query_saving);
                    $stmt1->bindParam(':m_id', $m_id);
                    $stmt1->bindParam(':amount', $amount);
                    $stmt1->bindParam(':shares', $shares);
                    $stmt1->bindParam(':transaction_type', $transaction_type);
                    $stmt1->execute();

                    $conn->commit();

                    echo '<div class="alert alert-success accordion-close">Deposit Successful</div>';
                    echo  $membername;
                }


                
            } catch (PDOException $e) {
                $conn->rollback();
                echo "Failed to insert data: " . $e->getMessage();
            }
        }
    } else {     
            header("Location: ../members/login.php");
            exit;
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Savings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px;
        }
        .form-container {
            margin: 50px auto;
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <form method="post" id="saving-form">
            <div class="mb-3">                
                
                <label for="amount">Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount" required placeholder="Enter amount in FRW">
            </div>
            <button type="submit" class="btn btn-primary" name="update">Save Now</button>
            
        </form>
        <div id="feedback-message" class="mt-3"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('saving-form').addEventListener('submit', function(event) {
        var amountInput = document.getElementById('amount');
        var amount = parseFloat(amountInput.value);
        if (isNaN(amount) || amount <= 0) {
            event.preventDefault();
            amountInput.classList.add('is-invalid');
            document.getElementById('feedback-message').innerHTML = '<div class="alert alert-danger">Please enter a valid amount.</div>';
        } else {
            amountInput.classList.remove('is-invalid');
            document.getElementById('feedback-message').innerHTML = '';
        }
    });
</script>
</body>
</html>
