<?php
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

echo "<h2>Admin Account Check</h2>";

// Check if admin exists
$sql = "SELECT * FROM admins WHERE username = 'admin'";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "Admin user exists<br>";
    echo "Username: " . $admin['username'] . "<br>";
    echo "Current password hash: " . $admin['password'] . "<br><br>";
    
    // Update the password to a known working one
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $updateSql = "UPDATE admins SET password = '$newHash' WHERE username = 'admin'";
    if ($connection->query($updateSql) === TRUE) {
        echo "✓ Password has been reset successfully!<br>";
        echo "New hash: " . $newHash . "<br>";
        echo "<br><strong>You can now login with:</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Error updating password: " . $connection->error;
    }
} else {
    echo "Admin user not found. Creating new admin...<br>";
    
    // Create new admin
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $insertSql = "INSERT INTO admins (username, password, email) VALUES ('admin', '$newHash', 'admin@printcraft.com')";
    if ($connection->query($insertSql) === TRUE) {
        echo "✓ Admin created successfully!<br>";
        echo "<br><strong>Login with:</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Error creating admin: " . $connection->error;
    }
}

echo "<br><br><a href='/printcraft-customers/login.php' class='btn btn-primary'>Go to Login Page</a>";

$connection->close();
?>