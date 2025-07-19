<?php
session_start();
session_destroy(); // Destroy all active sessions
header("Location: user_login.php"); // Redirect to login page
exit();
?>
