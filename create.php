<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "printcraftdb";

$connection = new mysqli($servername, $username, $password, $database);

$name = "";
$email = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required";
    } else {

        $sql = "INSERT INTO clients (name, email, phone, address) 
                VALUES ('$name', '$email', '$phone', '$address')";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Client added successfully";

            // clear inputs
            $name = $email = $phone = $address = "";

            // redirect
            header("location: /printcraft-customers/index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <h2>New Client</h2>

    <?php if (!empty($errorMessage)) { ?>
        <div class="alert alert-warning"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <form method="POST">

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

        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-outline-primary" href="/printcraft-customers/index.php">Cancel</a>

    </form>
</div>

</body>
</html>