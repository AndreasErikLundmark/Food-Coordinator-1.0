<?php

//Sets background color of foodcards heartIndicator() in main.js
//Indicates whether current card is in the shoppinglist or not.

session_start();


if (isset($_SESSION['currentCard'], $_SESSION['shoppingList'])) {
  if (in_array($_SESSION['currentCard'], $_SESSION['shoppingList'])) {
    echo "#FCF9FC";
  } else {
    echo "#FEFFF1";
  }
}
