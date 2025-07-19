<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY posted_at DESC");

// Handle Add Announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $admin_id = $_SESSION['admin_id'];
    
    $stmt = $conn->prepare("INSERT INTO announcements (admin_id, title, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $admin_id, $title, $message);
    $stmt->execute();
    header("Location: announcements.php");
    exit();
}

// Handle Delete Announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_announcement'])) {
    $announcement_id = $_POST['announcement_id'];
    $stmt = $conn->prepare("DELETE FROM announcements WHERE announcement_id = ?");
    $stmt->bind_param("i", $announcement_id);
    $stmt->execute();
    header("Location: announcements.php");
    exit();
}
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
        <a class="navbar-brand" href="#">VDM Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">User Management</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_volunteers.php">Volunteer Management</a></li>
                <li class="nav-item"><a class="nav-link" href="problem_reports.php">Problem Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link active" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Announcements Section -->
<div class="container mt-4">
    <h2 class="mb-4">Announcements</h2>
    
    <!-- Add Announcement Form -->
    <form method="POST" class="mb-3">
        <div class="mb-3">
            <input type="text" class="form-control" name="title" placeholder="Title" required>
        </div>
        <div class="mb-3">
            <textarea class="form-control" name="message" placeholder="Message" rows="3" required></textarea>
        </div>
        <button type="submit" name="add_announcement" class="btn btn-primary">Post Announcement</button>
    </form>

    <!-- Announcements Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Message</th>
                <th>Posted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $announcements->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['announcement_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo date("d M Y, h:i A", strtotime($row['posted_at'])); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="announcement_id" value="<?php echo $row['announcement_id']; ?>">
                            <button type="submit" name="delete_announcement" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
