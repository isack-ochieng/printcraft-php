<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: /printcraft-customers/login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "printcraftdb";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch all clients
$sql = "SELECT id, name, email, phone, address, created_at FROM clients ORDER BY created_at DESC";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}

// Set headers for CSV download
$filename = "printcraft_clients_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel handling of special characters
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add column headers
fputcsv($output, array('ID', 'Name', 'Email', 'Phone', 'Address', 'Created At'));

// Add data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, array(
        $row['id'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['address'],
        $row['created_at']
    ));
}

fclose($output);
$connection->close();
exit;
?>