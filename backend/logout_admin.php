<?php
session_start();
session_destroy();
header("Location: ../admin_login.html"); // Redirects to faculty login page
exit();
?>
