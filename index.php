<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
header("Location: homepage.html"); // Redirect to homepage.html
exit();
?>
