<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "vdm_system";

$conn = new mysqli("localhost", "root", "", "vdm_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
