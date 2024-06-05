<?php

// deletes card from database completely
session_start();
echo "hej";
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'FoodCoordinator'; // Database
$card_header = null;
$userID = $_SESSION['current_user_id'];
$current_card = $_SESSION['currentCardName'];
//$userID = 1;
//$current_card = "Test2";

// Creating a connection with the Xampp Mysql Server.
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Cannot connect to " . $dbname . " database");
}
$conn->select_db($dbname);
// Delete the current foodcard that belongs to current user
$sql = "DELETE FROM foodcards WHERE user_id = ? AND foodcard_name = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userID, $current_card);  // Bind the integer 'userID' to the statement
if ($stmt->execute()) {
  echo $current_card . " Deleted";
} else {
  echo "Could not delete";
}
// Delete the ingredients that no longer have a connection to a foodcard.
$sql2 = "DELETE FROM ingredients WHERE ingredient_id NOT IN (SELECT ingredient_id FROM junctionTable)";
$stmt2 = $conn->prepare($sql2);
if ($stmt2->execute()) {
  echo "Unused ingredients also deleted";
} else {
  echo "No ingredients deleted";
}


$stmt->close();
$stmt2->close();
$conn->close();
?>
