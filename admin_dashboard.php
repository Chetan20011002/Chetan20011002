<?php
session_start();
include 'config.php';

// Fetch summary counts
$residents_count = $conn->query("SELECT COUNT(*) AS total FROM residents")->fetch_assoc()['total'];
$volunteers_count = $conn->query("SELECT COUNT(*) AS total FROM volunteers")->fetch_assoc()['total'];
$issues_pending = $conn->query("SELECT COUNT(*) AS total FROM issues WHERE status='Pending'")->fetch_assoc()['total'];
$resources_count = $conn->query("SELECT COUNT(*) AS total FROM resources")->fetch_assoc()['total'];

// Fetch issue type breakdown
$issue_types = $conn->query("SELECT issue_type, COUNT(*) as total FROM issues GROUP BY issue_type");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reports & Analytics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f3f4f6;
    }

    .navbar {
      background: linear-gradient(to right, #1f2937, #111827);
    }

    .nav-link {
      position: relative;
      color: #d1d5db !important;
    }

    .nav-link:hover {
      color: #60a5fa !important;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      left: 50%;
      bottom: 0;
      background-color: #60a5fa;
      transition: 0.3s;
    }

    .nav-link:hover::after {
      width: 80%;
      left: 10%;
    }

    .summary-card {
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      color: #fff;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .summary-card:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .summary-card i {
      font-size: 2rem;
    }

    .dashboard-table {
      margin-top: 2rem;
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .dashboard-table table {
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 0.5rem;
      overflow: hidden;
    }

    .dashboard-table thead th {
      background-color: #1f2937;
      color: #fff;
    }

    .dashboard-table tbody tr {
      transition: background-color 0.2s ease;
    }

    .dashboard-table tbody tr:hover {
      background-color: #e5f3ff;
    }

    .dashboard-table tbody td:first-child {
      border-left: 5px solid #60a5fa;
    }

    .dashboard-table td, th {
      padding: 12px 16px;
    }

    .dashboard-table tbody td {
      background-color: #fff;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">VDM Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">User Management</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_volunteers.php">Volunteer Management</a></li>
        <li class="nav-item"><a class="nav-link" href="problem_reports.php">Problem Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
        <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
        <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="summary-card bg-primary">
        <i class="fas fa-users"></i>
        <div>
          <h6 class="mb-1">Total Residents</h6>
          <h4><?php echo $residents_count; ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card bg-success">
        <i class="fas fa-hand-holding-heart"></i>
        <div>
          <h6 class="mb-1">Volunteers</h6>
          <h4><?php echo $volunteers_count; ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card bg-warning text-dark">
        <i class="fas fa-exclamation-circle"></i>
        <div>
          <h6 class="mb-1">Pending Issues</h6>
          <h4><?php echo $issues_pending; ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card bg-info">
        <i class="fas fa-boxes"></i>
        <div>
          <h6 class="mb-1">Resources</h6>
          <h4><?php echo $resources_count; ?></h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Issues Table -->
  <h4 class="mt-5 mb-3">📌 Issues by Type</h4>
  <div class="table-responsive dashboard-table">
    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th>Issue Type</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $issue_types->fetch_assoc()) { ?>
          <tr>
            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
            <td><?php echo $row['total']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
