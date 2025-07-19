<?php
session_start();
include 'config.php';
$error_message = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 Encryption
    // Check admin credentials
    $stmt = $conn->prepare("SELECT admin_id, name FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: admin_dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        $error_message = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background-color: #1e2a3a;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 22px;
            color: white;
        }
        
        .login-container {
            width: 380px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h3 {
            color: #1e2a3a;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 14px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dfe3e8;
            transition: all 0.3s;
            font-size: 15px;
        }
        
        .form-control:focus {
            border-color: #4b7bec;
            box-shadow: 0 0 0 3px rgba(75, 123, 236, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .btn-primary {
            background-color: #4b7bec;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #3867d6;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #f1f2f6;
            border: none;
            color: #1e2a3a;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #dfe4ea;
        }
        
        .alert {
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 15px;
        }
        
        .home-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .card-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        
        .stat-card {
            width: 23%;
            border-radius: 10px;
            padding: 20px;
            color: white;
            text-align: center;
        }
        
        .stat-card.blue {
            background-color: #4b7bec;
        }
        
        .stat-card.green {
            background-color: #26de81;
        }
        
        .stat-card.orange {
            background-color: #fd9644;
        }
        
        .stat-card.teal {
            background-color: #2bcbba;
        }
        
        .stat-card h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .icon-container {
            margin-bottom: 15px;
            display: inline-block;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">VDM Admin</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="login-container">
        <div class="login-header">
            <h3>Admin Login</h3>
            <p>Enter your credentials to access the Admin portal</p>
        </div>
        
        <?php if ($error_message) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        <!-- Home Page Redirect Button -->
        <div class="home-btn">
            <a href="index.php" class="btn btn-secondary">Go to Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>