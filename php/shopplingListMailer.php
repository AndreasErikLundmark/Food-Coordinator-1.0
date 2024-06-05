<?php

//https://github.com/PHPMailer/PHPMailer
//https://github.com/PHPMailer/PHPMailer/blob/master/README.md
//sends the attached shopping list to given email. echoes shoppingListMailer.html

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '../vendor/autoload.php';
$mail = new PHPMailer\PHPMailer\PHPMailer();

if (file_exists("../doc/shoppingListMailer.html")) {
  $html = file_get_contents("../doc/shoppingListMailer.html");
} else {
  die("Error: This file was not found.");
}
$html_pieces = explode("<!--===infoSection===-->", $html);

//print the first part of the html page.
echo($html_pieces[0]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $tempString = $html_pieces[1];

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'xxxxxxx';  // SMTP username
    $mail->Password = 'xxxxxxx';  // SMTP password
    $mail->SMTPSecure = 'ssl';

    $From = "xxxxxxx";
    $To = $_POST['emailto'];
    $Subject = $_POST['subject'];
    $Message = $_POST['message'] . "<br>" . "<br>";

    if (empty($Message)) {
      $Message = "";
    }

    if (empty($Subject)) {
      $Subject = '';
    }

    if (!filter_var($To, FILTER_VALIDATE_EMAIL) || !filter_var($From, FILTER_VALIDATE_EMAIL)) { // checks if To variable is an ok email adress
      echo "Please fill in both from and to field with an email. " . PHP_EOL;
    } else { // Constructing the email here:

//        adding an extra line to the message.
      $Message .= $_SESSION['exportListStr'];

      $mail->setFrom($From, $From);
      $mail->addAddress($To, 'User');
      $mail->isHTML(true);
      $mail->Subject = $Subject;
      $mail->Body = $Message;
      $mail->AltBody = strip_tags($Message);

      $mail->send();
      $message = 'Message successfully sent!';
      $tempString = str_replace('---info---', $message, $tempString);
      echo($tempString);
    }
  } catch
  (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
  }
}
?>
