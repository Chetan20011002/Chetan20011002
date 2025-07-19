<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch reports data
$issues_report = $conn->query("
    SELECT 
        issue_type,
        SUM(CASE WHEN status = 'Pending' OR status = 'In Progress' THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        COUNT(*) as total
    FROM issues
    GROUP BY issue_type
");
$chart_labels = [];
$chart_counts = [];

while ($row = $issues_report->fetch_assoc()) {
    $chart_labels[] = $row['issue_type'];    // like 'Maintenance', 'Security', etc.
    $chart_counts[] = $row['total'];         // total number for each type
}
$labels_json = json_encode($chart_labels);
$data_json = json_encode($chart_counts);

$volunteer_report = $conn->query("SELECT COUNT(*) as total FROM volunteers")->fetch_assoc();
$resident_report = $conn->query("SELECT COUNT(*) as total FROM residents")->fetch_assoc();
$active_issues = $conn->query("SELECT COUNT(*) as total FROM issues WHERE status IN ('Pending', 'In Progress')")->fetch_assoc();

// Get monthly statistics
$monthly_issues = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total 
    FROM issues 
    GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
    ORDER BY month DESC 
    LIMIT 6
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - VDM Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
    <style>
        .dashboard-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
        }
    </style>
</head>
<body class="bg-light">
<!-- Navbar remains the same -->
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
                <li class="nav-item"><a class="nav-link active" href="manage_volunteers.php">Volunteer Management</a></li>
                <li class="nav-item"><a class="nav-link" href="problem_reports.php">Problem Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Reports & Analytics</h2>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="exportToPDF()">Export to PDF</button>
            <button class="btn btn-outline-success" onclick="exportToExcel()">Export to Excel</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card text-white bg-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Residents</h6>
                            <h2 class="mt-3 mb-0"><?php echo number_format($resident_report['total']); ?></h2>
                            <p class="small mb-0 mt-2">↑ 12% from last month</p>
                        </div>
                        <div class="stat-icon">👥</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card text-white bg-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Active Volunteers</h6>
                            <h2 class="mt-3 mb-0"><?php echo number_format($volunteer_report['total']); ?></h2>
                            <p class="small mb-0 mt-2">↑ 5% from last month</p>
                        </div>
                        <div class="stat-icon">🤝</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card text-white bg-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Active Issues</h6>
                            <h2 class="mt-3 mb-0"><?php echo number_format($active_issues['total']); ?></h2>
                            <p class="small mb-0 mt-2">↓ 8% from last month</p>
                        </div>
                        <div class="stat-icon">⚠️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card text-white bg-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Resolution Rate</h6>
                            <h2 class="mt-3 mb-0">87%</h2>
                            <p class="small mb-0 mt-2">↑ 3% from last month</p>
                        </div>
                        <div class="stat-icon">📈</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Issues by Type</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="issuesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Trends</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Detailed Issue Reports</h5>
                <div class="input-group w-auto">
                    <input type="text" class="form-control" placeholder="Search issues...">
                    <button class="btn btn-outline-secondary" type="button">Search</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Issue Type</th>
                            <th>Total Reports</th>
                            <th>Active</th>
                            <th>Resolved</th>
                            <th>Avg. Resolution Time</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $issues_report->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2"></span>
                                        <?php echo htmlspecialchars($row['issue_type']); ?>
                                    </div>
                                </td>
                                <td><?php echo number_format($row['total']); ?></td>
                                <td><?php echo $row['active']; ?></td>
<td><?php echo $row['resolved']; ?></td>

                                <td><?php echo rand(24, 72); ?> hours</td>
                                <td>
                                    <span class="badge bg-success">↑ 2.4%</span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Issues Chart
    const issuesCtx = document.getElementById('issuesChart').getContext('2d');
    new Chart(issuesCtx, {
        type: 'doughnut',
        data: {
    labels: <?php echo $labels_json; ?>,
    datasets: [{
        label: 'Issues by Type',
        data: <?php echo $data_json; ?>,
        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#6c757d', '#dc3545', '#0dcaf0']
    }]
},

        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Trends Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Issues',
                data: [65, 59, 80, 81, 56, 55],
                borderColor: '#0d6efd',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});

// Export functions
function exportToPDF() {
    alert('Exporting to PDF...');
    // Implement PDF export functionality
}

function exportToExcel() {
    alert('Exporting to Excel...');
    // Implement Excel export functionality
}
</script>
</body>
</html>

