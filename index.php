<?php
// Include database connection
include_once '../connection.php'; // Adjust path as necessary
include_once 'menu.php';
// Retrieve POST data
$phoneNumber = $_POST['phoneNumber'];
$serviceCode = $_POST['serviceCode'];
$text = $_POST['text'];
$menu = new Menu($text);
$text = $menu->middleware($text);
$textArray = explode("*", $text);
// Check if the user is registered
function isRegistered($phoneNumber, $conn) {
    try {
        // Prepare and execute query to check if phone number exists
        $stmt = $conn->prepare("SELECT MemberId FROM members WHERE telephone = :telephone");
        $stmt->bindParam(':telephone', $phoneNumber, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result['MemberId'] : null;
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>';
        return false; 
    }
}

// Create an instance of the Menu class

// Get the user ID
$userId = isRegistered($phoneNumber, $conn);
$isRegistered=$userId;
if($text == "" && !$isRegistered){
    //Do something
    $menu -> mainMenuUnregistered();
}else if($text == "" && $isRegistered){
    //Do something
    $menu -> mainMenuRegistered();
 
}else if(!$isRegistered){
    switch($textArray[0]){
        case 1:
            $menu->menuRegister($textArray,$phoneNumber,$conn);
            break;
        default:
            echo "END Invalid option, retry";
    }
}else{
    if ($textArray[0] == "1") { // Note the string comparison
        echo $menu->Savings($textArray, $phoneNumber, $conn, $userId); // Pass phoneNumber here
    } elseif ($textArray[0] == "2") { // Note the string comparison
        echo $menu->Loans($textArray, $phoneNumber, $conn,$userId); // Pass phoneNumber here
    } elseif ($textArray[0] == "3") { // Note the string comparison
        echo $menu->penalties($textArray, $phoneNumber, $conn); // Pass phoneNumber here
    } elseif ($textArray[0] == "4") { // Note the string comparison
        echo $menu->Exit($textArray, $phoneNumber, $conn); // Pass phoneNumber here
    } else {
        echo "END Invalid choice\n";
    }
}
?>
