<?php
session_start();
session_destroy(); // Destroy all active sessions
header("Location: admin_login.php"); // Redirect to login page
exit();
?>
