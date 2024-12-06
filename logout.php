<?php
session_start(); // Start the session

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page with a logout success message
header("Location: login.php?logout=success");
exit();
?>
