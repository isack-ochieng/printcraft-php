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
$sql = "SELECT * FROM clients ORDER BY created_at DESC";
$result = $connection->query($sql);
if (!$result) {
    die("Invalid query: " . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printcraft Customer Database</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/printcraft-customers/styles.css">
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>List of Clients</h2>
        <div>
            <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="/printcraft-customers/logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>

    <div class="mb-3">
        <a class="btn btn-primary" href="/printcraft-customers/create.php" role="button">New Client</a>
        <a class="btn btn-success" href="/printcraft-customers/export.php" role="button">📥 Export to CSV</a>
    </div>   
    
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="/printcraft-customers/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a class="btn btn-danger btn-sm" href="/printcraft-customers/delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>