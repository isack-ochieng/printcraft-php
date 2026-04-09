<?php
session_start();

// If already logged in, go straight to index.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("location: /printcraft-customers/index.php");
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

$errorMessage = "";
$inputUsername = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = trim($_POST["username"]);
    $inputPassword = trim($_POST["password"]);
    
    if (empty($inputUsername) || empty($inputPassword)) {
        $errorMessage = "Please enter both username and password";
    } else {
        // Use prepared statement to be safe
        $stmt = $connection->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($inputPassword, $admin['password'])) {
                // Password is correct
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Redirect to index.php
                header("location: index.php");
                exit;
            } else {
                $errorMessage = "Invalid username or password";
            }
        } else {
            $errorMessage = "Invalid username or password";
        }
        
        $stmt->close();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Craft - Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
        }
        .company-name {
            color: #667eea;
            font-weight: 700;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
        }
        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2><span class="company-name">Print Craft</span></h2>
                        <p class="text-muted">Admin Portal Login</p>
                    </div>
                    
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($inputUsername); ?>" 
                                   placeholder="Enter username"
                                   required
                                   autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   placeholder="Enter password"
                                   required>
                        </div>
                        
                        <button type="submit" class="btn btn-login">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>