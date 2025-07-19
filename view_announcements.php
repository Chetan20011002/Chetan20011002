<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY posted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                <li class="nav-item"><a class="nav-link active" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Announcements Section -->
<div class="container mt-4">
    <h2 class="mb-4">Latest Announcements</h2>
    <div class="list-group">
        <?php while ($row = $announcements->fetch_assoc()) { ?>
            <div class="list-group-item">
                <h5 class="mb-1 text-primary"> <?php echo $row['title']; ?> </h5>
                <p class="mb-1"> <?php echo $row['message']; ?> </p>
                <small class="text-muted">Posted on: <?php echo date("d M Y, h:i A", strtotime($row['posted_at'])); ?></small>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
