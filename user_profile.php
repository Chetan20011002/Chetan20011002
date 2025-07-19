<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, phone, address, occupation, household_size FROM residents WHERE resident_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $occupation = $_POST['occupation'];
    $household_size = $_POST['household_size'];
    
    $stmt = $conn->prepare("UPDATE residents SET name = ?, phone = ?, address = ?, occupation = ?, household_size = ? WHERE resident_id = ?");
    $stmt->bind_param("ssssii", $name, $phone, $address, $occupation, $household_size, $user_id);
    $stmt->execute();
    header("Location: user_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VDM User</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="report_issue.php">Report Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="track_issue.php">Track Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="volunteer_register.php">Volunteer Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link active" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Profile Section -->
<div class="container profile-container">
    <h2 class="mb-4 text-center">User Profile</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $user['phone']; ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" name="address" required><?php echo $user['address']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="occupation" class="form-label">Occupation</label>
            <input type="text" class="form-control" name="occupation" value="<?php echo $user['occupation']; ?>">
        </div>
        <div class="mb-3">
            <label for="household_size" class="form-label">Household Size</label>
            <input type="number" class="form-control" name="household_size" value="<?php echo $user['household_size']; ?>" min="1">
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
