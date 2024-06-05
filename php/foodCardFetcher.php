<?php

//foodCardFetcher is fetching the foodcards from database and displaying the content in home.html.

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'FoodCoordinator'; // Database
$card_header = null;

//all the foodcards as a subarray with ingredients basically
$foodcards_fetched = [];
//Storing the names of each foodcard as key in separate array
$foodcards_names = [];

// Creating a connection with the Xampp Mysql Server.
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



//UPDATE THE CONNECTION TO POINT TO YOUR DB

$conn->select_db($dbname);

$userID = $_SESSION['current_user_id'];

//GATHERING ALL THE FOODCARDS AND INGREDIENTS ASSOCIATED WITH A SPECIFIC USER;
// fetching foodcard_name from foodcards, ingredient_name from ingredients
//, quantity from junction table. User is in focus here = FROM users u.
//Connect Foodcards with user: JOIN foodCards fc ON u.user_id = fc.user_id
//Connect junctiontable with foodcard with all findings on foodcard_id: LEFT JOIN junctionTable jt ON fc.foodcard_id = jt.foodcard_id
//Connect ingredients table with junctiontable.. all ingredience where ingredient_id exist:LEFT JOIN ingredients i ON jt.ingredient_id = i.ingredient_id
//Do this depending on user: WHERE u.user_id = ?";
$sql = "SELECT fc.foodcard_name, fc.foodcard_id, i.ingredient_name, i.ingredient_metric, jt.quantity
        FROM users u
        JOIN foodCards fc ON u.user_id = fc.user_id
        LEFT JOIN junctionTable jt ON fc.foodcard_id = jt.foodcard_id
        LEFT JOIN ingredients i ON jt.ingredient_id = i.ingredient_id
        WHERE u.user_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID,);  // Bind the integer 'userID' to the statement
$stmt->execute();
//workaround to get table size
$stmt->store_result();

//if there is data fetched...>0
if ($stmt->num_rows > 0) {
  // bind_result separate data from datatable
  $stmt->bind_result($foodcard_name, $foodcard_id, $ingredient_name, $ingredient_metric, $quantity);

  $name = $foodcard_name;
  //iterating over the sql content
  while ($stmt->fetch()) {

    if (!isset($foodcards_fetched[$foodcard_name])) {
      // If the food card doesn't exist, create a new entry for it
      $foodcards_fetched[$foodcard_name] = [];
      if (!isset($foodcards_names[$foodcard_name])) {
        $foodcards_names[] = $foodcard_name;
      }
    }

    $foodcards_fetched[$foodcard_name][] = [
      "ingredient_name" => $ingredient_name,
      "quantity" => $quantity,
      "ingredient_metric" => $ingredient_metric
    ];

  }
} else {
//  echo "No data was fetched from " . $dbname . "<br>";
}

$stmt->close();
$conn->close();

function showCard(&$foodcards_fetched, &$foodcards_names)
{

  $ingredientsPrint = "";
  $deckSize = count($foodcards_names);

  // IndexLogic. see also js file indexPlus(). It is determining whether index should be "plus" or "minus" indexMinus()
  // and indexPlus()
  if (isset($_GET['indexCommand'])) {
    $currentIndex = $_SESSION['cardIndex'];
    if ($_GET['indexCommand'] == 'plus') {
      if ($currentIndex + 1 < $deckSize) {
        $_SESSION['cardIndex']++;
      } else {
        $_SESSION['cardIndex'] = 0;
      }
    }
    if ($_GET['indexCommand'] == 'minus') {
      if ($currentIndex > 0) {
        $_SESSION['cardIndex']--;
      } else {
        $_SESSION['cardIndex'] = $deckSize - 1;
      }
    }
  }
  $index = $_SESSION['cardIndex'];

  $next_card = [];
  if ($foodcards_fetched == null) {
    echo "<h3><strong></strong> You have no food cards<br /></h3>";
    echo "<p><strong></strong>Please <a href='../doc/addCard.html'>add a new card!<br /></p>";
  } else {
    //this gets the next card name by index for foodcards_names.. next_card_name is used as key in next step
    $next_card_name = $foodcards_names[$index];
    $_SESSION['currentCardName'] = $next_card_name;
    //getting nextcard in foodcards_fetched by using next_card_name as key.
    $next_card = $foodcards_fetched[$next_card_name];
    //each foodcard is basically an array
    foreach ($next_card as $ingredient) {
      $dots = " ..";
      $row = $ingredient['ingredient_name'] . $dots . $ingredient['quantity'] . $ingredient['ingredient_metric'] . "<br>";
      $ingredientsPrint .= $row;
    }

    echo "<h3><strong></strong> $next_card_name<br /></h3>";

    echo "<p><strong></strong>$ingredientsPrint<br /></p>";
    $_SESSION['currentCard'] = $next_card;
  }
}

showCard($foodcards_fetched, $foodcards_names);

?>




