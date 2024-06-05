<?php

session_start();

if (isset($_SESSION['currentCard'])) {

  if (in_array($_SESSION['currentCard'], $_SESSION['shoppingList'])) {
    echo "Already in shopping list!";
    return;
  } else {
    array_push($_SESSION['shoppingList'], $_SESSION['currentCard']);

  }
}
?>
