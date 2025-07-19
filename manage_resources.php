<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch resources
$resources = $conn->query("SELECT * FROM resources");

// Handle Add Resource
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_resource'])) {
    $resource_name = $_POST['resource_name'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("INSERT INTO resources (resource_name, quantity, unit, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $resource_name, $quantity, $unit, $status);
    $stmt->execute();
    header("Location: manage_resources.php");
    exit();
}

// Handle Delete Resource
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_resource'])) {
    $resource_id = $_POST['resource_id'];
    $stmt = $conn->prepare("DELETE FROM resources WHERE resource_id = ?");
    $stmt->bind_param("i", $resource_id);
    $stmt->execute();
    header("Location: manage_resources.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources</title>
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
                <li class="nav-item"><a class="nav-link active" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Manage Resources Section -->
<div class="container mt-4">
    <h2 class="mb-4">Manage Resources</h2>
    
    <!-- Add Resource Form -->
    <form method="POST" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="resource_name" placeholder="Resource Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="unit" placeholder="Unit" required>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="status" required>
                    <option value="Available">Available</option>
                    <option value="In Use">In Use</option>
                    <option value="Depleted">Depleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="add_resource" class="btn btn-primary w-100">Add Resource</button>
            </div>
        </div>
    </form>

    <!-- Resource Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Resource Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resources->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['resource_id']; ?></td>
                    <td><?php echo $row['resource_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td>
                        <span class="badge <?php echo ($row['status'] == 'Available') ? 'bg-success' : (($row['status'] == 'In Use') ? 'bg-warning' : 'bg-danger'); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="resource_id" value="<?php echo $row['resource_id']; ?>">
                            <button type="submit" name="delete_resource" class="btn btn-danger btn-sm">Delete</button>
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
