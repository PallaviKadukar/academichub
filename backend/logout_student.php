<?php
session_start();
session_destroy();
header("Location: ../studentsignin.html");
exit();
?>
