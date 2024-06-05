<?php

// new account registration. echoes newaccount.html
//https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-passwords-using-php/

session_start();

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
if (file_exists("../doc/newaccount.html")) {
  $html = file_get_contents("../doc/newaccount.html");
} else {
  die("Error: This file was not found.");
}
$html_pieces = explode("<!--===infoSection===-->", $html);

//print the first part of the html page.
echo($html_pieces[0]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $current_username = strip_tags($_POST['name']);
  $current_userpassword = strip_tags($_POST['userpassword']);

  //encrypting password
  $current_hash_password = password_hash($current_userpassword,
    PASSWORD_DEFAULT);

  $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $current_username);

  $stmt->execute();
  $stmt->bind_result($user_name, $user_password, $user_id);
  $stmt->store_result();

  //Replacing html elements with updated values for user information update
  $tempString = $html_pieces[1];

  // if the row count is one, a user name match is found, no account creation made possible
  if ($stmt->num_rows == 1) {

    $message = "Account not available for registration";
    $tempString = str_replace('---info---', $message, $tempString);
    echo($tempString);
    exit();
  } else {

    //store new user to database
    $stmt = $conn->prepare("INSERT INTO users (user_name,user_password) VALUES (?,?)");
    $stmt->bind_param("ss", $current_username, $current_hash_password);

    if (!$stmt->execute()) {
      throw new Exception("Exception loading users table: " . $conn->error);
    } else {
      header("location: ../php/authorization.php");
      exit();
    }
  }
  $stmt->close();
  $conn->close();
}

