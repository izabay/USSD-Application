<?php
include 'Util.php';
// include 'functionZone.php';
class Menu {
    protected $text;
    
    function __construct($text) {
        $this->text = $text;
    }

    // Main menu for unregistered users
    public function mainMenuUnregistered() {
        $response = "CON Welcome to the Saving system \n";
        $response .= "1. Register\n";
        echo $response;
    }
    public function menuRegister($textArray, $tel, $conn) {
        $level = count($textArray);
        if ($level == 1) {
            echo "CON Enter your names\n";
        } elseif ($level == 2) {
            echo "CON Enter your National ID\n";
        } elseif ($level == 3) {
            echo "CON Enter your email \n";
        } elseif ($level == 4) {
            echo "CON Set your PIN\n";
        } elseif ($level == 5) {
            echo "CON Re-enter PIN\n";
        } else {
            $name = $textArray[1];
            $id = $textArray[2];
            $email = $textArray[3];
            $password = $textArray[4];
            $confirm_password = $textArray[5];
            
            if ($password != $confirm_password) {
                echo "END PINs do not match, Retry";
            } else {
                try {
                    $stmt = $conn->prepare("INSERT INTO members (names, national_identity, telephone, email, password) 
                                            VALUES (:names, :national_identity, :telephone, :email, :password)");
    
                    $stmt->bindValue(':names', $name);
                    $stmt->bindValue(':national_identity', $id);
                    $stmt->bindValue(':telephone', $tel);
                    $stmt->bindValue(':email', $email);
                    $stmt->bindValue(':password', $password);
    
                    if ($stmt->execute()) {
                        echo "END $name, You have successfully registered";
                    } else {
                        echo "END Error registering user.";
                    }
                } catch (PDOException $e) {
                    echo "END Error: " . $e->getMessage();
                }
            }
        }
    }
    
    // Main menu for registered users
    public function mainMenuRegistered() {
        $response = "CON Welcome back to the Saving system\n";
        $response .= "1. Savings\n";
        $response .= "2. loans\n";
        $response .= "3. Penalty\n";
        // $response .= "4. Exit\n";
        echo $response;
    }

    public function Savings($textArray, $phoneNumber, $conn, $userId) {    
        $level = count($textArray);
        if ($level == 1) {
            $response = "CON Savings Choice Enter Your Choice\n";
            $response .= "1. Deposit\n";
            $response .= "2. Withdrawal\n";
            $response .= "3. Check Balance\n";
            // $response .= "4. Saving History\n";
            // $response .= "5. Interest Calculation\n";
            $response .= Util::$GO_BACK . " Back\n";
            $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
            echo $response;
        } elseif ($level == 2) {
            
            switch ($textArray[1]) {
                case 1:
                    echo "Amount should be between 2000 FRW and 20000 FRW\n";
                    echo "CON Enter amount to Deposit\n";
                    break;
                case 2:
                    echo "CON Enter withdrawal amount\n";
                    break;
                case 3:
                    echo "CON Enter your PIN to check your account balance:";
                    break;
                case 4:
                    $this->SavingHistrory($textArray, $conn, $userId);
                    break;
                case 5:
                    // Add your code for case 5
                    break;
                default:
                    echo "END Invalid option, retry";
            }
    
        } elseif ($level == 3) {
             switch ($textArray[1]) {
                case 1:
                    echo "CON Enter Your PIN to Deposit\n";
                    break;
                case 2:
                     echo "CON Enter Your PIN to withdrawal\n";
                    break;
                case 3:
                    $pin = $textArray[2];
                    // Verify the PIN before retrieving the account balance
                    $select_pin_query = "SELECT password FROM members WHERE MemberId = :userId";
                    $stmt_pin = $conn->prepare($select_pin_query);
                    $stmt_pin->bindParam(':userId', $userId);
                    $stmt_pin->execute();
                    $row_pin = $stmt_pin->fetch(PDO::FETCH_ASSOC);
        
                    if ($pin == $row_pin['password']) {
                        // PIN is correct, retrieve the user's balance
                        $select_balance_query = "SELECT balance FROM balances WHERE m_id = :userId";
                        $stmt_balance = $conn->prepare($select_balance_query);
                        $stmt_balance->bindParam(':userId', $userId);
                        $stmt_balance->execute();
                        $row_balance = $stmt_balance->fetch(PDO::FETCH_ASSOC);
        
                        if ($row_balance) {
                            // Display the account balance
                            $balance = $row_balance['balance'];
                            echo "END Your account balance is: $balance";
                        } else {
                            // No balance found for the user
                            echo "END Unable to retrieve account balance.";
                        }
                    } else {
                        // Incorrect PIN
                        echo "END Incorrect PIN. Please try again.";
                    }
                    break;
                case 4:
                    echo "CON Enter your PIN to View Saving Histrory.";
                    break;
                case 5:
                    // Add your code for case 5
                    break;
                default:
                    echo "END Invalid option, retry";
            }
        } elseif ($level == 4) {
            switch ($textArray[1]) {
                case 1:
                    $amount = $textArray[2];
                    $pin = $textArray[3];
            
                    // Check if the amount is within the allowed range
                    if ($amount < 2000 || $amount > 20000) {
                        echo "END Amount should be between 2000 FRW and 20000 FRW. Retry";
                        
                        break;
                    }
    
                    // The user entered both amount and PIN
                    $share = 2000;
                    $shares = $amount / $share;
                    $select_query = "SELECT * FROM members WHERE MemberId = :userId";
                    $stmt_select = $conn->prepare($select_query);
                    $stmt_select->bindParam(':userId', $userId);
                    $stmt_select->execute();
                    $statement_select = $stmt_select->fetch(PDO::FETCH_ASSOC); // Fetch the result
                    //echo "pin: ".$pin." password: ".$statement_select['password'];
                    if ($pin == $statement_select['password']) {
                        try {
                            // Insert record into saving table
                            $insert_saving_query = "INSERT INTO saving (m_id, amount, shares, transaction_type) VALUES (:m_id, :amount, :shares, 'deposit')";
                            $stmt_saving = $conn->prepare($insert_saving_query);
                            $stmt_saving->bindParam(':m_id', $userId); // Bind the correct user ID
                            $stmt_saving->bindParam(':amount', $amount);
                            $stmt_saving->bindParam(':shares', $shares);
                            $stmt_saving->execute();
                            if ($stmt_saving->rowCount()>0) {
                                $select=$conn->prepare("SELECT * FROM balances WHERE m_id='$userId'");
                                $select->execute();
                                if ($select->rowCount()>0) {
                                    $update_balance_query = "UPDATE balances SET balance = balance + :amount WHERE m_id = :m_id";
                                    $stmt_balance = $conn->prepare($update_balance_query);
                                    $stmt_balance->bindParam(':amount', $amount);
                                    $stmt_balance->bindParam(':m_id', $userId); // Bind the correct user ID
                                    $stmt_balance->execute();
                                    if ($stmt_balance->rowCount()) {
                                        echo "END Money saved $amount successfully\n";
                                    }else{
                                        echo "END Money  $amount fail to be saved balance\n";
                                    }
                                }else{
                                    echo "no balance account found";
                                    $insert_balance_query = "INSERT INTO balances (m_id, MemberName, balance) VALUES (:m_id, :member_name, :amount)";
                                    $stmt_insert_balance = $conn->prepare($insert_balance_query);
                                    $stmt_insert_balance->bindParam(':m_id', $userId);
                                    
                                    $stmt_insert_balance->bindParam(':member_name',  $statement_select['names']); 
                                    $stmt_insert_balance->bindParam(':amount', $amount);
                                    $stmt_insert_balance->execute();
                                    if ($stmt_insert_balance->rowCount()>0) {
                                        echo "END Money saved $amount successfully\n";
                                    }else{
                                        echo "END Money  $amount fail to be saved balance\n";
                                    }
                                }
                            }else{
                                echo "END Money  $amount fail to be saved\n";
                            }
                        } catch (Exception $e) {
                            echo "END Error: " . $e->getMessage();
                        }
                    } else {
                        echo "CON Invalid PIN.\n";
                    }
                    break;
                case 2:
                    $select_query = "SELECT * FROM balances WHERE m_id = :user_id";
                    $statement = $conn->prepare($select_query);
                    $statement->bindParam(':user_id', $userId);
                    $statement->execute();
                    if ($statement->rowCount()>0) {
                        $row = $statement->fetch(PDO::FETCH_ASSOC);
                        $savings_amount = $row['balance'];
                        $withdrawal_amount = $textArray[2];
                        if ($withdrawal_amount <= $savings_amount) {
                            $new_savings_amount=$savings_amount-$withdrawal_amount;
                            // Update the user's balance in the balances table
                            $update_balance_query = "UPDATE balances SET balance = balance - :withdrawal_amount WHERE m_id = :user_id";
                            $update_balance_statement = $conn->prepare($update_balance_query);
                            $update_balance_statement->bindParam(':withdrawal_amount', $withdrawal_amount);
                            $update_balance_statement->bindParam(':user_id', $userId);
                            if ($update_balance_statement->execute()) {
                                echo "END Withdrawal successful. Your new savings balance is $new_savings_amount";
                                // Record transaction history
                                $re=$this->recordTransaction($userId, $withdrawal_amount, 'withdrawal', $conn);
                                if($re->rowCount()>0){
                                    echo "successfully to withdrawal";
                                 } else {
                                    echo "END Failed to process withdrawal. Please try again later.";
                                }
                            } else {
                                echo "END Failed to update balance. Please try again later.";
                            }
                               
                        } else {
                            echo "END Insufficient savings balance.";
                        }
                    }else{
                        echo "END No account of saving Found";
                    }   
                    break;
                case 3:
                    echo "END Ivalid option";
                    break;
                case 4:
                    echo "Procees fo show transaction";
                    break;
                case 5:
                    // Add your code for case 5
                    break;
                default:
                    echo "END Invalid option, retry";
            }
        }else{
            echo "END Invalid option";
        }
    }
    
    
    public function SavingHistrory($textArray, $conn, $userId){
        $response = "CON Saving History\n";
        $response .= "1. View Saving History\n";
        $response .= "2. Back\n";
        $response .= "2. Back to Main Menu\n";
        $response .= "3. Exit\n";
        echo $response;
    }

    public function InteresteCalculation($textArray, $phoneNumber, $conn){
        // i want to make this function next time

    } 
    
    //INFORMATION ABOUT LOANS

    public function Loans($textArray, $phoneNumber, $conn,$userId){
        $level = count($textArray);
        if ($level == 1) {
            $response = "CON Loans\n";
            $response .= "1. Request Loan\n";
            $response .= "2. Pay Loan\n";
            $response .= "3. Loan Status\n";
            $response .= Util::$GO_BACK . " Back\n";
            $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
            echo $response;
        } elseif ($level == 2) {
            switch ($textArray[1]) {
                case 1:
                    echo "CON Enter loan amount to request\n";
                    break;
                case 2:
                    echo "CON Enter loan amount to pay\n";
                    break;
                case 3:
                    echo "CON Enter your PIN to check your loan balance:";
                    break;
                default:
                    echo "END Invalid option, retry";
            }

        } elseif ($level == 3) {
            switch ($textArray[1]) {
                case 1:
                    echo "CON Enter your PIN\n";
                    break;
                case 2:
                    echo "CON Enter your PIN\n";
                    break;
                case 3:
                    $pin=$textArray['2'];
                    $select_query = "SELECT * FROM members WHERE MemberId = :userId";
                    $stmt_select = $conn->prepare($select_query);
                    $stmt_select->bindParam(':userId', $userId);
                    $stmt_select->execute();
                    $statement_select = $stmt_select->fetch(PDO::FETCH_ASSOC); // Fetch the result
                    //echo "pin: ".$pin." password: ".$statement_select['password'];
                    if ($pin == $statement_select['password']) {
                        $this->menuLoanStatus($userId, $conn);
                    }else{
                        echo "END Invalid PIN";
                    }
                    break;
                default:
                    echo "END Invalid option, retry";
            }
        } elseif ($level == 4) {
            switch ($textArray[1]) {
                case 1:
                    $loan_amount = $textArray[2];
                    $pin = $textArray[3];
                    $select_query = "SELECT * FROM members WHERE MemberId = :userId";
                    $stmt_select = $conn->prepare($select_query);
                    $stmt_select->bindParam(':userId', $userId);
                    $stmt_select->execute();
                    if ($stmt_select->rowCount()>0) {
                    $statement_select = $stmt_select->fetch(PDO::FETCH_ASSOC); // Fetch the result
                    //echo "pin: ".$pin." password: ".$statement_select['password'];
                    if ($pin == $statement_select['password']) {
                   
                        $interest_rate = 5; // 5% interest rate

                        // Calculate payment deadline date (30 days from the issued date)
                        $issued_date = date("Y-m-d H:i:s");
                        $payment_deadline_date = date('Y-m-d H:i:s', strtotime($issued_date . ' + 30 days'));

                        // Calculate repayment (loan_amount * interest_rate)
                        $repayment = $loan_amount * ($interest_rate / 100);

                        // Prepare and execute the loan request query using prepared statements
                        $insert_query = "INSERT INTO loans (m_id, loan_amount, status, repayment, interest_rate, issued_date, payment_deadline_date) VALUES (:m_id, :loan_amount, 'pending', :repayment, :interest_rate, :issued_date, :payment_deadline_date)";
                        $stmt = $conn->prepare($insert_query);
                        $stmt->bindValue(':m_id', $userId, PDO::PARAM_INT);
                        $stmt->bindValue(':loan_amount', $loan_amount, PDO::PARAM_INT);
                        $stmt->bindValue(':repayment', $repayment, PDO::PARAM_INT);
                        $stmt->bindValue(':interest_rate', $interest_rate, PDO::PARAM_INT);
                        $stmt->bindValue(':issued_date', $issued_date, PDO::PARAM_STR);
                        $stmt->bindValue(':payment_deadline_date', $payment_deadline_date, PDO::PARAM_STR);
                        
                        if ($stmt->execute()) {
                            echo "END Loan request submitted successfully. Please wait for admin approval.";
                        } else {
                            echo "END Failed to submit loan request. Please try again later.";
                        }
                    }else{
                        echo "Invalid PIN";
                    }
                    }else{
                        echo "End No user found with id $userId";
                    }
                    break;
                case 2:
                    $pin = $textArray[3];
                    $loan_amount_pay = $textArray[2];
                    $select_query = "SELECT * FROM members WHERE MemberId = :userId";
                    $stmt_select = $conn->prepare($select_query);
                    $stmt_select->bindParam(':userId', $userId);
                    $stmt_select->execute();
                    $statement_select = $stmt_select->fetch(PDO::FETCH_ASSOC); // Fetch the result
                    //echo "pin: ".$pin." password: ".$statement_select['password'];
                    if ($pin == $statement_select['password']) {
                        $this->menuPayLoan($loan_amount_pay, $userId, $conn);
                    }else{
                        echo "END Invalid PIN";
                    }
                    break;
                case 3:
                    echo "END Invalid option, retry";
                    break;
                default:
                    echo "END Invalid option, retry";
            }
        } 
    }

    public function menuLoanStatus($userId, $conn) {
        // Retrieve loan details for the user
        $select_query = "SELECT * FROM loans WHERE m_id = :user_id";
        $statement = $conn->prepare($select_query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();
        $loan_details = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($loan_details) {
            echo "END Your Loan Status:\n";
            foreach ($loan_details as $loan) {
                $loanid = $loan['LoanId'];
                $statementw = $conn->prepare("SELECT * FROM `loanhistory` WHERE LoanId=:loan_id GROUP BY l_id");
                $statementw->bindParam(':loan_id', $loanid);
                $statementw->execute();
                $totalpayed = 0;
                if ($statementw->rowCount() > 0) {
                    // Fetch all rows from loanhistory for the current loan and calculate total payment
                    $loan_payments = $statementw->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($loan_payments as $payment) {
                        $totalpayed += $payment['amount'];
                    }
                }
                echo "Loan ID: {$loan['LoanId']}\n";
                echo "Loan Amount: {$loan['loan_amount']}\n";
                echo "Issued Date: {$loan['issued_date']}\n";
                echo "Payment Deadline: {$loan['payment_deadline_date']}\n";
                echo "Total Loan Payments: $totalpayed\n";
                echo "Status: {$loan['status']}\n\n";
            }
        } else {
            echo "END No loan details found.";
        }
    }

    public function menuPayLoan($loan_amount_pay, $userId, $conn) {
        // Retrieve loan details for the user
        $select_query = "SELECT * FROM loans WHERE m_id = :user_id AND status = 'approved'";
        $statement = $conn->prepare($select_query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();
        $loan_details = $statement->fetch(PDO::FETCH_ASSOC);


        if ($loan_details) {
            $loanid=$loan_details['LoanId'];
            // Calculate the total amount to be paid (loan amount + interest)
            $total_amount_due = $loan_details['loan_amount'] + $loan_details['repayment'];

            $statementw = $conn->prepare("SELECT * FROM `loanhistory` WHERE LoanId=:loan_id GROUP BY l_id");
            $statementw->bindParam(':loan_id', $loanid);
            $statementw->execute();
            $totalpayed = 0;
            if ($statementw->rowCount() > 0) {
                // Fetch all rows from loanhistory for the current loan and calculate total payment
                $loan_payments = $statementw->fetchAll(PDO::FETCH_ASSOC);
                foreach ($loan_payments as $payment) {
                    $totalpayed += $payment['amount'];
                }
            }

            // Check if the user has sufficient balance to pay off the loan
            $select_balance_query = "SELECT balance FROM balances WHERE m_id = :user_id";
            $stmt_balance = $conn->prepare($select_balance_query);
            $stmt_balance->bindParam(':user_id', $userId);
            $stmt_balance->execute();
            $row_balance = $stmt_balance->fetch(PDO::FETCH_ASSOC);

            if ($row_balance) {
                $balance = $row_balance['balance'];
                if ($balance >= $loan_amount_pay) {


                    // Update loan status to 'paid' and deduct the total amount from the balance
                    $update_loan_query = "UPDATE loans SET status = 'paid' WHERE m_id = :user_id";
                    $update_balance_query = "UPDATE balances SET balance = balance - :amount WHERE m_id = :user_id";
                    $conn->beginTransaction();
                    try {
                        $remainedPay=$total_amount_due-$totalpayed;
                        if ($loan_amount_pay==$remainedPay) {
                            $stmt_update_loan = $conn->prepare($update_loan_query);
                            $stmt_update_loan->bindParam(':user_id', $userId);
                            $stmt_update_loan->execute();
                        }
                        $totalpayed +=$loan_amount_pay;
                        if($totalpayed <= $total_amount_due){
                            $stmt_update_balance = $conn->prepare($update_balance_query);
                            $stmt_update_balance->bindParam(':amount', $loan_amount_pay);
                            $stmt_update_balance->bindParam(':user_id', $userId);
                            $stmt_update_balance->execute();

                            $inser=$conn->prepare("INSERT INTO `loanhistory` (`l_id`, `LoanId`, `amount`, `date`) VALUES (NULL, '$loanid', '$loan_amount_pay', current_timestamp())");
                            $inser->execute();
                            if ($inser->rowCount()) {
                                echo "END Loan paid off successfully.";
                            }else{
                                echo "END Loan paid off Fail.";
                            }
                        }else{
                             echo "END you're try to pay amount ".$loan_amount_pay." that is greater than Available Loan ".$total_amount_due." Please try again";
                        }
                         $conn->commit();
                    } catch (PDOException $e) {
                        $conn->rollBack();
                        echo "END Error: " . $e->getMessage();
                    }
                } else {
                    echo "END Insufficient balance to pay off the loan.";
                }
            } else {
                echo "END Error: Unable to retrieve account balance.";
            }
        } else {
            echo "END You do not have any active loans to pay off.";
        }
    }

    public function penalties($textArray, $phoneNumber, $conn){
        $response = "CON Penalty\n";
        $response .= "1. Pay Penalty\n";
        $response .= "2. Penalty Status\n";
        $response .= "3. Back\n";
        $response .= "4. Exit\n";
       
        
        echo $response;
    }


public function PayPenalty($textArray, $phoneNumber, $conn){}
public function PenaltyStatus($textArray, $phoneNumber, $conn,$userId) {
    try {

            $select_penalty_query = "SELECT * FROM penalties WHERE m_id = :user_id";
            $stmt_penalty = $conn->prepare($select_penalty_query);
            $stmt_penalty->bindParam(':user_id', $userId);
            $stmt_penalty->execute();

            // Check if penalty records were successfully fetched
            if ($stmt_penalty) {
                // Fetch penalty records as an associative array
                $penalties = $stmt_penalty->fetchAll(PDO::FETCH_ASSOC);

                // Output HTML to display penalty status
                echo "<div class='container'>";
                echo "<h2>Penalty Status</h2>";
                echo "<table class='table table-bordered'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Penalty ID</th>";
                echo "<th>Amount</th>";
                echo "<th>Issued Date</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($penalties as $penalty) {
                    echo "<tr>";
                    echo "<td>{$penalty['PenaltyId']}</td>";
                    echo "<td>{$penalty['amount']}</td>";
                    echo "<td>{$penalty['issued_date']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            }else {
                echo "END Failed to retrieve penalty information. Please try again later.";            
        }
    } catch (PDOException $e) {
        echo "END Error retrieving penalty information: " . $e->getMessage();
    }
}
    public function Exit($textArray, $phoneNumber, $conn){

        //exit function
    }

    private function recordTransaction($userId, $amount, $transactionType, $conn) {
        try {
            // Insert transaction record into the transactions table
            $insert_transaction_query = "INSERT INTO transactions (m_id, amount, transaction_type) VALUES (:m_id, :amount, :transaction_type)";
            $stmt_transaction = $conn->prepare($insert_transaction_query);
            $stmt_transaction->bindParam(':m_id', $userId);
            $stmt_transaction->bindParam(':amount', $amount);
            $stmt_transaction->bindParam(':transaction_type', $transactionType);
            $stmt_transaction->execute();
            return $stmt_transaction;
        } catch (PDOException $e) {
            echo "Error recording transaction: " . $e->getMessage();
        }
    }
    
public function middleware($text){
    //remove entries for going back and going to the main menu
    return $this->goBack($this->goToMainMenu($text));
}

public function goBack($text){
    //1*4*5*1*98*2*1234
    $explodedText = explode("*",$text);
    while(array_search(Util::$GO_BACK, $explodedText) != false){
        $firstIndex = array_search(Util::$GO_BACK, $explodedText);
        array_splice($explodedText, $firstIndex-1, 2);
    }
    return join("*", $explodedText);
}

public function goToMainMenu($text){
    //1*4*5*1*99*2*1234*99
    $explodedText = explode("*",$text);
    while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false){
        $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
        $explodedText = array_slice($explodedText, $firstIndex + 1);
    }
    return join("*",$explodedText);
}   
}
?>
