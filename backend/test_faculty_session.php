<?php
session_start();
echo isset($_SESSION['faculty_id']) ? "Faculty ID: " . $_SESSION['faculty_id'] : "No faculty session set.";
?>
