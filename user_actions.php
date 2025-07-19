<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $occupation = $_POST['occupation'];
        $household_size = $_POST['household_size'];
        $password = md5($_POST['password']); // MD5 Encryption

        // Insert user into residents table
        $stmt = $conn->prepare("INSERT INTO residents (name, email, phone, address, occupation, household_size, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $name, $email, $phone, $address, $occupation, $household_size, $password);

        if ($stmt->execute()) {
            echo "User added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];

        // Delete user from residents table
        $stmt = $conn->prepare("DELETE FROM residents WHERE resident_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "User deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
