
session_start();
var ingredients = [];
let selected = false;

//indexPlus() + nextCard() is handling index increase when user toggles between cards in
// foodCardFetcher.php. If user clicked right button indexCommand == plus
function indexPlus() {
  function increaseIndex() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      nextCard();
    }
    xhttp.open("GET", "../php/foodCardFetcher.php?indexCommand=plus", true);
    xhttp.send();
  }

  function nextCard() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      document.getElementById("Foodcard").innerHTML = this.responseText;
      heartIndicator();
    }
    xhttp.open("GET", "../php/foodCardFetcher.php?showCard", true);
    xhttp.send();
  }

  increaseIndex();
}

//indexMinus() + nextCard() are handling index decrease when user toggles between cards in
// foodCardFetcher.php. If user clicked right button indexCommand == minus
function indexMinus() {
  function decreaseIndex() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      nextCard();
    }
    xhttp.open("GET", "../php/foodCardFetcher.php?indexCommand=minus");
    xhttp.send();
  }

  function nextCard() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      document.getElementById("Foodcard").innerHTML = this.responseText;
      heartIndicator();
    }
    xhttp.open("GET", "../php/foodCardFetcher.php?showCard", true);
    xhttp.send();
  }

  decreaseIndex();
}

//heartIndicator() takes the respondtext from heartIndicator.php and sets FoodCard
// background style to that color value.
function heartIndicator() {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("status").innerHTML = this.responseText;
    document.getElementById("Foodcard").style.backgroundColor = this.responseText;
  }
  xhttp.open("GET", "../php/heartIndicator.php", true);
  xhttp.send();
}

//deleteCard() triggers foodCardDeleter.php which deletes foodcard from database
function deleteCard() {
  if (confirm("Do you want to delete this card completely?")) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      document.getElementById("Foodcard").innerHTML = "Card deleted";
    }
    xhttp.open("GET", "../php/foodCardDeleter.php", true);
    xhttp.send();
  } else {
    return
  }
}

//Triggers php script to add current foodcart to Shopping list + hearIndicator().
function addToShoppingList() {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    heartIndicator();
  }
  xhttp.open("GET", "../php/addToShoppingList.php", true);
  xhttp.send();
}

//removeFromShoppingList() triggers removeFromShoppingList.php and heartIndicator()
function removeFromShoppingList() {
  const xhttp = new XMLHttpRequest
  xhttp.onload = function () {
    heartIndicator();
  }
  xhttp.open("GET", "../php/removeFromShoppingList.php", true);
  xhttp.send();
}




