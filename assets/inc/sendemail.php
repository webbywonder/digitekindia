<?php

// Define some constants
define( "RECIPIENT_NAME", "Digitek India" );
define( "RECIPIENT_EMAIL", "info@digitekindia.in" );

// Verify Captcha
$captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : "";
if(!$captcha){
  echo "<div class='inner error'><p class='error'>Please validate recaptcha.</p></div><!-- /.inner -->";
  return false;
}
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcDuLgaAAAAAPxA5lXIfRd5hDSJ_YY5_MjsrZm5&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
$responseData = json_decode($response); 
if(isset($responseData->success) && $responseData->success=false) {
  echo "<div class='inner error'><p class='error'>Spammer no spamming. Spammer no spamming.</p></div><!-- /.inner -->";
  return false;
}

// Read the form values
$success = false;
$name = isset( $_POST['name'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['name'] ) : "";
$senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['email'] ) : "";
$phone = isset( $_POST['phone'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone'] ) : "";
$services = isset( $_POST['services'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['services'] ) : "";
$subject = isset( $_POST['subject'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['subject'] ) : "";
$address = isset( $_POST['address'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['address'] ) : "";
$website = isset( $_POST['website'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['website'] ) : "";
$message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";

$mail_subject = 'A contact request send by ' . $name . '| Digitek Website';

$body = 'Name: '. $name . "\r\n";
$body .= 'Email: '. $senderEmail . "\r\n";

if ($phone) {$body .= 'Phone: '. $phone . "\r\n"; }
if ($services) {$body .= 'services: '. $services . "\r\n"; }
if ($subject) {$body .= 'Subject: '. $subject . "\r\n"; }
if ($address) {$body .= 'Address: '. $address . "\r\n"; }
if ($website) {$body .= 'Website: '. $website . "\r\n"; }

$body .= 'message: ' . "\r\n" . $message;

// If all values exist, send the email
if ( $name && $senderEmail && $message ) {
  $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
  $headers = "From: " . $name . " <" . $senderEmail . ">";  
  $success = mail( $recipient, $mail_subject, $body, $headers );
  echo "<div class='inner success'><p class='success'>Thanks for contacting us. We will contact you ASAP!</p></div><!-- /.inner -->";
}else {
	echo "<div class='inner error'><p class='error'>Something went wrong. Please try again.</p></div><!-- /.inner -->";
}

?>
