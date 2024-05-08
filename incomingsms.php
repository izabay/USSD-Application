<?php
include_once 'menu.php';
include_once '../connection.php';

  

    // Receive data from the gateway
    $phoneNumber = $_POST['from'];
    $text = $_POST['text']; // Format: "name id email pin"
    $text = explode(" ", $text);

    if (count($text) >= 4) {
        $name = $text[0];
        $id = $text[1];
        $email = $text[2];
        $pin = $text[3];

        // Check if any of the fields are empty
        if (empty($name)) {
            echo "END Fill your name";
        } else if (empty($id)) {
            echo "END Enter your National Identity";
        } else if (empty($email)) {
            echo "END Enter your email address";
        } else if (empty($pin)) {
            echo "END Enter your PIN number";
        } else {
            // Check if phone number already exists
            $stmt_check = $conn->prepare("SELECT * FROM members WHERE telephone = :telephone");
            $stmt_check->bindValue(':telephone', $phoneNumber);
            $stmt_check->execute();
            $existingUser = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                echo "END Phone number $phoneNumber already registered";
            } else {
                // Insert new member if not already registered
                $stmt = $conn->prepare("INSERT INTO members (names, national_identity, telephone, email, password) 
                VALUES (:names, :national_identity, :telephone, :email, :password)");
                $stmt->bindValue(':names', $name);
                $stmt->bindValue(':national_identity', $id);
                $stmt->bindValue(':telephone', $phoneNumber);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $pin);

                if ($stmt->execute()) {
                    echo "you heve successfull registerd the following information: \n name is:  $name\n your national id is:  $id \n your phone number is : $phoneNumber \n your id is : $email";
                } else {
                    echo "Failed to insert";
                }
            }
        }
    } else {
        // Check which field is empty and prompt for it
        $missingFields = [];
        if (empty($text[0])) {
            $missingFields[] = "name";
        }
        if (empty($text[1])) {
            $missingFields[] = "national Identity";
        }
        if (empty($text[2])) {
            $missingFields[] = "email";
        }
        if (empty($text[3])) {
            $missingFields[] = "pin";
        }

        if (count($missingFields) > 0) {
            $message = "END Fill your ";
            $message .= implode(", ", $missingFields);
            echo $message;
        } else {
            // If all fields are provided but count is less than 4
            echo "END Please provide all required information";
        }

    
}
?>
