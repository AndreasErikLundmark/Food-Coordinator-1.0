<?php

//prints the cards that are hearted by user for display to shoppingList. Also creates list for email
//echoes shoppingList.html

session_start();

if(file_exists("../doc/shoppingList.html")) {
  $html = file_get_contents("../doc/shoppingList.html");
} else {
  die("Error: This file was not found.");
}

//currently not used
$user_name = $_SESSION['current_user_name'];

//temp array to keep track of which ingredients are going to the shoppinglist
$cards_for_print = array();
if(isset($_SESSION['shoppingList'])) {
  foreach ($_SESSION['shoppingList'] as $card) {
    foreach ($card as $ingredient) {
      $added = false;
//This logic is to combine identical ingredient names ant metric. Ketchup 1 piece + Ketchup 1 piece = Ketchup 2 piece.
// It makes the shopping list a little smarter
      foreach ($cards_for_print as &$alreadyAdded) {
        if ($alreadyAdded["ingredient_name"] == $ingredient["ingredient_name"] &&
           $alreadyAdded['ingredient_metric'] == $ingredient['ingredient_metric']){
          $alreadyAdded['quantity'] += $ingredient['quantity'] ;
            $added = true;
            break;
        }
      }
      if(!$added) {
        $cards_for_print[] = $ingredient;
      }
    }
  }
}
$printList = "";

//Sorting the shoppinglist!!!
sort($cards_for_print);
$cardNames ="";
foreach ($_SESSION['shoppingList'] as $card) {
  $card['food_card_name'];
}

//preparing a print str for html display
  foreach ($cards_for_print as $ingredient) {
    $row = $ingredient['ingredient_name'] . " " . $ingredient['quantity'] . $ingredient['ingredient_metric'] . "<br>";
    $printList .= $row;
}

$_SESSION['exportListStr'] = $printList;// for email export see shoppingListMailer.php

$html= str_replace('---user---', $user_name, $html);
$html= str_replace('---items---', $printList, $html);
//$html= str_replace('---exportlink---',$exportList, $html); //this is for email link
echo $html;





