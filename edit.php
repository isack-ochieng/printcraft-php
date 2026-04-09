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

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$email = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

// ✅ GET: Load existing data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"])) {
        header("location: /printcraft-customers/index.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM clients WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /printcraft-customers/index.php");
        exit;
    }

    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
    $address = $row["address"];
}

// ✅ POST: Update data
else {

    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required";
    } else {

        $sql = "UPDATE clients 
                SET name='$name', email='$email', phone='$phone', address='$address' 
                WHERE id=$id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Client updated successfully";
            header("location: /printcraft-customers/index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container my-5">
    <h2>Edit Client</h2>

    <?php if (!empty($errorMessage)) { ?>
        <div class="alert alert-warning"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <form method="POST">

        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a class="btn btn-outline-primary" href="/printcraft-customers/index.php">Cancel</a>

    </form>
</div>

</body>
</html>