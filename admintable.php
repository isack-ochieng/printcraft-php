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

echo "Connected successfully to printcraftdb<br>";

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($connection->query($sql) === TRUE) {
    echo "✓ Admins table created successfully<br>";
} else {
    echo "✗ Error creating table: " . $connection->error . "<br>";
}

// Check if admin already exists
$checkSql = "SELECT id FROM admins WHERE username = 'admin'";
$result = $connection->query($checkSql);

if ($result->num_rows == 0) {
    // Insert default admin
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertSql = "INSERT INTO admins (username, password, email) 
                  VALUES ('admin', '$hashedPassword', 'admin@printcraft.com')";
    
    if ($connection->query($insertSql) === TRUE) {
        echo "✓ Default admin created successfully<br>";
        echo "<br><strong>Login Credentials:</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "✗ Error creating admin: " . $connection->error . "<br>";
    }
} else {
    echo "✓ Admin user already exists<br>";
}

echo "<br><a href='/printcraft-customers/login.php' class='btn btn-primary'>Go to Login Page</a>";

$connection->close();
?>