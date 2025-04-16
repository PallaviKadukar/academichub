<?php
session_start();
session_destroy();
header("Location: ../facultysignin.html"); // Redirects to faculty login page
exit();
?>
