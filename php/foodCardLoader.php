<?php

//Loading new card to the database including Card, Ingredients, Junction Table.
// inlcudes home.html at bottom script

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'FoodCoordinator'; // Database
$numberOfFormInputs = 11; // this is number of input fields in the addCard.html form
// Creating a connection with the Xampp Mysql Server.

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Cannot connect to " . $dbname . " database");
}
$conn->select_db($dbname);

$ingredients_from_addCard = [];

//checking all Post elements after submit is clicked. Iterating the addCard.html form
for ($i = 1; $i <= $numberOfFormInputs; $i++) {
  // Check if all required fields for this ingredient are set

  // Construct the ingredient array
  $searchId = 'ingredient' . $i;
  $searchQuantity = 'quantity' . $i;
  $searchMetric = 'metric' . $i;
  if (isset($_POST[$searchId], $_POST[$searchQuantity], $_POST[$searchMetric])) {
    $ingredient = [
      "ingredient_name" => strip_tags($_POST[$searchId]),
      "ingredient_quantity" => strip_tags($_POST[$searchQuantity]), // Convert to integer intval strip_tags($_POST[$searchQuantity])
      "ingredient_metric" => strip_tags($_POST[$searchMetric])
    ];
    if ($ingredient['ingredient_name'] != null) { // if the input field was left empty it is not added to the database
      $ingredients_from_addCard[] = $ingredient;
    }
  }
}

//Safe transaction. Loading new card to the database including Card, Ingredients, Junction Table
$conn->autocommit(FALSE);
$conn->begin_transaction();
try {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //checking that there is a file uploaded from html form
    if (isset($_SESSION['current_user_id'])) {
      $user_key = $_SESSION['current_user_id'];
    } else {
      die("User session key is not set.");
    }
//foodCard Table. Adds the card
    $stmt2 = $conn->prepare("INSERT INTO foodCards (user_id, foodcard_name) VALUES (?,?)");
    $foodcard_name = strip_tags($_POST['foodcardname']);
    $stmt2->bind_param("ss", $user_key, $foodcard_name);
    if (!$stmt2->execute()) {
      throw new Exception("Exception loading foodCards table");
    }
    $foodCard_key = $stmt2->insert_id;
    $stmt2->close();


//ingredients Table. Adds ingredients that are currently in $ingredients_from_addCard see upper script
    foreach ($ingredients_from_addCard as $ingredient) {
      $stmt3 = $conn->prepare("INSERT INTO ingredients (ingredient_metric, ingredient_name) VALUES (?,?)");
      $ingredient_name = strtolower($ingredient['ingredient_name']); // Making all letter lower case
      $ingredient_name = ucfirst($ingredient_name); // Making first letter upper case
      $ingredient_quantity = $ingredient['ingredient_quantity'];
      $ingredient_metric = $ingredient['ingredient_metric'];
      $stmt3->bind_param("ss", $ingredient_metric, $ingredient_name);
      if (!$stmt3->execute()) {
        throw new Exception("Exception loading ingredients table");
      }
      $ingredient_key = $stmt3->insert_id;
      $stmt3->close();

// junction Table. Connects ingredient to foodcard
      $stmt4 = $conn->prepare("INSERT INTO junctionTable (foodcard_id, ingredient_id, quantity) VALUES (?,?,?)");
      $foodcard_id = $foodCard_key;
      $ingredient_id = $ingredient_key;
      $quantity = $ingredient_quantity;
      $stmt4->bind_param("iii", $foodcard_id, $ingredient_id, $quantity);
      if (!$stmt4->execute()) {
        throw new Exception("Exception loading ingredients table");
      }
      $stmt4->close();
    }
  }

  $conn->commit();

} catch
(Exception $e) {
  $conn->rollback();
  echo "Transaction failed: " . $e->getMessage();
}
//$html = file_get_contents("../doc/home.html");
//echo $html;
include '../doc/home.html';

$conn->close();
