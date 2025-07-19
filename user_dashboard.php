<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch data for dashboard
$issues_count = $conn->query("SELECT COUNT(*) AS total FROM issues WHERE resident_id = $user_id")->fetch_assoc()['total'];
$announcements = $conn->query("SELECT * FROM announcements ORDER BY posted_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(to right, #2c3e50, #3498db);
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: #fff !important;
        }
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff !important;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .welcome-text {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0;
            font-weight: 600;
        }

        /* Dashboard Card */
        .dashboard-card {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            padding: 2rem;
            border-radius: 15px;
            text-align: left;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .dashboard-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        .dashboard-card p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }
        .dashboard-card i {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            font-size: 4rem;
            opacity: 0.2;
            transform: rotate(-15deg);
            transition: all 0.3s ease;
        }
        .dashboard-card:hover i {
            transform: rotate(0deg) scale(1.1);
            opacity: 0.3;
        }

        /* Calendar Card */
        .calendar-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: all 0.3s ease;
        }
        .calendar-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .calendar-card .card-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.3rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            margin-bottom: 0;
        }
        .calendar-card .card-body {
            padding: 1.5rem;
        }

        /* Announcements Section */
        .announcements-section {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }
        .announcements-section h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        .announcement-item {
            padding: 1rem;
            border-left: 4px solid #4e54c8;
            background: #f8f9fa;
            margin-bottom: 1rem;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
        }
        .announcement-item:hover {
            transform: translateX(5px);
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .announcement-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .announcement-message {
            color: #666;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">VDM User</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="user_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="report_issue.php">Report Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="track_issue.php">Track Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="volunteer_register.php">Volunteer Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="container mt-4">
    <div class="welcome-section">
        <p class="welcome-text">Welcome, <?php echo $user_name; ?>! 👋</p>
    </div>
</div>

<!-- Dashboard Widgets & Calendar -->
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="text-white dashboard-card">
                <h3><?php echo $issues_count; ?></h3>
                <p>Issues Reported</p>
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
        <div class="col-md-8">
            <div class="calendar-card">
                <h5 class="card-title">Calendar</h5>
                <div class="card-body">
                    <iframe src="https://calendar.google.com/calendar/embed?src=en.indian%23holiday%40group.v.calendar.google.com&ctz=Asia%2FKolkata" 
                            style="border: 0" width="100%" height="300" frameborder="0" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcements Section -->
<div class="container">
    <div class="announcements-section">
        <h3>Latest Announcements</h3>
        <?php while ($announcement = $announcements->fetch_assoc()) { ?>
            <div class="announcement-item">
                <h6 class="announcement-title"><?php echo $announcement['title']; ?></h6>
                <p class="announcement-message"><?php echo $announcement['message']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>