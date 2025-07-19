<?php
// Connect to your database
include 'config.php';

// Read the raw POST body from Twilio
$from = $_POST['From'] ?? null;
$body = $_POST['Body'] ?? null;

// Clean input
$from = mysqli_real_escape_string($conn, $from);
$body = trim($body);

// Check required fields
if (!$from || !$body) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

// Step 1: Parse the body
// Expected format: "Issue: Road Repair, Location: Near Market"
preg_match('/issue\s*:\s*(.*?),\s*location\s*:\s*(.*)/i', $body, $matches);
$issue_type = $matches[1] ?? "General";
$location = $matches[2] ?? "Unknown";

// Step 2: Match phone number to a resident
$query = $conn->prepare("SELECT resident_id FROM residents WHERE phone = ?");
$query->bind_param("s", $from);
$query->execute();
$query->store_result();

if ($query->num_rows === 0) {
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<Response><Message>Your phone number is not registered. Please register first.</Message></Response>";
    exit;
}

$query->bind_result($resident_id);
$query->fetch();

// Step 3: Insert issue into database
$stmt = $conn->prepare("INSERT INTO issues (resident_id, issue_type, description, location, status) VALUES (?, ?, ?, ?, 'Pending')");
$desc = "Reported via SMS";
$stmt->bind_param("isss", $resident_id, $issue_type, $desc, $location);

if ($stmt->execute()) {
    $response = "Issue reported successfully! Type: $issue_type, Location: $location";
} else {
    $response = "Failed to report the issue. Please try again.";
}

// Step 4: Return TwiML response
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response><Message>$response</Message></Response>";
?>
