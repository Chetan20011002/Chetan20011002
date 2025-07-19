<?php
include 'config.php';

if ($_POST['action'] == 'add') {
    $stmt = $conn->prepare("INSERT INTO volunteers (resident_id, skills, availability, status) VALUES (?, ?, ?, 'Active')");
    $stmt->bind_param("iss", $_POST['resident_id'], $_POST['skills'], $_POST['availability']);
    $stmt->execute();
    echo "Volunteer added";
}

if ($_POST['action'] == 'delete') {
    $stmt = $conn->prepare("DELETE FROM volunteers WHERE volunteer_id = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    echo "Volunteer deleted";
}
?>
