<?php

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: /printcraft-customers/login.php");
    exit;
}

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "printcraftdb";

    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM clients WHERE id=$id";
    $connection->query($sql);
}

// redirect back
header("location: /printcraft-customers/index.php");
exit;
?>