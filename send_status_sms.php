<?php
require_once 'twilio-php/src/Twilio/autoload.php'; // Adjusted path
use Twilio\Rest\Client;


// Your Twilio credentials
$sid    = 'ACa3c2cf85e3caf10692f66619bd2f89ce';
$token  = 'dd790886b865bc61f9781d0d9b444af4';
$twilio = new Client($sid, $token);

// Replace with your friend's verified number
$to_number = '+91 90198 87733';  // <- Your friend's verified Indian number
$from_number = '+917411354184'; // E.g., '+1970xxxxxxx'

// Compose your message
$message = "Hello! Your issue (#123) has been resolved successfully. Thank you for using the Village Management System.";

// Send the SMS
try {
    $twilio->messages->create($to_number, [
        'from' => $from_number,
        'body' => $message
    ]);
    echo "✅ SMS sent successfully to $to_number";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
