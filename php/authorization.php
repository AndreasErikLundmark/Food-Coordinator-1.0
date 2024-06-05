<?php

//https://stackify.com/display-php-errors/
//https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-passwords-using-php/


// ErrorDisplay
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Killing any ongoing session and starting a new one.
session_start();
$_SESSION = []; // empty session array just in case.
session_destroy();
session_start();
// Session variables reset
$_SESSION['cardIndex'] = 0;
$_SESSION['shoppingList'] = [];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'FoodCoordinator'; // Database

// Creating a connection with the Xampp Mysql Server.
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Cannot connect to " . $dbname . " database");
}

$conn->select_db($dbname);
//Fetching html
if (file_exists("../doc/login.html")) {
  $html = file_get_contents("../doc/login.html");
} else {
  die("Error: This file was not found.");
}

$html_pieces = explode("<!--===infoSection===-->", $html);
echo $html_pieces[0];
$tempstring = $html_pieces[1];

//Form fetch
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  //Username is an email. Require login.html
  $current_username = strip_tags($_POST['name']);
  $current_userpassword = strip_tags($_POST['userpassword']);

  //checking database by user
  $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $current_username);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($user_id, $user_name, $user_password);
  //if a user was found, row count == 1;
  if ($stmt->num_rows == 1) {
    $stmt->fetch(); // getting the data

//    Verifying the plain text password to database hash using password_verify()
    $verify = password_verify($current_userpassword, $user_password);
    if ($verify) {
      // Setting user details for the session
      $_SESSION['current_user_name'] = $user_name;
      $_SESSION['current_user_id'] = $user_id;
      ///////////////direct to the page you want the user to go.///////////////////////////////////////
      header("location: ../doc/home.html");
      exit();
    } else {
      $message = "Access denied, try again or create an account";
      $tempstring = str_replace('---info---', $message, $tempstring);
      echo $tempstring;
    }
  } else {
    $message = "Access denied, try again or create an account";
    $tempstring = str_replace('---info---', $message, $tempstring);
    echo $tempstring;
  }
  $stmt->close();
  $conn->close();
}




