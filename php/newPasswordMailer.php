<?php

//https://github.com/PHPMailer/PHPMailer
//https://github.com/PHPMailer/PHPMailer/blob/master/README.md
// Sends a new password to given email. echoes sendPassword.html

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
$mail = new PHPMailer\PHPMailer\PHPMailer();

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


// custom password generator: https://www.php.net/manual/en/function.rand.php
  function newPassWord($length)
  {
    $str = random_bytes($length);
    $str = base64_encode($str);
    return strval($str);
  }

  $newPlainPassword = newPassWord(20);

  //encrypting password
  $current_hash_password = password_hash($newPlainPassword,
  PASSWORD_DEFAULT);




//Fetching html
  if (file_exists("../doc/sendPassword.html")) {
    $html = file_get_contents("../doc/sendPassword.html");
  } else {
    die("Error: This file was not found.");
  }
  $html_pieces = explode("<!--===infoSection===-->", $html);

//print the first part of the html page.
  echo($html_pieces[0]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $current_username = strip_tags($_POST['emailto']);
  $tempString = $html_pieces[1];

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'xxxxxxx';  // SMTP username
    $mail->Password = 'xxxxxxx';  // SMTP password
    $mail->SMTPSecure = 'ssl';

    $From = "xxxxxxxxxxx";
    $To = $current_username;
    $Subject = 'Food Coordinator New Password';
    $Message = 'This is your temporary password: ' . '"' . $newPlainPassword . '"' . '. Please copy and follow the link to create a new password. ' .
      '<a href="http://localhost/andreasphptest/Matkoordineraren/php/newPassword.php">Food Coordinator: New password</a>';


    $mail->setFrom($From, $From);
    $mail->addAddress($To, 'User');
    $mail->isHTML(true);
    $mail->Subject = $Subject;
    $mail->Body = $Message;
    $mail->AltBody = strip_tags($Message);



    $stmt = $conn->prepare("UPDATE users SET user_password =? WHERE user_name =?");
    $stmt->bind_param("ss",$current_hash_password, $current_username);

    if (!$stmt->execute()) {
//      throw new Exception("Exception loading users table: " . $conn->error);

    }elseif ($stmt->affected_rows===0){
      $message = "No user such user was found!";
      $tempString = str_replace('---info---', $message, $tempString);
      echo($tempString);
      exit();
    }else{
      $mail->send();
      $message = 'Message successfully sent!';
      $tempString = str_replace('---info---', $message, $tempString);
      echo($tempString);
    }

  } catch
  (Exception $e) {
    $message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    $tempString = str_replace('---info---', $message, $tempString);
    echo($tempString);
    exit();
  }
}
?>
