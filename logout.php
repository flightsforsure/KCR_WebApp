<?php
session_start();
// Destroy current session and redirect to login page
session_destroy();
header('Location: /auth/login.php');
exit;
?>
