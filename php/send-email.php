<?php
header('Content-Type: text/html; charset=UTF-8');
mb_language('uni');
mb_internal_encoding('UTF-8');
$headers .= "MIME-Version: 1.0\r\n";
// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Set the recipient email address
$to = 'info@solareng.eu';

// Set the email subject and message
$subject = 'Nova poruka od: ' . $name;
$message = "Name: $name\nEmail: $email\nMessage: $message";

// Set the headers
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send the email
if (mail($to, $subject, $message, $headers)) {
  // Return a success response
  echo 'success';
} else {
  // Return an error response
  header('HTTP/1.1 500 Internal Server Error');
  echo 'An error occurred while sending the email.';
}
?>
