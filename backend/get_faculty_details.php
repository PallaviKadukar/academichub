<?php
session_start();

if (isset($_SESSION["faculty_name"])) {
    echo json_encode(["faculty_name" => $_SESSION["faculty_name"]]);
} else {
    echo json_encode(["error" => "Not logged in"]);
}
?>
