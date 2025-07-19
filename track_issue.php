<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user issues
$issues = $conn->query("SELECT issue_id, issue_type, description, status, created_at FROM issues WHERE resident_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Issue</title>
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
                <li class="nav-item"><a class="nav-link active" href="track_issue.php">Track Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="volunteer_register.php">Volunteer Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Track Issues Section -->
<div class="container mt-4">
    <h2 class="mb-4">Track Your Reported Issues</h2>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Reported Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $issues->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['issue_id']; ?></td>
                    <td><?php echo $row['issue_type']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <span class="badge <?php echo ($row['status'] == 'Resolved') ? 'bg-success' : (($row['status'] == 'In Progress') ? 'bg-warning' : 'bg-danger'); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
