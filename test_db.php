<?php
$conn = new mysqli("localhost", "root", "", "vdm_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connection successful!";
?>
