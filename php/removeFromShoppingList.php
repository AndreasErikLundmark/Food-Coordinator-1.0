<?php

//removes food card from shopping list
session_start();
if(isset($_SESSION['shoppingList'], $_SESSION['currentCard'])) {
//find the index if the card is in the shopping list.
  $key = array_search($_SESSION['currentCard'], $_SESSION['shoppingList']);
  if ($key !== false) {
    unset($_SESSION['shoppingList'][$key]);
  }
}
